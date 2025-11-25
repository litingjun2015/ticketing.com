define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/financeincome/index' + location.search,
                    add_url: 'fzly/financeincome/add',
                    edit_url: 'fzly/financeincome/edit',
                    del_url: 'fzly/financeincome/del',
                    multi_url: 'fzly/financeincome/multi',
                    import_url: 'fzly/financeincome/import',
                    table: 'fzly_finance_income',
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
                        {field: 'date', title: __('Date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'income_type', title: __('Income_type'), searchList: {"ticket":__('Ticket'),"product":__('Product'),"food":__('Food'),"timed_service":__('Timed_service'),"parking":__('Parking')}, formatter: Table.api.formatter.normal},
                        {field: 'channel', title: __('Channel'), searchList: {"offline":__('Offline'),"ota":__('Ota'),"mini_program":__('Mini_program'),"official":__('Official'),"other":__('Other')}, formatter: Table.api.formatter.normal},
                        {field: 'order_no', title: __('Order_no'), operate: 'LIKE'},
                        {field: 'amount', title: __('Amount'), operate:'BETWEEN'},
                        {field: 'status', title: __('Status'), searchList: {"pending":__('Pending'),"confirmed":__('Confirmed'),"refunded":__('Refunded')}, formatter: Table.api.formatter.status},
                        {field: 'remark', title: __('Remark'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
