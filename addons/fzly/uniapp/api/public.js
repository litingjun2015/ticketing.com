// 公共api
import request from '../utils/request.js'

// 上传图片
export function upload(data) {
	return request({
		url: 'api/common/upload',
		method: 'POST',
		data
	})
}
// 微信登录接口
export function login(data) {
	return request({
		url: 'addons/fzly/user/login',
		method: 'POST',
		data
	})
}
// 退出登录
export function logout(data) {
	return request({
		url: 'addons/fzly/user/logout',
		method: 'POST',
		data
	})
}
// 获取用户信息
export function userInfo(data) {
	return request({
		url: 'addons/fzly/user/userInfo',
		method: 'POST',
		data
	})
}
// 修改用户信息
export function profile(data) {
	return request({
		url: 'addons/fzly/user/profile',
		method: 'POST',
		data
	})
}
// 获取城市列表
export function area(data) {
	return request({
		url: 'addons/fzly/common/area',
		method: 'POST',
		data
	})
}

// 获取协议
export function pact(data) {
	return request({
		url: 'addons/fzly/user/pact',
		method: 'POST',
		data
	})
}
// 获取公共参数
export function common(data) {
	return request({
		url: 'addons/fzly/common/common',
		method: 'POST',
		data
	})
}