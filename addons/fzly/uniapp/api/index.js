// 公共api
import request from '../utils/request.js'

// 首页广告位
export function ad_list(data) {
	return request({
		url: 'addons/fzly/index/ad_list',
		method: 'POST',
		data
	})
}
// 点击广告位
export function ad_click(data) {
	return request({
		url: 'addons/fzly/index/ad_click',
		method: 'POST',
		data
	})
}
//首页菜单
export function nav_list(data) {
	return request({
		url: 'addons/fzly/index/nav_list',
		method: 'POST',
		data
	})
}

// 首页导游列表
export function guide_list(data) {
	return request({
		url: 'addons/fzly/index/guide_list',
		method: 'POST',
		data
	})
}
// 首页导游列表
export function spot_type_list(data) {
	return request({
		url: 'addons/fzly/index/spot_type_list',
		method: 'POST',
		data
	})
}
// 首页今日最热推荐
export function spot_tj(data) {
	return request({
		url: 'addons/fzly/index/spot_tj',
		method: 'POST',
		data
	})
}
// 首页游记推荐
export function travel_tj(data) {
	return request({
		url: 'addons/fzly/index/travel_tj',
		method: 'POST',
		data
	})
}
// 热门活动
export function activity_list(data) {
	return request({
		url: 'addons/fzly/index/activity_list',
		method: 'POST',
		data
	})
}
// 获取动态分类
export function trends_type(data) {
	return request({
		url: 'addons/fzly/content/trends_type',
		method: 'POST',
		data
	})
}
// 获取动态列表
export function trends(data) {
	return request({
		url: 'addons/fzly/content/trends',
		method: 'POST',
		data
	})
}
// 获取jssdk
export function mpJsdk(data) {
	return request({
		url: 'addons/fzly/user/getjssdk',
		method: 'POST',
		data
	})
}

// ----------------------------分销---------------------------------

//分销首页
export const distributionIndex = (data) => request({
	url: 'addons/fzly/distribution/index',
	method: 'post',
	data
})

//佣金流水
export const brokeragedetailed = (data) => request({
	url: 'addons/fzly/distribution/detailed',
	method: 'post',
	data
})


//海报
export const distlist = (data) => request({
	url: 'addons/fzly/distribution/distlist',
	method: 'post',
	data
})

//提现
export const draw = (data) => request({
	url: 'addons/fzly/distribution/draw',
	method: 'post',
	data
})


//提现记录
export const drawLog = (data) => request({
	url: 'addons/fzly/distribution/drawLog',
	method: 'post',
	data
})


//我的团队
export const distributionteam = (data) => request({
	url: '/addons/fzly/distribution/team',
	method: 'post',
	data
})


//我的团队列表
export const teamlist = (data) => request({
	url: '/addons/fzly/distribution/teamlist',
	method: 'post',
	data
})