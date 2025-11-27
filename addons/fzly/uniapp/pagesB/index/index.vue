<template>
	<view class="content font_family">
		<view class="header f_j" :style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/index_bg.png)`}">
			<view class="f_j">
				<view class="avatar">
					<u-avatar v-if="user_info" :src="user_info.avatar" size="140rpx" mode="aspectFill"></u-avatar>
				</view>
				<view class="name">
					{{user_info.username}}
				</view>
			</view>
		</view>
		<!-- 数据明细 -->
		<view class="box">
			<view class="detail f_z_b">
				<view class="item f_d f_j" @click="go('/pagesB/income/income')">
					<view class="">
						{{msg.total_money?msg.total_money:'0.00'}}
					</view>
					<view class="bot">
						总收入
					</view>
				</view>
				<view class="item f_d f_j" @click="go('/pagesB/order/order')">
					<view class="">
						{{msg.y_order_num?msg.y_order_num:'0'}}
					</view>
					<view class="bot">
						昨日订单
					</view>
				</view>
				<view class="item f_d f_j" @click="go('/pagesB/order/order')">
					<view class="">
						{{msg.t_order_num?msg.t_order_num:'0'}}
					</view>
					<view class="bot">
						今日订单
					</view>
				</view>
			</view>
			<view class="operation_box f">
				<view class="left" @click="sm">
					<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/sm.png" mode="aspectFill"></image>
					<view class="title_box">
						<view class="title">
							扫码核销
						</view>
						<view class="small_title">
							扫码即可核销
						</view>
					</view>
				</view>
				<view class="right f_d f_z_b">
					<view class="top" @click="go('/pagesB/income/income')">
						<view class="title_box">
							<view class="title">
								去提现
							</view>
							<view class="small_title">
								收入提现到账户
							</view>
						</view>
						<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/price.png" mode=""></image>
					</view>
					<view class="bottom" @click="go_tabbar()">
						<view class="title_box">
							<view class="title">
								查看消息
							</view>
							<view class="small_title">
								消息多多
							</view>
						</view>
						<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/msg.png" mode=""></image>
					</view>
				</view>
			</view>
		</view>
		<!-- 菜单 -->
		<view class="menu">
			<view class="item f_j" v-for="(item,index) in menu" :key="index" @click="go(item.url)">
				<image class="icon" :src="item.icon" mode=""></image>
				<view class="text">
					{{item.text}}
				</view>
				<image class="go" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/go_hot.png" mode=""></image>
			</view>
		</view>
		<u-tabbar :value="value" inactiveColor='#242424' activeColor='#FFAE35' :placeholder="true" :border="false"
			:fixed="true" :safeAreaInsetBottom="true">
			<u-tabbar-item v-for="(item,index) in tabbar_list" :key="index"
				:icon="value==index?item.action_icon:item.icon" :text="item.text" @click='change'></u-tabbar-item>
		</u-tabbar>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import mixin from '@/mixins/tabbar.js'
	import { guide_center, hx_code } from '@/api/user.js'
	import { mapState } from 'vuex'
	export default {
		mixins: [mixin],
		computed: {
			...mapState(['user_info', 'common'])
		},
		data() {
			return {
				projectUrl: '',
				value: 0,
				menu: [
					{ icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/sr.png', text: '收入明细', url: '/pagesB/income/income' },
					{ icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/gy.png', text: '关于我们', url: '/pagesA/public/public?title=关于我们' },
					{
						icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/fb_icon.png',
						text: '发布产品',
						url: '/pagesB/product/product'
					},
					{ icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/set.png', text: '设置', url: '/pagesB/set/set' },
				],
				msg: ''
			};
		},
		onLoad() {
			this.projectUrl = this.$projectUrl
			this.get_msg()
		},
		methods: {
			sm() {
				uni.scanCode({
					success: (res) => {
						console.log(res)
						if (res.errMsg == 'scanCode:ok') {
							let obj = {}
							let arr = res.result.split(',')
							arr.forEach(item => {
								let arr2 = item.split('=')
								let key = arr2[0]
								let value = arr2[1]
								obj[key] = value
							})
							let query_data = {
								order_no: obj.orderNo,
								code: obj.code
							}
							hx_code(query_data).then(res => {
								console.log(res)
								if (res.code == 1) {
									this.$refs.uToast.show({
										type: 'success',
										message: '核销成功',
										duration: 1000,
									})
									// 核销成功刷新总收入
									this.get_msg()
									let ids = []
									this.common.push.forEach(item => {
										if (item.message_type == 2) {
											ids.push(item.masterplate)
										}
									})
									// #ifdef MP-WEIXIN
									wx.requestSubscribeMessage({
										tmplIds: ids,
										success: (res) => {
											console.log(res)
										},
										fail: (res) => {
											console.log(res)
										}
									})
									// #endif
								} else {
									this.$refs.uToast.show({
										message: res.msg,
										duration: 1000,
									})
								}
							})
						}
					},
					fail: (res) => {
						console.log(res)
					}
				})
			},
			// 菜单跳转
			go(url) {
				uni.navigateTo({
					url
				})
			},
			// 获取导游中心数据
			get_msg() {
				guide_center().then(res => {
					if (res.code == 1) {
						this.msg = res.data
					}
				})
			},
			go_tabbar() {
				uni.switchTab({
					url: '/pages/msg/msg'
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.header {
			width: 100%;
			height: 402rpx;
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;

			.avatar {
				margin-left: 40rpx;
				border-radius: 50%;
				border: 2rpx solid #ffffff;
			}

			.name {
				margin-left: 30rpx;
				font-weight: 700;
				font-size: 36rpx;
				color: #232323;
			}
		}

		.box {
			padding: 0rpx 40rpx;

			.detail {
				margin-top: -60rpx;
				width: 100%;
				background: #FFFFFF;
				border-radius: 26rpx 26rpx 26rpx 26rpx;
				padding: 20rpx 40rpx;
				box-sizing: border-box;

				.item {
					font-weight: 400;
					font-size: 30rpx;
					color: #3D3D3D;

					.bot {
						margin-top: 20rpx;
					}
				}
			}

			.operation_box {
				margin-top: 30rpx;

				.left {
					overflow: hidden;
					position: relative;
					width: 338rpx;
					height: 341rpx;
					background: #E1F2FA;
					border-radius: 16rpx 16rpx 16rpx 16rpx;

					.img {
						width: 100%;
						height: 100%;
						position: absolute;
						top: 0;
						left: 0;
					}

					.title_box {
						margin-top: 48rpx;
						margin-left: 24rpx;

						.title {
							font-weight: 400;
							font-size: 33rpx;
							color: #3D3D3D;
						}

						.small_title {
							margin-top: 20rpx;
							font-weight: 400;
							font-size: 20rpx;
							color: #797473;
						}
					}
				}

				.right {
					margin-left: 15rpx;

					.img {
						width: 110rpx;
						height: 110rpx;
						position: absolute;
						right: 10rpx;
						bottom: 10rpx;
					}

					.title_box {
						margin-top: 26rpx;
						margin-left: 24rpx;

						.title {
							font-weight: 400;
							font-size: 24rpx;
							color: #3D3D3D;
						}

						.small_title {
							margin-top: 12rpx;
							font-weight: 400;
							font-size: 20rpx;
							color: #797473;
						}
					}

					.top {
						position: relative;
						width: 338rpx;
						height: 159rpx;
						background: #DAF3EF;
						border-radius: 16rpx 16rpx 16rpx 16rpx;
					}

					.bottom {
						position: relative;
						width: 338rpx;
						height: 159rpx;
						background: #ECECFA;
						border-radius: 16rpx 16rpx 16rpx 16rpx;
					}
				}
			}
		}

		.menu {
			margin-top: 30rpx;

			.item {
				background-color: #FFFFFF;
				padding: 30rpx;

				.icon {
					width: 32rpx;
					height: 32rpx;
				}

				.text {
					margin-left: 30rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
				}

				.go {
					width: 16rpx;
					height: 30rpx;
					margin-left: auto;
				}
			}
		}
	}
</style>