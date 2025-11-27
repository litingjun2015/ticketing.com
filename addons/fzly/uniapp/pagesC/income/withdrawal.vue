<template>
	<view class="content font_family">
		<view class="tx_to f_z_b" @click="show=true">
			<view class="left">
				提现至
			</view>
			<view class="right f_j">
				<image v-if="account_action!==''" class="icon" :src="tx_icon" mode="aspectFill"></image>
				<view class="">
					{{tx_text}}
				</view>
				<image class="icon2" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/go_hot.png" mode=""></image>
			</view>
		</view>
		<!-- 提现输入框 -->
		<view class="tx_box">
			<view class="title">
				提现金额
			</view>
			<view class="price_box f_j">
				<view class="icon">
					￥
				</view>
				<view class="price" @click="Key_show=true" :class="{no_price:query_data.money==''}">
					{{query_data.money?query_data.money:'输入金额'}}
				</view>
				<view class="all" @click="query_data.money=can_price">
					全部提现
				</view>
			</view>
			<view class="yes_price">
				可提现金额￥{{can_price}}
			</view>
		</view>
		<!-- 确认按钮 -->
		<view class="btn f_zj" @click="withdrawal">
			确认提现
		</view>
		<!-- 数字键盘 -->
		<u-keyboard ref="uKeyboard" :showTips='false' mode="number" :closeOnClickOverlay='true' @close='close'
			@confirm="Key_show=false" @cancel="Key_show=false" @backspace="backspace" @change="change"
			:safeAreaInsetBottom='true' :show="Key_show"></u-keyboard>
		<!-- 提现账户弹窗 -->
		<u-popup :show="show" round='20' @close="pop_close">
			<view class="account_box">
				<view class="title">
					请选择提现账户
				</view>
				<scroll-view scroll-y="true" class="account">
					<view class="item f_j" @click="account_check(0)">
						<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/vx_account.png" mode="aspectFill"></image>
						<view class="name f_d f_z_b">
							<view class="">
								微信
							</view>
							<view class="" style="margin-top: 10rpx;" v-if="user_info.openid">
								{{user_info.mobile | hide_tel}}
							</view>
						</view>
						<image v-if="0===account_action" class="check_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/dg_y.png"
							mode="aspectFill"></image>
						<image v-else class="check_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/dg_h.png" mode="aspectFill"></image>
					</view>
					<view class="item f_j" v-for="(item,index) in account_arr" :key="index"
						@click="account_check(index+1)">
						<image class="icon" v-if="item.type_text=='支付宝'" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/zfb_account.png"
							mode="aspectFill"></image>
						<image class="icon" v-else :src="item.bank_icon" mode="aspectFill"></image>
						<view class="name f_d f_z_b">
							<view class="">
								{{item.bank_name?item.bank_name:item.type_text}}
							</view>
							<view class="" style="margin-top: 10rpx;" v-if="item.type_text=='支付宝'">
								{{item.zfb_code | hide_tel}}
							</view>
							<view class="" style="margin-top: 10rpx;" v-else>
								{{item.bank_code | hide_card}}
							</view>
						</view>
						<image v-if="index+1===account_action" class="check_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/dg_y.png"
							mode="aspectFill"></image>
						<image v-else class="check_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/dg_h.png" mode="aspectFill"></image>
					</view>
				</scroll-view>
				<view class="add_account f_j" @click="go('/pagesB/income/add_account')">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode="aspectFill"></image>
					<view class="text">
						添加账户
					</view>
				</view>
			</view>
		</u-popup>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { account_list, binding, withdraw } from '@/api/user.js'
	import { mapState, mapActions } from 'vuex'
	export default {
		data() {
			return {
				Key_show: false,
				show: false,
				query_data: {
					money: '',
					id: ''
				},
				tx_text: '请选择',
				tx_icon: '',
				account_action: '',
				account_arr: '',
				can_price: 0.00,
			}
		},
		computed: {
			...mapState(['user_info'])
		},
		onShow() {
			this.get_account()
		},
		onLoad() {},
		methods: {
			...mapActions(['get_user']),
			go(url) {
				uni.navigateTo({
					url
				})
			},
			account_check(index) {
				this.account_action = index
				if (index == 0) {
					//判断用户是否绑定微信
					console.log(this.user_info)
					if (!this.user_info.openid) {
						uni.showModal({
							title: '提示',
							content: '当前账号未绑定微信，是否绑定？',
							success: (res) => {
								if (res.confirm) {
									uni.login({
										success: (res) => {
											if (res.errMsg == 'login:ok') {
												binding({ code: res.code }).then(res => {
													if (res.code == 1) {
														this.$refs.uToast.show({
															type: 'success',
															message: '账号已绑定微信'
														})
													}
													this.account_action = ''
													// 更新用户信息
													this.get_user()
												})
											}
										},
										fail: (res) => {
											console.log(res)
										}
									})
								} else if (res.cancel) {
									this.account_action = ''
								}
							}
						});
					} else {
						this.tx_text = '微信'
						this.tx_icon = 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/vx_account.png'
						this.query_data.id = 0
					}
				} else {
					if (this.account_arr[index - 1].type_text == '银行卡') {
						this.tx_text = this.account_arr[index - 1].bank_name
						this.tx_icon = this.account_arr[index - 1].bank_icon
						this.query_data.id = this.account_arr[index - 1].id
					} else {
						this.tx_text = this.account_arr[index - 1].type_text
						this.tx_icon = 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/zfb_account.png'
						this.query_data.id = this.account_arr[index - 1].id
					}

				}
				this.show = false
			},
			close() {
				this.Key_show = false
			},
			pop_close() {
				this.show = false
			},
			change(e) {
				// console.log(e)
				// 查找小数点的位置  
				const decimalIndex = this.query_data.money.indexOf('.');
				if (decimalIndex !== -1) {
					let num = this.query_data.money.substring(decimalIndex + 1).length
					if (num >= 2) {
						return
					}
				}
				if (this.query_data.money.length > 9) {
					return
				} else {
					this.query_data.money += e
				}
			},
			// 退格键被点击
			backspace() {
				console.log('2222')
				// 删除value的最后一个字符
				if (this.query_data.money.length) this.query_data.money = this.query_data.money.substr(0, this.query_data
					.money.length - 1);
			},
			// 获取提现账户列表
			get_account() {
				account_list({ status: 1 }).then(res => {
					if (res.code == 1) {
						this.account_arr = res.data.data
						this.can_price = res.data.money
					}
				})
			},
			// 提现
			withdrawal() {
				if (this.query_data.id === '') {
					this.$refs.uToast.show({
						message: '请选择提现账户'
					})
					return
				}
				if (this.query_data.money == '' || this.query_data.money <= 0) {
					this.$refs.uToast.show({
						message: '请输入提现金额'
					})
					return
				} else {
					console.log(this.query_data.money > this.can_price)
					if (this.query_data.money > this.can_price) {
						this.$refs.uToast.show({
							message: '账户余额不足'
						})
						return
					}
				}
				withdraw(this.query_data).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.$refs.uToast.show({
							type: 'success',
							message: res.msg,
							duration: 1000,
							complete: () => {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					} else {
						this.$refs.uToast.show({
							message: res.msg,
							duration: 1000,
						})
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;

		.tx_to {
			background: #FFFFFF;
			border-radius: 14rpx 14rpx 14rpx 14rpx;
			padding: 30rpx;

			.left {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
			}

			.right {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;

				.icon {
					width: 36rpx;
					height: 36rpx;
					margin-right: 15rpx;
				}

				.icon2 {
					margin-left: 10rpx;
					width: 12rpx;
					height: 24rpx;
				}
			}
		}

		.tx_box {
			margin-top: 30rpx;
			background: #FFFFFF;
			border-radius: 14rpx 14rpx 14rpx 14rpx;
			padding: 30rpx;

			.title {
				font-weight: 400;
				font-size: 28rpx;
				color: #3D3D3D;
			}

			.price_box {
				padding: 20rpx 0rpx;
				border-bottom: 1rpx solid #EBEBEB;

				.icon {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
				}

				.price {
					width: 450rpx;
					margin-left: 34rpx;
					font-weight: 400;
					font-size: 61rpx;
					color: #3D3D3D;
				}

				.no_price {
					color: #999999;
				}

				.all {
					margin-left: auto;
					font-weight: 400;
					font-size: 24rpx;
					color: #FFAE35;
				}
			}

			.yes_price {
				font-weight: 400;
				font-size: 24rpx;
				color: #3D3D3D;
				margin-top: 15rpx;
			}
		}

		.btn {
			width: 100%;
			height: 85rpx;
			background: #FFAE35;
			border-radius: 16rpx 16rpx 16rpx 16rpx;
			font-weight: 400;
			font-size: 28rpx;
			color: #FFFFFF;
			margin-top: 60rpx;
		}

		::v-deep.u-keyboard__tooltip__submit {
			color: #FFAE35 !important;
		}

		.account_box {
			padding: 40rpx;

			.title {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				text-align: center;
			}

			.account {
				height: 600rpx;
				margin-top: 20rpx;

				.item {
					margin-bottom: 50rpx;

					.icon {
						width: 90rpx;
						height: 90rpx;
					}

					.name {
						font-weight: 400;
						font-size: 28rpx;
						color: #3D3D3D;
						margin-left: 20rpx;
					}

					.check_icon {
						width: 34rpx;
						height: 34rpx;
						margin-left: auto;
					}
				}
			}

			.add_account {
				padding: 20rpx;
				border-bottom: 1rpx solid #EBEBEB;

				.icon {
					width: 28rpx;
					height: 28rpx;
				}

				.text {
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
					margin-left: 20rpx;
				}
			}
		}
	}
</style>