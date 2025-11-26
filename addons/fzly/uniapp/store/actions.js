import { login, userInfo } from '@/api/public.js'
export default {
	loging(state, data) {
		console.log('loging action 调用', data)
		uni.showLoading({
			title: '登录中...'
		})
		login(data).then(res => {
			console.log('登录接口返回', res)
			if (res.code == 1) {
				// 登陆注册成功,存入token，使用token获取用户信息存入vuex，本地缓存
				uni.setStorageSync('token', res.data.userinfo.token)
				userInfo().then(res => {
					uni.hideLoading()
					console.log('用户信息', res)
					if (res.code == 1) {
						state.commit('set_user_info', res.data)
						uni.setStorageSync('user_info', res.data)
						uni.switchTab({
							url: '/pages/index/index'
						})
					}
				}).catch(err => {
					uni.hideLoading()
					console.log('获取用户信息失败', err)
				})
			} else {
				uni.hideLoading()
				uni.showToast({
					title: res.msg || '登录失败',
					icon: 'none'
				})
			}
		}).catch(err => {
			uni.hideLoading()
			console.log('登录接口请求失败', err)
			uni.showToast({
				title: '网络错误，请重试',
				icon: 'none'
			})
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