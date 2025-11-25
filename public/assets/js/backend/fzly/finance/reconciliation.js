define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/finance/reconciliation/index' + location.search,
                    add_url: 'fzly/finance/reconciliation/add',
                    edit_url: 'fzly/finance/reconciliation/edit',
                    del_url: 'fzly/finance/reconciliation/del',
                    multi_url: 'fzly/finance/reconciliation/multi',
                    import_url: 'fzly/finance/reconciliation/import',
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
                        {field: 'reconciliation_type', title: __('Reconciliation_type'), searchList: {"ota":__('Ota'),"supplier":__('Supplier'),"bank":__('Bank')}, formatter: Table.api.formatter.normal},
                        {field: 'partner', title: __('Partner'), operate: 'LIKE'},
                        {field: 'start_date', title: __('Start_date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'end_date', title: __('End_date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'system_amount', title: __('System_amount'), operate:'BETWEEN'},
                        {field: 'partner_amount', title: __('Partner_amount'), operate:'BETWEEN'},
                        {field: 'difference', title: __('Difference'), operate:'BETWEEN'},
                        {field: 'status', title: __('Status'), searchList: {"pending":__('Pending'),"reconciling":__('Reconciling'),"completed":__('Completed'),"abnormal":__('Abnormal')}, formatter: Table.api.formatter.status},
                        {field: 'file_url', title: __('File_url'), operate: 'LIKE', formatter: Table.api.formatter.url},
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
