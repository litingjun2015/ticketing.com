<template>
	<view class="content font_family">
		<view class="header">
			<view class="user_top f_j" :style="{paddingTop:menuButtonInfo+'px'}">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_b.png" mode="" @click="back"></image>
				<u-avatar size="80rpx" @click="go_author"
					src="https://q1.itc.cn/q_70/images03/20240320/fcf023d835c54f78bac6c7efc98fbb4c.jpeg"></u-avatar>
				<text class="name" @click="go_author">江然凌</text>
				<view class="btn">
					关注
				</view>
			</view>
		</view>
		<!-- 轮播图 -->
		<view class="swiper_box">
			<u-swiper height='582rpx' :circular='true' :list="list1" @change="e => currentNum = e.current"
				@click="click"></u-swiper>
			<!-- 指示器 -->
			<view class="indicator">
				{{ currentNum + 1 }}/{{ list1.length }}
			</view>
			<!-- 位置 -->
			<view class="position f_j">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/pt.png" mode=""></image>
				<text>中国</text>
			</view>
			<view class="like_tag f_zj">
				<image class="like_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/like_t.png" mode=""></image>
			</view>
		</view>
		<view class="title">
			淮浦巷-合肥母亲河帮胖的历史记忆街淮浦巷-合肥母亲河帮胖的历史记忆街
		</view>
		<view class="article">
			黄工刺杀师傅还u师傅合法iu是发不发不发啥办法方巴斯 克方便不放假啊三部分建华吧就把房间哈比上海把建华 吧不好就发生纠纷不回家啊vs法v韩剧v阿贾师傅v家
		</view>
		<view class="time">
			一天前发布
		</view>
		<!-- 点赞框 -->
		<view class="like_box">
			<view class="btn f_z_b">
				<view class="item">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/collection.png" mode=""></image>
					<text class="num">10</text>
				</view>
				<view class="item">
					<image class="icon1" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/comment.png" mode=""></image>
					<text class="num">10</text>
				</view>
				<view class="item" @click="fx_show=true">
					<image class="icon1" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/zf.png" mode=""></image>
					<text class="num">10</text>
				</view>
			</view>
		</view>
		<!-- 评论 -->
		<view class="comment">
			<view class="comment_title">
				评论
			</view>
			<view class="user f_z_b" @click="comment_show=true">
				<u-avatar size="56rpx"
					src="https://q1.itc.cn/q_70/images03/20240320/fcf023d835c54f78bac6c7efc98fbb4c.jpeg"></u-avatar>
				<view class="ipt_box f_j">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
					<view class="ipt_text">
						精彩评论将被优先展示
					</view>
					<!-- <u--input placeholder="精彩评论将被优先展示" border="none" v-model="value"></u--input> -->
				</view>
			</view>
			<!-- 没有评论时 -->
			<!-- <view class="no_comment">
				来抢首评论 , 留下你的想法
			</view> -->
			<!-- 评论组件 -->
			<comment v-for="(item,index) in 2" :key="index" :msg='comment'></comment>
			<!-- 有评论 -->
			<view class="more">
				<u-divider lineColor='#9A9FA1' textSize='20rpx' textColor='#242424' text="展示更多"></u-divider>
			</view>
		</view>
		<!-- 推荐 -->
		<view class="tj">
			推荐
		</view>
		<view class="list f_z_b">
			<placecard type='1' v-for="(item,index) in 5" :key="index"></placecard>
		</view>
		<!-- 评论弹出框 -->
		<u-popup round='40rpx' :show="comment_show" @close="close">
			<scroll-view class="comment_box" scroll-y="true">
				<comment :msg='comment'></comment>
			</scroll-view>
			<!-- 回复框 -->
			<view class="bottom f_zj">
				<view class="ipt_box f_j">
					<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
					<u--input placeholder="说点什么吧" border="none" v-model="value" @change="change"></u--input>
				</view>
			</view>
		</u-popup>
		<!-- 分享框 -->
		<u-popup round='40rpx' :show="fx_show" closeable @close="fx_close">
			<view class="fx_box">
				<view class="fx_title">
					分享至
				</view>
				<view class="fx_item f">
					<button type="default" open-type="share">
						<view class="item f_d f_j">
							<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/vx.png" mode=""></image>
							<text class="text">微信好友</text>
						</view>
					</button>
					<!-- <button type="default" open-type="share">
						<view class="item f_d f_j" @click="share">
							<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/pyq.png" mode=""></image>
							<text class="text">朋友圈</text>
						</view>
					</button> -->
				</view>
				<!-- 取消按钮 -->
				<view class="fx_btn">
					取消
				</view>
			</view>
		</u-popup>
	</view>
</template>

<script>
	import comment from '@/components/comment.vue'
	import placecard from '@/components/place_card.vue'
	export default {
		components: { comment, placecard },
		data() {
			return {
				menuButtonInfo: 0,
				list1: [
					'https://img2.baidu.com/it/u=1217239032,91900238&fm=253&fmt=auto&app=138&f=JPEG?w=800&h=500',
					'https://q8.itc.cn/q_70/images01/20240414/ecc350b2709c4c5a878272309960c948.jpeg',
					'https://gss0.baidu.com/7Po3dSag_xI4khGko9WTAnF6hhy/zhidao/pic/item/2fdda3cc7cd98d104e8010d4233fb80e7aec90fa.jpg',
				],
				currentNum: 0,
				value: '',
				// 评论列表
				comment: {
					reply: [
						{ type: 1 },
						{ type: 1 },
						{ type: 1 },
						{ type: 1 },
					],
				},
				comment_show: false,
				fx_show: false
			};
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
		},
		onShareAppMessage(e) {
			console.log(e)
		},
		onShareTimeline() {

		},
		methods: {
			// 作者主页
			go_author() {
				uni.navigateTo({
					url: '/pagesA/index_menu/author'
				})
			},
			// 弹出框
			close() {
				this.comment_show = false
			},
			fx_close() {
				this.fx_show = false
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-bottom: 30rpx;

		.user_top {
			padding-left: 46rpx;
			padding-bottom: 20rpx;

			.icon {
				width: 18rpx;
				height: 36rpx;
				margin-right: 68rpx;
			}

			.name {
				margin-left: 22rpx;
				font-weight: 400;
				font-size: 32rpx;
				line-height: 44rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.btn {
				margin-left: 30rpx;
				background: #FFE706;
				border-radius: 622rpx 622rpx 622rpx 622rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #3D3D3D;
				font-style: normal;
				text-transform: none;
				padding: 4rpx 24rpx;
			}
		}

		.swiper_box {
			padding: 20rpx 30rpx;
			position: relative;

			.indicator {
				position: absolute;
				top: 50rpx;
				right: 50rpx;
				padding: 4rpx 30rpx;
				background: rgba(35, 35, 35, 0.1);
				border-radius: 1300rpx 1300rpx 1300rpx 1300rpx;
				font-weight: 400;
				font-size: 20rpx;
				color: #FFFFFF;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.position {
				position: absolute;
				left: 50rpx;
				bottom: 50rpx;
				padding: 10rpx 22rpx;
				background: rgba(0, 0, 0, 0.36);
				border-radius: 30rpx 30rpx 30rpx 30rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #FFFFFF;
				font-style: normal;
				text-transform: none;

				.icon {
					width: 24rpx;
					height: 26rpx;
					margin-right: 10rpx;
				}
			}

			.like_tag {
				position: absolute;
				right: 50rpx;
				bottom: 50rpx;
				width: 46rpx;
				height: 46rpx;
				background: rgba(35, 35, 35, 0.1);
				border-radius: 50%;

				.like_icon {
					width: 30rpx;
					height: 28rpx;
				}
			}
		}

		.title {
			font-weight: 400;
			font-size: 32rpx;
			color: #3D3D3D;
			text-align: left;
			font-style: normal;
			text-transform: none;
			padding: 0rpx 30rpx;
		}

		.article {
			font-family: PingFang SC, PingFang SC;
			font-weight: 400;
			font-size: 28rpx;
			color: #3D3D3D;
			text-align: left;
			font-style: normal;
			text-transform: none;
			padding: 0rpx 30rpx;
			margin-top: 15rpx;
		}

		.time {
			font-weight: 400;
			font-size: 22rpx;
			color: #999999;
			line-height: 40rpx;
			text-align: left;
			font-style: normal;
			text-transform: none;
			padding: 0rpx 30rpx;
			margin-top: 15rpx;
		}

		.like_box {
			margin-top: 15rpx;
			padding: 0rpx 30rpx;

			.btn {

				.item {
					.icon {
						width: 26rpx;
						height: 24rpx;
					}

					.icon1 {
						width: 30rpx;
						height: 26rpx;
					}

					.num {
						margin-left: 10rpx;
						font-weight: 400;
						font-size: 32rpx;
						color: #3D3D3D;
						line-height: 40rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}
				}
			}
		}

		.comment {
			padding: 0rpx 30rpx;
			margin-top: 40rpx;

			.comment_title {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.user {
				margin-top: 30rpx;
				margin-bottom: 20rpx;

				.ipt_box {
					padding: 0rpx 20rpx;
					flex: 1;
					margin-left: 20rpx;
					height: 54rpx;
					background: #FFFFFF;
					border-radius: 4rpx 4rpx 4rpx 4rpx;

					.icon {
						width: 26rpx;
						height: 22rpx;
						margin-right: 20rpx;
					}

					.ipt_text {
						font-weight: 400;
						font-size: 24rpx;
						color: #9A9FA1;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}
				}
			}

			.no_comment {
				margin-top: 30rpx;
				font-weight: 400;
				font-size: 22rpx;
				color: #999999;
				text-align: center;
				font-style: normal;
				text-transform: none;
			}

			.more {
				padding: 0rpx 180rpx;
			}
		}

		.tj {
			padding: 0rpx 30rpx;
			margin-top: 30rpx;
			font-weight: 400;
			font-size: 32rpx;
			color: #232323;
			text-align: left;
			font-style: normal;
			text-transform: none;
		}

		.list {
			flex-wrap: wrap;
			padding: 0rpx 30rpx;
			margin-top: 30rpx;
		}

		.comment_box {
			width: 100%;
			height: 696rpx;
			padding: 56rpx 36rpx;
			padding-bottom: 0rpx;
			box-sizing: border-box;
		}

		.bottom {
			margin-top: 20rpx;
			padding-bottom: calc(20rpx + env(safe-area-inset-bottom) /2);

			.ipt_box {
				padding: 8rpx 20rpx;
				width: 616rpx;
				height: 54rpx;
				background: #F8F9FC;
				border-radius: 4rpx 4rpx 4rpx 4rpx;
				box-sizing: border-box;

				.img {
					width: 26rpx;
					height: 22rpx;
					margin-right: 20rpx;
				}
			}
		}

		.fx_box {
			padding: 34rpx 40rpx;

			.fx_title {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.fx_item {
				margin-top: 38rpx;
				padding-bottom: 34rpx;
				border-bottom: 1rpx solid #DFDFDF;

				::v-deep button:after {
					border: none !important;
				}

				::v-deep button {
					background: none !important;
					margin: 0;
					padding: 0;
				}

				.item {
					margin-right: 40rpx;

					.img {
						width: 58rpx;
						height: 58rpx;
					}

					.text {
						font-weight: 400;
						font-size: 28rpx;
						color: #666666;
						text-align: left;
						font-style: normal;
						text-transform: none;
						margin-top: 10rpx;
					}
				}
			}

			.fx_btn {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				line-height: 33rpx;
				text-align: center;
				font-style: normal;
				text-transform: none;
				padding: 32rpx 0rpx;
			}
		}
	}
</style>