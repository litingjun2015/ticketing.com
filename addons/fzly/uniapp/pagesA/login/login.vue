<template>
	<view class="content font_family" :style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/bb.png)`}">
		<view class="mask" :style="{paddingTop:menuButtonInfo+'px'}">
			<image class="close" src="../../static/public/close2.png" mode="" @click="go_index"></image>
			<!-- <view class="">
				<image class="img_text" src="../../static/public/login_text.png" mode=""></image>
			</view> -->
			<!-- 登录框 -->
			<view class="login_box f_d f_j">
				<view class="item f_j f_z_b">
					<u--input placeholder="请输入手机号" fontSize='35rpx' border="none" v-model="query.mobile"></u--input>
					<view class="right f_zj" @click="getCode">
						<view class="">
							{{code_tip}}
						</view>
					</view>
				</view>
				<view class="item f_j f_z_b">
					<u--input placeholder="请输入验证码" fontSize='35rpx' border="none" v-model="query.captcha"></u--input>
				</view>
				<!-- 条款 -->
				<view class="read f">
					<image @click="check" v-if="read_check" class="icon" src="../../static/public/read.png" mode="">
					</image>
					<image @click="check" v-else class="icon" src="../../static/public/no_read.png" mode=""></image>
					<view class="" style="flex: 1;">
						<text @click="check">我已阅读并同意</text>
						<text class="text" @click="go('/pagesA/public/public?title=用户协议')">《用户协议》</text>
						<text class="text" @click="go('/pagesA/public/public?title=隐私政策')">《隐私政策》</text>
						<text class="text" @click="go('/pagesA/public/public?title=中国电信认证服务条款')">《中国电信认证服务条款》</text>
					</view>
				</view>
				<view class="login_btn f_zj" @click="mobile_login">
					登录
				</view>
				<view class="other f_d f_j">
					<view class="f_j">
						<view class="line">

						</view>
						<view class="">
							其他登陆方式
						</view>
						<view class="line">

						</view>
					</view>
					<!-- #ifdef MP-WEIXIN -->
					<button open-type="getPhoneNumber" @getphonenumber="wxlogin">
						<image class="icon" src="../../static/public/login_icon.png" mode=""></image>
					</button>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<button @click="gzh_login">
						<image class="icon" src="../../static/public/login_icon.png" mode=""></image>
					</button>
					<!-- #endif -->
				</view>
			</view>
		</view>
		<u-code :seconds="seconds" changeText='xs后重新获取' keepRunning @end="end" @start="start" ref="uCode"
			@change="codeChange"></u-code>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { send } from '@/api/public.js'
	import { mapState, mapActions } from 'vuex'
	export default {
		computed: {
			...mapState(['menuButtonInfo'])
		},
		data() {
			return {
				seconds: 60,
				query: {
					mobile: '',
					captcha: '',
					event: 'register',
					code: ''
				},
				read_check: false,
				code_tip: '',
				projectUrl: ''
			}
		},
		onLoad() {
			this.projectUrl = this.$projectUrl
		},
		methods: {
			...mapActions(['loging', 'mobile_loging']),
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
			codeChange(text) {
				this.code_tip = text;
			},
			getCode() {
				// 判断手机号格式
				if (!uni.$u.test.mobile(this.query.mobile)) {
					uni.$u.toast('请输入正确的手机号');
					return
				}
				if (this.$refs.uCode.canGetCode) {
					// 向后端请求验证码
					uni.showLoading({
						title: '正在获取验证码'
					})
					send({ mobile: this.query.mobile }).then(res => {
						uni.hideLoading();
						if (res.code == 1) {
							// 这里此提示会被this.start()方法中的提示覆盖
							uni.$u.toast('验证码已发送');
							// 通知验证码组件内部开始倒计时
							this.$refs.uCode.start();
						} else {
							uni.$u.toast(res.msg);
						}

						console.log(res)
					})
				} else {
					uni.$u.toast('倒计时结束后再发送');
				}
			},
			end() {
				// uni.$u.toast('倒计时结束');
			},
			start() {
				uni.$u.toast('已发送');
			},
			// 微信登录回调
			wxlogin(e) {
				// console.log(e)
				if (!this.read_check) {
					this.$refs.uToast.show({
						type: 'error',
						message: '请先勾选用户须知'
					})
					return
				}
				if (e.detail.errMsg === 'getPhoneNumber:ok') {
					// 授权成功,获取code
					let obj = {
						iv: encodeURIComponent(e.detail.iv),
						encryptedData: encodeURIComponent(e.detail.encryptedData),
						code: ''
					}
					uni.login({
						success: (res) => {
							console.log(res)
							if (res.errMsg === 'login:ok') {
								obj.code = res.code
								// console.log(obj)
								this.loging(obj)
							}
						}
					})
				}
			},
			// 手机号登录
			mobile_login() {
				if (!this.read_check) {
					this.$refs.uToast.show({
						type: 'error',
						message: '请先勾选用户须知'
					})
					return
				}
				// 判断非空
				if (!this.query.mobile) {
					this.$refs.uToast.show({
						message: '请输入手机号'
					})
					return
				} else {
					if (!uni.$u.test.mobile(this.query.mobile)) {
						this.$refs.uToast.show({
							message: '请输入正确的手机号'
						})
						return
					}
				}
				if (!this.query.captcha) {
					this.$refs.uToast.show({
						message: '请输入验证码'
					})
					return
				}
				uni.login({
					success: (res) => {
						// console.log(res)
						this.query.code = res.code
						this.mobile_loging(this.query)
					},
					fail: (res) => {
						this.$refs.uToast.show({
							message: '登录失败，请重试'
						})
					}
				})
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