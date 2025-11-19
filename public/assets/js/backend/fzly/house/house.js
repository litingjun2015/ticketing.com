define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/house/house/index' + location.search,
                    add_url: 'fzly/house/house/add',
                    edit_url: 'fzly/house/house/edit',
                    del_url: 'fzly/house/house/del',
                    multi_url: 'fzly/house/house/multi',
                    import_url: 'fzly/house/house/import',
                    table: 'fzly_house',
                }
            });

            var table = $("#table");

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
                        {field: 'brand.title', title: __('Brand.title'), operate: 'LIKE'},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'city', title: __('City'), operate: 'LIKE'},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'stay_type', title: __('Stay_type'), searchList: {"1":__('Stay_type 1'),"2":__('Stay_type 2'),"3":__('Stay_type 3'),"4":__('Stay_type 4'),"5":__('Stay_type 5'),"6":__('Stay_type 6'),"7":__('Stay_type 7')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'grade', title: __('Grade'), searchList: {"1":__('Grade 1'),"2":__('Grade 2'),"3":__('Grade 3'),"4":__('Grade 4')}, formatter: Table.api.formatter.normal},
                        {field: 'facilities', title: __('Facilities'), searchList: {"1":__('Facilities 1'),"2":__('Facilities 2'),"3":__('Facilities 3'),"4":__('Facilities 4'),"5":__('Facilities 5')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'images', title: __('Images'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.images},
                        {field: 'address', title: __('Address'), operate: 'LIKE'},
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
                url: 'fzly/house/house/recyclebin' + location.search,
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
                                    url: 'fzly/house/house/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'fzly/house/house/destroy',
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
            $(document).on("fa.event.appendfieldlist", "#second-table1 .btn-append", function (e, obj) {
                //绑定上传组件
                Form.events.faupload(obj);
                //上传成功回调事件，变更按钮的背景
                $(".upload-imagesss", obj).data("upload-success", function (data) {
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
            $(document).on("fa.event.appendfieldlist", "#second-table1 .btn-append", function (e, obj) {
                //绑定上传组件
                Form.events.faupload(obj);
                //上传成功回调事件，变更按钮的背景
                $(".upload-imagesss", obj).data("upload-success", function (data) {
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
