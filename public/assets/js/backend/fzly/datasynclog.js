define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/datasynclog/index' + location.search,
                    add_url: 'fzly/datasynclog/add',
                    edit_url: 'fzly/datasynclog/edit',
                    del_url: 'fzly/datasynclog/del',
                    multi_url: 'fzly/datasynclog/multi',
                    import_url: 'fzly/datasynclog/import',
                    table: 'fzly_data_sync_log',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'module', title: __('Module'), operate: 'LIKE'},
                        {field: 'sync_time', title: __('Sync_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'data_count', title: __('Data_count')},
                        {field: 'status', title: __('Status'), searchList: {"success":__('Success'),"fail":__('Fail')}, formatter: Table.api.formatter.status},
                        {field: 'duration', title: __('Duration')},
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
