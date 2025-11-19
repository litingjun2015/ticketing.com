import Vue from 'vue'
import Vuex from 'vuex'
import mutations from './mutations.js'
import actions from './actions.js'

Vue.use(Vuex);
const store = new Vuex.Store({
	state: {
		menuButtonInfo: 0, //顶部安全距离
		city: uni.getStorageSync('city') ? uni.getStorageSync('city') : '',
		lon: uni.getStorageSync('lon') ? uni.getStorageSync('lon') : '',
		lat: uni.getStorageSync('lat') ? uni.getStorageSync('lat') : '',
		user_info: uni.getStorageSync('user_info') ? uni.getStorageSync('user_info') : '',
		residence: '',
		KeyboardHeight: 0,
		travel_id: [],
		travel_people: [],
		common: uni.getStorageSync('common') ? uni.getStorageSync('common') : '', //公共参数
		jq_city: '',
	},
	mutations,
	actions
})
export default store