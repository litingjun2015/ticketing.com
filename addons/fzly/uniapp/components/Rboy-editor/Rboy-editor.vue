<template>
	<div>
		<view class="fu_box">
			<editor class="" @ready='onEditorReady' id="editor" :show-img-toolbar="true" :show-img-resize="true"
				:show-img-size="true" class="" :placeholder="placeholder"></editor>
		</view>
		<view class="icons_box f_j">
			<!-- <text class="iconfont icon-zuoduiqi" @click="editor_format_btn('align','left')"></text>
			<text class="iconfont icon-juzhongduiqi" @click="editor_format_btn('align','center')"></text>
			<text class="iconfont icon-youduiqi" @click="editor_format_btn('align','right')"></text>
			<text class="iconfont icon-zuoyouduiqi" @click="editor_format_btn('align','justify')">
			</text> -->
			<!-- <text class="iconfont icon-zitijiacu" @click="editor_format_btn('bold')"></text> -->
			<!-- <text class="iconfont icon-format-header-1" @click="editor_format_btn('H1')"></text> -->
			<!-- <text class="iconfont icon-zitixieti" @click="editor_format_btn('italic')"></text> -->
			<view class="iconfont" @click="editor_img_btn">
				<image class="icon" src="https://wsbmb.oss-cn-chengdu.aliyuncs.com/qlx/public/add_img.png" mode=""></image>
			</view>
			<!-- <image src="" mode=""></image> -->
		</view>
	</div>
</template>

<script>
	import projecturl from '@/config.js'
	export default {
		name: "Rboy-editor",
		props: {
			count: {
				type: Number,
				default: 6
			},
			type: {
				type: String,
				default: '1'
			},
			content: {
				type: String,
				default: ''
			},
			placeholder: {
				type: String,
				default: ''
			}
		},
		data() {
			return {
				editorCtx: '',
			}
		},
		created() {

		},
		mounted() {
			// // #ifdef APP-PLUS || H5 ||MP-WEIXIN
			// uni.createSelectorQuery().in(this).select('#editor').context((res2) => {
			// 	this.editorCtx = res2.context
			// }).exec()
			// // #endif

		},
		methods: {
			onEditorReady() {
				uni.createSelectorQuery().in(this).select('#editor').context((res) => {
					this.editorCtx = res.context
					// this.editorCtx.setContents({
					// 	html: 'text' //this.EditGoodsDetail.content为赋值内容。    
					// })
				}).exec()
			},
			// 获取内容
			editor_getcontents() {
				return new Promise((resove, reject) => {
					this.editorCtx.getContents({
						success: (res) => {
							// console.log(res)
							// #ifdef MP-WEIXIN
							if (res.errMsg == 'ok') {
								resove(res.html)
							} else {
								reject(res)
							}
							// #endif
							// #ifdef H5
							if (res.errMsg == 'getContents:ok') {
								resove(res.html)
							} else {
								reject(res)
							}
							// #endif
						}
					})
				})
			},
			// 设置内容
			editor_setContents(text = "") {
				console.log(text)
				// 需要重新获取组件实例
				uni.createSelectorQuery().in(this).select('#editor').context((res) => {
					this.editorCtx = res.context
					console.log('设置内容', text)
					this.editorCtx.setContents({
						html: text //this.EditGoodsDetail.content为赋值内容。    
					})
				}).exec()
			},
			// 设置属性
			editor_format_btn(name, value) {
				this.editorCtx.format(name, value)
			},
			// 设置图片
			editor_insertImage(data) {
				if (!data) return false
				this.editorCtx.insertImage(data)
			},
			// 上传图片
			editor_img_btn() {
				uni.chooseImage({
					count: this.count || 6, // 控制上传图片的数量 默认 6
					type: 'image',
					success: async (res) => {
						console.log(res, '=======')
						this.upload_img(res.tempFilePaths[0]).then(res => {
							if (res.code == 1) {
								console.log(res)
								this.$emit("uploadFile", res.data.fullurl)
							}
						})
					}
				});
			},
			// 上传图片到服务器
			upload_img(data) {
				return new Promise(function(resolve, reject) {
					uni.uploadFile({
						url: projecturl.serverIp + 'api/common/upload',
						filePath: data,
						header: {
							token: uni.getStorageSync('token')
						},
						name: 'file',
						success: (res) => {
							if (res.statusCode == 200) {
								resolve(JSON.parse(res.data))
							} else {
								reject({
									msg: '上传失败'
								})
							}
						},
						fail: (res) => {
							console.log(res)
						}
					})
				})
			},
		}
	}
</script>

<style scoped lang="less">
	@import './editor-icon.css';

	.fu_box {
		margin-top: 35rpx;
		min-height: 35px !important;
		// border: 1px solid #DCDFE6;
		border-radius: 4px;
		font-size: 14px;
		width: 100%;
		box-sizing: border-box;
		text-align: left;
		color: #333;
		// padding: 7px;
		border: none;

		editor {
			width: 100%;
			height: 600rpx;
			font-family: PingFang SC, PingFang SC;
			font-weight: 400;
			font-size: 28rpx;
			// color: #999999;
		}
	}


	.icons_box {
		.iconfont {
			margin: 10rpx 0rpx;

			.icon {
				width: 48rpx;
				height: 48rpx;
			}
		}
	}
</style>