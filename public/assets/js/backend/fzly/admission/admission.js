define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/admission/admission/index' + location.search,
                    add_url: 'fzly/admission/admission/add',
                    edit_url: 'fzly/admission/admission/edit',
                    del_url: 'fzly/admission/admission/del',
                    multi_url: 'fzly/admission/admission/multi',
                    import_url: 'fzly/admission/admission/import',
                    table: 'fzly_admission',
                    dragsort_url:""
                }
            });

            var table = $("#table");
            $('.btn-add').data('area', ['80%', '80%']);
            table.on('post-body.bs.table', function(e, setting, json, xhr) {
                $('.btn-editone,.btn-edit').data('area', ['80%', '80%']);
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'attractions.title', title: __('所属景区'), operate: 'LIKE'},
                        {field: 'fl.title', title: __('门票分类'), operate: 'LIKE'},
                        {field: 'type_ids', title: __('类型'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'open_times', title: __('Open_times')},
                        {field: 'end_times', title: __('End_times')},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'images', title: __('Images'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'city', title: __('City'), operate: 'LIKE'},
                        {field: 'address', title: __('Address'), operate: 'LIKE'},
                        {field: 'flag', title: __('Flag'), searchList: {"hot":__('Flag hot'),"recommend":__('Flag recommend')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'tags', title: __('Tags'), operate: 'LIKE', formatter: Table.api.formatter.flag},
                        {field: 'score', title: __('Score'), operate: 'LIKE'},
                        {field: 'common_nums', title: __('Common_nums'), operate: 'LIKE'},
                        {field: 'hot', title: __('热度')},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Normal'),"hidden":__('Hidden')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'fzly/admission/admission/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), align: 'left'},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '140px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'fzly/admission/admission/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'fzly/admission/admission/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
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
            $(document).on("fa.event.appendfieldlist", "#xxmp .btn-append", function (e, obj) {
                //绑定动态下拉组件
                Form.events.selectpage(obj);
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
            $(document).on("fa.event.appendfieldlist", "#xxmp .btn-append", function (e, obj) {
                //绑定动态下拉组件
                Form.events.selectpage(obj);
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
