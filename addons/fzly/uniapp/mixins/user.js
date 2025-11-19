// 使用此mixin需要将详情变量设置为detail_msg
import { follow } from "@/api/user.js"
import { dz, sc } from '@/api/index_menu/index.js'
export default {
	methods: {
		// 关注
		follow_btn(type) {
			// console.log(this.query_data)
			let obj = {
				df_id: this.detail_msg.user_id,
				type
			}
			follow(obj).then(res => {
				console.log(res)
				if (res.code == 1) {
					if (type == 1) {
						this.$refs.uToast.show({
							type: 'success',
							message: '关注成功'
						})
						this.detail_msg.is_gz = 1
					} else {
						this.$refs.uToast.show({
							type: 'default',
							message: '取消关注'
						})
						this.detail_msg.is_gz = 0
					}
					this.$forceUpdate()
				}
			})
		},
		// 点赞
		dz_btn(type) {
			this.dz_sc.option = type
			dz(this.dz_sc).then(res => {
				if (res.code == 1) {
					if (type == 1) {
						this.$refs.uToast.show({
							type: 'success',
							message: '点赞成功'
						})
						this.detail_msg.is_dz = 1
						this.detail_msg.dz_nums += 1
					} else {
						this.detail_msg.dz_nums -= 1
						this.detail_msg.is_dz = 0
					}
					this.$forceUpdate()
				}
			})
		},
		// 收藏
		sc_btn(type) {
			// console.log(111111111111)
			this.dz_sc.option = type
			sc(this.dz_sc).then(res => {
				if (res.code == 1) {
					if (type == 1) {
						this.$refs.uToast.show({
							type: 'success',
							message: '收藏成功'
						})
						this.detail_msg.is_sc = 1
						this.detail_msg.sc_nums += 1
					} else {
						this.detail_msg.sc_nums -= 1
						this.detail_msg.is_sc = 0
					}
					this.$forceUpdate()
				}
			})
		},
	},
}