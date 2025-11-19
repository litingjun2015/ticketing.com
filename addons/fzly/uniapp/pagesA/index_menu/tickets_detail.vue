<template>
	<view class="content font_family" v-if="detail">
		<view class="header">
			<u-swiper height="439rpx" :list="detail.images" :circular='true'
				@change="e => currentNum = e.current"></u-swiper>
			<!-- 指示器 -->
			<view class="indicator">
				{{ currentNum + 1 }}/{{ detail.images.length }}
			</view>
			<view class="back f_zj" :style="{top:menuButtonInfo+'px'}" @click="back">
				<image class="img" src="../../static/public/back_w.png" mode=""></image>
			</view>
		</view>
		<view class="msg_box">
			<view class="basis">
				<view class="top f_z_b">
					<view class="left">
						<view class="title">
							{{detail.title}}
						</view>
						<view class="pt f_j" @click="go_here">
							<image class="icon" src="../../static/public/pt_h.png" mode=""></image>
							<view class="text" style="width: 400rpx;">{{detail.address_xx}}</view>
						</view>
					</view>
					<view class="right">
						<view class="statr f_zj">
							{{detail.score}}
						</view>
						<view class="num f_zj">
							{{detail.common_nums | num}}人评论
						</view>
					</view>
				</view>
				<view class="bottom f_z_b">
					<view class="left">
						每天{{detail.open_times}}-{{detail.end_times}}开放
					</view>
					<!-- <view class="right">
						简介/攻略>
					</view> -->
				</view>
			</view>
			<!-- 服务保障 -->
			<view class="guarantee">
				<!-- 优惠 -->
				<view class="protect f" @click="go('/pagesA/coupon/receive')" v-if="detail.is_y==1">
					<view class="guarantee_title">
						优惠
					</view>
					<view class="protect_box f">
						<view class="item f_j">
							<view class="text" style="color:#FF5151">
								您有活动优惠券待领取
							</view>
						</view>
					</view>
				</view>
				<!-- 预定 -->
				<view class="reserve f">
					<view class="guarantee_title">
						预定
					</view>
					<view class="reserve_box f">
						<view class="item f_j" v-for="(item,index) in detail.yd_multiplejson" :key="index">
							<image v-if="item.icon" class="img" :src="item.icon" mode=""></image>
							<text class="text">{{item.intro}}</text>
						</view>
					</view>
				</view>
				<!-- 保障 -->
				<view class="protect f">
					<view class="guarantee_title">
						保障
					</view>
					<view class="protect_box f">
						<view class="item f_j">
							<view class="text">
								{{detail.guarantee}}
							</view>
						</view>
					</view>
				</view>
			</view>
			<!-- 门票 -->
			<more title='门票' :tomore='false'></more>
			<!-- 特价团购 -->
			<view :class="{no_special:item.tj!=1}" class="special" v-for="(item,index) in detail.mp_multiplejson"
				:key="index">
				<view class="title" v-if="item.tj==1">
					特价团购
				</view>
				<view class="card">
					<view class="card_title">
						{{item.intro}}
					</view>
					<view class="tag f">
						<view class="item">
							{{item.label}}
						</view>
					</view>
					<view class="rule">
						{{item.text}}
					</view>
					<!-- <view class="notice">
						<text class="color_l">有效期至8月30日</text>
						<text class="">随时退</text>
						<text class="">购票须知></text>
					</view> -->
					<view class="card_price f_z_b">
						<view class="left">
							<text class="icon">￥</text>
							<text class="price">{{item.price}}</text>
							<text class="old_price">￥{{item.line_price}}</text>
							<text class="num">已售{{item.ys | num}}+</text>
						</view>
						<view class="right f_zj" v-if="common.mp_switch==0">
							仅供参考
						</view>
						<view class="right f_zj" @click="go_order(item)" v-else>
							抢购
						</view>
					</view>
				</view>
			</view>
			<!-- 简介 -->
			<more title='简介' :tomore='false'></more>
			<view class="Introduction">
				<u-parse :content="detail.content"></u-parse>
			</view>
			<!-- 猜你想去 -->
			<more title='猜你想去' :tomore='false'></more>
			<scroll-view class="scroll_box" scroll-x="true">
				<view class="item" v-for="(item,index) in guess_like_list" :key="index"
					@click="go_red('/pagesA/index_menu/tickets_detail?id='+item.id)">
					<view class="img_box">
						<image class="img" :src="item.image" mode="aspectFill">
						</image>
						<!-- 推荐tag -->
						<view class="tag f_zj">
							推荐
						</view>
					</view>
					<view class="title">
						{{item.title}}
					</view>
					<view class="price">
						<text class="text1">￥</text>
						<text class="text2">{{item.lowest_price}}</text>
						<text class="text3">起</text>
					</view>
				</view>
			</scroll-view>
			<!-- 用户评价 -->
			<more title='用户评价' :tomore='true' moretitle='全部评论' :url='go_url'></more>
			<view class="user_evaluate">
				<view class="evaluate_tag f" v-if="comment_list.length!=0">
					<view class="item" v-for="(item,index) in tag_list" :key="index">
						<text>{{item.name}}</text>
						<text class="text_msr">{{item.num}}</text>
					</view>
				</view>
				<view class="user_list" v-for="(item,index) in comment_list" :key="index">
					<view class="user_msg f">
						<u-avatar size="68rpx" :src="item.avatar" mode="aspectFill"></u-avatar>
						<view class="name_box">
							<view class="name">
								{{item.username}}
							</view>
							<view class="time">
								{{item.creattime}}
							</view>
						</view>
					</view>
					<view class="evaluate_text " :class="{text_ellipsis3:evaluate_all!==index}">
						{{item.evaluate}}
					</view>
					<view class="all_text" @click="set_all(index)">
						{{evaluate_all!==index?'全文':'收起'}}
					</view>
					<view class="img_arr f">
						<image v-for="(img,img_index) in item.images" :key="img_index" class="img_item" :src="img"
							mode="aspectFill"></image>
					</view>
				</view>
				<!-- 空 -->
				<u-empty v-if="comment_list.length==0" text='当前没有评论哟~' icon="/static/public/kong.png">
				</u-empty>
			</view>
		</view>
	</view>
</template>

<script>
	import { ticket_detail, guess_like, admission_comment } from '@/api/index_menu/index.js'
	import more from '@/components/more.vue'
	import { mapState } from 'vuex'
	export default {
		components: {
			more
		},
		computed: {
			...mapState(['menuButtonInfo', 'common'])
		},
		data() {
			return {
				currentNum: 0,
				list1: [],
				evaluate_all: '',
				detail: '',
				query: {
					id: ''
				},
				guess_like_list: [],
				comment_list: [],
				go_url: '',
				tag_list: []
			};
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		onShow() {
			this.get_ticket_detail()
			this.get_guess_like()
			this.get_user_comment()
		},
		onLoad(e) {
			// this.evaluate_all = [true, true]
			this.query.id = e.id
		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			},
			go_red(url) {
				uni.redirectTo({
					url
				})
			},
			set_all(index) {
				console.log(index)
				if (this.evaluate_all === index) {
					this.evaluate_all = ''
				} else {
					this.evaluate_all = index
				}
				// this.$forceUpdate()
			},
			// 获取门票详情
			get_ticket_detail() {
				ticket_detail(this.query).then(res => {
					if (res.code == 1) {
						this.detail = res.data
						this.go_url =
							`/pagesA/all_evaluate/all_evaluate?id=${this.query.id}&start=${this.detail.score}`
					}
				})
			},
			// 猜你喜欢
			get_guess_like() {
				guess_like(this.query).then(res => {
					if (res.code == 1) {
						this.guess_like_list = res.data
					}
				})
			},
			// 获取用户评论
			get_user_comment() {
				let obj = {
					type: 1,
					id: this.query.id
				}
				admission_comment(obj).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.tag_list = res.data.tags
						this.comment_list = res.data.data.data
					}
				})
			},
			// 去支付
			go_order(item) {
				let detail = this.detail
				detail.content = ''
				detail = JSON.stringify(detail)
				// console.log(encodeURIComponent(detail))
				// console.log(decodeURIComponent(encodeURIComponent(detail)))
				let obj = JSON.stringify(item);
				uni.navigateTo({
					url: `/pagesA/index_menu/order_buy?type=1&detail=${encodeURIComponent(detail)}&obj=${obj}`
				})
			},
			// 导航
			go_here() {
				uni.openLocation({
					latitude: this.detail.lat * 1,
					longitude: this.detail.lon * 1,
					success: (res) => {
						console.log(res)
					},
					fail: (res) => {
						console.log(res)
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-bottom: 30rpx;

		.color_l {
			color: #26A4FF;
		}

		.header {
			position: relative;

			.back {
				position: absolute;
				left: 30rpx;
				width: 59rpx;
				height: 59rpx;
				background: rgba(0, 0, 0, 0.4);
				border-radius: 50%;

				.img {
					width: 17rpx;
					height: 29rpx;
				}
			}

			.indicator {
				position: absolute;
				bottom: 80rpx;
				right: 50rpx;
				padding: 4rpx 30rpx;
				background: rgba(35, 35, 35, 0.1);
				border-radius: 1300rpx 1300rpx 1300rpx 1300rpx;
				font-weight: 400;
				font-size: 20rpx;
				color: #FFFFFF;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}
		}

		.msg_box {
			position: relative;
			min-height: 100vh;
			background: #F4F6FC;
			border-radius: 24rpx 24rpx 0rpx 0rpx;
			margin-top: -60rpx;
			padding: 30rpx;

			.basis {
				padding: 20rpx;
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;

				.top {
					.left {
						.title {
							font-weight: 400;
							font-size: 32rpx;
							color: #000000;
							font-style: normal;
							text-transform: none;
							width: 550rpx;
						}

						.pt {
							margin-top: 10rpx;

							.icon {
								width: 16rpx;
								height: 20rpx;
								vertical-align: bottom;
							}

							.text {
								flex: 1;
								font-weight: 400;
								font-size: 22rpx;
								color: #666666;
								font-style: normal;
								text-transform: none;
								margin-left: 10rpx;
							}
						}
					}

					.right {

						.statr {
							font-weight: 600;
							font-size: 26rpx;
							color: #000000;
							font-style: normal;
							text-transform: none;
							background: #FFE706;
							padding: 5rpx 0rpx;
							border-radius: 10rpx 10rpx 0rpx 0rpx;
						}

						.num {
							font-weight: 400;
							font-size: 20rpx;
							color: #666666;
							font-style: normal;
							text-transform: none;
							background: #FFFBD2;
							padding: 4rpx 12rpx;
							border-radius: 0rpx 0rpx 10rpx 10rpx;
						}
					}
				}

				.bottom {
					margin-top: 20rpx;

					.left {
						font-weight: 400;
						font-size: 24rpx;
						color: #000000;
						line-height: 44rpx;
						text-align: center;
						font-style: normal;
						text-transform: none;
					}

					.right {
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
						line-height: 44rpx;
						text-align: center;
						font-style: normal;
						text-transform: none;
					}
				}
			}

			.guarantee {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 20rpx 20rpx;
				margin-top: 30rpx;
				padding-bottom: 1rpx;

				.guarantee_title {
					font-weight: 400;
					font-size: 24rpx;
					color: #666666;
					line-height: 44rpx;
					font-style: normal;
					text-transform: none;
					width: 100rpx;
				}

				.reserve {
					margin-bottom: 20rpx;

					.reserve_box {
						.item {
							margin-right: 20rpx;

							.img {
								width: 24rpx;
								height: 24rpx;
								margin-right: 10rpx;
							}

							.text {
								font-weight: 400;
								font-size: 24rpx;
								color: #232323;
								line-height: 44rpx;
								text-align: center;
								font-style: normal;
								text-transform: none;
							}
						}
					}
				}

				.protect {
					margin-bottom: 20rpx;

					.protect_box {
						.item {
							.text {
								font-weight: 400;
								font-size: 24rpx;
								color: #232323;
								line-height: 44rpx;
								text-align: center;
								font-style: normal;
								text-transform: none;
							}

							.line {
								margin: 0rpx 10rpx;
								width: 15rpx;
								height: 1rpx;
								border-top: 1rpx solid #232323;
							}
						}
					}
				}

				.phone {
					.phone_num {
						font-weight: 400;
						font-size: 24rpx;
						color: #232323;
						line-height: 44rpx;
						text-align: center;
						font-style: normal;
						text-transform: none;
					}
				}
			}

			.no_special {
				background: none !important;
			}

			.special {
				margin-top: 30rpx;
				width: 100%;
				background: #FFE706;
				border-radius: 20rpx 20rpx 20rpx 20rpx;

				.title {
					font-family: YouSheBiaoTiHei, YouSheBiaoTiHei;
					font-weight: 400;
					font-size: 36rpx;
					color: #000000;
					font-style: normal;
					text-transform: none;
					padding: 16rpx 28rpx;
				}

				.card {
					background: #FFFFFF;
					border-radius: 20rpx 20rpx 20rpx 20rpx;
					padding: 15rpx 20rpx;

					text {
						margin-right: 10rpx;
					}

					.card_title {
						font-weight: 400;
						font-size: 32rpx;
						color: #000000;
						font-style: normal;
						text-transform: none;
						margin-bottom: 15rpx;
					}

					.tag {
						margin-bottom: 15rpx;

						.item {
							font-weight: 400;
							font-size: 20rpx;
							color: #000000;
							background: #FFE706;
							border-radius: 108rpx 108rpx 108rpx 108rpx;
							margin-right: 15rpx;
							padding: 4rpx 10rpx;
						}
					}

					.rule {
						margin-bottom: 15rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
					}

					.notice {
						margin-bottom: 15rpx;
						font-weight: 400;
						font-size: 24rpx;
					}

					.card_price {
						margin-bottom: 15rpx;

						.left {
							.icon {
								font-size: 20rpx;
								color: #FF372F;
							}

							.price {
								font-size: 32rpx;
								color: #FF372F;
							}

							.old_price {
								font-weight: 400;
								font-size: 20rpx;
								color: #999999;
								text-decoration: line-through;
							}

							.num {
								font-weight: 400;
								font-size: 20rpx;
								color: #999999;
								margin-left: 30rpx;
							}
						}

						.right {
							padding: 6rpx 30rpx;
							background: #FFE706;
							border-radius: 36rpx 36rpx 36rpx 36rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #000000;
						}
					}
				}
			}

			.Introduction {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 20rpx;
				margin-top: 30rpx;
			}

			.scroll_box {
				margin-top: 30rpx;
				width: 100%;
				height: 100%;
				white-space: nowrap;

				.item {
					margin-right: 20rpx;
					width: 200rpx;
					background: #FFFFFF;
					border-radius: 12rpx 12rpx 12rpx 12rpx;
					overflow: hidden;
					display: inline-block;

					.img_box {
						position: relative;

						.img {
							vertical-align: bottom;
							width: 100%;
							height: 200rpx;
						}

						.tag {
							position: absolute;
							right: 0;
							top: 0;
							width: 82rpx;
							height: 33rpx;
							background: #F87415;
							border-radius: 0rpx 12rpx 0rpx 12rpx;
							font-weight: 400;
							font-size: 20rpx;
							color: #FFFFFF;
							line-height: 23rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}
					}

					.title {
						padding: 2rpx 10rpx;
						width: 100%;
						overflow: hidden;
						white-space: nowrap;
						text-overflow: ellipsis;
					}

					.price {
						margin-top: 6rpx;
						padding: 2rpx 10rpx;
						margin-bottom: 20rpx;
						font-family: PingFang SC, PingFang SC;
						font-weight: 400;
						font-size: 24rpx;
						color: #000000;
						line-height: 28rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;

						.text1 {}

						.text2 {
							font-size: 32rpx
						}

						.text3 {
							color: #999999;
							margin-left: 8rpx;
						}
					}
				}
			}

			.user_evaluate {
				margin-top: 26rpx;
				background: #FFFFFF;
				border-radius: 18rpx 18rpx 18rpx 18rpx;
				padding: 20rpx;

				.evaluate_tag {
					flex-wrap: wrap;
					font-weight: 400;
					font-size: 20rpx;
					color: #232323;

					.item {
						margin-right: 34rpx;
						padding: 15rpx 20rpx;
						background: #F4F4F4;
						border-radius: 12rpx 12rpx 12rpx 12rpx;
						margin-bottom: 15rpx;

						.text_msr {
							margin-left: 10rpx;
						}
					}
				}

				.user_list {
					margin-top: 50rpx;

					.user_msg {
						.name_box {
							margin-left: 15rpx;

							.name {
								font-weight: 400;
								font-size: 28rpx;
								color: #232323;
							}

							.time {
								font-weight: 400;
								font-size: 22rpx;
								color: #666666;
							}
						}
					}

					.evaluate_text {
						margin-top: 20rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #232323;
					}

					.all_text {
						margin-top: 20rpx;
						font-weight: 400;
						font-size: 20rpx;
						color: #26A4FF;
					}

					.img_arr {
						flex-wrap: wrap;

						.img_item {
							width: 153rpx;
							height: 153rpx;
							border-radius: 12rpx 12rpx 12rpx 12rpx;
							margin-right: 18rpx;
							margin-top: 18rpx;
						}
					}
				}
			}
		}

	}
</style>