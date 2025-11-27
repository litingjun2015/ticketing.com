<template>
	<view class="content font_family">
		<view class="header" :style="{paddingTop:menuButtonInfo+'px',backgroundImage:`url(${detail.back_avatar})`}">
			<view class="back f_zj" @click="back">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_w.png" mode=""></image>
			</view>
		</view>
		<!-- 导游信息 -->
		<view class="guide_msg" v-if="detail" :class="{guide_msg2:common.mp_switch==0}">
			<view class="top f">
				<view class="avatar">
					<u-avatar :src="detail.avatar" size="106rpx" mode="aspectFill"></u-avatar>
				</view>
				<view class="msg">
					<view class="name f">
						<text>{{detail.username}}</text>
						<button open-type="share" class="f_zj">
							<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/zf.png" mode=""></image>
						</button>
					</view>
					<view class="remarks f">
						<view class="f_j">
							<text class="text">实名认证</text>
							<!-- <view class="line" v-if="index+1!=3">
							</view> -->
						</view>
					</view>
				</view>
			</view>
			<view class="bottom " v-if="common.mp_switch==1">
				<view class="box f_z_b">
					<view class="item f_d f_j">
						<view class="text">
							月服务数
						</view>
						<view class="num">
							{{detail.month_servers}}
						</view>
					</view>
					<view class="item f_d f_j">
						<view class="text">
							好评率
						</view>
						<view class="num">
							{{detail.good_rate}}%
						</view>
					</view>
					<view class="item f_d f_j">
						<view class="text">
							入驻时间
						</view>
						<view class="num">
							{{detail.create_time}}
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list" v-if="detail.product">
			<view class="title">
				产品列表
			</view>
			<view class="item f" v-for="(item,index) in detail.product" :key="index" @click="go_order(item.id)">
				<view class="left">
					<image class="img" :src="item.image" mode="aspectFill"></image>
					<view class="tag">
						{{item.type_title}}
					</view>
				</view>
				<view class="item_right f_d f_z_b">
					<view class="title">
						{{item.title}}
					</view>
					<view class="label f">
						<view class="label_item" v-for="(items,indexs) in item.yd_multiplejson" :key="indexs">
							{{items.intro}}
						</view>
					</view>
					<view class="price f_z_b f_j">
						<view class="left">
							<text class="text">月销</text>
							<text>{{item.order_nums}}</text>
						</view>
						<view class="right">
							<text class="text1">￥</text>
							<text class="text2">{{item.price}}</text>
							<text class="text3">起</text>
						</view>
					</view>
				</view>
			</view>
			<view class="" v-if="detail.product.length==0">
				<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
				</u-empty>
			</view>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { guide_detail } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				id: '', //导游id
				detail: ''
			};
		},
		computed: {
			...mapState(['common'])
		},
		onShareAppMessage() {
			return {
				title: this.detail.username,
				imageUrl: detail.back_avatar,
			}
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
			this.get_detail()
		},
		methods: {
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			go_order(id) {
				uni.navigateTo({
					url: '/pagesA/index_menu/guide_order_detail?id=' + id
				})
			},
			// 获取导游详情
			get_detail() {
				guide_detail({ id: this.id }).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.detail = res.data
					}
				})
			}
		},
	}
</script>

<style lang="scss" scoped>
	.content {
		.header {
			width: 100%;
			height: 400rpx;
			background-image: url(https://img1.baidu.com/it/u=1678477941,1592983849&fm=253&fmt=auto&app=138&f=JPEG?w=800&h=500);
			background-position: center;
			background-repeat: no-repeat;
			background-size: 100%;
			padding-left: 30rpx;
			box-sizing: border-box;

			.back {
				width: 59rpx;
				height: 59rpx;
				background: rgba(0, 0, 0, 0.4);
				border-radius: 50%;

				.icon {
					width: 17rpx;
					height: 29rpx;
				}
			}
		}

		.guide_msg2 {
			height: 128rpx !important;
		}

		.guide_msg {
			position: relative;
			width: 100%;
			height: 228rpx;
			background: #FFFFFF;
			border-radius: 34rpx 34rpx 0rpx 0rpx;
			margin-top: -50rpx;

			.top {
				position: absolute;
				margin-left: 46rpx;

				.avatar {
					margin-top: -20rpx;
				}

				.msg {
					margin-left: 15rpx;
					align-self: flex-end;

					.name {
						font-weight: 400;
						font-size: 28rpx;
						color: #3D3D3D;
						line-height: 40rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
						margin-bottom: 8rpx;

						button {
							line-height: 0;
							padding: 0;
							background: none;
						}

						button::after {
							border: none;
						}

						.icon {
							margin-left: 15rpx;
							width: 22rpx;
							height: 22rpx;
						}
					}

					.remarks {
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
						line-height: 34rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;

						.line {
							height: 25rpx;
							border-left: 1rpx solid #666666;
							margin: 0rpx 10rpx;
						}
					}

				}
			}

			.bottom {
				padding: 0rpx 30rpx;
				padding-top: 110rpx;

				.box {
					justify-content: space-around;
					background: #F8F9FC;
					border-radius: 8rpx 8rpx 8rpx 8rpx;
					padding: 14rpx 0rpx;

					.item {
						.text {
							font-weight: 400;
							font-size: 22rpx;
							color: #999999;
							line-height: 30rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.num {
							font-weight: 400;
							font-size: 28rpx;
							color: #232323;
							line-height: 40rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
							margin-top: 5rpx;
						}
					}
				}
			}
		}

		.list {
			background-color: #FFFFFF;
			padding: 24rpx 30rpx;
			padding-bottom: 50rpx;
			margin-top: 30rpx;
			min-height: 60vh;

			.title {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				line-height: 44rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.item {
				padding: 20rpx 0rpx;
				border-bottom: 1rpx solid #EAEAEA;

				.left {
					position: relative;

					.img {
						width: 172rpx;
						height: 180rpx;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
					}

					.tag {
						padding: 5rpx;
						position: absolute;
						top: 0;
						left: 0;
						font-weight: 400;
						font-size: 20rpx;
						color: #FFFFFF;
						line-height: 28rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
						background: rgba(36, 36, 36, 0.4);
						border-radius: 8rpx 0rpx 8rpx 0rpx;
					}
				}

				.item_right {
					flex: 1;
					margin-left: 20rpx;

					.title {
						display: -webkit-box;
						-webkit-line-clamp: 2;
						-webkit-box-orient: vertical;
						overflow: hidden;
						text-overflow: ellipsis;
						font-weight: 400;
						font-size: 28rpx;
						color: #3D3D3D;
						line-height: 40rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.label {
						flex-wrap: wrap;

						.label_item {
							border-radius: 2rpx 2rpx 2rpx 2rpx;
							border: 2rpx solid #27ACFF;
							font-weight: 400;
							font-size: 20rpx;
							color: #27ACFF;
							line-height: 28rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
							padding: 3rpx 8rpx;
							margin-right: 10rpx;
							margin-bottom: 8rpx;
						}
					}

					.price {
						.left {
							font-weight: 400;
							font-size: 24rpx;
							color: #666666;
							line-height: 34rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;

							.text {
								margin-right: 10rpx;
							}
						}

						.right {
							color: #FF372F;

							.text1 {
								font-size: 28rpx;
								margin-right: 5rpx;
							}

							.text2 {
								font-size: 40rpx;
								margin-right: 5rpx;
							}

							.text3 {
								font-size: 22rpx;
								color: #999999;
							}
						}
					}
				}
			}
		}
	}
</style>