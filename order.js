var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
list:[],
region:{
    title:''
},
member:{
    address:''
},
room:{
    title:''
},
params:{},
totalfee:'0.00'
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {
    console.log('参数',options.params)
    
    var baocun=JSON.parse(options.params||'{}')
    console.log('解析之后',baocun)
    if(baocun){
this.setData({
    params:baocun
})
    }
    this.qingqiu()
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow() {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage() {

  },


  qiehuan(e){
    var list =this.data.list
    list[e.currentTarget.dataset.index].zhankai=!list[e.currentTarget.dataset.index].zhankai
this.setData({
  list
})
  },

  xuanzeall(e){
    console.log(e)
    var list =this.data.list
    list[e.currentTarget.dataset.index].checked=!list[e.currentTarget.dataset.index].checked
    if(list[e.currentTarget.dataset.index].checked===false){
      var items=list[e.currentTarget.dataset.index].data
  for (let j = 0; j < items.length; j++) {
    items[j].checked=false
    items[j].disabled=false
          
                 
                 }
    }else{
      var itemss=list[e.currentTarget.dataset.index].data
      for (let jjj = 0; jjj < itemss.length; jjj++) {
        itemss[jjj].checked=true
        itemss[jjj].disabled=true
        if(jjj==itemss.length-1){
          itemss[jjj].disabled=false
        } 
                     
                     }
    }
    
    var totalfee=0
        for (let i = 0; i < list.length; i++) {
          var item = list[i].data;
    // if(list[i].checked){
        for (let j = 0; j < item.length; j++) {
       if(item[j].checked){
       
        totalfee=totalfee+(item[j].fee+item[j].latefee)*1
       }
        
        // }
    }
    }
        totalfee=totalfee.toFixed(2)
    
    this.setData({
      list,
      totalfee
    })
      },


  xuanze(e){
console.log(e)
var list =this.data.list
list[e.currentTarget.dataset.index].data[e.currentTarget.dataset.idx].checked=!list[e.currentTarget.dataset.index].data[e.currentTarget.dataset.idx].checked

if(e.currentTarget.dataset.idx>0&&list[e.currentTarget.dataset.index].data[e.currentTarget.dataset.idx].checked==false){
  list[e.currentTarget.dataset.index].data[e.currentTarget.dataset.idx-1].disabled=false
}

if(e.currentTarget.dataset.idx>0&&list[e.currentTarget.dataset.index].data[e.currentTarget.dataset.idx].checked==true){
  for (let iii = 0; iii < e.currentTarget.dataset.idx; iii++) {
    list[e.currentTarget.dataset.index].data[iii].disabled=true
    list[e.currentTarget.dataset.index].data[iii].checked=true
    
  }
 
}

if(e.currentTarget.dataset.idx==list[e.currentTarget.dataset.index].data.length-1&&list[e.currentTarget.dataset.index].data[e.currentTarget.dataset.idx].checked==true){
  list[e.currentTarget.dataset.index].checked=true

}else{
  list[e.currentTarget.dataset.index].checked=false
}

var totalfee=0
    for (let i = 0; i < list.length; i++) {
        var item = list[i].data;
        // if(list[i].checked){
            for (let j = 0; j < item.length; j++) {
           if(item[j].checked){
               //小于他的最后一个可选
        //    if(j>0){
            // item[j].disabled=false
        //    }
            totalfee=totalfee+(item[j].fee+item[j].latefee)*1
           }
            
            // }
        }
        
    }
    totalfee=totalfee.toFixed(2)

this.setData({
  list,
  totalfee
})
  },
  checkboxChange(e){
console.log(e)
var list =this.data.list
var items = list[e.currentTarget.dataset.index].data

var values = e.detail.value
var newfee=0
    for (let i = 0, lenI = items.length; i < lenI; ++i) {
      items[i].checked = false
    
      for (let j = 0, lenJ = values.length; j < lenJ; ++j) {
        if (items[i].id === values[j]) {
          items[i].checked = true
          newfee=newfee+(items[i].fee+items[i].latefee)*1
          break
        }
      }
    }
    var totalfee=0
    for (let i = 0; i < list.length; i++) {
        var item = list[i].data;
        if(list[i].checked){
            for (let j = 0; j < item.length; j++) {
           if(item[j].checked){
               //小于他的最后一个可选
        //    if(j>0){
            // item[j].disabled=false
        //    }
            totalfee=totalfee+(item[j].fee+item[j].latefee)*1
           }
            
            }
        }
        
    }
    newfee=newfee.toFixed(2)
    totalfee=totalfee.toFixed(2)
    console.log(items,newfee,totalfee)
    list[e.currentTarget.dataset.index].yearfee=newfee
    this.setData({
        list,
        totalfee
    })

  },
  fanhui(){
wx.navigateBack()
  },
  his(){
    wx.navigateTo({
      url: '/pages/index/index?url='+encodeURIComponent(app.siteInfo.siteroot + '?i=' + app.siteInfo.uniacid + '&c=entry&op=myhisfeebill&pid=' + this.data.params.pid + '&rid=' + this.data.params.rid + '&bid=' + this.data.params.bid + '&tid=' + this.data.params.tid + '&hid=' + this.data.params.hid + '&do=fee&m=rhinfo_zyxq'),
    })
      },
  qingqiu(){
    var that=this;
    // i=19&c=entry&op=yearbill&pid=30&rid=35&bid=1184&tid=3041&hid=55863&do=fee&m=rhinfo_zyxq
    app.util.request({
        url: 'entry/site/openid',
        data: {
            'm': 'rhinfo_zyxq',
          'op': 'yearbill',
          'pid': that.data.params.pid ,
          'rid':  that.data.params.rid ,
          'bid': that.data.params.bid ,
          'tid': that.data.params.tid ,
          'hid': that.data.params.hid ,

        },
        cachetime: 0,
        showLoading: true,
        success: function (res) {
    console.log('返回',res)
    that.setData({
      list:res.data.result.billlist,
      room:res.data.result.room,
      region:res.data.result.region,
      member:res.data.result.member,
      totalfee:res.data.result.totalfee
    })
        }
      })
  },
  zhifu(){
    var that=this;
    var billid=''
    var totalfee=0;
    var list=that.data.list
for (let i = 0; i < list.length; i++) {
    var item = list[i].data;
    // if(list[i].checked){
        for (let j = 0; j < item.length; j++) {
       if(item[j].checked){
        billid =billid+ item[j].id+',';
        totalfee=totalfee+(item[j].fee+item[j].latefee)*1
       }
        
        // }
    }
    
}
billid = billid.substring(0,billid.length - 1);
totalfee=totalfee.toFixed(2)
console.log('选中账单',billid,totalfee)
    if(!billid||totalfee==0){
wx.showToast({
  title: '请选择要结算账单',
  icon:'none'
})
return
    }
    var str='fee='+totalfee+'&creditfee=0&billid='+billid;
    var params=this.base64_encode(str);
    // i=19&c=entry&op=yearbill&pid=30&rid=35&bid=1184&tid=3041&hid=55863&do=fee&m=rhinfo_zyxq
    app.util.request({
        url: 'entry/site/openid',
        data: {
            'm': 'rhinfo_zyxq',
            'op': 'pay',
            'pid': that.data.params.pid ,
          'rid':  that.data.params.rid ,
          'bid': that.data.params.bid ,
          'tid': that.data.params.tid ,
          'hid': that.data.params.hid ,
            params:params,
            feetype:1,
            iswxapp:1	

        },
        cachetime: 0,
        showLoading: true,
        success: function (res) {
    console.log('返回',res)
    var canshu=res.data.result
    wx.navigateToMiniProgram({
        // 测试环境: wxfe2ba11956414da1
        // 生产环境: wxe8a78f33c5f9b2ae
        
        // appId: 'wxfe2ba11956414da1',
        //  path: '/singlePay/pages/router/index?transType=01&canalType=15&canal=kmjxwy&itemCode=860974095&userNo='+data.mobile+'&filed1=phone&filed2='+data.CEB_CHANNEL_ID+'&filed3='+data.tid+'&merOrderNo='+data.uniontid+'&merOrderDate='+data.date+'&pushFlag=1&showwxpaytitle=1',
        appId:'wxe8a78f33c5f9b2ae',
         path: '/singlePay/pages/router/index?transType=01&canalType=15&canal='+canshu.region_cfg.CEB_CANAL+'&itemCode='+canshu.region_cfg.CEB_ITEMCODE+'&userNo='+canshu.mobile+'&filed1=phone&filed2='+canshu.region_cfg.CEB_CHANNEL_ID+'&filed3='+canshu.tid+'&merOrderNo='+canshu.uniontid+'&merOrderDate='+canshu.date+'&pushFlag=1&showwxpaytitle=1', 
  
        success(res) {
          wx.redirectTo({
            url: '/pages/index/index',
          })
        }
        })
        }
      }) 
  },
  base64_encode(str) {
    var c1, c2, c3;
    var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
    var i = 0, len = str.length, string = '';

    while (i < len) {
        c1 = str.charCodeAt(i++) & 0xff;
        if (i == len) {
            string += base64EncodeChars.charAt(c1 >> 2);
            string += base64EncodeChars.charAt((c1 & 0x3) << 4);
            string += "==";
            break;
        }
        c2 = str.charCodeAt(i++);
        if (i == len) {
            string += base64EncodeChars.charAt(c1 >> 2);
            string += base64EncodeChars.charAt(((c1 & 0x3) << 4)
                | ((c2 & 0xF0) >> 4));
            string += base64EncodeChars.charAt((c2 & 0xF) << 2);
            string += "=";
            break;
        }
        c3 = str.charCodeAt(i++);
        string += base64EncodeChars.charAt(c1 >> 2);
        string += base64EncodeChars.charAt(((c1 & 0x3) << 4)
            | ((c2 & 0xF0) >> 4));
        string += base64EncodeChars.charAt(((c2 & 0xF) << 2)
            | ((c3 & 0xC0) >> 6));
        string += base64EncodeChars.charAt(c3 & 0x3F)
    }
    return string
}
})