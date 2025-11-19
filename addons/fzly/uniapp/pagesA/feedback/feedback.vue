<template>
	<view class="content font_family">
		<view class="feedback_box">
			<u--textarea height='306rpx' border='none' maxlength='500' v-model="query_data.content"
				placeholder="请输入您要反馈的问题(5-500字)" :placeholderStyle='placeholderStyle'></u--textarea>
		</view>
		<view class="phone_box f_z_b f_j">
			<view class="title">
				联系方式
			</view>
			<view class="ipt">
				<u--input placeholder="请输入手机号" border="none" inputAlign='right' v-model="query_data.tel"></u--input>
			</view>
		</view>
		<view class="btn f_zj" @click="sub">
			提交
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { feedback } from '@/api/user.js'
	export default {
		data() {
			return {
				placeholderStyle: {
					fontSize: '28rpx',
					color: '#999999',
				},
				query_data: {
					content: '',
					tel: ''
				},
			};
		},
		methods: {
			sub() {
				if (!this.query_data.content) {
					this.$refs.uToast.show({
						message: '请输入内容'
					})
					return
				}
				if (!this.query_data.tel) {
					this.$refs.uToast.show({
						message: '请输入手机号'
					})
					return
				} else if (!uni.$u.test.mobile(this.query_data.tel)) {
					this.$refs.uToast.show({
						message: '请输正确的手机号'
					})
					return
				}
				feedback(this.query_data).then(res => {
					if (res.code == 1) {
						this.$refs.uToast.show({
							message: '我们已经收到您的意见',
							complete: () => {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					}
				})
			}
		},
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;
		background-color: #ffffff;

		.feedback_box {
			// padding: 30rpx;
			// background: #F9F9FB;
			border-radius: 24rpx 24rpx 24rpx 24rpx;

			::v-deep .u-textarea {
				background: #F9F9FB;
				border-radius: 24rpx;
			}

			::v-deep .u-textarea__count {
				background: #F9F9FB !important;
			}
		}

		.phone_box {
			margin-top: 30rpx;
			padding: 20rpx 30rpx;
			background: #F9F9FB;
			border-radius: 24rpx 24rpx 24rpx 24rpx;
		}

		.btn {
			width: 658rpx;
			height: 84rpx;
			background: #FFE706;
			border-radius: 16rpx 16rpx 16rpx 16rpx;
			font-weight: 400;
			font-size: 28rpx;
			color: #232323;
			margin: 0 auto;
			margin-top: 100rpx;
		}
	}
</style>