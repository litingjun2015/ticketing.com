<template>
	<view class="content font_family">
		<view class="add ">
			<view class="box f_zj" @click="open(1)">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/add.png" mode=""></image>
				<text>新增出行人</text>
			</view>
		</view>
		<!-- 出行人列表 -->
		<view class="people_box">
			<view class="item f_z_b f_j" v-for="(item,index) in list" :key="index" @click="people_check(index)">
				<view class="left f_j">
					<view class="no_check" v-if="!item.check">

					</view>
					<image v-else class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/checkmark_l.png" mode=""></image>
					<text class="text">{{item.name}}</text>
				</view>
				<image @click.stop="open(2,item)" class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
			</view>
		</view>
		<!-- 确定按钮 -->
		<view class="btn f_zj" @click="travel_people">
			确定
		</view>
		<!-- 弹出层 -->
		<u-popup :show="show" @close="close" round='40rpx'>
			<view class="pop_box f_d f_zj">
				<view class="ipt_box f_j">
					<text>姓名：</text>
					<u--input placeholder="请输入内容" border="none" v-model="set_data.name"></u--input>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
				</view>
				<view class="ipt_box f_j">
					<text>电话：</text>
					<u--input placeholder="请输入内容" border="none" v-model="set_data.tel"></u--input>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
				</view>
				<view class="ipt_box f_j">
					<text>身份证：</text>
					<u--input placeholder="请输入内容" border="none" v-model="set_data.id_card"></u--input>
					<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/set.png" mode=""></image>
				</view>
				<view class="btn_set f_zj" @click="confirm">
					确定
				</view>
			</view>
		</u-popup>
		<u-toast ref="uToast"></u-toast>
	</view>
</template>

<script>
	import { mapMutations } from 'vuex'
	import { traveler_list, add_traveler, edit_traveler } from '@/api/user.js'
	export default {
		data() {
			return {
				people_action: '',
				show: false,
				set_data: {
					name: '',
					tel: '',
					id_card: ''
				},
				list: [],
				type: '',
				set_id: ''
			};
		},
		onLoad() {
			this.get_list()
		},
		methods: {
			...mapMutations(['set_travel_id', 'set_travel_people']),
			people_check(index) {
				this.people_action = index
				this.list[index].check = !this.list[index].check
			},
			open(type, item) {
				this.show = true
				this.type = type
				if (type == 2) {
					this.set_data.name = item.name
					this.set_data.id_card = item.id_card
					this.set_data.tel = item.tel
					this.set_id = item.id
				}
			},
			close() {
				this.show = false
				this.value = ''
			},
			// 获取出行人列表
			get_list() {
				traveler_list().then(res => {
					if (res.code == 1) {
						res.data.forEach(item => {
							item.check = false
						})
						console.log(res.data)
						this.list = res.data
					}
				})
			},
			// 确定按钮
			confirm() {
				if (!this.set_data.name) {
					this.$refs.uToast.show({
						message: '请输入姓名'
					})
					return
				}
				if (!uni.$u.test.mobile(this.set_data.tel)) {
					this.$refs.uToast.show({
						message: '请输入正确的手机号'
					})
					return
				}
				if (!uni.$u.test.idCard(this.set_data.id_card)) {
					this.$refs.uToast.show({
						message: '请输入正确的身份证号'
					})
					return
				}
				if (this.type == 1) {
					this.add()
				} else {
					this.set()
				}
			},
			// 增加出行人
			add() {
				add_traveler(this.set_data).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.$refs.uToast.show({
							message: res.msg
						})
						this.set_data.name = ''
						this.set_data.id_card = ''
						this.set_data.tel = ''
						this.get_list()
					} else {
						this.$refs.uToast.show({
							message: res.msg
						})
					}
					this.show = false
				})
			},
			// 修改出行人
			set() {
				let obj = this.set_data
				obj.id = this.set_id
				edit_traveler(obj).then(res => {
					if (res.code == 1) {
						this.$refs.uToast.show({
							message: res.msg
						})
						this.set_data.name = ''
						this.set_data.id_card = ''
						this.set_data.tel = ''
						this.get_list()
					} else {
						this.$refs.uToast.show({
							message: res.msg
						})
					}
					this.show = false
				})
			},
			// 确定出行人
			travel_people() {
				let arr = []
				let arr1 = []
				this.list.forEach(item => {
					if (item.check) {
						arr.push(item.id)
						arr1.push(item)
					}
				})
				this.set_travel_id(arr)
				this.set_travel_people(arr1)
				if (arr.length == 0) {
					this.$refs.uToast.show({
						message: '请选择出行人'
					})
					return
				}
				uni.navigateBack({
					delta: 1
				})
			}
		},
	}
</script>

<style lang="scss" scoped>
	.content {
		.add {
			padding: 50rpx 30rpx;

			.box {
				width: 100%;
				height: 74rpx;
				background: #FFFFFF;
				border-radius: 4rpx 4rpx 4rpx 4rpx;

				.icon {
					width: 36rpx;
					height: 36rpx;
					margin-right: 20rpx;
				}
			}
		}

		.people_box {
			.item {
				padding: 22rpx 30rpx;
				background: #FFFFFF;

				.left {
					.no_check {
						width: 25rpx;
						height: 25rpx;
						border-radius: 50%;
						border: 2rpx solid #1D85ED;
					}

					.icon {
						width: 26rpx;
						height: 26rpx;
					}

					.text {
						font-weight: 400;
						font-size: 32rpx;
						color: #3D3D3D;
						font-style: normal;
						text-transform: none;
						margin-left: 20rpx;
					}
				}

				.icon {
					width: 28rpx;
					height: 28rpx;
				}
			}
		}

		.btn {
			margin: 0 auto;
			margin-top: 200rpx;
			width: 658rpx;
			height: 84rpx;
			background: #FFE706;
			border-radius: 16rpx 16rpx 16rpx 16rpx;
			font-weight: 400;
			font-size: 28rpx;
			color: #232323;
			font-style: normal;
			text-transform: none;
		}
	}

	.pop_box {
		// height: 354rpx;
		padding-top: 20rpx;

		.ipt_box {
			margin-top: 30rpx;
			width: 658rpx;
			border-bottom: 1rpx solid #D8D8D8;
			padding-bottom: 30rpx;

			.icon {
				width: 36rpx;
				height: 36rpx;
				margin-left: 20rpx;
			}
		}

		.btn_set {
			margin-top: 50rpx;
			width: 658rpx;
			height: 84rpx;
			background: #FFE706;
			border-radius: 16rpx 16rpx 16rpx 16rpx;
			font-weight: 400;
			font-size: 28rpx;
			color: #232323;
			font-style: normal;
			text-transform: none;
		}
	}
</style>