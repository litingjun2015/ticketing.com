define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/financeexpense/index' + location.search,
                    add_url: 'fzly/financeexpense/add',
                    edit_url: 'fzly/financeexpense/edit',
                    del_url: 'fzly/financeexpense/del',
                    multi_url: 'fzly/financeexpense/multi',
                    import_url: 'fzly/financeexpense/import',
                    table: 'fzly_finance_expense',
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
                        {field: 'expense_type', title: __('Expense_type'), searchList: {"purchase":__('Purchase'),"ota_commission":__('Ota_commission'),"equipment_maintenance":__('Equipment_maintenance'),"salary":__('Salary'),"other":__('Other')}, formatter: Table.api.formatter.normal},
                        {field: 'amount', title: __('Amount'), operate:'BETWEEN'},
                        {field: 'voucher_id', title: __('Voucher_id')},
                        {field: 'supplier', title: __('Supplier'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"pending":__('Pending'),"confirmed":__('Confirmed'),"canceled":__('Canceled')}, formatter: Table.api.formatter.status},
                        {field: 'remark', title: __('Remark'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
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
