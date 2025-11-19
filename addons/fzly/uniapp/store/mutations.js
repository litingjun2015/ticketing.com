export default {
	set_menuButtonInfo(state, val) {
		state.menuButtonInfo = val
	},
	set_city(state, val) {
		state.city = val
	},
	set_lon(state, val) {
		state.lon = val
	},
	set_lat(state, val) {
		state.lat = val
	},
	set_user_info(state, val) {
		console.log(val, '修改用户信息')
		state.user_info = val
	},
	set_residence(state, val) {
		state.residence = val
	},
	set_KeyboardHeight(state, val) {
		state.KeyboardHeight = val
	},
	set_travel_id(state, val) {
		state.travel_id = val
	},
	set_travel_people(state, val) {
		state.travel_people = val
	},
	set_common(state, val) {
		console.log(val, '修改公共参数')
		state.common = val
	},
	set_jq_city(state, val) {
		state.jq_city = val
	}
}