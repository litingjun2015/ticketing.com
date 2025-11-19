define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/user/guide/product/index' + location.search,
                    add_url: 'fzly/user/guide/product/add',
                    edit_url: 'fzly/user/guide/product/edit',
                    del_url: 'fzly/user/guide/product/del',
                    multi_url: 'fzly/user/guide/product/multi',
                    import_url: 'fzly/user/guide/product/import',
                    table: 'fzly_guide_product',
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
                        {field: 'user.username', title: __('导游'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'type.title', title: __('产品分类'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'jq_title', title: __('Jq_title'), operate: 'LIKE'},
                        {field: 'zm_image', title: __('Zm_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'price', title: __('价格')},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4')}, formatter: Table.api.formatter.status},
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
            Template.helper("Fast", Fast);

            $(document).on("fa.event.appendfieldlist", "#second-table .btn-append", function (e, obj) {
                //绑定上传组件
                Form.events.faupload(obj);
                //上传成功回调事件，变更按钮的背景
                $(".upload-imagess", obj).data("upload-success", function (data) {
                    $(this).css("background-image", "url('" + Fast.api.cdnurl(data.url) + "')");
                })
            });
            Controller.api.bindevent();
        },
        edit: function () {
            Template.helper("Fast", Fast);

            $(document).on("fa.event.appendfieldlist", "#second-table .btn-append", function (e, obj) {
                //绑定上传组件
                Form.events.faupload(obj);
                //上传成功回调事件，变更按钮的背景
                $(".upload-imagess", obj).data("upload-success", function (data) {
                    $(this).css("background-image", "url('" + Fast.api.cdnurl(data.url) + "')");
                })
            });
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
