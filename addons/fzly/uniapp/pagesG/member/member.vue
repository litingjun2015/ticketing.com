<template>
	<view>
		<u-sticky bgColor='#fafafa'>
			<view class="search">
				<u-search placeholder="请输入关键字" searchIconSize='36rpx' height="70rpx" :showAction='false' v-model="value"
					@search='search()'></u-search>
			</view>
		</u-sticky>

		<view class="users_classifying">
			<view class="users_classifying_box">
				<empty v-if="list.length<1"></empty>
				<view class="users_classifying_box_1" v-for="item in list" :key="item.id">
					<view class="users_classifying_box_1_left">
						<image class="users_classifying_box_1_left_img" :src="item.avatar" mode="" />
						<view class="users_classifying_box_1_left_title">
							<view class="users_classifying_box_1_left_title_top">
								{{item.username}}
							</view>
							<view class="users_classifying_box_1_left_title_bottom">
								{{item.createtime }}
							</view>
						</view>
					</view>
					<view class="users_classifying_box_1_right">
						<view class="users_classifying_box_1_right_1">
							邀请人数：<text class="users_classifying_box_1_right_2">{{item.childrenCount || 0}}</text>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		teamlist
	} from '@/api/index.js'
	export default {
		data() {
			return {
				type: 1,
				value: "",
				list: [],
				page: 1
			};
		},
		onLoad(e) {
			this.type = e.type
			this.getList()
		},
		onReachBottom() {
			this.page++
			this.getList(1)
		},
		methods: {
			search() {
				this.page = 1
				this.getList()
			},
			async getList(e) {
				let res = await teamlist({
					type: this.type,
					search: this.value,
					page: this.page
				})
				if (res.code == 1) {
					if (e == 1) {
						this.list = [...this.list, ...res.data.data]
					} else {
						this.list = res.data.data
					}
				}

			}
		}
	}
</script>

<style lang="scss">
	.search {
		padding: 20rpx;
		box-sizing: border-box;
		background-color: #fafafa;
	}

	.users_classifying {
		margin-top: 20rpx;
	}

	.users_classifying_box {
		width: 90%;
		margin: 0 auto;
	}

	.users_classifying_box_1 {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 40rpx;
	}

	.users_classifying_box_1_left {
		display: flex;
		align-items: center;
	}

	.users_classifying_box_1_left_img {
		width: 80rpx;
		height: 80rpx;
		border-radius: 50%;
	}

	.users_classifying_box_1_left_title {
		margin-left: 20rpx;
	}

	.users_classifying_box_1_left_title_top {
		font-size: 32rpx;
		font-weight: 400;
		color: #000216;
	}

	.users_classifying_box_1_left_title_bottom {
		font-size: 22rpx;
		font-weight: 400;
		color: #A6A7BB;
		line-height: 30rpx;
	}

	.users_classifying_box_1_right {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.users_classifying_box_1_right_1 {
		font-size: 20rpx;
		font-weight: 400;
		color: #A6A7BB;
	}

	.users_classifying_box_1_right_2 {
		font-size: 32rpx;
		font-weight: 600;
		color: #000216;
		line-height: 45rpx;
	}

	.users_classifying_box_1_right_3 {
		width: 120rpx;
		height: 56rpx;
		border-radius: 28rpx;
		border: 1rpx solid #377EF5;
		font-size: 24rpx;
		font-weight: 400;
		color: #377EF5;
		line-height: 56rpx;
		text-align: center;
	}
</style>