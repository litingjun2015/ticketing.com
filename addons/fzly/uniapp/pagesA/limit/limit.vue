<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image class="back" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_b.png" mode="" @click="back"></image>
			<view class="ipt_box f_j">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/magnif.png" mode=""></image>
				<u--input placeholder="搜一搜" border="none" @confirm='confirm' v-model="query_data.search"></u--input>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list" v-if="list.length>0">
			<view class="item f" v-for="(item,index) in list" :key="index"
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
								v-for="(tj_item,tj_index) in item.tj_user" :key='index'>
								<image class="avatar" :src="tj_item.avatar" mode=""></image>
							</view>
						</view>
						<view class="see_right">
							<text>{{item.view_nums}}</text>
							<text>人已看</text>
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 加载更多 -->
		<view class="" v-if="list.length>0">
			<u-loadmore line @loadmore='get_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { lists } from '@/api/index_menu/index.js'
	export default {
		computed: {
			...mapState(['menuButtonInfo'])
		},
		data() {
			return {
				list: [],
				query_data: {
					page: 1,
					type: 2,
					search: ''
				},
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			};
		},
		onReachBottom() {
			if (this.status == 'loadmore') {
				this.get_list()
			}
		},
		onLoad() {
			this.get_list()
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
				this.get_list()
			},
			// 获取列表
			get_list() {
				this.status = 'loading'
				lists(this.query_data).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.status = 'loadmore'
							this.query_data.page += 1
							this.list = [...this.list, ...res.data.data]
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
	}
</style>