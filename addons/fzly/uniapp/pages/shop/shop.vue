<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="search f_j h5_search" @click="go_detail('/pagesA/index_menu/search')">
			<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/magnif.png" mode=""></image>
			<view class="pl">
				搜索喜欢的内容~
			</view>
		</view>
		<!-- 轮播图 -->
		<view class="swiper">
			<u-swiper indicator indicatorMode='dot' keyName="image" height='306rpx' radius='24rpx' :list="list"
				@click="adClick"></u-swiper>
		</view>
		<!-- 更多标题 -->
		<more :title="'热门活动'" :tomore='true' url='/pagesA/hot/hot'></more>
		<scroll-view class="scroll_box" scroll-x="true">
			<view class="item" v-for="(item,index) in hot_list" :key="index"
				@click="go_detail('/pagesA/index_menu/guide_order_detail?id='+item.id)">
				<view class="img_box">
					<image class="img" :src="item.image" mode="aspectFill">
					</image>
					<!-- 推荐tag -->
					<view class="tag f_zj">
						推荐
					</view>
				</view>
				<view class="title">
					{{item.title}}
				</view>
				<view class="price">
					<text class="text1">￥</text>
					<text class="text2">{{item.price}}</text>
					<text class="text3">起</text>
				</view>
			</view>
			<view class="empty_top" v-if="hot_list.length==0">
				<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
				</u-empty>
			</view>
		</scroll-view>
		<!-- 限定 -->
		<more :title="'初夏限定篇'" :tomore='true' url='/pagesA/limit/limit'></more>
		<view class="prescribe">
			<view v-if="prescribe_list.length>0" class="item f" v-for="(item,index) in prescribe_list" :key="index"
				@click="go_detail('/pagesA/index_menu/record_detail?id='+item.id)">
				<view class="item_left">
					<image class="img" :src="item.image" mode="aspectFill">
					</image>
				</view>
				<view class="item_right f_d f_z_b">
					<view class="title">
						{{item.title}}
					</view>
					<view class="des">
						{{item.c_content}}
					</view>
					<view class="see f_j">
						<view class="see_left f">
							<view class="avatar_box" :style="{marginLeft:tj_index>=1?'-15rpx':0}"
								v-for="(tj_item,tj_index) in item.tj_user" :key='tj_index'>
								<image class="avatar" :src="tj_item.avatar" mode="aspectFill"></image>
							</view>
						</view>
						<view class="see_right">
							<text>{{item.view_nums}}</text>
							<text>人已看</text>
						</view>
					</view>
				</view>
			</view>
			<view class="empty_top" v-if='prescribe_list.length==0'>
				<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
				</u-empty>
			</view>
		</view>
		<!-- 都在看 -->
		<more :title="'大家都在看'" :tomore='true' url='/pagesA/index_menu/food'></more>
		<view class="all" v-if="all_list.length>0">
			<waterfall v-for="(item,index) in all_list" :img='item'></waterfall>
		</view>
		<view class="empty_top" v-if='all_list.length==0'>
			<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import more from '@/components/more.vue'
	import waterfall from '@/components/waterfall_card.vue'
	import { ad_list, activity_list, ad_click } from '@/api/index.js'
	import { lists } from '@/api/index_menu/index.js'
	import { mapState } from 'vuex'
	export default {
		components: {
			more,
			waterfall
		},
		computed: {
			...mapState(['user_info'])
		},
		data() {
			return {
				menuButtonInfo: 0,
				// 轮播图
				list: [],
				all_list: [],
				hot_list: [],
				prescribe_list: [],
			}
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.get_swiper()
			this.get_hot()
			this.get_prescribe()
			this.get_all()
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
			adClick(e) {
				ad_click({ id: this.list[e].id }).then(res => {
					if (res.code == 1) {
						uni.navigateTo({
							url: this.list[e].tz_url,
							success: () => {

							},
							fail: () => {
								uni.switchTab({
									url: this.list[e].tz_url,
								})
							}
						})
					}
				})
			},
			go_detail(url) {
				uni.navigateTo({
					url
				})
			},
			// 获取轮播图
			get_swiper() {
				let obj = {
					url: '/pages/shop/shop',
					position: 1
				}
				ad_list(obj).then(res => {
					this.list = res.data
				})
			},
			// 获取热门活动
			get_hot() {
				activity_list().then(res => {
					// console.log(res)
					if (res.code == 1) {
						this.hot_list = res.data.data
					}
				})
			},
			// 获取初夏限定列表
			get_prescribe() {
				lists({ type: 2, limit: 5 }).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.prescribe_list = res.data.data
					}
				})
			},
			// 获取大家都在看
			get_all() {
				lists({ type: 3, limit: 8 }).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.all_list = res.data.data
					}
				})
			},
		},
	}
</script>

<style lang="scss" scoped>
	.content {
		background: #F8F9FC;
		min-height: 100vh;
		padding: 0rpx 30rpx 30rpx 30rpx;

		// padding-top: 100rpx;
		/* #ifdef H5 */
		.h5_search {
			width: 670rpx !important;
			box-sizing: border-box;

		}

		/* #endif */
		.search {
			width: 450rpx;
			// height: 68rpx;
			padding: 18rpx 24rpx;
			background: #FFFFFF;
			border-radius: 32rpx 32rpx 32rpx 32rpx;

			.icon {
				width: 30rpx;
				height: 30rpx;
			}

			.pl {
				margin-left: 20rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #BEBFC1;
			}
		}

		.swiper {
			margin-top: 32rpx;
		}

		.scroll_box {
			margin-top: 30rpx;
			width: 100%;
			height: 100%;
			white-space: nowrap;

			.item {
				margin-right: 20rpx;
				width: 200rpx;
				background: #FFFFFF;
				border-radius: 12rpx 12rpx 12rpx 12rpx;
				overflow: hidden;
				display: inline-block;

				.img_box {
					position: relative;

					.img {
						vertical-align: bottom;
						width: 100%;
						height: 200rpx;
					}

					.tag {
						position: absolute;
						right: 0;
						top: 0;
						width: 82rpx;
						height: 33rpx;
						background: #F87415;
						border-radius: 0rpx 12rpx 0rpx 12rpx;
						font-weight: 400;
						font-size: 20rpx;
						color: #FFFFFF;
						line-height: 23rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}
				}

				.title {
					padding: 2rpx 10rpx;
					width: 100%;
					overflow: hidden;
					white-space: nowrap;
					text-overflow: ellipsis;
				}

				.price {
					margin-top: 6rpx;
					padding: 2rpx 10rpx;
					margin-bottom: 20rpx;
					font-family: PingFang SC, PingFang SC;
					font-weight: 400;
					font-size: 24rpx;
					color: #000000;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;

					.text1 {}

					.text2 {
						font-size: 32rpx
					}

					.text3 {
						color: #999999;
						margin-left: 8rpx;
					}
				}
			}
		}

		.prescribe {
			margin-top: 30rpx;

			.item {
				padding: 20rpx;
				background: #FFFFFF;
				border-radius: 22rpx 22rpx 22rpx 22rpx;
				margin-bottom: 30rpx;

				.item_left {
					.img {
						border-radius: 12rpx 12rpx 12rpx 12rpx;
						width: 188rpx;
						height: 174rpx;
						vertical-align: bottom;
					}
				}

				.item_right {
					margin-left: 20rpx;

					.title {
						width: 420rpx;
						overflow: hidden;
						white-space: nowrap;
						text-overflow: ellipsis;
						font-weight: 400;
						font-size: 28rpx;
						color: #000000;
						line-height: 33rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.des {
						width: 420rpx;
						background: #EFEFEF;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						overflow: hidden;
						white-space: nowrap;
						text-overflow: ellipsis;
						padding: 10rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #1E1E1E;
						line-height: 28rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.see {
						.see_left {
							.avatar_box {
								width: 32rpx;
								height: 32rpx;
								border: 2rpx solid #ffffff;
								border-radius: 50%;
								overflow: hidden;

								.avatar {
									width: 32rpx;
									height: 32rpx;
								}
							}
						}

						.see_right {
							font-family: PingFang SC, PingFang SC;
							font-weight: 400;
							font-size: 20rpx;
							color: #666666;
							line-height: 23rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
							margin-left: 10rpx;
						}
					}
				}
			}
		}

		// 都在看
		.all {
			margin-top: 30rpx;
			column-count: 2;
			// column-gap: 20rpx;
		}
	}
</style>