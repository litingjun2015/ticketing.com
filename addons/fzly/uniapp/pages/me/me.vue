<template>
	<view class="content font_family"
		:style="{paddingTop:menuButtonInfo+'px',backgroundImage:`url(${projectUrl}assets/addons/fzly/img/x1.png)`}">
		<view class="user f_z_b">
			<view class="left f_j">
				<view class="avatar">
					<u-avatar v-if="user_info" :src="user_info.avatar" size="140rpx" @click="go('/pagesA/set/set')"
						mode="aspectFill"></u-avatar>
					<u-avatar v-else src="" size="140rpx"></u-avatar>
				</view>
				<text class="name text_ellipsis" @click="go('/pagesA/set/set')"
					v-if="user_info">{{user_info.username}}</text>
				<text class="name" v-else @click="go_lonig">请先登录</text>
			</view>
			<view class="right f_j" v-if="user_info">
				<view class="set_icon" @click="go('/pagesA/set/set')">
					<image class="icon" src="../../static/me/me_set_icon.png" mode=""></image>
				</view>
			</view>
		</view>
		<!-- 我的订单 -->
		<view class="order_box">
			<view class="f_j f_z_b" @click="go('/pagesA/order/order?type=0')">
				<text class="title">我的订单</text>
				<image class="go_order" src="../../static/index/go_hot.png" mode=""></image>
			</view>
			<view class="item_box f">
				<view class="item f_d f_j" v-for="(item,index) in order" :key="index" @click="go(item.url)">
					<image class="icon" :src="item.icon" mode=""></image>
					<text class="text">{{item.text}}</text>
				</view>
			</view>
		</view>
		<!-- 更多功能 -->
		<view class="more_fun">
			<text class="title">更多功能</text>
			<view class="item_box">
				<view class="item f_z_b" @click="go('/pagesB/index/index')" v-if="user_info.is_dy==2">
					<view class="left f_j">
						<image class="icon" src="../../static/me/function1.png" mode=""></image>
						<text class="text">导游中心</text>
					</view>
					<view class="right">
						<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
					</view>
				</view>
				<view class="item f_z_b" @click="go(item.url)" v-for="(item,index) in more" :key="index">
					<view class="left f_j">
						<image class="icon" :src="item.icon" mode=""></image>
						<text class="text">{{item.text}}</text>
					</view>
					<view class="right">
						<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
					</view>
				</view>
				<view class="item f_z_b" @click="go('/pagesC/index/index')" v-if="user_info.is_admissionuser==1">
					<view class="left f_j">
						<image class="icon" src="../../static/me/mp.png" mode=""></image>
						<text class="text">门票管理</text>
					</view>
					<view class="right">
						<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
					</view>
				</view>
				<view class="item f_z_b" @click="go('/pagesC/join/join')" v-else>
					<view class="left f_j">
						<image class="icon" src="../../static/me/mp.png" mode=""></image>
						<text class="text">景区入驻</text>
					</view>
					<view class="right">
						<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
					</view>
				</view>
			</view>
		</view>
		<view class="more_fun fun2">
			<view class="item_box box2">
				<view class="item f_z_b" @click="go(item.url)" v-for="(item,index) in more2" :key="index">
					<view class="left f_j">
						<image class="icon" :src="item.icon" mode=""></image>
						<text class="text">{{item.text}}</text>
					</view>
					<view class="right">
						<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
					</view>
				</view>
				<view class="item f_z_b" @click="clean">
					<view class="left f_j">
						<image class="icon" src="../../static/me/function4.png" mode=""></image>
						<text class="text">清除记录</text>
					</view>
					<view class="right">
						<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
					</view>
				</view>
				<!-- #ifdef MP-WEIXIN -->
				<button open-type="contact">
					<view class="item p_0 f_z_b">
						<view class="left f_j">
							<image class="icon" src="../../static/me/function3.png" mode=""></image>
							<text class="text">客服中心</text>
							<!-- <button open-type="contact">客服中心</button> -->
						</view>
						<view class="right f_j">
							<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
						</view>
					</view>
				</button>
				<!-- #endif -->
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapState, mapActions } from 'vuex'
	import { userInfo } from '../../api/public'
	export default {
		computed: {
			...mapState(['user_info'])
		},
		data() {
			return {
				projectUrl: '',
				// 胶囊
				menuButtonInfo: 0,
				// 订单
				order: [
					{ icon: '../../static/me/order1.png', text: '待付款', url: '/pagesA/order/order?type=1' },
					{ icon: '../../static/me/order2.png', text: '已付款', url: '/pagesA/order/order?type=2' },
					{ icon: '../../static/me/order3.png', text: '已核销', url: '/pagesA/order/order?type=3' },
					// { icon: '../../static/me/order4.png', text: '待退款', url: '/pagesA/order/order?type=4' },
					{ icon: '../../static/me/order5.png', text: '已取消', url: '/pagesA/order/order?type=4' },
				],
				// 更多功能
				more: [{
						icon: '../../static/me/function2.png',
						text: '成为导游',
						url: '/pagesA/register_guide/register_guide'
					},
					{ icon: '../../static/me/coupon.png', text: '我的优惠券', url: '/pagesA/coupon/coupon' },
					{ icon: '../../static/me/grzy.png', text: '个人主页', url: '/pagesA/user_homepage/user_homepage' },
					{ icon: '../../static/me/fx.png', text: '分销', url: '/pagesG/distribution/distribution' },
				],
				more2: [
					{ icon: '../../static/me/function5.png', text: '关于我们', url: '/pagesA/public/public?title=关于我们' },
					{ icon: '../../static/me/function6.png', text: '意见反馈', url: '/pagesA/feedback/feedback' },
				]
			}
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().bottom
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.projectUrl = this.$projectUrl
		},
		onShow() {
			this.get_user()
		},
		methods: {
			...mapActions(['get_user']),
			go_lonig() {
				uni.navigateTo({
					url: '/pagesA/login/login'
				})
			},
			go(url) {
				// 判断是否登录
				if (!this.user_info) {
					uni.showModal({
						title: '提示',
						content: '您还未登录是否去登录？',
						success: function(res) {
							if (res.confirm) {
								uni.reLaunch({
									url: '/pagesA/login/login'
								})
							} else if (res.cancel) {}
						}
					});
					return
				}
				uni.navigateTo({
					url
				})
			},
			clean() {
				// 判断是否登录
				if (!this.user_info) {
					uni.showModal({
						title: '提示',
						content: '您还未登录是否去登录？',
						success: function(res) {
							if (res.confirm) {
								uni.reLaunch({
									url: '/pagesA/login/login'
								})
							} else if (res.cancel) {}
						}
					});
					return
				}
				// uni.clearStorageSync()
				this.$refs.uToast.show({
					type: 'success',
					message: '清除成功'
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		// background-image: url(../../static/me/me_bg.png);
		background-repeat: no-repeat;
		background-size: 100%;
		padding: 0rpx 30rpx;

		.user {
			.avatar {
				border-radius: 50%;
				border: 4rpx solid white;
			}

			.name {
				width: 350rpx;
				margin-left: 20rpx;
				font-weight: 400;
				font-size: 32rpx;
				color: #000000;
				line-height: 44rpx;
				font-style: normal;
				text-transform: none;
			}

			.right {
				.set_icon {
					margin-left: 18rpx;
				}

				.icon {
					width: 48rpx;
					height: 48rpx;
				}
			}
		}

		// 订单
		.order_box {
			background: #FFFFFF;
			border-radius: 20rpx 20rpx 20rpx 20rpx;
			padding: 20rpx;
			margin-top: 38rpx;

			.title {
				font-weight: 400;
				font-size: 32rpx;
				color: #000000;
				line-height: 44rpx;
				text-align: center;
				font-style: normal;
				text-transform: none;
			}

			.go_order {
				width: 10rpx;
				height: 17rpx;
			}

			.item_box {
				justify-content: space-around;
				margin-top: 30rpx;

				.item {
					.icon {
						width: 50rpx;
						height: 50rpx;
					}

					.text {
						margin-top: 10rpx;
						font-weight: 400;
						font-size: 28rpx;
						color: #000000;
						line-height: 44rpx;
						text-align: center;
						font-style: normal;
						text-transform: none;
					}
				}
			}
		}

		// 更多功能
		.fun2 {
			margin-top: 19rpx !important;
		}

		.more_fun {
			background: #FFFFFF;
			border-radius: 20rpx;
			margin-top: 38rpx;
			padding: 28rpx 20rpx;

			.title {
				font-weight: 400;
				font-size: 32rpx;
				color: #000000;
				line-height: 44rpx;
				text-align: center;
				font-style: normal;
				text-transform: none;
			}

			.box2 {
				margin-top: 0rpx !important;
			}

			.item_box {
				margin-top: 20rpx;

				.item {
					padding: 25rpx 0rpx;

					.left {
						.icon {
							vertical-align: bottom;
							width: 36rpx;
							height: 36rpx;
						}

						.text {
							margin-left: 30rpx;
							font-weight: 400;
							font-size: 28rpx;
							color: #000000;
							line-height: 44rpx;
							text-align: center;
							font-style: normal;
							text-transform: none;
						}
					}

					.right {
						.icon {
							width: 10rpx;
							height: 16rpx;
						}
					}
				}

				// .p_0 {
				// 	padding: 0;
				// }

				button {
					background: none;
					font-size: 28rpx;
					padding: 0;
					line-height: 0;
					// width: 300rpx;
				}

				button::after {
					border: none !important;
				}
			}
		}
	}
</style>