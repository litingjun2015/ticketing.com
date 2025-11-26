<template>
	<view class="content font_family" :style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/bb.png)`}">
		<view class="mask" :style="{paddingTop:menuButtonInfo+'px'}">
			<image class="close" src="../../static/public/close2.png" mode="" @click="go_index"></image>
			<!-- 登录框 -->
			<view class="login_box f_d f_j">
				<!-- 条款 -->
				<view class="read f">
					<image @click="check" v-if="read_check" class="icon" src="../../static/public/read.png" mode="">
					</image>
					<image @click="check" v-else class="icon" src="../../static/public/no_read.png" mode=""></image>
					<view class="" style="flex: 1;">
						<text @click="check">我已阅读并同意</text>
						<text class="text" @click="go('/pagesA/public/public?title=用户协议')">《用户协议》</text>
						<text class="text" @click="go('/pagesA/public/public?title=隐私政策')">《隐私政策》</text>
					</view>
				</view>
				<!-- #ifdef MP-WEIXIN -->
				<button class="login_btn f_zj" open-type="getPhoneNumber" @getphonenumber="wxlogin">
					微信一键登录
				</button>
				<!-- #endif -->
				<!-- #ifdef H5 -->
				<button class="login_btn f_zj" @click="gzh_login">
					微信授权登录
				</button>
				<!-- #endif -->
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	export default {
		computed: {
			...mapState(['menuButtonInfo'])
		},
		data() {
			return {
				read_check: false,
				projectUrl: ''
			}
		},
		onLoad() {
			this.projectUrl = this.$projectUrl
		},
		methods: {
			...mapActions(['loging']),
			go_index() {
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			go(url) {
				uni.navigateTo({
					url
				})
			},
			check() {
				this.read_check = !this.read_check
			},
			// 微信登录回调
			wxlogin(e) {
				console.log('微信登录回调', e)
				if (!this.read_check) {
					this.$refs.uToast.show({
						type: 'error',
						message: '请先勾选用户须知'
					})
					return
				}
				if (e.detail.errMsg === 'getPhoneNumber:ok') {
					uni.showLoading({ title: '登录中...' })
					// 授权成功,获取code
					let obj = {
						iv: encodeURIComponent(e.detail.iv),
						encryptedData: encodeURIComponent(e.detail.encryptedData),
						code: ''
					}
					uni.login({
						success: (res) => {
							console.log('uni.login成功', res)
							if (res.errMsg === 'login:ok') {
								obj.code = res.code
								console.log('登录参数', obj)
								this.loging(obj)
							}
						},
						fail: (err) => {
							uni.hideLoading()
							console.log('uni.login失败', err)
							this.$refs.uToast.show({
								type: 'error',
								message: '登录失败，请重试'
							})
						}
					})
				} else {
					console.log('用户拒绝授权手机号', e.detail.errMsg)
					this.$refs.uToast.show({
						type: 'error',
						message: '需要授权手机号才能登录'
					})
				}
			},
			// 公众号登录
			gzh_login() {
				if (!this.read_check) {
					this.$refs.uToast.show({
						type: 'error',
						message: '请先勾选用户须知'
					})
					return
				}
				let common_val = uni.getStorageSync('common')
				window.location.href = common_val.mpurl
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		// background-image: url(https://ly.51meteor.com/assets/img/bb.png);
		// background-position: center;
		background-repeat: no-repeat;
		background-size: 100% auto;
		// filter: blur(8rpx);

		.mask {
			width: 100vw;
			height: 100vh;
			// background-color: rgba(0, 0, 0, 0.5);
			position: fixed;
			top: 0;
			left: 0;

			.close {
				width: 36rpx;
				height: 36rpx;
				margin-left: 48rpx;
			}

			.img_text {
				width: 274rpx;
				height: 200rpx;
				margin-left: 66rpx;
				margin-top: 130rpx;
			}

			.login_box {
				padding-top: 40rpx;
				position: fixed;
				bottom: 0;
				left: 0;
				width: 100%;
				height: 72vh;
				// background: rgba(0, 0, 0, 0.8);
				background-color: #FFFFFF;
				border-radius: 34rpx 34rpx 0rpx 0rpx;

				.item {
					margin-bottom: 22rpx;
					width: 688rpx;
					height: 99rpx;
					background: rgba(255, 255, 255, 0.15);
					border-radius: 50rpx 50rpx 50rpx 50rpx;
					overflow: hidden;
					padding-left: 38rpx;
					border: 2rpx solid #EEEEEC;

					.right {
						margin-left: 30rpx;
						width: 220rpx;
						height: 100rpx;
						background: #FFE706;
						border-radius: 0rpx 48rpx 48rpx 0rpx;
						font-weight: 400;
						font-size: 30rpx;
						color: #3D3D3D;
					}
				}

				.read {
					font-weight: 400;
					font-size: 26rpx;
					color: #999999;
					padding: 0rpx 30rpx;
					box-sizing: border-box;

					.icon {
						box-sizing: border-box;
						width: 26rpx;
						height: 26rpx;
						margin-top: 5rpx;
						margin-right: 10rpx;
					}

					.text {
						color: #5784F9;
						// margin-left: 15rpx;
					}
				}

				.login_btn {
					margin-top: 52rpx;
					width: 688rpx;
					height: 99rpx;
					background: #FFE706;
					border-radius: 50rpx 50rpx 50rpx 50rpx;
					font-weight: 400;
					font-size: 30rpx;
					color: #000000;
				}

				.other {
					margin-top: 100rpx;
					font-weight: 400;
					font-size: 30rpx;
					color: #9A9A9A;

					.icon {
						width: 80rpx;
						height: 80rpx;
					}

					.line {
						margin: 0rpx 15rpx;
						width: 38rpx;
						// height: 0rpx;
						border: 1rpx solid #C1C1C1;
					}

					button::after {
						border: none;
					}

					button {
						margin-top: 20rpx;
						padding: 0;
						line-height: 0;
						background: none;
					}
				}
			}
		}
	}
</style>