<template>
	<view class="content font_family f">
		<view class="left">
			<scroll-view class="item_box" scroll-y="true">
				<view class="item" :class="{item_action:item_check==index}" v-for="(item,index) in must_item"
					:key="index" @click="item_click(index,item.id)">{{item.title}}</view>
			</scroll-view>
		</view>
		<scroll-view class="right" scroll-y="true">
			<view class="list_item" v-for="(item,index) in list" :key="index"
				@click="go('/pagesA/index_menu/tickets_detail?id='+item.id)">
				<image class="img" :src="item.image" mode="aspectFill"></image>
				<view v-if="index<5" class="sort f_d f_j" :class="{sort_h:index>2}">
					<text>TOP</text>
					<text class="num">{{index+1}}</text>
				</view>
				<view class="title text_ellipsis">
					{{item.title}}
				</view>
			</view>
			<!-- 空列表 -->
			<view class="" v-if="list.length==0">
				<u-empty text='没有数据啦~' icon="/static/public/kong.png">
				</u-empty>
			</view>
		</scroll-view>
	</view>
</template>

<script>
	import { spot_type_list } from "@/api/index.js"
	import { must_list } from "@/api/index_menu/index.js"
	export default {
		data() {
			return {
				must_item: [],
				list: [],
				item_check: 0,
				type_id: '',
			}
		},
		onLoad() {
			this.get_type_list()
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			},
			item_click(index, id) {
				this.item_check = index
				this.type_id = id
				this.get_list()
			},
			// 获取分类列表
			get_type_list() {
				spot_type_list().then(res => {
					this.must_item = res.data
					this.type_id = res.data[0].id
					this.get_list()
				})
			},
			// 获取列表
			get_list() {
				must_list({ type_id: this.type_id }).then(res => {
					if (res.code == 1) {
						this.list = res.data
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.left {

			.item_box {
				width: 200rpx;
				height: 100vh;
				background: #FFFFFF;
				font-weight: 400;
				font-size: 28rpx;
				color: #3D3D3D;
				line-height: 40rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;

				// background: red;
				.item {
					// height: 100rpx;
					padding: 25rpx 25rpx;
				}

				.item_action {
					font-size: 36rpx;
					color: #64BAFD;
				}
			}
		}

		.right {
			height: 100vh;
			padding: 0rpx 30rpx;
			padding-bottom: 40rpx;
			box-sizing: border-box;

			.list_item {
				position: relative;
				width: 494rpx;
				height: 222rpx;
				margin-top: 40rpx;
				// overflow: hidden;

				.img {
					width: 100%;
					height: 100%;
					vertical-align: bottom;
					border-radius: 16rpx 16rpx 16rpx 16rpx;
				}

				.sort {
					justify-content: center;
					width: 74rpx;
					height: 109rpx;
					position: absolute;
					top: -15rpx;
					left: 20rpx;
					background-image: url(../../static/public/sort_q.png);
					background-repeat: no-repeat;
					background-size: 100%;
				}

				.sort_h {
					background-image: url(../../static/public/sort_h.png) !important;
					color: #FFFFFF;
				}

				.title {
					position: absolute;
					left: 20rpx;
					bottom: 20rpx;
					font-weight: 400;
					font-size: 32rpx;
					color: #FFFFFF;
					text-align: left;
					font-style: normal;
					text-transform: none;
					width: 450rpx;
				}
			}
		}
	}
</style>