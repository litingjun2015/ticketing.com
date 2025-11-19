<template>
	<view class="content font_family">
		<u-parse :content="content"></u-parse>
	</view>
</template>

<script>
	import { pact } from '@/api/public.js'
	export default {
		data() {
			return {
				content: '',
				query_deta: {
					name: ''
				}
			}
		},
		onLoad(e) {
			uni.setNavigationBarTitle({
				title: e.title
			})
			this.query_deta.name = e.title
			this.get_content()
		},
		methods: {
			// 获取详细内容
			get_content() {
				pact(this.query_deta).then(res => {
					console.log(res)
					if (res.code == 1) {
						this.content = res.data.content
					}
				})
			}
		}
	}
</script>

<style lang="scss" scoped>
	.content {
		padding: 30rpx;
	}
</style>