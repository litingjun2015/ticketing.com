define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/user/user/index' + location.search,
                    add_url: 'fzly/user/user/add',
                    edit_url: 'fzly/user/user/edit',
                    del_url: 'fzly/user/user/del',
                    multi_url: 'fzly/user/user/multi',
                    import_url: 'fzly/user/user/import',
                    table: 'user',
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
                        {field: 'username', title: __('Username'), operate: 'LIKE'},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'avatar', title: __('Avatar'), operate: 'LIKE', events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'detail.back_avatar', title: __('Detail.back_avatar'), operate: 'LIKE', events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'gender', title: __('Gender'), searchList: {"0":__('女'),"1":__('男')}, formatter: Table.api.formatter.status},
                        {field: 'birthday', title: __('Birthday'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        {field: 'detail.like_s', title: __('Detail.like_s')},
                        {field: 'detail.gz_s', title: __('Detail.gz_s')},
                        {field: 'detail.fs_s', title: __('Detail.fs_s')},
                        {field: 'detail.view_s', title: __('Detail.view_s')},
                        {field: 'detail.is_dy', title: __('Detail.is_dy'), searchList: {"0":__('否'),"1":__('是')}, formatter: Table.api.formatter.status},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('正常'),"hidden":__('隐藏')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            buttons: [
                                {
                                    name: 'detail',
                                    text: __('查看订单'),
                                    title: __('查看订单'),
                                    extend: 'data-area=\'["100%", "100%"]\'',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-edit',
                                    url: 'fzly/order/order/index?guide_id={id}',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    success:function (data) {
                                        $(".btn-refresh").trigger("click");
                                    },
                                    visible: function (row) {
                                        if(row.detail.is_dy === 1)
                                        {return true;}
                                        else
                                        {return false;}
                                    }
                                },
                            ],
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
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
