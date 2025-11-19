<template>
	<view v-show="display">
		<u-navbar title="我的分销" leftIconColor='#fff' :titleStyle='{color:"#fff"}' :autoBack="true" :bgColor="bgColor">>
		</u-navbar>
		<view class="head">
			<view class="a-center head_user ">
				<image class="mr-20" :src="info.avatar" mode=""></image>
				<view class="f-size-36 color-f f-600">
					{{info.username}}
				</view>
			</view>
		</view>

		<view class="box_1">
			<view class="box_1_top a-center j-sb">
				<view class="box_1_top_item">
					<view class=" f-size-36 f-600 mb-10 color-23">
						{{info.commission}}
					</view>
					<view class=" f-size-24 color-9">
						可提现(元)
					</view>
				</view>
				<view class="box_1_top_item">
					<view class=" f-size-36 f-600 mb-10 color-23">
						{{info.recorded}}
					</view>
					<view class=" f-size-24 color-9">
						未入帐(元)
					</view>
				</view>
			</view>
			<view class="box_1_bom  a-center j-sb">
				<view class="box_1_bom_item">
					<view class=" f-size-36 f-600 mb-10 color-23">
						{{info.commissionAll}}
					</view>
					<view class=" f-size-24 color-9">
						累计佣金(元)
					</view>
				</view>
				<view class="box_1_bom_item">
					<view class=" f-size-36 f-600 mb-10 color-23">
						{{info.commissionOver || 0}}
					</view>
					<view class=" f-size-24 color-9">
						已提现(元)
					</view>
				</view>
				<view class="box_1_bom_item">
					<view class=" f-size-36 f-600 mb-10 color-23">
						{{info.commissionAll || 0}}
					</view>
					<view class=" f-size-24 color-9">
						总成交金额(元)
					</view>
				</view>
			</view>
			<view class="box_1_btn" @click="routerTo('/pagesG/TiXian/TiXian')">
				我要提现
			</view>
		</view>

		<view class="box_2 a-center j-sb">
			<view class="box_2_item" @click="routerTo('/pagesG/yongJin/yongJin')">
				<image src="../static/Gicon1.svg" mode=""></image>
				<view class=" f-size-24 mt-10 color-23">
					佣金流水
				</view>
			</view>
			<view class="box_2_item" @click="routerTo('/pagesG/MyYaoQin/MyYaoQin')">
				<image src="../static/Gicon2.svg" mode=""></image>
				<view class=" f-size-24 mt-10 color-23">
					我的邀请
				</view>
			</view>
			<view class="box_2_item" @click="routerTo('/pagesG/TiXianJiLu/TiXianJiLu')">
				<image src="../static/Gicon3.svg" mode=""></image>
				<view class=" f-size-24 mt-10 color-23">
					提现记录
				</view>
			</view>
		</view>

		<view class="box_3 a-center j-sb" @click="routerTo('/pagesG/haiBao/haiBao')">
			<view class="a-center j-sb">
				<image src="../static/Gicon4.png" mode=""></image>
				<view class="box_3_center ml-20">
					<view class="color-23 f-size-30 mb-10">
						推荐收入
					</view>
					<view class="color-6 f-size-24">
						推荐旅游享佣金
					</view>
				</view>
			</view>

			<view class="box_3_btn">
				前往邀请
			</view>

		</view>

	</view>
</template>

<script>
	import {
		distributionIndex
	} from '@/api/index.js'
	import { mapState } from 'vuex'
	export default {
		data() {
			return {
				bgColor: 'transparent',
				info: {},
				display: false,
			};
		},
		computed: {
			...mapState(['menuButtonInfo'])
		},
		onLoad() {
			this.distributionIndexApi()
		},
		onShow() {
			this.distributionIndexApi()
		},
		methods: {
			async distributionIndexApi() {
				uni.showLoading()
				let res = await distributionIndex()
				if (res.code == 1) {
					this.info = res.data
					this.display = true
					uni.hideLoading()
				}
			}
		}
	}
</script>

<style lang="scss">
	.head {
		height: 500rpx;
		padding: 20rpx;
		box-sizing: border-box;
		background-color: #FFE0C4;

		.head_user {
			margin-top: 130rpx;

			image {
				width: 100rpx;
				height: 100rpx;
				border-radius: 50%;
			}
		}
	}

	.box_3 {
		width: 700rpx;
		margin: 20rpx auto 0;
		background: linear-gradient(90deg, #feeae9ff 0%, #fef2f3ff 50%, #fff 100%);
		padding: 30rpx;
		box-sizing: border-box;
		border-radius: 30rpx;

		image {
			width: 100rpx;
			height: 100rpx;
			border-radius: 50%;
		}

		.box_3_btn {
			width: 150rpx;
			height: 60rpx;
			text-align: center;
			line-height: 60rpx;
			background-color: #ff6124ff;
			font-size: 24rpx;
			color: #fff;
			border-radius: 30rpx;

		}
	}

	.box_2 {
		width: 700rpx;
		margin: 20rpx auto 0;
		padding: 30rpx;
		box-sizing: border-box;
		border-radius: 20rpx;
		background-color: #fff;

		.box_2_item {
			width: 30%;
			text-align: center;

			image {
				width: 80rpx;
				height: 80rpx;
				border-radius: 50%;
			}

		}
	}

	.box_1 {
		width: 700rpx;
		margin: -240rpx auto 0;
		padding: 30rpx;
		box-sizing: border-box;
		border-radius: 20rpx;
		background-color: #fff;

		.box_1_top {
			padding: 30rpx 0;
			box-sizing: border-box;
			border-bottom: 1rpx solid #eee;

			.box_1_top_item {
				width: 50%;
				text-align: center;
			}
		}

		.box_1_bom {
			padding: 28rpx 0;
			box-sizing: border-box;

			.box_1_bom_item {
				width: 31%;
				text-align: center;
			}
		}

		.box_1_btn {
			width: 100%;
			height: 80rpx;
			line-height: 80rpx;
			color: #fff;
			font-size: 28rpx;
			color: #fff;
			text-align: center;
			background-color: #FFE0C4;
			border-radius: 50rpx;
			margin-top: 20rpx;
		}
	}
</style>