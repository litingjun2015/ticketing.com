<template>
	<view>
		<view>
			<view class="canvasbox pt-20" @tap="save">
				<image style="width: 100%; height: 80%;border-radius: 10rpx;" :src="intimg" mode="aspectFit" />
			</view>
			<view class="bottom_ico">
				<view style="height: 20rpx;">
				</view>
				<view class="bottom_ico_text">
					点击上方图片保存，分享给朋友
				</view>
				<view class="out flexs-c">
					<image class="img_b" @click="setImg(item.tgimg)" v-for="item in intall" :src="item.tgimg"
						mode="aspectFill" />

				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		distlist,

	} from "@/api/index.js";
	export default {
		data() {
			return {
				intall: [],
				intimg: '',
				canvas: null, // 实例
				img: ''
			};
		},
		onLoad(options) {
			this.psot_common()
		},
		methods: {
			setImg(url) {
				this.intimg = url
				this.downLoadImg(this.intimg, 'storageKeyUrl') //调用如上 方法   
			},
			async psot_common() {
				let data = await distlist({})
				if (data.code == 1) {
					this.intall = data.data
					this.intimg = data.data[0].tgimg
					this.downLoadImg(this.intimg, 'storageKeyUrl') //调用如上 方法   
				}
			},
			downLoadImg(netUrl, storageKeyUrl) {
				uni.getImageInfo({
					src: netUrl, //请求的网络图片路径
					success: function(res) {
						//请求成功后将会生成一个本地路径即res.path,然后将该路径缓存到storageKeyUrl关键字中
						uni.setStorage({
							key: storageKeyUrl,
							data: res.path,
						});

					}
				})
			},

			save() {
				console.log('1111');
				// let storageKeyUrl = uni.getStorageSync('storageKeyUrl')
				// this.saveImage(storageKeyUrl)
				this.saveImage(this.intimg)
				// uni.saveImageToPhotosAlbum({ //保存图片到相册
				// 	filePath: storageKeyUrl,
				// 	success: function() {
				// 		uni.showToast({
				// 			title: "保存成功！",
				// 			duration: 2000
				// 		})
				// 	}
				// })
			},
		}
	}
</script>

<style lang="scss">
	page {
		background: #EFF1F3;
	}

	.box-img {
		width: 614rpx;
		height: 1091rpx;
		margin: 30rpx auto;
	}

	.img_a {
		width: 100%;
		height: 100%;
	}

	.bottom_ico {
		width: 100%;
		height: 24%;
		background: #FFFFFF;
		position: fixed;
		bottom: 0;
	}

	.bottom_ico_text {
		font-size: 26rpx;
		font-weight: 400;
		color: #171717;
		text-align: center;
	}

	.out {
		image {
			width: 120rpx;
			height: 200rpx;
			margin: 20rpx;
		}

		.img_b {
			width: 120rpx;
			height: 200rpx;
			margin: 20rpx;
		}

	}



	.canvasbox {
		width: 100%;
		height: 90vh;
		position: relative;
		margin: 0 20rpx;

		image {
			border-radius: 10rpx;
		}
	}

	canvas {
		width: 100%;
		height: 100%;
		display: block;
		position: absolute;
		top: 0;
	}
</style>