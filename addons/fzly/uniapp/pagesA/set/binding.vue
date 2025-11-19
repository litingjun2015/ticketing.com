<template>
	<view class="content font_family">
		<view class="item f_j">
			<image class="icon" src="../../static/public/vx.png" mode=""></image>
			<text class="text">微信</text>
			<view class="btn" @click="binding(1)" v-if="user_info.openid">
				已绑定
			</view>
			<view class="no_btn" @click="binding(2)" v-else>
				未绑定
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	import { binding, remove } from '@/api/user.js'
	export default {
		data() {
			return {

			}
		},
		computed: {
			...mapState(['user_info'])
		},
		methods: {
			...mapActions(['get_user']),
			// 账号绑定
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
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.item {
			padding: 30rpx;

			.icon {
				width: 58rpx;
				height: 58rpx;
			}

			.text {
				font-weight: 500;
				font-size: 28rpx;
				color: #3D3D3D;
				margin-left: 16rpx;
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
		}
	}
</style>