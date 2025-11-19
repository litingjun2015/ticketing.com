<template>
	<view class="content font_family">
		<view class="header f_zj" :style="{backgroundImage:`url(${set_data.back_avatar})`}">
			<view class="user_box f_d f_j">
				<view class="avatar">
					<!-- #ifdef MP-WEIXIN -->
					<u-avatar :src="set_data.avatar" size="140rpx" mode="aspectFill"></u-avatar>
					<button class="ava_btn" open-type="chooseAvatar" @chooseavatar="chooseavatar">
						<view class="set_avatar f_zj">
							<image class="icon" src="../../static/public/set_avatar.png" mode="aspectFill"></image>
						</view>
					</button>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<u-avatar :src="set_data.avatar" size="140rpx" mode="aspectFill" @click="h5_avatar"></u-avatar>
					<button class="ava_btn" @click="h5_avatar">
						<view class="set_avatar f_zj">
							<image class="icon" src="../../static/public/set_avatar.png" mode="aspectFill"></image>
						</view>
					</button>
					<!-- #endif -->
				</view>
				<!-- v-if='common.mp_switch==1' -->
				<view class="bg" @click="set_bg">
					设置背景
				</view>
			</view>
		</view>
		<!-- 用户基本信息 -->
		<view class="msg_box">
			<view class="item f_z_b">
				<view class="left">
					昵称
				</view>
				<view class="right">
					<!-- #ifdef MP-WEIXIN -->
					<u--input type='nickname' :readonly='common.mp_switch==0' inputAlign='right' placeholder="请输入内容"
						border="none" v-model="set_data.username" @change='nickname'></u--input>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<u--input inputAlign='right' placeholder="请输入内容" border="none" v-model="set_data.username"
						@change='nickname'></u--input>
					<!-- #endif -->
					<!-- <input type="nickname" placeholder="请输入内容" 
						v-model="set_data.username"> -->
				</view>
			</view>
			<view class="item f_z_b" @click="gender_show=true">
				<view class="left">
					性别
				</view>
				<view class="right f_zj">
					<view class="">
						{{gender_val}}
					</view>
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
			<view class="item f_z_b" @click="birthday_show=true">
				<view class="left">
					生日
				</view>
				<view class="right f_zj">
					<view class="">
						{{set_data.birthday}}
					</view>
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
			<view class="item f_z_b" @click="go('/pagesA/set/address?type=set')">
				<view class="left">
					常住地
				</view>
				<view class="right f_zj">
					<view class="">
						{{set_data.c_city}}
					</view>
					<image class="icon" src="../../static/index/go_hot.png" mode=""></image>
				</view>
			</view>
		</view>
		<!-- 确认修改按钮 -->
		<view class="set_btn f_zj" @click="set">
			修改
		</view>
		<!-- 生日选择器 -->
		<u-datetime-picker confirmColor='#FFE706' :minDate='-1262332800000' @cancel='birthday_show=false'
			@confirm="confirm" :show="birthday_show" v-model="birthday_val" mode="date"></u-datetime-picker>
		<!-- 性别选择器 -->
		<u-picker @cancel='gender_show=false' confirmColor='#FFE706' :show="gender_show" keyName='text'
			:columns="columns" @confirm='g_confirm'></u-picker>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { userInfo, profile } from '@/api/public.js'
	import { mapState, mapMutations } from 'vuex'
	export default {
		computed: {
			...mapState(['user_info', 'common'])
		},
		data() {
			return {
				set_data: {
					username: '',
					birthday: '',
					gender: '',
					avatar: '',
					back_avatar: '',
					c_city: ''
				},
				birthday_show: false,
				birthday_val: -631180800000,
				gender_val: '',
				gender_show: false,
				columns: [
					[
						{ text: '男', type: 1 },
						{ text: '女', type: 0 },
					]
				]
			};
		},
		onReady() {},
		onShow() {
			// 获取最新的城市
			this.set_data.c_city = this.user_info.c_city ? this.user_info.c_city : ""
		},
		onLoad() {
			this.get_user_msg()
		},
		methods: {
			nickname(e) {
				console.log(e)
			},
			go(url) {
				uni.navigateTo({
					url
				})
			},
			...mapMutations(['set_user_info']),
			// 赋值
			assignment() {
				this.set_data.username = this.user_info.username
				this.set_data.birthday = this.user_info.birthday ? this.user_info.birthday : ''
				this.set_data.gender = this.user_info.gender
				this.set_data.avatar = this.user_info.avatar
				this.set_data.back_avatar = this.user_info.back_avatar
				this.set_data.c_city = this.user_info.c_city ? this.user_info.c_city : ""
				this.gender_val = this.user_info.gender == 1 ? '男' : '女'
			},
			chooseavatar(e) {
				// console.log(e)
				this.upload_img(e.detail.avatarUrl).then(res => {
					console.log(res)
					if (res.code == 1) {
						profile({ avatar: res.data.url }).then(res => {
							if (res.code == 1) {
								this.get_user_msg()
							}
						})
					}
				})
			},
			// h5修改头像
			h5_avatar() {
				uni.chooseImage({
					count: 1,
					success: (res) => {
						console.log(res)
						if (res.errMsg == 'chooseImage:ok') {
							this.upload_img(res.tempFilePaths[0]).then(res => {
								console.log(res)
								if (res.code == 1) {
									profile({ avatar: res.data.url }).then(res => {
										if (res.code == 1) {
											this.get_user_msg()
										}
									})
								}
							})
						}
					},
					fail: (res) => {
						console.log(res)
					}
				})
			},
			confirm(e) {
				// console.log(e)
				this.set_data.birthday = uni.$u.timeFormat(e.value, 'yyyy-mm-dd');
				this.birthday_show = false
			},
			g_confirm(e) {
				this.gender_show = false
				this.set_data.gender = e.value[0].type
				this.gender_val = e.value[0].text
			},
			// 获取用户信息
			get_user_msg() {
				userInfo().then(res => {
					if (res.code == 1) {
						this.set_user_info(res.data)
						uni.setStorageSync('user_info', res.data)
						this.assignment()
					}
				})
			},
			set_bg() {
				uni.chooseImage({
					count: 1,
					success: (res) => {
						this.upload_img(res.tempFilePaths[0]).then(res => {
							if (res.code == 1) {
								profile({ back_avatar: res.data.url }).then(res => {
									if (res.code == 1) {
										this.get_user_msg()
									}
								})
							}
						})
					}
				})
			},
			// 修改信息
			set() {
				let obj = {
					username: this.set_data.username,
					birthday: this.set_data.birthday,
					gender: this.set_data.gender,
					c_city: this.set_data.c_city
				}
				profile(obj).then(res => {
					if (res.code == 1) {
						this.get_user_msg()
						this.$refs.uToast.show({
							type: 'success',
							message: '修改成功'
						})
					} else {
						this.$refs.uToast.show({
							message: res.msg
						})
					}
				})
			},
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		.header {
			width: 100%;
			height: 298rpx;
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;

			.user_box {
				.avatar {
					position: relative;

					.ava_btn::after {
						border: none;
					}

					.ava_btn {
						position: absolute;
						right: -7rpx;
						bottom: -3rpx;
						padding: 0;
						line-height: 0;
						background: none;


						.set_avatar {
							width: 40rpx;
							height: 40rpx;
							background: #232323;
							border-radius: 50%;

							.icon {
								width: 24rpx;
								height: 24rpx;
							}
						}
					}
				}

				.bg {
					margin-top: 20rpx;
					padding: 14rpx 38rpx;
					background: #FFFFFF;
					border-radius: 1254rpx 1254rpx 1254rpx 1254rpx;
				}
			}
		}

		.msg_box {
			padding: 0rpx 28rpx;

			.item {
				padding: 26rpx 0rpx;
				border-bottom: 1rpx solid #EEEEEE;
				font-weight: 400;
				font-size: 32rpx;
				color: #202020;

				.right {
					.icon {
						margin-left: 15rpx;
						width: 10rpx;
						height: 22rpx;
					}

					input {
						text-align: right;
					}
				}
			}
		}

		.set_btn {
			width: 688rpx;
			height: 99rpx;
			background: #FFE706;
			border-radius: 50rpx 50rpx 50rpx 50rpx;
			margin: 0 auto;
			font-weight: 400;
			font-size: 30rpx;
			color: #232323;
			margin-top: 50rpx;
		}

		::v-deep.u-popup__content {
			border-radius: 40rpx 40rpx 0rpx 0rpx !important;
		}

		::v-deep.u-toolbar {
			margin-top: 10rpx;
		}
	}
</style>