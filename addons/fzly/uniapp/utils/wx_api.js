// 服务的地址、 图片拼接地址
import config from '@/config.js'
// 网络请求http配置文件
import request from '@/utils/request.js'
// #ifdef H5 
var fzweixin = require('jweixin-module')
// #endif
import { mpJsdk } from "@/api/index";

export default {
	/**
	 * @路由调转
	 */
	routerTo(url, type = 'navigateTo') {
		uni[type]({
			url
		})
	},
	/**
	 * @路由返回
	 */
	routerBack(delta = 1) {
		let page = getCurrentPages()
		if (page.length > 1) {
			uni.navigateBack({
				delta
			})
		} else {
			uni.reLaunch({
				url: '/pages/index/index'
			})
		}

	},
	routerGo(url, type = 'navigateTo') {
		let token = uni.getStorageSync('token')
		if (token) {
			uni[type]({
				url
			})
		} else {
			uni.navigateTo({
				url: '/pages/login/login'
			})
		}
	},
	/**
	 * @toast弹框
	 */

	setNavTitle(title) {
		uni.setNavigationBarTitle({
			title
		})
	},
	// 
	setClipboardData(data) {
		uni.setClipboardData({
			data: data,
			showToast: true,
			success: function() {
				uni.showToast({
					title: '复制成功，请粘贴到手机浏览器打开即可完成下载！',
					icon: 'none'
				})
			},
			fail() {
				uni.showToast({
					title: '复制失败',
					icon: 'none'
				})
			}
		});
	},
	openloadFile(url) {
		let token = uni.getStorageSync('token')
		if (!token) {
			uni.navigateTo({
				url: '/pages/login/login'
			})
		} else {
			uni.downloadFile({
				url: url, //文件的下载路径
				success(res) {
					//保存到本地
					uni.saveFile({
						tempFilePath: res.tempFilePath, //文件的临时路径
						success: function(res) {
							const savedFilePath = res.savedFilePath;
							// 打开文件
							uni.openDocument({
								filePath: savedFilePath,
								success: function(res) {
									uni.hideLoading()
								},
								fail: function(res) {},
								complete: function(res) {
									setTimeout(() => {
										uni.hideLoading()
									}, 4000)
								},
							});
						},
						fail: function(err) {}
					});
				},
				fail(res) {}
			})

		}
	},
	// 预览
	downloadFile(url) {
		let token = uni.getStorageSync('token')
		if (!token) {
			uni.navigateTo({
				url: '/pages/login/login'
			})
		} else {
			uni.downloadFile({
				url: url, //文件的下载路径
				success(res) {
					//保存到本地
					uni.saveFile({
						tempFilePath: res.tempFilePath, //文件的临时路径
						success: function(res) {
							const savedFilePath = res.savedFilePath;
							// 打开文件
							uni.openDocument({
								filePath: savedFilePath,
								success: function(res) {
									uni.hideLoading()
								},
								fail: function(res) {},
								complete: function(res) {
									setTimeout(() => {
										uni.hideLoading()
									}, 4000)
								},
							});
						},
						fail: function(err) {}
					});
				},
				fail(res) {}
			})

		}
	},
	// 保存图片到相册
	saveImage(filePath) {
		uni.downloadFile({
			url: filePath, //图片的地址
			success: function(res) {
				const tempFilePath = res.tempFilePath //通过res中的tempFilePath 得到需要下载的图片地址
				uni.saveImageToPhotosAlbum({
					filePath: tempFilePath, //设置下载图片的地址
					success: function() {
						uni.showToast({
							title: "保存成功",
							duration: 2000
						})
					},
					fail: (err) => {
						uni.showToast({
							title: "保存失败",
							duration: 2000,
							icon: 'none'
						})
						if (err.errMsg !== 'saveImageToPhotosAlbum:fail auth deny') return
						uni.showModal({
							title: '提示',
							content: '需要您授权保存相册',
							showCancel: false,
							success(res) {
								if (res.confirm) {
									uni.openSetting({
										success(settingdata) {
											if (settingdata.authSetting[
													'scope.writePhotosAlbum']) {
												uni.showModal({
													title: '提示',
													content: '获取权限成功,再次保存图片即可成功',
													showCancel: false,
												})
											} else {
												uni.showModal({
													title: '提示',
													content: '获取权限失败，无法保存到相册',
													showCancel: false
												})
											}
										}
									})
								}
							}
						})
					}

				})
			}
		})

	},
	/**
	 * @米转换为公里
	 */
	mkm(x) {
		let nub = Math.round((x / 100) / 10);
		return nub.toFixed(2)
	},
	/**
	 * @数字精度
	 */
	BumberPrecision(x) {
		return Number(x).toFixed(2)
	},
	/**
	 * @微信获取用户信息
	 */
	getWxUserInfo() {
		return new Promise((reslove, reject) => {
			uni.getUserInfo({
				success(data) {
					reslove(data)
				},
				fail(err) {
					reject(err)
				}
			})
		})
	},
	/**
	 * @微信支付
	 * @orderInfo 参数对象格式
	 * @支付成功跳转页面url
	 */
	wxparApi(orderInfo, url, map, navidx) {
		let that = this
		uni.requestPayment({
			timeStamp: orderInfo.timeStamp,
			nonceStr: orderInfo.nonceStr,
			package: orderInfo.package,
			signType: orderInfo.signType,
			paySign: orderInfo.paySign,
			success(res) {
				if (url) {
					that.subscription(map, url, navidx)
				} else {
					uni.showToast({
						title: '支付成功',
						icon: 'none',
						duration: 2000,
					})
					// uni.reLaunch({
					// 	url: '、pages/o'
					// })
				}
			},
			fail(res) {
				uni.showToast({
					title: '支付失败',
					icon: 'error',
					duration: 2000,
					success() {
						setTimeout(res => {
							if (navidx == 2 || navidx == 1) return
							uni.reLaunch({
								url: url
							})
						}, 1500)
					}
				})
			}
		})
	},

	/**
	 * @微信公众号支付
	 * @orderInfo 参数对象格式
	 * @支付成功跳转页面url
	 */
	mpwxparApi(orderInfo, url, map, navidx) {
		let that = this
		fzweixin.config({
			debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
			appId: orderInfo.appId, // 必填，公众号的唯一标识
			timestamp: orderInfo.timeStamp, // 必填，生成签名的时间戳
			nonceStr: orderInfo.nonceStr, // 必填，生成签名的随机串
			signature: orderInfo.paySign, // 必填，签名
			jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表
		})
		fzweixin.ready(function() {
			fzweixin.chooseWXPay({
				timestamp: orderInfo.timeStamp, // 时间戳
				nonceStr: orderInfo.nonceStr, // 随机数
				package: orderInfo.package, //
				signType: orderInfo.signType,
				paySign: orderInfo.paySign, // 签名
				success: function() {
					uni.showToast({ title: '支付成功' });
					setTimeout(() => {
						uni.reLaunch({
							url: url
						})
					}, 2000);
				},
				cancel: function() {
					uni.showToast({ title: '取消支付', icon: 'none' });
					setTimeout(() => {
						uni.reLaunch({
							url: url
						})
					}, 2000);
				},
				fail: function() {
					uni.showToast({ title: '支付失败', icon: 'none' });
					setTimeout(() => {
						uni.reLaunch({
							url: url
						})
					}, 2000);
				}
			})
		})


	},

	async mpweixinShareInfo(urls) {
		var that = this;
		var data = {
			urls: urls
		}
		//获取到微信分享相关配置
		await mpJsdk(data).then(res => {
			if (res.data && res.data.signData) {
				console.log('res.data.signData:', res.data.signData);
				fzweixin.config({
					debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					appId: res.data.signData.appId, // 必填，公众号的唯一标识
					timestamp: res.data.signData.timestamp, // 必填，生成签名的时间戳
					nonceStr: res.data.signData.nonceStr, // 必填，生成签名的随机串
					signature: res.data.signData.signature, // 必填，签名
					jsApiList: ["onMenuShareTimeline", "onMenuShareQQ", "onMenuShareAppMessage",
						"updateAppMessageShareData", "updateTimelineShareData"
					] // 必填，需要使用的JS接口列表
				});
				fzweixin.ready(function() {
					console.log(999999999998888888888);
					fzweixin.checkJsApi({
						jsApiList: ['updateAppMessageShareData',
							"updateTimelineShareData"
						], // 需要检测的JS接口列表，所有JS接口列表见附录2,
						success: function(res2) {
							// 以键值对的形式返回，可用的api值true，不可用为false
							// 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
							// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。

							fzweixin.updateAppMessageShareData({
								title: uni.getStorageSync('public')
									.fz_h5_title, // 分享标题
								desc: uni.getStorageSync('public')
									.fz_h5_intro, // 分享描述
								link: uni.getStorageSync('public')
									.fz_h5_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
								imgUrl: uni.getStorageSync('public')
									.fz_h5_fximg, // 分享图标
								success: function() {
									// 设置成功
									console.log('===分享===');
								}
							})

							fzweixin.updateTimelineShareData({
								title: uni.getStorageSync('public')
									.fz_h5_title, // 分享标题
								desc: uni.getStorageSync('public')
									.fz_h5_intro, // 分享描述
								link: uni.getStorageSync('public')
									.fz_h5_url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
								imgUrl: uni.getStorageSync('public')
									.fz_h5_fximg, // 分享图标
								success: function() {
									// 设置成功
									console.log('===分享===');
								}
							})
						},
						fail: function(err) {
							console.log('checkJsApi:', err);
						}
					});

				})
				fzweixin.error(function(err) {
					console.log('errxx:', err);
					// config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。

				})

			}

		}).catch(err => {
			console.log('err:', err);
		})

	},

	/**
	 * @微信获取code
	 */
	loginApi() {
		return new Promise((reslove, reject) => {
			uni.login({
				success(res) {
					reslove(res)
				},
				fail(err) {
					reject(err)
				}
			})

		})
	},
	/**
	 * @选择图片或拍照
	 */
	chooseImage(count = 1) {
		return new Promise((reslove, reject) => {
			uni.chooseImage({
				count,
				success(data) {
					reslove(data)
				},
				fail(err) {
					reject(err)
				}
			})

		})
	},
	/**
	 * @上传图片
	 */
	uploadImg(url) {
		let that = this
		return new Promise(async (relove, reject) => {
			let tempFilePaths // 储存图片信息
			// 是否传入图片地址
			if (!url) {
				await that.chooseImage().then(d => {
					tempFilePaths = d.tempFilePaths[0]
				}).catch(err => {
					tempFilePaths = err
				})
			} else {
				tempFilePaths = url
			}
			// 开始上传
			uni.uploadFile({
				url: config.serverIp + '/api/common/upload',
				fileType: 'image',
				filePath: tempFilePaths,
				name: 'file',
				header: {
					token: uni.getStorageSync('token')
				},
				success: res => {
					let result = JSON.parse(res.data)
					relove(result.data)
				},
				fail(err) {
					reject(err)
				}
			})

		})
	},
	/**
	 * @扫码
	 */
	scanCode() {
		return new Promise((reslove, reject) => {
			uni.scanCode({
				success: (data) => {
					reslove(data)
					// this.rou
				},
				fail(err) {
					reject(err)
				}
			})
		})
	},
	/**
	 * @打电话
	 */
	openTel(phoneNumber) {
		uni.makePhoneCall({
			phoneNumber
		});
	},
	/**
	 * @设置系统剪贴板的内容
	 * @String 参数
	 */
	/**
	 * @对象转get参数
	 */
	getParams: obj => {
		let str = '?'
		for (let s in obj) {
			str += `${s}=${obj[s]}&`
		}
		return str.substr(0, str.length - 1)
	},
	/**
	 * @展示图片
	 */
	viewImage(e, urls) {
		urls = urls.map(obj => {
			return obj = uni.getStorageSync('loginInfo').imgUrl + '/' + obj
		})
		console.log(urls)
		uni.previewImage({
			urls,
			current: e.currentTarget.dataset.url
		});
	},
	/**
	 * @获取登录数据
	 */
	getLoginInfo() {
		let that = this
		return new Promise(function(relove, reject) {
			that.getLoginCode().then(code => {
				authentication({
					code
				}).then(d => {
					uni.setStorageSync('loginInfo', d.data)
					relove(d)
				}).catch(reject)
			})
		})
	},
	/**
	 * @获取两个经纬度的距离
	 */
	getDistance(lat1, lng1, lat2, lng2) {
		var radLat1 = lat1 * Math.PI / 180.0;
		var radLat2 = lat2 * Math.PI / 180.0;
		var a = radLat1 - radLat2;
		var b = lng1 * Math.PI / 180.0 - lng2 * Math.PI / 180.0;
		var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) +
			Math.cos(radLat1) * Math.cos(radLat2) * Math.pow(Math.sin(b / 2), 2)));
		s = s * 6378.137; // EARTH_RADIUS;
		s = Math.round(s * 10000) / 10;
		return s; // m
	},
	/**
	 * @配置授权位置
	 */
	getLocation() {
		return new Promise((relove, reject) => {
			// 授权
			uni.getLocation({
				success: data => {
					relove(data)
				},
				fail(err) {
					uni.getSetting({
						success: (res) => {
							var statu = res.authSetting;
							if (!statu['scope.userLocation']) {
								uni.showModal({
									title: '是否授权当前位置',
									content: '需要获取您的地理位置，请确认授权，否则地图功能将无法使用',
									success(tip) {
										if (tip.confirm) {
											uni.openSetting({
												success: data => {
													console.log('授权成功')
													if (data
														.authSetting[
															"scope.userLocation"
														] === true
													) {
														uni.showToast({
															title: '授权成功',
															icon: 'success',
															duration: 1000
														})
														//授权成功之后，再调用chooseLocation选择地方
														setTimeout(
															function() {
																uni.getLocation({
																	success: data => {
																		relove
																			(
																				data
																			)
																	},
																	fail(
																		err
																	) {
																		reject
																			(
																				err
																			)
																	}
																})

															}, 1000)
													} else {
														reject()
													}
												},
												fail() {
													console.log(
														'openSetting fail'
													)
												}
											})
										} else {
											uni.showToast({
												title: '授权失败',
												icon: 'none',
												duration: 1000
											})
											reject()
										}
									}
								})

							} else {
								uni.getLocation({
									success: data => {
										relove(data)
									},
									fail(err) {
										reject(err)
									}
								})
							}
						}
					})
				}
			})
		})
	},
	/**
	 * @uni方式拿到位置
	 */
	uniGetLocation() {
		return new Promise((relove, reject) => {
			uni.getLocation({
				success: d => {
					relove(d)
				},
				fail: d => {
					reject(d)
				}
			})

		})
	},
	/**
	 * @微信小程序模糊定位位置
	 */
	wxgetFuzzyLocation() {
		return new Promise((relove, reject) => {
			uni.getFuzzyLocation({
				type: 'wgs84',
				success: d => {
					relove(d)
				},
				fail: d => {
					uni.showToast({
						title: '未授权位置请在小程序设置开启',
						icon: 'none'
					})
					reject(d)
				}
			})
		})
	},
	/**
	 * @打开地图手动选择位置
	 */
	getchooseLocation() {
		return new Promise((relove, reject) => {
			uni.chooseLocation({
				success: function(d) {
					relove(d)
				},
				fail: function(d) {
					reject(d)
				}
			});
		})
	},
	/**
	 * @使用应用内置地图查看位置
	 */
	getopenLocation(latitude, longitude) {
		return new Promise((relove, reject) => {
			uni.openLocation({
				latitude: Number(latitude),
				longitude: Number(longitude),
				success: function(d) {
					relove(d)
				},
				fail: function(d) {
					reject(d)
				}
			});
		})
	},

	/**
	 * @获取url参数
	 */
	get_url(url, name) {
		var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
		var trueUrl1 = url.split('?')[2];
		var trueUrl2 = trueUrl1.match(/(\S*)#/)[1];
		//console.log(trueUrl2)
		var r = trueUrl2.match(reg);
		if (r != null) {
			return unescape(r[2]);
		}
		return null;
	},
	/**
	 * @获取缓存数据
	 */
	getStorage(key) {
		return uni.getStorageSync(key) || ''
	},
	/**
	 * @微信消息订阅 tmp为数组,url为支订阅完成跳转的页面
	 */
	subscription(tmp, url, navidx) {
		uni.getSetting({
			withSubscriptions: true,
			success(res) {
				var itemSettings = res.subscriptionsSetting.itemSettings;
				if (itemSettings) {
					// 页面重载
					const pages = getCurrentPages()
					// 声明一个pages使用getCurrentPages方法
					const curPage = pages[pages.length - 1]
					// 声明一个当前页面
					curPage.onLoad(curPage.options) // 传入参数
					curPage.onShow()
					curPage.onReady()
					uni.showToast({
						title: '支付成功',
						icon: 'success',
						duration: 2000,
						success(rse) {
							if (url) {
								setTimeout(res => {
									if (navidx == 1) {
										uni.navigateBack()
									} else if (navidx == 2) {

									} else {
										uni.reLaunch({
											url
										})
									}
								}, 2000)
							}

						}
					})
				} else {
					console.log('tmp', tmp);
					uni.getSetting({
						withSubscriptions: true,
						success(res) {
							uni.requestSubscribeMessage({
								tmplIds: tmp,
								success(res) {
									// 页面重载
									const pages = getCurrentPages()
									// 声明一个pages使用getCurrentPages方法
									const curPage = pages[pages.length - 1]
									// 声明一个当前页面
									curPage.onLoad(curPage.options) // 传入参数
									curPage.onShow()
									curPage.onReady()
									uni.showToast({
										title: '订阅成功',
										icon: 'success',
										duration: 2000,
										success(res) {
											console.log('订阅成功');
											if (url) {
												setTimeout(res => {
													if (navidx == 1) {
														uni.navigateBack()
													} else if (navidx == 2) {

													} else {
														uni.reLaunch({
															url
														})
													}
												}, 2000)
											}

										}
									})
								},
								fail(res) {
									// 页面重载
									const pages = getCurrentPages()
									// 声明一个pages使用getCurrentPages方法
									const curPage = pages[pages.length - 1]
									// 声明一个当前页面
									curPage.onLoad(curPage.options) // 传入参数
									curPage.onShow()
									curPage.onReady()
									uni.showToast({
										title: '订阅失败',
										icon: 'error',
										duration: 2000,
										success() {
											console.log('订阅失败');
											if (url) {
												setTimeout(res => {

													if (navidx == 1) {
														uni.navigateBack()
													} else if (navidx == 2) {

													} else {
														uni.reLaunch({
															url
														})
													}
												}, 2000)
											}
										}
									})
								},
							})

						}
					})

				}
			},
			fail(err) {
				console.log('获取用户的当前设置失败', err);
			},
			complete(res) {
				console.log('获取用户的当前设置complete', res);
			}

		})

	},
	/**
	 * @微信小程序 开启全页面分享功能在APP.js中onLaunch中调用此方法
	 */
	onLaunch(options) {
		this.overShare()
	},
	overShare() {
		uni.onAppRoute((res) => {
			// console.log('route', res)
			let pages = getCurrentPages()
			let view = pages[pages.length - 1]
			if (view) {
				uni.showShareMenu({
					withShareTicket: true,
					menus: ['shareAppMessage', 'shareTimeline'],
					success(res) {
						console.log(res, '分享了');
					},
					fail(e) {
						console.log(e, '分享失败');
					}
				})
			}
		})
	},
	// 弹窗提示
	uniModel(title, content) {
		return new Promise((reslove, reject) => {
			uni.showModal({
				title,
				content,
				success: function(res) {
					if (res.confirm) {
						//未登录
						reslove('confirm')
					} else if (res.cancel) {
						reslove('cancel')
					}
				}
			});
		})
	},
	// toast弹框
	showToast(title) {
		uni.showToast({
			title,
			icon: 'none',
			duration: 2000
		})
	},
}