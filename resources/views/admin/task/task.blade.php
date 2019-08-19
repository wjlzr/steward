@extends('admin.layoutAnalyse')

@section('content')

    <div class="app-title">
        <ul>
            <li class="cur">
                <span>任务管理</span>
            </li>
        </ul>
        <div class="right-btn">
        </div>
    </div>

    <div  class="app-content">

        <div class="row">

            <div class="col-lg-12">
                <div id="toolbar">

                    <div class="ebsig_container">
                        <div class="comm_right">
                            <div class="comm_content" style="width: 100%;margin:0px auto;">
                                @if(isset($task))
                                <ul class="nav nav-tabs" >
                                    @foreach($task as $key => $tt)
                                    @if($key == 1)
                                    <li id="active_{{$key}}" class="active"><a href="javaScript:void(0);"  onclick="sysTask.changeTask({{$key}});">{{$tt}}</a></li>
                                    @else
                                    <li id="active_{{$key}}" ><a href="javaScript:void(0);" onclick="sysTask.changeTask({{$key}});">{{$tt}}</a></li>
                                    @endif
                                    @endforeach
                                </ul>
                                @endif
                                <br>

                                <form name="task_form" id="task_form" method="post" class="form-inline" onsubmit="return false;">
                                    <div  class="form-inline">
                                        <input type="text" name="taskName" value="" id="taskName" class="form-control w150" placeholder="请输入任务名称">
                                        <input type="text" name="taskLink" value="" id ="taskLink"  class="form-control" style="width:300px" placeholder="请输入任务链接">
                                        <input type="button" value="查询" onclick="sysTask.search()" class="btn btn-primary" onfocus="this.blur();" />
                                        <input type="button" value="重置" onclick="sysTask.clear()" class="btn btn-warning" onfocus="this.blur();" />
                                        <input type="hidden" name="task_type" value="1" id="task_type">
                                    </div>
                                    <div style="margin-top:5px;">
                                        <table class="table table-bordered" >
                                            <tr>
                                                <th width="260">任务名称</th>
                                                <th width="*">任务链接</th>
                                                <th width="210">act值</th>
                                                <th width="70" style="text-align: center;">状态</th>
                                                <th width="180" style="text-align: center;">操作</th>
                                            </tr>
                                            <tbody id="main_content">
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                                <table id="table"></table>
                                <div>
                                    <input class="btn btn-primary" style="margin-top:5px;" type="button" value="添加任务" onclick="sysTask.edit();">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="pop" style="display: none;">

        <form id="addForm" onsubmit="return false;" class="form-horizontal" role="form" style="margin-top: 10px">
            {{csrf_field()}}
            <div class="form-group"  >
                <label class="col-sm-3 control-label" for="task_name"><span class="red pr5">*</span>任务名称：</label>
                <div class="col-sm-5">
                    <input class="form-control w300"  type="text" id="task_name" name="task_name"  placeholder="请输入任务名称" />
                </div>
            </div>

            <div class="form-group"  >
                <label class="col-sm-3 control-label" for="task_link" ><span class="red pr5">*</span>任务链接：</label>
                <div class="col-sm-5">
                    <input class="form-control w300"  type="text" id="task_link" name="task_link"  placeholder="请输入任务链接" />
                </div>
            </div>

            <div class="form-group"  >
                <label class="col-sm-3 control-label" for="task_act_value"><span class="red pr5">*</span>act值：</label>
                <div class="col-sm-5">
                    <input class="form-control w300"  type="text" id="task_act_value" name="task_act_value"  placeholder="请输入act值" />
                </div>
            </div>
            <input type="hidden" id="task_id" name="task_id">
        </form>

    </div>

@endsection

@section('js')
    <script type="text/javascript">

        $(document).ready(function(){
            sysTask.changeTask(1);
        });

        var sysTask = {

            task_type:'',
            task_id:'',
            status_task_id:'',
            status_task_status:'',
            del_task_id:'',
            show_task_id:'',
            load_index :'',
            //任务名称切换
            changeTask:function( task_type ){
                sysTask.task_type = task_type;
                sysTask.load_index = layer.load();
                $("li#active_"+task_type).siblings().removeClass("active");
                $("#active_"+task_type).addClass("active");
                $("#task_type").val( task_type );
                $("#taskName").val("");
                $("#taskLink").val("");

                E.ajax({
                    type: 'get',
                    url: '/admin/task/search',
                    data: {
                        task_type: task_type
                    },
                    success: function (o) {
                        sysTask.result(o)
                    }
                });
            },

            //任务名称切换回调
            result: function( o ) {
                layer.close(sysTask.load_index);
                $('#main_content').html('');
                var html = "";

                if (o.code == 200) {
                    var data = o.data;

                    $.each( data, function(k,v){

                        html += '<tr>';
                        html += '<td>'+ v.task_name +'</td>';
                        html += '<td style="white-space:nowrap">'+ v.task_link +'</td>';
                        html += '<td>'+ v.task_act_value +'</td>';
                        html += '<td style="text-align: center;">'+ v.task_status_value +'</td>';
                        html += '<td style="text-align: center;">';
                        html +='<a href="javaScript:void(0);" class="log_"'+v.task_id+ ' logid_value="'+v.task_id+'" onclick="sysTask.searchLog(1,'+v.task_id+');" >日志</a>&nbsp;&nbsp;&nbsp;';
                        if( v.task_status == 1 ){
                            html +='<a href="javaScript:void(0);" onclick="sysTask.changeStatus('+ v.task_status +','+ v.task_id +');" >暂停</a>&nbsp;&nbsp;&nbsp;';
                        }else{
                            html +='<a href="javaScript:void(0);" onclick="sysTask.changeStatus('+ v.task_status +',' + v.task_id +');" >运行</a>&nbsp;&nbsp;&nbsp;';
                        }

                        html +='<a href="javaScript:void(0);" onclick="sysTask.edit('+ v.task_id +');" >修改</a>&nbsp;&nbsp;&nbsp;';
                        html +='<a href="javaScript:void(0);" onclick="sysTask.delConfirm('+ v.task_id +');" >删除</a>';
                        html+='</td>';

                        html += '</tr>';
                    });
                    $('#main_content').html( html );

                }
            },

            //添加or编辑任务
            edit: function( task_id ) {

                sysTask.reset();

                var name = "";

                if ( task_id ) {
                    name = "修改任务";
                } else {
                    name = "添加任务";
                }

                layer.open({
                    type:1,
                    title: name,
                    content: $("#pop"),
                    offset:'50px',
                    area:'550px',
                    btn: ['确定', '取消'],
                    yes: function(){
                        sysTask.check();
                    },
                    btn2: function(){
                        sysTask.reset();
                    }
                });

                if ( task_id ) {
                    sysTask.task_id = task_id;

                    E.ajax({
                        type: 'get',
                        url: "/admin/task/get",
                        data: {
                            task_id: task_id
                        },
                        success: function ( o ) {

                            if( o.code == 200 ) {
                                $('#task_id').val(o.data.task_id);
                                $('#task_name').val(o.data.task_name);
                                $('#task_link').val(o.data.task_link);
                                $('#task_act_value').val(o.data.task_act_value);
                            }

                        }
                    });

                }
            },

            //编辑任务成功后 重置弹出层input框
            reset: function(){
                $('#task_name').val("");
                $('#task_id').val("");
                $('#task_link').val("");
                $('#task_act_value').val("");
                layer.closeAll();
            },

            //校验数据
            check: function (){
                this.dt = E.getFormValues("addForm");
                this.dt.task_type = sysTask.task_type;
                var error_msg = "";

                if (this.dt.task_name == '') {
                    error_msg += "任务名不能为空<br>";
                }
                if (E.isEmpty(this.dt.task_link)) {
                    error_msg += "任务链接不能为空<br>";
                }
                if (E.isEmpty(this.dt.task_act_value)) {
                    error_msg += "act值不能为空<br>";
                }

                if (error_msg != "") {
                    layer.alert(error_msg,{icon:2});
                    return false;
                } else {
                    if( sysTask.task_id != "" ){
                        var name = "修改";
                    }else{
                        var name = "添加";
                    }

                    var dt = E.getFormValues("addForm");
                    dt.task_type = sysTask.task_type;

                    layer.confirm('您确认'+name+'该任务吗?',{icon:3}, function () {

                        E.ajax({
                            type: 'post',
                            url: "/admin/task/edit",
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: dt,
                            success:function( o ) {
                                if( o.code == 200 ) {
                                    sysTask.changeTask( sysTask.task_type );
                                    layer.alert(o.message,{icon:1,time:1000}, function () {
                                        sysTask.reset();
                                    });
                                    setTimeout('sysTask.reset();' ,1000 );
                                }else{
                                    layer.alert(o.message,{icon:2});
                                }
                            }
                        });
                    });
                }
            },

            //运行or暂停任务确认
            changeStatus:function( task_status, task_id ){
                sysTask.status_task_id = task_id;
                sysTask.status_task_status = task_status;

                if( task_status == 1 ){
                    var name = "暂停";
                } else {
                    var name = "运行";
                }

                layer.confirm('您确认'+name+'该任务吗?', {icon:3}, function () {
                    E.ajax({
                        type: 'get',
                        url: "/admin/task/status",
                        data: {
                            task_id:sysTask.status_task_id,
                            task_status:sysTask.status_task_status
                        },
                        success:function( o ) {
                            if( o.code == 200 ) {
                                sysTask.changeTask( sysTask.task_type );
                                layer.alert(o.message,{icon:1,time:1000}, function () {
                                    sysTask.reset();
                                })
                                setTimeout('sysTask.reset();' ,1000 );
                            }else{
                                layer.alert(o.message,{icon:2});
                            }
                        }
                    });
                });
            },
            //删除任务确认
            delConfirm:function( task_id ){
                sysTask.del_task_id = task_id;

                layer.confirm('您确认删除该任务吗?',{icon:3}, function () {
                    E.ajax({
                        type: 'get',
                        url: "/admin/task/del",
                        data: {
                            task_id:sysTask.del_task_id
                        },
                        success: function ( o ) {
                            if( o.code == 200 ) {
                                sysTask.changeTask( sysTask.task_type );
                                layer.alert(o.message,{icon:1,time:1000}, function () {
                                    sysTask.reset();
                                });
                                setTimeout('sysTask.reset();' ,1000 );
                            }else{
                                layer.alert(o.message,{icon:2});
                            }
                        }
                    });
                });

            },
            
            //查询日志信息
            searchLog: function( page, task_id ) {

                if (task_id) {
                    sysTask.show_task_id = task_id
                }
                var load_index = layer.load();
                E.ajax({
                    type: 'get',
                    url: '/admin/task/log',
                    data: {
                        task_id: sysTask.show_task_id,
                        sortname: 'start_time',
                        sortorder: 'DESC',
                        rp: 8,
                        page: page
                    },
                    success: function ( o ) {

                        //关闭等待层
                        layer.close(load_index);
                        layer.closeAll();

                        var html = '';
                        html += '<table class="table table-striped table-bordered table-hover">';
                        html += '<tbody>';
                        html += '<tr>';
                        html += '<th width="90" style="text-align: center;">任务执行时间</th>';
                        html += '<th width="90" style="text-align: center;">任务结束时间</th>';
                        html += '<th width="80">总耗时时间</th>';
                        html += '<th width="160">任务执行结果</th>';

                        html += '</tr>';
                        if(o.data.total>0){
                            $.each(o.data.rows, function(k, v) {

                                html += '<tr>';
                                html += '<td style="text-align: center;">' + v.start_time + '</td>';
                                if( v.end_time ){
                                    html += '<td style="text-align: center;">' + v.end_time + '</td>';
                                }else{
                                    html += '<td></td>';
                                }

                                if( v.total_time ){
                                    html += '<td>' + v.total_time + '&nbsp;s</td>';
                                }else{
                                    html += '<td></td>';
                                }

                                if( v.result ){
                                    html += '<td>' + v.result + '</td>';
                                }else{
                                    html += '<td></td>';
                                }

                                html += '</tr>';

                            });
                        }else{
                            html += '<tr>';
                            html += '<td style="text-align: center" colspan="5">没有找到记录</td>';
                            html += '</tr>';
                        }

                        html += '</tbody>';
                        html += '</table>';

                        if (o.data.paging) {
                            html += o.data.paging;
                        }

                        layer.open({
                            type:1,
                            content: html,
                            offset:'20px',
                            title: '任务日志列表',
                            area:['800px','450px']
                        });
                    }
                });
            },
            search: function ( ) {
                var task_name = $("#taskName").val();
                var task_type = $("#task_type").val();
                var task_link = $("#taskLink").val();
                sysTask.load_index = layer.load();

                E.ajax({
                    type: 'get',
                    url: '/admin/task/search',
                    data: {
                        task_type: task_type,
                        task_name: task_name,
                        task_link: task_link
                    },
                    success: function (o) {
                        sysTask.result( o );
                    }
                });
            },
            clear: function () {
                $("#taskName").val("");
                $("#taskLink").val("");
                this.search();
            }
        }
    </script>

@endsection