<template>
	<view class="">
		<!-- <view class="" :style="{height:placeholder_view+menuButtonInfo*2+'rpx'}">

		</view> -->
		<view class="t_screen_content  font_family" @touchmove.stop.prevent="">
			<div :style="{height:menuButtonInfo+'px'}"></div>
			<!-- :style="{paddingTop:menuButtonInfo+'px'}" -->
			<view class="header f_j" @touchmove.stop.prevent="">
				<image class="icon" src="../static/public/back_b.png" mode="" @click="back"></image>
				<view class="search f_z_b">
					<view class="search_left f_zj">
						<view class="city" @click.stop="go('/pagesA/set/address?type=query')">
							{{city}}
						</view>
						<image class="icon" @click.stop="go('/pagesA/set/address?type=query')"
							src="../static/index/vector.png" mode=""></image>
						<view class="line">

						</view>
						<view>
							<image class="magnif" src="../static/index/magnif.png" mode=""></image>
						</view>
					</view>
					<view class="search_right f_zj">
						<u--input placeholder="请输入内容" border="none" v-model="search" @confirm='mask_close'></u--input>
					</view>
				</view>
			</view>
			<view class="header_bottom">
				<view class="f_j" :class="{f_z_b:type==2}">
					<view class="screen_box f_j" :class="{box2:type==1}" v-for="(item,index) in screen_title"
						:key="index">
						<view class="title" :style="{color:title_index===index?'#FFE706':''}"
							@click="title_check(index,item.type)">
							{{item.title}}
						</view>
						<image v-if='title_index===index' class="img" src="../static/public/screen_up.png" mode="">
						</image>
						<image v-else class="img" src="../static/public/screen_down.png" mode=""></image>
					</view>
				</view>
				<!-- 下拉框 -->
				<view class="drop_down" @touchmove.stop.prevent="" v-if="show">
					<!-- 排序框 -->
					<scroll-view scroll-y="true" class="down_box" v-if="type_show==1">
						<view class="down_item" v-for="(item,index) in sort" :key="index" @click="sort_check(index)"
							:style="{color:sort_action===index?'#FFE706':''}">
							{{item.text}}
						</view>
					</scroll-view>
					<!-- 分类选择框 -->
					<view class="category" v-if="type_show==2">
						<scroll-view scroll-y="true" class="category_box">
							<view class="title">
								门票类型
							</view>
							<view class="item f">
								<view :class="[item.action?'item_action':'item_check']" class="f_zj"
									v-for="(item,index) in ticket_type" :key="index"
									@click="category_click(index,item)">
									{{item.title}}
								</view>
							</view>
						</scroll-view>
						<!-- 确认按钮 -->
						<view class="confirm f_zj" @click="mask_close">
							确认
						</view>
					</view>
					<!-- 品牌 -->
					<view class="category" v-if="type_show==3">
						<scroll-view scroll-y="true" class="category_box">
							<view class="item f">
								<view :class="[item.action?'item_action':'item_check']" class="f_zj"
									v-for="(item,index) in data_list.brand" :key="index" @click="brand_click(index)">
									{{item.title}}
								</view>
							</view>
						</scroll-view>
						<!-- 确认按钮 -->
						<view class="confirm f_zj" @click="mask_close">
							查询
						</view>
					</view>
					<!-- 档次 -->
					<view class="category" v-if="type_show==4">
						<scroll-view scroll-y="true" class="category_box">
							<view class="item f">
								<view :class="[item.action?'item_action':'item_check']" class="f_zj"
									v-for="(item,index) in data_list.level" :key="index" @click="level_click(index)">
									{{item.title}}
								</view>
							</view>
						</scroll-view>
						<!-- 确认按钮 -->
						<view class="confirm f_zj" @click="mask_close">
							查询
						</view>
					</view>
					<!-- 筛选 -->
					<view class="category" v-if="type_show==5">
						<scroll-view scroll-y="true" class="category_box">
							<view style="margin-bottom: 20rpx;">
								<view class="title">
									住宿类型
								</view>
								<view class="item f">
									<view :class="[item.action?'item_action':'item_check']" class="f_zj"
										v-for="(item,index) in data_list.type_data" :key="index"
										@click="type_data_click(index)">
										{{item.title}}
									</view>
								</view>
							</view>
							<view style="margin-bottom: 20rpx;">
								<view class="title">
									热门设施
								</view>
								<view class="item f">
									<view :class="[item.action?'item_action':'item_check']" class="f_zj"
										v-for="(item,index) in data_list.facility" :key="index"
										@click="facility_click(index)">
										{{item.title}}
									</view>
								</view>
							</view>
						</scroll-view>
						<!-- 确认按钮 -->
						<view class="confirm f_zj" @click="mask_close">
							查询
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="mask" @touchmove.stop.prevent="" @click="mask_close" v-if="show">
		</view>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	export default {
		props: ['type', 'ticket_type', 'data_list', 'keyname'],
		name: "t_screen",
		computed: {
			...mapState(['city'])
		},
		data() {
			return {
				top_height: 200,
				menuButtonInfo: 0,
				show: false,
				// 当前激活的title
				title_index: '综合排序',
				screen_title: [],
				sort: [
					{ text: '综合排序' },
					{ text: '价格最低' },
					{ text: '价格最高' },
					{ text: '距离最近' }
				],
				sort_action: '',
				// 类型确认
				category_action: '',
				// 当前展示的筛选类型
				type_show: '',
				// 品牌
				brand_action: '',
				// 筛选
				screen_action: '',
				search: '',
				placeholder_view: '',
			};
		},
		watch: {
			city() {
				let obj = {}
				if (this.type == 1) {
					let arr = []
					this.ticket_type.forEach(item => {
						if (item.action) {
							arr.push(item.id)
						}
					})
					obj.sort = (this.sort_action + 1) * 1
					obj.type_id = arr
					obj.search = this.search
				} else if (this.type == 2) {
					obj.sort = (this.sort_action + 1) * 1
					obj.search = this.search
					this.get_hotel_type(obj)
				}
				this.$emit('query', obj)
			},
		},
		mounted() {
			if (this.keyname) {
				this.search = this.keyname
			}
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			this.$nextTick(() => {
				uni.createSelectorQuery().in(this).select('.t_screen_content').boundingClientRect(rect => {
					console.log(rect.height, '高度'); // 输出元素的高度  
					this.placeholder_view = rect.height * 2
				}).exec();
			});
			if (this.type == 1) {
				this.screen_title = [
					{ title: '综合排序', type: 1 },
					{ title: '全部分类', type: 2 }
				]
			} else {
				this.screen_title = [
					{ title: '综合排序', type: 1 },
					{ title: '品牌', type: 3 },
					{ title: '档次', type: 4 },
					{ title: '筛选', type: 5 },
				]
			}
		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			},
			back() {
				uni.navigateBack({
					delta: 1
				})
			},
			title_check(index, type) {
				this.show = true
				this.title_index = index
				this.type_show = type
			},
			sort_check(index) {
				this.sort_action = index
				// 修改title
				this.screen_title[this.title_index].title = this.sort[index].text
			},
			//类型选择
			category_click(index, item) {
				this.category_action = index
				// console.log(item)
				// 通知父组件修改数据
				this.$emit('updata', index)
			},
			// 品牌选择
			brand_click(index) {
				this.brand_action = index
				this.$emit('updata', index, 'brand')
			},
			// 档次选择
			level_click(index) {
				this.$emit('updata', index, 'level')
			},
			// 住宿类型
			type_data_click(index) {
				// this.screen_action = index
				this.$emit('updata', index, 'type_data')
			},
			// 热门设施
			facility_click(index) {
				// this.screen_action = index
				this.$emit('updata', index, 'facility')
			},
			mask_close() {
				// 关闭组件，同时让父组件发送请求
				this.title_index = ''
				this.show = false
				let obj = {}
				if (this.type == 1) {
					let arr = []
					this.ticket_type.forEach(item => {
						if (item.action) {
							arr.push(item.id)
						}
					})
					obj.sort = (this.sort_action + 1) * 1
					obj.type_id = arr
					obj.search = this.search
				} else if (this.type == 2) {
					obj.sort = (this.sort_action + 1) * 1
					obj.search = this.search
					this.get_hotel_type(obj)
				}
				this.$emit('query', obj)
			},
			// 组件关闭时整合选择的选项
			get_hotel_type(obj) {
				let arr1 = []
				let arr2 = []
				let arr3 = []
				let arr4 = []
				this.data_list.brand.forEach(item => {
					if (item.action) {
						arr1.push(item.id)
					}
				})
				this.data_list.facility.forEach(item => {
					if (item.action) {
						arr2.push(item.id)
					}
				})
				this.data_list.level.forEach(item => {
					if (item.action) {
						arr3.push(item.id)
					}
				})
				this.data_list.type_data.forEach(item => {
					if (item.action) {
						arr4.push(item.id)
					}
				})
				obj.brand_id = arr1
				obj.facility_id = arr2
				obj.level_id = arr3
				obj.type_id = arr4
			},
		}
	}
</script>

<style lang="scss" scoped>
	.t_screen_content {
		width: 100%;
		position: fixed;
		// padding: 42rpx;
		top: 0rpx;
		// padding-bottom: 20rpx;
		background-color: white;
		z-index: 9999999;

		.header {
			box-sizing: border-box;
			width: 100%;
			padding-left: 30rpx;
			background-color: white;
			// position: fixed;
			// top: 0;
			// left: 0;
			// z-index: 999999;

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

		.header_bottom {
			box-sizing: border-box;
			padding: 30rpx 40rpx;
		}

		.box2 {
			margin-right: 50rpx !important;
		}

		.screen_box {
			// width: 100%;
			// position: absolute;
			margin-right: 30rpx;

			.title {
				font-weight: 400;
				font-size: 30rpx;
				color: #353535;
				line-height: 36rpx;
				text-align: left;
				font-style: normal;
				text-transform: none;
			}

			.img {
				margin-left: 10rpx;
				width: 12rpx;
				height: 6rpx;
			}
		}

		.drop_down {
			margin-top: 40rpx;
			// position: fixed;
			// top: 245rpx;
			// left: 0;
			z-index: 9999;

			.down_box {
				background-color: white;
				width: 100vw;
				height: 404rpx;
				padding: 30rpx 0rpx;
				padding-top: 0rpx;
				box-sizing: border-box;

				.down_item {
					margin-bottom: 40rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #353535;
					line-height: 34rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}

			.category {
				background-color: white;
				// width: 100vw;
				height: 404rpx;
				padding: 30rpx 0rpx;
				padding-top: 0rpx;
				box-sizing: border-box;

				.category_box {
					height: 270rpx;
					box-sizing: border-box;
					// border: 1rpx solid red;

					.title {}

					.item {
						flex-wrap: wrap;

						.item_check {
							margin-top: 30rpx;
							margin-right: 30rpx;
							padding: 12rpx 26rpx;
							border: 2rpx solid #D8D8D8;
							border-radius: 8rpx 8rpx 8rpx 8rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #232323;
							line-height: 34rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.item_action {
							margin-top: 30rpx;
							margin-right: 30rpx;
							padding: 12rpx 26rpx;
							background: #FFE706;
							border: 2rpx solid #FFE706;
							border-radius: 8rpx 8rpx 8rpx 8rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #232323;
							line-height: 34rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}
					}
				}

				.confirm {
					width: 100%;
					height: 84rpx;
					background: #FFE706;
					border-radius: 16rpx 16rpx 16rpx 16rpx;
					font-weight: 400;
					font-size: 28rpx;
					color: #232323;
					line-height: 40rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
					margin-top: 25rpx;
				}
			}
		}
	}

	.mask {
		width: 100vw;
		height: 100vh;
		position: fixed;
		top: 0;
		left: 0;
		background-color: rgba(0, 0, 0, 0.2);
		z-index: 100;
	}
</style>