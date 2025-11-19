import config from '@/config.js'
const request = function(obj) {
	return new Promise(function(resolve, reject) {
		let opt = {
			url: config.serverIp + obj.url,
			method: obj.method,
			timeout: obj.timeout || 10000 * 3,
			header: {
				'Content-type': 'application/json' || 'application/x-www-form-urlencoded',
			},
			data: obj.data,
			success: res => {
				if (res.statusCode === 200) {
					// lodash 截取
					resolve(res.data)
				} else {
					reject({
						code: -1,
						msg: 'server error'
					})
					if (res.data.code == 401) {
						uni.hideLoading()
						uni.showModal({
							title: '提示',
							content: '您还未登录是否去登录？',
							success: function(res) {
								if (res.confirm) {
									// console.log('用户点击确定');
									uni.reLaunch({
										url: '/pagesA/login/login'
									})
								} else if (res.cancel) {
									uni.reLaunch({
										url: '/pages/index/index'
									})
									// console.log('用户点击取消');
								}
							}
						});
					} else if (res.data.code == 402) {
						wx.login({
							success: (res) => {
								console.log('获取成功', res);
								Bind_to_wechat({
									code: res.code
								}).then(res => {
									uni.showToast({
										title: '网络开小差了，请重新支付！',
										icon: 'none'
									})
								})
							},
							flil(res) {
								console.log('获取失败', res);
							},
							complete(res) {
								console.log(res);
							}
						})
					}


				}
			},
			fail: function({
				errMsg
			}) {
				console.log(errMsg, "=====")
				if (errMsg === 'request:fail timeout') {
					uni.showToast({
						title: '服务忙,稍等会再试吧',
						icon: 'none'
					})
				}
				reject({
					code: -1,
					msg: 'server error'
				})
			}
		}
		let pid = uni.getStorageSync('pid')
		// #ifdef MP-WEIXIN
		opt.header.platform = 'mp-weixin'
		// #endif
		// #ifdef H5
		opt.header.platform = 'mp'
		// #endif
		let city = uni.getStorageSync('city')
		let lon = uni.getStorageSync('lon')
		let lat = uni.getStorageSync('lat')
		let token = uni.getStorageSync('token')
		city ? opt.header.city = encodeURIComponent(city) : ''
		lon ? opt.header.lon = lon : ''
		lat ? opt.header.lat = lat : ''
		token ? opt.header.token = token : ''

		pid ? opt.header.pid = pid : ''

		// console.log(obj, '请求来源')
		// let location = uni.getStorageSync('location')
		// if (location) {
		// 	location = JSON.parse(location)
		// 	console.log(location);
		// }
		// uni.getStorageSync('userinfo') ? opt.header.token = token.token : ' '
		// uni.getStorageSync('location') ? opt.header.lat = location.latitude : ' '
		// uni.getStorageSync('location') ? opt.header.lon = location.longitude : ' '
		uni.request(opt)
	})
}
const getParams = obj => {

	let str = '?'
	for (let s in obj) {
		str += `${s}=${obj[s]}&`
	}
	return str.substr(0, str.length - 1)
}

export default request