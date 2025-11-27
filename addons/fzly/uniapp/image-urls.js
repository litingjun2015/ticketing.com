/**
 * 图片资源云端URL映射配置
 * 使用方法：将 static 目录下的所有图片上传到云端（阿里云OSS/腾讯云COS等）
 * 然后替换下面的 BASE_URL 为你的云端存储地址
 */

// 云端存储基础URL（请替换为你的实际URL）
const BASE_URL = 'https://your-oss-domain.com/uniapp/static'

// 图片URL映射表
export const IMAGE_URLS = {
    // business 目录
    business: {
        back: `${BASE_URL}/business/back.png`,
        fb_action: `${BASE_URL}/business/fb_action.png`,
        fb_icon: `${BASE_URL}/business/fb_icon.png`,
        fb: `${BASE_URL}/business/fb.png`,
        gy: `${BASE_URL}/business/gy.png`,
        index_action: `${BASE_URL}/business/index_action.png`,
        index_bg: `${BASE_URL}/business/index_bg.png`,
        index: `${BASE_URL}/business/index.png`,
        msg: `${BASE_URL}/business/msg.png`,
        order_action: `${BASE_URL}/business/order_action.png`,
        order: `${BASE_URL}/business/order.png`,
        price: `${BASE_URL}/business/price.png`,
        set: `${BASE_URL}/business/set.png`,
        sm: `${BASE_URL}/business/sm.png`,
        sr: `${BASE_URL}/business/sr.png`,
        vx_account: `${BASE_URL}/business/vx_account.png`,
        zfb_account: `${BASE_URL}/business/zfb_account.png`,
        zm: `${BASE_URL}/business/zm.png`
    },

    // community 目录
    community: {
        add: `${BASE_URL}/community/add.png`,
        comment: `${BASE_URL}/community/comment.png`,
        follow: `${BASE_URL}/community/follow.png`,
        like_action: `${BASE_URL}/community/like_action.png`,
        like: `${BASE_URL}/community/like.png`
    },

    // index 目录
    index: {
        community_action: `${BASE_URL}/index/community_action.png`,
        community: `${BASE_URL}/index/community.png`,
        go_hot: `${BASE_URL}/index/go_hot.png`,
        index_action: `${BASE_URL}/index/index_action.png`,
        index_icon0: `${BASE_URL}/index/index_icon0.png`,
        index_icon1: `${BASE_URL}/index/index_icon1.png`,
        index_icon2: `${BASE_URL}/index/index_icon2.png`,
        index_icon3: `${BASE_URL}/index/index_icon3.png`,
        index_icon4: `${BASE_URL}/index/index_icon4.png`,
        index_icon5: `${BASE_URL}/index/index_icon5.png`,
        index_icon6: `${BASE_URL}/index/index_icon6.png`,
        index_icon7: `${BASE_URL}/index/index_icon7.png`,
        index: `${BASE_URL}/index/index.png`,
        like: `${BASE_URL}/index/like.png`,
        magnif: `${BASE_URL}/index/magnif.png`,
        man: `${BASE_URL}/index/man.png`,
        me_action: `${BASE_URL}/index/me_action.png`,
        me: `${BASE_URL}/index/me.png`,
        more: `${BASE_URL}/index/more.png`,
        msg_action: `${BASE_URL}/index/msg_action.png`,
        msg: `${BASE_URL}/index/msg.png`,
        pt: `${BASE_URL}/index/pt.png`,
        rectangle: `${BASE_URL}/index/Rectangle 194@2x.png`,
        shop_action: `${BASE_URL}/index/shop_action.png`,
        shop: `${BASE_URL}/index/shop.png`,
        vector: `${BASE_URL}/index/vector.png`,
        woman: `${BASE_URL}/index/woman.png`
    },

    // me 目录
    me: {
        coupon: `${BASE_URL}/me/coupon.png`,
        function1: `${BASE_URL}/me/function1.png`,
        function2: `${BASE_URL}/me/function2.png`,
        function3: `${BASE_URL}/me/function3.png`,
        function4: `${BASE_URL}/me/function4.png`,
        function5: `${BASE_URL}/me/function5.png`,
        function6: `${BASE_URL}/me/function6.png`,
        fx: `${BASE_URL}/me/fx.png`,
        grzy: `${BASE_URL}/me/grzy.png`,
        jd: `${BASE_URL}/me/jd.png`,
        me_bg: `${BASE_URL}/me/me_bg.png`,
        me_set_icon: `${BASE_URL}/me/me_set_icon.png`,
        mp: `${BASE_URL}/me/mp.png`,
        order1: `${BASE_URL}/me/order1.png`,
        order2: `${BASE_URL}/me/order2.png`,
        order3: `${BASE_URL}/me/order3.png`,
        order4: `${BASE_URL}/me/order4.png`,
        order5: `${BASE_URL}/me/order5.png`
    },

    // msg 目录
    msg: {
        back_icon: `${BASE_URL}/msg/back_icon.png`,
        chat_add: `${BASE_URL}/msg/chat_add.png`,
        msg_left: `${BASE_URL}/msg/msg_left.png`,
        msg_right: `${BASE_URL}/msg/msg_right.png`,
        no_up: `${BASE_URL}/msg/no_up.png`,
        photograph: `${BASE_URL}/msg/photograph.png`,
        sc: `${BASE_URL}/msg/sc.png`,
        send_img: `${BASE_URL}/msg/send_img.png`,
        speech: `${BASE_URL}/msg/speech.png`,
        up: `${BASE_URL}/msg/up.png`
    },

    // public 目录
    public: {
        add_b: `${BASE_URL}/public/add_b.png`,
        add_cover: `${BASE_URL}/public/add_cover.png`,
        add_img: `${BASE_URL}/public/add_img.png`,
        add: `${BASE_URL}/public/add.png`,
        back_b: `${BASE_URL}/public/back_b.png`,
        back_w: `${BASE_URL}/public/back_w.png`,
        bj: `${BASE_URL}/public/bj.png`,
        can_use: `${BASE_URL}/public/can_use.png`,
        checkmark_b: `${BASE_URL}/public/checkmark_b.png`,
        checkmark_l: `${BASE_URL}/public/checkmark_l.png`,
        close: `${BASE_URL}/public/close.png`,
        close2: `${BASE_URL}/public/close2.png`,
        collection_h: `${BASE_URL}/public/collection_h.png`,
        collection: `${BASE_URL}/public/collection.png`,
        comment: `${BASE_URL}/public/comment.png`,
        coupon_g: `${BASE_URL}/public/coupon_g.png`,
        date: `${BASE_URL}/public/date.png`,
        dg_h: `${BASE_URL}/public/dg_h.png`,
        dg_y: `${BASE_URL}/public/dg_y.png`,
        homepage_back: `${BASE_URL}/public/homepage_back.png`,
        hot_city: `${BASE_URL}/public/hot_city.png`,
        kong: `${BASE_URL}/public/kong.png`,
        like_t: `${BASE_URL}/public/like_t.png`,
        like: `${BASE_URL}/public/like.png`,
        login_icon: `${BASE_URL}/public/login_icon.png`,
        login_text: `${BASE_URL}/public/login_text.png`,
        man: `${BASE_URL}/public/man.png`,
        msg_h: `${BASE_URL}/public/msg_h.png`,
        no_read: `${BASE_URL}/public/no_read.png`,
        pt_h: `${BASE_URL}/public/pt_h.png`,
        pyq: `${BASE_URL}/public/pyq.png`,
        read: `${BASE_URL}/public/read.png`,
        red_sc: `${BASE_URL}/public/red_sc.png`,
        sc_dt: `${BASE_URL}/public/sc_dt.png`,
        sc: `${BASE_URL}/public/sc.png`,
        screen_down: `${BASE_URL}/public/screen_down.png`,
        screen_up: `${BASE_URL}/public/screen_up.png`,
        set_avatar: `${BASE_URL}/public/set_avatar.png`,
        set_more: `${BASE_URL}/public/set_more.png`,
        set: `${BASE_URL}/public/set.png`,
        sort_h: `${BASE_URL}/public/sort_h.png`,
        sort_q: `${BASE_URL}/public/sort_q.png`,
        star_t: `${BASE_URL}/public/star_t.png`,
        view: `${BASE_URL}/public/view.png`,
        vx: `${BASE_URL}/public/vx.png`,
        woman: `${BASE_URL}/public/woman.png`,
        wsy: `${BASE_URL}/public/wsy.png`,
        ygq: `${BASE_URL}/public/ygq.png`,
        ygz: `${BASE_URL}/public/ygz.png`,
        yh: `${BASE_URL}/public/yh.png`,
        ysy: `${BASE_URL}/public/ysy.png`,
        zf_w: `${BASE_URL}/public/zf_w.png`,
        zf: `${BASE_URL}/public/zf.png`
    },

    // 根目录图片
    img: `${BASE_URL}/img.png`,
    img1: `${BASE_URL}/img1.png`
}

// 便捷访问方法
export function getImageUrl(category, name) {
    if (!name) {
        // 如果只传一个参数，则是根目录图片
        return IMAGE_URLS[category] || ''
    }
    return IMAGE_URLS[category]?.[name] || ''
}

export default IMAGE_URLS
