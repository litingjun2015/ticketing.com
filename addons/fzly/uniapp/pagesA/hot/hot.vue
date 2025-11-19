<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image class="back" src="../../static/public/back_b.png" mode="" @click="back"></image>
			<view class="ipt_box f_j">
				<image class="icon" src="../../static/index/magnif.png" mode=""></image>
				<u--input placeholder="搜一搜" border="none" v-model="query_data.search" @confirm='confirm'></u--input>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list f_z_b" v-if="list.length>0">
			<view class="item" v-for="(item,index) in list" :key="index"
				@click="go_detail('/pagesA/index_menu/guide_order_detail?id='+item.id)">
				<image class="img" :src="item.image" mode="aspectFill"></image>
				<view class="title text_ellipsis">
					{{item.title}}
				</view>
				<view class="price">
					<text>￥{{item.price}}</text>
					<text class="text">起</text>
				</view>
			</view>
		</view>
		<!-- 加载更多 -->
		<view class="" v-if="list.length>0">
			<u-loadmore line @loadmore='get_hot' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="/static/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { activity_list } from '@/api/index.js'
	import { mapState } from 'vuex'
	export default {
		computed: {
			...mapState(['menuButtonInfo'])
		},
		data() {
			return {
				query_data: {
					page: 1,
					search: ''
				},
				list: [],
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			};
		},
		onLoad() {
			this.get_hot()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_hot()
			}
		},
		methods: {
			go_detail(url) {
				uni.navigateTo({
					url
				})
			},
			confirm() {
				this.query_data.page = 1
				this.list = []
				this.get_hot()
			},
			// 获取热门活动
			get_hot() {
				this.status = 'loadong'
				activity_list(this.query_data).then(res => {
					// console.log(res)
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.list = [...this.list, ...res.data.data]
							this.query_data.page += 1
							this.status = 'loadmore'
						} else {
							this.status = 'nomore'
						}
					}
				})
			}
		},
	}
</script>

<style lang="scss">
	.content {
		padding-bottom: 30rpx;

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
				border: 2rpx solid #FFFFFF;

				.icon {
					margin-right: 14rpx;
					width: 30rpx;
					height: 30rpx;
				}
			}
		}

		.list {
			padding: 0rpx 30rpx;
			margin-top: 40rpx;
			padding-bottom: 40rpx;
			flex-wrap: wrap;

			.item {
				width: 336rpx;
				background: #FFFFFF;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
				overflow: hidden;
				padding-bottom: 10rpx;
				margin-bottom: 20rpx;

				.img {
					width: 100%;
					height: 172rpx;
					vertical-align: bottom;
				}

				.title {
					padding: 0rpx 20rpx;
					margin-top: 6rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
				}

				.price {
					padding: 0rpx 20rpx;
					margin-top: 6rpx;
					font-weight: 400;
					font-size: 32rpx;
					color: #000000;

					.text {
						font-weight: 400;
						font-size: 24rpx;
						color: #999999;
						margin-left: 10rpx;
					}
				}
			}
		}
	}
</style>