export default {
	// 服务的地址
	serverIp: 'https://qlx.udu100.cn/',
	baseURL: 'qlx.udu100.cn', // 启动workerman服务的域名，无需填写协议和端口
	https_switch: true, // 是否启用https协议(默认关，正式版必开，且需要参考文档创建wss服务)
	wss_switch: true, // 是否启用https协议(默认关，正式版必开，且需要参考文档创建wss服务)
	httPort: '' // HTTP端口，若为默认的80端口则无需填写，请注意是`http`端口，不是`ws`端口，ws请在后台插件配置中进行配置
	// 无需登录的api
}