define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/refund/index' + location.search,
                    add_url: 'fzly/refund/add',
                    edit_url: 'fzly/refund/edit',
                    del_url: 'fzly/refund/del',
                    multi_url: 'fzly/refund/multi',
                    import_url: 'fzly/refund/import',
                    table: 'fzly_refund_order',
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
                        {field: 'refund_no', title: __('Refund_no'), operate: 'LIKE'},
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'order_no', title: __('Order_no'), operate: 'LIKE'},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'order_type', title: __('Order_type')},
                        {field: 'refund_amount', title: __('Refund_amount'), operate:'BETWEEN'},
                        {field: 'fee_amount', title: __('Fee_amount'), operate:'BETWEEN'},
                        {field: 'actual_amount', title: __('Actual_amount'), operate:'BETWEEN'},
                        {field: 'refund_reason', title: __('Refund_reason'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'refund_type', title: __('Refund_type')},
                        {field: 'status', title: __('Status')},
                        {field: 'rule_id', title: __('Rule_id')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'handle_time', title: __('Handle_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'handle_note', title: __('Handle_note'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'transaction_id', title: __('Transaction_id'), operate: 'LIKE'},
                        {field: 'refund_transaction_id', title: __('Refund_transaction_id'), operate: 'LIKE'},
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
