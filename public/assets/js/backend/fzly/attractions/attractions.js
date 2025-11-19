define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/attractions/attractions/index' + location.search,
                    add_url: 'fzly/attractions/attractions/add',
                    edit_url: 'fzly/attractions/attractions/edit',
                    del_url: 'fzly/attractions/attractions/del',
                    multi_url: 'fzly/attractions/attractions/multi',
                    import_url: 'fzly/attractions/attractions/import',
                    table: 'fzly_attractions',
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
                        {field: 'user.username', title: __('所属用户'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'desc', title: __('景点简述'), operate: 'LIKE'},
                        {field: 'city', title: __('景点城市'), operate: 'LIKE'},
                        // {field: 'type.title', title: __('分类'), operate: 'LIKE'},
                        {field: 'image', title: __('缩略图'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'hot', title: __('Hot')},
                        {field: 'money', title: __('收益')},
                        {field: 'name', title: __('姓名'), operate: 'LIKE'},
                        {field: 'tel', title: __('手机号'), operate: 'LIKE'},
                        {field: 'card', title: __('身份证号'), operate: 'LIKE'},
                        {field: 'yy_image', title: __('营业执照'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},

                        {field: 'status', title: __('审核状态'), searchList: {"0":__('未审核'),"1":__('审核通过'),"2":__('审核拒绝')}, formatter: Table.api.formatter.status},

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
