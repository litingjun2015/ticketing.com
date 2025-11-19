// 公共api
import request from '../utils/request.js'

// 门票核销
export function hx_code(data) {
	return request({
		url: 'addons/fzly/order/hx_mp_code',
		method: 'POST',
		data
	})
}
// 门票分类列表
export function type_list(data) {
	return request({
		url: 'addons/fzly/admissionm/type_list',
		method: 'POST',
		data
	})
}
// 门票类型列表
export function type_lists(data) {
	return request({
		url: 'addons/fzly/admissionm/type_lists',
		method: 'POST',
		data
	})
}
// 门票类型列表
export function add_product(data) {
	return request({
		url: 'addons/fzly/admissionm/add_product',
		method: 'POST',
		data
	})
}
// 门票中心
export function guide_center(data) {
	return request({
		url: 'addons/fzly/admissionm/guide_center',
		method: 'POST',
		data
	})
}
// 门票产品列表
export function product_list(data) {
	return request({
		url: 'addons/fzly/admissionm/product_list',
		method: 'POST',
		data
	})
}
// 我的导游产品列表
export function product_status(data) {
	return request({
		url: 'addons/fzly/admissionm/product_status',
		method: 'POST',
		data
	})
}
// 我的导游产品价格调整
export function product_price(data) {
	return request({
		url: 'addons/fzly/admissionm/product_price',
		method: 'POST',
		data
	})
}
// 门票订单列表
export function guide_lists(data) {
	return request({
		url: 'addons/fzly/admissionm/lists',
		method: 'POST',
		data
	})
}
// 获取收入明细
export function income_detail(data) {
	return request({
		url: 'addons/fzly/admissionm/income_detail',
		method: 'POST',
		data
	})
}
// 提现记录
export function withdraw_list(data) {
	return request({
		url: 'addons/fzly/admissionm/withdraw_list',
		method: 'POST',
		data
	})
}
// 景区入驻
export function admission(data) {
	return request({
		url: 'addons/fzly/user/admission',
		method: 'POST',
		data
	})
}
// 景区城市
export function city(data) {
	return request({
		url: 'addons/fzly/common/city',
		method: 'POST',
		data
	})
}