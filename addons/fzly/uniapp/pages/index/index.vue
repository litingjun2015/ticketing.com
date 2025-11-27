<template>
	<view class="content font_family">
		<!-- :style="{backgroundImage:`url(https://img95.699pic.com/photo/50773/0353.jpg_wh300.jpg)`,paddingTop:menuButtonInfo+'px'}" -->
		<view class="headr">
			<view class="search h5_search f_z_b" @click="go('/pagesA/index_menu/search')"
				:style="{top:menuButtonInfo+'px'}">
				<view class="search_left f_zj">
					<view class="city" @click.stop="go('/pagesA/set/address?type=query')">
						{{city}}
					</view>
					<image @click.stop="go('/pagesA/set/address?type=query')" class="icon"
						src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/vector.png" mode=""></image>
					<view class="line">

					</view>
					<view class="f_j">
						<image class="magnif" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/magnif.png" mode=""></image>
					</view>
				</view>
				<view class="pl">
					搜一搜吧~
				</view>
				<view class="search_right f_zj">
					搜索
				</view>
			</view>
			<u-swiper radius='0' keyName="image" indicatorMode='line' indicator :list="swiper_list" height="400rpx"
				:indicatorStyle='indicatorStyle' @click="ad_click" circular></u-swiper>
		</view>
		<!-- 内容 -->
		<view class="content_box">
			<!-- 菜单 -->
			<u-grid col='4'>
				<u-grid-item v-for="(item,index) in meun_list" :key="index" @click="go_menu(item.url)">
					<view class="grid_item f_zj">
						<image class="grid_icon" :src="item.image" mode=""></image>
						<text class="grid_text">{{item.title}}</text>
					</view>
				</u-grid-item>
			</u-grid>
			<!-- 热门导游 -->
			<view class="guide_box" v-if="guide_list">
				<view class="box_title f f_z_b">
					<view class="left f_d">
						<text class="text">热门导游</text>
						<view class="line">

						</view>
					</view>
					<view class="right" @click="go_more">
						<text class="text">更多</text>
						<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/more.png" mode=""></image>
					</view>
				</view>
				<!-- 导游卡片 -->
				<view class="hot_guide_box f_z_b" v-if="guide_list.length!==0">
					<guidecard v-for="(item,index) in guide_list" :key="index" :item='item'></guidecard>
				</view>
				<view class="" v-else>
					<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
					</u-empty>
				</view>
			</view>
			<!-- 推荐 -->
			<view class="recommend">
				<view class="recommend_menu">
					<scroll-view class="tab_box" scroll-x="true">
						<!-- <view class="tabs_box"> -->
						<view class="tab_item " v-for="(item,index) in tab_list" :key="index"
							@click="menu_chack(index,item.id)">
							<view class="f_d f_j">
								<view :class="[menu_action==index?'menu_name_action':'menu_name']">
									{{item.title}}
								</view>
								<view class="line" v-if="menu_action==index">

								</view>
							</view>
						</view>
						<!-- </view> -->
					</scroll-view>
				</view>
				<!-- 内容 最热 -->
				<view class="hot" v-if="hot_msg" @click="go('/pagesA/index_menu/tickets_detail?id='+hot_msg.id)">
					<view class="img_box">
						<view class="hot_text">
							今日最热
						</view>
						<image class="hot_img" :src="hot_msg.image" mode="aspectFill">
						</image>
						<view class="hot_swiper">
							<swiper class="swiper_box" vertical :indicator-dots="false" circular :autoplay="true"
								:interval="3000" :duration="1000">
								<swiper-item v-for="(item,index) in hot_msg.tj_user" :key="index">
									<view class="swiper-item f_j">
										<u-avatar :src="item.avatar" size="15"></u-avatar>
										<text class="text">最好的推荐</text>
									</view>
								</swiper-item>
							</swiper>
						</view>
					</view>
					<view class="tip_box">
						<view class="tip_title f_j">
							<view class="title_text">{{hot_msg.title}}</view>
							<view class="tip_icon f_zj">
								<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/index/go_hot.png" mode=""></image>
							</view>
						</view>
						<view class="tip_content">
							{{hot_msg.desc}}
						</view>
					</view>
				</view>
				<!-- 列表 -->
				<view class="list f_z_b">
					<placecard type='1' :item='item' v-for="(item,index) in travel_tj_list" :key="index"></placecard>
				</view>
				<view class="" v-if="travel_tj_list.length==0 && hot_msg===null">
					<u-empty text='没有数据啦~' icon="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/kong.png">
					</u-empty>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import { common, userInfo } from '@/api/public.js'
	import Config from "@/config.js"
	import guidecard from '@/components/guide_card.vue'
	import placecard from '@/components/place_card.vue'
	import { ad_list, nav_list, guide_list, spot_type_list, spot_tj, travel_tj, ad_click } from "@/api/index.js"
	import { mapState, mapMutations } from 'vuex'
	import { get_comment } from '../../api/index_menu'
	import { loginWxOfficial } from '@/api/user.js'
	export default {
		computed: {
			...mapState(['city', 'user_info', ])
		},
		components: {
			guidecard,
			placecard
		},
		data() {
			return {
				// 胶囊
				menuButtonInfo: 0,
				swiper_list: [],
				indicatorStyle: {
					bottom: '60rpx'
				},
				// 导游列表
				guide_list: [],
				// 菜单栏
				meun_list: [],
				tab_list: [],
				menu_action: 0,
				spot_tj_data: {
					type_id: ''
				},
				hot_msg: '',
				travel_tj_list: [],

				// websocket参数
				config: [],
				total: 0,
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
			}
		},
		onHide() {
			// #ifdef MP-WEIXIN
			if (this.ws.SocketTask && this.ws.socketOpen && this.ws.pageHideCloseWs) {
				console.log('微信小程序页面hide主动关闭链接')
			}
			uni.closeSocket();
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
		onShareAppMessage() {
			return {
				path: `/pages/index/index?pid=${this.user_info.id}`
			}
		},
		onShareTimeline() {
			return {
				path: `/pages/index/index?pid=${this.user_info.id}`
			}
		},
		onShow() {
			if (this.user_info) {
				// 用户登录后再进行连接
				this.connect_socket()
			}
			// this.get_spot_tj()
			this.get_travel_tj()
		},
		onLoad(e) {
			// #ifdef MP-WEIXIN
			this.menuButtonInfo = uni.getMenuButtonBoundingClientRect().top
			// #endif
			// #ifdef H5
			this.menuButtonInfo = 20
			// #endif
			console.log(e)
			this.get_common(e)

			this.get_ad_list()
			this.get_menu_list()
			this.get_guide_list()
			this.get_spot_type_list()


		},
		watch: {
			// 监听用户选择城市变化
			city(newval, oldval) {
				this.get_guide_list()
				this.get_spot_tj()
				this.get_travel_tj()
			},
			// common(val, old) {
			// 	if (val.auth_type == 0) {
			// 		this.get_code()
			// 	}
			// }
		},
		methods: {
			// 点击广告位
			ad_click(e) {
				ad_click({ id: this.swiper_list[e].id }).then(res => {
					if (res.code == 1) {
						uni.navigateTo({
							url: this.swiper_list[e].tz_url,
							success: () => {

							},
							fail: (ee) => {
								console.log(ee)
								uni.switchTab({
									url: this.swiper_list[e].tz_url,
								})
							}
						})
					}
				})
			},
			getUrlCode() {
				var url = location.search
				var theRequest = new Object()
				if (url.indexOf("?") != -1) {
					var str = url.substr(1)
					var strs = str.split("&")
					for (var i = 0; i < strs.length; i++) {
						theRequest[strs[i].split("=")[0]] = (strs[i].split("=")[1])
					}
				}
				console.log(theRequest, '111')
				return theRequest
			},
			async get_common(e) {
				let data = await common()
				if (data.code == 1) {
					// 公共参数存入缓存，vuex
					this.$store.commit('set_common', data.data)
					uni.setStorageSync('common', data.data)
				}
				// #ifdef H5
				let common_val = uni.getStorageSync('common')
				let code = this.getUrlCode().code
				// console.log(code, '=====================')
				if (code) {
					loginWxOfficial({ code }).then(res => {
						if (res.code == 1) {
							uni.setStorageSync('token', res.data.userinfo.token)
							userInfo().then(res => {
								// console.log(res, '用户信息')
								if (res.code == 1) {
									this.$store.commit('set_user_info', res.data)
									uni.setStorageSync('user_info', res.data)
								}
							})
						}
					})
				} else {
					if (common_val.auth_type == 0) {
						window.location.href = common_val.mpurl
					}
				}
				// #endif
			},
			build_url: function(type = 'ws', res_path) {
				var that = this
				var protocol = Config.https_switch ? 'https://' : 'http://';
				var token = uni.getStorageSync('token');
				var buildObj = {
					ws: () => {
						protocol = Config.wss_switch ? 'wss://' : 'ws://';
						return protocol + Config.baseURL + ":" + Config.httPort + "?token=" + token
					},
					default: () => {
						return protocol + Config.baseURL;
					}
				};

				let action = buildObj[type] || buildObj['default']
				return action.call(this)
			},
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
			domsg: function(msg) {
				var that = this
				// console.log(uni.getStorageSync('userinfo').id, '==========')
				return {
					default: (msg) => {
						console.log('default', msg);
					},
					initialize: (msg) => {
						var user_initialize = {
							c: 'Message',
							a: 'sessionList',
							data: {
								user_id: uni.getStorageSync('user_info').id
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
					show_msg: (msg) => {
						uni.showToast({
							title: msg.msg,
							icon: 'none'
						})
					},
					leave_message: (msg) => {
						uni.showToast({
							title: msg.msg,
							icon: 'none'
						})
						that.leaveMessage = {
							name: '',
							contact: '',
							message: ''
						}
					},
					clear: (msg) => {
						if (msg.msg) {
							uni.showToast({
								title: msg.msg,
								icon: 'none'
							})
						}

						var clear = {
							c: 'Message',
							a: 'clear'
						};
						that.ws_send(clear);

						that.ws.unloadCloseWs = true;
					},
					offline: (msg) => {
						if (msg.user_id == that.csr) {
							this.errorTips = '客服离线~'
						}
					},
					online: (msg) => {
						// 来自 admin 的用户上线
						if (msg.modulename == 'admin') {

							if (msg.user_id == that.csr) {
								that.errorTips = '';
							} else if (that.csr == 'none') {
								// 重新为用户分配客服代表
								var user_initialize = {
									c: 'Message',
									a: 'userInitialize'
								};
								that.ws_send(user_initialize);
							}
						}
					},
					csr_change_status: (msg) => {
						if (that.csr == msg.data.csr) {
							if (msg.data.csr_status != 3) {
								that.errorTips = '客服' + that.get_csr_status(msg.data.csr_status) + '~'
							} else {
								that.errorTips = '';
							}
						}
					},
					transfer_done: (msg) => {
						that.csr = msg.data.csr;
						that.set_navigation_bar_title('客服 ' + msg.data.nickname + ' 为您服务')
					},
					new_message: (msg) => {


					},
					chat_record: (msg) => {
						// 下一页
						that.chatRecordPage = msg.data.next_page;
						for (let i in msg.data.chat_record) {
							msg.data.chat_record[i].data = that.format_message(msg.data.chat_record[i].data)
						}
						if (msg.data.page == 1) {
							that.messageList = msg.data.chat_record;
							// 滑动到最后一条消息
							that.scroll_into_footer(200, 99991);
						} else {
							for (let i = msg.data.chat_record.length - 1; i >= 0; i--) {
								msg.data.chat_record[i].data.reverse()
								that.messageList.unshift(msg.data.chat_record[i]);
							}

							setTimeout(function() {
								let kefu_scroll = uni.createSelectorQuery().select('#kefu_scroll');
								kefu_scroll.fields({
									scrollOffset: true
								}, data => {
									that.kefu_scroll_top = data.scrollHeight - that
										.record_scroll_height
									that.kefu_scroll_with_animation = true
								}).exec()
							}, 200)
						}
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
					read_message_done: (msg) => {
						if (that.sessionId == msg.data.session_id) {
							if (msg.data.record_id == 'all') {
								for (let messageListIndex in that.messageList) {
									for (let i in that.messageList[messageListIndex].data) {
										that.messageList[messageListIndex].data[i].status = 1;
									}
								}
							} else {
								for (let messageListIndex in that.messageList) {
									for (let i in that.messageList[messageListIndex].data) {
										if (that.messageList[messageListIndex].data[i].id == msg.data.record_id) {
											that.messageList[messageListIndex].data[i].status = 1;
										}
									}
								}
							}
						}
					},
					message_input: (msg) => {
						var that = this
						var input_status_display = parseInt(that.config.input_status_display);
						if (input_status_display == 0 || input_status_display == 2) {
							return;
						}

						if (msg.data.type == 'input') {
							that.set_navigation_bar_title('客服正在输入...', false)
						} else {
							that.set_navigation_bar_title(that.navigation_bar_title, false)
						}
					},
					upload_multipart: (msg) => {
						if (msg.data.upload_multipart) {
							that.config.upload.multipart = msg.data.upload_multipart
						}
					}
				};
			},

			// 开始链接
			connect_socket: function() {
				console.log('开始链接')
				var that = this
				// if (this.ws.SocketTask && this.ws.socketOpen) {
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
					complete: res => {
						console.log('sdfasdfsdf');
					}
				});

				// 链接打开
				uni.onSocketOpen(function(res) {
					that.ws.socketOpen = true
					that.ws.CurrentRetryCount = 0;
					// 重新发送所有出错的消息
					that.ws.ErrorMsg = [];
					that.errorTips = '';

				});

				// 收到消息
				uni.onSocketMessage(function(res) {
					let msg = JSON.parse(res.data)
					console.log('收到消息', msg);
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

					}
					if (msg.msgtype == "new_message") {
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
			// 上面是websocket连接
			go(url) {
				uni.navigateTo({
					url
				})
			},
			// 菜单切换
			menu_chack(index, id) {
				this.menu_action = index
				this.spot_tj_data.type_id = id
				this.get_spot_tj()
				this.get_travel_tj()
			},
			go_menu(url) {
				uni.navigateTo({
					url
				})
			},
			go_more() {
				uni.navigateTo({
					url: '/pagesA/index_menu/guide'
				})
			},
			// 获取广告位列表
			get_ad_list() {
				let obj = {
					url: '/pages/index/index',
					position: 1
				}
				ad_list(obj).then(res => {
					this.swiper_list = res.data
					console.log(this.swiper_list)
				})
			},
			// 获取菜单列表
			get_menu_list() {
				nav_list().then(res => {
					console.log(res)
					this.meun_list = res.data
				})
			},
			// 获取导游列表
			get_guide_list() {
				guide_list({ limit: 6 }).then(res => {
					// console.log(res)
					this.guide_list = res.data.data
				})
			},
			// 获取景区分类
			get_spot_type_list() {
				spot_type_list().then(res => {
					this.tab_list = res.data
					// 首次请求
					this.spot_tj_data.type_id = res.data[0].id
					this.get_spot_tj()
					this.get_travel_tj()
				})
			},
			// 今日最热
			get_spot_tj() {
				spot_tj(this.spot_tj_data).then(res => {
					// console.log(res)
					this.hot_msg = res.data
				})
			},
			// 游记推荐
			get_travel_tj() {
				travel_tj(this.spot_tj_data).then(res => {
					// console.log(res.data, '222')
					if (res.data != null) {
						this.travel_tj_list = res.data
					}
				})
			},
			// 公众号静默登录
			get_code() {
				console.log('跳转')
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding-bottom: 30rpx;
		background-color: #F8F9FC;

		.headr {
			position: relative;

			// padding-top: 100rpx;
			/* #ifdef H5 */
			.h5_search {
				width: 670rpx !important;
				box-sizing: border-box;

			}

			/* #endif */
			.search {
				position: absolute;
				top: 0;
				left: 0;
				margin-left: 40rpx;
				align-items: center;
				width: 450rpx;
				height: 68rpx;
				background: #FFFFFF;
				border-radius: 80rpx 80rpx 80rpx 80rpx;
				padding: 0rpx 26rpx;
				z-index: 9999;

				.search_left {
					.icon {
						width: 18rpx;
						height: 10rpx;
						margin-left: 10rpx;
					}

					.line {
						border-right: 1rpx solid #666666;
						height: 34rpx;
						margin-left: 20rpx;
					}

					.magnif {
						width: 30rpx;
						height: 30rpx;
						margin-left: 20rpx;
					}
				}

				.pl {
					font-weight: 400;
					font-size: 24rpx;
					color: #BEBFC1;
				}

				.search_right {
					width: 94rpx;
					height: 42rpx;
					background: #FFE706;
					border-radius: 1300rpx 1300rpx 1300rpx 1300rpx;
					font-weight: 400;
					font-size: 24rpx;
					color: #232323;
					line-height: 33rpx;
					text-align: left;
					font-style: normal;
					text-transform: none;
				}
			}

			// height: 318rpx;
			background-repeat: no-repeat;
			background-position: 50;
			background-size: 100%;

			.headr_img {
				width: 100%;
				height: 100%;
			}
		}

		.content_box {
			position: relative;
			min-height: 80vh;
			background: #F8F9FC;
			border-radius: 40rpx 40rpx 0rpx 0rpx;
			margin-top: -30rpx;
			padding-top: 22rpx;
			// z-index: 999999;

			.grid_item {
				flex-direction: column;

				.grid_icon {
					width: 64.5rpx;
					height: 64.5rpx;
					margin-top: 26rpx;
				}

				.grid_text {
					padding: 10rpx 0 20rpx 0rpx;
					font-weight: 400;
					font-size: 20rpx;
					color: #000000;
					line-height: 23rpx;
					text-align: left;
					font-style: normal;
					text-transform: none
				}
			}

			// 导游
			.guide_box {
				padding: 50rpx 30rpx 0rpx 30rpx;

				.box_title {
					.left {
						.text {
							font-weight: 400;
							font-size: 36rpx;
							color: #000000;
							line-height: 42rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
							z-index: 1;
						}

						.line {
							width: 100%;
							height: 14rpx;
							background: #FFE706;
							margin-top: -10rpx;
						}
					}

					.right {
						.text {
							font-weight: 400;
							font-size: 24rpx;
							color: #999999;
							line-height: 28rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.icon {
							width: 11rpx;
							height: 19rpx;
							margin-left: 9rpx;
						}
					}
				}

				.hot_guide_box {
					margin-top: 14rpx;
					flex-wrap: wrap;
				}
			}

			// 推荐
			.recommend {
				padding: 0rpx 30rpx;
				margin-top: 50rpx;

				.recommend_menu {
					.menu_name_action {
						font-weight: 400;
						font-size: 40rpx;
						color: #232323;
						line-height: 47rpx;
						text-align: left;
						font-style: normal;
						text-transform: none;
					}

					.tab_box {
						width: 100%;
						white-space: nowrap;
					}

					.tab_item {
						display: inline-block !important;
						padding-right: 60rpx;
					}

					.menu_name {
						font-weight: 400;
						font-size: 32rpx;
						color: #666666;
						line-height: 37rpx;
						text-align: left;
						font-style: normal;
						text-transform: none
					}

					.line {
						margin-top: 4rpx;
						width: 38rpx;
						height: 11rpx;
						background: #FFE706;
						border-radius: 8rpx 8rpx 8rpx 8rpx;
					}
				}

				.hot {
					width: 100%;
					border-radius: 24rpx 24rpx 24rpx 24rpx;
					overflow: hidden;
					margin-top: 20rpx;

					.img_box {
						position: relative;

						.hot_text {
							position: absolute;
							top: 20rpx;
							left: 30rpx;
							z-index: 1;
							font-family: YouSheBiaoTiHei, YouSheBiaoTiHei;
							font-weight: 400;
							font-size: 40rpx;
							color: #FFFFFF;
							line-height: 47rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}

						.hot_img {
							width: 100%;
							height: 258rpx;
							vertical-align: bottom;
						}
					}

					.hot_swiper {
						position: absolute;
						bottom: 10rpx;
						left: 30rpx;
						z-index: 1;
						width: 177rpx;
						height: 34rpx;
						background: #FFFFFF;
						border-radius: 10rpx 10rpx 10rpx 10rpx;

						.swiper_box {
							height: 34rpx;

							.swiper-item {
								padding-left: 5rpx;
								height: 100%;

								.text {
									font-weight: 400;
									font-size: 20rpx;
									color: #232323;
									line-height: 23rpx;
									text-align: left;
									font-style: normal;
									text-transform: none;
									margin-left: 5rpx;
								}
							}
						}
					}

					.tip_box {
						background: #68612C;
						padding: 10rpx 20rpx 20rpx 20rpx;

						.tip_title {
							.title_text {
								font-weight: 400;
								font-size: 28rpx;
								color: #FFFFFF;
								line-height: 33rpx;
								text-align: left;
								font-style: normal;
								text-transform: none;
							}

							.tip_icon {
								margin-left: 10rpx;
								width: 26rpx;
								height: 26rpx;
								border-radius: 50%;
								background-color: white;

								.icon {
									width: 9rpx;
									height: 16rpx;
								}
							}
						}

						.tip_content {
							margin-top: 5rpx;
							font-weight: 400;
							font-size: 22rpx;
							color: #FFFFFF;
							line-height: 26rpx;
							text-align: left;
							font-style: normal;
							text-transform: none;
						}
					}
				}

				.list {
					margin-top: 20rpx;
					flex-wrap: wrap;
				}
			}
		}
	}
</style>