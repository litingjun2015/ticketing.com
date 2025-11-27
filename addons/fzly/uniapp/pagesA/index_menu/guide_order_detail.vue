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
								v-for="(item,index) in detail.dates" :key="index" @click="time_check(index,item)">
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
							v-for="(item,index) in detail.model" :key="index" @click="car_check(index,item)">
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
							v-for="(item,index) in detail.duration" :key="index" @click="hours_check(index,item)">
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
							<image v-if="item.icon" class="img" :src="item.icon" mode="aspectFill"></image>
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
						{{detail.mobile}}
					</view>
				</view>
			</view>
			<!-- 商品内容 -->
			<view class="commodity_des" v-if="detail.tw_content">
				<u-parse :content='detail.tw_content'></u-parse>
			</view>
			<!-- 费用说明 -->
			<view class="cost_description" v-if="common.mp_switch==1">
				<view class="cost_title">
					费用说明
				</view>
				<!-- <view class="little_title">
					费用包含
				</view> -->
				<view class="description" v-if="detail.fy_content">
					<u-parse :content='detail.fy_content'></u-parse>
				</view>
			</view>
			<!-- 退改原则 -->
			<view class="returns" v-if="common.mp_switch==1">
				<view class="return_title">
					退改原则
				</view>
				<!-- <view class="return_little_title">
					用户取消
				</view> -->
				<view class="return_des" v-if="detail.tg_content">
					<u-parse :content='detail.tg_content'></u-parse>
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
				<u-empty v-if="comment_list.length==0" text='当前没有评论哟~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
				</u-empty>
			</view>
		</view>

		<!-- 日历 -->
		<u-calendar color='#FFE706' :minDate='minDate' :defaultDate='minDate' :show="calendar_show" closeOnClickOverlay
			@confirm="confirm" @close="calendar_show=false"></u-calendar>
		<!-- 底部按钮 -->
		<view class="bottom f_j f_z_b">
			<view class="price">
				<text class="text1">￥</text>
				<text class="text2">{{detail.price}}</text>
			</view>
			<view class="btn" @click="go_buy" v-if="common.mp_switch==1">
				立即预定
			</view>
			<button open-type="contact" class="btn" v-else>
				联系客服
			</button>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { guide_info, admission_comment } from '@/api/index_menu/index.js'
	import more from '@/components/more.vue'
	export default {
		components: {
			more
		},
		computed: {
			...mapState(['user_info', 'common'])
		},
		data() {
			return {
				menuButtonInfo: 0,
				calendar_show: false,
				time_action: '',
				car_action: '',
				hours_action: '',
				query: {
					id: '',
				},
				detail: '',
				date: '', //时间
				car_type: '', //时间
				time: '', //时间
				comment_list: [],
				go_url: '',
				tag_list: [],
				minDate: '',
			};
		},
		onLoad(e) {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.query.id = e.id
			this.get_detail()
			this.get_user_comment()
			// 设置最小可选日期
			this.set_minDate()
		},
		methods: {
			set_minDate() {
				const now = new Date()
				const currentHour = now.getHours()
				if (currentHour >= 12) {
					now.setDate(now.getDate() + 1)
					const timestamp = now.getTime()
					this.minDate = uni.$u.timeFormat(timestamp, 'yyyy-mm-dd')
				} else {

				}
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// 选择时间
			time_check(index, item) {
				this.time_action = index
				this.date = item.date
			},
			confirm(e) {
				console.log(e)
				this.date = e[0].slice(5)
				console.log(this.date)
				this.calendar_show = false
				// 循环日期，存在的话选中日期
				try {
					this.detail.dates.forEach((item, index) => {
						if (item.date == e[0].slice(5)) {
							console.log('wolaile', index)
							this.time_action = index
							throw index
						} else {
							this.time_action = ''
						}
					})
				} catch (e) {
					throw e
				}

				this.$forceUpdate()
			},
			// 选择车型
			car_check(index, item) {
				this.car_action = index
				this.car_type = item
			},
			// 选择时长
			hours_check(index, item) {
				this.hours_action = index
				this.time = item
			},
			// 去下单
			go_buy() {
				// 判断是否为自己的产品
				if (this.user_info.id == this.detail.user_id) {
					this.$refs.uToast.show({
						message: '无法购买自己的产品'
					})
					return
				}
				let obj = {}
				if (!this.date) {
					this.$refs.uToast.show({
						message: '请选择时间'
					})
					return
				}
				obj.date = this.date
				if (this.detail.type_name == '包车游') {
					if (!this.car_type) {
						this.$refs.uToast.show({
							message: '请选择车型'
						})
						return
					}
					console.log(33333)
					obj.car = this.car_type
				} else {
					if (!this.time) {
						this.$refs.uToast.show({
							message: '请选择时长'
						})
						return
					}
					obj.time = this.time
				}
				let json = JSON.stringify(obj);
				let detail = this.detail
				detail.tw_content = ''
				detail = JSON.stringify(detail)
				uni.navigateTo({
					url: '/pagesA/index_menu/order_buy?type=2&detail=' + encodeURIComponent(detail) + '&obj=' +
						json
				})
			},
			// 获取产品详情
			get_detail() {
				guide_info(this.query).then(res => {
					if (res.code == 1) {
						this.detail = res.data
						this.go_url =
							`/pagesA/all_evaluate/dy_all_evaluate?id=${this.query.id}&start=${this.detail.score}`
					}
				})
			},
			// 获取用户评论
			get_user_comment() {
				let obj = {
					type: 2,
					id: this.query.id
				}
				admission_comment(obj).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.comment_list = res.data.data.data
						this.tag_list = res.data.tags
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
			height: 400rpx;
			// background-image: url(https://img1.baidu.com/it/u=1678477941,1592983849&fm=253&fmt=auto&app=138&f=JPEG?w=800&h=500);
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
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
				padding: 20rpx 20rpx;
				// padding-left: 40rpx;

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
				padding: 20rpx 20rpx;
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
				padding: 20rpx 20rpx;
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

			.commodity_des {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 20rpx 20rpx;
				margin-top: 30rpx;
			}

			.cost_description {
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 20rpx 20rpx;
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
				padding: 20rpx 20rpx;
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
			z-index: 2;

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

			button {
				margin: 0rpx;
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
</style>