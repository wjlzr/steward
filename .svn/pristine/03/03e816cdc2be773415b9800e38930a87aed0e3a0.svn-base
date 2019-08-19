@extends('admin.layoutEdit')
@section('css')
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/libs/bootstrap-table-master/dist/bootstrap-table.min.css">
    <link href="/libs/layui/css/layui.css" rel="stylesheet">
    <link href="/css/admin/online.css?v=20171214" rel="stylesheet">
    <link href="/libs/iCheck/skins/square/blue.css" rel="stylesheet">
    <style>
        .form-inline {
            display: inline-block;
            width:auto
        }
        .good-logo{
            width:170px;
            text-align:left
        }
        a {
            color : #337ab7;
            text-decoration: none;
            cursor: pointer;
        }

    </style>

@endsection

@section('title')
    <li class="cur bill-detail">
        <span>商品详情</span>
    </li>
@endsection

@section('go-back-btn')
    <button class="btn btn-default layer-go-back" type="button">返回</button>
@endsection
@section('content')
    <div class="goods-cont">
        <div class="good-info">
            <div class="good-img"><img src="{{ $goods_data['image'] or ''}}" style="width: 50px;"></div>
            <div class="good-spec">
                <div class="spec-info">{{ $goods_data['name']  or ''}}</div>
                <div class="spec-check">

                    <span>{{ $goods_data['spec'] or '' }}<em class="red-color">￥{{ $goods_data['price'] or ''}}</em></span>

                </div>
            </div>
        </div>
        <div class="good-info-spec">
            <div class="spec-nav">
                <span><em class="red-color">*</em>平台</span>
                <span class="platcategory">规格</span>
                <span class="Specifications">价格（元）</span>
                <span class="price">库存</span>
            </div>
            <div id="nopublish-list">
            </div>
            <div id="publish-list">
            </div>
            <div class="btn-color btn btn-word-color confirm" id="confirm">确定</div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/libs/iCheck/icheck.js"></script>
    <script>

        var spec_id = '{!! $spec_id or '' !!}';
        E.ajax({
            type :'get',
            url : '/admin/mallgoods/search_online',
            data : { spec_id : spec_id },
            dataType : 'json',
            success : function ( obj){
                if( obj.code == 200 ){
                    if( obj.data.online != ''){

                        var html = '';
                            html += '<div class="notice">';
                            html += '<div class="notice-left">已发布</div>';
                            html += '</div>';

                        $.each( obj.data.online ,function ( k ,v ){

                            html += '<form id="nopublish_form_'+ v.app_id+'" data-wm="'+ v.app_id+'">';
                            html += '<div class="spec-cont">';
                            html += '<div class="platform" style="width: 25%;">';
                            html += '<div class="table-cell">';
                            html += '<div class="good-logo">';
                            html += '<input type="checkbox" class="square-radio publish-wm" style="display: inline-block" checked disabled data-wm="'+ v.app_id +'">';
                            html += '&nbsp;&nbsp;<img src="'+ v.app_logo +'">';
                            html += '&nbsp;&nbsp;<span>'+ v.app_name +'</span>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="platform" style="width: 25%;">';
                            html += '<div class="table-cell">';
                            html += '<span>------</span>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="platform Specifications" style="width:25%">';
                            html += '<div class="table-cell">';
                            html += '<input type="text" disabled name="price"  class="form-control form-inline tab-input" value="'+ v.price +'">';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="platform price">';
                            html += '<div class="table-cell">';
                            html += '<div class="input-box">';
                            html += '<input type="text" disabled name="enable_number"  class="form-control form-inline tab-input" value="'+ v.enable_number +'">';
                            html += '<a href="javescript:void(0)" class="reset">    沽清 | </a>';
                            html += '<a href="javescript:void(0)" class="infinite">无限</a>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '<input type="hidden" name="spec_id" value="'+ spec_id+'">';
                            html += '</form>';
                        });

                        $('#publish-list').html(html);
                        addIcheck();
                    }

                    if( obj.code.no_online != ''){

                        var html = '';
                            html += '<div class="notice">';
                            html += '<div class="notice-left">未发布</div>';
                            html += '</div>';

                        $.each( obj.data.no_online ,function ( k ,v ){

                            html += '<form id="nopublish_form_'+ v.app_id+'" class="form-list" data-wm="'+ v.app_id+'">';
                            html += '<div class="spec-cont">';
                            html += '<div class="platform" style="width: 25%;">';
                            html += '<div class="table-cell">';
                            html += '<div class="good-logo">';
                            html += '<input type="checkbox" class="square-radio nopublish-wm" style="display: inline-block" data-wm="'+ v.app_id +'">';
                            html += '&nbsp;&nbsp;<img src="'+ v.app_logo +'">';
                            html += '&nbsp;&nbsp;<span>'+ v.app_name +'</span>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="platform" style="width: 25%;">';
                            html += '<div class="table-cell">';
                            html += '<span>------</span>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="platform Specifications" style="width:25%">';
                            html += '<div class="table-cell">';
                            html += '<input type="text" name="price" class="form-control form-inline tab-input" value="'+ v.price +'">';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="platform price">';
                            html += '<div class="table-cell">';
                            html += '<div class="input-box">';
                            html += '<input type="text" name="enable_number"  class="form-control form-inline tab-input" value="'+ v.enable_number +'">';
                            html += '<a href="javescript:void(0)" class="reset">    沽清 | </a>';
                            html += '<a href="javescript:void(0)" class="infinite">无限</a>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '<input type="hidden" name="spec_id" value="'+ spec_id+'">';
                            html += '</form>';

                        });

                        $('#nopublish-list').html(html);
                        addIcheck();

                        $('input[name="enable_number"]').on('blur',function(){

                            var val = $(this).val();
                            $('input[name="enable_number"]').val(val);
                        });

                        $('input[name="price"]').on('blur',function(){

                            var val = $(this).val();
                            $('input[name="price"]').val(val);
                        });

                        $(document).on('click','.reset',function(){

                            $('input[name="enable_number"]').val(0);
                        }).on('click','.infinite',function(){

                            $('input[name="enable_number"]').val(9999);
                        })

                    }
                }
            }
        });

        $(document).on('click','#confirm',function(){

            var dt = {
                form_value : [],
                app_ids :[]
            };

            $('.nopublish-wm').each(function (){
                if( $(this).is(':checked')){
                    dt.app_ids.push($(this).attr('data-wm'));
                }
            });

            var error = '';

            $('.form-list').each(function (){
                var app_id = $(this).attr('data-wm');

                if($.inArray(app_id ,dt.app_ids) != -1){
                    var data = E.getFormValues('nopublish_form_'+app_id);

                    if(!E.isMoney(data.price)){
                        error = '您输入的商品价格有误';
                    }

                    if(!E.isInt(data.enable_number)){
                        error = '您输入的库存数量有误';
                    }

                    dt.form_value.push(data);
                }
            });

            if(error != ''){
                layer.msg( error,{icon :2 ,time :2000});
                return false;
            }

            E.ajax({
                type :'get',
                url : '/admin/mallgoods/online',
                dataType : 'json',
                data : dt,
                success : function (obj){
                    if( obj.code == 200){
                        layer.msg( obj.message,{icon : 1 ,time:1500,offset : '120px'},function(){
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            parent.bootstrap_table_init();
                        });
                    }else{
                        layer.msg( obj.message,{icon :2 ,time :2000,offset : '120px'});
                    }
                }
            });
        });

        function addIcheck(){   //icheck 初始化
            $('.square-radio').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        }


    </script>
@endsection