define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/salesorder/index' + location.search,
                    add_url: 'fzly/salesorder/add',
                    edit_url: 'fzly/salesorder/edit',
                    del_url: 'fzly/salesorder/del',
                    multi_url: 'fzly/salesorder/multi',
                    import_url: 'fzly/salesorder/import',
                    table: 'fzly_sales_order',
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
                        {field: 'order_sn', title: __('Order_sn'), operate: 'LIKE'},
                        {field: 'channel', title: __('Channel'), searchList: {"ticket_window":__('Ticket_window'),"restaurant":__('Restaurant'),"online":__('Online')}, formatter: Table.api.formatter.normal},
                        {field: 'total_amount', title: __('Total_amount'), operate:'BETWEEN'},
                        {field: 'status', title: __('Status'), searchList: {"pending":__('Pending'),"completed":__('Completed'),"cancelled":__('Cancelled')}, formatter: Table.api.formatter.status},
                        {field: 'operate_id', title: __('Operate_id')},
                        {field: 'operate_name', title: __('Operate_name'), operate: 'LIKE'},
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
