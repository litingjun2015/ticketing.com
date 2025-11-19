<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image class="icon" src="../../static/public/back_b.png" mode="" @click="back"></image>
			<view class="search f_z_b">
				<view class="search_left f_zj">
					<view>
						<image class="magnif" src="../../static/index/magnif.png" mode=""></image>
					</view>
				</view>
				<view class="search_right f_zj">
					<u--input placeholder="请输入内容" border="none" v-model="value"
						@confirm="go(`/pagesA/index_menu/tickets?key=${value}`)"></u--input>
				</view>
				<view class="btn f_zj" @click="go(`/pagesA/index_menu/tickets?key=${value}`)">
					搜索
				</view>
			</view>
		</view>
		<!-- 历史搜索 -->
		<view class="history " v-if="history.length>0">
			<view class="title f_z_b">
				<text class="text">历史搜索</text>
				<image class="icon" @click="del_history" src="../../static/public/sc.png" mode=""></image>
			</view>
			<view class="item_box f">
				<view class="item" v-for="(item,index) in history" :key="index">
					{{item.title}}
				</view>
			</view>
		</view>
		<!-- 热门搜索 -->
		<view class="hot_search">
			<view class="title">
				热门搜索
			</view>
			<scroll-view scroll-x="true">
				<view class="hot_box f">
					<view class="hot_list">
						<view class="item f" v-for="(item,index) in ticket_hot" :key="index"
							@click="go(`/pagesA/index_menu/tickets_detail?id=${item.id}`)">
							<view class="left">
								<image class="img" :src="item.image" mode="aspectFill"></image>
								<view class="tag f_zj" :class="{tag1:index==0,tag2:index==1,tag3:index==2}"
									v-if="index<=2">
									{{index+1}}
								</view>
							</view>
							<view class="right f_d f_z_b">
								<view class="title text_ellipsis">
									{{item.title}}
								</view>
								<view class="des text_ellipsis">
									{{item.address_xx}}
								</view>
							</view>
						</view>
					</view>
					<view class="hot_list hot_list2">
						<view class="item f" v-for="(item,index) in dy_hot" :key="index"
							@click="go(`/pagesA/index_menu/guide_order_detail?id=${item.id}`)">
							<view class="left">
								<image class="img" :src="item.image" mode="aspectFill"></image>
								<view class="tag f_zj" :class="{tag1:index==0,tag2:index==1,tag3:index==2}"
									v-if="index<=2">
									{{index+1}}
								</view>
							</view>
							<view class="right f_d f_z_b">
								<view class="title text_ellipsis">
									{{item.title}}
								</view>
								<view class="des text_ellipsis">
									￥{{item.price}}
								</view>
							</view>
						</view>
					</view>
				</view>
			</scroll-view>
		</view>
	</view>
</template>

<script>
	import { history, delHistory } from '@/api/user.js'
	import { activity_list } from '@/api/index.js'
	import { ticket_list } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				history: [],
				ticket_hot: [],
				dy_hot: [],
				value: '',
			}
		},
		onShow() {
			this.get_history()
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.get_ticket_list()
			this.get_dy()
		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			get_history() {
				history().then(res => {
					console.log(res)
					if (res.code == 1) {
						this.history = res.data
					}
				})
			},
			get_ticket_list() {
				ticket_list({ flag: 'hot' }).then(res => {
					if (res.code == 1) {
						this.ticket_hot = res.data.data
					}
				})
			},
			// 获取热门活动
			get_dy() {
				activity_list().then(res => {
					// console.log(res)
					if (res.code == 1) {
						this.dy_hot = res.data.data
					}
				})
			},
			del_history() {
				uni.showModal({
					title: '提示',
					content: '确认删除历史记录吗？',
					success: (res) => {
						if (res.confirm) {
							delHistory().then(res => {
								if (res.code == 1) {
									console.log(222)
									this.history = []
								}
							})
						} else if (res.cancel) {

						}
					}
				});
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-bottom: 30rpx;

		.header {
			padding-left: 30rpx;

			.icon {
				width: 18rpx;
				height: 36rpx;
			}

			.search {
				margin-left: 40rpx;
				align-items: center;
				width: 400rpx;
				height: 68rpx;
				background: #F5F6F8;
				border-radius: 80rpx 80rpx 80rpx 80rpx;
				padding: 0rpx 26rpx;
				border: 2rpx solid #232323;

				.search_left {
					.icon {
						width: 18rpx;
						height: 10rpx;
						margin-left: 10rpx;
					}

					.line {
						border-right: 1rpx solid #666666;
						height: 34rpx;
						margin-left: 20rpx;
					}

					.magnif {
						width: 30rpx;
						height: 30rpx;
						vertical-align: baseline;
					}
				}

				.search_right {
					margin-left: 10rpx;
					flex: 1;
					height: 42rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #232323;
					line-height: 33rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.btn {
					width: 94rpx;
					height: 50rpx;
					background: #FFE706;
					border-radius: 1300rpx 1300rpx 1300rpx 1300rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #232323;
					line-height: 33rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}
		}

		.history {
			padding: 0rpx 30rpx;
			margin-top: 30rpx;

			.title {
				.text {
					font-weight: 400;
					font-size: 32rpx;
					color: #181818;
					line-height: 44rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.icon {
					width: 29rpx;
					height: 29rpx;
				}
			}

			.item_box {
				margin-top: 28rpx;
				flex-wrap: wrap;

				.item {
					background: #FFFFFF;
					border-radius: 29rpx 29rpx 29rpx 29rpx;
					margin-right: 20rpx;
					padding: 12rpx 38rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #181818;
					line-height: 34rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-bottom: 10rpx;
				}
			}
		}

		.hot_search {
			padding: 0rpx 30rpx;
			margin-top: 30rpx;

			.title {
				font-weight: 400;
				font-size: 32rpx;
				color: #181818;
				line-height: 44rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.hot_list2 {
				background: linear-gradient(180deg, #E4FFFC 0%, #FFFFFF 30%) !important;
			}

			.hot_list {
				margin-top: 28rpx;
				border-radius: 16rpx;
				min-height: 50vh;
				width: 70vw;
				background: linear-gradient(180deg, #FFF5E4 0%, #FFFFFF 30%);
				padding: 36rpx;
				margin-right: 20rpx;

				.item {
					margin-bottom: 30rpx;

					.left {
						position: relative;

						.img {
							width: 84rpx;
							height: 76rpx;
							border-radius: 8rpx;
						}

						.tag {
							position: absolute;
							top: 0;
							left: 0;
							width: 32rpx;
							height: 32rpx;
							border-radius: 8rpx 8rpx 8rpx 8rpx;
							font-weight: 400;
							font-size: 22rpx;
							color: #FFFFFF;
							line-height: 30rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.tag1 {
							background: linear-gradient(137deg, #FE5C13 0%, #FD8012 100%);
						}

						.tag2 {
							background: linear-gradient(137deg, #FAC661 0%, #FBE5A9 100%);
						}

						.tag3 {
							background: linear-gradient(137deg, #E3B68F 0%, #FBD5B0 100%);
						}

					}

					.right {
						width: 60vw;
						margin-left: 20rpx;

						.title {
							// border: 1rpx solid red;
							width: 350rpx;
							font-weight: 500;
							font-size: 24rpx;
							color: #181818;
							line-height: 39rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.des {
							width: 350rpx;
							font-weight: 500;
							font-size: 22rpx;
							color: #9F5F3C;
							line-height: 30rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}
					}
				}
			}
		}
	}
</style>