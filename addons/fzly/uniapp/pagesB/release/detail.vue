<template>
	<view class="content font_family" v-if="detail">
		<view class="header" :style="{paddingTop:menuButtonInfo+'px',backgroundImage:`url(${detail.image})`}">
			<view class="back f_zj" @click="back">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_w.png" mode=""></image>
			</view>
		</view>
		<!-- 内容 -->
		<view class="content_box">
			<!-- 标题 -->
			<view class="title">
				{{detail.title}}
				<view class="address f_j">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/pt_h.png" mode=""></image>
					<view class="text">
						{{detail.jq_title}}
					</view>
				</view>
			</view>
			<!-- 导游信息 -->
			<view class="guide_box f">
				<view class="left">
					<u-avatar :src="detail.avatar" size="116rpx" mode="aspectFill"></u-avatar>
				</view>
				<view class="right f_d f_z_b">
					<view class="name">
						{{detail.username}}
					</view>
					<view class="f_j des">
						<view class="time">
							<text>入驻</text>
							<text class="mar">{{detail.create_time}}</text>
						</view>
						<view class="line">

						</view>
						<view class="fw">
							<text>月服务次数</text>
							<text class="mar">{{detail.month_servers}}</text>
							<text>次</text>
						</view>
					</view>
				</view>
			</view>
			<!-- 选择操作 -->
			<view class="check_box">
				<!-- 时间选择 -->
				<view class="check_time">
					<view class="tilte f">
						选择时间
						<view class="date_check" style="margin-left: 10rpx;">
							{{date}}
						</view>
					</view>
					<view class="time_box f">
						<scroll-view scroll-x="true" class="item_box">
							<view class="item f_d f_z" :class="[index===time_action?'action':'']"
								v-for="(item,index) in detail.dates" :key="index">
								<view class="week">
									{{item.weekDay}}
								</view>
								<view class="day">
									{{item.date}}
								</view>
								<!-- <view class="price">
									<text>￥</text>
									<text>99</text>
								</view> -->
							</view>
						</scroll-view>
						<view class="more f_zj" @click="calendar_show=true">
							更多
						</view>
					</view>
				</view>
				<!-- 车型选择 -->
				<view class="car" v-if="detail.type_name=='包车游'">
					<view class="car_title">
						选择车型
					</view>
					<view class="item_box f">
						<view class="item" :class="[index===car_action?'action':'']"
							v-for="(item,index) in detail.model" :key="index">
							{{item}}
						</view>
					</view>
				</view>
				<!-- 讲解时间选择 -->
				<view class="time" v-if="detail.type_name=='景区讲解'">
					<view class="time_title">
						选择时长
					</view>
					<view class="item_box f">
						<view class="item" :class="[index===hours_action?'action':'']"
							v-for="(item,index) in detail.duration" :key="index">
							{{item}}
						</view>
					</view>
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
						<view class="item f_j" v-for="(item,index) in detail.yd_multiplejson" :key="index">
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
						</view>
					</view>
				</view>
				<!-- 电话 -->
				<view class="phone f">
					<view class="guarantee_title">
						电话
					</view>
					<view class="phone_num">
						17839912920
					</view>
				</view>
			</view>
			<!-- 商品内容 -->
			<view class="commodity_des">
				<u-parse :content='detail.tw_content'></u-parse>
			</view>
			<!-- 费用说明 -->
			<view class="cost_description">
				<view class="cost_title">
					费用说明
				</view>
				<!-- <view class="little_title">
					费用包含
				</view> -->
				<view class="description">
					{{detail.fy_content}}
				</view>
			</view>
			<!-- 退改原则 -->
			<view class="returns">
				<view class="return_title">
					退改原则
				</view>
				<!-- <view class="return_little_title">
					用户取消
				</view> -->
				<view class="return_des">
					{{detail.tg_content}}
				</view>
				<!-- 表格 -->
				<view class="return_table">
					<view class="table_header f_z_b">
						<view class="left">
							取消时间
						</view>
						<view class="right">
							损失费用
						</view>
					</view>
					<view class="retuen_item f_z_b" v-for="(item,index) in detail.tg_multiplejson" :key="index">
						<view class="left">
							{{item.time}}
						</view>
						<view class="right">
							{{item.fy}}
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 日历 -->
		<u-calendar color='#FFE706' :show="calendar_show" closeOnClickOverlay @confirm="confirm"
			@close="calendar_show=false"></u-calendar>
		<!-- 底部按钮 -->
		<view class="bottom f_j ">
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
		<!-- 价格调整 -->
		<u-popup :show="show" @close="close" round='20'>
			<view class="pop_box f_d f_j">
				<text class="title">价格调整至</text>
				<view class="ipt f_j">
					<view class="">
						￥
					</view>
					<view class="ipt_box">
						<u--input :placeholder="placeholder" inputAlign='center' border="none"
							v-model="price_data.price"></u--input>
					</view>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
				</view>
				<view class="btn" @click="price_set">
					确认
				</view>
			</view>
		</u-popup>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { guide_info } from '@/api/index_menu/index.js'
	import { product_status, product_price } from '@/api/user.js'
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				calendar_show: false,
				time_action: '',
				car_action: '',
				hours_action: '',
				query: {
					id: ''
				},
				detail: '',
				date: '', //时间
				car_type: '', //时间
				time: '', //时间
				price_data: {
					id: '',
					price: ''
				},
				placeholder: '填写价格',
				show: false
			};
		},
		onLoad(e) {
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			this.query.id = e.id
			this.price_data.id = e.id
			this.get_detail()
		},
		methods: {
			go_set(url) {
				uni.navigateTo({
					url
				})
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			close() {
				this.show = false
			},
			open(id, price) {
				this.show = true
				this.placeholder = this.detail.price
			},
			// 获取产品详情
			get_detail() {
				guide_info(this.query).then(res => {
					if (res.code == 1) {
						this.detail = res.data
					}
				})
			},
			price_set() {
				product_price(this.price_data).then(res => {
					if (res.code == 1) {
						this.$refs.uToast.show({
							message: '修改成功'
						})
						this.show = false
					}
				})
			},
			// 上下架 2上架4下架
			up_down(type, id) {
				let obj = {
					id,
					type
				}
				uni.showModal({
					title: '提示',
					content: `是否${type==2?'上架':'下架'}该商品`,
					success: (res) => {
						if (res.confirm) {
							product_status(obj).then(res => {
								console.log(res)
								if (res.code == 1) {
									uni.navigateBack({
										delta: 1
									})
								}
							})
						} else if (res.cancel) {

						}
					}
				})
			},
		},
	}
</script>

<style lang="scss">
	.content {
		.header {
			width: 100%;
			height: 334rpx;
			// background-image: url(https://img1.baidu.com/it/u=1678477941,1592983849&fm=253&fmt=auto&app=138&f=JPEG?w=800&h=500);
			background-position: center;
			background-repeat: no-repeat;
			background-size: 100%;
			padding-left: 30rpx;

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

					.icon {
						width: 24rpx;
						height: 32rpx;
					}

					.text {
						margin-left: 10rpx;
						font-weight: 400;
						font-size: 28rpx;
						color: #666666;
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

			.commodity_des {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 22rpx 40rpx;
				margin-top: 30rpx;
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

		.pop_box {
			padding: 30rpx;

			.title {
				font-weight: 400;
				font-size: 36rpx;
				color: #3D3D3D;
			}

			.ipt {
				font-weight: 400;
				font-size: 36rpx;
				color: #3D3D3D;
				margin-top: 30rpx;

				.ipt_box {
					margin: 0rpx 15rpx;
					width: 150rpx;
				}

				.icon {
					width: 24rpx;
					height: 26rpx;
				}
			}

			.btn {
				font-weight: 400;
				font-size: 28rpx;
				color: #FFFFFF;
				background: #FFAE35;
				border-radius: 496rpx 496rpx 496rpx 496rpx;
				width: 100%;
				padding: 15rpx 0rpx;
				text-align: center;
				margin-top: 70rpx;
			}
		}
	}
</style>