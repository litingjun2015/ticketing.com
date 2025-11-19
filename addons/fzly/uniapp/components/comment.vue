<template>
	<view class="comment font_family">
		<view class="outher">
			<view class="user f_j">
				<u-avatar :src="msg.avatar" size="56rpx"></u-avatar>
				<view class="name">
					{{msg.username}}
				</view>
				<view class="tag f_zj" v-if="msg.is_zz==1">
					作者
				</view>
			</view>
			<view class="comment_content">
				{{msg.content}}
			</view>
			<view class="comment_date">
				<text class="day">{{msg.createtime}}</text>
				<text class="reply" @click="reply(msg,1)">回复</text>
			</view>
			<!-- 回复评论 -->
			<view class="reply_box" v-for="(item,index) in msg.chileren" :key="index">
				<view class="reply_user f_j">
					<u-avatar :src="item.avatar" size="40rpx"></u-avatar>
					<view class="name">
						{{item.username}}
					</view>
				</view>
				<view class="reply_content">
					<text>回复<text style="color: #9A9FA1;">@{{item.hf_username}}</text></text>
					{{item.content}}
				</view>
				<view class="reply_time">
					<text class="day">
						{{item.createtime}}
					</text>
					<text class="reply" @click="reply(item,2)">
						回复
					</text>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		props: ['msg'],
		name: "comment",
		data() {
			return {

			};
		},
		methods: {
			reply(data, type) {
				this.$emit('user_reply', data, type)
			},
		}
	}
</script>

<style lang="scss" scoped>
	.comment {
		margin-bottom: 20rpx;

		.outher {
			.user {
				.name {
					margin-left: 16rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #9A9FA1;
					line-height: 34rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.tag {
					margin-left: 16rpx;
					width: 104rpx;
					height: 36rpx;
					background: rgba(255, 87, 87, 0.1);
					border-radius: 10rpx 10rpx 10rpx 10rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #FF5757;
					line-height: 34rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}
		}

		.comment_content {
			margin-left: 72rpx;
			font-weight: 400;
			font-size: 24rpx;
			color: #3D3D3D;
			line-height: 34rpx;
			text-align: left;
			font-style: normal;
			text-transform: none;
		}

		.comment_date {
			margin-left: 72rpx;

			.day {
				font-weight: 400;
				font-size: 20rpx;
				color: #9A9FA1;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.reply {
				font-weight: 400;
				font-size: 20rpx;
				color: #242424;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
				margin-left: 28rpx;
			}
		}

		.reply_box {
			margin-top: 26rpx;
			margin-left: 72rpx;

			.reply_user {
				.name {
					font-weight: 400;
					font-size: 22rpx;
					color: #9A9FA1;
					line-height: 32rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-left: 14rpx;
				}
			}

			.reply_content {
				font-weight: 400;
				font-size: 22rpx;
				color: #3D3D3D;
				line-height: 32rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
				margin-top: 10rpx;
			}

			.reply_time {
				.day {
					font-weight: 400;
					font-size: 20rpx;
					color: #9A9FA1;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.reply {
					font-weight: 400;
					font-size: 20rpx;
					color: #242424;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-left: 28rpx;
				}
			}
		}
	}
</style>