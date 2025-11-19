<template>
	<view class="content font_family">
		<!-- tabs -->
		<view class="tabs">
			<u-tabs :list="tablist" lineColor='#FDDC05' :current='tabcurrent' @click="click"></u-tabs>
		</view>
		<!-- 列表 -->
		<view class="list" v-if="list.length>0">
			<view class="item" v-for="(item,index) in list" :key="index" @click="go_detail(item)">
				<view class="order_num f_z_b">
					<view class="order_left">
						<text>订单号:</text>
						<text>{{item.order_no}}</text>
					</view>
					<view class="order_right">
						<text>{{item.status | order_status}}</text>
					</view>
				</view>
				<view class="order_msg f">
					<view class="msg_left">
						<image class="img" :src="item.detail.image" mode="aspectFill"></image>
					</view>
					<view class="msg_right">
						<view class="title f_z_b f_j">
							<view class="text text_ellipsis">
								{{item.detail.title}}
							</view>
							<view class="price">
								￥{{item.price}}元
							</view>
						</view>
						<view class="tag f_z_b">
							<view class="tag_box f f_w">
								<view v-if="item.order_type==1" class="tag_item">
									{{item.remark}}
								</view>
								<view class="type_text" v-else>
									<view class="f_j">
										<image class="type_icon" src="../../static/public/checkmark_b.png" mode="">
										</image>
										<text v-if="item.yd_model!=null">{{item.yd_model}}</text>
										<text v-if="item.yd_dsj!=null">{{item.yd_dsj}}</text>
									</view>
									<view class="f_j">
										<image class="type_icon" src="../../static/public/date.png" mode=""></image>
										<text>{{item.yd_time}}</text>
									</view>
								</view>
							</view>
							<view class="num">
								x{{item.count}}
							</view>
						</view>
					</view>
				</view>
				<!-- 价格 -->
				<view class="bottom f">
					<view class="price">
						<text>总价:</text>
						<text>￥{{item.price*item.count}}元</text>
					</view>
					<view class="actual">
						<text>实付:</text>
						<text>￥{{item.order_amount_total}}元</text>
					</view>
				</view>
				<!-- 按钮 -->
				<view class="btn_box f">
					<view class="cancel" v-if="item.status==1" @click.stop="cancel_order(item.id)">
						取消订单
					</view>
					<view class="immediately" v-if="item.status==1">
						立即付款
					</view>
					<view class="immediately" v-if="item.status==2" @click.stop="return_price(item.id)">
						退款
					</view>
					<view class="evaluate"
						@click.stop="go('/pagesA/order/evaluate?id='+item.id+'&type='+item.order_type)"
						v-if="item.status==3 && item.is_pl!=1">
						去评价
					</view>
					<view class="evaluate" @click.stop="" v-if="item.status==3 && item.is_pl==1">
						已评价
					</view>
				</view>
			</view>
			<!-- 加载更多 -->
			<u-loadmore line @loadmore='get_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="/static/public/kong.png">
			</u-empty>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapMutations, mapState } from 'vuex'
	import { lists, cancel, refund } from '@/api/user.js'
	export default {
		computed: {
			...mapState(['user_info'])
		},
		data() {
			return {
				tabcurrent: 0,
				tablist: [
					{ name: '全部', status: 0 },
					{ name: '待付款', status: 1 },
					{ name: '已付款', status: 2 },
					{ name: '已核销', status: 3 },
					// { name: '待退款', status: 4 },
					{ name: '已取消', status: 5 },
				],
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~',
				query_data: {
					page: 1,
					status: ''
				},
				list: [],
			}
		},
		onLoad(e) {
			this.tabcurrent = e.type
			// this.get_list()
		},
		onShow() {
			this.list = []
			this.query_data.page = 1
			this.get_list()
		},
		watch: {

		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_list()
			}
		},
		methods: {
			...mapMutations(['set_travel_id', 'set_travel_people']),
			click(index) {
				// console.log(index)
				this.list = []
				this.query_data.page = 1
				this.tabcurrent = index.index
				this.get_list()
			},
			go(url) {
				uni.navigateTo({
					url
				})
			},
			get_list() {
				this.status = 'loading'
				this.query_data.status = this.tablist[this.tabcurrent].status
				lists(this.query_data).then(res => {
					console.log(res)
					if (res.code == 1) {
						if (res.data.data.data.length > 0) {
							this.list = [...this.list, ...res.data.data.data]
							this.query_data.page += 1
							this.status = 'loadmore'
						} else {
							this.status = 'nomore'
						}
					}
					console.log(this.list)
				})
			},
			// 取消订单
			cancel_order(id) {
				uni.showModal({
					title: '提示',
					content: '确认取消该订单吗？',
					success: (res) => {
						if (res.confirm) {
							cancel({ id }).then(res => {
								console.log(res)
								if (res.code == 1) {
									this.list = []
									this.query_data.page = 1
									this.$refs.uToast.show({
										type: 'success',
										message: '订单已取消'
									})
									this.get_list()
								}
							})
						} else if (res.cancel) {

						}
					}
				})
			},
			return_price(id) {
				uni.showModal({
					title: '提示',
					content: '是否确认退款？',
					success: (res) => {
						if (res.confirm) {
							refund({ id }).then(res => {
								if (res.code == 1) {
									this.$refs.uToast.show({
										type: 'success',
										message: '已申请退款，等待审核'
									})
									this.list = []
									this.query_data.page = 1
									this.get_list()
								}
							})
						} else if (res.cancel) {

						}
					}
				});

			},
			// 前往订单详情
			go_detail(item) {
				if (item.status == 2) {
					let detail = item
					detail.detail.tw_content = ''
					detail.detail.content = ''
					detail = JSON.stringify(detail)
					uni.navigateTo({
						url: '/pagesA/order/write_off?detail=' + encodeURIComponent(detail)
					})
					return
				} else if (item.status == 1) {
					let obj = {}
					// 判断订单类型
					if (item.order_type == 1) {
						// 门票类型
						obj.intro = item.remark
						obj.price = item.price
					} else {
						obj.date = item.yd_time
						obj.time = item.yd_dsj
						obj.car = item.yd_model
					}
					let detail = item
					detail.detail.tw_content = ''
					detail.detail.content = ''
					detail = JSON.stringify(detail.detail)
					let obj2 = JSON.stringify(obj);
					// 该订单是否使用优惠券
					let coupon = ''
					if (item.coupon) {
						coupon = JSON.stringify(item.coupon)
					} else {
						coupon = 'no_use'
					}
					// 将出行人信息写入vuex
					let travel_id = []
					item.travel.forEach(item => {
						travel_id.push(item.travel_id)
					})
					this.set_travel_id(travel_id)
					this.set_travel_people(item.travel)
					uni.navigateTo({
						url: '/pagesA/index_menu/order_buy?again=again&type=' + item.order_type + '&detail=' +
							encodeURIComponent(detail) +
							'&obj=' + obj2 + '&order_no=' + item.order_no + '&coupon=' + coupon
					})
				} else {
					console.log(item)
					let type = item.order_type
					let id = item.id
					let status = item.status
					let order_no = item.order_no
					let price = item.price
					let msg = item.detail
					let pays_time = item.pays_time
					let is_pl = item.is_pl
					msg.tw_content = ''
					msg.content = ''
					msg = JSON.stringify(msg)
					uni.navigateTo({
						url: `/pagesA/order/complete?type=${type}&status=${status}&msg=${msg}&order_no=${order_no}&price=${price}&pays_time=${pays_time}&id=${id}&is_pl=${is_pl}`
					})
				}
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.tabs {
			background-color: #FFFFFF;

			::v-deep.u-tabs__wrapper__nav__item {
				flex: 1;
			}
		}

		.list {
			padding: 30rpx;

			.item {
				background: #FFFFFF;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				padding: 28rpx;
				margin-bottom: 30rpx;

				.order_num {
					.order_left {
						font-weight: 400;
						font-size: 22rpx;
						color: #333333;
					}

					.order_right {
						font-weight: 400;
						font-size: 22rpx;
						color: #999999;
					}
				}

				.order_msg {
					margin-top: 20rpx;

					.msg_left {
						.img {
							width: 126rpx;
							height: 126rpx;
							border-radius: 8rpx 8rpx 8rpx 8rpx;
						}
					}

					.msg_right {
						margin-left: 16rpx;
						flex: 1;

						.title {
							.text {
								width: 350rpx;
								// border: 1rpx solid red;
								font-weight: 400;
								font-size: 26rpx;
								color: #000000;
							}

							.price {
								font-weight: 400;
								font-size: 26rpx;
								color: #232323;
							}
						}

						.tag {

							.tag_box {
								width: 350rpx;

								.tag_item {
									margin-right: 10rpx;
									font-weight: 400;
									font-size: 20rpx;
									color: #FDDC05;
									border-radius: 4rpx 4rpx 4rpx 4rpx;
									border: 2rpx solid #FDDC05;
									padding: 5rpx 14rpx;
									margin-top: 10rpx;
								}

								.type_text {
									margin-top: 10rpx;
									font-weight: 400;
									font-size: 24rpx;

									.type_icon {
										margin-right: 10rpx;
										width: 24rpx;
										height: 24rpx;
									}
								}
							}

							.num {
								font-weight: 400;
								font-size: 26rpx;
								color: #232323;
							}
						}
					}
				}

				.bottom {
					justify-content: flex-end;
					margin-top: 30rpx;
					border-top: 1rpx solid #EBEBEB;
					padding-top: 30rpx;

					.price {
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
					}

					.actual {
						font-weight: 400;
						font-size: 24rpx;
						color: #000000;
						margin-left: 30rpx;
					}
				}

				.btn_box {
					justify-content: flex-end;
					margin-top: 30rpx;

					.cancel {
						padding: 18rpx 44rpx;
						background: rgba(102, 102, 102, 0.1);
						border-radius: 35rpx 35rpx 35rpx 35rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
					}

					.immediately {
						padding: 18rpx 44rpx;
						background: #FDDC05;
						border-radius: 35rpx 35rpx 35rpx 35rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #232323;
						margin-left: 30rpx;
					}

					.evaluate {
						padding: 18rpx 44rpx;
						background: #232323;
						border-radius: 35rpx 35rpx 35rpx 35rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #FFFFFF;
						margin-left: 30rpx;
					}
				}
			}
		}
	}
</style>