<template>
	<view class="box">
		<view class="" style="height: 120rpx;">
		</view>
		<!-- <u-sticky bgColor='#fafafa'> -->
		<view class="content_time">
			<view class="content_time_left" @tap="select_time">
				<view class="content_time_left_time">
					<view v-if="date.length<1">
						全部
					</view>
					<view class="" v-else>
						{{date}}
					</view>
				</view>
				<image class="content_time_left_img" src="../static/triangle_6.png" mode="" />
			</view>
			<view class="content_time_right">
				<view class="content_time_right_left">
					全部收入
				</view>
				<view class="content_time_right_right">
					¥{{detailed.countamount || 0}}
				</view>
			</view>
		</view>
		<!-- </u-sticky> -->

		<view class="content">
			<view class="main_content">
				<view class="">
					<view class="main_content_box" v-for="item in list" :key="item.id">
						<view class="main_content_1">
							<view class="main_content_1_top">
								{{item.username}}
							</view>
							<view class="main_content_1_bottom">
								{{item.pay_time | timerFormat}}
							</view>
						</view>
						<view class="main_content_2" v-if="item.status==2">
							已到帐
						</view>
						<view class="main_content_3">
							{{item.distribution_amount}}
						</view>
					</view>
				</view>
				<!-- <empty v-if="list.length<1"></empty> -->
				<u-empty v-if="list.length<1" text='当前没有记录~' icon="/static/public/kong.png">
				</u-empty>
			</view>
		</view>
		<u-calendar :show="show" :minDate="time" :maxDate="max" @close="onClose" @confirm="onConfirm" />
	</view>
</template>

<script>
	import {
		brokeragedetailed
	} from "@/api/index.js";
	export default {
		data() {
			return {
				page: 1,
				detailed: {},
				list: [],
				date: '',
				show: false,
				max: Number(new Date()),
				time: new Date(new Date().toLocaleDateString()).getTime() - 31 * 24 * 3600 * 1000
			};
		},
		onLoad(options) {
			this.post_brokeragedetailed()
		},
		onReachBottom() {
			this.page++
			this.post_brokeragedetailed(1)
		},
		methods: {
			async post_brokeragedetailed(e) {
				let data = await brokeragedetailed({
					time: this.date,
					page: this.page
				})
				this.detailed = data.data
				if (e == 1) {
					this.list = [...this.list, ...data.data.data.data]
				} else {
					this.list = data.data.data.data
				}
				console.log('this.list', this.list);
			},
			select_time() {
				this.show = true
			},
			onClose() {
				this.show = false
			},
			formatDate(date) {
				date = new Date(date);
				return `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
			},
			onConfirm(event) {
				console.log(event);
				this.show = false
				this.date = event[0]
				this.post_brokeragedetailed()
			},
			go_back() {
				uni.navigateBack({
					delta: 1
				})
			},
		}
	}
</script>

<style lang="scss">
	.content {
		width: 95%;
		margin: 0 auto;
	}

	.content_hadr {
		display: flex;
		align-items: center;
	}

	.content_hadr_img {
		width: 25rpx;
		height: 35rpx;
	}

	.content_hadr_tltle {
		font-size: 38rpx;
		font-weight: 600;
		color: #171717;
		line-height: 53rpx;
		letter-spacing: 1px;
		margin-left: 20rpx;
	}

	.content_time {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 30rpx 10rpx;
		box-sizing: border-box;
		background-color: #fafafa;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		z-index: 99;
	}

	.content_time_left {
		display: flex;
		align-items: center;
		justify-content: space-around;
		height: 51rpx;
		background: #FFFFFF;
		border-radius: 26rpx;
		padding: 0 10rpx 0 0;
		margin-left: 10rpx;
	}

	.content_time_left_time {
		font-size: 24rpx;
		font-family: PingFangSC-Regular, PingFang SC;
		font-weight: 400;
		color: #616161;
		text-align: center;
		margin-left: 30rpx;
	}

	.content_time_left_img {
		width: 23rpx;
		height: 23rpx;
		margin-right: 10rpx;
	}

	.content_time_right {
		width: 220rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-right: 10rpx;

	}

	.content_time_right_left {
		font-size: 28rpx;
		font-weight: 400;
		color: #616161;
	}

	.content_time_right_right {
		font-size: 38rpx;
		font-weight: 500;
		color: #010101;
	}

	.main_content {}

	.main_content_box {
		height: 143rpx;
		margin: 0 auto;
		display: flex;
		justify-content: space-between;
		align-items: center;
		background-color: #FFFFFF;
		border-radius: 14rpx;
		margin-bottom: 20rpx;
		position: relative;
	}

	.main_content_1 {

		margin-left: 20rpx;
	}

	.main_content_1_top {
		font-size: 28rpx;
		font-weight: 400;
		color: #010101;
		margin-bottom: 10rpx;
	}

	.main_content_1_bottom {
		font-size: 24rpx;
		font-weight: 400;
		color: #888B90;
	}

	.main_content_2 {
		font-size: 28rpx;
		font-weight: 400;
		color: #4F99F6;
		line-height: 40rpx;
		position: absolute;
		left: 50%;
		transform: translateX(-50%);
	}

	.main_content_3 {
		font-size: 40rpx;
		font-weight: 600;
		color: #010101;
		margin-right: 20rpx;
	}

	.van-empty {
		height: 80vh;
	}
</style>