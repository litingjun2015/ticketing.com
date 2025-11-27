<template>
	<view class="content font_family">
		<view class="headr_box f" v-if="msg">
			<view class="item" :style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/1.png)`}">
				<view class="title">
					超高评价不一样
				</view>
				<view class="data f_j">
					<view class="data_item">
						<view class="num">
							{{msg.ffyh | num}}{{msg.ffyh<1000?'':'+'}}
						</view>
						<view class="text">
							服务用户
						</view>
					</view>
					<view class="line">

					</view>
					<view class="data_item">
						<view class="num">
							{{msg.hps}}
						</view>
						<view class="text">
							获赞数
						</view>
					</view>
				</view>
			</view>
			<view class="item f_d item_right"
				:style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/2.png)`}">
				<view class="title">
					行业能力官方验
				</view>
				<view class="data f_j">
					<view class="data_item f_d data_item_right">
						<view class="num">
							{{msg.zz}}年+
						</view>
						<view class="text">
							行业资历
						</view>
					</view>
					<view class="line">

					</view>
					<view class="data_item f_d data_item_right">
						<view class="num">
							{{msg.hpl}}%
						</view>
						<view class="text">
							好评率
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="list_box">
			<view class="headr f_z_b f_j">
				<view class="title">用户评价</view>
				<view class="statr">
					<text class="num">{{start}}</text>
					<text class="text">分</text>
				</view>
			</view>
			<view class="tag f">
				<view class="item" v-for="(item,index) in tag_list" :key="index">
					<text>{{item.name}}</text>
					<text class="text_mar">{{item.num}}</text>
				</view>
			</view>
			<!-- 用户列表 -->
			<view class="user_item" v-for="(item,index) in comment_list" :key="index">
				<view class="user_msg f_z_b f_j">
					<view class="left f_j">
						<u-avatar size="60rpx" :src="item.avatar"></u-avatar>
						<view class="name">
							{{item.username}}
						</view>
					</view>
					<view class="right">
						{{item.creattime}}
					</view>
				</view>
				<view class="evaluate_content">
					{{item.evaluate}}
				</view>
				<view class="img_arr">
					<image class="item" v-for="(img,img_index) in item.images" :key="img_index" :src="img"
						mode="aspectFill"></image>
				</view>
			</view>
			<!-- 加载更多 -->
			<u-loadmore v-if="comment_list.length>0" line @loadmore="get_user_comment" :status="status"
				:loading-text="loadingText" :loadmore-text="loadmoreText" :nomore-text="nomoreText" />
			<!-- 空 -->
			<u-empty v-if="comment_list.length==0" text='当前没有评论哟~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
	</view>
</template>

<script>
	import { admission_comment } from '@/api/index_menu/index.js'
	export default {
		data() {
			return {
				projectUrl: '',
				comment_list: [],
				// 加载更多
				status: 'loadmore',
				loadingText: '努力加载中',
				loadmoreText: '点击或上拉加载更多',
				nomoreText: '没有更多啦~',
				start: '',
				query: {
					id: '',
					page: 1,
					type: 2
				},
				tag_list: [],
				msg: ''
			}
		},
		onLoad(e) {
			this.projectUrl = this.$projectUrl
			this.start = e.start
			this.query.id = e.id
			this.get_user_comment()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_user_comment()
			}
		},
		methods: {
			// 获取用户评论
			get_user_comment() {
				this.status = 'loading'
				admission_comment(this.query).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.tag_list = res.data.tags
						this.msg = res.data.xx
						if (res.data.data.data.length > 0) {
							this.status = 'loadmore'
							this.comment_list = [...this.comment_list, ...res.data.data.data]
							this.query.page += 1
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
		background-color: #F6FAFF;

		.headr_box {
			.item_right {
				align-items: flex-end;
			}

			.item {
				flex: 1;
				height: 194rpx;
				background-repeat: no-repeat;
				background-size: cover;
				background-position: center;
				padding: 20rpx;
				box-sizing: border-box;


				.title {
					font-weight: 400;
					font-size: 28rpx;
					color: #232323;
				}

				.data {
					margin-top: 10rpx;

					.data_item_right {
						align-items: flex-end;
					}

					.data_item {
						.num {
							font-family: Alibaba PuHuiTi 3.0, Alibaba PuHuiTi 30;
							font-weight: 800;
							font-size: 36rpx;
							color: #232323;
						}

						.text {
							font-weight: 400;
							font-size: 22rpx;
							color: #333333;
							margin-top: 10rpx;
						}
					}

					.line {
						width: 2rpx;
						height: 58rpx;
						background: rgba(153, 153, 153, 0.2);
						margin: 0rpx 25rpx;
					}
				}
			}
		}

		box-sizing: border-box;
		padding: 30rpx;

		.list_box {
			margin-top: 40rpx;
			padding: 30rpx 26rpx;
			background: #FFFFFF;
			border-radius: 24rpx 24rpx 24rpx 24rpx;
			min-height: 70vh;

			.headr {
				.title {
					font-weight: 400;
					font-size: 32rpx;
					color: #000000;
				}

				.statr {
					.num {
						font-weight: 700;
						font-size: 60rpx;
						color: #000000;
					}

					.text {
						font-weight: 400;
						font-size: 26rpx;
						color: #000000;
					}
				}
			}

			.tag {
				margin-top: 18rpx;
				flex-wrap: wrap;

				.item {
					border-radius: 25rpx 25rpx 25rpx 25rpx;
					border: 2rpx solid #232323;
					padding: 12rpx 30rpx;
					font-weight: 400;
					font-size: 20rpx;
					color: #232323;
					margin-right: 20rpx;
					margin-bottom: 15rpx;

					.text_mar {
						margin-left: 20rpx;
					}
				}
			}

			.user_item {
				margin-top: 20rpx;

				.user_msg {
					.left {
						.name {
							font-weight: 400;
							font-size: 28rpx;
							color: #232323;
							margin-left: 20rpx;
						}
					}

					.right {
						font-weight: 400;
						font-size: 22rpx;
						color: #999999;
					}
				}

				.evaluate_content {
					margin-top: 15rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #000000;
				}

				.img_arr {
					margin-top: 15rpx;
					flex-wrap: wrap;

					.item {
						width: 198rpx;
						height: 198rpx;
						border-radius: 16rpx 16rpx 16rpx 16rpx;
						margin-right: 12rpx;
						margin-bottom: 12rpx;
						vertical-align: bottom;
					}
				}
			}
		}
	}
</style>