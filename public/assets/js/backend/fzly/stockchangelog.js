define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/stockchangelog/index' + location.search,
                    add_url: 'fzly/stockchangelog/add',
                    edit_url: 'fzly/stockchangelog/edit',
                    del_url: 'fzly/stockchangelog/del',
                    multi_url: 'fzly/stockchangelog/multi',
                    import_url: 'fzly/stockchangelog/import',
                    table: 'fzly_stock_change_log',
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
                        {field: 'product_id', title: __('Product_id')},
                        {field: 'type', title: __('Type'), searchList: {"purchase":__('Purchase'),"sale":__('Sale'),"adjust":__('Adjust'),"check":__('Check')}, formatter: Table.api.formatter.normal},
                        {field: 'related_id', title: __('Related_id')},
                        {field: 'quantity', title: __('Quantity')},
                        {field: 'before_quantity', title: __('Before_quantity')},
                        {field: 'after_quantity', title: __('After_quantity')},
                        {field: 'operate_id', title: __('Operate_id')},
                        {field: 'operate_name', title: __('Operate_name'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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
