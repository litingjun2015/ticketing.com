<template>
	<view class="content font_family">
		<view class="title">
			{{status==3?'已核销':'已取消'}}
		</view>
		<view class="msg_box">
			<view class="msg_title">
				订单信息
			</view>
			<view class="msg_item f">
				<view class="left">
					订单名称：
				</view>
				<view class="right">
					{{msg.title}}
				</view>
			</view>
			<view class="msg_item f">
				<view class="left">
					地点：
				</view>
				<view class="right">
					{{type==1?msg.address_xx:msg.jq_title}}
				</view>
			</view>
			<view class="msg_item f">
				<view class="left">
					订单编号：
				</view>
				<view class="right">
					{{order_no}}
				</view>
			</view>
			<view class="msg_item f">
				<view class="left">
					金额：
				</view>
				<view class="right">
					{{price}}元
				</view>
			</view>
			<view class="msg_item f" v-if="status==3">
				<view class="left">
					支付时间：
				</view>
				<view class="right">
					{{pays_time}}
				</view>
			</view>
			<view class="btn f" v-if="status==3 ">
				<view class="go f_zj" v-if="is_pl==1">
					已评价
				</view>
				<view v-else class="go f_zj" @click="go('/pagesA/order/evaluate?id='+id+'&type='+type)">
					评价
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				type: '',
				status: '',
				msg: '',
				order_no: '',
				price: '',
				pays_time: '',
				id: '',
				is_pl: ''
			};
		},
		onLoad(e) {
			this.type = e.type
			this.id = e.id
			this.status = e.status
			this.order_no = e.order_no
			this.price = e.price
			this.pays_time = e.pays_time
			this.is_pl = e.is_pl
			this.msg = JSON.parse(e.msg)
		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;

		.title {
			font-weight: 400;
			font-size: 40rpx;
			color: #3D3D3D;
		}

		.msg_box {
			background: #FFFFFF;
			border-radius: 12rpx 12rpx 12rpx 12rpx;
			padding: 30rpx;
			margin-top: 30rpx;

			.msg_title {
				font-weight: 500;
				font-size: 28rpx;
				color: #333333;
			}

			.msg_item {
				margin-top: 30rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #333333;
			}

			.btn {
				justify-content: flex-end;
			}

			.go {
				margin-top: 30rpx;
				width: 192rpx;
				height: 66rpx;
				background: #FDDC05;
				border-radius: 35rpx 35rpx 35rpx 35rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #232323;
			}
		}
	}
</style>