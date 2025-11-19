// 公共api
import request from '../utils/request.js'

// 关注/取消关注用户
export function follow(data) {
	return request({
		url: 'addons/fzly/user/follow',
		method: 'POST',
		data
	})
}
// 出行人列表
export function traveler_list(data) {
	return request({
		url: 'addons/fzly/user/traveler_list',
		method: 'POST',
		data
	})
}
// 增加出行人列表
export function add_traveler(data) {
	return request({
		url: 'addons/fzly/user/add_traveler',
		method: 'POST',
		data
	})
}
// 修改出行人列表
export function edit_traveler(data) {
	return request({
		url: 'addons/fzly/user/edit_traveler',
		method: 'POST',
		data
	})
}
// 计算商品价格
export function before(data) {
	return request({
		url: 'addons/fzly/order/before',
		method: 'POST',
		data
	})
}
// 创建订单
export function create(data) {
	return request({
		url: 'addons/fzly/order/create',
		method: 'POST',
		data
	})
}
// 获取openid
export function get_openid(data) {
	return request({
		url: 'addons/fzly/user/get_openid',
		method: 'POST',
		data
	})
}
// 获取openid
export function pay(data) {
	return request({
		url: 'addons/fzly/order/pay',
		method: 'POST',
		data
	})
}
// 获取订单列表
export function lists(data) {
	return request({
		url: 'addons/fzly/order/lists',
		method: 'POST',
		data
	})
}
// 取消订单
export function cancel(data) {
	return request({
		url: 'addons/fzly/order/cancel',
		method: 'POST',
		data
	})
}
// 退款订单
export function refund(data) {
	return request({
		url: 'addons/fzly/order/refund',
		method: 'POST',
		data
	})
}
// 核销二维码
export function hx_code(data) {
	return request({
		url: 'addons/fzly/order/hx_code',
		method: 'POST',
		data
	})
}
// 订单评论
export function order_comment(data) {
	return request({
		url: 'addons/fzly/order/comment',
		method: 'POST',
		data
	})
}
// 意见反馈
export function feedback(data) {
	return request({
		url: 'addons/fzly/user/feedback',
		method: 'POST',
		data
	})
}
// 优惠券列表
export function coupon_list(data) {
	return request({
		url: 'addons/fzly/coupon/coupon_list',
		method: 'POST',
		data
	})
}
// 领取优惠券
export function draw(data) {
	return request({
		url: 'addons/fzly/coupon/draw',
		method: 'POST',
		data
	})
}
// 我的优惠券列表
export function me_coupon_list(data) {
	return request({
		url: 'addons/fzly/coupon/list',
		method: 'POST',
		data
	})
}
// 可用优惠券列表
export function usable(data) {
	return request({
		url: 'addons/fzly/coupon/usable',
		method: 'POST',
		data
	})
}

// 申请导游
export function apply(data) {
	return request({
		url: 'addons/fzly/user/apply',
		method: 'POST',
		data
	})
}
// 账号绑定
export function binding(data) {
	return request({
		url: 'addons/fzly/user/binding',
		method: 'POST',
		data
	})
}
// 解除绑定
export function remove(data) {
	return request({
		url: 'addons/fzly/user/remove',
		method: 'POST',
		data
	})
}
// 获取我的收藏喜欢
export function my_like(data) {
	return request({
		url: 'addons/fzly/user/my_like',
		method: 'POST',
		data
	})
}

// 获取发布产品类型
export function product_type(data) {
	return request({
		url: 'addons/fzly/guide/get_product_type',
		method: 'POST',
		data
	})
}
// 获取发布产品车型
export function product_car(data) {
	return request({
		url: 'addons/fzly/guide/get_product_car',
		method: 'POST',
		data
	})
}
// 获取发布产品时长
export function product_time(data) {
	return request({
		url: 'addons/fzly/guide/get_product_time',
		method: 'POST',
		data
	})
}
// 发布导游产品
export function add_product(data) {
	return request({
		url: 'addons/fzly/guide/add_product',
		method: 'POST',
		data
	})
}
// 我的导游产品列表
export function product_list(data) {
	return request({
		url: 'addons/fzly/guide/product_list',
		method: 'POST',
		data
	})
}
// 我的导游产品列表
export function product_status(data) {
	return request({
		url: 'addons/fzly/guide/product_status',
		method: 'POST',
		data
	})
}
// 我的导游产品价格调整
export function product_price(data) {
	return request({
		url: 'addons/fzly/guide/product_price',
		method: 'POST',
		data
	})
}
// 导游中心
export function guide_center(data) {
	return request({
		url: 'addons/fzly/guide/guide_center',
		method: 'POST',
		data
	})
}
// 获取收入明细
export function income_detail(data) {
	return request({
		url: 'addons/fzly/guide/income_detail',
		method: 'POST',
		data
	})
}
// 获取搜索记录
export function history(data) {
	return request({
		url: 'addons/fzly/user/history',
		method: 'POST',
		data
	})
}
// 删除搜索记录
export function delHistory(data) {
	return request({
		url: 'addons/fzly/user/delHistory',
		method: 'POST',
		data
	})
}
// 添加提现账户
export function add_withdrawal_account(data) {
	return request({
		url: 'addons/fzly/guide/add_withdrawal_account',
		method: 'POST',
		data
	})
}
// 商家订单列表
export function guide_lists(data) {
	return request({
		url: 'addons/fzly/guide/lists',
		method: 'POST',
		data
	})
}
// 获取提现账号列表
export function account_list(data) {
	return request({
		url: 'addons/fzly/guide/withdrawal_account_list',
		method: 'POST',
		data
	})
}
// 提现
export function withdraw(data) {
	return request({
		url: 'addons/fzly/guide/withdraw',
		method: 'POST',
		data
	})
}
// 提现记录
export function withdraw_list(data) {
	return request({
		url: 'addons/fzly/guide/withdraw_list',
		method: 'POST',
		data
	})
}
// 提现账号解绑
export function del_account(data) {
	return request({
		url: 'addons/fzly/guide/del_withdrawal_account',
		method: 'POST',
		data
	})
}
// 获取评论标签
export function get_evaluate_tags(data) {
	return request({
		url: 'addons/fzly/order/get_evaluate_tags',
		method: 'POST',
		data
	})
}
// 公众号登录
export function loginWxOfficial(data) {
	return request({
		url: 'addons/fzly/user/loginWxOfficial',
		method: 'POST',
		data
	})
}