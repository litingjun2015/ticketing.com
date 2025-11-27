<template>
	<view class="content font_family">
		<view class="header f_j" :style="{paddingTop:menuButtonInfo+'px'}">
			<image class="back" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_b.png" mode="" @click="back"></image>
			<view class="ipt_box f_j">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/magnif.png" mode=""></image>
				<u--input placeholder="搜一搜导游" border="none" @confirm='confirm' v-model="query_data.search"></u--input>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list f_z_b">
			<guide :item='item' v-for="(item,index) in guide_list" :key="index"></guide>
		</view>
		<!-- 加载更多 -->
		<view class="">
			<u-loadmore :status="status" nomoreText='没有更多啦~' line loadingIcon='semicircle' @loadmore='get_guide_list' />
		</view>
	</view>
</template>

<script>
	import { guide_list } from "@/api/index.js"
	import guide from '@/components/guide_card.vue'
	export default {
		components: {
			guide
		},
		data() {
			return {
				menuButtonInfo: 0,
				query_data: {
					page: 1,
					search: '',
				},
				guide_list: [],
				status: 'loadmore'
			};
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
			this.get_guide_list()
		},
		methods: {
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			confirm() {
				this.query_data.page = 1
				this.guide_list = []
				this.get_guide_list()
			},
			// 获取导游列表
			get_guide_list() {
				this.status = 'loading'
				guide_list(this.query_data).then(res => {
					// console.log(res)
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.guide_list = [...this.guide_list, ...res.data.data]
							this.query_data.page += 1
							this.status = 'loadmore'
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
		},
	}
</script>

<style lang="scss" scoped>
	.content {
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
				// border: 2rpx solid #F5F6F8;

				.icon {
					margin-right: 14rpx;
					width: 30rpx;
					height: 30rpx;
				}
			}
		}

		.list {
			padding: 30rpx;
			padding-bottom: 40rpx;
			flex-wrap: wrap;
		}
	}
</style>