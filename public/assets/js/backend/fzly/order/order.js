define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/order/order/index' + location.search,
                    edit_url: 'fzly/order/order/edit',
                    del_url: 'fzly/order/order/del',
                    multi_url: 'fzly/order/order/multi',
                    import_url: 'fzly/order/order/import',
                    table: 'fzly_order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'order_no', title: __('Order_no'), operate: 'LIKE'},
                        {field: 'order_type', title: __('Order_type'), searchList: {"1":__('Order_type 1'),"2":__('Order_type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'user.username', title: __('下单用户'), operate: 'LIKE'},
                        // {field: 'guide_name', title: __('导游名称')},
                        // {field: 'guide_id', title: __('导游id')},
                        {field: 'goods_id', title: __('购买商品')},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4'),"5":__('Status 5')}, formatter: Table.api.formatter.status},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'count', title: __('Count')},
                        {field: 'order_amount_total', title: __('Order_amount_total'), operate:'BETWEEN'},
                        {field: 'out_trade_no', title: __('Out_trade_no'), operate: 'LIKE'},
                        {field: 'remark', title: __('Remark'), operate: 'LIKE'},
                        {field: 'pay_time', title: __('Pay_time'), operate: 'LIKE',autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('出行详情'),
                                    title: __('出行详情'),

                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-edit',
                                    url: 'fzly/order/order/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    success:function (data) {
                                        $(".btn-refresh").trigger("click");
                                    }
                                },
                                {
                                    name: 'detail',
                                    text: __('查看评论'),
                                    title: __('查看评论'),
                                    extend: 'data-area=\'["100%", "100%"]\'',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-edit',
                                    url: 'fzly/evaluate/order/index?order_id={id}',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    success:function (data) {
                                        $(".btn-refresh").trigger("click");
                                    }
                                },

                                {
                                    name: 'refund',
                                    text: __('退款审核'),
                                    title: __('退款审核'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa',
                                    url: 'fzly/order/order/refund',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    success:function (data) {
                                        $(".btn-refresh").trigger("click");
                                    },
                                    visible: function (row) {
                                        if(row.status === "4")
                                        {return true;}
                                        else
                                        {return false;}
                                    }
                                },



                            ],
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
		refund: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
