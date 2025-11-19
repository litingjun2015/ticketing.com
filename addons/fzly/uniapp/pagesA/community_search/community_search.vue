<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image class="icon" src="../../static/public/back_b.png" mode="" @click="back"></image>
			<view class="search f_z_b">
				<view class="search_left f_zj">
					<view>
						<image class="magnif" src="../../static/index/magnif.png" mode=""></image>
					</view>
				</view>
				<view class="search_right f_zj">
					<u--input placeholder="请输入内容" border="none" v-model="value" @change="change"></u--input>
				</view>
				<view class="btn f_zj">
					搜索
				</view>
			</view>
		</view>
		<!-- 历史搜索 -->
		<view class="history ">
			<view class="title f_z_b">
				<text class="text">历史搜索</text>
				<image class="icon" src="../../static/public/sc.png" mode=""></image>
			</view>
			<view class="item_box f">
				<view class="item" v-for="(item,index) in 8" :key="index">
					海洋馆
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				menuButtonInfo: 0
			}
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
		},
		methods: {
			back() {
				uni.navigateBack({
					delta: 1
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-bottom: 30rpx;

		.header {
			padding-left: 30rpx;

			.icon {
				width: 18rpx;
				height: 36rpx;
			}

			.search {
				margin-left: 40rpx;
				align-items: center;
				width: 400rpx;
				height: 68rpx;
				background: #F5F6F8;
				border-radius: 80rpx 80rpx 80rpx 80rpx;
				padding: 0rpx 26rpx;
				border: 2rpx solid #232323;

				.search_left {
					.icon {
						width: 18rpx;
						height: 10rpx;
						margin-left: 10rpx;
					}

					.line {
						border-right: 1rpx solid #666666;
						height: 34rpx;
						margin-left: 20rpx;
					}

					.magnif {
						width: 30rpx;
						height: 30rpx;
						vertical-align: baseline;
					}
				}

				.search_right {
					margin-left: 10rpx;
					flex: 1;
					height: 42rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #232323;
					line-height: 33rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.btn {
					width: 94rpx;
					height: 50rpx;
					background: #FFE706;
					border-radius: 1300rpx 1300rpx 1300rpx 1300rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #232323;
					line-height: 33rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}
		}

		.history {
			padding: 0rpx 30rpx;
			margin-top: 30rpx;

			.title {
				.text {
					font-weight: 400;
					font-size: 32rpx;
					color: #181818;
					line-height: 44rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.icon {
					width: 29rpx;
					height: 29rpx;
				}
			}

			.item_box {
				margin-top: 28rpx;
				flex-wrap: wrap;

				.item {
					background: #FFFFFF;
					border-radius: 29rpx 29rpx 29rpx 29rpx;
					margin-right: 20rpx;
					padding: 12rpx 38rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #181818;
					line-height: 34rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-bottom: 10rpx;
				}
			}
		}
	}
</style>