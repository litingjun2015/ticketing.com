define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/financeprofit/index' + location.search,
                    add_url: 'fzly/financeprofit/add',
                    edit_url: 'fzly/financeprofit/edit',
                    del_url: 'fzly/financeprofit/del',
                    multi_url: 'fzly/financeprofit/multi',
                    import_url: 'fzly/financeprofit/import',
                    table: 'fzly_finance_profit',
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
                        {field: 'period_type', title: __('Period_type'), searchList: {"daily":__('Daily'),"monthly":__('Monthly'),"yearly":__('Yearly')}, formatter: Table.api.formatter.normal},
                        {field: 'period', title: __('Period'), operate: 'LIKE'},
                        {field: 'total_income', title: __('Total_income'), operate:'BETWEEN'},
                        {field: 'total_expense', title: __('Total_expense'), operate:'BETWEEN'},
                        {field: 'gross_profit', title: __('Gross_profit'), operate:'BETWEEN'},
                        {field: 'net_profit', title: __('Net_profit'), operate:'BETWEEN'},
                        {field: 'profit_rate', title: __('Profit_rate'), operate:'BETWEEN'},
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
