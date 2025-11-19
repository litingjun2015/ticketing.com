<template>
	<view class="content font_family">
		<!-- tabs -->
		<view class="tabs" :style="{paddingTop:menuButtonInfo+'px'}">
			<u-tabs :list="tablist" lineColor='#FFAE35' :current='tabcurrent' @click="click"></u-tabs>
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
								￥{{item.price}}
							</view>
						</view>
						<view class="tag f_z_b">
							<view class="tag_box f f_w">
								<view v-if="item.order_type==1" class="tag_item">
									{{item.remark}}
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
						<text>￥{{item.price*item.count}}</text>
					</view>
					<view class="actual">
						<text>实付:</text>
						<text>￥{{item.order_amount_total}}</text>
					</view>
				</view>
				<!-- 按钮 -->
				<!-- <view class="btn_box f">
					<view class="cancel" @click.stop="sm" v-if="item.status==2">
						核销
					</view>
				</view> -->
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
		<u-tabbar :value="value" inactiveColor='#242424' activeColor='#FFAE35' :placeholder="true" :border="false"
			@change="name => value = name" :fixed="true" :safeAreaInsetBottom="true">
			<u-tabbar-item v-for="(item,index) in tabbar_list" :key="index"
				:icon="value==index?item.action_icon:item.icon" :text="item.text" @click='change'></u-tabbar-item>
		</u-tabbar>
	</view>
</template>

<script>
	import { guide_lists } from '@/api/admissionm.js'
	import { mapState } from 'vuex'
	import mixin from '../mixin/tabbar.js'
	export default {
		mixins: [mixin],
		computed: {
			...mapState(['menuButtonInfo'])
		},
		data() {
			return {
				value: 2,
				tabcurrent: 0,
				tablist: [
					{ name: '全部', status: '' },
					{ name: '待核销', status: 2 },
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
			};
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_list()
			}
		},
		onLoad() {
			this.get_list()
		},
		methods: {
			sm() {
				uni.scanCode({
					success: (res) => {
						console.log(res)
					},
					fail: (res) => {
						console.log(res)
					}
				})
			},
			click(index) {
				// console.log(index)
				this.list = []
				this.query_data.page = 1
				this.query_data.status = this.tablist[index.index].status
				this.tabcurrent = index.index
				this.get_list()
			},
			go_detail(item) {
				let detail = item.detail
				detail.tw_content = ''
				detail = JSON.stringify(detail)
				let travel = JSON.stringify(item.travel)
				uni.navigateTo({
					url: `/pagesC/order/order_detail?detail=${detail}&travel=${travel}&type=${item.order_type}&mp_gg=${item.remark}&status=${item.status}&order_no=${item.order_no}&pay_price=${item.order_amount_total}&time=${item.pays_time}`
				})
			},
			// 获取商家订单列表
			get_list() {
				this.status = 'loadmore'
				guide_lists(this.query_data).then(res => {
					if (res.code == 1) {
						if (res.data.data.data.length > 0) {
							this.list = [...this.list, ...res.data.data.data]
							this.query_data.page += 1
							this.status = 'loadmore'
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.tabs {
			background-color: #FFFFFF;
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
						background: #FFAE35;
						border-radius: 35rpx 35rpx 35rpx 35rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #FFFFFF;
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