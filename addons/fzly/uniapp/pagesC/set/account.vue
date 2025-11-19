<template>
	<view class="content font_family">
		<view class="add ">
			<view class="box f_zj" @click="go('/pagesB/income/add_account')">
				<image class="icon" src="../../static/public/add.png" mode=""></image>
				<text>新增提现账户</text>
			</view>
		</view>
		<view class="item f_j">
			<image class="icon" src="../../static/business/vx_account.png" mode="aspectFill"></image>
			<view class="name f_d f_z_b">
				<view class="">
					微信
				</view>
				<view class="" style="margin-top: 10rpx;" v-if="user_info.openid">
					{{user_info.mobile | hide_tel}}
				</view>
			</view>
			<view class="btn" @click="binding(1)" v-if="user_info.openid">
				已绑定
			</view>
			<view class="no_btn" @click="binding(2)" v-else>
				未绑定
			</view>
		</view>
		<view class="item f_j" v-for="(item,index) in account_arr" :key="index">
			<image class="icon" v-if="item.type_text=='支付宝'" src="../../static/business/zfb_account.png"
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
			<image class="sc" v-if="item.status==1" src="../../static/public/red_sc.png" mode="" @click="sc(item.id)">
			</image>
			<view class="no_btn" v-if="item.status==0">
				审核中
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { account_list, binding, remove, del_account } from '@/api/user.js'
	import { mapState, mapActions } from 'vuex'
	export default {
		data() {
			return {
				account_arr: ''
			};
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
			// 获取提现账户列表
			get_account() {
				account_list().then(res => {
					if (res.code == 1) {
						this.account_arr = res.data.data
					}
				})
			},
			// 微信绑定
			binding(type) {
				if (type == 1) {
					remove().then(res => {
						if (res.code == 1) {
							this.$refs.uToast.show({
								message: '账号解除绑定'
							})
							// 更新用户信息
							this.get_user()
						}
					})
				} else {
					uni.login({
						success: (res) => {
							if (res.errMsg == 'login:ok') {
								binding({ code: res.code }).then(res => {
									if (res.code == 1) {
										this.$refs.uToast.show({
											type: 'success',
											message: '账号已绑定微信'
										})
										// 更新用户信息
										this.get_user()
									}
								})
							}
						},
						fail: (res) => {
							console.log(res)
						}
					})
				}
			},
			// 提现账号解绑
			sc(id) {
				uni.showModal({
					title: '提示',
					content: '确定要解除绑定该账号吗？',
					success: (res) => {
						if (res.confirm) {
							del_account({ id }).then(res => {
								if (res.code == 1) {
									this.$refs.uToast.show({
										type: 'success',
										message: '账号已解除绑定'
									})
									this.get_account()
								}
							})
						} else if (res.cancel) {

						}
					}
				});

			}
		}
	}
</script>

<style lang="scss">
	.content {
		padding: 30rpx;

		.add {
			padding: 30rpx;

			.box {
				width: 100%;
				height: 74rpx;
				background: #FFFFFF;
				border-radius: 4rpx 4rpx 4rpx 4rpx;

				.icon {
					width: 36rpx;
					height: 36rpx;
					margin-right: 20rpx;
				}
			}
		}

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

			.btn {
				margin-left: auto;
				background: #30ADFF;
				border-radius: 8rpx 8rpx 8rpx 8rpx;
				font-weight: 500;
				font-size: 24rpx;
				color: #FFFFFF;
				padding: 10rpx 34rpx;
			}

			.no_btn {
				margin-left: auto;
				background: rgba(102, 102, 102, 0.1);
				border-radius: 8rpx 8rpx 8rpx 8rpx;
				font-weight: 500;
				font-size: 24rpx;
				color: #666666;
				padding: 10rpx 34rpx;
			}

			.check_icon {
				width: 34rpx;
				height: 34rpx;
				margin-left: auto;
			}

			.sc {
				width: 50rpx;
				height: 50rpx;
				margin-left: auto;
			}
		}
	}
</style>