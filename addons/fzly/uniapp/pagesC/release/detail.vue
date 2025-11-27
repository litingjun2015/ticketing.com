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
				<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_w.png" mode=""></image>
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
							<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/pt_h.png" mode=""></image>
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
					</view>
				</view>
			</view>
			<!-- 占位高度 -->
		</view>
		<view class="bottom_box f_j ">
			<view class="btn btn1 f_zj" @click.stop="up_down(2,detail.id)" v-if="detail.status==4">
				上架
			</view>
			<view class="btn btn1 f_zj" @click.stop="up_down(4,detail.id)" v-if="detail.status==2">
				下架
			</view>
			<view class="btn btn1 f_zj" @click.stop="up_down(4,detail.id)" v-if="detail.status==1">
				审核中
			</view>
			<view class="btn btn2 f_zj" @click="open()">
				价格
			</view>
			<view class="btn btn3 f_zj" @click.stop="go_set(`/pagesB/product/product?id=${detail.id}`)">
				编辑
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
		onShow() {
			this.get_ticket_detail()
		},
		onLoad(e) {
			// this.evaluate_all = [true, true]
			this.query.id = e.id

		},
		onReady() {
			// console.log(uni.$u, '===========');

			// const query = uni.createSelectorQuery().in(this);
			// query.select(".bottom_box").boundingClientRect((data) => {
			// 		console.log("得到布局位置信息", data);
			// 		console.log("节点离页面顶部的距离为" + data.top);
			// 	})
			// 	.exec();
			this.$nextTick(() => {
				uni.$u.getRect('.bottom_box').then(res => {
					console.log(res, '===========');
				})
			})
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
			// min-height: 100vh;
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

		.bottom_box {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			// height: 88rpx;
			background: #FFFFFF;
			box-shadow: 0rpx 8rpx 20rpx 0rpx rgba(0, 0, 0, 0.1);
			border-radius: 0rpx 0rpx 0rpx 0rpx;
			padding: 0rpx 22rpx 0rpx 42rpx;
			box-sizing: border-box;
			padding-bottom: calc(20rpx + env(safe-area-inset-bottom) /2);
			padding-top: 20rpx;
			justify-content: flex-end;

			.price {
				font-weight: 400;
				color: #FF372F;
				font-style: normal;
				text-transform: none;

				.text1 {
					font-size: 28rpx;
				}

				.text2 {
					font-size: 44rpx;
				}
			}

			.btn {
				width: 140rpx;
				height: 52rpx;
				border-radius: 956rpx 956rpx 956rpx 956rpx;
				font-weight: 400;
				font-size: 24rpx;
				margin-left: 20rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #FFFFFF;
			}

			.btn1 {
				background: #E4E3E3;
				font-weight: 400;
				font-size: 24rpx;
				color: #999999;
			}

			.btn2 {
				background: #232323;
			}

			.btn3 {
				background: #FFAE35;
			}
		}
	}
</style>