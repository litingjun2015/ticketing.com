<template>
	<view class="content font_family" :style="{paddingBottom:120+safe_bottom+'rpx'}" v-if="detail_msg">
		<view class="user_top f_j" :style="{paddingTop:menuButtonInfo+'px'}" v-if="top_show">
			<image class="icon" src="../../static/public/back_b.png" mode="" @click="back"></image>
			<u-avatar size="80rpx" :src="detail_msg.avatar"></u-avatar>
			<text class="name">{{detail_msg.username}}</text>
			<view class="btn" @click="follow_btn(1)" v-if="detail_msg.is_gz==0 && user_info.id!==detail_msg.user_id">
				关注
			</view>
			<view class="btn is_gz" @click="follow_btn(2)"
				v-if="detail_msg.is_gz!=0 && user_info.id!==detail_msg.user_id">
				已关注
			</view>
		</view>
		<view class="header " :style="{paddingTop:menuButtonInfo+'px',backgroundImage:`url(${detail_msg.image})`}">
			<view class="box">
				<view class="user f_j">
					<image class="icon" src="../../static/public/back_w.png" mode="" @click="back"></image>
				</view>
				<view class="position" :style="{bottom:menuButtonInfo-10+'px'}">
					<view class="title">
						{{detail_msg.title}}
					</view>
					<view class="num">
						<text>{{detail_msg.view_nums}}</text>
						<text>阅读</text>
					</view>
				</view>
			</view>
		</view>
		<!-- 内容 -->
		<view class="article">
			<view class=" f_z">
				<view class="line">

				</view>
			</view>
			<!-- 作者 -->
			<view class="user_box f_z_b f_j">
				<view class="left f_j">
					<u-avatar size="80rpx" :src="detail_msg.avatar"></u-avatar>
					<text class="name">{{detail_msg.username}}</text>
				</view>
				<view class="right" @click="follow_btn(1)"
					v-if="detail_msg.is_gz==0 && user_info.id!==detail_msg.user_id">
					关注
				</view>
				<view class="right is_gz" @click="follow_btn(2)"
					v-if="detail_msg.is_gz!=0 && user_info.id!==detail_msg.user_id">
					已关注
				</view>
			</view>
			<view class="title">
				{{detail_msg.title}}
			</view>
			<view class="text" style="margin-top: 20rpx;">
				<u-parse :content='detail_msg.content'></u-parse>
			</view>
		</view>
		<!-- 底部菜单栏 -->
		<view class="bottom">
			<view class="btn f_z_b">
				<view class="item">
					<image @click="dz_btn(1)" v-if="detail_msg.is_dz==0" class="icon" src="../../static/public/like.png"
						mode=""></image>
					<image @click="dz_btn(2)" v-else class="icon" src="../../static/public/like_t.png" mode=""></image>
					<text v-if="detail_msg.is_dz==0" @click="dz_btn(1)" class="num">{{detail_msg.dz_nums}}</text>
					<text v-else @click="dz_btn(2)" class="num">{{detail_msg.dz_nums}}</text>
				</view>
				<view class="item">
					<image @click="sc_btn(1)" v-if="detail_msg.is_sc==0" class="icon"
						src="../../static/public/collection.png" mode=""></image>
					<image @click="sc_btn(2)" v-else class="icon" src="../../static/public/star_t.png" mode=""></image>
					<text @click="sc_btn(1)" v-if="detail_msg.is_sc==0" class="num">{{detail_msg.sc_nums}}</text>
					<text @click="sc_btn(2)" v-else class="num">{{detail_msg.sc_nums}}</text>
				</view>
				<view class="item">
					<image class="icon1" src="../../static/public/view.png" mode=""></image>
					<text class="num">{{detail_msg.view_nums}}</text>
				</view>
			</view>
			<!-- 底部占位 -->
			<view class="safe_bottom">

			</view>

		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import { detail } from "@/api/index_menu/index.js"
	import minix from '@/mixins/user.js'
	export default {
		mixins: [minix],
		data() {
			return {
				menuButtonInfo: 0,
				safe_bottom: 0,
				top_show: false,
				id: '',
				detail_msg: '',
				dz_sc: {
					option: '',
					type: 2,
					id: ''
				},
			}
		},
		computed: {
			...mapState(['user_info'])
		},
		onLoad(e) {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.safe_bottom = getApp().globalData.safe_bottom
			this.id = e.id
			this.dz_sc.id = e.id
			this.get_detail()
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		onPageScroll() {
			this.$nextTick(() => {
				uni.createSelectorQuery().in(this).select('.user_box').boundingClientRect(rect => {
					if (rect.top < -10) {
						this.top_show = true
					} else if (rect.top > 100) {
						this.top_show = false
					}
				}).exec();
			});
		},
		methods: {
			clivk() {
				console.log(2222)
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			// 获取详情
			get_detail() {
				detail({ id: this.id }).then(res => {
					// console.log(res)
					if (res.code == 1) {
						this.detail_msg = res.data
					}
				})
			},
			cs() {
				console.log(2222)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.user_top {
			position: fixed;
			top: 0;
			left: 0;
			padding-left: 46rpx;
			background-color: #FFFFFF;
			width: 100%;
			padding-bottom: 20rpx;
			z-index: 99999;

			.icon {
				width: 18rpx;
				height: 36rpx;
				margin-right: 68rpx;
			}

			.name {
				margin-left: 22rpx;
				font-weight: 400;
				font-size: 32rpx;
				line-height: 44rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			// .is_gz {
			// 	background: none;
			// 	border: 2rpx solid #FFE706;
			// }

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
		}

		.header {
			height: 378rpx;
			// background-image: url(https://img0.baidu.com/it/u=2848384610,1881282476&fm=253&fmt=auto&app=138&f=JPEG?w=1422&h=800);
			background-repeat: no-repeat;
			background-size: 100%;

			.box {
				padding-left: 46rpx;
				height: 100%;
				position: relative;
				box-sizing: border-box;
			}

			.user {
				.icon {
					width: 18rpx;
					height: 36rpx;
					margin-right: 68rpx;
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
			}

			.position {
				position: absolute;
				left: 46rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #FFFFFF;
				font-style: normal;
				text-transform: none;
				padding-right: 20rpx;

				.title {
					font-size: 32rpx;
				}

				.icon {
					width: 24rpx;
					height: 26rpx;
					margin-right: 10rpx;
				}

				.num {
					font-weight: 400;
					font-size: 24rpx;
					color: #D6D6D6;
					line-height: 40rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}
		}

		.article {

			.line {
				margin-top: -50rpx;
				width: 68rpx;
				height: 12rpx;
				background: #FFFFFF;
				border-radius: 904rpx 904rpx 904rpx 904rpx;
			}

			padding: 20rpx 40rpx;
			z-index: 100;
			z-index: 999999999;
			margin-top: -30rpx;
			min-height: 120vh;
			background: #FFFFFF;
			border-radius: 40rpx 40rpx 0rpx 0rpx;

			.user_box {
				margin-bottom: 30rpx;
				margin-top: 10rpx;
				padding: 22rpx 34rpx;
				background: #F8F9FC;
				border-radius: 20rpx 20rpx 20rpx 20rpx;
				z-index: 99999;

				.left {
					.name {
						font-weight: 400;
						font-size: 32rpx;
						color: #3D3D3D;
						line-height: 44rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
						margin-left: 22rpx;
					}
				}

				.right {
					font-weight: 400;
					font-size: 24rpx;
					color: #3D3D3D;
					line-height: 34rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					background: #FFE706;
					border-radius: 622rpx 622rpx 622rpx 622rpx;
					padding: 4rpx 24rpx;
				}

				.is_gz {
					// background: none;
					border: 2rpx solid #FFE706;
				}
			}

			.title {
				font-weight: 400;
				font-size: 32rpx;
				color: #3D3D3D;
				line-height: 40rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
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

				.item {
					.icon {
						width: 26rpx;
						height: 24rpx;
					}

					.icon1 {
						width: 30rpx;
						height: 26rpx;
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