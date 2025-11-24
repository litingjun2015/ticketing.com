define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/visitorreport/index' + location.search,
                    add_url: 'fzly/visitorreport/add',
                    edit_url: 'fzly/visitorreport/edit',
                    del_url: 'fzly/visitorreport/del',
                    multi_url: 'fzly/visitorreport/multi',
                    import_url: 'fzly/visitorreport/import',
                    table: 'fzly_visitor_report',
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
                        {field: 'entry_num', title: __('Entry_num')},
                        {field: 'exit_num', title: __('Exit_num')},
                        {field: 'current_num', title: __('Current_num')},
                        {field: 'staff_entry', title: __('Staff_entry')},
                        {field: 'onsite_ticket', title: __('Onsite_ticket')},
                        {field: 'ota_ticket', title: __('Ota_ticket')},
                        {field: 'official_ticket', title: __('Official_ticket')},
                        {field: 'max_capacity', title: __('Max_capacity')},
                        {field: 'instant_max_capacity', title: __('Instant_max_capacity')},
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
