define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/promotionstat/index' + location.search,
                    add_url: 'fzly/promotionstat/add',
                    edit_url: 'fzly/promotionstat/edit',
                    del_url: 'fzly/promotionstat/del',
                    multi_url: 'fzly/promotionstat/multi',
                    import_url: 'fzly/promotionstat/import',
                    table: 'fzly_promotion_stat',
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
                        {field: 'promotion_id', title: __('Promotion_id')},
                        {field: 'stat_date', title: __('Stat_date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'receive_count', title: __('Receive_count')},
                        {field: 'use_count', title: __('Use_count')},
                        {field: 'sales_increase', title: __('Sales_increase')},
                        {field: 'discount_amount', title: __('Discount_amount'), operate:'BETWEEN'},
                        {field: 'verification_rate', title: __('Verification_rate'), operate:'BETWEEN'},
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
