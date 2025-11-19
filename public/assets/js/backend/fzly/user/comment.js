define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/user/comment/index' + location.search,
                    add_url: 'fzly/user/comment/add',
                    // edit_url: 'fzly/user/comment/edit',
                    del_url: 'fzly/user/comment/del',
                    multi_url: 'fzly/user/comment/multi',
                    import_url: 'fzly/user/comment/import',
                    table: 'fzly_comment',
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
                        {field: 'user.username', title: __('评论用户'), operate: 'LIKE'},
                        {field: 'content', title: __('Content'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), searchList: {"1":__('动态'),"2":__('其他'),"3":__('门票')}, formatter: Table.api.formatter.normal},
                        {field: 'is_del', title: __('Is_del'), searchList: {"0":__('否'),"1":__('是')}, formatter: Table.api.formatter.normal},
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
