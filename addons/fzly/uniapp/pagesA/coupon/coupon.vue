<template>
	<view class="content font_family">
		<view class="item" v-for="(item,index) in list" :key="index">
			<view class="top f">
				<view class="left">
					<view class="left_big">
						￥{{item.coupon_used_amount}}
					</view>
					<view class="left_small">
						满{{item.coupon_with_amount}}元可使用
					</view>
					<view class="yuan">

					</view>
				</view>
				<view class="right f_j">
					<view class="text">
						<view class="big_text">
							{{item.coupon_title}}
						</view>
						<view class="small_text">
							有效期：{{item.draw_range}}
						</view>
					</view>
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
					{{item.coupon_remarks}}
				</view>
			</view>
			<view class="btn_box f">
				<view class="btn" v-if="item.state==0" @click="go_use('/pagesA/index_menu/tickets')">
					去使用
				</view>
			</view>
			<!-- 标记 -->
			<view class="tag">
				<image v-if="item.state==-1" class="img" src="../../static/public/ygq.png" mode=""></image>
				<image v-if="item.state==1" class="img" src="../../static/public/ysy.png" mode=""></image>
				<image v-if="item.state==0" class="img" src="../../static/public/wsy.png" mode=""></image>
			</view>
		</view>
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="/static/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { me_coupon_list } from '@/api/user.js'
	export default {
		data() {
			return {
				coupon_index: '',
				query_data: {
					page: 1,
				},
				list: [],
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
			see_rule(index) {
				if (index === this.coupon_index) {
					this.coupon_index = ''
				} else {
					this.coupon_index = index
				}
			},
			get_list() {
				me_coupon_list(this.query_data).then(res => {
					if (res.code == 1) {
						this.list = res.data
						console.log(this.list)
					}
				})
			},
			go_use(url) {
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 40rpx;
		box-sizing: border-box;

		.item {
			background-color: #FFFFFF;
			border-radius: 10rpx;
			overflow: hidden;
			margin-bottom: 30rpx;
			position: relative;

			.tag {
				position: absolute;
				top: 0;
				right: 0;

				.img {
					width: 140rpx;
					height: 122rpx;
				}
			}

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