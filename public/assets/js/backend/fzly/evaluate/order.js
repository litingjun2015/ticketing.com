define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/evaluate/order/index' + location.search,
                    add_url: 'fzly/evaluate/order/add',
                    edit_url: 'fzly/evaluate/order/edit',
                    del_url: 'fzly/evaluate/order/del',
                    multi_url: 'fzly/evaluate/order/multi',
                    import_url: 'fzly/evaluate/order/import',
                    table: 'fzly_order_evaluate',
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
                        {field: 'user.username', title: __('评论人'), operate: 'LIKE'},
                        {field: 'order_id', title: __('订单ID')},
                        {field: 'order_type', title: __('Order_type'), searchList: {"1":__('Order_type 1'),"2":__('Order_type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'admission.title', title: __('Admission.title'), operate: 'LIKE'},
                        {field: 'gudie', title: __('导游'), operate: 'LIKE'},
                        {field: 'score', title: __('Score'), operate: 'LIKE'},
                        {field: 'evaluate', title: __('Evaluate'), operate: false},
                        {field: 'is_nm', title: __('Is_nm'), searchList: {"0":__('否'),"1":__('是')}, formatter: Table.api.formatter.normal},
                        {field: 'images', title: __('Images'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
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
