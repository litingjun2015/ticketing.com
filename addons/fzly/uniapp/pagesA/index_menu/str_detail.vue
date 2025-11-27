<template>
	<view class="content font_family" :style="{paddingBottom:120+safe_bottom+'rpx'}" v-if="msg">
		<view class="header " :style="{paddingTop:menuButtonInfo+'px',backgroundImage:`url(${msg.image})`}">
			<view class="box">
				<view class="user f_j">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_w.png" mode="" @click="back"></image>
					<u-avatar size="80rpx" @click="go(msg.user_id)" :src="msg.avatar"></u-avatar>
					<text class="name text_ellipsis">{{msg.username}}</text>
					<view class="btn" @click="follow_btn(1)" v-if="msg.is_gz==0 && user_info.id!==msg.user_id">
						关注
					</view>
					<view class="btn is_gz" @click="follow_btn(2)" v-if="msg.is_gz!=0 && user_info.id!==msg.user_id">
						已关注
					</view>
				</view>
				<view class="position f_j" :style="{bottom:menuButtonInfo+28+'px'}">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/pt.png" mode=""></image>
					<text>{{msg.city}}</text>
				</view>
			</view>
		</view>
		<!-- 内容 -->
		<view class="article">
			<view class="title">
				{{msg.title}}
			</view>
			<view class="text">
				<u-parse :content="msg.content"></u-parse>
			</view>
		</view>
		<!-- 底部菜单栏 -->
		<view class="bottom">
			<view class="btn f_z_b">
				<view class="item">
					<image @click="dz_btn(1)" v-if="msg.is_dz==0" class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/like.png"
						mode=""></image>
					<image @click="dz_btn(2)" v-else class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/like_t.png" mode=""></image>
					<text class="num" @click="dz_btn(1)" v-if="msg.is_dz==0">{{msg.dz_nums}}</text>
					<text class="num" @click="dz_btn(2)" v-else>{{msg.dz_nums}}</text>
				</view>
				<view class="item">
					<image @click="sc_btn(1)" v-if="msg.is_sc==0" class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/collection.png"
						mode=""></image>
					<image @click="sc_btn(2)" v-else class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/star_t.png" mode=""></image>
					<text class="num" @click="sc_btn(1)" v-if="msg.is_sc==0">{{msg.sc_nums}}</text>
					<text class="num" @click="sc_btn(2)" v-else>{{msg.sc_nums}}</text>
				</view>
				<view class="item">
					<image class="icon1" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/view.png" mode=""></image>
					<text class="num">{{msg.view_nums}}</text>
				</view>
			</view>
			<!-- 底部占位 -->
			<view class="safe_bottom">

			</view>
			<u-toast ref="uToast"></u-toast>
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { detail, dz, sc } from '@/api/index_menu/index.js'
	import { follow } from "@/api/user.js"
	export default {
		data() {
			return {
				menuButtonInfo: 0,
				safe_bottom: 0,
				query: {
					id: ''
				},
				msg: '',
				dz_sc: {
					option: '',
					type: 1,
					id: ''
				},
			}
		},
		computed: {
			...mapState(['user_info'])
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		onLoad(e) {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.safe_bottom = getApp().globalData.safe_bottom
			this.query.id = e.id
			this.dz_sc.id = e.id
			this.get_content()
		},
		methods: {
			go(id) {
				uni.navigateTo({
					url: '/pagesA/index_menu/author?id=' + id
				})
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// 获取攻略内容
			get_content() {
				detail(this.query).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.msg = res.data
					}
				})
			},
			// 点赞
			dz_btn(type) {
				this.dz_sc.option = type
				dz(this.dz_sc).then(res => {
					if (res.code == 1) {
						if (type == 1) {
							this.$refs.uToast.show({
								type: 'success',
								message: '点赞成功'
							})
							this.msg.is_dz = 1
							this.msg.dz_nums += 1
						} else {
							this.msg.dz_nums -= 1
							this.msg.is_dz = 0
						}
						this.$forceUpdate()
					}
				})
			},
			// 收藏
			sc_btn(type) {
				this.dz_sc.option = type
				sc(this.dz_sc).then(res => {
					if (res.code == 1) {
						if (type == 1) {
							this.$refs.uToast.show({
								type: 'success',
								message: '收藏成功'
							})
							this.msg.is_sc = 1
							this.msg.sc_nums += 1
						} else {
							this.msg.sc_nums -= 1
							this.msg.is_sc = 0
						}
						this.$forceUpdate()
					}
				})
			},
			// 关注
			follow_btn(type) {
				let obj = {
					df_id: this.msg.user_id,
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
							this.msg.is_gz = 1
						} else {
							this.$refs.uToast.show({
								type: 'default',
								message: '取消关注'
							})
							this.msg.is_gz = 0
						}
						this.$forceUpdate()
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: #ffffff;

		.header {
			height: 378rpx;
			background-repeat: no-repeat;
			background-size: 100%;
			box-sizing: border-box;

			.box {
				padding-left: 46rpx;
				height: 100%;
				position: relative;
			}

			.user {
				.icon {
					width: 18rpx;
					height: 36rpx;
					margin-right: 20rpx;
				}

				.name {
					margin-left: 22rpx;
					font-weight: 400;
					font-size: 32rpx;
					color: #FFFFFF;
					line-height: 44rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					width: 150rpx;
				}

				.btn {
					margin-left: 30rpx;
					background: #FFE706;
					border-radius: 622rpx 622rpx 622rpx 622rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #3D3D3D;
					font-style: normal;
					text-transform: none;
					padding: 4rpx 24rpx;
				}

				.is_gz {
					background-color: #ffffff !important;
				}
			}

			.position {
				position: absolute;
				left: 46rpx;
				bottom: 46rpx !important;
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

		.article {
			padding: 20rpx 40rpx;
			z-index: 100;
			margin-top: -30rpx;
			// min-height: 80vh;
			background: #FFFFFF;
			border-radius: 40rpx 40rpx 0rpx 0rpx;

			.title {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				line-height: 40rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.text {
				margin-top: 20rpx;
			}
		}

		.bottom {
			width: 100vw;
			position: fixed;
			bottom: 0;
			left: 0;
			background: #FFFFFF;
			box-shadow: 2rpx -6rpx 4rpx 0rpx rgba(0, 0, 0, 0.02);

			.btn {
				padding: 30rpx 40rpx;
				padding-bottom: 0rpx;
				// justify-content: flex-end;

				.item {
					// margin-left: 100rpx;

					.icon {
						width: 26rpx;
						height: 24rpx;
					}

					.icon1 {
						width: 30rpx;
						height: 20rpx;
					}

					.num {
						margin-left: 10rpx;
						font-weight: 400;
						font-size: 32rpx;
						color: #3D3D3D;
						line-height: 40rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}
				}
			}
		}
	}
</style>