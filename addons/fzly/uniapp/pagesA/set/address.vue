<template>
	<view class="content font_family">
		<scroll-view scroll-y="true" class="city_box" :scroll-into-view='scroll_index'>
			<view class="hot_city_box" id="index">
				<view class="title">
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/hot_city.png" mode=""></image>
					<text>热门城市</text>
				</view>
				<view class="hot_city f">
					<view class="item" v-for="(item,index) in hot_city" :key="index" @click="check_residence(item)">
						{{item}}
					</view>
				</view>
			</view>
			<!-- 城市列表 -->
			<view class="class_city f" v-for="(item,index) in itemArr" :id="item.id">
				<text class="text_id">{{item.id}}</text>
				<view class="item_box">
					<view class="item" v-for="(city,index1) in item.value" @click="check_residence(city.shortname)">
						<text class="text_city">{{city.shortname}}</text>
					</view>
				</view>
			</view>
			<view class="city_index">
				<view class="item" @click="check_index('index')">
					#
				</view>
				<view class="item" v-for="(item,index) in indexList" :key="index" @click="check_index(item)">
					{{item}}
				</view>
			</view>
		</scroll-view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { area, userInfo, profile } from "@/api/public.js"
	import { mapMutations } from 'vuex'
	export default {
		data() {
			return {
				indexList: ["A", "B", "C", "d", "e", "f", "g", "h", "i", "j", "k", "k", "k", "k", "k", "k", "k", "k",
					"k",
					"k", "k", "k", "k", "k", "c", "c"
				],
				itemArr: [
					['列表A1', '列表A2', '列表A3'],
					['列表B1', '列表B2', '列表B3'],
					['列表C1', '列表C2', '列表C3'],
					['列表', '列表C2', '列表C3'],
					['列表C1', '列表C2', '列表C3'],
					['列表C1', '列表C2', '列表C3'],
					['列表C1', '列表C2', '列表C3'],
					['列表C1', '列表C2', '列表C3'],
					['列表C1', '列表C2', '列表C3'],
					['列表C1', '列表C2', '列表C3'],
					['列表C1', '列表C2', '列表C3'],
				],
				hot_city: [],
				scroll_index: '#',
				go_type: '', //来到当前页面的要干什么 set是修改常住地，query是用于用户搜索,check为商家发布产品选择景区城市
			}
		},
		onLoad(e) {
			this.get_city_list()
			this.go_type = e.type
		},
		methods: {
			...mapMutations(['set_user_info', 'set_city', 'set_jq_city']),
			list_cell(cell) {
				console.log(cell)
			},
			check_index(index) {
				console.log(index)
				this.scroll_index = index
			},
			// 获取城市列表
			get_city_list() {
				area().then(res => {
					console.log(res)
					if (res.code == 1) {
						this.hot_city = res.data.hot
						this.indexList = res.data.indexList
						this.itemArr = res.data.data
					}
				})
			},
			// 选择常住地
			check_residence(name) {
				// this.set_residence(name)
				if (this.go_type == 'set') {
					profile({ c_city: name }).then(res => {
						if (res.code == 1) {
							this.get_user_msg()
							this.$refs.uToast.show({
								type: 'success',
								message: '修改成功',
								complete: () => {
									uni.navigateBack({
										delta: 1
									})
								}
							})
						}
					})
				} else if (this.go_type == 'query') {
					this.set_city(name)
					uni.setStorageSync('city', name)
					uni.navigateBack({
						delta: 1
					})
				} else {
					this.set_jq_city(name)
					uni.navigateBack({
						delta: 1
					})
				}
			},
			// 获取用户信息
			get_user_msg() {
				userInfo().then(res => {
					if (res.code == 1) {
						this.set_user_info(res.data)
						uni.setStorageSync('user_info', res.data)
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.city_box {
			height: 100vh;
			background-color: #ffffff;
			position: relative;

			.class_city {
				padding: 0rpx 20rpx;
				// align-items: center;

				.text_id {
					padding-top: 28rpx;
					font-weight: 400;
					font-size: 32rpx;
					color: #818181;
				}

				.item_box {
					margin-left: 20rpx;
					flex: 1;

					.item {
						padding: 28rpx 0rpx;
						border-bottom: 1rpx solid #EEEEEE;

						.text_city {
							font-weight: 400;
							font-size: 32rpx;
							color: #2B2B2B;
						}
					}
				}

			}

			.city_index {
				font-size: 20rpx;
				color: #2B2B2B;
				position: fixed;
				top: 50%;
				right: 20rpx;
				/* 或其他需要的值 */
				transform: translateY(-50%);

				.item {
					margin-bottom: 10rpx;
				}
			}
		}

		// ::v-deep .list-cell {
		// 	padding: 20rpx;
		// }

		// ::v-deep .u-index-anchor {
		// 	background-color: #ffffff !important;
		// 	border: none !important;
		// }

		.hot_city_box {
			padding: 20rpx;

			.title {
				font-weight: 400;
				font-size: 28rpx;
				color: #8E9498;

				.icon {
					width: 30rpx;
					height: 30rpx;
					margin-right: 10rpx;
				}
			}

			.hot_city {
				flex-wrap: wrap;
				margin-top: 30rpx;

				.item {
					padding: 18rpx 40rpx;
					background: #F5F5F5;
					border-radius: 6rpx 6rpx 6rpx 6rpx;
					font-weight: 400;
					font-size: 30rpx;
					color: #2B2B2B;
					margin-right: 20rpx;
					margin-bottom: 20rpx;
				}
			}
		}
	}
</style>