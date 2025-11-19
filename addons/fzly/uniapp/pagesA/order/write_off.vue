<template>
	<view class="content font_family" v-if="detail">
		<view class="title">
			待使用
		</view>
		<view class="title_box">
			<view class="top">
				{{detail.detail.title}}
			</view>
			<view class="bottom">
				<text>地点：</text>
				<text>{{detail.order_type==1?detail.detail.address_xx:detail.detail.jq_title}}</text>
			</view>
		</view>
		<!-- 二维码 -->
		<view class="qr_code">
			<view class="tip">
				<u-divider text="待使用"></u-divider>
			</view>
			<view class="code_text">
				凭「入园码」直接入园
			</view>
			<view class="img_box f_d f_zj" v-for="(item,index) in travel_arr" :key="index">
				<view class="left f_zj">
					入
					园
					码
				</view>
				<image class="img" :src="item.code_image" mode="widthFix"></image>
				<view class="text">
					入场前请向工作人员出示本页面
				</view>
				<!-- <view class="btn" @click="get_qrcode(item.code,index)">
					手动刷新
				</view> -->
			</view>
			<!-- 展开查看更多 -->
			<view class="see_more" v-if="detail.travel.length>1" @click="see_more">
				{{see_more_action?'收起':'查看更多'}}
			</view>
		</view>
		<!-- 订单信息 -->
		<view class="order_msg">
			<view class="title">
				订单信息
			</view>
			<view class="item">
				<text>订单编号：</text>
				<text>{{detail.order_no}}</text>
			</view>
			<view class="item">
				<text>支付金额：</text>
				<text>{{detail.order_amount_total}}元</text>
			</view>
			<view class="item">
				<text>支付时间：</text>
				<text>{{detail.pays_time}}</text>
			</view>
		</view>
	</view>
</template>

<script>
	// import { hx_code } from '@/api/user.js'
	export default {
		data() {
			return {
				detail: '',
				see_more_show: false, //超出两个展示更多按钮
				see_more_action: false, //是否展示更多
				travel_arr: '',
				set_qr_code: '',
			}
		},
		onLoad(e) {
			this.detail = JSON.parse(decodeURIComponent(e.detail))
			if (this.detail.travel.length > 1) {
				this.travel_arr = this.detail.travel.slice(0, 1)
			} else {
				this.travel_arr = this.detail.travel
			}
		},
		methods: {
			see_more() {
				if (!this.see_more_action) {
					this.travel_arr = this.detail.travel
				} else {
					this.travel_arr = this.detail.travel.slice(0, 1)
				}
				this.see_more_action = !this.see_more_action
			},
			// get_qrcode(code, index) {
			// 	this.set_qr_code = index
			// 	let obj = {
			// 		order_no: this.detail.order_no,
			// 		code
			// 	}
			// 	hx_code(obj).then(res => {
			// 		// console.log(res)
			// 		if (res.code == 1) {}
			// 	})
			// }
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;

		.title {
			font-weight: 400;
			font-size: 40rpx;
		}

		.title_box {
			margin-top: 30rpx;
			padding: 30rpx;
			background-color: #ffffff;
			border-radius: 12rpx 12rpx 12rpx 12rpx;

			.top {
				font-weight: 500;
				font-size: 30rpx;
				padding-bottom: 20rpx;
				border-bottom: 1rpx solid #D8D8D8;
			}

			.bottom {
				margin-top: 20rpx;
				font-weight: 400;
				font-size: 22rpx;
				color: #333333;
			}
		}

		.qr_code {
			margin-top: 30rpx;
			padding: 30rpx;
			border-radius: 12rpx 12rpx 12rpx 12rpx;
			background-color: #ffffff;

			.tip {
				width: 300rpx;
				margin: 0 auto;
			}

			.code_text {
				margin-top: 30rpx;
				font-weight: 400;
				font-size: 28rpx;
			}

			.img_box {
				margin-top: 30rpx;
				width: 100%;
				background: #F4F5F7;
				border-radius: 18rpx 18rpx 18rpx 18rpx;
				position: relative;
				padding: 30rpx 0rpx;

				.left {
					height: 100%;
					position: absolute;
					left: 0;
					top: 0;
					width: 68rpx;
					background: #232323;
					border-radius: 18rpx 18rpx 18rpx 18rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #FFFFFF;
					writing-mode: vertical-rl;
					// transform: rotate(180deg);
				}

				.img {
					width: 280rpx;
				}

				.text {
					font-weight: 400;
					font-size: 22rpx;
					color: #0A0A0A;
					margin-top: 20rpx;
				}

				.btn {
					font-weight: 400;
					font-size: 22rpx;
					color: #3CBDFF;
					background: #F9FCFE;
					border-radius: 19rpx 19rpx 19rpx 19rpx;
					padding: 10rpx 24rpx;
					margin-top: 20rpx;
				}
			}

			.see_more {
				text-align: center;
				margin-top: 20rpx;
				color: #3CBDFF;
			}
		}

		.order_msg {
			background: #FFFFFF;
			border-radius: 12rpx 12rpx 12rpx 12rpx;
			padding: 30rpx;
			margin-top: 30rpx;

			.title {
				font-weight: 500;
				font-size: 26rpx;
				color: #333333;
			}

			.item {
				font-weight: 400;
				font-size: 22rpx;
				color: #333333;
				margin-top: 20rpx;
			}
		}
	}
</style>