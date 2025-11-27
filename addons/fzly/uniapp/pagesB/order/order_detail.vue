<template>
	<view class="content font_family">
		<!-- 预订成功 -->
		<view class="msg">
			<view class="success_box">
				<view class="header f_z_b f_j">
					<view class="text">
						订单信息
					</view>
					<view class="order_num" v-if="order_no">
						<text>订单号：</text>
						<text>{{order_no}}</text>
					</view>
				</view>
				<view class="order_msg f">
					<view class="left">
						<image class="img" :src="type==1?detail.images[0]:detail.image" mode="aspectFill"></image>
					</view>
					<view class="right f_d f_z_b">
						<view class="title text_ellipsis2">
							{{detail.title}}
						</view>
						<view class="tag f">
							<view class="item" v-for="(item,index) in detail.yd_multiplejson" :key="index">
								{{item.intro}}
							</view>
						</view>
						<view class="price f_z_b f_j">
							<view class="left">
								<text>月销</text>
								<text>40</text>
							</view>
							<view class="price_right">
								<text style="color:#FF372F">￥</text>
								<text class="num" style="color:#FF372F;">{{type==1?obj.price:detail.price}}</text>
								<text>起</text>
							</view>
						</view>
					</view>
				</view>
			</view>
			<!-- 时间，类型 -->
			<view class="style" v-if="type==2">
				<view class="style_style f_j">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/checkmark_b.png" mode=""></image>
					<text class="text" v-if="obj.car">{{obj.car}}</text>
					<text class="text" v-if="obj.time">{{obj.time}}</text>
				</view>
				<view class="style_style f_j mar">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/date.png" mode=""></image>
					<text class="text">{{obj.date}}</text>
				</view>
			</view>
			<!-- 出行信息 -->
			<view class="travel_msg">
				<view class="header f_z_b">
					<text>出行信息</text>
				</view>
				<view class="travel_msg_list" v-for="(item,index) in travel" :key="index"
					:class="{travel_msg_last:index+1<travel.length}">
					<view class="item f_z_b f_j">
						<text>出行人</text>
						<text>{{item.name}}</text>
					</view>
					<view class="item f_z_b f_j">
						<text>电话</text>
						<text>{{item.tel}}</text>
					</view>
					<view class="item f_z_b f_j">
						<text>身份证</text>
						<text>{{item.id_card}}</text>
					</view>
				</view>
			</view>
			<!-- 费用 -->
			<view class="cost ">
				<view class="f_z_b">
					<text class="text">费用</text>
					<view class="right">
						<text class="q_cost">全额</text>
						<text style="color:#FF372F;">￥</text>
						<text class="num" style="color:#FF372F;">{{price}}</text>
					</view>
				</view>
				<view class="f_z_b" style="margin-top: 20rpx;">
					<text class="text">支付时间</text>
					<view class="right">
						<text class="q_cost">{{pay_time}}</text>
					</view>
				</view>
			</view>
			<!-- 按钮 -->
			<!-- <view class="btn_box f">
				<view class="btn f_zj" @click="sm" v-if="status==2">
					核销
				</view>
			</view> -->
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { before } from '@/api/user.js'
	export default {
		data() {
			return {
				type: '',
				detail: '',
				obj: '',
				before_data: {
					type: '',
					id: '',
					travel_id: '',
					coupon_id: '',
					name: ''
				},
				create_data: {
					type: '',
					id: '',
					coupon_id: '',
					travel_id: '',
					yd_time: '',
					yd_dsj: '',
					yd_model: '',
					name: ''
				},
				price: 0.00,
				order_no: '',
				travel: '',
				obj: '',
				status: '',
				pay_time: '',
			};
		},
		computed: {},
		watch: {},
		onLoad(e) {
			console.log(e)
			this.type = e.type
			this.before_data.type = e.type
			this.create_data.type = e.type
			this.detail = JSON.parse(e.detail)
			this.travel = JSON.parse(e.travel)
			this.obj = JSON.parse(e.obj)
			this.status = e.status
			this.order_no = e.order_no
			this.before_data.id = this.detail.id
			this.price = e.pay_price
			this.pay_time = e.time
			// this.calculate()
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
			go(url) {
				uni.navigateTo({
					url
				})
			},
			// calculate() {
			// 	let arr = []
			// 	this.travel.forEach(item => {
			// 		arr.push(item.travel_id)
			// 	})
			// 	this.before_data.travel_id = arr
			// 	// this.before_data.name = this.obj.intro
			// 	before(this.before_data).then(res => {
			// 		console.log(res)
			// 		if (res.code == 1) {
			// 			this.price = res.data.price
			// 		}
			// 	})
			// },
		},
	}
</script>

<style lang="scss" scoped>
	.content {
		.msg {
			padding: 30rpx;

			.success_box {
				background: #FFFFFF;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				padding: 25rpx 30rpx;

				.header {
					border-bottom: 2rpx solid #D8D8D8;
					padding-bottom: 10rpx;

					.text {
						font-weight: 400;
						font-size: 32rpx;
						color: #242424;
						font-style: normal;
						text-transform: none;
					}

					.order_num {
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
						font-style: normal;
						text-transform: none
					}
				}

				.order_msg {
					margin-top: 15rpx;

					.left {
						.img {
							width: 172rpx;
							height: 180rpx;
							border-radius: 8rpx 8rpx 8rpx 8rpx;
						}
					}

					.right {
						flex: 1;
						margin-left: 20rpx;

						.title {
							width: 100%;
							font-weight: 400;
							font-size: 28rpx;
							color: #3D3D3D;
							font-style: normal;
							text-transform: none;
						}

						.tag {
							flex-wrap: wrap;

							.item {
								border-radius: 2rpx 2rpx 2rpx 2rpx;
								border: 2rpx solid #27ACFF;
								font-weight: 400;
								font-size: 20rpx;
								color: #27ACFF;
								font-style: normal;
								text-transform: none;
								padding: 3rpx 6rpx;
								margin-right: 10rpx;
								margin-bottom: 10rpx;
							}
						}

						.price {
							width: 100%;
							font-weight: 400;
							font-size: 24rpx;
							color: #666666;
							font-style: normal;
							text-transform: none;

							.price_right {
								.num {
									font-size: 40rpx;
								}
							}
						}
					}
				}
			}

			.style {
				margin-top: 30rpx;
				background: #FFFFFF;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				padding: 25rpx 30rpx;

				.style_style {
					.icon {
						width: 24rpx;
						height: 24rpx;
					}

					.text {
						font-weight: 400;
						font-size: 24rpx;
						color: #232323;
						font-style: normal;
						text-transform: none;
						margin-left: 20rpx;
					}
				}

				.mar {
					margin-top: 10rpx;
				}
			}

			.travel_msg {
				margin-top: 30rpx;
				background: #FFFFFF;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				padding: 25rpx 30rpx;

				.header {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					font-style: normal;
					text-transform: none;
					border-bottom: 2rpx solid #D8D8D8;
					padding-bottom: 10rpx;

					.add_btn {
						padding: 10rpx 20rpx;
						font-weight: 400;
						font-size: 22rpx;
						color: #3D3D3D;
						background: #FFE706;
						border-radius: 972rpx 972rpx 972rpx 972rpx;
					}
				}

				.travel_msg_last {
					border-bottom: 2rpx solid #D8D8D8;
				}

				.travel_msg_list {
					padding-bottom: 15rpx;

					.item {
						font-weight: 400;
						font-size: 28rpx;
						color: #666666;
						line-height: 40rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
						margin-top: 15rpx;

						.icon {
							width: 10rpx;
							height: 18rpx;
						}
					}
				}


			}

			.cost {
				margin-top: 30rpx;
				background: #FFFFFF;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				padding: 25rpx 30rpx;

				.text {
					font-weight: 400;
					font-size: 32rpx;
					color: #242424;
					font-style: normal;
					text-transform: none;
				}

				.right {
					.q_cost {
						font-weight: 400;
						font-size: 28rpx;
						color: #232323;
						font-style: normal;
						text-transform: none;

					}

					.num {
						font-size: 40rpx;
					}
				}
			}

			.btn_box {
				margin-top: 30rpx;
				justify-content: flex-end;

				.btn {
					width: 186rpx;
					height: 66rpx;
					background: rgba(102, 102, 102, 0.1);
					border-radius: 35rpx 35rpx 35rpx 35rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #666666;
				}

				.btn_2 {
					margin-left: 30rpx;
					background: #FFAE35;
					font-weight: 400;
					font-size: 24rpx;
					color: #FFFFFF;
				}
			}
		}
	}
</style>