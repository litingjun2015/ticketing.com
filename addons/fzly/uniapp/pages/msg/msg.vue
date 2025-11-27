<template>
	<view class="main">
		<view class="header flexc" :style="{paddingTop:menuButtonInfo+'px'}">
			<view class="">

			</view>
			<view class="headerTitle">
				消息
			</view>
			<view class="">

			</view>
		</view>
		<view class="con">
			<view class="empty_top" v-if="messageList==0">
				<u-empty text='没有消息哦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
				</u-empty>
			</view>
			<view class="" v-else>
				<u-swipe-action :autoClose='true' v-for="item in messageList" :key="item.id">
					<u-swipe-action-item v-if="item.is_top==1" :show='false' :options="options2"
						@click="actionClick($event,item)">
						<view class="conBox active"
							@click="go(`/pages/msg/msg_room?id=${item.id}&user_id=${item.user_id}&df_id=${item.df_id}`)">
							<view class="img">
								<image :src="item.df_avatar" mode="aspectFill"></image>
							</view>
							<view class="txt">
								<view class="name">
									{{item.df_user}}
								</view>
								<view class="content_msg me-text-beyond">
									{{item.last_message || '暂无消息'}}
								</view>
								<view class="time">
									{{item.last_time}}
								</view>
								<view class="number" v-if="item.unread_msg_count>0">
									{{item.unread_msg_count}}
								</view>
							</view>
						</view>
					</u-swipe-action-item>
					<u-swipe-action-item v-if="item.is_top==0" :show='false' :options="options1"
						@click="actionClick($event,item)">
						<view class="conBox unactive"
							@click="go(`/pages/msg/msg_room?id=${item.id}&user_id=${item.user_id}&df_id=${item.df_id}`)">
							<view class="img">
								<image :src="item.df_avatar" mode="aspectFill"></image>
							</view>
							<view class="txt">
								<view class="name">
									{{item.df_user}}
								</view>
								<view class="content_msg me-text-beyond">
									{{item.last_message || '暂无消息'}}
								</view>
								<view class="time">
									{{item.last_time}}
								</view>
								<view class="number" v-if="item.unread_msg_count>0">
									{{item.unread_msg_count}}
								</view>
							</view>
						</view>
					</u-swipe-action-item>
				</u-swipe-action>
			</view>
		</view>
	</view>
</template>

<script>
	// import Config from "@/config.js"; // 本地配置数据
	import Config from "@/config.js"
	import { mapState } from 'vuex'
	var token = ''; // 用户身份标识
	var fixedCsr = ''; // 指定客服
	var innerAudioContext = null;
	export default {
		computed: {
			...mapState(['menuButtonInfo'])
		},
		components: {

		},
		data() {
			return {
				tabShow: true,
				options1: [{
					btn: '置顶',
					style: {
						backgroundColor: '#FFFFFF',
					}
				}, {
					btn: '删除',
					style: {
						backgroundColor: '#FFFFFF',
					}
				}],
				options2: [{
					btn: '取消置顶',
					style: {
						backgroundColor: '#FFFFFF',
					}
				}, {
					btn: '删除',
					style: {
						backgroundColor: '#FFFFFF',
					}
				}],
				total: 0,
				list: [],
				leaveMessage: {
					name: '',
					contact: '',
					message: ''
				},
				attachBackground: '',
				showSendButton: false,
				showTool: false,
				showLeaveMessage: false,
				expressionData: [],
				config: [],
				tokenList: [],
				kefuMessage: '',
				wrapperHeight: 46,
				ws: {
					SocketTask: null,
					Timer: null, // 计时器
					ErrorMsg: [], // 发送失败的消息
					MaxRetryCount: 3, // 最大重连次数
					CurrentRetryCount: 0,
					socketOpen: false,
					pageHideCloseWs: true, // 是否在页面hide时关闭链接，兼容预览图片的情况
					unloadCloseWs: false // ws关闭状态码兼容性不好，手动标记页面卸载关闭ws链接
				},
				csr: '', // 当前用户的客服代表ID
				sessionId: 0, // 会话ID
				sessionUser: "", // 带身份标识的用户ID
				kefuMessageFocus: false,
				messageList: [],
				chatRecordPage: 1,
				errorTips: '链接中...',
				selectModel: false,
				selectModelData: [],
				writeBottom: 0,
				kefu_scroll_top: 0,
				kefu_scroll_with_animation: true,
				record_scroll_height: 0,
				navigation_bar_title: '',
				user_id: uni.getStorageSync('user_info').id || null,
				csr_id: ''
			}
		},
		onLoad(opt) {
			let token = uni.getStorageSync('token')
			// console.log(token, 'wowowowowowowoow')
			this.token = opt.token ? opt.token : token
			if (opt.tabShow) {
				this.tabShow = false
			}
			// 微信小程序端onshow时再链接，并在onhide时关闭链接
			// #ifndef MP-WEIXIN
			this.connect_socket()
			// #endif
		},
		onShow() {
			if (!this.ws.pageHideCloseWs) {
				this.ws.pageHideCloseWs = true;
			}
			// #ifdef MP-WEIXIN
			this.connect_socket()
			// #endif
		},
		onHide() {
			console.log(222)
			// #ifdef MP-WEIXIN
			if (this.ws.SocketTask && this.ws.socketOpen && this.ws.pageHideCloseWs) {
				console.log('微信小程序页面hide主动关闭链接')
				uni.closeSocket();
			}
			// #endif
		},
		onUnload() {
			// console.log(222)
			if (this.ws.SocketTask && this.ws.socketOpen) {
				console.log('页面卸载主动关闭链接')
				this.ws.unloadCloseWs = true
				uni.closeSocket();
			}
		},
		methods: {
			// 前往聊天页
			go(url) {
				uni.navigateTo({
					url
				})
			},
			actionClick(e, item) {
				let message;
				let type = 1;
				if (e.index == 0) {
					if (item.is_top) {
						type = 0
					}
					message = {
						c: 'Message',
						a: 'top',
						data: {
							session_id: item.id,
							user_id: item.user_id,
							csr_id: item.df_id,
							type
						}
					}
				} else {
					message = {
						c: 'Message',
						a: 'delSession',
						data: {
							session_id: item.id,
							user_id: item.user_id
						}
					}
				}
				this.ws_send(message)
			},

			set_navigation_bar_title: function(title, back_title = true) {
				if (back_title) {
					this.navigation_bar_title = title
				}
				uni.setNavigationBarTitle({
					title: title
				});
			},



			parseUrl: function(data) {
				var reg = new RegExp("(https?:\/\/)([0-9a-z.]+)([\?0-9a-z&=]+)?(#[0-9-a-z]+)?", "g");
				return data.replace(reg, '<a target="_blank" title="$&" class="link" href="$&">$&</a>');
			},

			// 开始链接
			connect_socket: function() {
				var that = this
				// if (this.ws.SocketTask && this.ws.socketOpen) {
				// 	console.log('无需链接')
				// 	return;
				// }
				// 开始链接
				let res = this.build_url('ws')
				that.ws.SocketTask = uni.connectSocket({
					url: this.build_url('ws'),
					header: {
						'content-type': 'application/json',
						'SERVER_NAME': 'https://fzcar2.51meteor.com/'
					},
				});

				// 链接打开
				uni.onSocketOpen(function(res) {
					console.log('链接打开', res);
					that.ws.socketOpen = true
					that.ws.CurrentRetryCount = 0;
					// 重新发送所有出错的消息
					for (let i in that.ws.ErrorMsg) {
						that.ws_send(that.ws.ErrorMsg[i]);
					}
					that.ws.ErrorMsg = [];
					that.errorTips = '';

					if (that.ws.Timer != null) {
						clearInterval(that.ws.Timer);
					}

					that.ws.Timer = setInterval(that.ws_send, 28000); //定时发送心跳
				});

				// 收到消息
				uni.onSocketMessage(function(res) {
					let msg = JSON.parse(res.data)
					console.log('// 收到消息', msg);
					if (msg.msgtype == 'session_list') {
						that.total = msg.total
						if (msg.total > 0) {
							uni.setTabBarBadge({
								index: 3,
								text: msg.total.toString()
							})
						} else {
							uni.removeTabBarBadge({
								index: 3
							})
						}
						if (msg.data.length > 0) {
							that.messageList = msg.data
							that.$forceUpdate()
						}
					}
					if (msg.msgtype == "new_message" || msg.msgtype == "top" || msg.msgtype == "delSession") {
						msg.msgtype = "initialize"
					}
					let actions = that.domsg()
					let action = actions[msg.msgtype] || actions['default']
					action.call(this, msg)
				})

				// 出错
				uni.onSocketError(function(res) {
					that.ws.socketOpen = false;
					that.errorTips = 'WebSocket 发生错误!'
					console.log('链接出错', res)
				});

				// 链接关闭
				uni.onSocketClose(function(res) {
					// 工具上是1000，真机上测试是10000
					console.log('链接已关闭', res)
					that.ws.socketOpen = false;

					if (that.ws.Timer != null) {
						clearInterval(that.ws.Timer);
					}

					if (that.errorTips.indexOf('重连') === -1) {
						that.errorTips = '网络链接已断开!';
					}

					if (res.code == 1000 || res.code == 10000 || that.ws.unloadCloseWs) {
						return;
					}

					if (that.ws.MaxRetryCount) {
						that.ws.Timer = setInterval(that.retry_websocket, 3000); //每3秒重新连接一次
					}
				});
			},

			// 重连ws
			retry_websocket: function() {
				var that = this
				if (that.ws.CurrentRetryCount < that.ws.MaxRetryCount) {
					that.ws.CurrentRetryCount++;
					that.connect_socket();
					that.errorTips = '重连WebSocket第' + that.ws.CurrentRetryCount + '次';
				} else {
					if (that.ws.Timer != null) {
						clearInterval(that.ws.Timer);
					}

					that.errorTips = '每隔10秒将再次尝试重连 WebSocket';
					that.ws.Timer = setInterval(that.connect_socket, 10000); //每10秒重新连接一次
				}
			},
			// 发送ws消息
			ws_send: function(message) {
				var that = this
				if (!message) {
					message = {
						c: 'Message',
						a: 'ping'
					};
				}

				if (that.ws.SocketTask && that.ws.socketOpen) {
					uni.sendSocketMessage({
						data: JSON.stringify(message)
					});
				} else {
					that.ws.ErrorMsg.push(message);
				}
			},

			send_message: function(message, message_type) {
				var that = this
				if (message == '') {
					uni.showToast({
						title: '请输入消息内容',
						icon: 'none'
					})
					return;
				}
				// 检查 websocket 是否连接
				if (!that.ws.SocketTask || !that.ws.socketOpen) {
					uni.showToast({
						title: '网络链接异常，请稍后重试~',
						icon: 'none'
					})
					return;
				}

				if (!that.sessionId) {
					uni.showToast({
						title: '初始化未完成(会话获取失败)~',
						icon: 'none'
					})
					return;
				}

				if (message_type == 0) {
					// 处理链接
					message = that.parseUrl(message);

					// 处理表情
					var reg = /\[(.+?)\]/g; // [] 中括号
					var reg_match = message.match(reg);

					if (reg_match) {

						for (let i in reg_match) {
							var emoji_item = that.find_emoji(reg_match[i]);
							message = message.replace(emoji_item.title, that.build_chat_img(emoji_item.src, '',
								'emoji'));
						}
					}

				}

				let message_data = that.messageList[that.messageList.length - 1].data;
				var message_id = parseInt(message_data[message_data.length - 1].id) + 1;
				// var message_id = new Date().getTime() + that.sessionId + Math.floor(Math.random() * 10000);
				var load_message = {
					c: 'Message',
					a: 'sendMessage',
					data: {
						message: message,
						message_type: message_type,
						session_id: that.sessionId,
						modulename: 'index',
						message_id: message_id
					}
				};

				that.ws_send(load_message);

				var data = {
					id: message_id,
					status: 2, // 标记待发送状态
					sender: 'me',
					message: (message_type == 1 || message_type == 2) ? that.config.upload.cdnurl + message :
						message,
					message_type: message_type
				}

				var messageListIndex = that.messageList.length - 1
				if (that.messageList[messageListIndex] && that.messageList[messageListIndex].datetime == '刚刚') {
					that.messageList[messageListIndex].data = that.messageList[messageListIndex].data.concat(that
						.format_message([
							data
						]))
				} else {
					that.messageList = that.messageList.concat({
						datetime: '刚刚',
						data: that.format_message([data])
					});
				}

				that.kefuMessage = ''
			},
			find_emoji: function(emoji_title) {
				for (let i in this.expressionData) {
					if (this.expressionData[i].title == emoji_title) {
						return this.expressionData[i];
					}
				}

				return false;
			},
			build_chat_img: function(filename, facename, class_name = 'emoji') {
				var protocol = Config.https_switch ? 'https://' : 'http://';
				if (class_name == 'emoji') {
					return '<img class="emoji" src="' + filename + '" />';
				} else {
					return '<img class="' + class_name + '" src="' + filename + '" />';
				}
			},
			// 记录用户填写的留言数据，方便后续清空输入等处理
			leave_message: function(e) {
				if (e.currentTarget.id == 'c-name') {
					this.leaveMessage.name = e.detail.value
				} else if (e.currentTarget.id == 'c-contact') {
					this.leaveMessage.contact = e.detail.value
				} else if (e.currentTarget.id == 'c-message') {
					this.leaveMessage.message = e.detail.value
				}
			},

			domsg: function(msg) {
				console.log('========')
				var that = this
				return {
					default: (msg) => {
						if (msg.msgtype == "session_list") {
							this.messageList = msg.data
						}
					},
					initialize: (msg) => {
						if (msg.data) {
							if (msg.data.new_msg) {
								that.new_message_prompt()
							}
						}
						var user_initialize = {
							c: 'Message',
							a: 'sessionList',
							data: {
								user_id: uni.getStorageSync('user_info').id
							}
						};
						that.ws_send(user_initialize);
					},


					new_message: (msg) => {
						that.new_message_prompt();
					},

					send_message: (msg) => {
						if (!msg.data.message_id) {
							return;
						}

						var messageListIndex = that.messageList.length - 1
						for (let i in that.messageList[messageListIndex].data) {
							if (that.messageList[messageListIndex].data[i].id == msg.data.message_id) {
								that.messageList[messageListIndex].data[i].status = (msg.code == 1) ? 0 : 3;
								that.messageList[messageListIndex].data[i].id = msg.data.id
							}
						}

						if (msg.code == 0) {
							uni.showToast({
								title: msg.data.msg,
								icon: 'none'
							})
						}
					},

				};
			},

			build_url: function(type = 'ws', res_path) {
				var that = this
				var protocol = Config.https_switch ? 'https://' : 'http://';
				var token = uni.getStorageSync('token')
				var buildObj = {
					ws: () => {
						protocol = Config.wss_switch == true ? 'wss://' : 'ws://';
						return protocol + Config.baseURL + ":" + Config.httPort + "?token=" + token
					},
					initialize: () => {},
					default: () => {
						return protocol + Config.baseURL;
					}
				};

				let action = buildObj[type] || buildObj['default']
				return action.call(this)
			},

			new_message_prompt: function() {
				if (innerAudioContext) {
					innerAudioContext.play();
					setTimeout(function() {
						innerAudioContext.stop();
					}, 1000)
				} else {
					console.error('来信提示音播放失败！');
				}
			},
			format_message: function(message) {
				for (let i in message) {
					if (message[i].message_type == 4 || message[i].message_type == 5) {
						var message_arr = message[i].message.split('#');
						var message_obj = {};

						for (let y in message_arr) {
							let message_temp = message_arr[y].split('=');
							if (typeof message_temp[1] != 'undefined') {
								message_obj[message_temp[0]] = message_temp[1];
							}
						}
						message[i].message = message_obj;
					}
				}
				return message;
			}
		}
	}
</script>

<style lang="scss" scoped>
	.main {
		min-height: 100vh;
		background-color: #fff;
	}

	.header {
		background-color: #FFFFFF;
		// height: 10vh;
		padding: 3vh 30rpx 0;
		padding-bottom: 30rpx;
		box-sizing: border-box;
		font-size: 34rpx;

		.headerTitle {
			// width: 100vw;
			// text-align: center;
			font-weight: 400;
			font-size: 48rpx;
			color: #000000;
		}
	}

	.con {
		.active {
			background: #f5f5f5;
		}

		// padding: 0 30rpx;
		.conBox {
			display: flex;
			// margin-bottom: 40rpx;
			align-items: center;
			padding: 20rpx 30rpx;
			box-sizing: border-box;


			.img {
				width: 72rpx;
				height: 72rpx;
				border-radius: 50%;
				background-color: teal;
				margin-right: 16rpx;

				image {
					width: 100%;
					height: 100%;
					border-radius: 50%;
				}
			}

			.txt {
				// width: 100%;
				flex: 1;
				position: relative;
				padding-top: 8rpx;

				.name {
					font-weight: 400;
					font-size: 28rpx;
					color: #232323;
					// margin-bottom: 16rpx;
				}

				.content_msg {
					font-weight: 400;
					font-size: 22rpx;
					color: #666666;
					width: 500rpx;
					margin-top: 15rpx;
				}

				.time {
					position: absolute;
					right: 0;
					top: 14rpx;
					color: #9A9FA1;
					font-size: 24rpx;
				}

				.number {
					position: absolute;
					right: 0;
					bottom: 0rpx;
					width: 34rpx;
					height: 34rpx;
					text-align: center;
					line-height: 34rpx;
					color: #fff;
					background-color: #EB702D;
					border-radius: 50%;
					font-size: 20rpx;
				}
			}
		}
	}

	.flexc {
		display: flex;
		align-items: center;
	}

	.flexs {
		display: flex;
		justify-content: space-between;
	}
</style>