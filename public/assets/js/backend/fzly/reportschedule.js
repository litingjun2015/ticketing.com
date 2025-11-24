define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/reportschedule/index' + location.search,
                    add_url: 'fzly/reportschedule/add',
                    edit_url: 'fzly/reportschedule/edit',
                    del_url: 'fzly/reportschedule/del',
                    multi_url: 'fzly/reportschedule/multi',
                    import_url: 'fzly/reportschedule/import',
                    table: 'fzly_report_schedule',
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
                        {field: 'report_type', title: __('Report_type'), searchList: {"sales":__('Sales'),"stock":__('Stock'),"visitor":__('Visitor'),"device":__('Device')}, formatter: Table.api.formatter.normal},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'frequency', title: __('Frequency'), searchList: {"daily":__('Daily'),"weekly":__('Weekly'),"monthly":__('Monthly')}, formatter: Table.api.formatter.normal},
                        {field: 'send_time', title: __('Send_time'), operate: 'LIKE'},
                        {field: 'format', title: __('Format'), searchList: {"excel":__('Excel'),"pdf":__('Pdf')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'last_send_time', title: __('Last_send_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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
