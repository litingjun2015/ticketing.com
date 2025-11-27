<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_b.png" mode="" @click="back"></image>
			<view class="search f_z_b">
				<view class="search_left f_zj">
					<view class="city" @click.stop="go('/pagesA/set/address?type=query')">
						{{city}}
					</view>
					<image class="icon" @click.stop="go('/pagesA/set/address?type=query')"
						src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/vector.png" mode=""></image>
					<view class="line">

					</view>
					<view>
						<image class="magnif" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/magnif.png" mode=""></image>
					</view>
				</view>
				<view class="search_right f_zj">
					<u--input placeholder="请输入内容" border="none" v-model="query.search" @confirm='confirm'></u--input>
				</view>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list" v-if="list.length>0">
			<view class="item f" v-for="(item,index) in list" :key="index"
				@click="go('/pagesA/index_menu/tickets_detail?id='+item.id)">
				<view class="left">
					<image class="img" :src="item.image" mode="aspectFill"></image>
				</view>
				<view class="right f_d f_z_b">
					<view class="title text_ellipsis">
						{{item.title}}
					</view>
					<view class="price">
						<text>{{item.comment_count}}条评论</text>
						<text class="text_mar">门票</text>
						<text class="text_mar">￥{{item.lowest_price}}</text>
						<text class="text_mar">起</text>
					</view>
					<view class="distance f_z_b">
						<view class="left f">
							<!-- <text>地标建筑</text> -->
							<text class="text_ellipsis mark">{{item.address_xx}}</text>
						</view>
						<view class="right">
							{{item.distance | conversion_km}}km
						</view>
					</view>
					<view class="des text_ellipsis">
						{{item.tags}}
					</view>
				</view>
			</view>
			<!-- 加载更多 -->
			<u-loadmore line @loadmore='get_tickets_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<!-- 空 -->
		<view class="empty_top" v-else>
			<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { mapState } from "vuex"
	import { ticket_list } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				list: [],
				query: {
					page: 1,
					search: '',
					sort: 4
				},
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			};
		},
		computed: {
			...mapState(['city'])
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		watch: {
			city() {
				// console.log('我变化了')
				this.list = []
				this.query.page = 1
				this.get_tickets_list()
			},
		},
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.get_tickets_list()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_tickets_list()
			}
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
			// 获取门票列表
			get_tickets_list() {
				this.status = 'loading'
				ticket_list(this.query).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.list = [...this.list, ...res.data.data]
							this.status = 'loadmore'
							this.query.page += 1
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
			confirm() {
				this.query.page = 1
				this.list = []
				this.get_tickets_list()
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: #ffffff;
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
						margin-left: 20rpx;
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
			}
		}

		.list {
			padding: 0rpx 30rpx;
			margin-top: 40rpx;

			.item {
				margin-bottom: 28rpx;

				.left {
					.img {
						width: 190rpx;
						height: 190rpx;
						border-radius: 16rpx;
					}
				}

				.right {
					margin-left: 20rpx;

					.title {
						width: 450rpx;
						font-weight: 400;
						font-size: 32rpx;
						color: #3D3D3D;
						line-height: 36rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.price {
						font-weight: 400;
						font-size: 28rpx;
						color: #666666;
						line-height: 36rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;

						.text_mar {
							margin-left: 15rpx;
						}
					}

					.distance {
						font-weight: 400;
						font-size: 28rpx;
						color: #666666;
						line-height: 36rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;

						.left {
							.mark {
								// margin-left: 15rpx;
								width: 350rpx;
							}
						}

						.right {}
					}

					.des {
						width: 450rpx;
						font-weight: 400;
						font-size: 28rpx;
						color: #999999;
						line-height: 36rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}
				}
			}
		}
	}
</style>