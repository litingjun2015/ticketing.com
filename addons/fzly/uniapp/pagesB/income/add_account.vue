<template>
	<view class="content font_family">
		<view class="msg_box">
			<view class="item f_j">
				<view class="left">
					类型
				</view>
				<view class="right f_z_b f_j" @click="type_show=true">
					<view class="" :class="{check_text:query_data.type}">
						{{type_text}}
					</view>
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
			<view class="item f_j" v-if="type_text!='银行卡'">
				<view class="left">
					账号
				</view>
				<view class="right">
					<u--input placeholderStyle='{color:#999999}' :placeholder="`请输入${type_text}账号`" border="none"
						v-model="query_data.zh_code"></u--input>
				</view>
			</view>
			<view class="item f_j" v-if="type_text=='银行卡'">
				<view class="left">
					卡号
				</view>
				<view class="right">
					<u--input placeholderStyle='{color:#999999}' placeholder="请输入卡号" border="none"
						v-model="query_data.bank_code"></u--input>
				</view>
			</view>
			<view class="item f_j" v-if="type_text=='银行卡'">
				<view class="left">
					手机号
				</view>
				<view class="right">
					<u--input placeholderStyle='{color:#999999}' placeholder="请输入银行预留手机号" border="none"
						v-model="query_data.bank_mobile"></u--input>
				</view>
			</view>
			<view class="item f_j" v-if="type_text=='银行卡'">
				<view class="left">
					验证码
				</view>
				<view class="right f_z_b f_j">
					<u--input placeholderStyle='{color:#999999}' placeholder="请输入验证码" border="none"
						v-model="query_data.code"></u--input>
					<view class="code f_zj" @click="getCode">
						{{code_tip}}
					</view>
				</view>
			</view>
		</view>
		<view class="btn f_zj" @click="add">
			确认添加
		</view>
		<u-code :seconds="seconds" changeText='xs后重新获取' keepRunning @end="end" @start="start" ref="uCode"
			@change="codeChange"></u-code>
		<!-- 银行卡类型选择 -->
		<u-picker :show="type_show" @confirm='confirm' @cancel='type_show=false' cancelColor='#999999'
			confirmColor='#FFAE35' :columns="columns"></u-picker>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { send } from '@/api/public.js'
	import { add_withdrawal_account } from '@/api/user.js'
	export default {
		data() {
			return {
				seconds: 60,
				code_tip: '',
				query_data: {
					bank_code: '',
					type: 1,
					bank_mobile: '',
					code: '',
					zh_code: ''
				},
				type_text: '银行卡',
				type_show: false,
				columns: [
					[
						{ text: '银行卡', type: 1 },
						// { text: '微信', type: 2 },
						{ text: '支付宝', type: 3 },
					]
				]
			};
		},
		methods: {
			codeChange(text) {
				this.code_tip = text;
			},
			confirm(e) {
				this.query_data = {
					bank_code: '',
					type: 1,
					bank_mobile: '',
					code: '',
					zh_code: ''
				}
				this.type_text = e.value[0].text
				this.query_data.type = e.value[0].type
				this.type_show = false
			},
			getCode() {
				// 判断手机号格式
				if (!uni.$u.test.mobile(this.query_data.bank_mobile)) {
					uni.$u.toast('请输入正确的手机号');
					return
				}
				if (this.$refs.uCode.canGetCode) {
					// this.$refs.uCode.start();
					// 向后端请求验证码
					uni.showLoading({
						title: '正在获取验证码'
					})
					send({ mobile: this.query_data.bank_mobile }).then(res => {
						uni.hideLoading();
						if (res.code == 1) {
							// 这里此提示会被this.start()方法中的提示覆盖
							uni.$u.toast('验证码已发送');
							// 通知验证码组件内部开始倒计时
							this.$refs.uCode.start();
						} else {
							uni.$u.toast(res.msg);
						}

						console.log(res)
					})
				} else {
					uni.$u.toast('倒计时结束后再发送');
				}
			},
			// 添加账户
			add() {
				if (this.add_account_kong(this.query_data)) {
					return
				}
				add_withdrawal_account(this.query_data).then(res => {
					console.log(res);
					if (res.code == 1) {
						this.$refs.uToast.show({
							message: '提交成功，等待审核',
							duration: 1000,
							complete: () => {
								uni.navigateBack({
									delta: 1
								})
							}
						})
					} else {
						uni.$u.toast(res.msg);
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;

		.msg_box {
			padding: 30rpx 40rpx;
			padding-top: 0rpx;
			background: #FFFFFF;
			border-radius: 14rpx 14rpx 14rpx 14rpx;

			.item {
				padding: 30rpx 0rpx;
				border-bottom: 1rpx solid #EBEBEB;

				.left {
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
					width: 100rpx;
				}

				.right {
					flex: 1;
					margin-left: 30rpx;
					color: #999999;

					.icon {
						width: 10rpx;
						height: 17rpx;
					}

					.code {
						width: 183rpx;
						height: 59rpx;
						border-radius: 831rpx 831rpx 831rpx 831rpx;
						border: 2rpx solid #FFAE35;
						font-weight: 400;
						font-size: 24rpx;
						color: #FFAE35;
					}

					.check_text {
						color: #000000;
					}
				}
			}
		}

		.btn {
			width: 100%;
			height: 85rpx;
			background: #FFAE35;
			border-radius: 16rpx 16rpx 16rpx 16rpx;
			font-weight: 400;
			font-size: 28rpx;
			color: #FFFFFF;
			margin-top: 50rpx;
		}

		// ::v-deep.uni-picker-view-indicato {
		// 	background: #F6F7FB !important;
		// }

		::v-deep.u-popup__content {
			border-radius: 41rpx 41rpx 0rpx 0rpx !important;
		}


	}
</style>