<template>
	<view v-if="display">
		<u-sticky>
			<u-navbar title="提现记录" leftIconColor='#fff' :titleStyle='{color:"#fff"}' :autoBack="true"
				:bgColor="bgColor">>
			</u-navbar>
			<view class="head">
				<view class="f-size-26 mb-30">
					已累计提现金额(元)
				</view>
				<view class="head_price">
					￥<span>{{info.commissionOver}}</span>
				</view>
			</view>
			<view class="tabs">
				<u-tabs @click="click" :list="tabList" lineColor="#50ae5aff" :activeStyle="{
            color: '#50ae5aff',
        }" :inactiveStyle="{
            color: '#606266',
        }" :current='current'></u-tabs>
			</view>
		</u-sticky>

		<view class="content pt-20">
			<!-- <empty v-if="list.length<1"></empty> -->
			<u-empty v-if="list.length<1" text='当前没有记录~' icon="/static/public/kong.png">
			</u-empty>
			<view class="main_content" v-for="item in list" :key="item">
				<view class="main_content_box">
					<view class="main_content_1">
						<view class="main_content_1_top">
							{{item.username}}
						</view>
						<view class="main_content_1_bottom">
							{{item.createtime | timerFormat}}
						</view>
					</view>
					<view class="main_content_2">
						<view v-if="item.status==1">
							未到账
						</view>
						<view v-if="item.status==4">
							已到账
						</view>
						<view v-if="item.status==2">
							已拒绝
						</view>

					</view>
					<view class="main_content_3">
						<view class="main_content_3_1">
							{{item.withdraw_money}}
						</view>
						<view class="main_content_3_2">
							余额：{{item.leave_withdraw_money}}
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		distributionIndex,
		drawLog
	} from '@/api/index.js'
	export default {
		data() {
			return {
				info: {},
				display: false,
				bgColor: 'transparent',
				tabList: [{
					name: "全部"
				}, {
					name: "未到账"
				}, {
					name: "已到账"
				}, {
					name: "已拒绝"
				}],
				current: 0,
				page: 1,
				list: []
			};
		},
		onLoad() {
			this.distributionIndexApi()
			this.drawLog()
		},
		onReachBottom() {
			this.page++
			this.drawLog(1)
		},
		methods: {
			click(e) {
				this.current = e.index
				this.drawLog()
			},
			async distributionIndexApi(e) {
				let res = await distributionIndex()
				if (res.code == 1) {
					this.info = res.data
					this.display = true
				}
			},
			async drawLog(e) {
				if (e !== 1) {
					this.page = 1
				}
				let res = await drawLog({
					page: this.page,
					status: this.current
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
	.tabs {
		background-color: #fff;

		::v-deep .u-tabs__wrapper__nav__item {
			flex: 1 !important;
		}
	}

	.head {
		height: 400rpx;
		padding: 20rpx;
		box-sizing: border-box;
		background-color: #50ae5aff;
		text-align: center;
		color: #fff;
		padding-top: 160rpx;

		.head_price {
			font-size: 44rpx;

			span {
				font-size: 80rpx;
			}
		}
	}

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
		padding: 20rpx 0;
		box-sizing: border-box;
	}

	.content_time_left {
		display: flex;
		align-items: center;
		justify-content: space-around;
		width: 186rpx;
		height: 51rpx;
		/* background: #FFFFFF; */
		border-radius: 26rpx;
		margin-left: 10rpx;
	}

	.content_time_left_time {
		font-size: 24rpx;
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
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-right: 10rpx;
		background-color: #FFFFFF;
		border-radius: 35rpx;
		padding: 0 20rpx;
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

	.main_content {

		background: #FFFFFF;
		border-radius: 14rpx;
		margin-bottom: 20rpx;
	}

	.main_content_box {
		width: 90%;
		height: 143rpx;
		margin: 0 auto;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 20rpx;
		position: relative;
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
		text-align: center;
	}

	.main_content_3_1 {
		font-size: 34rpx;
		font-weight: 600;
		color: #010101;
	}

	.main_content_3_2 {
		font-size: 24rpx;
		font-weight: 400;
		color: #888B90;
	}
</style>