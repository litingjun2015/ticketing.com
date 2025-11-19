<template>
	<view class="content font_family">
		<view class="evaluate_box">
			<view class="headr f_j" v-for="(item,index) in start_arr" :key="index">
				<view class="title">
					{{item}}
				</view>
				<u-rate activeColor='#FF9922' size="25" allowHalf :count="5"
					v-model="query_data.xx_json[index].score"></u-rate>
				<text
					class="score_text">{{query_data.xx_json[index].score<3?'一般':query_data.xx_json[index].score==3?'满意':'非常好'}}</text>
			</view>
			<!-- 标签 -->
			<view class="tip">
				<image class="tip_icon" src="../../static/public/set.png" mode=""></image>
				<text class="tip_text">点击下方小标签写出你的感受,可以帮助更多小伙伴哦~</text>
			</view>
			<view class="tag f">
				<view class="item" :class="{check_item:item.check}" v-for="(item,index) in tag_arr" :key="index"
					@click="tag_check(item)">
					{{item.title}}
				</view>
			</view>
			<!-- 内容 -->
			<view class="evaluate_content">
				<u--textarea maxlength='-1' v-model="query_data.evaluate" border='none' placeholder="写出你的感受,可以帮助更多小伙伴哦~"
					height='200'></u--textarea>
			</view>
			<!-- 上传图片 -->
			<view class="add_img_box f_j">
				<view class="img_arr" v-for="(item,index) in img_arr" :key="index">
					<view class="uni_icon" @click="delete_img(index)">
						<u-icon color='#ffffff' name="close-circle" size="20"></u-icon>
					</view>
					<image class="item" :src="item" mode="aspectFill"></image>
				</view>
				<view class="add_img f_zj" @click="add_img" v-if="img_arr.length<9">
					<image class="icon" src="../../static/public/add_img.png" mode=""></image>
				</view>
			</view>
			<!-- 匿名评价 -->
			<view class="anonymous f_j">
				<image @click="anonymous_check" v-if="anonymous" class="anonymous_action"
					src="../../static/public/dg_y.png" mode="">
				</image>
				<image @click="anonymous_check" v-else class="no_anonymous" src="../../static/public/dg_h.png" mode="">
				</image>
				<view class="anonymous_text">
					匿名评价
				</view>
			</view>
		</view>
		<!-- 提交按钮 -->
		<view class="btn f_zj" @click="sub">
			提交
		</view>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { order_comment, get_evaluate_tags } from '@/api/user.js'
	export default {
		data() {
			return {
				value: '',
				img_arr: [],
				anonymous: false,
				query_data: {
					id: '',
					evaluate: '',
					images: [],
					is_nm: '',
					xx_json: [],
					tag_json: [],
				},
				tag_arr: [],
				start_arr: [],
				tag_query: {
					type: ''
				}
			}
		},
		onLoad(e) {
			this.query_data.id = e.id
			this.tag_query.type = e.type
			this.get_tag_list()
		},
		methods: {
			delete_img(index) {
				this.img_arr.splice(index, 1)
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
								this.img_arr.push(res.data.fullurl)
								this.query_data.images.push(res.data.url)
							}
						})
					}
				})

			},
			anonymous_check() {
				this.anonymous = !this.anonymous
			},
			sub() {
				for (let i = 0; i < this.query_data.xx_json.length; i++) {
					if (!this.query_data.xx_json[i].score) {
						this.$refs.uToast.show({
							message: `请给${this.query_data.xx_json[i].name}打分吧~`
						})
						return
					}
				}
				if (!this.query_data.evaluate) {
					this.$refs.uToast.show({
						message: '请输入内容'
					})
					return
				}
				if (this.anonymous) {
					this.query_data.is_nm = 1
				} else {
					this.query_data.is_nm = 0
				}
				order_comment(this.query_data).then(res => {
					console.log(res)
					if (res.code == 1) {
						uni.navigateBack({
							delta: 1
						})
					} else {
						this.$refs.uToast.show({
							message: res.msg
						})
					}
				})
			},
			// 获取评论标签
			get_tag_list() {
				get_evaluate_tags(this.tag_query).then(res => {
					// 循环标签
					res.data.tag_json.forEach(item => {
						this.tag_arr.push({ title: item, check: false })
					})
					// 循环星星评级
					res.data.xx_json.forEach(item => {
						this.query_data.xx_json.push({ name: item, score: '' })
					})
					this.start_arr = res.data.xx_json

				})
			},
			// 标签选择
			tag_check(item) {
				this.query_data.tag_json = []
				item.check = !item.check
				this.tag_arr.forEach(item => {
					if (item.check) {
						this.query_data.tag_json.push(item.title)
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.evaluate_box {
			background-color: #FFFFFF;
			padding: 30rpx;

			.headr {
				margin-bottom: 20rpx;
			}

			.title {
				font-weight: 400;
				font-size: 28rpx;
				color: #000000;
				margin-right: 30rpx;
			}

			.score_text {
				margin-left: 40rpx;
				font-weight: 400;
				font-size: 24rpx;
				color: #999999;
			}

			.tip {
				.tip_icon {
					width: 28rpx;
					height: 28rpx;
					margin-right: 15rpx;
				}

				.tip_text {
					font-weight: 400;
					font-size: 24rpx;
					color: #999999;
				}
			}

			.tag {
				margin-top: 50rpx;
				flex-wrap: wrap;

				.item {
					margin-right: 15rpx;
					margin-bottom: 15rpx;
					font-weight: 400;
					font-size: 26rpx;
					color: #878787;
					background: rgba(135, 135, 135, 0.1);
					padding: 10rpx 40rpx;
					border-radius: 25rpx 25rpx 25rpx 25rpx;
				}

				.check_item {
					color: #FF9922;
					background: rgba(255, 153, 34, 0.1);
				}
			}

			.evaluate_content {
				margin-top: 50rpx;

				::v-deep .u-textarea {
					padding: 0;
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

			.anonymous {
				margin-top: 20rpx;

				.no_anonymous {
					width: 36rpx;
					height: 36rpx;
				}

				.anonymous_action {
					width: 36rpx;
					height: 36rpx;
				}

				.anonymous_text {
					font-weight: 400;
					font-size: 28rpx;
					color: #232323;
					margin-left: 16rpx;
				}
			}
		}

		.btn {
			width: 690rpx;
			height: 100rpx;
			background: #FDDC05;
			border-radius: 50rpx 50rpx 50rpx 50rpx;
			margin: 0 auto;
			font-weight: 400;
			font-size: 28rpx;
			color: #232323;
			margin-top: 50rpx;
		}
	}
</style>