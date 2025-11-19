<template>
	<view class="conten">
		<!-- 顶部 -->
		<view class="header">
			<view style="width: 100vw" :style="{height:menuButtonInfo+'px'}"></view>
			<view class="header_title">
				<image class="icon" src="../../static/msg/back_icon.png" mode="" @click="back"></image>
				<view class="text global-font">
					消息
				</view>
			</view>
		</view>
		<!-- 消息 -->
		<scroll-view class="chat_list" scroll-y>
			<!-- 占位 -->
			<view class="top_seize">

			</view>
			<view class="item" v-for="(item,index) in arr" :key="index">
				<!-- 别人的消息 -->
				<view class="item_box" v-if="item.type==1">
					<u-avatar src='https://q1.itc.cn/q_70/images03/20240320/fcf023d835c54f78bac6c7efc98fbb4c.jpeg'
						size='82rpx'></u-avatar>
					<image class="msg_icon" src="../../static/msg/msg_left.png" mode=""></image>
					<view class="msg_box global-font">
						<view class="msg">
							啥u厚度阿是蛋黄酥啥u厚度阿是蛋黄酥 啥u厚度阿是蛋黄酥
						</view>
					</view>
				</view>
				<!-- 自己的消息 -->
				<view class="item_box_right" v-else>
					<view class="msg_box global-font">
						<view class="msg_right">
							啥u厚度阿是蛋黄酥啥u厚度阿是蛋黄酥 啥u厚度阿是蛋黄酥
							啥u厚度阿是蛋黄酥啥u厚度阿是蛋黄酥 啥u厚度阿是蛋黄酥
							啥u厚度阿是蛋黄酥啥u厚度阿是蛋黄酥 啥u厚度阿是蛋黄酥
							啥u厚度阿是蛋黄酥啥u厚度阿是蛋黄酥 啥u厚度阿是蛋黄酥
						</view>
					</view>
					<image class="msg_icon_right" src="../../static/msg/msg_right.png" mode=""></image>
					<u-avatar src='https://q1.itc.cn/q_70/images03/20240320/fcf023d835c54f78bac6c7efc98fbb4c.jpeg'
						size='82rpx'></u-avatar>
				</view>
			</view>
			<!-- 占位 -->
			<view class="bottom_seize" :style="{paddingBottom:bottom_seize_padding+'rpx'}">

			</view>
		</scroll-view>
		<!-- 底部 -->
		<view class="bottom" :style="{paddingBottom:paddingBottom+'rpx',bottom:KeyboardHeight+'px'}">
			<!-- 功能区 -->
			<view class="chat_function">
				<image class="speech" src="../../static/msg/speech.png" mode=""></image>
				<view class="ipt">
					<u--textarea maxlength='250' :adjustPosition='false' autoHeight placeholder="请输入内容" border="none"
						v-model="chat_msg" @change="change" @confirm='send_confirm'></u--textarea>
				</view>
				<image @click="send_img" class="chat_add" src="../../static/msg/chat_add.png" mode=""></image>
			</view>
			<!-- 发送图片 -->
			<view class="send_img" v-if="send_img_show" ref="sendImg">
				<view class="item">
					<image class="tu" src="../../static/msg/send_img.png" mode=""></image>
					<text>图片</text>
				</view>
				<view class="item">
					<image class="pai" src="../../static/msg/photograph.png" mode=""></image>
					<text>拍照</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				// 键盘高度
				KeyboardHeight: 0,
				// 底部安全区
				paddingBottom: 0,
				// 底部占位padding
				bottom_seize_padding: 0,
				arr: [{ type: 1 }, { type: 2 }, { type: 1 }, { type: 1 }, { type: 2 }, { type: 2 }, { type: 2 }, ],
				// 聊天消息
				chat_msg: '',
				// 发送图片
				send_img_show: false
			}
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// 监听键盘的弹出事件
			uni.onKeyboardHeightChange((res) => {
				if (res.height > 0) {
					// 键盘弹出时，根据键盘的高度调整固定元素的位置
					this.KeyboardHeight = res.height;
				} else {
					// 键盘收起时恢复初始位置
					this.KeyboardHeight = 0;
				}
			});
			// 获取系统信息
			uni.getSystemInfo({
				success: (res) => {
					// console.log(res)
					this.paddingBottom = res.safeArea.bottom - res.safeArea.height
				}
			});
		},
		methods: {
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			send_img() {
				this.send_img_show = !this.send_img_show
				if (this.send_img_show) {
					this.bottom_seize_padding = 100
				} else {
					this.bottom_seize_padding = 0
				}
			},
			send_confirm() {
				console.log('发送')
			}
		},
	}
</script>

<style lang="scss" scoped>
	.conten {
		background: #F8F9FC;
		min-height: 100vh;
		// display: flex;
		// flex-direction: column;
	}

	.header {
		position: fixed;
		top: 0;
		padding: 0rpx 44rpx;
		background: white;
		padding-bottom: 36rpx;
		z-index: 10000000;

		.header_title {
			display: flex;
			align-items: center;

			.icon {
				width: 52rpx;
				height: 52rpx;
			}

			.text {
				margin-left: 260rpx;
				font-weight: 500;
				font-size: 32rpx;
				color: #3D3D3D;
				line-height: 38rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}
		}
	}

	.chat_list {
		padding: 10rpx 44rpx;
		box-sizing: border-box;
		height: 100vh;
		// background: red;
		flex: 1;

		.top_seize {
			height: 200rpx;
		}

		.bottom_seize {
			padding-bottom: 800rpx;
			height: 140rpx;
		}

		.item {
			display: flex;
			flex-direction: column;
			justify-content: flex-end;
			margin-bottom: 50rpx;

			.item_box {
				display: flex;

				.msg_icon {
					width: 20rpx;
					height: 20rpx;
					margin-right: -2rpx;
					margin-top: 30rpx;
					margin-left: 20rpx;
				}

				.msg_box {
					border-radius: 8rpx 8rpx 8rpx 8rpx;
					background: white;
					padding: 12rpx 26rpx;
					font-weight: 500;
					font-size: 28rpx;
					color: #3D3D3D;
					line-height: 38rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;

					.msg {
						width: 400rpx;
					}

					.msg_right {}
				}
			}

			.item_box_right {
				display: flex;
				justify-content: flex-end;

				.msg_icon_right {
					width: 20rpx;
					height: 20rpx;
					margin-right: 20rpx;
					margin-top: 30rpx;
					margin-left: -5rpx;
				}

				.msg_box {
					border-radius: 8rpx 8rpx 8rpx 8rpx;
					background: #2970FE;
					padding: 12rpx 26rpx;
					font-weight: 500;
					font-size: 28rpx;
					color: white;
					line-height: 38rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;


					.msg_right {
						width: 400rpx;
					}
				}
			}
		}
	}

	.bottom {
		position: fixed;
		bottom: 0;
		left: 0;
		background: #FFFFFF;
		width: 100%;
		transition: all 0.2s;

		.chat_function {
			padding: 24rpx 50rpx;
			display: flex;
			align-items: center;
			justify-content: center;

			.speech {
				width: 42rpx;
				height: 56rpx;
			}

			.ipt {
				margin: 0rpx 30rpx 0rpx 20rpx;
				// padding: 8rpx 18rpx;
				width: 506rpx;
				background: #F5F6F7;
				border-radius: 4rpx 4rpx 4rpx 4rpx;

				::v-deep .u-textarea {
					background: #F5F6F7;
				}
			}

			.chat_add {
				width: 52rpx;
				height: 52rpx;
			}
		}

		.send_img {
			box-sizing: border-box;
			display: flex;
			padding: 0rpx 64rpx;
			height: 100rpx;

			.item {
				display: flex;
				flex-direction: column;
				justify-content: center;
				align-items: center;
				margin-right: 64rpx;

				.tu {
					width: 40rpx;
					height: 36rpx;
				}

				.pai {
					width: 46rpx;
					height: 36rpx;
				}
			}
		}
	}
</style>