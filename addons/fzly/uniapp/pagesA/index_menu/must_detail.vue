<template>
	<view class="content font_family" v-if="detail_msg">
		<view class="header">
			<view class="user_top f_j" :style="{paddingTop:menuButtonInfo+'px'}">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_b.png" mode="" @click="back"></image>
				<u-avatar size="80rpx" @click="go_author" :src="detail_msg.avatar"></u-avatar>
				<text class="name" @click="go_author">{{detail_msg.username}}</text>
				<view class="btn" @click="follow_btn(1)" v-if="detail_msg.is_gz==0">
					关注
				</view>
				<view class="btn is_gz" @click="follow_btn(2)" v-else>
					已关注
				</view>
			</view>
		</view>
		<!-- 轮播图 -->
		<view class="swiper_box">
			<u-swiper height='582rpx' :circular='true' :list="detail_msg.images" @change="e => currentNum = e.current"
				@click="click"></u-swiper>
			<!-- 指示器 -->
			<view class="indicator">
				{{ currentNum + 1 }}/{{ detail_msg.images.length }}
			</view>
			<!-- 位置 -->
			<view class="position f_j">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/pt.png" mode=""></image>
				<text>{{detail_msg.city}}</text>
			</view>
			<!-- <view class="like_tag f_zj">
				<image class="like_icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/like_t.png" mode=""></image>
			</view> -->
		</view>
		<view class="title">
			{{detail_msg.title}}
		</view>
		<view class="article">
			<u-parse :content="detail_msg.content"></u-parse>
		</view>
		<view class="time">
			{{detail_msg.createtime}}发布
		</view>
		<!-- 点赞框 -->
		<view class="like_box">
			<view class="btn f_z_b">
				<view class="item">
					<!-- <image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/collection.png" mode=""></image> -->
					<image @click="sc_btn(1)" v-if="detail_msg.is_sc==0" class="icon"
						src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/collection.png" mode=""></image>
					<image @click="sc_btn(2)" v-else class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/star_t.png" mode=""></image>
					<text @click="sc_btn(1)" v-if="detail_msg.is_sc==0" class="num">{{detail_msg.sc_nums}}</text>
					<text @click="sc_btn(2)" v-else class="num">{{detail_msg.sc_nums}}</text>
				</view>
				<view class="item" @click="show_comment_box">
					<image class="icon1" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/comment.png" mode=""></image>
					<text class="num">{{detail_msg.common_nums}}</text>
				</view>
				<!-- #ifdef MP-WEIXIN -->
				<view class="item" @click="fx_show=true">
					<image class="icon1" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/zf.png" mode=""></image>
					<!-- <text class="num">10</text> -->
				</view>
				<!-- #endif -->
			</view>
		</view>
		<!-- 评论 -->
		<view class="comment">
			<view class="comment_title">
				评论
			</view>
			<view class="user f_z_b" @click="show_comment_box">
				<u-avatar size="56rpx" :src="user_info.avatar" mode="aspectFill"></u-avatar>
				<view class="ipt_box f_j">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png"></image>
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
			<comment v-for="(item,index) in comment" :key="index" :msg='item' @user_reply='user_reply'></comment>
			<!-- 有评论 -->
			<view class="more">
				<!-- 加载更多 -->
				<u-loadmore line @loadmore='get_ticket_list' :status="status" :loading-text="loadingText"
					:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
			</view>
		</view>
		<!-- 评论弹出框 -->
		<u-popup round='40rpx' :show="comment_show" @close="close">
			<scroll-view class="comment_box" scroll-y="true">
				<comment v-for="(item,index) in comment" :key="index" :msg='item' @user_reply='user_reply'></comment>
				<view class="empty_top" v-if="comment.length==0">
					<u-empty text='快来分享你的评论吧~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
					</u-empty>
				</view>
			</scroll-view>
			<!-- 回复框 -->
			<view class="bottom f_zj">
				<view class="ipt_box f_j">
					<image class="img" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
					<u--input :placeholder="placeholder" border="none" v-model="publish_commont.content"
						@confirm='confirm'></u--input>
				</view>
			</view>
			<!-- 输入框占位 -->
			<view class="" :style="{height:KeyboardHeight+'rpx'}">

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
				<!-- <view class="fx_btn">
					取消
				</view> -->
			</view>
		</u-popup>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import comment from '@/components/comment.vue'
	import placecard from '@/components/place_card.vue'
	import { detail, get_comment, comment_api } from "@/api/index_menu/index.js"
	import mixins from '@/mixins/user.js'
	export default {
		components: { comment, placecard },
		mixins: [mixins],
		data() {
			return {
				menuButtonInfo: 0,
				currentNum: 0,
				value: '',
				// 评论列表
				comment: [],
				comment_show: false,
				fx_show: false,
				query_data: {
					id: '',
				},
				dz_sc: {
					option: '',
					type: 3,
					id: ''
				},
				detail_msg: '',
				query_commont: {
					id: '',
					page: 1,
					type: 2
				},
				// 发表评论参数
				publish_commont: {
					id: '',
					p_id: '',
					u_id: '',
					content: '',
					type: 2
				},
				placeholder: '说点什么吧',
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			};
		},
		computed: {
			...mapState(['user_info', 'KeyboardHeight'])
		},
		onLoad(e) {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.query_data.id = e.id
			this.dz_sc.id = e.id
			this.query_commont.id = e.id
			this.publish_commont.id = e.id
			this.get_detail()
			this.get_comment_list()
		},
		onShareAppMessage(e) {
			return {
				title: this.detail_msg.title,
				path: `pagesA/index_menu/must_detail?id=${this.query_data.id}`,
				imageUrl: this.detail_msg.images[0],
				desc: '快来跟我一起吧'
			}
		},
		onShareTimeline() {

		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_comment_list()
			}
		},
		methods: {
			// 作者主页
			go_author() {
				uni.navigateTo({
					url: '/pagesA/index_menu/author'
				})
			},
			show_comment_box() {
				if (!this.user_info) {
					this.user_no_login()
					return
				}
				this.comment_show = true
			},
			// 弹出框
			close() {
				this.comment_show = false
				this.placeholder = '说点什么吧'
			},
			fx_close() {
				this.fx_show = false
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// 获取详情
			get_detail() {
				detail(this.query_data).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.detail_msg = res.data
					}
				})
			},
			// 获取评论列表
			get_comment_list() {
				this.status = 'loading'
				get_comment(this.query_commont).then(res => {
					console.log(res)
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.status = 'loadmore'
							this.comment = [...this.comment, ...res.data.data]
							this.query_commont.page += 1
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
			// 用户发表评论
			confirm() {
				if (!this.publish_commont.content) {
					this.$refs.uToast.show({
						type: 'default',
						message: '请输入内容'
					})
					return
				}
				comment_api(this.publish_commont).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.$refs.uToast.show({
							type: 'success',
							message: res.msg
						})
						// 发表评论之后重置评论参数
						this.publish_commont.content = ''
						this.publish_commont.p_id = ''
						this.publish_commont.u_id = ''
						// 更新评论列表
						this.comment = []
						this.query_commont.page = 1
						this.get_comment_list()
						this.comment_show = false
					} else {
						this.$refs.uToast.show({
							type: 'error',
							message: res.msg
						})
					}
				})
			},
			// 用户回复
			user_reply(data, type) {
				if (!this.user_info) {
					this.user_no_login()
					return
				}
				if (type == 1) {
					this.publish_commont.p_id = data.id
				} else {
					this.publish_commont.p_id = data.p_id
				}
				this.publish_commont.u_id = data.user_id
				this.placeholder = '回复' + data.username
				this.comment_show = true
			}
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

			.is_gz {
				background-color: #ffffff;
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
				// padding-bottom: 34rpx;
				// border-bottom: 1rpx solid #DFDFDF;

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