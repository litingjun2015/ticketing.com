<template>
	<view>
		<view class="box_title f f_z_b">
			<view class="left f_d">
				<text class="text">{{title}}</text>
				<view class="line">

				</view>
			</view>
			<view class="right" v-if="tomore" @click="go">
				<text class="text">{{moretitle?moretitle:'更多'}}</text>
				<image class="icon" src="../static/index/more.png" mode=""></image>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: ['title', 'tomore', 'moretitle', 'url'],
		name: "more",
		data() {
			return {

			};
		},
		methods: {
			go() {
				uni.navigateTo({
					url: this.url
				})
			},
		}
	}
</script>

<style lang="scss">
	.box_title {
		margin-top: 30rpx;

		.left {
			.text {
				font-weight: 400;
				font-size: 36rpx;
				color: #000000;
				line-height: 42rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
				z-index: 1;
			}

			.line {
				width: 100%;
				height: 14rpx;
				background: #FFE706;
				margin-top: -10rpx;
			}
		}

		.right {
			.text {
				font-weight: 400;
				font-size: 24rpx;
				color: #999999;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.icon {
				width: 11rpx;
				height: 19rpx;
				margin-left: 9rpx;
			}
		}
	}
</style>