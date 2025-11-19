import App from './App'


// #ifndef VUE3
import Vue from 'vue'
import './uni.promisify.adaptor'
import store from './store/index.js'
import uView from '@/uni_modules/uview-ui'
import globalRoute from '@/mixins/route.js'
import uploadimg from '@/mixins/add.js'
import filters from '@/utils/filters.js'
import projectUrl from 'config.js'

import wxApi from '@/utils/wx_api.js'

Object.keys(wxApi).forEach(key => {
	Vue.prototype[key] = wxApi[key]
})

// 全局过滤器
for (let key in filters) {
	Vue.filter(key, filters[key])
}
Vue.prototype.$projectUrl = projectUrl.serverIp
Vue.mixin(globalRoute)
Vue.mixin(uploadimg)
Vue.use(uView)

Vue.prototype.$store = store
Vue.config.productionTip = false
App.mpType = 'app'
const app = new Vue({
	store,
	...App
})
app.$mount()
// #endif

// #ifdef VUE3
import { createSSRApp } from 'vue'
export function createApp() {
	const app = createSSRApp(App)
	return {
		app
	}
}
// #endif