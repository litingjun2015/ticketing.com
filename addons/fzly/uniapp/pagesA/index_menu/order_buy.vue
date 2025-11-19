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
			<view class="style" v-if="type==2"
				:style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/order_bg.png)`}">
				<view class="style_style f_j">
					<image class="icon" src="../../static/public/checkmark_b.png" mode=""></image>
					<text class="text" v-if="obj.car">{{obj.car}}</text>
					<text class="text" v-if="obj.time">{{obj.time}}</text>
				</view>
				<view class="style_style f_j mar">
					<image class="icon" src="../../static/public/date.png" mode=""></image>
					<text class="text">{{obj.date}}</text>
				</view>
			</view>
			<!-- 出行信息 -->
			<view class="travel_msg">
				<view class="header f_z_b">
					<text>出行信息</text>
					<view class="add_btn" @click="go('/pagesA/index_menu/travel_people')" v-if="!order_no">
						+ 增加出行人
					</view>
				</view>
				<view class="travel_msg_list" v-for="(item,index) in travel_people" :key="index"
					:class="{travel_msg_last:index+1<travel_people.length}">
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
			<view class="cost">
				<view class="can_use f_z_b" v-if="can_title!='no_use'">
					<view class="left f_j">
						<image class="img" src="../../static/public/can_use.png" mode=""></image>
						<text class="text">使用优惠券</text>
					</view>
					<view class="right f_j" @click="check_use">
						<text class="text" v-if="!can_title">{{can_list.length>0?'有优惠券可用':'暂无优惠券可用'}}</text>
						<text class="text" style="color: #000000;" v-if="can_title">使用{{can_title}}</text>
						<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
					</view>
				</view>
				<view class="f_z_b">
					<text class="text">费用</text>
					<view class="right">
						<text class="q_cost">全额</text>
						<text style="color:#FF372F;">￥</text>
						<text class="num" style="color:#FF372F;">{{price}}</text>
					</view>
				</view>
			</view>
			<!-- 按钮 -->
			<view class="pay f_zj" @click="$u.throttle(buy,1000)">
				立即支付
			</view>
			<!-- <view class="cancel_orde f_zj">
				取消订单
			</view> -->
		</view>
		<!-- 可用优惠券弹窗 -->
		<u-popup :show="can_show" @close="can_close" round='20'>
			<view class="can_title">
				选择优惠券
			</view>
			<scroll-view class="can_box" :scroll-y="true">
				<view class="item" v-for="(item,index) in can_list" :key="index">
					<view class="top f">
						<view class="left">
							<view class="left_big">
								￥{{item.coupon.used_amount}}
							</view>
							<view class="left_small">
								满{{item.coupon.with_amount}}元可使用
							</view>
							<view class="yuan">

							</view>
						</view>
						<view class="right f_j">
							<view class="text">
								<view class="big_text">
									{{item.coupon.title}}
								</view>
								<view class="small_text">
									有效期：{{item.coupon.draw_range}}
								</view>
							</view>
						</view>
					</view>
					<view class="btn_box f">
						<view class="btn" v-if="item.state==0" @click="go_use(item)">
							使用
						</view>
					</view>
					<!-- 标记 -->
					<view class="tag">
						<image v-if="item.state==-1" class="img" src="../../static/public/ygq.png" mode="">
						</image>
						<image v-if="item.state==1" class="img" src="../../static/public/ysy.png" mode="">
						</image>
						<image v-if="item.state==0" class="img" src="../../static/public/wsy.png" mode="">
						</image>
					</view>
				</view>
			</scroll-view>
		</u-popup>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import wx_api from '@/utils/wx_api.js'
	import { before, create, get_openid, pay, usable } from '@/api/user.js'
	import wxapi from '@/utils/wx_api.js'
	import { mapState, mapMutations } from 'vuex'
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
				projectUrl: '',
				// 可用优惠券请求参数
				can_data: {
					type: '',
					id: '',
					intro: '',
					count: ''
				},
				can_list: [],
				can_title: '',
				// 优惠券弹窗
				can_show: false
			};
		},
		computed: {
			...mapState(['user_info', 'travel_id', 'travel_people', 'common'])
		},
		watch: {
			travel_id() {
				this.calculate()
				// 选择出行人之后获取可用优惠券
				this.can_data.count = this.travel_id.length
				this.get_can_use()
			}
		},
		// onUnload() {
		// 	// 页面卸载清空出行人数据
		// 	this.set_travel_id('')
		// 	this.set_travel_people('')
		// },
		onLoad(e) {
			this.projectUrl = this.$projectUrl
			// console.log(e)
			this.type = e.type
			this.before_data.type = e.type
			this.create_data.type = e.type
			this.detail = JSON.parse(decodeURIComponent(e.detail))
			this.obj = JSON.parse(e.obj)
			console.log(this.obj, this.detail)
			this.before_data.id = this.detail.id

			// 初始化优惠券请求参数
			this.can_data.type = e.type
			this.can_data.id = this.detail.id
			if (e.type == 1) {
				// 门票类型带上门票名称
				this.can_data.intro = this.obj.intro
			}

			// 判断是否是订单列表页来的
			if (e.again) {
				this.order_no = e.order_no
			}
			// 判断该订单是否使用优惠券
			if (e.coupon != 'no_use') {
				if (e.coupon) {
					let coupon = JSON.parse(e.coupon)
					this.before_data.coupon_id = coupon.id
					this.create_data.coupon_id = coupon.id
					this.can_title = coupon.title
				}
			} else {
				this.can_title = 'no_use'
			}
			if (this.travel_id.length > 0) {
				this.can_data.count = this.travel_id.length
				this.get_can_use()
			}
			this.calculate()
		},
		methods: {
			...mapMutations(['set_travel_id', 'set_travel_people']),
			go(url) {
				uni.navigateTo({
					url
				})
			},
			buy() {
				if (this.travel_id.length == 0) {
					this.$refs.uToast.show({
						message: '请选择出行人'
					})
					return
				}
				this.create_order()
			},
			// 计算商品价格
			calculate() {
				this.before_data.travel_id = this.travel_id
				this.before_data.name = this.obj.intro
				before(this.before_data).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.price = res.data.price
					}
				})
			},
			// 获取openid
			get_openid(orderNo) {
				uni.login({
					success: (res) => {
						get_openid({ code: res.code }).then(res => {
							console.log(res)
							if (res.code == 1) {
								this.pay(res.data.openid, orderNo)
							}
						})
					},
					fail: (res) => {
						console.log(res)
					}
				})

			},
			// 创建订单
			create_order() {
				if (this.order_no) {
					// 当前有订单编号无需在创建订单
					// #ifdef MP-WEIXIN
					if (!this.user_info.openid) {
						this.get_openid(this.order_no)
					} else {
						this.pay(this.user_info.openid, this.order_no)
					}
					// #endif
					// #ifdef H5
					this.pay(this.user_info.openid, this.order_no)
					// #endif
				} else {
					this.create_data.id = this.detail.id
					this.create_data.travel_id = this.travel_id
					if (this.type == 2) {
						this.create_data.yd_time = this.obj.date
						this.create_data.yd_dsj = this.obj.time
						this.create_data.yd_model = this.obj.car
					} else {
						this.create_data.name = this.obj.intro
					}

					create(this.create_data).then(res => {
						// console.log(res)
						if (res.code == 1) {
							// #ifdef MP-WEIXIN
							if (!this.user_info.openid) {
								this.get_openid(res.data.orderNo)
							} else {
								this.pay(this.user_info.openid, res.data.orderNo)
							}
							// #endif
							// #ifdef H5
							this.pay(this.user_info.openid, res.data.orderNo)
							// #endif
						}
					})
				}
			},
			// 支付
			pay(openid, orderNo) {
				// #ifdef MP-WEIXIN
				pay({ openid, order_no: orderNo }).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.open_pay(res.data)
					} else {
						this.$refs.uToast.show({
							message: res.msg
						})
					}
				})
				// #endif
				// #ifdef H5
				// 判断浏览器是否为微信浏览器
				let ua = navigator.userAgent.toLowerCase()
				if (ua.match(/MicroMessenger/i) == "micromessenger") {
					pay({ openid, order_no: orderNo, platform: 'mp' }).then(res => {
						console.log(res)
						if (res.code == 1) {
							// this.open_h5_pay(res.data)
							wxapi.mpwxparApi(res.data)
						} else {
							this.$refs.uToast.show({
								message: res.msg
							})
						}
					})
				} else {
					pay({ openid, order_no: orderNo, type: 'wap' }).then(res => {
						console.log(res)
						if (res.code == 1) {
							document.location.href = res.data
						} else {
							this.$refs.uToast.show({
								message: res.msg
							})
						}
					})
				}
				// #endif
			},
			// 拉起微信h5支付
			open_h5_pay(data) {
				wx.chooseWXPay({
					// provider: 'wxpay',
					timeStamp: data.timeStamp,
					nonceStr: data.nonceStr,
					package: data.package,
					signType: data.signType,
					paySign: data.paySign,
					success: (res) => {
						console.log(res)
						if (res.errMsg == 'requestPayment:ok') {
							let ids = []
							this.common.push.forEach(item => {
								if (item.message_type == 1) {
									ids.push(item.masterplate)
								}
							})
							// #ifdef MP-WEIXIN
							wx.requestSubscribeMessage({
								tmplIds: ids,
								success: (res) => {
									console.log(res)
								},
								fail: (res) => {
									console.log(res)
								}
							})
							// #endif

							uni.redirectTo({
								url: '/pagesA/order/order?type=2'
							})
						}
					},
					fail: (res) => {
						// 支付失败跳转待付款订单页
						uni.redirectTo({
							url: '/pagesA/order/order?type=1'
						})
					}
				})
			},
			// 拉起微信支付
			open_pay(data) {
				uni.requestPayment({
					provider: 'wxpay',
					timeStamp: data.timeStamp,
					nonceStr: data.nonceStr,
					package: data.package,
					signType: data.signType,
					paySign: data.paySign,
					success: (res) => {
						console.log(res)
						if (res.errMsg == 'requestPayment:ok') {
							let ids = []
							this.common.push.forEach(item => {
								if (item.message_type == 1) {
									ids.push(item.masterplate)
								}
							})
							// #ifdef MP-WEIXIN
							wx.requestSubscribeMessage({
								tmplIds: ids,
								success: (res) => {
									console.log(res)
								},
								fail: (res) => {
									console.log(res)
								}
							})
							// #endif

							uni.redirectTo({
								url: '/pagesA/order/order?type=2'
							})
						}
					},
					fail: (res) => {
						// 支付失败跳转待付款订单页
						uni.redirectTo({
							url: '/pagesA/order/order?type=1'
						})
					}
				})
			},
			// 获取可用优惠券
			get_can_use() {
				usable(this.can_data).then(res => {
					if (res.code == 1) {
						this.can_list = res.data
					}
				})
			},
			// 优惠券选择弹窗
			check_use() {
				if (this.can_list.length > 0 && !this.order_no) {
					this.can_show = true
				}
			},
			go_use(item) {
				this.create_data.coupon_id = item.coupon_id
				this.before_data.coupon_id = item.coupon_id
				this.can_title = item.coupon.title
				this.calculate()
				this.can_show = false
			},
			can_close() {
				this.can_show = false
			}
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
				background-repeat: no-repeat;
				background-size: cover;
				background-position: center;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				padding: 25rpx 30rpx;
				height: 128rpx;
				box-sizing: border-box;

				.style_style {
					padding: 5rpx 0rpx;

					.icon {
						width: 30rpx;
						height: 30rpx;
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
					border-bottom: 1rpx solid #D8D8D8;
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

				.can_use {
					margin-bottom: 20rpx;

					.left {
						.img {
							width: 31rpx;
							height: 31rpx;
							margin-right: 15rpx;
						}

						.text {
							font-weight: 400;
							font-size: 28rpx;
							color: #313131;
						}
					}

					.right {
						.icon {
							margin-left: 10rpx;
							width: 11rpx;
							height: 19rpx;
						}

						.text {
							font-weight: 400;
							font-size: 24rpx;
							color: #C2C2C2;
						}

						.use_text {
							color: #000000;
						}
					}
				}

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

			.pay {
				margin-top: 50rpx;
				height: 84rpx;
				background: #FFE706;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				font-weight: 400;
				font-size: 28rpx;
				color: #232323;
				line-height: 40rpx;
				text-align: left;
				font-style: normal;
				text-transform: none
			}

			.cancel_orde {
				margin-top: 30rpx;
				height: 84rpx;
				background: rgba(102, 102, 102, 0.1);
				border-radius: 16rpx;
				font-weight: 400;
				font-size: 28rpx;
				color: #666666;
				line-height: 40rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}
		}

		.can_title {
			padding-top: 30rpx;
			font-weight: 400;
			font-size: 32rpx;
			color: #3D3D3D;
			margin-bottom: 30rpx;
			text-align: center;
		}

		.can_box {
			height: 600rpx;
			padding: 30rpx;
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
	}
</style>