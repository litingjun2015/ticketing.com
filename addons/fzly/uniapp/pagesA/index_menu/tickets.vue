<template>
	<view class="content font_family">
		<!-- 筛选组件 -->
		<screen type='1' :keyname='keyname' :ticket_type='ticket_type_list' @updata='updata' @query='query_data'>
		</screen>
		<!-- 列表 -->
		<view class="list" v-if="data_list.length>0">
			<view class="item f" v-for="(item,index) in data_list" :key="index"
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
			<u-loadmore line @loadmore='get_ticket_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-else>
			<u-empty text='没有数据啦~' icon="/static/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { ticket_type, ticket_list } from '@/api/index_menu/index.js'
	import screen from '@/components/t_screen.vue'
	export default {
		computed: {
			...mapState(['lon', 'lat'])
		},
		components: { screen },
		data() {
			return {
				menuButtonInfo: 0,
				ticket_type_list: [],
				// 请求参数
				query: {
					sort: 1,
					page: 1,
					search: '',
					type_id: []
				},
				keyname: '',
				// 列表
				data_list: [],
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			};
		},
		onLoad(e) {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			if (e.key) {
				this.keyname = e.key
				this.query.search = e.key
				// this.$forceUpdate()
			}
			this.get_ticket_type()
			this.get_ticket_list()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_ticket_list()
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
			// 获取门票类型
			get_ticket_type() {
				ticket_type().then(res => {
					console.log(res)
					if (res.code == 1) {
						let arr = []
						res.data.forEach(item => {
							item.action = false
							arr.push(item)
						})
						this.ticket_type_list = arr
					}
				})
			},
			updata(index) {
				console.log(index)
				this.ticket_type_list[index].action = !this.ticket_type_list[index].action
				this.$forceUpdate()
			},
			// 请求门票列表
			query_data(obj) {
				// 每次筛选清空page
				this.query.page = 1
				this.data_list = []
				this.query = { ...this.query, ...obj }
				// console.log(this.query)
				this.get_ticket_list()
			},
			// 获取门票列表
			get_ticket_list() {
				this.status = 'loading'
				ticket_list(this.query).then(res => {
					// console.log(res)
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.data_list = [...this.data_list, ...res.data.data]
							this.query.page += 1
							this.status = 'loadmore'
						} else {
							this.status = 'nomore'
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-top: 270rpx;
	}

	.header {
		width: 100%;
		padding-left: 30rpx;
		background-color: white;
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999999;

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

	.list_box {
		padding: 0rpx 30rpx;
		height: 85vh;
		border: 1rpx solid red;
		box-sizing: border-box;
	}

	.list {
		padding: 0rpx 30rpx;
		padding-bottom: 40rpx;
		// background-color: red;

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
</style>