<template>
	<view class="content font_family">
		<view class="top_box f_z_b f_j">
			<view class="left">
				<view class="lj">
					账户余额
				</view>
				<view class="price">
					{{msg.money?msg.money:'0.00'}}元
				</view>
				<view class="time">
					数据截止{{cut_off}}
				</view>
			</view>
		</view>
		<!-- 查看收入切换 -->
		<view class="check_btn f">
			<view class="item" @click="check(index)" :class="{item_action:btn_action==index}"
				v-for="(item,index) in check_btn" :key="index">
				{{item.text}}
			</view>
		</view>
		<!-- 选择信息 -->
		<view class="check_msg f">
			<view class="price">
				<view class="title">
					收益金额
				</view>
				<view class="">
					{{income_price?income_price:'0.00'}}元
				</view>
			</view>
			<view class="date">
				<view class="title" v-if="btn_action==0 || btn_action==1">
					{{btn_action==0?'日期':'年份'}}
				</view>
				<view class="" @click="open" v-if="btn_action==0">
					<text>{{query_data.time}}</text>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/more.png" mode=""></image>
				</view>
				<view class="" v-if="btn_action==1" @click="year_open">
					<text>{{query_data.year}}</text>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/more.png" mode=""></image>
				</view>
			</view>
		</view>
		<!-- 月份收入展示 -->
		<!-- 头部星期 -->
		<view class="record">
			<view class="item" v-for="(item,index) in list" :key="index">
				<view class="top f_z_b">
					<view class="left">
						{{item.username}}购买
					</view>
					<view class="right">
						{{item.title}}
					</view>
				</view>
				<view class="bottom f_z_b">
					<view class="left">
						{{$u.timeFormat(item.createtime, 'yyyy-mm-dd hh:MM:ss')}}
					</view>
					<view class="right">
						￥{{item.money}}
					</view>
				</view>
			</view>
			<view class="empty_top" v-if="list.length==0">
				<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
				</u-empty>
			</view>
		</view>
		<!-- 日期选择器 -->
		<u-datetime-picker cancelColor="#999999" title='选择日期' @cancel='close' @close="close" @confirm="confirm"
			:closeOnClickOverlay='true' confirmColor="#FFE706" :show="date_show" :minDate='1704075012000'
			:defaultIndex='defaultIndex' v-model="month" mode="year-month"></u-datetime-picker>
		<!-- 年份选择器 -->
		<u-picker :show="year_show" :closeOnClickOverlay='true' cancelColor="#999999" @cancel='year_close'
			@close="year_close" @confirm="year_confirm" confirmColor="#FFE706" title='选择年份'
			:columns="columns"></u-picker>
	</view>
</template>

<script>
	import { guide_center, income_detail, withdraw_list } from '@/api/admissionm.js'
	export default {
		data() {
			return {
				cut_off: '',
				check_btn: [
					{ text: '月收入', type: 1 },
					{ text: '年收入', type: 2 },
					// { text: '提现记录', type: 3 },
				],
				btn_action: 0,
				weekday: [
					{ text: '日' },
					{ text: '一' },
					{ text: '二' },
					{ text: '三' },
					{ text: '四' },
					{ text: '五' },
					{ text: '六' },
				],
				zw_num: 0,
				month: '',
				year: '',
				date_show: false,
				year_show: false,
				defaultIndex: [],
				columns: [],
				query_data: {
					time: '',
					year: '',
					page: 1
				},
				msg: '',
				month_arr: [],
				year_arr: [],
				income_price: 0.00,
				withdraw_list_data: {
					page: 1
				},
				withdraw_lists: [],
				withdraw_list_nomore: false,

				// 收入列表
				list: []
			};
		},
		onReachBottom() {
			this.get_income_detail()
		},
		onShow() {
			// 获取当前时间戳
			var date = new Date();
			this.query_data.time = this.date_zh(date)
			this.query_data.year = this.date_zh(date, 'year')
			this.cut_off = this.date_zh(date, 'day')
			this.get_msg()


			this.list = []
			this.query_data.page = 1
			this.get_income_detail()
		},
		onReady() {},
		onLoad() {
			this.yearInit()
		},
		methods: {
			// 设置年份，最小2024年,最大当前年份
			yearInit() {
				var year = new Date().getFullYear();
				let arr = []
				this.yearadd(2024, year, arr)
				// console.log(arr)
				this.columns.push(arr)
			},
			yearadd(init, final, arr) {
				if (init < final) {
					arr.push(init)
					this.yearadd(init + 1, final, arr)
				} else {
					arr.push(init)
				}
			},
			go(url) {
				uni.navigateTo({
					url
				})
			},
			check(index) {
				this.btn_action = index
				if (index == 2) {
					this.withdraw_list_data.page = 1
					this.withdraw_lists = []
					this.get_withdraw_list()
				} else {
					this.list = []
					this.query_data.page = 1
					this.get_income_detail()
				}
			},
			confirm(e) {
				// console.log(e)
				this.date_show = false
				// 转换成日期格式
				this.query_data.time = this.date_zh(e.value)

				this.list = []
				this.query_data.page = 1
				this.get_income_detail()
			},
			year_confirm(e) {
				// console.log(e)
				this.query_data.year = e.value[0]
				this.year_show = false
			},
			open() {
				this.date_show = true
			},
			year_open() {
				this.year_show = true
			},
			close() {
				this.date_show = false
			},
			year_close() {
				this.year_show = false
			},
			// 时间格式转换
			date_zh(timestamp, type) {
				var date = new Date(timestamp);
				// 获取年份  
				var year = date.getFullYear();
				// 获取月份（从0开始，所以要+1）  
				var month = date.getMonth() + 1;
				// 如果月份小于10，则在前面添加一个'0'  
				month = month < 10 ? '0' + month : month;
				// 将年份和月份组合成字符串  
				var yearMonth = year + '-' + month;
				// console.log(yearMonth)
				// 获取日期（getDate返回的是一个月中的某一天，从1开始）  
				let day = date.getDate();
				// 如果日期小于10，则在前面补0  
				day = day < 10 ? '0' + day : day;
				day = year + '-' + month + '-' + day;
				if (type == 'year') {
					return year
				} else if (type == 'day') {
					return day
				} else {
					return yearMonth
				}
			},
			// 获取导游中心数据
			get_msg() {
				guide_center().then(res => {
					if (res.code == 1) {
						this.msg = res.data
					}
				})
			},
			// 获取收入明细
			get_income_detail() {
				if (this.btn_action == 0) {
					// 月收入
					let obj = {
						month: this.query_data.time.slice(5),
						year: this.query_data.time.slice(0, 4),
						page: this.query_data.page
					}
					this.get_detail(obj)
				} else if (this.btn_action == 1) {
					// 年收入
					let obj = {
						year: this.query_data.year,
						page: this.query_data.page
					}
					this.get_detail(obj)
				}
			},
			get_detail(data) {
				income_detail(data).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.list = [...this.list, ...res.data.data]
							this.query_data.page += 1
						}
					}
				})
			},
			// 获取提现记录
			get_withdraw_list() {
				withdraw_list(this.withdraw_list_data).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.withdraw_lists = [...this.withdraw_lists, ...res.data.data]
							this.withdraw_list_nomore = false
							this.withdraw_list_data.page += 1
						} else {
							this.withdraw_list_nomore = true
						}
					}
				})
			}
		}
	}
</script>

<style lang="scss">
	.content {
		padding: 30rpx;
		background-color: #FFFFFF;

		.top_box {
			background: #F7F8FA;
			border-radius: 18rpx 18rpx 18rpx 18rpx;
			padding: 40rpx;

			.left {
				.lj {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
				}

				.price {
					font-weight: 400;
					font-size: 60rpx;
					color: #3D3D3D;
				}

				.time {
					font-weight: 400;
					font-size: 24rpx;
					color: #999999;
					margin-top: 15rpx;
				}

				.tip {
					font-weight: 400;
					font-size: 24rpx;
					color: #999999;
					margin-top: 15rpx;
				}
			}

			.right {
				background: #232323;
				border-radius: 1246rpx 1246rpx 1246rpx 1246rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #F3F3F3;
				padding: 18rpx 64rpx;
			}
		}

		.check_btn {
			margin-top: 30rpx;

			.item {
				margin-right: 32rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #999999;
				background: #E4E4E4;
				border-radius: 18rpx 18rpx 18rpx 18rpx;
				padding: 16rpx 42rpx;
			}

			.item_action {
				background: #FFAE35 !important;
				color: #FFFFFF !important;
			}
		}

		.check_msg {
			margin-top: 30rpx;
			font-weight: 400;
			font-size: 24rpx;
			color: #3D3D3D;
			border-bottom: 1rpx solid #D8D8D8;
			padding-bottom: 20rpx;

			.price {
				.title {
					margin-bottom: 15rpx;
				}
			}

			.date {
				margin-left: 50rpx;

				.title {
					margin-bottom: 15rpx;
				}

				.icon {
					margin-left: 10rpx;
					width: 11rpx;
					height: 19rpx;
				}
			}
		}

		.rl_box {}

		.weekday {
			margin-top: 30rpx;

			.item {
				width: 82rpx;
				text-align: center;
				font-weight: 400;
				font-size: 28rpx;
				color: #3D3D3D;
				margin: 0rpx 9rpx;
			}
		}

		.date_box {
			flex-wrap: wrap;

			.zw_item {
				width: 82rpx;
				height: 106rpx;
				margin: 0rpx 9rpx;
				margin-top: 14rpx;
			}

			.item {
				width: 82rpx;
				height: 106rpx;
				background: #F8FAFB;
				border-radius: 4rpx 4rpx 4rpx 4rpx;
				margin: 0rpx 9rpx;
				margin-top: 14rpx;

				.top {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					margin-top: 10rpx;
				}

				.bottom {
					font-weight: 400;
					font-size: 24rpx;
					color: #A7A9AB;
					margin-top: 5rpx;
				}
			}
		}

		.year {
			flex-wrap: wrap;

			.item {
				width: 122rpx;
				height: 148rpx;
				background: #F8FAFB;
				border-radius: 8rpx 8rpx 8rpx 8rpx;
				margin: 0rpx 8rpx;
				margin-top: 20rpx;

				.top {
					font-weight: 400;
					font-size: 28rpx;
					color: #3D3D3D;
				}

				.bottom {
					font-weight: 400;
					font-size: 24rpx;
					color: #A7A9AB;
					margin-top: 20rpx;
				}
			}
		}

		.record {
			margin-top: 30rpx;

			.item {
				margin-bottom: 30rpx;
				// padding-bottom: 20rpx;
				// border-bottom: 1rpx solid #C3C6C9;

				.top {
					.left {
						font-weight: 600;
						font-size: 28rpx;
						color: #3D3D3D;
					}

					.right {
						font-weight: 400;
						font-size: 28rpx;
						color: #3D3D3D;
					}
				}

				.bottom {
					margin-top: 20rpx;

					.left {
						font-weight: 400;
						font-size: 24rpx;
						color: #999999;
					}

					.right {
						font-weight: 400;
						font-size: 28rpx;
						color: red;
					}
				}
			}
		}

		::v-deep .u-popup__content {
			border-radius: 40rpx 40rpx 0rpx 0rpx !important;
			overflow: hidden;
		}
	}
</style>