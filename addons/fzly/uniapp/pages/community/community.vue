<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<!-- 头部 -->
		<view class="p_f_top f_top" :style="{paddingTop:menuButtonInfo+'px'}">
			<view class="header f_j f_z">
				<view class="left" @click="go('/pagesA/community_search/community_search')">
					<!-- <image class="img" src="../../static/index/magnif.png" mode=""></image> -->
				</view>
				<view class="right f_zj">
					<view class="item" v-for="(item,index) in header_check" :key="index"
						@click="header_checkbtn(index,item.type)">
						<text class="text" :class="{check_action:header_check_action==index}">{{item.text}}</text>
					</view>
				</view>
			</view>
			<!-- 滑动菜单 -->
			<u-tabs :list="menu_list" keyName='title' lineWidth='52rpx' lineHeight='12rpx' @click="tabs_click"
				lineColor='#FFE706' :activeStyle='activeStyle'></u-tabs>
		</view>
		<!-- 列表 -->
		<view class="list" v-if="trends_list.length>0">
			<view class="item" v-for="(item,index) in trends_list" :key="index">
				<view class="user f_z_b f_j">
					<view class="left f_j">
						<u-avatar :src="item.avatar" mode="aspectFill" size="68rpx" @click="go_author(item)"></u-avatar>
						<view class="msg f_d f_z_b">
							<text class="name">{{item.username}}</text>
							<text class="date">{{item.uptime}}</text>
						</view>
					</view>
					<view class="" v-if="item.user_id!=user_info.id">
						<view v-if="item.is_gz==0" @click="gz_btn(1,item)" class="right f_zj">
							<image class="icon" src="../../static/community/follow.png" mode=""></image>
							<text class="text">关注</text>
						</view>
						<view v-else @click="gz_btn(2,item)" class="right f_zj">
							<text class="text">已关注</text>
						</view>
					</view>
					<view class="" v-else @click="sc_dt(item.id)">
						<image class="sc" src="../../static/public/sc_dt.png" mode=""></image>
					</view>
				</view>
				<view class="title" v-html="item.title"></view>
                <view class="des" v-html="item.content"></view>
				<view class="img_box" v-if="item.images.length>2">
					<image :class="{imgmargin:img_index.includes(img_index1)}" class="img" :src="img_item"
						mode="aspectFill" v-for="(img_item,img_index1) in item.images" :key="img_index1"
						@click="view_img(item.images,img_index1)"></image>
				</view>
				<view class="img_box" v-else>
					<image class="img_big" v-for="(img_item,img_index2) in item.images" :key="img_index2"
						:src="img_item" mode="aspectFill" @click="view_img(item.images,img_index2)"></image>
				</view>
				<!-- 评论 -->
				<view class="comment f_z_b">
					<view class="left f_j" @click.stop="open(item.id)">
						<u-avatar v-if="user_info" :src="user_info.avatar" size="48rpx" mode="aspectFill"></u-avatar>
						<text class="text">发表评论</text>
					</view>
					<view class="right f_j">
						<view class="like">
							<image v-if="item.is_dz==0" @click="dz_btn(1,item)" class="icon"
								src="../../static/community/like.png" mode="">
							</image>
							<image v-else class="icon" @click="dz_btn(2,item)"
								src="../../static/community/like_action.png" mode=""></image>
							<text class="text">{{item.dz_nums<99?item.dz_nums:'99+'}}</text>
						</view>
						<view class="like" @click.stop="open(item.id)">
							<image class="icon" src="../../static/community/comment.png" mode=""></image>
							<text class="text">{{item.common_nums}}</text>
						</view>
					</view>
				</view>
			</view>
		</view>
		<view style="padding-top: 200rpx;" class="empty_top" v-if='trends_list.length==0'>
			<u-empty text='没有数据啦~' icon="/static/public/kong.png">
			</u-empty>
		</view>
		<!-- 发布按钮 -->
		<movable-area class="area">
			<movable-view class="area_view" direction="all" x="600rpx" y="1300rpx">
				<view class="add" @click="go('/pagesA/release/release')">
					<image class="icon" src="../../static/community/add.png" mode=""></image>
				</view>
			</movable-view>
		</movable-area>
		<!-- 评论弹出框 -->
		<u-popup round='40rpx' :show="comment_show" @close="close">
			<scroll-view class="comment_box" scroll-y="true" @scrolltolower='scrolltolower'>
				<comment v-for="(item,index) in comment" @user_reply='user_reply' :key="index" :msg='item'></comment>
				<view class="empty_top" v-if="comment.length==0">
					<u-empty text='快来分享你的评论吧~' icon="/static/public/kong.png">
					</u-empty>
				</view>
			</scroll-view>
			<!-- 回复框 -->
			<view class="bottom f_zj">
				<view class="ipt_box f_j">
					<image class="img" src="../../static/public/set.png" mode=""></image>
					<u--input :placeholder="placeholder" border="none" v-model="publish_commont.content"
						@confirm="confirm"></u--input>
				</view>
			</view>
			<u-toast ref="uToast" position='top'></u-toast>
		</u-popup>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { trends_type, trends } from "@/api/index.js"
	import { follow } from "@/api/user.js"
	import { dz, sc, get_comment, comment_api, delete_content } from '@/api/index_menu/index.js'
	import comment from '@/components/comment.vue'
	export default {
		components: {
			comment
		},
		computed: {
			...mapState(['user_info'])
		},
		data() {
			return {
				//胶囊
				menuButtonInfo: 0,
				header_check_action: 1,
				header_check: [{
					text: '关注',
					type: 1
				}, {
					text: '广场',
					type: 2
				}, {
					text: '我的',
					type: 3
				}],
				// 菜单
				menu_action: 0,
				activeStyle: {
					fontWeight: 400,
					color: '#232323',
					lineHeight: '47rpx',
					textAlign: 'left',
					fontStyle: 'normal',
					textTransform: 'none',
				},
				menu_list: [],
				// 图片数组
				img_index: [1, 4, 7, 10],
				img_list: [{}, {}, {}],
				// 评论框
				comment_show: false,
				// 评论列表
				comment: [],
				query_trends: {
					page: 1,
					type: 2,
					type_id: '',
					df_id: ''
				},
				// 动态列表
				trends_list: [],
				statu: 'loadmore',
				dz_sc: {
					option: '',
					type: 4,
					id: ''
				},
				query_comment: {
					id: '',
					page: 1,
					type: '1'
				},
				// 发表评论参数
				publish_commont: {
					id: '',
					p_id: '',
					u_id: '',
					content: '',
					type: 1
				},
				comment_statu: 'loadmore',
				placeholder: '说点什么吧'
			}
		},
		onShow() {
			this.get_trends_type()
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
		},
		onReachBottom() {
			if (this.statu != 'nomore') {
				this.get_trends()
			}
		},
		onShareAppMessage() {
			return {
				path: `/pages/index/index?pid=${this.user_info.id}`
			}
		},
		onShareTimeline() {
			return {
				path: `/pages/index/index?pid=${this.user_info.id}`
			}
		},
		methods: {
			sc_dt(id) {
				uni.showModal({
					title: '提示',
					content: '确认删除该条动态吗？',
					success: (res) => {
						if (res.confirm) {
							delete_content({ id, type: 4 }).then(res => {
								if (res.code == 1) {
									this.$refs.uToast.show({
										type: 'success',
										message: '删除成功',
									})
									this.trends_list = []
									this.query_trends.page = 1
									this.get_trends()
								} else {
									this.$refs.uToast.show({
										message: res.msg,
									})
								}
							})
						}
					}
				})
			},
			go(url) {
				uni.navigateTo({
					url
				})
			},
			// 作者主页
			go_author(item) {
				if (item.user_id == this.user_info.id) {
					uni.navigateTo({
						url: '/pagesA/user_homepage/user_homepage'
					})
				} else {
					uni.navigateTo({
						url: '/pagesA/index_menu/author?id=' + item.user_id
					})
				}
			},
			// 图片预览
			view_img(urls, index) {
				console.log(index)
				uni.previewImage({
					current: index,
					urls
				})
			},
			header_checkbtn(index, type) {
				this.header_check_action = index
				this.query_trends.type = type
				this.query_trends.page = 1
				this.trends_list = []
				if (type == 3) {
					this.query_trends.df_id = this.user_info.id
				} else {
					this.query_trends.df_id = ''
				}
				this.get_trends()
			},
			menu_check(index) {
				this.menu_action = index
			},
			// 弹出框
			close() {
				this.comment_show = false
				// this.query_comment.page = 1
			},
			open(id) {
				if (!this.user_info) {
					this.user_no_login()
					return
				}
				this.comment = []
				this.comment_show = true
				this.query_comment.id = id
				this.publish_commont.id = id
				this.query_comment.page = 1
				this.get_comment_list()
			},
			tabs_click(e) {
				console.log(e)
				this.query_trends.type_id = e.id
				this.query_trends.page = 1
				this.trends_list = []
				this.get_trends()
			},
			// 获取动态分类
			get_trends_type() {
				trends_type().then(res => {
					if (res.code == 1) {
						this.menu_list = res.data
						// 首次请求
						this.query_trends.type_id = res.data[0].id
						this.get_trends()
					}
				})
			},
			get_trends() {
				trends(this.query_trends).then(res => {
					console.log(res)
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.trends_list = [...this.trends_list, ...res.data.data]
							this.query_trends.page += 1
						} else {
							this.statu = 'nomore'
						}
					}
				})
			},
			// 点赞
			dz_btn(type, item) {
				this.dz_sc.option = type
				this.dz_sc.id = item.id
				dz(this.dz_sc).then(res => {
					console.log(res)
					if (res.code == 1) {
						if (type == 1) {
							this.$refs.uToast.show({
								type: 'success',
								message: '点赞成功',
							})
							item.is_dz = 1
							item.dz_nums += 1
						} else {
							item.dz_nums -= 1
							item.is_dz = 0
						}
						this.$forceUpdate()
					}
				})
			},
			// 关注
			gz_btn(type, item) {
				let obj = {
					df_id: item.user_id,
					type
				}
				follow(obj).then(res => {
					console.log(res)
					if (res.code == 1) {
						if (type == 1) {
							this.$refs.uToast.show({
								type: 'success',
								message: '关注成功'
							})
							item.is_gz = 1
						} else {
							this.$refs.uToast.show({
								type: 'default',
								message: '取消关注'
							})
							item.is_gz = 0
						}
						this.$forceUpdate()
					}
				})
			},
			// 获取评论列表
			get_comment_list() {
				get_comment(this.query_comment).then(res => {
					console.log(res)
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.comment = [...this.comment, ...res.data.data]
							this.query_comment.page += 1
						} else {
							this.comment_statu = 'nomore'
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
						this.query_comment.page = 1
						this.get_comment_list()
						// this.comment_show = false
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
				if (type == 1) {
					this.publish_commont.p_id = data.id
				} else {
					this.publish_commont.p_id = data.p_id
				}
				this.publish_commont.u_id = data.user_id
				this.placeholder = '回复' + data.username
				// this.comment_show = true
			},
			// 评论分页请求
			scrolltolower() {
				if (this.comment_statu != 'nomore') {
					this.get_comment_list()
				}
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		min-height: 100vh;
		padding-bottom: 30rpx;

		.f_top {
			width: 100%;
			background-color: #FFFFFF;
			z-index: 999999;
		}

		.header {
			.left {
				margin-left: 40rpx;

				.img {
					width: 30rpx;
					height: 30rpx;
				}
			}

			.right {
				// margin-left: 210rpx;

				.item {
					margin-right: 24rpx;

					.text {
						font-weight: 400;
						font-size: 32rpx;
						color: #000000;
						line-height: 37rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.check_action {
						font-size: 48rpx;
					}
				}
			}
		}

		// 菜单
		::v-deep .u-tabs__wrapper__nav__item {
			padding: 0 30rpx;
		}

		::v-deep .u-tabs__wrapper__nav__line {
			bottom: 15rpx;
			width: 52rpx;
			height: 12rpx;
		}

		// 列表
		.list {
			padding: 0rpx 30rpx;
			margin-top: 150rpx;

			.item {
				margin-bottom: 20rpx;
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				padding: 30rpx;
				font-weight: 400;
				font-size: 28rpx;
				color: #232323;
				line-height: 33rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;

				.user {
					.sc {
						width: 32rpx;
						height: 32rpx;
					}

					.left {
						.msg {
							margin-left: 16rpx;

							.name {
								font-weight: 400;
								font-size: 28rpx;
								color: #232323;
								line-height: 33rpx;
								text-align: left;
								font-style: normal;
								text-transform: none;
							}

							.date {
								font-weight: 400;
								font-size: 22rpx;
								color: #666666;
								line-height: 26rpx;
								text-align: left;
								font-style: normal;
								text-transform: none;
							}
						}
					}

					.right {
						width: 108rpx;
						padding: 8rpx 0rpx;
						border-radius: 34rpx;
						border: 1rpx solid rgb(215, 227, 247);

						.text {
							font-weight: 400;
							font-size: 24rpx;
							color: #000000;
							line-height: 28rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.icon {
							margin-right: 6rpx;
							width: 20rpx;
							height: 20rpx;
						}
					}
				}

				.title {
					margin-top: 20rpx;
				}

				.des {
					margin-top: 6rpx;
				}

				.img_box {
					.img_big {
						width: 280rpx;
						height: 328rpx;
						border-radius: 12rpx 12rpx 12rpx 12rpx;
						margin-right: 18rpx;
						margin-top: 20rpx;
					}

					.img {
						width: 198rpx;
						height: 204rpx;
						border-radius: 12rpx 12rpx 12rpx 12rpx;
						vertical-align: bottom;
						margin-top: 20rpx;
					}

					.imgmargin {
						margin: 0rpx 18rpx;
					}
				}

				.comment {
					margin-top: 30rpx;

					.left {
						width: 400rpx;
						height: 76rpx;
						background: #F6F6F6;
						border-radius: 62rpx 62rpx 62rpx 62rpx;
						padding-left: 10rpx;

						.text {
							font-weight: 400;
							font-size: 28rpx;
							color: #999999;
							line-height: 33rpx;
							text-align: center;
							font-style: normal;
							text-transform: none;
							margin-left: 20rpx;
						}
					}

					.right {
						.like {
							margin-right: 40rpx;
							position: relative;

							.icon {
								width: 36rpx;
								height: 36rpx;
							}

							.text {
								position: absolute;
								right: -20rpx;
								top: -10rpx;
								font-weight: 400;
								font-size: 20rpx;
								color: #232323;
								line-height: 23rpx;
								text-align: center;
								font-style: normal;
								text-transform: none;
							}
						}
					}
				}
			}
		}

		.area {
			width: 100vw;
			height: 100vh;
			position: fixed;
			top: 0;
			left: 0;
			pointer-events: none;
			z-index: 999999;

			.area_view {
				pointer-events: all;
				width: 108rpx;
				height: 108rpx;
			}
		}

		.add {

			// position: fixed;
			// right: 20rpx;
			// bottom: 100rpx;
			.icon {
				width: 108rpx;
				height: 108rpx;
			}
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
			margin-bottom: 30rpx;
			// padding-bottom: calc(20rpx + env(safe-area-inset-bottom) /2);

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
	}
</style>