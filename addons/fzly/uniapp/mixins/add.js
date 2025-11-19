import projecturl from '@/config.js'
export default {
	methods: {
		// 上传图片
		upload_img(data) {
			return new Promise(function(resolve, reject) {
				uni.uploadFile({
					url: projecturl.serverIp + 'api/common/upload',
					filePath: data,
					header: {
						token: uni.getStorageSync('token')
					},
					name: 'file',
					success: (res) => {
						let data = JSON.parse(res.data)
						console.log(data)
						if (data.code == 0) {
							uni.showToast({
								title: '上传失败',
								icon: 'error'
							})
						}
						if (res.statusCode == 200) {
							resolve(JSON.parse(res.data))
						} else {
							reject({
								msg: '上传失败'
							})
						}
					},
					fail: (res) => {
						console.log(res)
					}
				})
			})
		},
		removeDomainFromUrl(url) {
			// 匹配 http:// 或 https:// 开头的 URL  
			const pattern = /^https?:\/\/(?:www\.)?([^/]+)/;
			const matches = url.match(pattern);

			if (matches && matches.length > 1) {
				// 截取匹配到的域名部分  
				const domain = matches[1];

				// 构造一个新的URL字符串，去掉原始域名部分  
				const remainingUrl = url.replace(new RegExp(`^https?://(?:www\\.)?${domain}/`), '/');

				// 如果剩下的部分以斜杠开头，则保留斜杠；否则，移除开头的斜杠  
				return remainingUrl.startsWith('/') ? remainingUrl : `/${remainingUrl}`;
			} else {
				// 如果没有匹配到域名，则返回原始URL  
				return url;
			}
		},
		// 检查发布动态是否为空
		check_fb_kong() {
			if (this.fb_data.type == 1 || this.fb_data.type == 2) {
				if (!this.fb_data.image) {
					this.$refs.uToast.show({
						message: '请上传封面',
					})
					return true
				}
			}
			if (!this.fb_data.title) {
				this.$refs.uToast.show({
					message: '请填写标题',
				})
				return true
			}
			if (!this.fb_data.content) {
				this.$refs.uToast.show({
					message: '请填写内容',
				})
				return true
			}
			if (this.fb_data.type == 3) {
				if (this.fb_data.images.length == 0) {
					this.$refs.uToast.show({
						message: '请上传至少一张图片',
					})
					return true
				}
			}
			if (this.fb_data.type == 4) {
				if (!this.fb_data.type_id) {
					this.$refs.uToast.show({
						message: '请选择分类',
					})
					return true
				}
			}
			if (this.fb_data.type == 1 || this.fb_data.type == 2 || this.fb_data.type == 3) {
				if (!this.fb_data.mp_id) {
					this.$refs.uToast.show({
						message: '请选择景区分类',
					})
					return true
				}
			}
			return false
		},
		check_dy_kong(data) {
			if (!data.name) {
				this.$refs.uToast.show({
					message: '姓名不能为空'
				})
				return true
			}
			if (!data.tel) {
				this.$refs.uToast.show({
					message: '手机号不能为空'
				})
				return true
			} else if (!uni.$u.test.mobile(data.tel)) {
				this.$refs.uToast.show({
					message: '请输入正确的手机号'
				})
				return true
			}
			if (!data.number) {
				this.$refs.uToast.show({
					message: '资格证号不能为空'
				})
				return true
			}
			if (!data.organ) {
				this.$refs.uToast.show({
					message: '发证机关不能为空'
				})
				return true
			}
			// if (!data.bank) {
			// 	this.$refs.uToast.show({
			// 		message: '银行卡号不能为空'
			// 	})
			// 	return true
			// }
			if (!data.back_image) {
				this.$refs.uToast.show({
					message: '人像面不能为空'
				})
				return true
			}
			if (!data.font_image) {
				this.$refs.uToast.show({
					message: '国徽面不能为空'
				})
				return true
			}
			if (!data.certificate_image) {
				this.$refs.uToast.show({
					message: '导游证不能为空'
				})
				return true
			}
			return false
		},

		// 检查发布产品为空
		check_product_kong(data, type) {
			if (!data.title) {
				this.$refs.uToast.show({
					message: '标题不能为空'
				})
				return true
			}
			if (!data.type_id) {
				this.$refs.uToast.show({
					message: '请选择产品类型'
				})
				return true
			}
			if (type == '包车游') {
				if (data.model.length == 0) {
					this.$refs.uToast.show({
						message: '请选择车型'
					})
					return true
				}
			} else {
				if (data.time.length == 0) {
					this.$refs.uToast.show({
						message: '请选择时长'
					})
					return true
				}
			}
			if (!data.price) {
				this.$refs.uToast.show({
					message: '请输入价格'
				})
				return true
			} else {
				console.log(data.price > 0, '================')
				if (!(data.price > 0)) {
					this.$refs.uToast.show({
						message: '请输入正确的价格'
					})
					return true
				}
			}
			if (!data.address) {
				this.$refs.uToast.show({
					message: '请输入景区地址'
				})
				return true
			}
			if (!data.zm_image) {
				this.$refs.uToast.show({
					message: '上传合作证明'
				})
				return true
			}
			if (!data.image) {
				this.$refs.uToast.show({
					message: '上传封面'
				})
				return true
			}
			if (!data.content) {
				this.$refs.uToast.show({
					message: '请填写真实的景区介绍'
				})
				return true
			}
		},
		// 检验添加账户是否为空
		add_account_kong(data) {
			if (data.type == 1) {
				if (!data.bank_code) {
					this.$refs.uToast.show({
						message: '请填写银行卡号'
					})
					return true
				} else {
					// 使用正则表达式匹配银行卡号的格式
					const regex = /^[0-9]{16,19}$/;
					if (!regex.test(data.bank_code)) {
						this.$refs.uToast.show({
							message: '请填写正确的银行卡号'
						})
						return true
					}
				}
				if (!data.bank_mobile) {
					this.$refs.uToast.show({
						message: '请填写银行预留手机号'
					})
					return true
				}
				if (!data.code) {
					this.$refs.uToast.show({
						message: '请填写验证码'
					})
					return true
				}
			} else {
				if (!data.zh_code) {
					this.$refs.uToast.show({
						message: '请填写账号'
					})
					return true
				}
			}
		},
		// 检查景区入驻填写信息
		check_jq_kong(data) {
			if (!data.name) {
				this.$refs.uToast.show({
					message: '请填写姓名'
				})
				return true
			}
			if (!data.tel) {
				this.$refs.uToast.show({
					message: '请填写手机号'
				})
				return true
			} else {
				if (!uni.$u.test.mobile(data.tel)) {
					this.$refs.uToast.show({
						message: '检查手机号是否正确'
					})
					return true
				}
			}
			if (!data.card) {
				this.$refs.uToast.show({
					message: '请填写身份证号'
				})
				return true
			} else {
				if (!uni.$u.test.idCard(data.card)) {
					this.$refs.uToast.show({
						message: '检查身份证号是否正确'
					})
					return true
				}
			}
			if (!data.admission_title) {
				this.$refs.uToast.show({
					message: '请填写景区名称'
				})
				return true
			}
			if (!data.admission_city) {
				this.$refs.uToast.show({
					message: '请选择景区所在城市'
				})
				return true
			}
			if (!data.admission_image) {
				this.$refs.uToast.show({
					message: '请上传景区缩略图'
				})
				return true
			}
			if (!data.yy_image) {
				this.$refs.uToast.show({
					message: '请上传营业执照'
				})
				return true
			}
			return false
		}
	},
}