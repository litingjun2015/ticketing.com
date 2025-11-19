<template>
	<view class="content font_family">
		<view class="title">
			选择您的门票基本信息
		</view>
		<view class="msg_box">
			<view class="item f_j f_z_b p_title">
				<view class="left">
					门票分类
				</view>
				<view class="ipt_box f_j">
					<text class="sel-text">请选择</text>
					<u-icon name="arrow-right" size="20rpx"></u-icon>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					门票类型
				</view>
				<view class="ipt_box f_j">
					<text class="sel-text">请选择</text>
					<u-icon name="arrow-right" size="20rpx"></u-icon>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					门票名称
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' placeholder="请输入内容" border="none"
						v-model="query_data.title"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					标志
				</view>
				<view class="ipt_box f_j">
					<text class="sel-text">请选择</text>
					<u-icon name="arrow-right" size="20rpx"></u-icon>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					城市
				</view>
				<picker mode="region">
					<view class="ipt_box f_j">
						<text class="sel-text">请选择</text>
						<u-icon name="arrow-right" size="20rpx"></u-icon>
					</view>
				</picker>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					地址
				</view>
				<view class="ipt_box f_j">
					<text class="sel-text">请选择</text>
					<u-icon name="arrow-right" size="20rpx"></u-icon>
				</view>
			</view>
			<!-- 景区详细地址 -->
			<view class="address">
				<view class="text">
					详细地址
				</view>
				<view class="">
					<u--textarea v-model="query_data.address" border="none" placeholder="请输入详细地址"></u--textarea>
				</view>
			</view>
			<!-- 封面 -->
			<view class="address">
				<view class="text">
					缩略图
				</view>
				<view class="fm_box f_zj" @click="add_img(2)">
					<view class="add f_j" v-if="!query_data.image">
						<image class="icon" src="../../static/public/add_cover.png" mode=""></image>
						<text class="text">添加封面</text>
					</view>
					<image class="fm" v-else :src="query_data.image" mode="aspectFill"></image>
				</view>
			</view>
			<!-- 详情图 -->
			<view class="address">
				<view class="text">
					详情图
				</view>
				<u-upload :fileList="fileList1" @afterRead="afterRead" @delete="deletePic" name="1" multiple
					:maxCount="10"></u-upload>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					标签
				</view>
				<view class="ipt_box f_j">
					<text class="sel-text">请选择</text>
					<u-icon name="arrow-right" size="20rpx"></u-icon>
				</view>
			</view>
			<view class="address item">
				<view class="text">
					简介
				</view>
				<u--textarea v-model="query_data.content" placeholder="请输入内容" border='none'></u--textarea>
			</view>
			<view class="address item">
				<view class="text">
					景区简述
				</view>
				<u--textarea v-model="query_data.desc" placeholder="请输入内容" border='none'></u--textarea>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					热度
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' type="number" placeholder="请输入内容" border="none"
						v-model="query_data.title"></u--input>
				</view>
			</view>
			<view class="address item">
				<view class="text f_z_b f_j">
					<view class="">
						预定
					</view>
					<view class="add-item f_j">
						<u-icon name="edit-pen-fill" color="#FFFFFF" size="20rpx"></u-icon>
						<text style="margin-left: 10rpx;">添加</text>
					</view>
				</view>
				<!-- 追加表格 -->
				<view class="add-table">
					<view class="header f b_b">
						<view class="icon b_r">
							图标
						</view>
						<view class="des b_r">
							介绍
						</view>
						<view class="cz">
							操作
						</view>
					</view>
					<view class="t-body f" :class="{b_b:index+1!=3}" v-for="(item,index) in 3" :key="index">
						<view class="icon b_r">
							<view class="icon_upload f_zj">
								<u-icon v-if="!item.icon" name="plus" size="20rpx" color="#F0F4FF"></u-icon>
								<image v-else :src="item.icon" mode="aspectFill"></image>
							</view>
						</view>
						<view class="des b_r">
							<u-input placeholder="介绍"></u-input>
						</view>
						<view class="cz f_zj">
							<view class="del_btn f_zj">
								<u-icon name="trash-fill" size="28rpx" color="#FFFFFF"></u-icon>
							</view>
						</view>
					</view>
				</view>
			</view>
			<!-- 保障 -->
			<view class="item f_j f_z_b p_title">
				<view class="left">
					保障
				</view>
				<view class="ipt_box">
					<u--input inputAlign='right' placeholder="请输入保障" border="none"
						v-model="query_data.title"></u--input>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					开启时间
				</view>
				<view class="ipt_box f_j">
					<text class="sel-text">请选择</text>
					<u-icon name="arrow-right" size="20rpx"></u-icon>
				</view>
			</view>
			<view class="item f_j f_z_b p_title">
				<view class="left">
					关闭时间
				</view>
				<view class="ipt_box f_j">
					<text class="sel-text">请选择</text>
					<u-icon name="arrow-right" size="20rpx"></u-icon>
				</view>
			</view>
			<!-- 添加门票 -->
			<view class="address item">
				<view class="text f_z_b f_j">
					<view class="">
						门票
					</view>
					<view class="add-item f_j">
						<u-icon name="edit-pen-fill" color="#FFFFFF" size="20rpx"></u-icon>
						<text style="margin-left: 10rpx;">添加</text>
					</view>
				</view>
				<!-- 追加表格 -->
				<scroll-view scroll-x="true" class="scroll_table">
					<view class="add-table nowrap">
						<view class="header nowrap  b_b ">
							<view class="t_title b_r mp_item">
								名称
							</view>
							<view class="tag b_r mp_item">
								标签
							</view>
							<view class="j_shu b_r mp_item">
								简述
							</view>
							<view class="ly b_r mp_item">
								来源
							</view>
							<view class="jg b_r mp_item">
								价格
							</view>
							<view class="line_jg b_r mp_item">
								划线价格
							</view>
							<view class="ys b_r mp_item">
								已售
							</view>
							<view class="tj b_r mp_item">
								是否特价
							</view>
							<view class="cz mp_item">
								操作
							</view>
						</view>
						<view class="t-body nowrap" :class="{b_b:index+1!=6}" v-for="(item,index) in 6" :key="index">
							<view class="t_title b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="tag b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="j_shu b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="ly b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="jg b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="line_jg b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="ys b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="tj b_r mp_item">
								<u-input></u-input>
							</view>
							<view class="cz f_zj mp_item">
								<view class="del_btn f_zj">
									<u-icon name="trash-fill" size="28rpx" color="#FFFFFF"></u-icon>
								</view>
							</view>
						</view>
					</view>
				</scroll-view>
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
					type_id: '',
					type_ids: '',
					title: '',
					flag: '',
					city: '',
					image: '',
					images: [],
					tags: '',
					address: '',
					address_xx: '',
					lon: '',
					lat: '',
					content: '',
					desc: '',
					guarantee: '',
					open_times: '',
					end_times: '',
					yd_multiplejson: [],
					mp_multiplejson: [],
				},
				type_text: '',
				query: {
					id: ''
				},
				// 详情图
				fileList1
			};
		},
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

		},
		methods: {
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

				.sel-text {
					margin-right: 10rpx;
				}

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

					.add-item {
						color: #FFFFFF;
						font-size: 24rpx;
						padding: 10rpx 20rpx;
						background-color: #FFAE35;
						border-radius: 500rpx;
					}
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

				.scroll_table {
					height: auto;
					margin-bottom: 20rpx;
				}

				.add-table {
					margin-top: 20rpx;
					border: 1rpx solid #F0F4FF;

					.header {
						background-color: #F8F9FC;
					}

					.t-body {}

					.b_r {
						border-right: 1rpx solid #F0F4FF;
					}

					.b_b {
						border-bottom: 1rpx solid #F0F4FF;
					}

					// 预定样式
					.icon,
					.cz {
						width: 100rpx;
						padding: 10rpx 20rpx;
						text-align: center;

						.icon_upload {
							width: 100%;
							height: 60rpx;
							border: 1rpx dashed #F0F4FF;
						}

						image {
							width: 100%;
							height: 100%;
						}

						.del_btn {
							background-color: #f56c6c;
							width: 50rpx;
							height: 50rpx;
							border-radius: 10rpx;
						}
					}


					.des {
						padding: 10rpx 20rpx;
						flex: 1;
					}

					.nowrap {
						white-space: nowrap;
					}

					// 门票样式
					.mp_item {
						padding: 10rpx 20rpx;
						width: 150rpx;
						display: inline-block;
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