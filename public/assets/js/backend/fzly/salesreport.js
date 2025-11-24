define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/salesreport/index' + location.search,
                    add_url: 'fzly/salesreport/add',
                    edit_url: 'fzly/salesreport/edit',
                    del_url: 'fzly/salesreport/del',
                    multi_url: 'fzly/salesreport/multi',
                    import_url: 'fzly/salesreport/import',
                    table: 'fzly_sales_report',
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
                        {field: 'product_id', title: __('Product_id')},
                        {field: 'product_name', title: __('Product_name'), operate: 'LIKE'},
                        {field: 'product_type', title: __('Product_type'), operate: 'LIKE'},
                        {field: 'channel', title: __('Channel'), searchList: {"offline":__('Offline'),"ota":__('Ota'),"mini_program":__('Mini_program'),"official":__('Official')}, formatter: Table.api.formatter.normal},
                        {field: 'sales_num', title: __('Sales_num')},
                        {field: 'sales_amount', title: __('Sales_amount'), operate:'BETWEEN'},
                        {field: 'refund_num', title: __('Refund_num')},
                        {field: 'refund_amount', title: __('Refund_amount'), operate:'BETWEEN'},
                        {field: 'actual_sales', title: __('Actual_sales'), operate:'BETWEEN'},
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
