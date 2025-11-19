<template>
	<view v-show="display">
		<view class="card">
			<view class="f-size-34 mb-20 mr-20 color-23 f-600">
				提现账户
			</view>
			<view class="">
				<label class="radio a-center">
					<radio value="" checked="true" style="transform: scale(0.6);" /><text class="ml-10">线上转账</text>
				</label>
			</view>
		</view>

		<view class="box">
			<view class="box_center">
				<view class="f-size-32  mr-20 color-23 f-600">
					提现金额
				</view>
				<view class="box_center_ipt a-center">
					<span class="f-size-26 f-600">￥</span><input type="text" placeholder="请输入提现金额"
						v-model='withdraw_money' />
				</view>
				<view class="box_center_tips">
					可提现余额￥{{info.commission }}<span @click="withdraw_money=info.commission">全部提现</span>
				</view>
				<view class="box_center_tips">
					最低提现额度<span>￥{{info.min_withdraw}}</span>
				</view>
				<view class="box_btn mt-50" @click="draw">
					提现
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		distributionIndex,
		draw
	} from '@/api/index.js'
	import { get_openid } from '@/api/user.js'
	export default {
		data() {
			return {
				info: {},
				display: false,
				withdraw_money: ""
			};
		},
		onLoad() {
			this.distributionIndexApi()
		},
		methods: {
			async distributionIndexApi() {
				let res = await distributionIndex()
				if (res.code == 1) {
					this.info = res.data
					this.display = true
				}
			},
			async draw() {
				this.uniModel('温馨提示', '确定要提现吗').then(async res => {
					if (res == 'confirm') {
						uni.login({
							success: (res) => {
								get_openid({ code: res.code }).then(async ress => {
									let res = await draw({
										withdraw_money: this
											.withdraw_money,
										openid: ress.data.openid
									})
									if (res.code == 1) {
										this.showToast('申请成功，请等待审核')
										setTimeout(() => {
											uni.navigateBack()
										}, 1500)
									} else {
										this.showToast(res.msg)
									}
								})
							}
						})

					}
				})



			}
		}
	}
</script>

<style lang="scss">
	page {
		padding: 20rpx 30rpx;
		box-sizing: border-box;

	}

	.box {
		background-color: #fff;
		border-radius: 20rpx;

		.box_top {
			border-radius: 20rpx 20rpx 0 0;
			padding: 40rpx 30rpx 40rpx;
			box-sizing: border-box;
			background-color: #f3f3f3;
			border-bottom: 1rpx solid #eee;
		}

		.box_center {
			padding: 30rpx;
			box-sizing: border-box;

			.box_center_ipt {
				padding: 24rpx 0;
				box-sizing: border-box;
				border-bottom: 1rpx solid #f3f3f3;
			}

			.box_center_tips {
				font-size: 24rpx;
				color: #999;
				margin-top: 20rpx;

				span {
					margin-left: 20rpx;
					color: red;
				}
			}
		}

		.box_btn {
			text-align: center;
			line-height: 80rpx;
			height: 80rpx;
			border-radius: 10rpx;
			background-color: #56b45d;
			color: #fff;
		}
	}

	.card {
		margin-bottom: 20rpx;
		border-radius: 20rpx;
		padding: 30rpx;
		background-color: #fff;
	}
</style>