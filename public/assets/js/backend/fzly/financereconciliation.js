define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/financereconciliation/index' + location.search,
                    add_url: 'fzly/financereconciliation/add',
                    edit_url: 'fzly/financereconciliation/edit',
                    del_url: 'fzly/financereconciliation/del',
                    multi_url: 'fzly/financereconciliation/multi',
                    import_url: 'fzly/financereconciliation/import',
                    table: 'fzly_finance_reconciliation',
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
                        {field: 'reconciliation_no', title: __('Reconciliation_no'), operate: 'LIKE'},
                        {field: 'start_date', title: __('Start_date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'end_date', title: __('End_date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'total_sales', title: __('Total_sales'), operate:'BETWEEN'},
                        {field: 'total_cost', title: __('Total_cost'), operate:'BETWEEN'},
                        {field: 'total_profit', title: __('Total_profit'), operate:'BETWEEN'},
                        {field: 'reconciliation_status', title: __('Reconciliation_status'), searchList: {"pending":__('Pending'),"completed":__('Completed'),"abnormal":__('Abnormal')}, formatter: Table.api.formatter.status},
                        {field: 'reconciliation_time', title: __('Reconciliation_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operator_id', title: __('Operator_id')},
                        {field: 'operator_name', title: __('Operator_name'), operate: 'LIKE'},
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
