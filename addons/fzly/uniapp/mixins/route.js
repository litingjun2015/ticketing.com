export default {
	methods: {
		back() {
			uni.navigateBack({
				delta: 1
			})
		},
		// 用户未登录
		user_no_login() {
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
						// uni.reLaunch({
						// 	url: '/pages/index/index'
						// })
						// console.log('用户点击取消');
					}
				}
			});
		},
		routerTo(url) {
			uni.navigateTo({
				url
			})
		}
	},
}