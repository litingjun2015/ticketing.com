<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image class="back" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_b.png" mode="" @click="back"></image>
			<view class="ipt_box f_j">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/magnif.png" mode=""></image>
				<u--input placeholder="想去哪里,搜一搜攻略" border="none" v-model="query.search" @confirm='confirm'></u--input>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list" v-if="list.length>0">
			<view class="item" v-for="(item,index) in list" :key="index" @click="go(item.id)">
				<view class="img_box">
					<image class="img" :src="item.image" mode="aspectFill"></image>
					<view class="time">
						{{item.createtime}}
					</view>
					<view class="title text_ellipsis">
						{{item.title}}
					</view>
					<view class="position f_j">
						<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/pt.png" mode=""></image>
						<text>{{item.city}}</text>
					</view>
				</view>
				<!-- 已看用户 -->
				<view class="see f_j">
					<view class="see_left f">
						<view class="avatar_box" :style="{marginLeft:user_index>=1?'-15rpx':0}"
							v-for="(user,user_index) in item.tj_user" :key='user_index'>
							<image class="avatar" :src="user.avatar" mode="aspectFill"></image>
						</view>
					</view>
					<view class="see_right">
						<text>{{item.view_nums}}</text>
						<text>人已看</text>
					</view>
				</view>
				<!-- 描述 -->
				<!-- <view class="des text_ellipsis">
					{{item.content}}
				</view> -->
			</view>
		</view>
		<!-- 加载更多 -->
		<view class="" v-if="list.length>0">
			<u-loadmore line @loadmore='get_lists' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { lists } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				// 胶囊
				menuButtonInfo: 0,
				list: [],
				// 请求参数
				query: {
					page: 1,
					type: 1,
					search: ''
				},
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			}
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
			this.get_lists()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_lists()
			}
		},
		methods: {
			go(id) {
				uni.navigateTo({
					url: '/pagesA/index_menu/str_detail?id=' + id
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
							this.list = [...this.list, ...res.data.data]
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
				this.list = []
				this.get_lists()
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-bottom: 30rpx;
		background-color: #FFFFFF;

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
				background: #F5F6F8;
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
			padding: 0rpx 30rpx;
			margin-top: 30rpx;

			.item {
				margin-bottom: 30rpx;
				background: #FFFFFF;
				box-shadow: 0rpx 8rpx 20rpx 0rpx rgba(0, 0, 0, 0.04);
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				overflow: hidden;

				.img_box {
					position: relative;

					.img {
						width: 100%;
						height: 258rpx;
						vertical-align: bottom;
					}

					.time {
						position: absolute;
						top: 14rpx;
						left: 20rpx;
						background: rgba(0, 0, 0, 0.36);
						border-radius: 30rpx 30rpx 30rpx 30rpx;
						font-weight: 400;
						font-size: 20rpx;
						color: #FFFFFF;
						font-style: normal;
						text-transform: none;
						padding: 8rpx 10rpx;
					}

					.title {
						position: absolute;
						left: 24rpx;
						bottom: 16rpx;
						width: 400rpx;
						font-weight: 400;
						font-size: 32rpx;
						color: #FFFFFF;
						font-style: normal;
						text-transform: none;
					}

					.position {
						position: absolute;
						right: 14rpx;
						bottom: 14rpx;
						padding: 10rpx 22rpx;
						background: rgba(0, 0, 0, 0.36);
						border-radius: 30rpx 30rpx 30rpx 30rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #FFFFFF;
						font-style: normal;
						text-transform: none;

						.icon {
							width: 24rpx;
							height: 26rpx;
							margin-right: 10rpx;
						}
					}
				}

				.see {
					padding: 20rpx 16rpx;

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

				.des {
					width: 100%;
					box-sizing: border-box;
					padding: 0rpx 16rpx;
					margin-bottom: 34rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
					font-style: normal;
					text-transform: none;
				}
			}
		}
	}
</style>