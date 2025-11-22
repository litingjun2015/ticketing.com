define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/promotionproduct/index' + location.search,
                    add_url: 'fzly/promotionproduct/add',
                    edit_url: 'fzly/promotionproduct/edit',
                    del_url: 'fzly/promotionproduct/del',
                    multi_url: 'fzly/promotionproduct/multi',
                    import_url: 'fzly/promotionproduct/import',
                    table: 'fzly_promotion_product',
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
                        {field: 'product_type', title: __('Product_type'), searchList: {"ticket":__('Ticket'),"goods":__('Goods')}, formatter: Table.api.formatter.normal},
                        {field: 'product_id', title: __('Product_id')},
                        {field: 'product_name', title: __('Product_name'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
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
