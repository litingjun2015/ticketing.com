<template>
	<view class="card font_family" @click="go_detail">
		<image class="card_img" :src="item.avatar" mode="aspectFill"></image>
		<view class="msg">
			<view class="name f_j">
				<view class="text text_ellipsis">
					{{item.username}}
				</view>
				<view class="gender f_zj" :style="{background:item.gender==1?'#16A9FA':''}">
					<image v-if="item.gender==1" class="gender_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/man.png" mode=""></image>
					<image v-else class="gender_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/woman.png" mode=""></image>
				</view>
			</view>
			<view class="num">
				<text class="text">月服务数量</text>
				<text class="number" style="margin-left: 14rpx;">{{item.month_servers}}</text>
			</view>
			<view class="price">
				<text style="margin-right: 14rpx;">{{item.product}}</text>
				<text class="price_text">￥{{item.price}}</text>
				<text style="margin-left:14rpx">起</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: ['item'],
		data() {
			return {}
		},
		methods: {
			go_detail() {
				uni.navigateTo({
					url: '/pagesA/index_menu/guide_detail?id=' + this.item.id
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.card {
		width: 336rpx;
		height: 318rpx;
		background: #FFFFFF;
		border-radius: 24rpx 24rpx 24rpx 24rpx;
		overflow: hidden;
		margin-top: 18rpx;

		.card_img {
			width: 100%;
			height: 172rpx;
		}

		.msg {
			padding: 12rpx 22rpx;

			.name {
				.text {
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.gender {
					width: 46rpx;
					height: 26rpx;
					background: #FF4588;
					border-radius: 44rpx 44rpx 44rpx 44rpx;
					margin-left: 10rpx;

					.gender_icon {
						width: 20rpx;
						height: 20rpx;
					}
				}
			}

			.num {
				font-weight: 400;
				font-size: 24rpx;
				color: #666666;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
				margin-top: 10rpx;
			}

			.price {
				margin-top: 10rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #666666;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;

				.price_text {
					font-weight: 400;
					font-size: 32rpx;
					color: #FF372F;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}
		}
	}
</style>