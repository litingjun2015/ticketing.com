export default {
	data() {
		return {
			tabbar_list: [{
					text: '首页',
					icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/index.png',
					action_icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/index_action.png'
				},
				{ text: '发布', icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/fb.png', action_icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/fb_action.png' },
				{
					text: '订单',
					icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/order.png',
					action_icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/order_action.png'
				},
				{ text: '返回', icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/back.png', action_icon: 'https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/business/back.png' },
			],
			tz_url: [
				'/pagesB/index/index',
				'/pagesB/release/release',
				'/pagesB/order/order',
			]
		}
	},
	methods: {
		change(e) {
			if (e == 3) {
				uni.switchTab({
					url: '/pages/me/me'
				})
			} else {
				uni.redirectTo({
					url: this.tz_url[e]
				})
			}
		}
	}
}