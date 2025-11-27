<template>
	<view class="content font_family">
		<!-- <view class="header f_j" :style="{paddingTop:menuButtonInfo+'px'}">
			<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/back_b.png" mode="" @click="back"></image>
			<view class="search f_z_b">
				<view class="search_left f_zj">
					<view class="city">
						郑州
					</view>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/vector.png" mode=""></image>
					<view class="line">

					</view>
					<view>
						<image class="magnif" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/magnif.png" mode=""></image>
					</view>
				</view>
				<view class="search_right f_zj">
					<u--input placeholder="请输入内容" border="none" v-model="value" @change="change"></u--input>
				</view>
			</view>
		</view> -->
		<screen type='2' :data_list='data_list' @updata='updata' @query='query'></screen>
		<!-- 列表 -->
		<view class="list f_z_b" v-if="hotel_data_list.length>0">
			<view class="item" v-for="(item,index) in hotel_data_list" :key="index"
				@click="go('/pagesA/index_menu/hotels_detail?id='+item.id)">
				<view class="top">
					<image class="img" :src="item.image" mode="aspectFill"></image>
				</view>
				<view class="title text_ellipsis2">
					{{item.title}}
				</view>
				<view class="address">
					{{item.address}}
				</view>
			</view>
		</view>
		<view class="list" v-else>
			<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
		<!-- 加载更多 -->
		<view class="" v-if="hotel_data_list.length>0">
			<u-loadmore line @loadmore='get_hotels_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
	</view>
</template>

<script>
	import { brand_list, facility_list, level_list, type_list, hotel_list } from '@/api/index_menu/index.js'
	import screen from '@/components/t_screen.vue'
	export default {
		components: {
			screen
		},
		data() {
			return {
				data_list: {
					brand: [],
					facility: [],
					level: [],
					type_data: [],
				},
				// 请求参数
				query_data: {
					sort: 1,
					page: 1,
					search: '',
					type_id: [],
					facility_id: [],
					brand_id: [],
					level_id: [],
				},
				hotel_data_list: [],
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~'
			};
		},
		onLoad() {
			this.get_brand_list()
			this.get_facility_list()
			this.get_level_list()
			this.get_type_list()
			this.get_hotels_list()
		},
		onShareAppMessage() {

		},
		onShareTimeline() {

		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_hotels_list()
			}
		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			},
			// 更新数据
			updata(index, name) {
				this.data_list[name][index].action = !this.data_list[name][index].action
				this.$forceUpdate()
			},
			// 条件查询
			query(val) {
				console.log(val)
				this.query_data.page = 1
				this.hotel_data_list = []
				this.query_data = { ...this.query_data, ...val }
				this.get_hotels_list()
			},
			// 获取酒店筛选列表
			get_brand_list() {
				brand_list().then(res => {
					let arr = []
					res.data.forEach(item => {
						item.action = false
						arr.push(item)
					})
					this.data_list.brand = arr
				})
			},
			get_facility_list() {
				facility_list().then(res => {
					let arr = []
					res.data.forEach(item => {
						item.action = false
						arr.push(item)
					})
					this.data_list.facility = arr
				})
			},
			get_level_list() {
				level_list().then(res => {
					let arr = []
					res.data.forEach(item => {
						item.action = false
						arr.push(item)
					})
					this.data_list.level = arr
				})
			},
			get_type_list() {
				type_list().then(res => {
					let arr = []
					res.data.forEach(item => {
						item.action = false
						arr.push(item)
					})
					this.data_list.type_data = arr
				})
			},
			// 获取酒店列表
			get_hotels_list() {
				hotel_list(this.query_data).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.hotel_data_list = [...this.hotel_data_list, ...res.data.data]
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
		padding-top: 270rpx;
		padding-bottom: 30rpx;

		.header {
			padding-left: 30rpx;
			background-color: white;

			.icon {
				width: 18rpx;
				height: 36rpx;
			}

			.search {
				margin-left: 40rpx;
				align-items: center;
				width: 400rpx;
				height: 68rpx;
				background: #F5F6F8;
				border-radius: 80rpx 80rpx 80rpx 80rpx;
				padding: 0rpx 26rpx;

				.search_left {
					.icon {
						width: 18rpx;
						height: 10rpx;
						margin-left: 10rpx;
					}

					.line {
						border-right: 1rpx solid #666666;
						height: 34rpx;
						margin-left: 20rpx;
					}

					.magnif {
						width: 30rpx;
						height: 30rpx;
						margin-left: 20rpx;
						vertical-align: baseline;
					}
				}

				.search_right {
					margin-left: 10rpx;
					flex: 1;
					height: 42rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #232323;
					line-height: 33rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}
		}

		.list_box {
			height: 85vh;
			// border: 1rpx solid red;
			box-sizing: border-box;

		}

		.list {
			padding: 0rpx 30rpx;
			flex-wrap: wrap;
			padding-bottom: 0rpx;
			// padding-bottom: 40rpx;

			.item {
				width: 327rpx;
				background: #FFFFFF;
				border-radius: 10rpx 10rpx 10rpx 10rpx;
				overflow: hidden;
				margin-bottom: 28rpx;
				padding-bottom: 10rpx;

				.top {
					.img {
						width: 100%;
						height: 213rpx;
					}
				}

				.title {
					font-weight: 700;
					font-size: 26rpx;
					color: #343434;
					line-height: 31rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					padding: 0rpx 14rpx;
					margin-top: 14rpx;
				}

				.address {
					font-weight: 400;
					font-size: 18rpx;
					color: #666666;
					font-style: normal;
					text-transform: none;
					padding: 0rpx 14rpx;
					margin-top: 14rpx;
				}
			}
		}
	}
</style>