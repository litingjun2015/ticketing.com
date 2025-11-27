<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<!-- 选择菜单 -->
		<view class="tabs_menu f">
			<view class="item f_d f_j" v-for="(item,index) in tabs" :key="index" @click="tabs_check(index)">
				<view class="text">
					{{item.text}}
				</view>
				<view class="line" v-if="index==tabs_action">

				</view>
			</view>
		</view>
		<!-- 列表 -->
		<view class="list">
			<view class="item" v-for="(item,index) in list" :key="index"
				@click="go_set(`/pagesC/product/product?id=${item.id}&msg=${JSON.stringify(item.mp_multiplejson)}`)">
				<view class="top f">
					<view class="left">
						<image class="img" :src="item.image" mode="aspectFill"></image>
					</view>
					<view class="right f_d f_z_b">
						<view class="title text_ellipsis2">
							{{item.title}}
						</view>
						<view class="small_title">
							{{item.guarantee}}
						</view>
						<view class="tag_box f">
							<view class="tag_item" v-for="(items,indexs) in item.yd_multiplejson">
								{{items.intro}}
							</view>
						</view>
						<view class="f_z_b f_j">
							<view class="price ">
								<text>￥</text>
								<text>{{item.price}}</text>
								<text class="text">起</text>
							</view>
							<view class="num f_j">
								<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/view.png" mode="aspectFill"></image>
								<view class="num_text">
									浏览数{{item.view_nums}}
								</view>
							</view>
						</view>
					</view>
				</view>

				<view class="bottom f">
					<!-- <view class="btn btn1" @click.stop="" v-if="tabs_action==0 && item.status==1">
						审核中
					</view>
					<view class="btn btn1" @click.stop="up_down(2,item.id)" v-if="tabs_action==1 && item.status==4">
						上架
					</view>
					<view class="btn btn1" @click.stop="up_down(4,item.id)" v-if="tabs_action==0 && item.status==2">
						下架
					</view> -->
					<!-- <view class="btn btn2" @click.stop="open(item.id,item.price)">
						价格
					</view> -->
					<view class="btn btn3"
						@click.stop="go_set(`/pagesC/product/product?id=${item.id}&msg=${JSON.stringify(item.mp_multiplejson)}`)">
						编辑
					</view>
				</view>
			</view>
			<!-- 加载更多 -->
			<u-loadmore v-if="list.length>0" line @loadmore='get_list' :status="status" :loading-text="loadingText"
				:loadmore-text="loadmoreText" :nomore-text="nomoreText" />
		</view>
		<view class="empty_top" v-if="list.length==0">
			<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
			</u-empty>
		</view>
		<u-tabbar :value="value" inactiveColor='#242424' activeColor='#FFAE35' :placeholder="true" :border="false"
			@change="name => value = name" :fixed="true" :safeAreaInsetBottom="true">
			<u-tabbar-item v-for="(item,index) in tabbar_list" :key="index"
				:icon="value==index?item.action_icon:item.icon" :text="item.text" @click='change'></u-tabbar-item>
		</u-tabbar>
		<!-- 价格调整 -->
		<u-popup :show="show" @close="close" round='20'>
			<view class="pop_box f_d f_j">
				<text class="title">价格调整至</text>
				<view class="ipt f_j">
					<view class="">
						￥
					</view>
					<view class="ipt_box">
						<u--input :placeholder="placeholder" inputAlign='center' border="none"
							v-model="price_data.price"></u--input>
					</view>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
				</view>
				<view class="btn" @click="price_set">
					确认
				</view>
			</view>
		</u-popup>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { product_list, product_status, product_price } from '@/api/admissionm.js'
	import { mapState } from 'vuex'
	import mixin from '../mixin/tabbar.js'
	export default {
		mixins: [mixin],
		data() {
			return {
				show: false,
				value: 1,
				tabs_action: 0,
				tabs: [
					{ text: '产品', type: 1 },
					{ text: '已下架', type: 2 },
				],
				query_data: {
					type: 1,
					page: 1,
				},
				price_data: {
					id: '',
					price: ''
				},
				list: [],
				placeholder: '填写价格',
				price: '',
				status: 'loadmore',
				loadingText: '正在加载中',
				loadmoreText: '点击或上拉加载更多~',
				nomoreText: '没有更多啦~',
			};
		},
		computed: {
			...mapState(['menuButtonInfo'])
		},
		// onLoad() {
		// 	this.get_list()
		// },
		onShow() {
			this.list = []
			this.query_data.page = 1
			this.get_list()
		},
		onReachBottom() {
			if (this.status != 'nomore') {
				this.get_list()
			}
		},
		methods: {
			go_set(url) {
				uni.navigateTo({
					url
				})
			},
			close() {
				this.show = false
			},
			open(id, price) {
				this.show = true
				this.placeholder = price
				this.price_data.id = id
			},
			price_set() {
				if (!(this.price_data.price > 0)) {
					this.$refs.uToast.show({
						message: '输入正确的价格'
					})
					return
				}
				product_price(this.price_data).then(res => {
					if (res.code == 1) {
						this.$refs.uToast.show({
							message: '修改成功'
						})
						this.query_data.page = 1
						this.list = []
						this.get_list()
						this.show = false
					} else {
						this.$refs.uToast.show({
							message: res.msg
						})
					}
				})
			},
			tabs_check(index) {
				this.tabs_action = index
				this.query_data.type = this.tabs[index].type
				this.query_data.page = 1
				this.list = []
				this.get_list()
			},
			// 上下架 2上架4下架
			up_down(type, id) {
				let obj = {
					id,
					type
				}
				uni.showModal({
					title: '提示',
					content: `是否${type==2?'上架':'下架'}该商品`,
					success: (res) => {
						if (res.confirm) {
							product_status(obj).then(res => {
								console.log(res)
								if (res.code == 1) {
									this.query_data.page = 1
									this.list = []
									this.get_list()
								}
							})
						} else if (res.cancel) {

						}
					}
				})
			},
			go_detail(url) {
				uni.navigateTo({
					url
				})
			},
			// 获取列表
			get_list() {
				this.status = 'loading'
				product_list(this.query_data).then(res => {
					if (res.code == 1) {
						if (res.data.data.length > 0) {
							this.status = 'loadmore'
							this.list = [...this.list, ...res.data.data]
							this.query_data.page += 1
						} else {
							this.status = 'nomore'
						}
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 0rpx 30rpx;
		padding-bottom: 30rpx;

		.tabs_menu {
			padding-top: 30rpx;

			.item {
				padding: 0rpx 30rpx;

				.text {
					font-weight: 400;
					font-size: 32rpx;
					color: #242424;
					z-index: 100;
				}

				.line {
					margin-top: -10rpx;
					width: 80%;
					height: 10rpx;
					border-radius: 1000rpx;
					background-image: linear-gradient(to right, #FFFFFF 10%, #FFAE35);
				}
			}
		}

		.list {
			margin-top: 30rpx;

			.item {
				padding-bottom: 30rpx;
				margin-bottom: 30rpx;
				border-bottom: 1rpx solid #D8D8D8;
			}

			.top {
				.left {
					.img {
						width: 250rpx;
						height: 240rpx;
						border-radius: 12rpx 12rpx 12rpx 12rpx;
					}
				}

				.right {
					margin-left: 20rpx;
					flex: 1;

					.title {
						font-weight: 400;
						font-size: 32rpx;
						color: #343434;
					}

					.small_title {
						font-weight: 400;
						font-size: 24rpx;
						color: #666666;
					}

					.tag_box {
						.tag_item {
							margin-bottom: 10rpx;
							margin-right: 20rpx;
							background: rgba(255, 174, 53, 0.1);
							font-weight: 400;
							font-size: 20rpx;
							color: #FFAE35;
							padding: 10rpx;
						}
					}

					.price {
						font-weight: 400;
						font-size: 36rpx;
						color: #FF4815;

						.text {
							margin-left: 10rpx;
							font-size: 20rpx;
							color: #666666;
						}
					}
				}
			}

			.num {
				margin-top: 20rpx;

				.icon {
					width: 30rpx;
					height: 20rpx;
				}

				.num_text {
					margin-left: 10rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #696A6F;
				}
			}

			.bottom {
				margin-top: 20rpx;
				justify-content: flex-end;

				.btn {
					margin-left: 20rpx;
					padding: 16rpx 60rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #696A6F;
					border-radius: 956rpx 956rpx 956rpx 956rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #FFFFFF;
				}

				.btn1 {
					background: #E4E3E3;
					font-weight: 400;
					font-size: 24rpx;
					color: #999999;
				}

				.btn2 {
					background: #232323;
				}

				.btn3 {
					background: #FFAE35;
				}
			}
		}

		.pop_box {
			padding: 30rpx;

			.title {
				font-weight: 400;
				font-size: 36rpx;
				color: #3D3D3D;
			}

			.ipt {
				font-weight: 400;
				font-size: 36rpx;
				color: #3D3D3D;
				margin-top: 30rpx;

				.ipt_box {
					margin: 0rpx 15rpx;
					width: 150rpx;
				}

				.icon {
					width: 24rpx;
					height: 26rpx;
				}
			}

			.btn {
				font-weight: 400;
				font-size: 28rpx;
				color: #FFFFFF;
				background: #FFAE35;
				border-radius: 496rpx 496rpx 496rpx 496rpx;
				width: 100%;
				padding: 15rpx 0rpx;
				text-align: center;
				margin-top: 70rpx;
			}
		}
	}
</style>