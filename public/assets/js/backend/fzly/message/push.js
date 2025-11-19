define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/message/push/index' + location.search,
                    add_url: 'fzly/message/push/add',
                    edit_url: 'fzly/message/push/edit',
                    del_url: 'fzly/message/push/del',
                    multi_url: 'fzly/message/push/multi',
                    import_url: 'fzly/message/push/import',
                    table: 'fzly_message_push',
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
                        {field: 'message_type', title: __('Message_type'), searchList: {"1":__('Message_type 1'),"2":__('Message_type 2'),"3":__('Message_type 3'),"4":__('Message_type 4'),"5":__('Message_type 5')}, formatter: Table.api.formatter.normal},
                        {field: 'masterplate', title: __('Masterplate'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            $(document).on("fa.event.appendfieldlist", "#second-table .btn-append", function (e, obj) {
                //绑定动态下拉组件
                Form.events.selectpage(obj);
            });
            Controller.api.bindevent();
        },
        edit: function () {
            $(document).on("fa.event.appendfieldlist", "#second-table .btn-append", function (e, obj) {
                //绑定动态下拉组件
                Form.events.selectpage(obj);
            });
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
