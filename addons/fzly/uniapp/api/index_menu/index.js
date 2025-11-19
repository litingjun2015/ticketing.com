// 公共api
import request from '../../utils/request.js'

// 门票类型
export function ticket_type(data) {
	return request({
		url: 'addons/fzly/admission/ticket_type',
		method: 'POST',
		data
	})
}
// 门票列表
export function ticket_list(data) {
	return request({
		url: 'addons/fzly/admission/ticket_list',
		method: 'POST',
		data
	})
}
// 门票详情
export function ticket_detail(data) {
	return request({
		url: 'addons/fzly/admission/ticket_detail',
		method: 'POST',
		data
	})
}
// 门票详情 猜你想去
export function guess_like(data) {
	return request({
		url: 'addons/fzly/admission/guess_like',
		method: 'POST',
		data
	})
}
// 门票详情 评论
export function admission_comment(data) {
	return request({
		url: 'addons/fzly/admission/comment',
		method: 'POST',
		data
	})
}

// 获取酒店品牌
export function brand_list(data) {
	return request({
		url: 'addons/fzly/house/brand_list',
		method: 'POST',
		data
	})
}
// 获取酒店设施
export function facility_list(data) {
	return request({
		url: 'addons/fzly/house/facility_list',
		method: 'POST',
		data
	})
}
// 获取酒店档次
export function level_list(data) {
	return request({
		url: 'addons/fzly/house/level_list',
		method: 'POST',
		data
	})
}
// 获取酒店类型
export function type_list(data) {
	return request({
		url: 'addons/fzly/house/type_list',
		method: 'POST',
		data
	})
}
// 获取酒店列表
export function hotel_list(data) {
	return request({
		url: 'addons/fzly/house/hotel_list',
		method: 'POST',
		data
	})
}
// 获取酒店详情
export function house_detail(data) {
	return request({
		url: 'addons/fzly/house/house_detail',
		method: 'POST',
		data
	})
}
// 获取必游榜列表
export function must_list(data) {
	return request({
		url: 'addons/fzly/index/must_list',
		method: 'POST',
		data
	})
}
// 获取攻略，游记，美食列表
export function lists(data) {
	return request({
		url: 'addons/fzly/content/lists',
		method: 'POST',
		data
	})
}
// 获取动态，攻略，游记，美食内容
export function detail(data) {
	return request({
		url: 'addons/fzly/content/detail',
		method: 'POST',
		data
	})
}
// 点赞/取消点赞
export function dz(data) {
	return request({
		url: 'addons/fzly/content/dz',
		method: 'POST',
		data
	})
}
// 收藏/取消收藏
export function sc(data) {
	return request({
		url: 'addons/fzly/content/sc',
		method: 'POST',
		data
	})
}
// 发布评论
export function comment_api(data) {
	return request({
		url: 'addons/fzly/content/comment',
		method: 'POST',
		data
	})
}
// 获取评论列表
export function get_comment(data) {
	return request({
		url: 'addons/fzly/content/get_comment',
		method: 'POST',
		data
	})
}
// 获取导游详情
export function guide_detail(data) {
	return request({
		url: 'addons/fzly/index/guide_detail',
		method: 'POST',
		data
	})
}
// 获取导游产品详情
export function guide_info(data) {
	return request({
		url: 'addons/fzly/user/guide_info',
		method: 'POST',
		data
	})
}
// 发布动态
export function add(data) {
	return request({
		url: 'addons/fzly/content/add',
		method: 'POST',
		data
	})
}
// 删除发布
export function delete_content(data) {
	return request({
		url: 'addons/fzly/content/delete',
		method: 'POST',
		data
	})
}
// 获取他人信息
export function other_info(data) {
	return request({
		url: 'addons/fzly/user/other_info',
		method: 'POST',
		data
	})
}