<script>
	import store from './store/index.js'
	import { common } from '@/api/public.js'
	import qqmapsdk from '@/utils/qq_map/qqmap-wx-jssdk.min.js'
	const QQMapWX = new qqmapsdk({
		key: 'JLEBZ-7RILJ-GVPFD-KVMPC-IMSIZ-VKF7M' //key
	});
	export default {
		globalData: {
			safe_bottom: 0
		},
		onLaunch: async function(e) {
			console.log('App Launch', e)
			if (e.query.pid) {
				uni.setStorageSync('pid', e.query.pid)
			} else if (e.query.scene) {
				uni.setStorageSync('pid', e.query.scene)
			}

			// 获取公共参数
			// common().then(res => {
			// 	if (res.code == 1) {
			// 		// 公共参数存入缓存，vuex
			// 		console.log(res)
			// 		store.commit('set_common', res.data)
			// 		uni.setStorageSync('common', res.data)
			// 	}
			// })
			let data = await common()
			if (data.code == 1) {
				// 公共参数存入缓存，vuex
				store.commit('set_common', data.data)
				uni.setStorageSync('common', data.data)
			}
			// 获取系统信息
			uni.getSystemInfo({
				success: (res) => {
					if (res.safeArea.bottom - res.safeArea.height <= 20) {
						this.globalData.safe_bottom = 0
					} else {
						this.globalData.safe_bottom = res.safeArea.bottom - res.safeArea.height
					}
				}
			});
			// #ifdef MP-WEIXIN
			let menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			store.commit('set_menuButtonInfo', menuButtonInfo)
			// #endif
			// #ifdef H5
			store.commit('set_menuButtonInfo', 20)
			// #endif

			// 获取位置信息
			// #ifdef MP-WEIXIN
			console.log('huoqudizhi')
			wx.getFuzzyLocation({
				type: "wgs84", //默认为 wgs84 返回 gps 坐标
				success: (res) => {
					console.log(res, "位置信息")
					// 存入缓存，vuex
					uni.setStorageSync('lon', res.longitude)
					uni.setStorageSync('lat', res.latitude)
					store.commit('set_lon', res.longitude)
					store.commit('set_lat', res.latitude)
					QQMapWX.reverseGeocoder({
						location: {
							latitude: res.latitude,
							longitude: res.longitude
						},
						success: (res) => {
							console.log(res, '地址解析')
							if (res.status == 0) {
								// 存入缓存，vuex
								uni.setStorageSync('city', res.result.ad_info.city)
								store.commit('set_city', res.result.ad_info.city)
							}
						},
						fail: (res) => {
							console.log(res, '地址解析')
							// 地址解析失败，vuex存入一个默认值
							store.commit('set_city', store.state.common.city)
						}
					})
				},
				fail: (res) => {
					// 用户拒绝位置授权，vuex存入一个默认值
					store.commit('set_city', store.state.common.city)
				}
			})
			// #endif
			// #ifdef H5
			console.log('wokaile ')
			uni.getLocation({
				success: (res) => {
					console.log(res)
					// 存入缓存，vuex
					uni.setStorageSync('lon', res.longitude)
					uni.setStorageSync('lat', res.latitude)
					store.commit('set_lon', res.longitude)
					store.commit('set_lat', res.latitude)
					QQMapWX.reverseGeocoder({
						location: {
							latitude: res.latitude,
							longitude: res.longitude
						},
						success: (res) => {
							console.log(res, '地址解析')
							if (res.status == 0) {
								// 存入缓存，vuex
								uni.setStorageSync('city', res.result.ad_info.city)
								store.commit('set_city', res.result.ad_info.city)
							}
						},
						fail: (res) => {
							// console.log(res, '地址解析', store.state.common.city)
							// 地址解析失败，vuex存入一个默认值
							store.commit('set_city', store.state.common.city)
						}
					})
				},
				fail: (res) => {
					console.log(res, 'shibai')
					// 用户拒绝位置授权，vuex存入一个默认值
					store.commit('set_city', store.state.common.city)
				}
			})
			// #endif
			// 监听键盘的弹出事件
			uni.onKeyboardHeightChange((res) => {
				console.log(res.height, '键盘弹起')
				if (res.height > 0) {
					// 键盘弹出时，根据键盘的高度调整固定元素的位置
					// this.KeyboardHeight = res.height;
					store.commit('set_KeyboardHeight', 60)
				} else {
					// 键盘收起时恢复初始位置
					store.commit('set_KeyboardHeight', 0)
				}
			});
			// 获取用户信息
			// store.dispatch('get_user')
		},
		onShow: function() {
			console.log('App Show')
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import "@/uni_modules/uview-ui/index.scss";

	@import '@/style/index.scss';

	.font_family {
		font-family: PingFang SC, PingFang SC;
	}

	// 页面公共背景色
	.content {
		background: #F8F9FC;
		min-height: 100vh;
	}

	.loadmore_box {
		margin-top: 30rpx;
		padding-bottom: 30rpx;
	}

	// 主 交对齐
	.f {
		display: flex;
	}

	.f_z_b {
		display: flex;
		justify-content: space-between;
	}

	.f_j_e {
		display: flex;
		align-items: flex-end;
	}

	.f_d {
		display: flex;
		flex-direction: column;
	}

	.f_zj {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.f_z {
		display: flex;
		justify-content: center;
	}

	.f_j {
		display: flex;
		align-items: center;
	}

	.f_w {
		flex-wrap: wrap;
	}

	// 文本超出长度省略
	.text_ellipsis {
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}

	.text_ellipsis2 {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		white-space: normal;
	}

	.text_ellipsis3 {
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		white-space: normal;
	}

	.p_f_top {
		position: fixed;
		top: 0;
		left: 0;
	}

	.p_f_bottom {
		position: fixed;
		bottom: 0;
		left: 0;
	}

	.safe_bottom {
		// height: calc(60px + env(safe-area-inset-bottom) /2);
		padding-bottom: calc(30rpx + env(safe-area-inset-bottom) /2);
	}

	.empty_top {
		margin-top: 50rpx;
	}
</style>