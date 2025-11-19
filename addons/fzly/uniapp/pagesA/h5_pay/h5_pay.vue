<template>
	<view class="content font_family">
		<u-parse :content="html"></u-parse>
		<view class="" v-html="html">

		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				html: ''
			};
		},
		onLoad(e) {
			this.html = e.html
			console.log(this.html)
		},
	}
</script>

<style lang="scss">

</style>