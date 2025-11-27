<template>
	<view class="content font_family">
		<view class="header" :style="{paddingTop:menuButtonInfo+'px',backgroundImage:`url(${user_info.back_avatar})`}">
			<view class="back">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/homepage_back.png" mode="" @click="back"></image>
			</view>
		</view>
		<view class="user_msg_box">
			<view class="user f">
				<view class="left">
					<u-avatar :src="user_info.avatar" size="140rpx" mode="aspectFill"></u-avatar>
				</view>
				<view class="right f_d f_z_b">
					<view class="name">
						{{user_info.username}}
					</view>
					<view class="tag f">
						<view class="item">
							<image v-if="user_info.gender==1" class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/man.png" mode="">
							</image>
							<image v-if="user_info.gender==0" class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/woman.png" mode="">
							</image>
						</view>
						<view class="item">
							常住地：{{user_info.c_city?user_info.c_city:'未知'}}
						</view>
					</view>
					<view class="autograph">
						欢迎来到我的主页~
					</view>
				</view>
			</view>
			<view class="data f_z_b">
				<view class="item f_d f_j">
					<view class="num">
						{{user_info.like_s}}
					</view>
					<view class="text">
						获赞
					</view>
				</view>
				<view class="item f_d f_j">
					<view class="num">
						{{user_info.gz_s}}
					</view>
					<view class="text">
						关注
					</view>
				</view>
				<view class="item f_d f_j">
					<view class="num">
						{{user_info.fs_s}}
					</view>
					<view class="text">
						粉丝
					</view>
				</view>
			</view>
			<!-- tabs 选项卡-->
			<view class="tabs_box">
				<view class="tabs f_z_b">
					<view class="item f_d f_j" v-for="(item,index) in tabs_list" :key="index"
						@click="tabs_check(index)">
						<view class="title">
							{{item.name}}
						</view>
						<view class="line" v-if="tabs_action==index">

						</view>
					</view>
				</view>
				<!-- 列表 -->
				<view class="list" v-if="list.length>0">
					<view class="item" v-for="(item,index) in list" :key="index" @click="go_detail(item)">
						<view class="title f_z_b f_j">
							<view class="text text_ellipsis">
								{{item.title}}
							</view>
							<view class="" @click.stop="open_more(item)">
								<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set_more.png" mode="">
								</image>
							</view>
						</view>
						<image class="img" :src="item.image" mode="aspectFill"></image>
						<view class="data_box f_z_b">
							<view class="data_item f_zj">
								<image class="data_icon1" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/view.png" mode=""></image>
								<text class="text">浏览</text>
								<text class="text">{{item.view_nums}}</text>
							</view>
							<view class="data_item f_zj" @click.stop="sc_btn(item)">
								<image v-if="item.is_sc==1" class="data_icon2" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/star_t.png"
									mode=""></image>
								<image v-else class="data_icon2" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/collection_h.png" mode="">
								</image>
								<text class="text">收藏</text>
								<text class="text">{{item.sc_nums}}</text>
							</view>
							<view class="data_item f_zj" @click.stop="dz_btn(item)">
								<image v-if="item.is_dz==1" class="data_icon3" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/like_t.png"
									mode=""></image>
								<image v-else class="data_icon3" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/like.png" mode=""></image>
								<text class="text">点赞</text>
								<text class="text">{{item.dz_nums}}</text>
							</view>
						</view>
					</view>
					<!-- 加载更多 -->
					<u-loadmore line @loadmore='get_list' :status="status" :loading-text="loadingText"
						:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
				</view>
				<view class="empty_top" v-if="list.length==0">
					<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
					</u-empty>
				</view>
			</view>
		</view>
		<!-- 评论弹出框 -->
		<u-popup round='40rpx' :show="comment_show" @close="close" @open="open">
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
		<!-- 更多弹窗 -->
		<u-popup round='40rpx' :show="more_show" @close="close_more">
			<view class="more_box f">
				<button class="item f_d f_j" open-type="share">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/vx.png" mode=""></image>
					<view class="text">
						微信
					</view>
				</button>
				<view class="item f_d f_j" v-if="tabs_action>1" @click="delete_btn">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/red_sc.png" mode=""></image>
					<view class="text">
						删除
					</view>
				</view>
				<view class="item f_d f_j" v-if="tabs_action>1 && more_item.status==2" @click="go()">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/bj.png" mode=""></image>
					<view class="text">
						编辑
					</view>
				</view>
			</view>
		</u-popup>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import comment from '@/components/comment.vue'
	import { mapState } from 'vuex'
	import { my_like } from '@/api/user.js'
	import { lists, delete_content, sc, dz } from "@/api/index_menu/index.js"
	export default {
		components: {
			comment
		},
		computed: {
			...mapState(['menuButtonInfo', 'user_info'])
		},
		data() {
			return {
				tabs_list: [
					{ name: '收藏' },
					{ name: '喜欢' },
					{ name: '游记', type: 2 },
					{ name: '攻略', type: 1 },
				],
				tabs_action: 0,
				// 评论框
				comment_show: false,
				more_show: false,
				// 评论列表
				comment: {},

				// 请求参数
				query_data: {
					type: 1,
					page: 1,
					df_id: ''
				},
				list: [],
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~',
				more_item: '',
			};
		},
		onShow() {
			this.list = []
			this.query_data.page = 1
			this.get_list()
		},
		onLoad() {

		},
		onShareAppMessage() {
			let path = ''
			if (this.tabs_action < 2) {
				if (this.more_item.type == 1) { //攻略
					path = `/pagesA/index_menu/str_detail?id=${this.more_item.con_id}`
				} else if (more_item.type == 2) { //游记
					path = `/pagesA/index_menu/record_detail?id=${this.more_item.con_id}`
				} else { //美食
					path = `/pagesA/index_menu/must_detail?id=${this.more_item.con_id}`
				}
			} else if (this.tabs_action == 2) {
				path = `/pagesA/index_menu/record_detail?id=${this.more_item.id}`
			} else {
				path = `/pagesA/index_menu/str_detail?id=${this.more_item.id}`
			}
			return {
				title: this.more_item.title,
				path,
				imageUrl: this.more_item.image
			}
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_list()
			}
		},
		methods: {
			tabs_check(index) {
				this.tabs_action = index
				this.query_data.type = this.tabs_list[index].type
				this.query_data.page = 1
				this.list = []
				this.get_list()
			},
			// 弹出框
			close() {
				this.comment_show = false
			},
			open() {
				this.comment_show = true
			},
			close_more() {
				this.more_show = false
			},
			open_more(item) {
				this.more_show = true
				this.more_item = item
			},
			go() {
				uni.navigateTo({
					url: `/pagesA/release/release?id=${this.more_item.id}&type=${this.more_item.type}`
				})
				this.more_show = false
			},
			// 获取游记攻略列表
			get_list() {
				this.status = 'loading'
				if (this.tabs_action > 1) {
					// 游记攻略
					this.query_data.df_id = this.user_info.id
					lists(this.query_data).then(res => {
						console.log(res)
						if (res.code == 1) {
							if (res.data.data.length > 0) {
								this.list = [...this.list, ...res.data.data]
								this.status = 'loadmore'
								this.query_data.page += 1
							} else {
								this.status = 'nomore'
							}
						}
					})
				} else {
					if (this.tabs_action == 0) {
						this.query_data.type = 1
					} else {
						this.query_data.type = 2
					}
					// 喜欢收藏
					my_like(this.query_data).then(res => {
						console.log(res)
						if (res.code == 1) {
							if (res.data.data.length > 0) {
								this.list = [...this.list, ...res.data.data]
								this.status = 'loadmore'
								this.query_data.page += 1
							} else {
								this.status = 'nomore'
							}
						}
					})
				}
			},
			// 前往详情页
			go_detail(item) {
				if (this.tabs_action < 2) {
					if (item.type == 1) { //攻略
						uni.navigateTo({
							url: `/pagesA/index_menu/str_detail?id=${item.con_id}`
						})
					} else if (item.type == 2) { //游记
						uni.navigateTo({
							url: `/pagesA/index_menu/record_detail?id=${item.con_id}`
						})
					} else { //美食
						uni.navigateTo({
							url: `/pagesA/index_menu/must_detail?id=${item.con_id}`
						})
					}
				} else if (this.tabs_action == 2) {
					uni.navigateTo({
						url: `/pagesA/index_menu/record_detail?id=${item.id}`
					})
				} else {
					uni.navigateTo({
						url: `/pagesA/index_menu/str_detail?id=${item.id}`
					})
				}
			},
			// 删除攻略，游记
			delete_btn() {
				uni.showModal({
					title: '提示',
					content: '是否删除该内容？',
					success: (res) => {
						if (res.confirm) {
							let obj = {
								type: this.tabs_list[this.tabs_action].type,
								id: this.more_item.id
							}
							delete_content(obj).then(res => {
								console.log(res)
								if (res.code == 1) {
									this.$refs.uToast.show({
										type: 'success',
										message: '删除成功'
									})
									this.more_show = false
									this.query_data.page = 1
									this.list = []
									this.get_list()
								}
							})
						} else if (res.cancel) {}
					}
				});
			},
			// 收藏，点赞
			sc_btn(item) {
				let obj = {
					option: '',
					type: item.type,
					id: ''
				}
				if (this.tabs_action < 2) {
					obj.id = item.con_id
				} else {
					obj.id = item.id
				}
				if (item.is_sc == 0) {
					obj.option = 1
				} else {
					obj.option = 2
				}
				sc(obj).then(res => {
					if (res.code == 1) {
						this.list = []
						this.query_data.page = 1
						this.get_list()
					}
				})
			},
			dz_btn(item) {
				let obj = {
					option: '',
					type: item.type,
					id: ''
				}
				if (this.tabs_action < 2) {
					obj.id = item.con_id
				} else {
					obj.id = item.id
				}
				if (item.is_dz == 0) {
					obj.option = 1
				} else {
					obj.option = 2
				}
				dz(obj).then(res => {
					if (res.code == 1) {
						this.list = []
						this.query_data.page = 1
						this.get_list()
					}
				})
			},
		},
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: #FFFFFF;
		padding-bottom: 30rpx;

		.header {
			width: 100%;
			height: 450rpx;
			padding-left: 30rpx;
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;
			box-sizing: border-box;

			.back {
				.icon {
					width: 48rpx;
					height: 48rpx;
				}
			}
		}

		.user_msg_box {
			// height: 300rpx;
			background: #F8F8F8;
			border-radius: 48rpx 48rpx 0rpx 0rpx;
			margin-top: -100rpx;

			.user {
				padding: 30rpx;

				.left {
					border-radius: 50%;
					border: 2rpx solid #FFFFFF;
				}

				.right {
					margin-left: 26rpx;

					.name {
						font-weight: 400;
						font-size: 32rpx;
						color: #000000;
					}

					.tag {
						.item {
							margin-right: 10rpx;
							padding: 6rpx 10rpx;
							background: #FFFFFF;
							border-radius: 34rpx 34rpx 34rpx 34rpx;
							font-weight: 400;
							font-size: 20rpx;
							color: #161616;

							.icon {
								width: 19rpx;
								height: 19rpx;
							}
						}
					}

					.autograph {
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
					}
				}
			}

			.data {
				padding: 0rpx 50rpx;

				.item {
					font-weight: 600;
					font-size: 32rpx;
					color: #000000;
				}
			}

			.tabs_box {
				margin-top: 30rpx;
				padding: 30rpx;
				background: #FFFFFF;
				border-radius: 20rpx 20rpx 0rpx 0rpx;
				// min-height: 70vh;

				.tabs {
					.item {
						.title {
							font-weight: 400;
							font-size: 36rpx;
							color: #000000;
							z-index: 999;
						}

						.line {
							width: 52rpx;
							height: 12rpx;
							background: #FFE706;
							border-radius: 10rpx 10rpx 10rpx 10rpx;
							margin-top: -15rpx;
						}
					}
				}

				.list {
					margin-top: 30rpx;

					.item {
						margin-bottom: 30rpx;

						.title {
							.text {
								font-weight: 400;
								font-size: 34rpx;
								color: #000000;
								width: 600rpx;
							}

							.icon {
								width: 46rpx;
								height: 10rpx;
							}
						}

						.img {
							margin-top: 20rpx;
							width: 100%;
							height: 220rpx;
							border-radius: 8rpx 8rpx 8rpx 8rpx;
						}

						.data_box {
							margin-top: 20rpx;

							.data_item {
								.data_icon1 {
									width: 30rpx;
									height: 20rpx;
								}

								.data_icon2 {
									width: 30rpx;
									height: 28rpx;
								}

								.data_icon3 {
									width: 30rpx;
									height: 28rpx;
								}

								.text {
									margin-left: 10rpx;
									font-weight: 400;
									font-size: 24rpx;
									color: #696A6F;
								}
							}
						}
					}
				}
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

		.more_box {
			height: 150rpx;
			padding: 0rpx 40rpx;
			padding-top: 60rpx;

			button {
				margin: 0;
				line-height: 40rpx;
				background: none;
			}

			button::after {
				border: none;
			}

			.item {
				padding: 0rpx 14rpx;
				margin-right: 40rpx;

				.icon {
					width: 58rpx;
					height: 58rpx;
				}

				.text {
					font-weight: 400;
					font-size: 28rpx;
					color: #666666;
					margin-top: 10rpx;
					line-height: 40rpx;
				}
			}
		}
	}
</style>