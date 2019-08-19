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
        <label for="mall_code">标题：</label>
        <input style="width: 125px;margin-top: -4px;" type="text" class="form-control" name="title" id="name">
        <input type="hidden" value="{{ $export_index or '' }}" name="export_index">
    </div>

@endsection

@section('extend-content')

@endsection

@section('js')
    <script type="text/javascript">

        var layui_table_ajax_url = '/plugin/export/search';

        layui_table({
            sort_name : 'created_at',
            sort_order : 'desc',
            cols: [[
                {title: '操作', field: 'operation', align: 'center' , width:100 },
                {title: '流水号', field: 'export_id', align: 'center' , width:100 },
                {title: '标题', field: 'title', align: 'center' , width: 280 },
                {title: '文件状态', field: 'status_name', align: 'center' , width:'' },
                {title: '失败信息', field: 'error_msg', align: 'center' , width:'' },
                {title: '更新时间', field: 'created_at', align: 'center' , width:180 }
            ]]
        });

        $(document).on('click','.download',function(){

            var export_id = $(this).attr('data-id');

            window.location.href = '/plugin/export/download/'+ export_id ;

        })

    </script>
@endsection
