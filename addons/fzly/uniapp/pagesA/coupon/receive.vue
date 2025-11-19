<template>
	<view class="content font_family">
		<view class="tabs">
			<u-tabs :list="tabs" lineColor='#FFE706' @click="click"></u-tabs>
		</view>
		<view class="item_box">
			<view class="item" v-for="(item,index) in list" :key="index">
				<view class="top f">
					<view class="left">
						<view class="left_big">
							￥{{item.used_amount}}
						</view>
						<view class="left_small">
							满{{item.with_amount}}元可使用
						</view>
						<view class="yuan">

						</view>
					</view>
					<view class="right f_j">
						<view class="text">
							<view class="big_text">
								{{item.title}}
							</view>
							<view class="small_text">
								有效期：{{item.draw_range}}
							</view>
						</view>
						<!-- <view class="icon f_zj">
							<image class="img" src="../../static/public/coupon_g.png" mode=""></image>
						</view> -->
					</view>
				</view>
				<view class="bottom">
					<view class="title f_z_b f_j" @click="see_rule(index)">
						<text class="text">使用规则</text>
						<image v-if="coupon_index===index" class="icon" src="../../static/public/screen_up.png" mode="">
						</image>
						<image v-else class="icon" src="../../static/public/screen_down.png" mode=""></image>
					</view>
					<view class="rules" v-if="coupon_index===index">
						{{item.remarks}}
					</view>
				</view>
				<view class="btn_box f">
					<view class="btn" v-if="item.status==1" @click="receive(item.id)">
						领取
					</view>
					<view class="btn" v-if="item.status==0">
						已领取
					</view>
					<view class="btn no" v-if="item.status==-1">
						不可领取
					</view>
				</view>
			</view>
		</view>
		<!-- 加载更多 -->
		<u-loadmore v-if="list.length>0" line @loadmore='get_list' :status="status" :loading-text="loadingText"
			:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="/static/public/kong.png">
			</u-empty>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { coupon_list, draw } from '@/api/user.js'
	export default {
		data() {
			return {
				coupon_index: '',
				query_data: {
					page: 1,
					status: 1,
				},
				tabs: [
					{ name: '可领取', status: 1 },
					{ name: '不可领取', status: -1 },
				],
				list: [],
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~',
			}
		},
		onLoad() {
			this.get_list()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_list()
			}
		},
		methods: {
			click(e) {
				// console.log(e)
				this.query_data.page = 1
				this.list = []
				this.query_data.status = this.tabs[e.index].status
				this.get_list()
			},
			see_rule(index) {
				if (index === this.coupon_index) {
					this.coupon_index = ''
				} else {
					this.coupon_index = index
				}
			},
			get_list() {
				this.status = 'loading'
				coupon_list(this.query_data).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.list = [...this.list, ...res.data.data]
							this.status = 'loadmore'
							this.query_data.page += 1
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
			receive(id) {
				draw({ id }).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.$refs.uToast.show({
							type: 'success',
							message: '领取成功'
						})
						this.list = []
						this.query_data.page = 1
						this.get_list()
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		box-sizing: border-box;

		.tabs {
			background-color: #FFFFFF;
		}

		.item_box {
			padding: 40rpx;
		}

		.item {
			background-color: #FFFFFF;
			border-radius: 10rpx;
			overflow: hidden;
			margin-bottom: 30rpx;

			.top {
				padding: 30rpx 20rpx;
				border-left: 8rpx solid #FFE706;
				border-bottom: 1rpx dashed #979797;

				.left {
					border-right: 1rpx dashed #979797;
					padding-right: 20rpx;
					position: relative;

					.yuan {
						position: absolute;
						right: -18rpx;
						top: -48rpx;
						width: 36rpx;
						height: 36rpx;
						border-radius: 50%;
						background-color: #F8F9FC;
						// background-color: #FF5900;
					}

					.left_big {
						font-weight: 500;
						font-size: 52rpx;
						color: #FF5900;
					}

					.left_small {
						font-weight: 400;
						font-size: 24rpx;
						color: #ED5C18;
						text-align: center;
					}
				}

				.right {
					flex: 1;
					padding-left: 20rpx;

					.text {
						.big_text {
							font-weight: 500;
							font-size: 32rpx;
							color: #242424;
						}

						.small_text {
							font-weight: 400;
							font-size: 24rpx;
							color: #6B6B6B;
							margin-top: 20rpx;
						}
					}

					.icon {
						margin-left: auto;
						width: 39rpx;
						height: 39rpx;
						background: #FFE706;
						border-radius: 50%;

						.img {
							width: 13rpx;
							height: 11rpx;
						}
					}
				}
			}

			.bottom {
				padding: 20rpx 32rpx;

				.title {
					.text {
						font-weight: 400;
						font-size: 24rpx;
						color: #949494;
					}

					.icon {
						width: 12rpx;
						height: 6rpx;
					}
				}

				.rules {
					margin-top: 20rpx;
					font-weight: 400;
					font-size: 24rpx;
					// color: #949494;
				}
			}

			.btn_box {
				padding: 20rpx;
				justify-content: flex-end;

				.btn {
					padding: 10rpx 40rpx;
					background: #FFE706;
					border-radius: 574rpx 574rpx 574rpx 574rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
				}

				.no {
					background-color: rgba(102, 102, 102, 0.1) !important;
				}
			}

		}
	}
</style>