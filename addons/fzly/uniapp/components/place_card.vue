<template>
	<view class="card font_family" :style="{width:type==1?'336rpx':'100%'}" @click="go_dynamic">
		<view class="card_img">
			<image class="img" :src="item.image" mode="aspectFill"></image>
			<view class="tag f_zj">
				{{item.type==1?'攻略':item.type==2?'游记':'美食'}}
			</view>
			<view class="pt f_j">
				<image class="pt_icon" src="../static/index/pt.png" mode=""></image>
				<text class="text">{{item.city}}</text>
			</view>
		</view>
		<!-- 标题 -->
		<view class="title">
			{{item.title}}
		</view>
		<!-- 喜欢 -->
		<view class="user_like f_z_b">
			<view class="user f_j">
				<u-avatar :src="item.avatar" size="30rpx"></u-avatar>
				<text class="text">{{item.username}}</text>
			</view>
			<view class="like f_j" v-if="item.is_sc==0">
				<image class="icon" src="../static/public/collection.png" mode=""></image>
				<text class="text">{{item.sc_nums}}</text>
			</view>
			<view class="like f_j" v-else>
				<image class="icon" src="../static/public/star_t.png" mode=""></image>
				<text class="text">{{item.sc_nums}}</text>
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { sc } from '@/api/index_menu/index.js'
	export default {
		name: "place_card",
		props: ['type', 'item'],
		data() {
			return {
				query_data: {
					option: '',
					type: '',
					id: '',
				}
			};
		},
		methods: {
			go_dynamic() {
				if (this.item.type == 1) {
					uni.navigateTo({
						url: '/pagesA/index_menu/str_detail?id=' + this.item.id
					})
				} else if (this.item.type == 2) {
					uni.navigateTo({
						url: '/pagesA/index_menu/record_detail?id=' + this.item.id
					})

				} else {
					uni.navigateTo({
						url: '/pagesA/index_menu/must_detail?id=' + this.item.id
					})
				}
			},
			// 收藏
			// sc_btn(type) {
			// 	console.log(this.item)
			// 	this.query_data.option = type
			// 	this.query_data.type = this.item.type
			// 	this.query_data.id = this.item.id
			// 	console.log(this.query_data)
			// 	sc(this.query_data).then(res => {
			// 		console.log(res)
			// 		if (res.code == 1) {
			// 			if (type == 1) {
			// 				this.$refs.uToast.show({
			// 					type: 'success',
			// 					message: '收藏成功'
			// 				})
			// 				this.item.is_sc = 1
			// 				this.item.sc_nums += 1
			// 			} else {
			// 				this.item.is_sc = 0
			// 				this.item.sc_nums -= 0
			// 			}
			// 			this.$forceUpdate()
			// 		}
			// 	})
			// }
		},
	}
</script>

<style lang="scss" scoped>
	.card {
		// width: 336rpx;
		// width: 100%;
		margin-bottom: 20rpx;

		.card_img {
			width: 100%;
			position: relative;

			.img {
				width: 100%;
				height: 336rpx;
				border-radius: 24rpx 24rpx 24rpx 24rpx;
			}

			.tag {
				position: absolute;
				right: 0;
				top: 0;
				width: 102rpx;
				height: 46rpx;
				background: rgba(0, 0, 0, 0.36);
				border-radius: 0rpx 24rpx 0rpx 24rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #FFFFFF;
				line-height: 28rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.pt {
				position: absolute;
				bottom: 16rpx;
				left: 16rpx;
				height: 46rpx;
				padding: 0rpx 22rpx;
				background: rgba(0, 0, 0, 0.36);
				border-radius: 30rpx 30rpx 30rpx 30rpx;

				.pt_icon {
					width: 24rpx;
					height: 26rpx;
				}

				.text {
					font-weight: 400;
					font-size: 24rpx;
					color: #FFFFFF;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-left: 10rpx;
				}
			}
		}

		.title {
			margin-top: 12rpx;
			width: 100%;
			font-weight: 400;
			font-size: 28rpx;
			color: #000000;
			line-height: 33rpx;
			text-align: left;
			font-style: normal;
			text-transform: none;
			overflow: hidden;
			white-space: nowrap;
			text-overflow: ellipsis;
		}

		.user_like {
			margin-top: 16rpx;

			.user {
				.text {
					font-weight: 400;
					font-size: 24rpx;
					color: #999999;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-left: 10rpx;
				}
			}

			.like {
				.icon {
					width: 24rpx;
					height: 24rpx;
				}

				.text {
					font-weight: 400;
					font-size: 24rpx;
					color: #999999;
					line-height: 28rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-left: 10rpx;
				}
			}
		}
	}
</style>