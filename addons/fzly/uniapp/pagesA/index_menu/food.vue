<template>
	<view class="content font_family">
		<view class="header f_j" :style="{paddingTop:menuButtonInfo+'px'}">
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
		<view class="list" v-if="waterfall_list.length>0">
			<view @click="go('/pagesA/index_menu/must_detail?id='+item.id)" class="item"
				v-for="(item,index) in waterfall_list" :key="index">
				<image class="img" :src="item.image" mode="widthFix"></image>
				<view class="title f_j">
					<view class="text text_ellipsis">
						{{item.title}}
					</view>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/view.png" mode=""></image>
					<text class="num">{{item.view_nums}}</text>
				</view>
			</view>
		</view>
		<!-- 加载更多 -->
		<view class="" v-if="waterfall_list.length>0">
			<u-loadmore line @loadmore='get_lists' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-if="waterfall_list.length==0">
			<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { lists } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				waterfall_list: [],
				// 请求参数
				query: {
					page: 1,
					type: 3,
					search: ''
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
		onLoad() {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.get_lists()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_lists()
			}
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		watch: {
			city() {
				this.query.page = 1
				this.waterfall_list = []
				this.get_lists()
			},
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
			// 获取列表
			get_lists() {
				lists(this.query).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.waterfall_list = [...this.waterfall_list, ...res.data.data]
							this.query.page += 1
							this.status = 'loadmore'
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
			confirm() {
				this.query.page = 1
				this.waterfall_list = []
				this.get_lists()
			}
		},
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: white;
		padding-bottom: 30rpx;

		.header {
			padding-left: 30rpx;
			background-color: white;

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
			margin-top: 30rpx;
			column-count: 2;
			padding: 0rpx 30rpx;

			.item {
				position: relative;
				break-inside: avoid;
				margin-bottom: 20rpx;

				.tag {
					position: absolute;
					right: 0;
					top: 0;
					width: 124rpx;
					height: 44rpx;
					background: #F87415;
					border-radius: 0rpx 16rpx 0rpx 16rpx;
					font-weight: 400;
					font-size: 20rpx;
					color: #FFFFFF;
					line-height: 23rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}

				.img {
					width: 336rpx;
					vertical-align: bottom;
					border-radius: 16rpx;
				}

				.title {
					margin-top: 20rpx;

					.text {
						// border: 1rpx solid red;
						width: 300rpx;
						font-weight: 400;
						font-size: 28rpx;
						color: #000000;
						line-height: 33rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.icon {
						margin-left: auto;
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
						margin-left: 8rpx;
					}
				}
			}
		}
	}
</style>