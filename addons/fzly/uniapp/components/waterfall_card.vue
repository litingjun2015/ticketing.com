<template>
	<view class="waterfall font_family" @click="go('/pagesA/index_menu/must_detail?id='+img.id)">
		<view class="img_box">
			<image class="img" :src="img.image" mode="widthFix"></image>
			<!-- <view class="tag f_zj">
				近期热销
			</view> -->
		</view>
		<view class="title f_z_b f_j">
			<view class="text">
				{{img.title}}
			</view>
			<view class="sales f_j">
				<image class="icon" src="../static/public/view.png" mode=""></image>
				<text>{{img.view_nums}}</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: ['img'],
		name: "waterfall",
		data() {
			return {

			};
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
	.waterfall {
		width: 336rpx;
		margin-bottom: 20rpx;
		break-inside: avoid;
		margin-bottom: 30rpx;

		.img_box {
			position: relative;

			.img {
				width: 100%;
				border-radius: 16rpx;
				vertical-align: bottom;
			}

			.tag {
				position: absolute;
				right: 0;
				top: 0;
				width: 124rpx;
				height: 44rpx;
				background: #F87415;
				border-radius: 0rpx 16rpx 0rpx 16rpx;
				font-weight: 400;
				font-size: 20rpx;
				color: #FFFFFF;
				line-height: 23rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}
		}

		.title {
			width: 100%;
			margin-top: 15rpx;

			.text {
				width: 280rpx;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
			}

			.sales {
				font-weight: 400;
				font-size: 20rpx;
				color: #666666;
				text-align: left;
				font-style: normal;
				text-transform: none;

				.icon {
					width: 30rpx;
					height: 20rpx;
					margin-right: 10rpx;
				}
			}
		}
	}
</style>