// 全局过滤器
export default {
	conversion_km(val) {
		const kilometers = val / 1000;
		return parseFloat(kilometers.toFixed(2)); // 转换为字符串并保留两位小数，然后再转回数字 
	},
	// 百，千，万过滤
	num(val) {
		let num = ''
		if (val < 1000) {
			// console.log(val)
			num = val * 1
			return num
		}
		if (val >= 1000 && val < 10000) {
			num = val / 1000
			return `${num.toFixed(1)}千`
		}
		if (val >= 10000) {
			num = val / 10000
			return `${num.toFixed(1)}万`
		}
	},
	// 百，千，万过滤
	price_num(val) {
		let num = ''
		if (val < 1000) {
			// console.log(val)
			num = val * 1
			return `${num.toFixed(2)}`
		}
		if (val >= 1000 && val < 10000) {
			num = val / 1000
			return `${num.toFixed(1)}千`
		}
		if (val >= 10000) {
			num = val / 10000
			return `${num.toFixed(1)}万`
		}
	},
	// 判断订单状态
	order_status(status) {
		let type = ''
		let order_status = [
			{ status: 1, type: '待付款' },
			{ status: 2, type: '已付款' },
			{ status: 3, type: '已核销' },
			{ status: 4, type: '待退款' },
			{ status: 5, type: '已取消' },
		]
		order_status.forEach(item => {
			if (status == item.status) {
				type = item.type
			}
		})
		return type
	},
	// 隐藏手机号
	hide_tel(val) {
		console.log(val)
		return val.slice(0, 3) + '****' + val.slice(7);
	},
	// 隐藏银行卡号
	hide_card(val) {
		return val.slice(0, 4) + '****' + val.slice(-4);
	}
}