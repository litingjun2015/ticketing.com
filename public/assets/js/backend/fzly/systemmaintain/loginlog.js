define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/systemmaintain/loginlog/index' + location.search,
                    add_url: 'fzly/systemmaintain/loginlog/add',
                    edit_url: 'fzly/systemmaintain/loginlog/edit',
                    del_url: 'fzly/systemmaintain/loginlog/del',
                    multi_url: 'fzly/systemmaintain/loginlog/multi',
                    import_url: 'fzly/systemmaintain/loginlog/import',
                    table: 'fzly_login_log',
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
                        {field: 'login_type', title: __('Login_type')},
                        {field: 'status', title: __('Status')},
                        {field: 'message', title: __('Message'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'ip', title: __('Ip'), operate: 'LIKE'},
                        {field: 'location', title: __('Location'), operate: 'LIKE'},
                        {field: 'useragent', title: __('Useragent'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'logintime', title: __('Logintime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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
