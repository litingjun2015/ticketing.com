<template>
	<view class="content font_family">
		<view class="header" :style="{paddingTop:menuButtonInfo+'px',backgroundImage:`url(${user_msg.back_avatar})`}">
			<view class="back f_zj" @click="back">
				<image class="icon" src="../../static/public/back_w.png"></image>
			</view>
			<!-- 作者信息 -->
			<view class="author_msg f">
				<!-- <view class="left">
				</view> -->
				<u-avatar size="124rpx" mode="aspectFill" :src="user_msg.avatar"></u-avatar>
				<view class="right">
					<view class="top">
						<text class="name">{{user_msg.username}}</text>
						<!-- <image class="icon" src="../../static/public/zf_w.png" mode=""></image> -->
					</view>
					<view class="bottom f">
						<view class="data f_d f_j">
							<text>{{user_msg.follow_num}}</text>
							<text class="text">关注</text>
						</view>
						<view class="data f_d f_j">
							<text>{{user_msg.fans_num}}</text>
							<text class="text">粉丝</text>
						</view>
						<view class="data f_d f_j">
							<text>{{user_msg.like_num}}</text>
							<text class="text">获赞</text>
						</view>
						<!-- <view class="btn f_zj">
							<image class="icon" src="../../static/public/ygz.png" mode=""></image>
						</view> -->
						<view class="btn f_zj gz_btn" @click="follow_btn(1)" v-if="user_msg.is_follow==0">
							关注
						</view>
						<view class="btn f_zj gz_btn" @click="follow_btn(2)" v-else>
							已关注
						</view>
						<view class="btn f_zj" @click="go(`/pages/msg/msg_room?df_id=${query_list.df_id}`)">
							发消息
						</view>
					</view>
				</view>
			</view>
		</view>
		<!-- 内容 -->
		<view class="record_box">
			<view class="tab_box">
				<u-tabs lineWidth='30' :activeStyle='activeStyle' lineColor='#FFE706' :list="tabs"
					@click="tab_click"></u-tabs>
			</view>
			<view class="list f_z_b" v-if="list.length>0">
				<placecard :style="{width:car_type==1?'336rpx':'100%'}" :item='item' :type='car_type'
					v-for="(item,index) in list" :key="index"></placecard>
			</view>
			<!-- 加载更多 -->
			<u-loadmore v-if="list.length>0" line @loadmore='get_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
			<view class="empty_top" v-if="list.length==0">
				<u-empty text='没有数据啦~' icon="/static/public/kong.png">
				</u-empty>
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { follow } from "@/api/user.js"
	import { other_info, lists } from '@/api/index_menu/index.js'
	import { mapState } from 'vuex'
	import placecard from '../../components/place_card.vue'
	export default {
		components: {
			placecard
		},
		computed: {
			...mapState(['menuButtonInfo', 'user_info'])
		},
		data() {
			return {
				car_type: 1,
				activeStyle: {
					fontWeight: 400,
					// fontSize: '36rpx',
					color: '#3D3D3D',
				},
				tabs: [
					{ name: '美食', type: 3 },
					{ name: '游记', type: 2 },
					{ name: '攻略', type: 1 },
				],
				query_data: {
					id: ''
				},
				query_list: {
					page: 1,
					type: '',
					df_id: ''
				},
				list: [],
				user_msg: '',
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~',
			};
		},
		onLoad(e) {
			this.query_data.id = e.id
			this.query_list.df_id = e.id
			this.query_list.type = this.tabs[0].type
			this.get_auther()
			this.get_list()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_list()
			}
		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			},
			tab_click(index) {
				if (index.name == '美食') {
					this.car_type = 1
				} else {
					this.car_type = 2
				}
				this.query_list.type = this.tabs[index.index].type
				this.query_list.page = 1
				this.list = []
				this.get_list()
			},
			// 获取他人信息
			get_auther() {
				other_info(this.query_data).then(res => {
					this.user_msg = res.data
				})
			},
			// 获取他人列表
			get_list() {
				this.status = 'loading'
				lists(this.query_list).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.list = [...this.list, ...res.data.data]
							this.query_list.page += 1
							this.status = 'loadmore'
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
			// 关注
			follow_btn(type) {
				// console.log(this.query_data)
				let obj = {
					df_id: this.user_msg.id,
					type
				}
				follow(obj).then(res => {
					console.log(res)
					if (res.code == 1) {
						if (type == 1) {
							this.$refs.uToast.show({
								type: 'success',
								message: '关注成功'
							})
							this.user_msg.is_follow = 1
						} else {
							this.$refs.uToast.show({
								type: 'default',
								message: '取消关注'
							})
							this.user_msg.is_follow = 0
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
		padding-bottom: 30rpx;

		.header {
			box-sizing: border-box;
			height: 400rpx;
			background-repeat: no-repeat;
			background-size: cover;
			padding: 0rpx 30rpx;

			.back {
				width: 59rpx;
				height: 59rpx;
				border-radius: 50%;
				background: rgba(0, 0, 0, 0.4);

				.icon {
					width: 17rpx;
					height: 29rpx;
				}
			}

			.author_msg {
				margin-top: 50rpx;

				.left {
					border: 2rpx solid #FFFFFF;
					border-radius: 50%;
				}

				.right {
					flex: 1;
					margin-left: 15rpx;

					.top {
						.name {
							font-weight: 400;
							font-size: 40rpx;
							color: #FFFFFF;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.icon {
							width: 30rpx;
							height: 30rpx;
							margin-left: 18rpx;
						}
					}

					.bottom {
						.data {
							font-weight: 400;
							font-size: 32rpx;
							color: #FFFFFF;
							line-height: 40rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
							margin-right: 30rpx;

							.text {
								font-size: 24rpx;
							}
						}

						.btn {
							align-self: flex-end;
							width: 116rpx;
							height: 46rpx;
							background: #F8F9FC;
							border-radius: 1914rpx 1914rpx 1914rpx 1914rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #3D3D3D;
							line-height: 40rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
							margin: 0 auto;

							.icon {
								width: 34rpx;
								height: 36rpx;
							}
						}

						.gz_btn {
							background: #FFE706;
						}
					}
				}
			}
		}

		.record_box {
			margin-top: -40rpx;
			min-height: 90vh;
			background: #FFFFFF;
			border-radius: 40rpx 40rpx 0rpx 0rpx;
			padding: 0rpx 30rpx;

			.tab_box {
				border-bottom: 1rpx solid #DFDFDF;
			}

			.list {
				flex-wrap: wrap;
				padding-top: 28rpx;
				padding-bottom: 30rpx;
			}
		}
	}
</style>