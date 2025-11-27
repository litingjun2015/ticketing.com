<template>
	<view class="content font_family" v-if="detail">
		<view class="header">
			<view class="back f_zj" @click="back" :style="{top:menuButtonInfo+'px'}">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_w.png" mode=""></image>
			</view>
			<u-swiper height="334rpx" :list="detail.images"></u-swiper>
		</view>
		<!-- 内容 -->
		<view class="content_box">
			<!-- 标题 -->
			<view class="title">
				<view>{{detail.title}}</view>
				<view class="address">{{detail.address_xx}}</view>
				<view class="jl f_j">
					<image class="address_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/pt_h.png" mode=""></image>
					<text class="text">距您{{detail.distance | conversion_km}}千米</text>
				</view>
			</view>
			<!-- 服务保障 -->
			<view class="guarantee">
				<!-- 预定 -->
				<view class="reserve f">
					<view class="guarantee_title">
						预定
					</view>
					<view class="reserve_box f">
						<view class="item f_j" v-for="(item,index) in detail.multiplejson_yd" :key="index">
							<image class="img" :src="item.icon" mode=""></image>
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
							<!-- <view class="line">

							</view> -->
						</view>
					</view>
				</view>
			</view>
			<!-- 商品内容 -->
			<view class="commodity_des">
				<view class="des_title">
					卖点-设施
				</view>
				<view class="item" v-for="(item,index) in detail.multiplejson_md" :key="index">
					<text>{{item.label}}</text>
					<text>{{item.intro}}</text>
				</view>
				<!-- 图片 -->
				<view class="">
					<view class="imgs_title">
						图片
					</view>
					<img class="imgs" :src="item" alt="" v-for="(item,index) in detail.images" :key="index">
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { house_detail } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				time_action: '',
				car_action: '',
				hours_action: '',
				id: '',
				detail: ''
			};
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		onLoad(e) {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.id = e.id
			this.get_hotels_detail()
		},
		methods: {
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// 获取酒店详情
			get_hotels_detail() {
				house_detail({ id: this.id }).then(res => {
					this.detail = res.data
				})
			}
		},
	}
</script>

<style lang="scss">
	.content {
		.header {
			width: 100%;
			height: 334rpx;
			position: relative;
			// background-image: url(https://img1.baidu.com/it/u=1678477941,1592983849&fm=253&fmt=auto&app=138&f=JPEG?w=800&h=500);
			// background-position: center;
			// background-repeat: no-repeat;
			// background-size: 100%;
			// padding-left: 30rpx;

			.back {
				position: absolute;
				left: 40rpx;
				width: 59rpx;
				height: 59rpx;
				background: rgba(0, 0, 0, 0.4);
				border-radius: 50%;
				z-index: 99999;

				.icon {
					width: 17rpx;
					height: 29rpx;
				}
			}
		}

		.content_box {
			padding: 32rpx;
			padding-bottom: 212rpx;

			.action {
				background: rgba(35, 125, 203, 0.1) !important;
				color: #237DCB !important;
			}

			.title {
				padding: 20rpx;
				background: #FFFFFF;
				border-radius: 12rpx 12rpx 12rpx 12rpx;
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				line-height: 44rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;

				.address {
					margin-top: 10rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #666666;
					font-style: normal;
					text-transform: none;
				}

				.jl {
					margin-top: 15rpx;

					.address_icon {
						width: 24rpx;
						height: 32rpx;
					}

					.text {
						margin-left: 20rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #999999;
						line-height: 34rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}
				}
			}

			.guide_box {
				margin-top: 30rpx;
				background: #FFFFFF;
				border-radius: 12rpx 12rpx 12rpx 12rpx;
				padding: 20rpx 0rpx;
				padding-left: 40rpx;

				.right {
					margin-left: 20rpx;

					.name {
						font-weight: 400;
						font-size: 32rpx;
						color: #3D3D3D;
						line-height: 44rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.des {
						font-weight: 400;
						font-size: 28rpx;
						color: #666666;
						line-height: 40rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;

						.line {
							height: 26rpx;
							border-left: 1rpx solid #EAEAEA;
							margin: 0rpx 12rpx;
						}
					}

					.mar {
						margin: 0rpx 12rpx;
					}
				}
			}


			.check_box {
				margin-top: 30rpx;
				background: #FFFFFF;
				border-radius: 12rpx 12rpx 12rpx 12rpx;
				padding: 20rpx 40rpx;
				padding-right: 10rpx;

				.title {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					line-height: 44rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;

				}

				.time_box {
					margin-top: 10rpx;

					.item_box {
						padding: 10rpx 0rpx;
						width: 550rpx;
						white-space: nowrap;
						// border: 1rpx solid red;

						.item {
							display: inline-block;
							padding: 10rpx 20rpx;
							background: #F8F8F8;
							border-radius: 4rpx 4rpx 4rpx 4rpx;
							font-weight: 400;
							font-size: 20rpx;
							color: #3D3D3D;
							line-height: 28rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
							margin-right: 10rpx;

							.week {
								margin-bottom: 10rpx;
							}

							.day {
								margin-bottom: 10rpx;
							}

							.price {}
						}
					}

					.more {
						padding: 0rpx 10rpx;
						margin-left: 10px;
						font-weight: 400;
						font-size: 22rpx;
						color: #3D3D3D;
						line-height: 30rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}
				}
			}

			.car {
				margin-top: 15rpx;

				.car_title {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					line-height: 44rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.item_box {
					flex-wrap: wrap;
					margin-top: 10rpx;

					.item {
						padding: 8rpx 20rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #3D3D3D;
						line-height: 34rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
						background: #F8F8F8;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						margin-right: 10rpx;
						margin-bottom: 10rpx;
					}
				}
			}

			.time {
				// margin-top: 15rpx;

				.time_title {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					line-height: 44rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.item_box {
					margin-top: 10rpx;
					flex-wrap: wrap;

					.item {
						padding: 8rpx 20rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #3D3D3D;
						line-height: 34rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
						background: #F8F8F8;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						margin-right: 10rpx;
						margin-bottom: 10rpx;
					}
				}
			}

			.guarantee {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 22rpx 40rpx;
				margin-top: 30rpx;

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
							}

							.text {
								font-weight: 400;
								font-size: 24rpx;
								color: #232323;
								line-height: 44rpx;
								text-align: center;
								font-style: normal;
								text-transform: none;
								margin-left: 10rpx;
							}
						}
					}
				}

				.protect {
					// margin-bottom: 20rpx;

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

			.commodity_des {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 22rpx 40rpx;
				margin-top: 30rpx;

				.des_title {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
				}

				.item {
					font-weight: 400;
					font-size: 24rpx;
					color: #666666;
					margin-top: 12rpx;
				}

				.imgs_title {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					margin-top: 20rpx;
				}

				.imgs {
					width: 100%;
					vertical-align: bottom;
					margin-top: 20rpx;
				}
			}

			.cost_description {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 22rpx 40rpx;
				margin-top: 30rpx;

				.cost_title {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					line-height: 44rpx;
					font-style: normal;
					text-transform: none;
				}

				.little_title {
					font-weight: 400;
					font-size: 24rpx;
					color: #3D3D3D;
					line-height: 44rpx;
					font-style: normal;
					text-transform: none;
				}

				.description {
					font-weight: 400;
					font-size: 24rpx;
					color: #3D3D3D;
					line-height: 44rpx;
					font-style: normal;
					text-transform: none;
				}
			}

			.returns {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 22rpx 40rpx;
				margin-top: 30rpx;

				.return_title {
					font-weight: 400;
					font-size: 32rpx;
					color: #232323;
					line-height: 44rpx;
					font-style: normal;
					text-transform: none;
				}

				.return_little_title {
					font-weight: 400;
					font-size: 24rpx;
					color: #666666;
					line-height: 44rpx;
					font-style: normal;
					text-transform: none;
				}

				.return_des {
					font-weight: 400;
					font-size: 24rpx;
					color: #666666;
					line-height: 44rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.return_table {
					margin-top: 10rpx;

					.table_header {
						background: #F8F9FC;
						border: 1rpx solid #F0F4FF;

						.left {
							padding: 10rpx 20rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #232323;
							line-height: 44rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.right {
							width: 150rpx;
							padding: 10rpx 20rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #232323;
							line-height: 44rpx;
							text-align: center;
							font-style: normal;
							text-transform: none;
							border-left: 1rpx solid #F0F4FF;
						}
					}

					.retuen_item {
						border: 1rpx solid #F0F4FF;
						border-top: none;

						.left {
							padding: 10rpx 20rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #666666;
							line-height: 44rpx;
							font-style: normal;
							text-transform: none;
						}

						.right {
							width: 150rpx;
							padding: 10rpx 20rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #666666;
							line-height: 44rpx;
							text-align: center;
							font-style: normal;
							text-transform: none;
							border-left: 1rpx solid #F0F4FF;
						}
					}
				}
			}
		}

		.bottom {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 182rpx;
			background: #FFFFFF;
			box-shadow: 0rpx 8rpx 20rpx 0rpx rgba(0, 0, 0, 0.1);
			border-radius: 0rpx 0rpx 0rpx 0rpx;
			padding: 0rpx 22rpx 0rpx 42rpx;
			box-sizing: border-box;

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
				font-weight: 400;
				font-size: 28rpx;
				color: #232323;
				line-height: 33rpx;
				font-style: normal;
				text-transform: none;
				padding: 28rpx 120rpx;
				background: #FFE706;
				border-radius: 96rpx 96rpx 96rpx 96rpx;
			}
		}
	}
</style>