<template>
	<view class="content font_family">
		<u-alert title="审核未通过,请重新提交" center type="warning" v-if="user_info.is_dy==3"></u-alert>
		<u-alert title="审核已通过" center type="success" v-if="user_info.is_dy==2"></u-alert>
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
					导游资格证号
				</view>
				<view class="right">
					<u--input fontSize='26rpx' :placeholderStyle='placeholderStyle' placeholder="请输入资格证号" border="none"
						v-model="query_data.number"></u--input>
				</view>
			</view>
			<view class="item f_j">
				<view class="left">
					发证机关
				</view>
				<view class="right">
					<u--input fontSize='26rpx' :placeholderStyle='placeholderStyle' placeholder="请输入" border="none"
						v-model="query_data.organ"></u--input>
				</view>
			</view>
			<!-- <view class="item f_j">
				<view class="left">
					银行卡
				</view>
				<view class="right">
					<u--input fontSize='26rpx' :placeholderStyle='placeholderStyle' placeholder="请输入银行卡号" border="none"
						v-model="query_data.bank"></u--input>
				</view>
			</view> -->
			<!-- 身份证 -->
			<view class="id_card">
				<view class="title">
					上传您的身份证照片
				</view>
				<view class="card_img f_z_b">
					<view class="left f_d f_j">
						<view class="img f_zj"
							:style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/card_r.png)`}">
							<image v-if="query_data.back_image" class="card_show" :src="query_data.back_image"
								mode="aspectFill"></image>
							<image @click="add_img(1)" v-else class="add" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/add_b.png" mode="">
							</image>
							<!-- 删除按钮 -->
							<view @click="delet(1)" class="delte_btn" v-if="query_data.back_image">
								<u-icon name="close-circle-fill" color="#ffffff" size="20"></u-icon>
							</view>
						</view>
						<view class="tips">
							上传人面像
						</view>
					</view>
					<view class="left right f_d f_j">
						<view class="img f_zj"
							:style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/card_g.png)`}">
							<image v-if="query_data.font_image" class="card_show" :src="query_data.font_image"
								mode="aspectFill"></image>
							<image @click="add_img(2)" v-else class="add" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/add_b.png" mode="">
							</image>
							<!-- 删除按钮 -->
							<view @click="delet(2)" class="delte_btn" v-if="query_data.font_image">
								<u-icon name="close-circle-fill" color="#ffffff" size="20"></u-icon>
							</view>
						</view>
						<view class="tips">
							上传国徽面
						</view>
					</view>
				</view>
				<view class="tip_text f_z">
					拍摄时，请确保身份证<text style="color:#2B72CA">边框完整，字体清晰，亮度均匀</text>
				</view>
			</view>
			<!-- 导游证 -->
			<view class="guide_card">
				<view class="title">
					上传您的导游证
				</view>
				<view class="card_box f_zj"
					:style="{backgroundImage:`url(${projectUrl}assets/addons/fzly/img/zg.png)`}">
					<image v-if="query_data.certificate_image" class="card_show" :src="query_data.certificate_image"
						mode="aspectFill"></image>
					<image @click="add_img(3)" v-else class="add" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/add_b.png" mode=""></image>
					<!-- 删除按钮 -->
					<view @click="delet(3)" class="delte_btn" v-if="query_data.certificate_image">
						<u-icon name="close-circle-fill" color="#ffffff" size="20"></u-icon>
					</view>
				</view>
			</view>
			<view class="btn f_zj" @click="sub" v-if="user_info.is_dy==-1">
				提交
			</view>
			<view class="btn f_zj" v-if="user_info.is_dy==1">
				审核中
			</view>
			<view class="btn f_zj" v-if="user_info.is_dy==2">
				审核通过
			</view>
			<view class="btn f_zj" @click="sub" v-if="user_info.is_dy==3">
				重新提交
			</view>
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapActions, mapState } from 'vuex'
	import { apply } from '@/api/user.js'
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
				card_r_show: '',
				card_g_show: '',
				card_zg_show: '',
				query_data: {
					name: '',
					tel: '',
					number: '',
					organ: '',
					// bank: '',
					font_image: '',
					back_image: '',
					certificate_image: ''
				},
			}
		},
		onLoad() {
			this.projectUrl = this.$projectUrl
			if (this.user_info.is_dy != -1) {
				this.data_view()
			}
			this.get_user()
		},
		methods: {
			...mapActions(['get_user']),
			add_img(type) {
				uni.chooseImage({
					count: 1,
					success: (res) => {
						if (type == 1) {
							this.upload_img(res.tempFilePaths[0]).then(res => {
								console.log(res)
								if (res.code == 1) {
									this.query_data.back_image = res.data.fullurl
								}
							})
						} else if (type == 2) {
							this.upload_img(res.tempFilePaths[0]).then(res => {
								console.log(res)
								if (res.code == 1) {
									this.query_data.font_image = res.data.fullurl
								}
							})
						} else {
							this.upload_img(res.tempFilePaths[0]).then(res => {
								console.log(res)
								if (res.code == 1) {
									this.query_data.certificate_image = res.data.fullurl
								}
							})
						}
					}
				})
			},
			delet(type) {
				if (type == 1) {
					this.query_data.back_image = ''
				} else if (type == 2) {
					this.query_data.font_image = ''
				} else {
					this.query_data.certificate_image = ''
				}
			},
			sub() {
				if (this.check_dy_kong(this.query_data)) {
					return
				}
				// 去除图片域名
				this.query_data.back_image = this.removeDomainFromUrl(this.query_data.back_image)
				this.query_data.font_image = this.removeDomainFromUrl(this.query_data.font_image)
				this.query_data.certificate_image = this.removeDomainFromUrl(this.query_data.certificate_image)
				apply(this.query_data).then(res => {
					console.log(res)
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
					}
				})
			},
			// 数据回显
			data_view() {
				this.query_data.name = this.user_info.guide.name
				this.query_data.tel = this.user_info.guide.tel
				this.query_data.number = this.user_info.guide.number
				this.query_data.organ = this.user_info.guide.organ
				// this.query_data.bank = this.user_info.guide.bank
				this.query_data.font_image = this.user_info.guide.font_image
				this.query_data.back_image = this.user_info.guide.back_image
				this.query_data.certificate_image = this.user_info.guide.certificate_image
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