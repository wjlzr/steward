@extends('admin.layoutList')
@section('css')
    <style>
        .col-extend-css {
            width:100%;
            text-align: left;
        }
        .app-content{
            min-height:0;
        }
        .app-title{
            height:10px;
        }
    </style>
@endsection

@section('search')
    <div class="form-group" >
        <label for="app_name">门店编号：</label>
        <input style="width: 125px" type="text" class="form-control" name="code" id="code">&nbsp;&nbsp;
    </div>
    <div class="form-group" >
        <label for="mall_code">门店名称：</label>
        <input style="width: 125px" type="text" class="form-control" name="name" id="name">
    </div>

@endsection

@section('extend-content')
    <div style="text-align: center;">
        <button id="select-mall" class="btn btn-border-blue">选择</button>
        <button id="mall-cancel-btn" class="btn btn-border-blue">关闭</button>
    </div>
@endsection

@section('js')
    <script type="text/javascript">

        var layui_table_ajax_url = '/admin/plugin/mall/search';

        layui_table({
            sort_name : 'created_at',
            sort_order : 'desc',
            cols: [[
                {type:'checkbox', event: 'checkbox'},
                {title: '门店编号', field: 'code', align: 'center' , width:'' },
                {title: '门店名称', field: 'name', align: 'center' , width:'' }
            ]]
        });

        $(document).on('click','#mall-cancel-btn',function(){
            mall.close();
        });

        var mall = {

            check_data: {},

            choose_num: 0,

            close: function () {
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
            }
        };

        //选择门店
        $(document).on('click','#select-mall',function(){

            var dt = {
                'id_arr' : []
            };
            var check_status = table.checkStatus('layui-table');
            var check_status_data = check_status.data;
            if (check_status_data.length <= 0) {
                layer.msg('请至少选择一个门店',{ icon : 2 , time : 1000 ,shade: [0.15, 'black'] });
                return false;
            }
            $.each(check_status_data, function(k, v){
                dt.id_arr.push(v.id);
            });

            $.ajax({
                type:'GET',
                url:'/admin/plugin/mall/query',
                data: dt,
                dataType: 'json',
                success: function (obj) {
                    if(obj.code == 200){
                        $.each(obj.data,function(k,v){
                            mall.check_data[v.id] = {
                                id: v.id,
                                name: v.name,
                                code: v.code,
                                city: v.city,
                                spec: v.spec,
                                business_time: v.business_time
                            };
                            mall.choose_num = k + 1;
                        });
                    }
                },
                complete: function(){
                    parent.plugin.mall(mall.check_data,mall.choose_num);
                    mall.close();
                }
            });
        });

    </script>
@endsection
