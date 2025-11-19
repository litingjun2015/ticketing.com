<template>
	<view class="content font_family">
		<view class="title">
			门票规格信息
		</view>
		<view class="msg_box">
			<!-- 添加门票 -->
			<view class="address item">
				<view class="text f_z_b f_j">
					<view class="">
						门票
					</view>
					<!-- <view class="add-item f_j">
						<u-icon name="edit-pen-fill" color="#FFFFFF" size="20rpx"></u-icon>
						<text style="margin-left: 10rpx;">添加</text>
					</view> -->
				</view>
				<!-- 追加表格 -->
				<view class="add-table ">
					<view class="header b_b ">
						<view class="t_title b_r mp_item">
							名称
						</view>
						<view class="jg b_r mp_item">
							价格
						</view>
						<view class="line_jg b_r mp_item">
							划线价格
						</view>
						<view class="cz mp_item">
							操作
						</view>
					</view>
					<view class="t-body f" :class="{b_b:index+1<query_data.mp_multiplejson.length}"
						v-for="(item,index) in query_data.mp_multiplejson" :key="index">
						<view class="t_title b_r mp_item">
							<u-input readonly v-model="item.intro"></u-input>
						</view>
						<view class="jg b_r mp_item">
							<u-input readonly v-model="item.price"></u-input>
						</view>
						<view class="line_jg b_r mp_item">
							<u-input readonly v-model="item.line_price"></u-input>
						</view>
						<view class="cz f_zj" style="flex: 1;">
							<view class="del_btn f_zj" @click="set_mp_item(item,index)">
								<u-icon name="edit-pen-fill" size="28rpx" color="#FFFFFF"></u-icon>
							</view>
						</view>
					</view>
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
	import { add_product } from '@/api/admissionm.js'
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
				query_data: {
					id: '',
					mp_multiplejson: []
				}
			};
		},
		onLoad(e) {
			console.log(e.msg)
			if (e.id && e.msg) {
				this.query_data.id = e.id
				this.query_data.mp_multiplejson = JSON.parse(e.msg)
			}
			console.log(this.query_data)
		},
		onReady() {

		},
		methods: {
			go(url) {
				uni.navigateTo({
					url
				})
			},
			set_mp_item(item, index) {
				uni.$once('mp', (data) => {
					this.query_data.mp_multiplejson.splice(index, 1, data)
				})
				uni.navigateTo({
					url: `/pagesC/product/set_mp?msg=${JSON.stringify(item)}`
				})
			},
			submit() {
				add_product(this.query_data).then(res => {
					if (res.code == 1) {
						uni.navigateBack({
							delta: 1
						})
					} else {
						uni.showToast({
							icon: 'none',
							title: res.msg
						})
					}
				})
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
					// margin-top: 20rpx;
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

					.t_title {
						width: 200rpx;
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