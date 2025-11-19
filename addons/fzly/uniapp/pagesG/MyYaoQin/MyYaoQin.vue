<template>
	<view class="box">
		<image class="hadr_img" src="../static/Blue_background.png" mode="" />
		<!-- <image src="../static/Arrow_right.png" mode="" /> -->
		<view class="content">
			<view class="content_introduce" @tap="goback">
				<image class="content_introduce_ima" src="../static/WhiteArrow.png" mode="" />
				<view class="content_introduce_title">
					我的团队
				</view>
			</view>
		</view>
		<view class="user_count">
			<view class="user_count_box">
				<view class="user_count_left">
					<view class="user_count_left_top">
						总人数
					</view>
					<view class="user_count_left_bottom">
						{{mygroup.peopleCount || 0}}
					</view>
				</view>
				<view class="user_count_right">
					<view class="user_count_right_1">
						<view class="user_count_right_1_1">
							今日
						</view>
						<view class="user_count_right_1_2">
							+{{mygroup.todayCount || 0}}
						</view>
					</view>
					<view class="user_count_right_1">
						<view class="user_count_right_1_1">
							昨日
						</view>
						<view class="user_count_right_1_2">
							+{{mygroup.ydayCount || 0}}
						</view>
					</view>
					<view class="user_count_right_1">
						<view class="user_count_right_1_1">
							本月
						</view>
						<view class="user_count_right_1_2">
							+{{mygroup.onemoonCount || 0}}
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="Membership_statistics">
			<view class="Membership_statistics_1">
				<view class="Membership_statistics_1_box" @tap="routerGo('/pagesG/member/member?type=1')">
					<view style="height: 20rpx;"></view>
					<view class="Membership_statistics_1_box_1">
						<view class="Membership_statistics_1_box_left">
							一级成员
						</view>
						<image class="Membership_statistics_1_box_right_img" src="../static/Arrow_right.png" mode="" />
					</view>
					<view class="Membership_statistics_1_box_user">
						<view class="Membership_statistics_1_box_user_1">
							{{mygroup.oneCount || 0}} <text
								style="font-size: 24rpx;font-weight: 400; margin-left: 10rpx;">人</text>
						</view>
					</view>
					<view class="Membership_statistics_1_box_go">
						<view class="Membership_statistics_1_box_go_left">
							本月 {{mygroup.onemoonCount || 0}}
						</view>
						<view class="Membership_statistics_1_box_go_right">
							较上月{{mygroup.oneb || 0}}%
							<image class="Membership_statistics_1_box_go_right_img" v-if="mygroup.oneb<=-1"
								src="../static/Dom.png" mode="" />
							<image class="Membership_statistics_1_box_go_right_img" v-if="mygroup.oneb>0"
								src="../static/up.png" mode="" />
						</view>
					</view>
				</view>
			</view>
			<view class="Membership_statistics_1" @tap="routerGo('/pagesG/member/member?type=2')">
				<view class="Membership_statistics_1_box">
					<view style="height: 20rpx;">
					</view>
					<view class="Membership_statistics_1_box_1">
						<view class="Membership_statistics_1_box_left">
							二级成员
						</view>
						<image class="Membership_statistics_1_box_right_img" src="../static/Arrow_right.png" mode="" />
					</view>
					<view class="Membership_statistics_1_box_user">
						<view class="Membership_statistics_1_box_user_1">
							{{mygroup.twoCount || 0}} <text
								style="font-size: 24rpx;font-weight: 400; margin-left: 10rpx;">人</text>
						</view>
					</view>
					<view class="Membership_statistics_1_box_go">
						<view class="Membership_statistics_1_box_go_left">
							本月 {{mygroup.twomoonCount || 0}}
						</view>
						<view class="Membership_statistics_1_box_go_right">
							较上月{{mygroup.twob || 0}}%
							<image class="Membership_statistics_1_box_go_right_img" v-if="mygroup.twob<=-1"
								src="../static/Dom.png" mode="" />
							<image class="Membership_statistics_1_box_go_right_img" v-if="mygroup.twob>0"
								src="../static/up.png" mode="" />
						</view>
					</view>
				</view>
			</view>
		</view>
		<view class="finally">
			<view class="" style="height: 170rpx;margin-bottom: 10rpx;">
				<view style="height: 40rpx;">
				</view>
				<view class="finally_1">
					<view class="finally_1_1" @tap="rank">
						<view class="finally_1_1_title" :class="current==0?'active':''">
							加入时间
						</view>
						<view class="finally_1_1_img">
							<image style="width: 20rpx;height: 30rpx;" v-if="rankall===0" src="../static/triangle_1.png"
								mode="" />
							<image style="width: 20rpx;height: 30rpx;" v-if="rankall===0" src="../static/triangle_4.png"
								mode="" />
							<image style="width: 20rpx;height: 25rpx;" v-if="rankall===1" src="../static/triangle_2.png"
								mode="" />
							<image style="width: 20rpx;height: 25rpx;" v-if="rankall===1" src="../static/triangle_6.png"
								mode="" />
						</view>
					</view>
					<view class="finally_1_2" @tap="rankuser">
						<view class="finally_1_2_title" :class="current==1?'active':''">
							邀请人数
						</view>
						<view class="finally_1_2_img">
							<image style="width: 20rpx;height: 30rpx;" v-if="rankalluser===0"
								src="../static/triangle_1.png" mode="" />
							<image style="width: 20rpx;height: 30rpx;" v-if="rankalluser===0"
								src="../static/triangle_4.png" mode="" />
							<image style="width: 20rpx;height: 25rpx;" v-if="rankalluser===1"
								src="../static/triangle_2.png" mode="" />
							<image style="width: 20rpx;height: 25rpx;" v-if="rankalluser===1"
								src="../static/triangle_6.png" mode="" />
						</view>
					</view>
				</view>
				<view class="search">
					<u-search placeholder="搜索推广员姓名、手机号" searchIconSize='36rpx' height="70rpx" v-model="value"
						use-action-slot @search="post_teamlist" @custom='post_teamlist' @clear='post_teamlist'>
					</u-search>
				</view>
			</view>
			<scroll-view scroll-y="true" class="scrollHeg" @scrolltolower="scrolltolower">
				<view class="users_classifying">
					<view class="users_classifying_box">
						<empty v-if="userlist.length<1"></empty>
						<view class="users_classifying_box_1" v-for="item in userlist" :key="item.id">
							<view class="users_classifying_box_1_left">
								<image class="users_classifying_box_1_left_img" :src="item.avatar" mode="" />
								<view class="users_classifying_box_1_left_title">
									<view class="users_classifying_box_1_left_title_top">
										{{item.username}}
									</view>
									<view class="users_classifying_box_1_left_title_bottom">
										{{item.createtime}}
									</view>
								</view>
							</view>
							<view class="users_classifying_box_1_right">
								<view class="users_classifying_box_1_right_1">
									邀请人数
								</view>
								<view class="users_classifying_box_1_right_2">
									{{item.childrenCount}}
								</view>
								<view class="users_classifying_box_1_right_3" @tap="cell(item.mobile)">
									联系他
								</view>
							</view>
						</view>
					</view>
				</view>

			</scroll-view>

		</view>
	</view>
</template>

<script>
	import {
		distributionteam,
		teamlist
	} from '@/api/index.js'
	export default {
		data() {
			return {
				value: '',
				// 加入时间
				rankall: '',
				// 邀请人数
				rankalluser: '',
				// 级别成员
				grade: 1,
				// 我的团队
				mygroup: {},
				// 我的团队列表
				mygrouplist: {},
				// userlist
				userlist: [],
				height: '',
				current: 0,
				page: 1
			};
		},
		onLoad(options) {
			let that = this
			uni.getSystemInfo({
				success: res => {
					let custom = uni.getMenuButtonBoundingClientRect();
					let Height = custom.bottom + custom.top - res.statusBarHeight;
					that.height = Height
				},
			});
			this.post_distributionteam()
			this.post_teamlist()
		},

		methods: {
			scrolltolower() {
				this.page++
				this.post_teamlist(1)
			},
			goback() {
				uni.navigateBack()
			},
			onChange(e) {
				this.value = e.detail
			},
			rank() {
				if (this.rankall == 0) {
					this.rankall = 1
				} else {
					this.rankall = 0
				}
				this.current = 0
				this.rankalluser = 'a'
				this.post_teamlist()
			},
			rankuser() {
				if (this.rankalluser == 0) {
					this.drankalluser = 1
				} else {
					this.rankalluser = 0
				}
				this.current = 1
				this.rankalluser = this.rankalluser
				this.rankall = 'a'
				this.post_teamlist()
			},
			async post_distributionteam() {
				let data = await distributionteam({})
				if (data.code == 1) {
					this.mygroup = data.data
				}
			},
			async post_teamlist(e) {
				let rankall = ''
				let rankalluser = ''
				if (this.rankall == 0) {
					rankall = 'desc'
				} else if (this.rankall == 1) {
					rankall = 'asc'
				} else if (this.rankall == 'a') {
					rankall = ''
				}
				if (this.rankalluser == 0) {
					rankalluser = 'desc'
				} else if (this.rankalluser == 1) {
					rankalluser = 'asc'
				} else if (this.rankalluser == 'a') {
					rankalluser = ''
				}
				if (e !== 1) {
					this.page = 1
				}
				let data = await teamlist({
					type: this.grade,
					timeOrder: rankall,
					peopleOrder: rankalluser,
					search: this.value,
					page: this.page
				})
				if (e == 1) {
					this.userlist = [...this.userlist, ...data.data.data]
				} else {
					this.userlist = data.data.data
				}
			},

			cell(e) {
				uni.makePhoneCall({
					phoneNumber: e, //仅为示例，并非真实的电话号码
					success(res) {},
					fail(res) {
						uni.$showMsg('拨打失败')
					}
				})

			},
		}
	}
</script>

<style lang="scss">
	.scrollHeg {
		height: calc(100vh - 539rpx - 150rpx);
	}

	.box {
		background: #FFFFFF;
		overflow: hidden;
	}

	.hadr_img {
		width: 100%;
		height: 539rpx;
		position: absolute;

	}

	.content {
		width: 95%;
		margin: 20rpx auto 0;
	}

	.content_introduce {
		display: flex;
		align-items: center;
		padding-top: 4.4vh;
	}

	.content_introduce_ima {
		width: 45rpx;
		height: 44rpx;
		z-index: 1;
	}

	.content_introduce_title {
		font-size: 38rpx;
		font-weight: 500;
		color: #FFFCFF;
		line-height: 53rpx;
		letter-spacing: 1px;
		margin-left: 20rpx;
		z-index: 1;
	}

	.user_count {
		width: 95%;
		position: absolute;
	}

	.user_count_box {
		width: 90%;
		margin: 0 auto;
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-top: 50rpx;
	}

	.user_count_left {}

	.user_count_left_top {
		font-size: 28rpx;
		font-weight: 400;
		color: #FFFCFF;
		z-index: 2;
		margin-bottom: 10rpx;
	}

	.user_count_left_bottom {
		font-size: 48rpx;
		font-weight: 600;
		color: #FFFCFF;
		line-height: 67rpx;
		z-index: 2;
	}

	.user_count_right {
		width: 400rpx;
		display: flex;
		justify-content: space-between;
	}

	.user_count_right_1_1 {
		z-index: 1;
		font-size: 20rpx;
		font-weight: 400;
		color: #FCFEFF;
	}

	.user_count_right_1_2 {
		z-index: 1;
		font-size: 34rpx;
		font-weight: 400;
		color: #FCFFFE;
		margin-top: 25rpx;
	}

	.Membership_statistics {
		width: 95%;
		position: absolute;
		top: 300rpx;
		margin-left: 20rpx;
		display: flex;
		justify-content: space-between;
	}

	.Membership_statistics_1_box {
		width: 340rpx;
		height: 151rpx;
		background: #FFFFFF;
		border-radius: 14rpx;
	}

	.Membership_statistics_1_box_right_img {
		width: 15rpx;
		height: 25rpx;
	}

	.Membership_statistics_1_box_1 {
		width: 80%;
		margin: 0 auto;
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 20rpx;
		font-weight: 400;
		color: #010101;
	}

	.Membership_statistics_1_box_user {
		display: flex;
		align-items: center;
		margin-left: 30rpx;
		font-size: 22rpx;
		font-weight: 400;
		color: #010101;
		margin-top: 10rpx;
	}

	.Membership_statistics_1_box_user_1 {
		font-size: 34rpx;
		font-weight: 600;
		color: #010101;
		display: flex;
		align-items: center;
	}

	.Membership_statistics_1_box_go {
		width: 80%;
		margin: 0 auto;
		margin-top: 5rpx;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.Membership_statistics_1_box_go_left {
		font-size: 22rpx;
		font-weight: 400;
		color: #B8D4EE;
	}

	.Membership_statistics_1_box_go_right {
		font-size: 22rpx;
		font-weight: 400;
		color: #B8D4EE;
		display: flex;
		align-items: center;
	}

	.Membership_statistics_1_box_go_right_img {
		width: 12rpx;
		height: 26rpx;
		margin-left: 10rpx;
	}

	.finally {
		width: 100%;
		// height: 1079rpx;
		background: #FFFFFF;
		border-radius: 30rpx 30rpx 0rpx 0rpx;
		position: absolute;
		top: 490rpx;
	}

	.finally_1 {
		display: flex;
	}

	.finally_1_1_title {
		font-size: 28rpx;
		font-weight: 400;
		line-height: 40rpx;
		color: #ABAFC2;
	}

	.active {
		color: #377EF5 !important;
		font-weight: 700;
	}

	.finally_1_1 {
		display: flex;
		margin-left: 30rpx;
	}

	.finally_1_1_img {
		height: 35rpx;
		display: flex;
		flex-direction: column;
		margin-left: 10rpx;
	}

	.finally_1_2 {
		display: flex;
		align-items: center;
		margin-left: 50rpx;
	}

	.finally_1_2_title {
		font-size: 28rpx;
		font-weight: 400;
		color: #ABAFC2;
	}

	.finally_1_2_img {
		height: 35rpx;
		display: flex;
		flex-direction: column;
		margin-left: 10rpx;
	}

	.search {
		width: 95%;
		margin: 10rpx auto;
	}

	.van-icon {
		color: #ABADBF;
		font-size: 40rpx !important;
	}



	.users_classifying_box {
		width: 90%;
		margin: 0 auto;
	}

	.users_classifying_box_1 {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 40rpx;
	}

	.users_classifying_box_1_left {
		display: flex;
		align-items: center;
	}

	.users_classifying_box_1_left_img {
		width: 80rpx;
		height: 80rpx;
		border-radius: 50%;
	}

	.users_classifying_box_1_left_title {
		margin-left: 20rpx;
	}

	.users_classifying_box_1_left_title_top {
		font-size: 32rpx;
		font-weight: 400;
		color: #000216;
	}

	.users_classifying_box_1_left_title_bottom {
		font-size: 22rpx;
		font-weight: 400;
		color: #A6A7BB;
		line-height: 30rpx;
	}

	.users_classifying_box_1_right {
		width: 250rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.users_classifying_box_1_right_1 {
		font-size: 20rpx;
		font-weight: 400;
		color: #A6A7BB;
	}

	.users_classifying_box_1_right_2 {
		font-size: 32rpx;
		font-weight: 600;
		color: #000216;
		line-height: 45rpx;
	}

	.users_classifying_box_1_right_3 {
		width: 120rpx;
		height: 56rpx;
		border-radius: 28rpx;
		border: 1rpx solid #377EF5;
		font-size: 24rpx;
		font-weight: 400;
		color: #377EF5;
		line-height: 56rpx;
		text-align: center;
	}
</style>