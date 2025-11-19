<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image class="back" src="../../static/public/back_b.png" mode="" @click="back"></image>
			<view class="ipt_box f_j">
				<image class="icon" src="../../static/index/magnif.png" mode=""></image>
				<u--input placeholder="搜一搜游记" border="none" v-model="query_data.search" @confirm='confirm'></u--input>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list" v-if="list.length>0">
			<view class="item f_z_b" v-for="(item,index) in list" :key="index" @click="go(item.id)">
				<view class="left f_d f_z_b">
					<view class="user f_j">
						<u-avatar :src="item.avatar" size="30rpx"></u-avatar>
						<view class="name">
							{{item.username}}
						</view>
					</view>
					<view class="title text_ellipsis2">
						{{item.title}}
					</view>
					<view class="see_box f">
						<view class="view f_j">
							<image class="icon" src="../../static/public/view.png" mode=""></image>
							<text class="num">{{item.view_nums}}</text>
						</view>
						<view class="collection f_j">
							<image v-if="item.is_sc==0" @click.stop="sc_btn(1,item)" class="icon"
								src="../../static/public/collection_h.png" mode="">
							</image>
							<image v-else class="icon" @click.stop="sc_btn(2,item)" src="../../static/public/star_t.png"
								mode=""></image>
							<text v-if="item.is_sc==0" @click.stop="sc_btn(1,item)" class="num">{{item.sc_nums}}</text>
							<text v-else @click.stop="sc_btn(2,item)" class="num">{{item.sc_nums}}</text>
						</view>
					</view>
				</view>
				<view class="right">
					<image class="img" :src="item.image" mode="aspectFill"></image>
				</view>
			</view>
		</view>
		<!-- 加载更多 -->
		<view class="" v-if="list.length>0">
			<u-loadmore line @loadmore='get_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="/static/public/kong.png">
			</u-empty>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { lists, sc } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				// 胶囊
				menuButtonInfo: 0,
				query_data: {
					page: 1,
					type: 2,
					search: '',
				},
				list: [],
				dz_sc: {
					option: '',
					type: 2,
					id: ''
				},
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			};
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.get_list()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_list()
			}
		},
		methods: {
			go(id) {
				uni.navigateTo({
					url: '/pagesA/index_menu/record_detail?id=' + id
				})
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			confirm() {
				this.query_data.page = 1
				this.list = []
				this.get_list()
			},
			// 获取游记列表
			get_list() {
				this.status = 'loading'
				lists(this.query_data).then(res => {
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
			},
			// 收藏
			sc_btn(type, item) {
				// console.log(111111111111)
				console.log(item)
				this.dz_sc.option = type
				this.dz_sc.id = item.id
				sc(this.dz_sc).then(res => {
					if (res.code == 1) {
						if (type == 1) {
							this.$refs.uToast.show({
								type: 'success',
								message: '收藏成功'
							})
							item.is_sc = 1
							item.sc_nums += 1
						} else {
							item.sc_nums -= 1
							item.is_sc = 0
						}
						this.$forceUpdate()
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.header {
			padding-left: 30rpx;

			.back {
				width: 18rpx;
				height: 36rpx;
			}

			.ipt_box {
				padding: 0rpx 24rpx;
				margin-left: 46rpx;
				width: 400rpx;
				height: 62rpx;
				background: #FFFFFF;
				border-radius: 1800rpx 1800rpx 1800rpx 1800rpx;
				border: 2rpx solid #F5F6F8;

				.icon {
					margin-right: 14rpx;
					width: 30rpx;
					height: 30rpx;
				}
			}
		}

		.list {
			padding: 0rpx 24rpx;
			padding-top: 40rpx;
			padding-bottom: 30rpx;

			.item {
				background: #FFFFFF;
				border-radius: 10rpx 10rpx 10rpx 10rpx;
				overflow: hidden;
				margin-bottom: 28rpx;

				.left {
					flex: 1;
					padding: 14rpx 0rpx;
					padding-left: 24rpx;
					margin-right: 10rpx;

					.user {
						.name {
							margin-left: 10rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #999999;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}
					}

					.title {
						font-weight: 400;
						font-size: 28rpx;
						color: #282828;
						line-height: 34rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.see_box {
						.view {
							.icon {
								width: 30rpx;
								height: 20rpx;
							}

							.num {
								font-weight: 400;
								font-size: 24rpx;
								color: #696A6F;
								line-height: 34rpx;
								text-align: left;
								font-style: normal;
								text-transform: none;
								margin-left: 10rpx;
							}
						}

						.collection {
							margin-left: 34rpx;

							.icon {
								width: 30rpx;
								height: 28rpx;
							}

							.num {
								font-weight: 400;
								font-size: 24rpx;
								color: #696A6F;
								line-height: 34rpx;
								text-align: left;
								font-style: normal;
								text-transform: none;
								margin-left: 10rpx;
							}
						}
					}
				}

				.right {
					.img {
						width: 213rpx;
						height: 202rpx;
						vertical-align: bottom;
					}
				}
			}
		}
	}
</style>