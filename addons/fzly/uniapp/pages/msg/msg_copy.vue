<template>
	<view class="content font_family">
		<view class="header" :style="{paddingTop:menuButtonInfo+'px'}">
			<text>消息</text>
		</view>
		<!-- 消息列表 -->
		<view class="msg_box">
			<view class="item">
				<u-swipe-action @click="swipe_action(index)">
					<u-swipe-action-item v-for="(item,index) in 4" :key="index" :name='index' :options="options1"
						@click='swipe_action_btn'>
						<view class="swipe-action">
							<view class="swipe-action__content">
								<view class="msg_content f_z_b" @click="go_room(index)">
									<view class="left f_zj">
										<u-avatar
											src="https://q1.itc.cn/q_70/images03/20240320/fcf023d835c54f78bac6c7efc98fbb4c.jpeg"
											size="72rpx"></u-avatar>
										<view class="msg">
											<view class="msg_title">
												叶秋
											</view>
											<view class="msg_des">
												这个地方也不错，有兴趣下次一块去吗这个地方也不错，有兴趣下次一块去吗
											</view>
										</view>
									</view>
									<!-- 右侧 -->
									<view class="right f_d f_z_b">
										<view class="time">
											刚刚
										</view>
										<view class="tag f_zj">
											3
										</view>
									</view>
								</view>
							</view>
						</view>
					</u-swipe-action-item>
				</u-swipe-action>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				// 胶囊
				menuButtonInfo: 0,
				options1: [{
					btn: '置顶',
					style: {
						backgroundColor: '#ffffff'
					}
				}, {
					btn: '删除',
					style: {
						backgroundColor: '#ffffff'
					}
				}]
			};
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
		},
		methods: {
			swipe_action(index) {
				console.log(index)
			},
			swipe_action_btn(index) {
				console.log(index)
			},
			go_room(index) {
				uni.navigateTo({
					url: '/pages/msg/msg_room'
				})
			}
		},
	};
</script>

<style lang="scss">
	.u-page {
		padding: 0;
	}

	.u-demo-block__title {
		padding: 10px 0 2px 15px;
	}

	.swipe-action {
		&__content {
			padding: 25rpx 0;

			&__text {
				font-size: 15px;
				color: $u-main-color;
				padding-left: 30rpx;
			}
		}
	}

	.content {
		background-color: white;

		::v-deep .u-border-top {
			border: none;
		}

		::v-deep .u-border-bottom {
			border: none;
		}

		.header {
			font-weight: 400;
			font-size: 48rpx;
			color: #000000;
			line-height: 44rpx;
			font-style: normal;
			text-transform: none;
			margin-left: 30rpx;
		}

		.msg_box {
			margin-top: 48rpx;

			.item {
				.msg_content {
					padding: 0rpx 30rpx;

					.left {
						.msg {
							margin-left: 24rpx;

							.msg_title {
								font-weight: 400;
								font-size: 28rpx;
								color: #232323;
								line-height: 44rpx;
								font-style: normal;
								text-transform: none;
							}

							.msg_des {
								font-weight: 400;
								font-size: 22rpx;
								color: #666666;
								line-height: 44rpx;
								font-style: normal;
								text-transform: none;
								width: 400rpx;
								overflow: hidden;
								white-space: nowrap;
								text-overflow: ellipsis;
							}
						}
					}

					.right {
						.time {
							font-weight: 400;
							font-size: 22rpx;
							color: rgba(35, 35, 35, 0.6);
							line-height: 44rpx;
							font-style: normal;
							text-transform: none;
						}

						.tag {
							height: 32rpx;
							background: #FF5967;
							border-radius: 30rpx 30rpx 30rpx 30rpx;
							padding: 0rpx 18rpx;
							font-weight: 400;
							font-size: 20rpx;
							color: #FFFFFF;
							line-height: 44rpx;
							font-style: normal;
							text-transform: none;
						}
					}
				}
			}
		}
	}
</style>