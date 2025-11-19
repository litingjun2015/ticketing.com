define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'fzly/user/guide/withdraw/index' + location.search,
                    add_url: 'fzly/user/guide/withdraw/add',
                    // edit_url: 'fzly/user/guide/withdraw/edit',
                    del_url: 'fzly/user/guide/withdraw/del',
                    multi_url: 'fzly/user/guide/withdraw/multi',
                    import_url: 'fzly/user/guide/withdraw/import',
                    table: 'fzly_withdraw_log',
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
                        {field: 'withdraw_money', title: __('Withdraw_money'), operate:'BETWEEN'},
                        {field: 'leave_withdraw_money', title: __('Leave_withdraw_money'), operate:'BETWEEN'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2'),"3":__('Status 3'),"4":__('Status 4')}, formatter: Table.api.formatter.status},
                        {field: 'desc', title: __('Desc'), operate: 'LIKE'},
                        {field: 'draw_no', title: __('Draw_no'), operate: 'LIKE'},
                        {field: 'batch_id', title: __('Batch_id'), operate: 'LIKE'},
                        {
                            field: 'operate',
                            buttons: [
                                {
                                    name: 'process',
                                    text: __('审核'),
                                    title: __('审核'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-list',
                                    url: 'fzly/user/guide/withdraw/process',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    },
                                    visible: function (row) {
                                        if(row.status === '1')
                                        {return true;}
                                        else
                                        {return false;}
                                    }

                                }
                            ],
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate, formatter: Table.api.formatter.operate
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
        process: function () {
            $(".a").on('click',function (e){
                var v = $(this).val();
                if (v === '2'){
                    console.log(23423);
                    $("#jj_desc").show();
                }else{
                    $("#jj_desc").hide();
                }
            })
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
