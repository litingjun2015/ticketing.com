<template>
	<view class="content font_family" :style="{paddingTop:menuButtonInfo+'px'}">
		<view class="header f_j">
			<image @click="back" class="icon" src="../../static/public/back_b.png" mode=""></image>
			<view class="item_box f">
				<view @click="header_check(index)" class="item" :class="{item_action:header_action==index}"
					v-for="(item,index) in header_title" :key="index">
					{{item.title}}
				</view>
			</view>
		</view>
		<!-- 编辑动态 -->
		<view class="set_dynamic" v-if="header_action===0 || header_action===1">
			<view class="title">
				<u--input placeholder="填写标题会有更多赞哦~" border="none" v-model="fb_data.title"></u--input>
			</view>
			<view class="dynamic_content">
				<u--textarea v-model="fb_data.content" border='none' height='150'
					placeholder="快来分享你的真实体验吧~可以带上明确的推荐 理由，更容易被推荐哦！"></u--textarea>
			</view>
			<!-- 上传图片 -->
			<view class="add_img_box f_j">
				<view class="img_arr" v-for="(item,index) in fb_data.images" :key="index">
					<view class="uni_icon" @click="delete_img(index)">
						<u-icon color='#ffffff' name="close-circle" size="20"></u-icon>
					</view>
					<image class="item" :src="item" mode="aspectFill"></image>
				</view>
				<view class="add_img f_zj" @click="add_img" v-if="img_arr.length<9">
					<image class="icon" src="../../static/public/add_img.png" mode=""></image>
				</view>
			</view>
			<!-- 选择动态分类 -->
			<view class="type f_j" @click="show=true" v-if="header_action===0">
				<view class="text" :style="{color:type_text?'#000000':''}">
					{{type_text?type_text:'请选择动态分类'}}
				</view>
				<view class="">
					<image class="img" style="width: 13rpx;height: 20rpx;" src="../../static/index/go_hot.png" mode="">
					</image>
				</view>
			</view>
		</view>
		<!-- 编辑游记/攻略 -->
		<view class="strategy" v-else>
			<view class="cover f_zj" @click="add_cover">
				<view class="add_cover" v-if="!fb_data.image">
					<image class="icon" src="../../static/public/add_cover.png" mode=""></image>
					<text class="text">添加封面</text>
				</view>
				<image v-else class="cover_img" :src="fb_data.image" mode="aspectFill"></image>
			</view>
			<view class="title">
				<u--input placeholder="填写标题会有更多赞哦~" border="none" v-model="fb_data.title"></u--input>
			</view>
			<editorme ref="ed" @uploadFile='uploadFile' placeholder="快来分享你的真实体验吧~可以带上明确的推荐 理由，更容易被推荐哦"></editorme>
		</view>
		<!-- 选择景区分类 -->
		<view class="spot_type f_j" @click="spot_show=true" v-if="header_action!==0">
			<view class="text" :style="{color:spot_text?'#000000':''}">
				{{spot_text?spot_text:'请选择景区分类'}}
			</view>
			<view class="">
				<image class="img" style="width: 13rpx;height: 20rpx;" src="../../static/index/go_hot.png" mode="">
				</image>
			</view>
		</view>
		<!-- 发布按钮 -->
		<view class="btn_box f_z">
			<view class="btn f_zj" @click="fb">
				发布
			</view>
		</view>
		<u-picker :show="show" :columns="trends_type_list" @cancel='show=false' keyName='title'
			@confirm='p_confirm'></u-picker>
		<u-picker :show="spot_show" :columns="spot_type" @cancel='spot_show=false' keyName='title'
			@confirm='spot_confirm'></u-picker>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { trends_type, spot_type_list } from "@/api/index.js"
	import { add, detail } from "@/api/index_menu/index.js"
	import { mapState } from 'vuex'
	import editorme from '../../components/Rboy-editor/Rboy-editor.vue'
	export default {
		components: {
			editorme
		},
		computed: {
			...mapState(['menuButtonInfo'])
		},
		data() {
			return {
				header_action: 0,
				header_title: [
					{ title: '动态', type: 4 },
					{ title: '美食', type: 3 },
					{ title: '写游记', type: 2 },
					{ title: '攻略', type: 1 },
				],
				content: '',
				img_arr: [],
				// 动态分类
				type_text: '',
				spot_text: '',
				show: false,
				spot_show: false,
				trends_type_list: [],
				spot_type: [], //景区分类
				fb_data: {
					type: '',
					title: '',
					content: '',
					images: [],
					type_id: '',
					fb_type: 2,
					image: '',
					mp_id: '',
					id: '',
				},
				set: false, //代表是否来自编辑按钮
			}
		},
		onLoad(e) {
			this.fb_data.type = this.header_title[0].type
			this.get_trends_type()
			this.get_spot_type_list()
			// 判断来自个人中心编辑按钮
			if (e.id) {
				this.fb_data.id = e.id
				this.fb_data.type = e.type
				if (e.type == 2) {
					this.header_action = 2
				} else {
					this.header_action = 3
				}
				this.set = true
				this.get_detail(e.id)
			}
		},
		methods: {
			delete_img(index) {
				this.fb_data.images.splice(index, 1)
			},
			add_img() {
				uni.chooseImage({
					count: 1,
					success: (res) => {
						// console.log(res)
						// this.img_arr.push(res.tempFilePaths[0])
						this.upload_img(res.tempFilePaths[0]).then(res => {
							console.log(res)
							if (res.code == 1) {
								// this.img_arr.push(res.data.fullurl)
								this.fb_data.images.push(res.data.fullurl)
							}
						})
					}
				})

			},
			// 上传封面
			add_cover() {
				uni.chooseImage({
					count: 1,
					success: (res) => {
						this.upload_img(res.tempFilePaths[0]).then(res => {
							console.log(res)
							if (res.code == 1) {
								this.fb_data.image = res.data.fullurl
							}
						})
					}
				})
			},
			header_check(index) {
				if (this.set) {
					return
				}
				// 切换类型之后重置请求数据
				this.fb_data = {
					type: '',
					title: '',
					content: '',
					images: [],
					type_id: '',
					fb_type: 2,
					image: '',
					mp_id: '',
					id: '',
				}
				this.header_action = index
				this.fb_data.type = this.header_title[index].type
			},
			uploadFile(res) {
				console.log(res)
				this.$refs.ed.editor_insertImage({
					src: res,
					alt: "图像",
				})
			},
			// 获取富文本编辑器内容
			async get_content() {
				let detail = await this.$refs.ed.editor_getcontents()
				console.log(detail)
				this.fb_data.content = detail
			},
			// 获取动态分类
			get_trends_type() {
				trends_type().then(res => {
					if (res.code == 1) {
						this.trends_type_list.push(res.data)
						// console.log(this.trends_type_list)
					}
				})
			},
			// 获取景区分类
			get_spot_type_list() {
				spot_type_list().then(res => {
					this.spot_type.push(res.data)
					// 首次请求
				})
			},
			p_confirm(e) {
				this.fb_data.type_id = e.value[0].id
				this.type_text = e.value[0].title
				this.show = false
			},
			spot_confirm(e) {
				this.fb_data.mp_id = e.value[0].id
				this.spot_text = e.value[0].title
				this.spot_show = false
			},
			fb() {
				// 获取富文本内容
				if (this.fb_data.type == 1 || this.fb_data.type == 2) {
					this.get_content()
				}
				console.log(this.fb_data)
				setTimeout(() => {
					if (this.check_fb_kong()) {
						return
					} else {
						// 发布的时候去掉图片域名
						if (this.fb_data.images.length > 0) {
							let arr = []
							this.fb_data.images.forEach(item => {
								arr.push(this.removeDomainFromUrl(item))
							})
							this.fb_data.images = arr
						}
						if (this.fb_data.image) {
							this.fb_data.image = this.removeDomainFromUrl(this.fb_data.image)
						}
						add(this.fb_data).then(res => {
							console.log(res)
							if (res.code == 1) {
								this.$refs.uToast.show({
									type: 'success',
									message: '发布成功，等待审核',
									duration: 1000,
									complete: () => {
										uni.navigateBack({
											delta: 1
										})
									}
								})
							} else {
								this.$refs.uToast.show({
									type: 'error',
									message: res.msg,
								})
							}
						})
					}
				}, 100)
			},
			// 获取编辑详情
			get_detail(id) {
				detail({ id }).then(res => {
					if (res.code == 1) {
						// 数据回显
						this.fb_data.image = res.data.image
						this.fb_data.title = res.data.title
						this.fb_data.content = res.data.content
						this.spot_text = res.data.jqt_name
						this.fb_data.mp_id = res.data.jqt_id
						this.set_content()
					}
				})
			},
			// 设置内容
			async set_content() {
				this.$refs.ed.editor_setContents(this.fb_data.content)
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: #ffffff;

		.header {
			padding-left: 30rpx;

			.icon {
				width: 18rpx;
				height: 36rpx;
			}

			.item_box {
				margin-left: 30rpx;

				.item_action {
					font-size: 32rpx;
				}

				.item {
					font-weight: 400;
					font-size: 32rpx;
					color: #3D3D3D;
					margin-right: 40rpx;
				}

				.item_action {
					font-weight: 600;
					font-size: 36rpx;
				}
			}
		}

		.set_dynamic {
			padding: 30rpx;
			margin-top: 70rpx;

			.type {
				margin-top: 30rpx;
				padding-bottom: 20rpx;

				.img {
					margin-left: 50rpx;
					// vertical-align: bottom;
				}

				.text {
					color: #999999;
					width: 250rpx;
				}
			}

			.title {
				padding-bottom: 25rpx;
				border-bottom: 2rpx solid #EEEEEE;
			}

			.dynamic_content {
				margin-top: 25rpx;

				::v-deep .u-textarea {
					background: none !important;
					padding: 0 !important;
				}
			}

			.add_img_box {
				flex-wrap: wrap;

				.img_arr {
					position: relative;
					margin-right: 20rpx;
					margin-bottom: 20rpx;

					.uni_icon {
						position: absolute;
						top: 10rpx;
						right: 10rpx;
					}

					.item {
						width: 160rpx;
						height: 160rpx;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
						vertical-align: bottom;

					}
				}

				.add_img {
					margin-right: 20rpx;
					margin-bottom: 20rpx;
					width: 160rpx;
					height: 160rpx;
					background: #F7F7F7;
					border-radius: 8rpx 8rpx 8rpx 8rpx;

					.icon {
						width: 48rpx;
						height: 48rpx;
					}
				}
			}
		}

		.strategy {
			padding: 30rpx;
			margin-top: 70rpx;

			.cover {
				width: 100%;
				height: 154rpx;
				background: #F9F9F9;
				border-radius: 12rpx 12rpx 12rpx 12rpx;
				border: 2rpx dashed #E0E0E0;
				overflow: hidden;

				.add_cover {
					.icon {
						width: 24rpx;
						height: 24rpx;
					}

					.text {
						font-weight: 400;
						font-size: 28rpx;
						color: #999999;
						margin-left: 20rpx;
					}
				}

				.cover_img {
					width: 100%;
					height: 100%;
				}
			}

			.title {
				padding-bottom: 25rpx;
				border-bottom: 2rpx solid #EEEEEE;
				margin-top: 25rpx;
			}
		}

		.btn_box {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			padding-bottom: 40rpx;

			.btn {
				width: 520rpx;
				height: 88rpx;
				background: #FFE706;
				border-radius: 96rpx 96rpx 96rpx 96rpx;
				font-size: 28rpx;
				color: #232323;
			}
		}

		.spot_type {
			padding: 0rpx 30rpx;
			padding-bottom: 20rpx;

			.img {
				margin-left: 50rpx;
				// vertical-align: bottom;
			}

			.text {
				color: #999999;
				width: 250rpx;
			}
		}
	}
</style>