define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/systemmaintain/datachangelog/index' + location.search,
                    add_url: 'fzly/systemmaintain/datachangelog/add',
                    edit_url: 'fzly/systemmaintain/datachangelog/edit',
                    del_url: 'fzly/systemmaintain/datachangelog/del',
                    multi_url: 'fzly/systemmaintain/datachangelog/multi',
                    import_url: 'fzly/systemmaintain/datachangelog/import',
                    table: 'fzly_data_change_log',
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
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'username', title: __('Username'), operate: 'LIKE'},
                        {field: 'table_name', title: __('Table_name'), operate: 'LIKE'},
                        {field: 'table_id', title: __('Table_id')},
                        {field: 'action', title: __('Action'), operate: 'LIKE'},
                        {field: 'ip', title: __('Ip'), operate: 'LIKE'},
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
