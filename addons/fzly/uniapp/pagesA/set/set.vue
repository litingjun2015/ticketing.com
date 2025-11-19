<template>
	<view class="content font_family">
		<view class="set_box">
			<view class="item f_j f_z_b" @click="go('/pagesA/set/user_msg')">
				<view class="left">
					编辑个人信息
				</view>
				<view class="right">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
			<!-- <view class="item f_j f_z_b" @click="go('/pagesA/set/address')">
				<view class="left">
					常住地
				</view>
				<view class="right">
					<text>漳州</text>
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view> -->
			<!-- <view class="item f_j f_z_b" @click="go('/pagesA/set/binding')">
				<view class="left">
					账号绑定
				</view>
				<view class="right">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view> -->
			<!-- <view class="item f_j f_z_b" @click="go('/pagesA/order/order?type=0')">
				<view class="left">
					我的订单
				</view>
				<view class="right">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view> -->
			<view class="item f_j f_z_b" @click="go('/pagesA/public/public?title=使用协议')">
				<view class="left">
					使用协议
				</view>
				<view class="right">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
			<view class="item f_j f_z_b" @click="go('/pagesA/public/public?title=隐私条款')">
				<view class="left">
					隐私条款
				</view>
				<view class="right">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
			<view class="item f_j f_z_b" @click="go('/pagesA/public/public?title=隐私条款概要')">
				<view class="left">
					隐私条款概要
				</view>
				<view class="right">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
			<!-- <view class="item f_j f_z_b">
				<view class="left">
					个人信息收集清单
				</view>
				<view class="right">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view> -->
		</view>
		<view class="btn f_zj" @click="logout_btn">
			退出登录
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapMutations } from 'vuex'
	import { logout } from '@/api/public.js'
	export default {
		data() {
			return {

			}
		},
		methods: {
			...mapMutations(['set_user_info']),
			go(url) {
				uni.navigateTo({
					url
				})
			},
			// 退出登录
			logout_btn() {
				logout().then(res => {
					if (res.code == 1) {
						// 移除token，用户信息
						uni.removeStorageSync('token');
						uni.removeStorageSync('user_info');
						this.set_user_info('')
						this.$refs.uToast.show({
							type: 'success',
							message: '退出登录',
							duration: '2000',
							complete: () => {
								uni.switchTab({
									url: '/pages/index/index'
								})
							}
						})
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-top: 20rpx;

		.set_box {
			background: #FFFFFF;
			padding: 0rpx 42rpx;

			.item {
				padding: 25rpx 0rpx;
				font-weight: 400;
				font-size: 34rpx;
				color: #353535;

				.left {}

				.right {
					.icon {
						width: 12rpx;
						height: 24rpx;
						margin-left: 20rpx;
					}
				}
			}
		}

		.btn {
			margin-top: 50rpx;
			height: 104rpx;
			background: #FFFFFF;
			border-radius: 0rpx 0rpx 0rpx 0rpx;
			font-weight: 400;
			font-size: 34rpx;
			color: #353535;
		}
	}
</style>