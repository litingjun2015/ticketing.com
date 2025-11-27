<template>
	<view class="content font_family">
		<u-alert title="审核未通过,请重新提交" description="点击查看原因" center type="warning" v-if="user_info.is_admissionuser==2"
			@click="view_refuse"></u-alert>
		<u-alert title="审核已通过" center type="success" v-if="user_info.is_admissionuser==1"></u-alert>
		<view class="msg_box">
			<view class="item f_j">
				<view class="left">
					姓名
				</view>
				<view class="right">
					<u--input fontSize='26rpx' :placeholderStyle='placeholderStyle' placeholder="请输入您的姓名" border="none"
						v-model="query_data.name"></u--input>
				</view>
			</view>
			<view class="item f_j">
				<view class="left">
					手机号
				</view>
				<view class="right">
					<u--input fontSize='26rpx' :placeholderStyle='placeholderStyle' placeholder="请输入您的手机号" border="none"
						v-model="query_data.tel"></u--input>
				</view>
			</view>
			<view class="item f_j">
				<view class="left">
					身份证号
				</view>
				<view class="right">
					<u--input fontSize='26rpx' :placeholderStyle='placeholderStyle' placeholder="请输入身份证号" border="none"
						v-model="query_data.card"></u--input>
				</view>
			</view>
			<view class="item f_j">
				<view class="left">
					景区
				</view>
				<view class="right f_j">
					<u--input fontSize='26rpx' :placeholderStyle='placeholderStyle' placeholder="请输入景区" border="none"
						v-model="query_data.admission_title"></u--input>
				</view>
			</view>
			<view class="item f_j">
				<view class="left">
					景区所在城市
				</view>
				<view class="right f_j" @click="show=true">
					<view class="" style="margin-right: 10rpx;">
						{{query_data.admission_city?query_data.admission_city:'请选择'}}
					</view>
					<u-icon name="arrow-right" size="15"></u-icon>
				</view>
			</view>
			<!-- 景区缩略图 -->
			<view class="guide_card">
				<view class="title">
					上传景区缩略图
				</view>
				<view class="card_box f_zj">
					<image v-if="query_data.admission_image" class="card_show" :src="query_data.admission_image"
						mode="aspectFill">
					</image>
					<image @click="add_img(1)" v-else class="add" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/add_b.png" mode=""></image>
					<!-- 删除按钮 -->
					<view @click="delet(1)" class="delte_btn" v-if="query_data.admission_image">
						<u-icon name="close-circle-fill" color="#f56c6c" size="20"></u-icon>
					</view>
				</view>
			</view>
			<!-- 导游证 -->
			<view class="guide_card">
				<view class="title">
					上传您的营业执照
				</view>
				<view class="card_box f_zj"
					:style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/zg.png)`}">
					<image v-if="query_data.yy_image" class="card_show" :src="query_data.yy_image" mode="aspectFill">
					</image>
					<image @click="add_img(3)" v-else class="add" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/add_b.png" mode=""></image>
					<!-- 删除按钮 -->
					<view @click="delet(3)" class="delte_btn" v-if="query_data.yy_image">
						<u-icon name="close-circle-fill" color="#f56c6c" size="20"></u-icon>
					</view>
				</view>
			</view>
			<view class="btn f_zj" @click="sub" v-if="user_info.is_admissionuser==-1">
				提交
			</view>
			<view class="btn f_zj" v-if="user_info.is_admissionuser==0">
				审核中
			</view>
			<view class="btn f_zj" v-if="user_info.is_admissionuser==1">
				审核通过
			</view>
			<view class="btn f_zj" @click="sub" v-if="user_info.is_admissionuser==2">
				重新提交
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
		<u-picker :show="show" ref="uPicker" :columns="city_list" keyName="name" @cancel='show=false' @confirm='confirm'
			@change="changeHandler"></u-picker>
	</view>
</template>

<script>
	import { mapActions, mapState } from 'vuex'
	import { apply } from '@/api/user.js'
	import { guideList, admission, city } from '@/api/admissionm.js'
	export default {
		computed: {
			...mapState(['user_info'])
		},
		data() {
			return {
				projectUrl: '',
				placeholderStyle: {
					color: '#C1C2C6',
					fontSize: '26rpx'
				},
				// 照片信息
				query_data: {
					name: '',
					tel: '',
					card: '',
					admission_title: '',
					admission_city: '',
					admission_image: '',
					yy_image: '',
				},
				admission_text: '',
				city_data: '',
				city_list: [],
				show: false
			}
		},
		onLoad() {
			this.projectUrl = this.$projectUrl
			if (this.user_info.is_admissionuser != -1) {
				this.data_view()
			}
			this.get_user()
			this.get_city()
		},
		methods: {
			...mapActions(['get_user']),
			view_refuse() {
				uni.showModal({
					showCancel: false,
					title: '拒绝原因',
					content: this.user_info.admissionuser.refuse
				})
			},
			// 获取景区列表
			get_city() {
				city().then(res => {
					if (res.code == 1) {
						this.city_data = res.data
						this.city_list.push(res.data)
						this.city_list.push(res.data[0].child)
					}
				})
			},
			changeHandler(e) {
				const {
					columnIndex,
					value,
					values, // values为当前变化列的数组内容
					index,
					// 微信小程序无法将picker实例传出来，只能通过ref操作
					picker = this.$refs.uPicker
				} = e
				// 当第一列值发生变化时，变化第二列(后一列)对应的选项
				if (columnIndex === 0) {
					// picker为选择器this实例，变化第二列对应的选项
					picker.setColumnValues(1, this.city_data[index].child)
				}
			},
			confirm(e) {
				// console.log(e)
				this.query_data.admission_city = `${e.value[0].name}/${e.value[1].name}`
				this.show = false
			},
			add_img(type) {
				uni.chooseImage({
					count: 1,
					success: (res) => {
						this.upload_img(res.tempFilePaths[0]).then(res => {
							console.log(res)
							if (res.code == 1) {
								if (type == 1) {
									this.query_data.admission_image = res.data.fullurl
								} else {
									this.query_data.yy_image = res.data.fullurl
								}
							} else {
								uni.showToast({
									icon: 'none',
									title: res.msg
								})
							}
						})
					}
				})
			},
			delet(type) {
				if (type == 1) {
					this.query_data.admission_image = ''
				} else {
					this.query_data.yy_image = ''
				}
			},
			sub() {
				if (this.check_jq_kong(this.query_data)) {
					return
				}
				// 去除图片域名
				let obj = uni.$u.deepClone(this.query_data)
				obj.yy_image = this.removeDomainFromUrl(obj.yy_image)
				obj.admission_image = this.removeDomainFromUrl(obj.admission_image)
				admission(obj).then(res => {
					if (res.code == 1) {
						// 申请成功之后，请求用户信息
						this.get_user()
						this.$refs.uToast.show({
							type: 'success',
							message: '申请成功，等待审核',
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
			},
			// 数据回显
			data_view() {
				this.query_data.name = this.user_info.admissionuser.name
				this.query_data.tel = this.user_info.admissionuser.tel
				this.query_data.card = this.user_info.admissionuser.card
				this.query_data.admission_title = this.user_info.admissionuser.title
				this.query_data.yy_image = this.user_info.admissionuser.yy_image
				this.query_data.admission_city = this.user_info.admissionuser.city
				this.query_data.admission_image = this.user_info.admissionuser.image
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		background-color: #ffffff;
		padding-bottom: 30rpx;

		.msg_box {
			padding: 30rpx;

			.item {
				padding: 0rpx 10rpx;
				margin-top: 25rpx;
				padding-bottom: 25rpx;
				border-bottom: 1rpx solid #D8D8D8;

				.left {
					font-weight: 400;
					font-size: 28rpx;
					color: #65696C;
					width: 200rpx;
				}

				.right {}
			}

			.id_card {
				margin-top: 50rpx;

				.title {
					font-weight: 500;
					font-size: 28rpx;
					color: #353535;
				}

				.card_img {
					margin-top: 40rpx;
					padding: 0rpx 30rpx;
					padding-bottom: 30rpx;
					border-bottom: 1rpx solid #EBEBEB;

					.left {
						.img {
							border: 1rpx dashed #D8D8D8;
							width: 275rpx;
							height: 162rpx;
							// background-image: url(http://192.168.110.41:8823/assets/addons/fzly/img/card_r.png);
							background-repeat: no-repeat;
							background-size: 100%;
							position: relative;

							.add {
								width: 68rpx;
								height: 68rpx;
							}

							.card_show {
								width: 100%;
								height: 100%;
							}

							.delte_btn {
								position: absolute;
								right: 10rpx;
								top: 10rpx;
							}
						}

						.tips {
							margin-top: 16rpx;
							font-weight: 400;
							font-size: 24rpx;
							color: #616268;
							margin-top: 10rpx;
						}
					}

					.right {
						.img {
							// background-image: url(http://192.168.110.41:8823/assets/addons/fzly/img/card_g.png) !important;
							background-repeat: no-repeat;
							background-size: 100%;
						}
					}
				}

				.tip_text {
					margin-top: 30rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #353535;
				}
			}

			.guide_card {
				margin-top: 50rpx;

				.title {
					font-weight: 500;
					font-size: 28rpx;
					color: #353535;
				}

				.card_box {
					width: 100%;
					height: 246rpx;
					border: 1rpx dashed #D8D8D8;
					margin-top: 24rpx;
					// background-image: url(http://192.168.110.41:8823/assets/addons/fzly/img/zg.png) !important;
					background-repeat: no-repeat;
					background-size: 610rpx 171rpx;
					background-position: center;
					position: relative;

					.card_show {
						width: 100%;
						height: 100%;
					}

					.add {
						width: 68rpx;
						height: 68rpx;
					}

					.delte_btn {
						position: absolute;
						right: 10rpx;
						top: 10rpx;
					}
				}
			}

			.btn {
				margin-top: 126rpx;
				height: 90rpx;
				background: #FFE706;
				border-radius: 6rpx 6rpx 6rpx 6rpx;
				font-weight: 400;
				font-size: 28rpx;
				color: #232323;
			}
		}
	}
</style>