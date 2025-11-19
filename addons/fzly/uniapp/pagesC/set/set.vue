<template>
	<view class="content font_family">
		<view class="msg_box">
			<view class="item f_z_b">
				<view class="left">
					头像
				</view>
				<button class="right f_j" open-type="chooseAvatar" @chooseavatar="onchooseavatar">
					<u-avatar :src="user_info.avatar" size="54rpx" mode="aspectFill"></u-avatar>
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</button>
			</view>
			<view class="item f_z_b">
				<view class="left">
					手机号绑定
				</view>
				<view class="right f_j">
					{{user_info.mobile}}
				</view>
			</view>
			<view class="item f_z_b" v-for="(item,index) in item_arr" :key="index"
				@click="go(`/pagesA/public/public?title=${item.text}`)">
				<view class="left">
					{{item.text}}
				</view>
				<view class="right f_j">
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
		</view>
		<view class="logout_btn" @click="logout_btn">
			退出登录
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { logout, userInfo, profile } from '@/api/public.js'
	import { mapState, mapMutations, mapActions } from 'vuex'
	export default {
		computed: {
			...mapState(['user_info'])
		},
		data() {
			return {
				item_arr: [
					{ text: '服务协议' },
					{ text: '个人信息处理规则' },
					{ text: '平台信息管理规范' },
				],
			};
		},
		methods: {
			...mapMutations(['set_user_info']),
			...mapActions(['get_user']),
			go(url) {
				uni.navigateTo({
					url
				})
			},
			onchooseavatar(e) {
				console.log(e)
				this.upload_img(e.detail.avatarUrl).then(res => {
					if (res.code == 1) {
						profile({ avatar: res.data.url }).then(res => {
							if (res.code == 1) {
								this.get_user()
							}
						})
					}
				})
			},
			// 退出登录
			logout_btn() {
				logout().then(res => {
					if (res.code == 1) {
						this.$refs.uToast.show({
							type: 'success',
							message: '退出登录',
							duration: '1000',
							complete: () => {
								// 移除token，用户信息
								uni.removeStorageSync('token');
								uni.removeStorageSync('user_info');
								this.set_user_info('')
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
		.msg_box {
			background-color: #FFFFFF;

			.item {
				padding: 28rpx 38rpx;

				.left {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
				}

				.right {
					.icon {
						margin-left: 24rpx;
						width: 16rpx;
						height: 30rpx;
					}
				}

				button {
					margin: 0;
					padding: 0;
					background: none;
				}

				button::after {
					border: none;
				}
			}
		}

		.logout_btn {
			background-color: #FFFFFF;
			font-weight: 400;
			font-size: 32rpx;
			color: #3D3D3D;
			padding: 34rpx 0rpx;
			text-align: center;
			margin-top: 50rpx;
		}
	}
</style>