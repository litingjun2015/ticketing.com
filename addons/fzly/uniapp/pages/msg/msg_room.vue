<template>
	<view class="box content">
		<view class="chat_list">
			<view class="box_1" v-for="item in messageList" :key="item.id">
				<view class="box_1_time">
					{{item.datetime}}
				</view>
				<view class="box_1_message" v-for="(item1,idx1) in item.data" :key="idx1">
					<view class="flexc box_1_message_item" v-if="item1.sender=='you'">
						<image class="box_1_message_head_img" :src="item1.session_user.avatar" mode=""></image>
						<view class="box_1_message_text">
							<image class="box_1_message_text_img" src="@/static/img.png" mode=""></image>
							<view class="">
								<rich-text :nodes="item1.message"></rich-text>
							</view>
						</view>
					</view>
					<view class="flexc flexEnd box_1_message_item" v-if="item1.sender=='me'">
						<view class="box_1_message_text_1">
							<image class="box_1_message_text_img_1" src="@/static/img1.png" mode=""></image>
							<view class="">
								<rich-text :nodes="item1.message"></rich-text>
							</view>
							<!-- 	<image v-if="item1.show" class="box_1_message_close_img" src="@/static/laji.png"
								mode="aspectFill">
							</image> -->
						</view>
						<image class="box_1_message_head_img_1" :src="item1.user.avatar" mode=""></image>
					</view>
				</view>
			</view>
		</view>
		<view class="" :style="{height:(bomHeg+50)+'px'}"></view>
		<view class="ipt_box flexc" :style="{bottom:bomHeg+'px'}">
			<u--textarea cursor-spacing="500" :disableDefaultPadding='false' :showConfirmBar='false' @blur='bomHeg=0'
				:adjustPosition='false' v-model="textareaValue" height='70rpx' :autoHeight='true' maxlength='300'
				placeholder="请输入内容"></u--textarea>
			<view class="ipt_box_btn" @click="confirm()">
				发送
			</view>
		</view>
	</view>
</template>

<script>
	// import Config from "@/config.js"; // 本地配置数据
	import Config from "@/config.js"
	var token = ''; // 用户身份标识
	var fixedCsr = ''; // 指定客服
	var innerAudioContext = null;
	export default {
		data() {
			return {
				bomHeg: 0,
				scrollTop: 0,
				textareaValue: '',
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
				session_id: '',
				user_id: '',
				csr_id: '',
				user_avatar: ''
			}
		},
		onLoad(opt) {
			uni.onKeyboardHeightChange(res => {
				if (res.height > 300) {
					this.bomHeg = res.height
				}
			})

			this.connect_socket()
			this.csr_id = opt.df_id
			if (opt.id) {
				this.sessionId = opt.id
				this.user_id = opt.user_id
			} else {
				this.user_id = uni.getStorageSync('user_info').id
				this.setSessionList()
			}

			this.user_avatar = uni.getStorageSync('user_info').avatar
			let token = uni.getStorageSync('token')
			this.token = opt.token ? opt.token : token
			this.fixedCsr = opt.fixed_csr ? opt.fixed_csr : fixedCsr
			// 微信小程序端onshow时再链接，并在onhide时关闭链接
			// #ifndef MP-WEIXIN

			// #endif

		},
		onShow() {
			if (!this.ws.pageHideCloseWs) {
				this.ws.pageHideCloseWs = true;
			}

		},
		onHide() {
			// #ifdef MP-WEIXIN
			if (this.ws.SocketTask && this.ws.socketOpen && this.ws.pageHideCloseWs) {
				console.log('微信小程序页面hide主动关闭链接')
				uni.closeSocket();
			}
			// #endif
		},
		onUnload() {
			uni.offKeyboardHeightChange();
			if (this.ws.SocketTask && this.ws.socketOpen) {
				console.log('页面卸载主动关闭链接')
				this.ws.unloadCloseWs = true
				uni.closeSocket();
			}
		},
		methods: {

			setSessionList() {
				let userID = uni.getStorageSync('user_info').id
				let message = {
					c: 'Message',
					a: 'getSessionId',
					data: {
						user_id: userID,
						csr_id: this.csr_id
					}
				}
				this.ws_send(message)
			},
			getscroll() {
				uni.createSelectorQuery().select(".chat_list").boundingClientRect((res) => {
					console.log(res, '---res')
					let newbottom = res.bottom + res.height
					uni.pageScrollTo({
						duration: 100, // 过渡时间
						scrollTop: newbottom + 2000, // 滚动的实际距离
					})
				}).exec();
			},
			confirm() {
				this.send_message(this.textareaValue, 0)
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

				that.ws.SocketTask = uni.connectSocket({
					url: this.build_url('ws'),
					header: {
						'content-type': 'application/json',
						'SERVER_NAME': 'https://fzcar2.51meteor.com/'
					},
					complete: res => {}
				});

				// 链接打开
				uni.onSocketOpen(function(res) {
					console.log('链接已打开', res)
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
					console.log('收到消息', msg);
					if (msg.msgtype == 'new_message') {
						that.messageList[that.messageList.length - 1].data.push({
							sender: 'you',
							message: msg.data.message,
							session_user: {
								avatar: msg.data.session_user.avatar
							}
						})
						console.log('this.messageList===>', that.messageList);
					} else if (msg.msgtype == 'getSessionId') {
						that.sessionId = msg.data.session_id
						that.user_id = uni.getStorageSync('user_info').id
						msg.msgtype = "initialize"
						console.log('msg.msgtype', msg.msgtype);
					} else {
						console.log('that.messageList', that.messageList);
						if (msg.data) {
							if (msg.data.chat_record) {
								that.messageList = msg.data.chat_record
								console.log('that.messageList', that.messageList);
							}
						}
					}
					that.getscroll()
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
						data: JSON.stringify(message),
					});
				} else {
					console.log('消息发送出错', message, that.ws.SocketTask, that.ws.socketOpen)
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
				console.log(that.messageList)
				let message_data = that.messageList[that.messageList.length - 1].data;
				console.log('message_data', message_data);
				var message_id = message_data.length > 0 ? parseInt(message_data[message_data.length - 1].id) + 1 : ''

				let load_message = {
					c: 'Message',
					a: 'sendMessage',
					data: {
						session_id: that.sessionId,
						message: message,
						message_type: message_type,
						user_id: that.user_id
					}
				};
				that.ws_send(load_message);

				var data = {
					id: message_id,
					status: 2, // 标记待发送状态
					sender: 'me',
					message: (message_type == 1 || message_type == 2) ? that.config.upload.cdnurl + message :
						message,
					message_type: message_type,
					user: {
						avatar: that.user_avatar
					}
				}

				var messageListIndex = that.messageList.length - 1
				if (that.messageList[messageListIndex] && that.messageList[messageListIndex].datetime == '刚刚') {
					that.messageList[messageListIndex].data = that.messageList[messageListIndex].data.concat(that
						.format_message([
							data
						]))
					console.log('<++++++==>', that.messageList);
				} else {
					console.log('data==>', data);
					that.messageList = that.messageList.concat({
						datetime: '刚刚',
						data: that.format_message([data])
					});
				}
				that.textareaValue = ''
				that.scroll_into_footer(200, 99992)
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

			domsg: function(msg) {
				var that = this
				return {
					default: (msg) => {
						console.log('default', msg);
					},
					initialize: (msg) => {
						if (msg.data.new_msg) {
							that.new_message_prompt()
						}

						var user_initialize = {
							c: 'Message',
							a: 'chatRecord',
							data: {
								session_id: that.sessionId,
								user_id: that.user_id,
								csr_id: that.csr_id
							}
						};
						that.ws_send(user_initialize);
					},
					user_initialize: (msg) => {
						// 用户客服分配结束
						if (msg.code == 1) {
							if (msg.data.session.user_tourists) {
								that.send_message = function() {
									uni.showToast({
										title: '为保护您的隐私请,请登录后发送消息~',
										icon: 'none'
									})
								}
							}
							that.csr = msg.data.session.csr;
							that.sessionId = msg.data.session.id;
							that.showLeaveMessage = false
							that.set_navigation_bar_title('客服 ' + msg.data.session.nickname + ' 为您服务')
						} else if (msg.code == 302) {

							if (!that.csr) {

								// 打开留言板
								that.csr = 'none';
								that.showLeaveMessage = true
								that.set_navigation_bar_title('当前无客服在线哦~')
							} else {
								uni.showToast({
									title: '当前客服暂时离开,您可以直接发送离线消息~',
									icon: 'none'
								})
							}

						}
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
				var token = uni.getStorageSync('token');
				var buildObj = {
					ws: () => {
						protocol = Config.wss_switch == true ? 'wss://' : 'ws://';
						// protocol = 'wss://'
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
			scroll_into_footer: function(timeout = 0, kefu_scroll_top = 0) {
				var that = this
				if (kefu_scroll_top) {
					setTimeout(function() {
						that.kefu_scroll_top = (that.kefu_scroll_top > 99990) ? that.kefu_scroll_top + 200 :
							kefu_scroll_top
					}, timeout)
					return;
				}
				let kefu_scroll = uni.createSelectorQuery().select('#kefu_scroll');
				kefu_scroll.fields({
					scrollOffset: true
				}, data => {
					setTimeout(function() {
						that.kefu_scroll_top = data.scrollHeight
					}, timeout)
				}).exec()
			},


			format_message: function(message) {
				console.log('message', message);
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
	page {
		background-color: #F6F6F6;

	}

	.box {
		padding: 0 48rpx;
		padding-top: 30rpx;
		box-sizing: border-box;
		background-color: #F6F6F6;
		min-height: 100vh;
	}

	.box_1 {
		.box_1_time {
			text-align: center;
			// margin: 28rpx 0;
			margin-bottom: 30rpx;
			font-size: 28rpx;
			color: #666666;
		}

		.box_1_message {
			.box_1_message_item {
				margin: 20rpx 0;
			}



			.box_1_message_close_img {
				width: 40rpx;
				height: 40rpx;
				position: absolute;
				top: -36rpx;
				left: 50%;
				transform: translate(-50%, -50%);
			}

			.box_1_message_text {
				padding: 12rpx 26rpx;
				background-color: #fff;
				font-size: 28rpx;
				position: relative;
				border-radius: 10rpx;
				max-width: 70vw;
			}

			.box_1_message_text_1 {
				padding: 12rpx 20rpx;
				background-color: #518BDD;
				font-size: 28rpx;
				border-radius: 10rpx;
				position: relative;
				color: #fff;
				max-width: 70vw;


				.box_1_message_text_img_1 {
					width: 20rpx;
					height: 20rpx;
					position: absolute;
					right: -20rpx;
					top: 50%;
					transform: translate(-50%, -50%);
				}


			}

			.box_1_message_head_img_1 {
				width: 58rpx;
				height: 58rpx;
				background: #D8D8D8;
				border-radius: 50%;
				margin-left: 20rpx;
			}

			.box_1_message_head_img {
				width: 58rpx;
				height: 58rpx;
				background: #D8D8D8;
				border-radius: 50%;
				margin-right: 20rpx;
			}

			.box_1_message_text_img {
				width: 20rpx;
				height: 20rpx;
				position: absolute;
				left: -6rpx;
				top: 50%;
				transform: translate(-50%, -50%);
			}


		}
	}

	.ipt_box {
		max-height: 400rpx;
		padding: 30rpx 34rpx;
		padding-bottom: 40rpx;
		box-sizing: border-box;
		background-color: #fff;
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;
		display: flex;
		align-items: center;


		.ipt_box_btn {
			color: #fff;
			width: 123rpx;
			height: 70rpx;
			background: #00C2B5;
			border-radius: 8rpx;
			text-align: center;
			line-height: 70rpx;
			margin-left: 16rpx;
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

	@import "@/utils/default.scss"
</style>