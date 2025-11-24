define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/devicereport/index' + location.search,
                    add_url: 'fzly/devicereport/add',
                    edit_url: 'fzly/devicereport/edit',
                    del_url: 'fzly/devicereport/del',
                    multi_url: 'fzly/devicereport/multi',
                    import_url: 'fzly/devicereport/import',
                    table: 'fzly_device_report',
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
                        {field: 'device_id', title: __('Device_id')},
                        {field: 'device_name', title: __('Device_name'), operate: 'LIKE'},
                        {field: 'device_type', title: __('Device_type'), operate: 'LIKE'},
                        {field: 'date', title: __('Date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Normal'),"warning":__('Warning'),"error":__('Error')}, formatter: Table.api.formatter.status},
                        {field: 'operation_time', title: __('Operation_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'fault_time', title: __('Fault_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'fault_count', title: __('Fault_count')},
                        {field: 'data_count', title: __('Data_count')},
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
