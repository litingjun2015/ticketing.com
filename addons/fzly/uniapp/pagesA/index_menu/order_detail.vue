<template>
	<view class="content font_family">
		<view class="title">
			支付已完成
		</view>
		<view class="travel_msg">
			<view class="title">
				如意湖景区门票大技术快递很快如意湖景区门票大技术快递很快
			</view>
			<view class="item">
				<text>时间：</text>
				<text>2023-03-14 至 2023-12-31</text>
			</view>
			<view class="item">
				<text>地点：</text>
				<text>大玉米</text>
			</view>
			<!-- 支付未完成按钮 -->
			<view class="btn f">
				<view class="cancel">
					取消订单
				</view>
				<view class="confirm">
					立即付款
				</view>
			</view>
		</view>
		<view class="travel_msg order_msg">
			<view class="title">
				订单信息
			</view>
			<view class="item">
				<text>订单编号：</text>
				<text>2023-03-14 至 2023-12-31</text>
			</view>
			<view class="item">
				<text>实付金额：</text>
				<text>大玉米</text>
			</view>
			<view class="item">
				<text>支付时间：</text>
				<text>大玉米</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {

			};
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 0rpx 30rpx;
		padding-top: 30rpx;

		.title {}

		.travel_msg {
			margin-top: 30rpx;
			background: #FFFFFF;
			border-radius: 34rpx 34rpx 34rpx 34rpx;
			padding: 26rpx 30rpx;

			.title {
				font-weight: 500;
				font-size: 30rpx;
				color: #282828;
				font-style: normal;
				text-transform: none;
			}

			.item {
				margin-top: 28rpx;
				font-weight: 400;
				font-size: 22rpx;
				color: #333333;
				font-style: normal;
				text-transform: none;
			}

			.btn {
				margin-top: 50rpx;

				.cancel {
					font-weight: 400;
					font-size: 24rpx;
					color: #666666;
					font-style: normal;
					text-transform: none;
					padding: 18rpx 44rpx;
					background: rgba(102, 102, 102, 0.1);
					border-radius: 35rpx 35rpx 35rpx 35rpx;
					margin-left: auto;
				}

				.confirm {
					font-weight: 400;
					font-size: 24rpx;
					color: #232323;
					font-style: normal;
					text-transform: none;
					background: #FDDC05;
					padding: 18rpx 44rpx;
					border-radius: 35rpx 35rpx 35rpx 35rpx;
					margin-left: 36rpx;
				}
			}
		}

		.order_msg {
			margin-top: 60rpx;
		}
	}
</style>