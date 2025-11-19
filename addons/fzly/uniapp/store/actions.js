import { login, userInfo, check } from '@/api/public.js'
export default {
	loging(state, data) {
		// console.log(data, 'actions')
		login(data).then(res => {
			if (res.code == 1) {
				// 登陆注册成功,存入token，使用token获取用户信息存入vuex，本地缓存
				uni.setStorageSync('token', res.data.userinfo.token)
				userInfo().then(res => {
					// console.log(res, '用户信息')
					if (res.code == 1) {
						state.commit('set_user_info', res.data)
						uni.setStorageSync('user_info', res.data)
						uni.switchTab({
							url: '/pages/index/index'
						})
					}
				})

			}
		})
	},
	// 手机号登录
	mobile_loging(state, data) {
		check(data).then(res => {
			if (res.code == 1) {
				// 登陆注册成功,存入token，使用token获取用户信息存入vuex，本地缓存
				uni.setStorageSync('token', res.data.userinfo.token)
				userInfo().then(res => {
					// console.log(res, '用户信息')
					if (res.code == 1) {
						state.commit('set_user_info', res.data)
						uni.setStorageSync('user_info', res.data)
						uni.switchTab({
							url: '/pages/index/index'
						})
					}
				})
			}
		})
	},
	// 获取用户信息
	get_user(state) {
		userInfo().then(res => {
			// console.log(res, '用户信息')
			if (res.code == 1) {
				state.commit('set_user_info', res.data)
				uni.setStorageSync('user_info', res.data)
			}
		})
	}
}