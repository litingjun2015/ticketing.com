define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/promotionrule/index' + location.search,
                    add_url: 'fzly/promotionrule/add',
                    edit_url: 'fzly/promotionrule/edit',
                    del_url: 'fzly/promotionrule/del',
                    multi_url: 'fzly/promotionrule/multi',
                    import_url: 'fzly/promotionrule/import',
                    table: 'fzly_promotion_rule',
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
                        {field: 'promotion_id', title: __('Promotion_id')},
                        {field: 'condition_amount', title: __('Condition_amount'), operate:'BETWEEN'},
                        {field: 'discount_value', title: __('Discount_value'), operate:'BETWEEN'},
                        {field: 'limit_per_person', title: __('Limit_per_person')},
                        {field: 'total_quota', title: __('Total_quota')},
                        {field: 'member_level', title: __('Member_level'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
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
