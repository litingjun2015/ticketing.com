<template>
	<view class="content font_family">
		<view class="msg_box">
			<view class="item f_j f_z_b p_title">
				<view class="left">
					名称
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' placeholder="请输入内容" border="none" v-model="mp.intro"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					标签
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' placeholder="请输入内容" border="none" v-model="mp.label"></u--input>
				</view>
			</view>
			<view class="item p_title">
				<view class="left">
					简述
				</view>
				<view class="textarea_box">
					<!-- <u--input inputAlign='right' placeholder="请输入内容" border="none" v-model="mp.text"></u--input> -->
					<u--textarea v-model="mp.text" placeholder="请输入内容" height='100'></u--textarea>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					来源
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' placeholder="请输入内容" border="none" v-model="mp.score"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					价格
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' type='digit' placeholder="请输入内容" border="none"
						v-model="mp.price"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					划线价
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' type='digit' placeholder="请输入内容" border="none"
						v-model="mp.line_price"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					已售
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' type='number' placeholder="请输入内容" border="none"
						v-model="mp.ys"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					是否特价
				</view>
				<view class="ipt_box f_j">
					<text style="margin-right:15rpx;">否</text>
					<u-switch v-model="value" activeColor="#FFAE35" @change="change"></u-switch>
					<text style="margin-left: 15rpx;">是</text>
				</view>
			</view>
			<!-- 按钮 -->
			<view class="btn f_zj" @click="submit">
				确认
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				value: false,
				mp: {},
			}
		},
		onLoad(e) {
			if (e.msg) {
				this.mp = JSON.parse(e.msg)
				if (this.mp.tj == 1) {
					this.value = true
				} else {
					this.value = false
				}
				console.log(this.mp)
			}
		},
		methods: {
			change(e) {
				if (e) {
					this.mp.tj = 1
				} else {
					this.mp.tj = 0
				}
			},
			submit() {
				uni.$emit('mp', this.mp)
				uni.navigateBack({
					delta: 1
				})
			}
		},

	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;

		.msg_box {
			margin-top: 20rpx;
			padding: 20rpx;
			background: #FFFFFF;
			border-radius: 15rpx 15rpx 15rpx 15rpx;

			.item {
				padding: 20rpx 0rpx;
				border-bottom: 1rpx solid #F0F0F0;

				.sel-text {
					margin-right: 10rpx;
				}

				.left {
					font-weight: 500;
					font-size: 28rpx;
					color: #242424;
				}

				.type {
					.icon {
						margin-left: 15rpx;
						width: 11rpx;
						height: 19rpx;
					}
				}

				.check {
					// border: 1rpx solid red;
					width: 500rpx;
					flex-wrap: wrap;
					justify-content: flex-end;

					.item {
						margin-bottom: 10rpx;
						margin-left: 8rpx;
					}

					.check_item {
						padding: 8rpx 14rpx;
						background: #F8F8F8;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #3D3D3D;
					}

					.check_action {
						padding: 8rpx 14rpx;
						background: #E9F1F9;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #237DCB;
					}
				}

				.textarea_box {
					margin-top: 20rpx;

					// ::v-deep .u-textarea {
					// 	padding: 0rpx;
					// }
				}
			}

			.btn {
				width: 100%;
				height: 84rpx;
				background: #FFAE35;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				margin-top: 20rpx;
				color: #FFFFFF;
			}
		}
	}
</style>