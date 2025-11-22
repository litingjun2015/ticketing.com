define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/stocktimeslot/index' + location.search,
                    add_url: 'fzly/stocktimeslot/add',
                    edit_url: 'fzly/stocktimeslot/edit',
                    del_url: 'fzly/stocktimeslot/del',
                    multi_url: 'fzly/stocktimeslot/multi',
                    import_url: 'fzly/stocktimeslot/import',
                    table: 'fzly_stock_time_slot',
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
                        {field: 'stock_id', title: __('Stock_id')},
                        {field: 'ticket_type', title: __('Ticket_type'), operate: 'LIKE'},
                        {field: 'channel', title: __('Channel'), operate: 'LIKE'},
                        {field: 'date', title: __('Date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'time_slot', title: __('Time_slot'), operate: 'LIKE'},
                        {field: 'start_time', title: __('Start_time')},
                        {field: 'end_time', title: __('End_time')},
                        {field: 'total_stock', title: __('Total_stock')},
                        {field: 'used_stock', title: __('Used_stock')},
                        {field: 'available_stock', title: __('Available_stock')},
                        {field: 'lock_stock', title: __('Lock_stock')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
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
