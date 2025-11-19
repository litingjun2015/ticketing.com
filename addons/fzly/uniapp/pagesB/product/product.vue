<template>
	<view class="content font_family">
		<view class="title">
			选择您的产品基本信息
		</view>
		<view class="msg_box">
			<view class="item f_j f_z_b p_title">
				<view class="left">
					标题
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' placeholder="请输入内容" border="none"
						v-model="query_data.title"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					产品类型
				</view>
				<view class="type f_j" @click="type_show=true">
					<view class="">
						{{type_text?type_text:'请选择'}}
					</view>
					<image class="icon" src="../../static/index/more.png" mode=""></image>
				</view>
			</view>
			<!-- 车型选择 -->
			<view class="item f_j f_z_b p_title" v-if="type_text=='包车游'">
				<view class="left">
					车型
				</view>
				<view class="check f_j">
					<view class="item" :class="[item.action?'check_action':'check_item'] "
						v-for="(item,index) in car_arr" @click="car_check(item)">
						{{item.title}}
					</view>
				</view>
			</view>
			<!-- 时间选择 -->
			<view class="item f_j f_z_b p_title" v-if="type_text=='景区讲解'">
				<view class="left">
					时长
				</view>
				<view class="check f_j">
					<view class="item" :class="[item.action?'check_action':'check_item']"
						v-for="(item,index) in time_arr" @click="time_check(item)">
						{{item.title}}
					</view>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					价格
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' placeholder="请输入价格" border="none"
						v-model="query_data.price"></u--input>
				</view>
			</view>
			<!-- 景区所在城市 -->
			<!-- <view class="item f_j f_z_b p_title">
				<view class="left">
					景区所在城市
				</view>
				<view class="type f_j" @click="go('/pagesA/set/address?type=check')">
					<view class="">
						{{query_data.city?query_data.city:'请选择'}}
					</view>
					<image class="icon" src="../../static/index/more.png" mode=""></image>
				</view>
			</view> -->
			<!-- 景区详细地址 -->
			<view class="address">
				<view class="text">
					景区详细地点
				</view>
				<view class="">
					<u--textarea v-model="query_data.address" border="none" placeholder="请输入详细地点"></u--textarea>
				</view>
			</view>
			<!-- 证明 -->
			<view class="address">
				<view class="text">
					景区合作证明
				</view>
				<view class="zm_box" @click="add_img(1)">
					<image v-if="!query_data.zm_image" class="zm_img" src="../../static/business/zm.png"
						mode="aspectFill">
					</image>
					<image v-else class="zm_img" :src="query_data.zm_image" mode="aspectFill">
					</image>
				</view>
			</view>
			<!-- 封面 -->
			<view class="address">
				<view class="text">
					上传封面
				</view>
				<view class="fm_box f_zj" @click="add_img(2)">
					<view class="add f_j" v-if="!query_data.image">
						<image class="icon" src="../../static/public/add_cover.png" mode=""></image>
						<text class="text">添加封面</text>
					</view>
					<image class="fm" v-else :src="query_data.image" mode="aspectFill"></image>
				</view>
			</view>
			<!-- 景区图文介绍 -->
			<view class="address">
				<view class="text">
					景区图文介绍
				</view>
				<view class="">
					<editorme ref="ed" @uploadFile='uploadFile' type='2' placeholder="请填写真实的景区图文介绍"></editorme>
				</view>
			</view>
			<!-- 按钮 -->
			<view class="btn f_zj" @click="submit">
				提交
			</view>
		</view>
		<!-- 选择器 -->
		<u-picker :show="type_show" keyName='title' cancelColor="#999999" @close="type_close" @cancel='type_show=false'
			@confirm="type_confirm" confirmColor="#FFAE35" title='选择类型' :columns="type_arr"></u-picker>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapState } from 'vuex'
	import editorme from '../../components/Rboy-editor/Rboy-editor.vue'
	import { product_type, product_car, product_time, add_product } from '@/api/user.js'
	import { guide_info } from '@/api/index_menu/index.js'
	export default {
		components: {
			editorme
		},
		computed: {
			...mapState(['jq_city'])
		},
		data() {
			return {
				type_arr: [],
				car_arr: [],
				time_arr: [],
				type_show: false,
				query_data: {
					id: '',
					image: '',
					title: '',
					type_id: '',
					model: [],
					time: [],
					address: '',
					zm_image: '',
					content: '',
					price: '',
					// city: ''
				},
				type_text: '',
				query: {
					id: ''
				},
			};
		},
		// watch: {
		// 	jq_city() {
		// 		this.query_data.city = this.jq_city
		// 	}
		// },
		onLoad(e) {
			if (e.id) {
				// 编辑数据回显
				this.query.id = e.id
				this.query_data.id = e.id
				this.get_detail()
			} else {
				this.get_type()
				this.get_car()
				this.get_time()
			}
		},
		onReady() {
			// console.log(this.$refs.ed)

		},
		methods: {
			// go(url) {
			// 	uni.navigateTo({
			// 		url
			// 	})
			// },
			// 设置内容
			async set_content() {
				this.$refs.ed.editor_setContents(this.query_data.content)
			},
			// 获取产品详情
			get_detail() {
				guide_info(this.query).then(res => {
					if (res.code == 1) {
						// 数据回显
						this.query_data.title = res.data.title
						this.query_data.image = res.data.image
						this.query_data.type_id = res.data.type_id //产品类型
						this.type_text = res.data.type_name //产品名称
						this.query_data.model = res.data.model_id
						this.query_data.time = res.data.duration_id
						this.query_data.address = res.data.jq_title
						this.query_data.zm_image = res.data.zm_image
						this.query_data.content = res.data.tw_content
						this.query_data.price = res.data.price
						this.set_content()
						// 循环选中时长车型 请求数据，保持同步
						if (res.data.type_name == '包车游') {
							product_car().then(res_car => {
								if (res_car.code == 1) {
									let arr = []
									res_car.data.forEach(item => {
										item.action = false
										arr.push(item)
									})
									this.car_arr = arr
									this.car_arr.forEach(item => {
										if (res.data.model_id.includes(item.id.toString())) {
											item.action = true
										}
									})
								}
							})
						} else {
							product_time().then(res_time => {
								if (res_time.code == 1) {
									let arr = []
									res_time.data.forEach(item => {
										item.action = false
										arr.push(item)
									})
									this.time_arr = arr
									this.time_arr.forEach(item => {
										if (res.data.duration_id.includes(item.id.toString())) {
											console.log('选中')
											item.action = true
										}
									})
								}
							})
						}
						this.$forceUpdate()
					}
				})
			},
			add_img(type) {
				uni.chooseImage({
					count: 1,
					success: (res) => {
						console.log(res)
						this.upload_img(res.tempFilePaths[0]).then(res => {
							if (res.code == 1) {
								if (type == 1) {
									this.query_data.zm_image = res.data.fullurl
								} else {
									this.query_data.image = res.data.fullurl
								}
							}
						})
					}
				})
			},
			uploadFile(res) {
				console.log(res)
				this.$refs.ed.editor_insertImage({
					src: res,
					alt: "图像",
				})
			},
			// 获取发布类型
			get_type() {
				product_type().then(res => {
					if (res.code == 1) {
						this.type_arr.push(res.data)
					}
				})
			},
			// 获取车型
			get_car() {
				product_car().then(res => {
					if (res.code == 1) {
						let arr = []
						res.data.forEach(item => {
							item.action = false
							arr.push(item)
						})
						this.car_arr = arr
					}
				})
			},
			// 获取时间
			get_time() {
				product_time().then(res => {
					if (res.code == 1) {
						let arr = []
						res.data.forEach(item => {
							item.action = false
							arr.push(item)
						})
						this.time_arr = arr
					}
				})
			},
			type_close() {
				this.type_show = false
			},
			type_confirm(e) {
				// console.log(e);
				this.type_text = e.value[0].title
				this.query_data.type_id = e.value[0].id
				this.type_show = false
			},
			car_check(item) {
				item.action = !item.action
				let arr = []
				this.car_arr.forEach(item => {
					if (item.action) {
						arr.push(item.id)
					}
				})
				this.query_data.model = arr
			},
			time_check(item) {
				item.action = !item.action
				let arr = []
				this.time_arr.forEach(item => {
					if (item.action) {
						arr.push(item.id)
					}
				})
				this.query_data.time = arr
			},
			// 获取富文本编辑器内容
			async get_content() {
				let detail = await this.$refs.ed.editor_getcontents()
				// console.log(detail)
				this.query_data.content = detail
			},
			submit() {
				this.get_content()
				setTimeout(() => {
					if (this.check_product_kong(this.query_data, this.type_text)) {
						return
					} else {
						// 去掉图片域名
						this.query_data.image = this.removeDomainFromUrl(this.query_data.image)
						this.query_data.zm_image = this.removeDomainFromUrl(this.query_data.zm_image)
						add_product(this.query_data).then(res => {
							console.log(res)
							if (res.code == 1) {
								let msg = ''
								if (this.query_data.id) {
									msg = '编辑成功'
								} else {
									msg = '发布成功，等待审核'
								}
								this.$refs.uToast.show({
									type: 'success',
									message: msg,
									duration: 1000,
									complete: () => {
										uni.navigateTo({
											url: '/pagesB/release/release'
										})
									}
								})
							} else {
								this.$refs.uToast.show({
									type: 'error',
									message: '发布失败',
									duration: 1000,
									complete: () => {
										uni.navigateBack({
											delta: 1
										})
									}
								})
							}
						})
					}
				}, 100)
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;

		.title {
			font-weight: 500;
			font-size: 32rpx;
			color: #242424;
		}

		.msg_box {
			margin-top: 20rpx;
			padding: 20rpx;
			background: #FFFFFF;
			border-radius: 15rpx 15rpx 15rpx 15rpx;

			.item {
				padding: 20rpx 0rpx;
				border-bottom: 1rpx solid #F0F0F0;

				.left {
					font-weight: 500;
					font-size: 28rpx;
					color: #242424;
				}

				.type {
					.icon {
						margin-left: 15rpx;
						width: 11rpx;
						height: 19rpx;
					}
				}

				.check {
					// border: 1rpx solid red;
					width: 500rpx;
					flex-wrap: wrap;
					justify-content: flex-end;

					.item {
						margin-bottom: 10rpx;
						margin-left: 8rpx;
					}

					.check_item {
						padding: 8rpx 14rpx;
						background: #F8F8F8;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #3D3D3D;
					}

					.check_action {
						padding: 8rpx 14rpx;
						background: #E9F1F9;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						font-weight: 400;
						font-size: 24rpx;
						color: #237DCB;
					}
				}
			}

			.address {
				padding: 20rpx 0rpx;

				.text {
					margin-bottom: 10rpx;
					font-weight: 500;
					font-size: 28rpx;
					color: #242424;
				}

				::v-deep .u-textarea {
					padding: 0rpx !important;
				}

				.zm_box {
					width: 100%;
					height: 234rpx;
					border: 2rpx dashed #E0E0E0;

					.zm_img {
						width: 100%;
						height: 100%;
					}
				}

				.fm_box {
					width: 100%;
					height: 234rpx;
					border-radius: 12rpx 12rpx 12rpx 12rpx;
					border: 2rpx dashed #E0E0E0;
					background: #F9F9F9;
					overflow: hidden;

					.add {
						.icon {
							width: 24rpx;
							height: 24rpx;
						}

						.text {
							margin-left: 15rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #999999;
						}
					}

					.fm {
						width: 100%;
						height: 100%;
					}
				}
			}

			.btn {
				width: 100%;
				height: 84rpx;
				background: #FFAE35;
				border-radius: 16rpx 16rpx 16rpx 16rpx;
				margin-top: 20rpx;
				color: #FFFFFF;
			}
		}

		::v-deep .u-popup__content {
			border-radius: 40rpx 40rpx 0rpx 0rpx !important;
			overflow: hidden;
		}
	}
</style>