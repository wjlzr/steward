@extends('admin.layoutEdit')

@section('css')
    <link href="/css/admin/goods/list.css?v=20180205000" rel="stylesheet">
    <style>
        .app-title{
            height: 0;;
        }
        #pop label {
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
    <div class="row" id="search-box">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-3">
                    <a class="btn btn-blue goods-add " data-type="1" href="#" data-id="0" >创建商品</a>
                    <a class="btn btn-blue goods-upload" href="#" >批量导入</a>
                    <a class="btn btn-border-blue goods-export" href="#">导出</a>
                </div>
                <div class="col-md-9 col-extend-css">
                    <form class="form-inline" id="search-form" onsubmit="return false;">
                        {{csrf_field()}}
                        <div class="form-group">
                            <span>商品名称：</span>
                            <input type="text" placeholder="请输入商品名称" class="form-control w130" name="name">&nbsp;&nbsp;
                        </div>
                        <div class="form-group">
                            <span>商家编码：</span>
                            <input type="text" placeholder="请输入商家编码" class="form-control w130" name="sku">&nbsp;&nbsp;
                        </div>
                        <div class="form-group">
                            <span>商品条码：</span>
                            <input type="text" placeholder="请输入商品条码" class="form-control w130" name="upc">
                        </div>
                        <button class="btn btn-blue" id="search" type="button">查询</button>
                        <button class="btn btn-default" id="re-set" type="button">重置</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div></div>
        <div class="col-lg-12">
            <table id=""></table>
        </div>
    </div>

    <div class="list-content">
        <div class="list-aside" >
            <div class="aside-tit">
                <div class="sorted-tit">分类列表</div>
                <div class=" active-border category"><a class="btn btn-border-blue" href="/admin/category">管理分类</a></div>
            </div>
            <ul class="sort-list" id="accordion"></ul>
        </div>
        <div class="list-body-right">
            <div class="body-box">
                <div class="nav-list">
                    <span class="nav-item on" data-type="0">全部</span>
                    <span class="nav-item" data-type="1">售卖中</span>
                    <span class="nav-item" data-type="2">已下架</span>
                    <div style="display: inline-block;width:330px;"></div>
                    <input type="checkbox" class="square-radio no-image" style="display: inline-block;">&nbsp;&nbsp;无图片
                    <input type="checkbox" class="square-radio no-weight" style="display: inline-block;">&nbsp;&nbsp;重量等于0
                </div>
                <div class="list-edit-body">
                    <div class="checked-box">
                        <div class="editBatch">
                            <button class=" btn btn-border-blue forsale all btn-batch" disabled="disabled" data-type="2">批量上架</button>&nbsp;&nbsp;&nbsp;
                            <button class=" btn btn-border-blue forsale all btn-batch" disabled="disabled" data-type="1">批量下架</button>
                        </div>
                    </div>
                    <div class="tab-border">
                        <table id="table" lay-filter="table-filter"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="htmlPrice" ></div>
@endsection

@section('js')

    <script src="/libs/layui-v2.2.5/layui.js"></script>
    <script language="JavaScript" src="/js/charts/export.js" type="text/javascript"></script>
    <script>

        $('.square-radio').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        function icheck_checked(){

            if( $('.no-image').is(':checked')){

                $('#search-form').append('<input type="hidden" name="image" value="1" >');
            }else{
                $('input[name="image"]').remove();
            }

            if( $('.no-weight').is(':checked')){

                $('#search-form').append('<input type="hidden" name="weight" value="1" >');
            }else{
                $('input[name="weight"]').remove();
            }
        }


        var table;

        $(document).on('click', '#search', function () {
            layui_table_reload();
        }).on('click', '#re-set', function () {
            $('#search-form')[0].reset();
            layui_table_reload();
        }).on('click', '.layer-go-back', function () {
            E.layerClose();
        });

        function layui_table(params) {

            layui.use('table', function () {

                table = layui.table;
                var top_height = 48 + 25 + $('#search-box').height() + 40;
                var dt = E.getFormValues('search-form');
                var render = {
                    elem: '#table',
                    id:'layui-table',
                    height: 'full-'+ top_height,
                    limit: 10,
                    url: layui_table_ajax_url,
                    page: true,
                    initSort:{
                        field:params.sort_name,
                        type:params.sort_order
                    },
                    where:dt,
                    cols: params.cols,
                    done: function() {
                        layui_checkbox_refresh();
                    }
                };
                for(var key in params) {
                    if (key != 'initSort') {
                        render[key] = params[key];
                    }
                }

                table.render(render);
                table.on('checkbox(table-filter)', function(obj) {
                    layui_checkbox_refresh();
                });

            });

        }

        function layui_table_reload() {
            var dt = E.getFormValues('search-form');
            table.reload('layui-table', {
                where:dt,
                page:{
                    curr:1
                }
            });
        }

        function layui_checkbox_refresh() {
            var check_status = table.checkStatus('layui-table');
            if (check_status.data.length <= 0) {
                $('.btn-batch').attr('disabled', true);
            } else {
                $('.btn-batch').attr('disabled', false);
            }
        }

        var layui_table_ajax_url = '/admin/goods/search';

        layui_table({
            sort_name: 'sku_upc', //排序字段
            sort_order: 'desc',//排序方式
            cols: [ [//字段
                { type: 'checkbox',event :'checkbox'},
                { title: '操作', field: 'operation', align: 'center',width : 100 },
                { title: '商品信息', field: 'info', align: 'left',width: 200 },
                { title: '商家编码/条码',  field: 'sku_upc', align: 'center',width : 120 },
                { title: '售价', field: 'price', align: 'left',width : 120 },
                { title: '所属分类', field: 'category', align: 'left',width : 140 },
                { title: '单位', field: 'unit', align: 'center',width : 80},
                { title: '重量(g)', field: 'weight', align: 'center' ,width : 80},
                { title: '售卖状态', field: 'status', align: 'center' ,width : 100}
            ]]
        });

        //分类列表
        E.ajax({
            type : 'get',
            url : '/admin/category/search',
            dataType : 'json' ,
            data : {} ,
            success : function (obj){

                if( obj.code == 200 ){

                    var html_category = '';
                    html_category += '<li class="link category_search all">';
                    html_category += '<div class="list-cont current">';
                    html_category += '<i class="icon-toggle"></i>';
                    html_category += '<div class="icon-text">全部商品</div>';
                    html_category += '<div style="float: right;margin-right: 20px;">'+obj.total+'</div>';
                    html_category += '</div>';
                    html_category +=  '<ul class="submenu">';
                    html_category +=  '</ul>';
                    html_category += '</li>';

                    $.each( obj.data ,function( k ,v ){

                        html_category +=  '<li class="link category_search" data-id="'+ v.id +'">';
                        html_category +=  '<div class="list-cont">';

                        if(v.children == ''){
                            html_category +=  '<i class="icon-toggle "></i>';
                        }else{
                            html_category +=  '<i class="icon-toggle iconDown"></i>';
                        }

                        html_category +=  '<div class="icon-text">'+ v.name +'</div>';
                        html_category += '<div style="float: right;margin-right: 20px;">'+ v.goods_num+'</div>';
                        html_category +=  '</div>';
                        html_category +=  '<ul class="submenu">';

                        if(v.children != ''){
                            $.each(v.children ,function ( km ,vm ){
                                html_category +=  '<li class="on category_search" data-id="'+ vm.id +'">';
                                html_category +=  '<div class="publicSubmit">';

                                if(vm.children == ''){
                                    html_category +=  '<i class="icon-toggle "></i>';
                                }else{
                                    html_category +=  '<i class="icon-toggle iconDown"></i>';
                                }

                                html_category +=  '<div class="second-text">'+ vm.name+'</div>';
                                html_category += '<div style="float: right;margin-right: 20px;">'+ vm.goods_num+'</div>';
                                html_category +=  '</div>';
                                html_category +=  '<ul class="third_menu">';
                                if( vm.children != '' ){
                                    $.each(vm.children ,function( ks , vs ){

                                        html_category +=  '<li class="category_search" data-id="'+ vs.id +'">';
                                        html_category +=  '<div class="third_cont">';
                                        html_category +=  '<div class="icon-text">'+ vs.name+'</div>';
                                        html_category += '<div style="float: right;margin-right: 20px;">'+ vs.goods_num+'</div>';
                                        html_category +=  '</div>';
                                    })
                                }
                                html_category +=  '</ul>';
                                html_category +=  '</li>';
                            })
                        }
                        html_category +=  '</ul>';
                        html_category +=  '</li>';
                    });

                    $('#accordion').append(html_category);
                }
            }
        });


        $(function() {

            $('.no-image,.no-weight').on('ifChanged',function(){

                icheck_checked();
                var dt = E.getFormValues('search-form');

                if( $('.no-image').is(':checked')){
                    dt.image = 1;
                }else{
                    dt.image = '';
                }

                if( $('.no-weight').is(':checked')){

                    dt.weight = 1;
                }else{
                    dt.weight = '';
                }

                table.reload('layui-table', {
                    where:dt,
                    page:{
                        curr:1
                    }
                });
            });

            // 点击一级菜单
            $(document).on("click",".sort-list li", function(){
                $(this).find('.submenu').slideToggle(200).parent().siblings().find('.submenu').slideUp(200);
                $(this).find('.third_menu').slideUp(200);
                $(this).find('.list-cont').addClass('current').parent('.link').siblings().find('.list-cont').removeClass('current').find('.iconUp').addClass('iconDown').removeClass('iconUp');
                $(this).find('.publicSubmit').find('.iconUp').addClass('iconDown').removeClass('iconUp')
                if($(this).find('.submenu > li').length > 0) {
                    if ($(this).find('.list-cont .icon-toggle').hasClass('iconDown')) {
                        $(this).find('.list-cont .icon-toggle').addClass('iconUp').removeClass('iconDown');
                    } else {
                        $(this).find('.list-cont .icon-toggle').addClass('iconDown').removeClass('iconUp');
                    }
                }
                $(this).find('.third_cont').removeClass('current');
                $(this).find('.publicSubmit').removeClass('current')

            });

            $(document).on('click', '.third_menu > li', function(e) {
                e.stopPropagation();
                $(this).find('.third_cont').addClass('current').parents('.on').find('.publicSubmit').removeClass('current');
            });


            $(document).on("click",".submenu li", function(e) {
                e.stopPropagation();
                $(this).find('.third_menu').slideToggle(200).parent().siblings().find('.third_menu').slideUp(200);
                $(this).find('.publicSubmit').addClass('current').parents('.link').find('.list-cont').removeClass('current');
                $(this).siblings().find('.publicSubmit').removeClass('current').find('.iconUp').addClass('iconDown');
                if($(this).find('.third_menu > li').length > 0) {
                    if ($(this).find('.publicSubmit .icon-toggle').hasClass('iconDown')) {
                        $(this).find('.publicSubmit .icon-toggle').addClass('iconUp').removeClass('iconDown');
                    } else {
                        $(this).find('.publicSubmit .icon-toggle').addClass('iconDown').removeClass('iconUp');
                    }
                }

            }).on('click', '.nav-list span',function(){

                icheck_checked();

                $(this).addClass('on').siblings().removeClass('on');

                var type = $(this).attr('data-type');
                $('input[name="type"]').remove();
                $('#search-form').append('<input type="hidden" name="type" value="'+type+'" >');
                var dt = E.getFormValues('search-form');

                table.reload('layui-table', {
                    where: dt
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                });

            }).on('click','.forsale',function(){       //商品上下架操作

                var type = $(this).attr('data-type');           //操作方式
                var err_msg = '';                               //错误信息
                var title = '';                                 //提示
                var id_arr = [];                               //操作对象

                if(type == 1){
                    title = '下架';
                }else{
                    title = '上架';
                }

                if( $(this).hasClass('all') ){

                    //批量操作
                    var num_selected = 0;

                    $('.layui-table-body').find('.layui-form-checked').each(function(){

                        num_selected = 1;
                        var id = $(this).parents('tr').find('.forsale').attr('data-id');
                        id_arr.push(id);
                    });

                    if(!num_selected){
                        layer.msg('请选择要操作的商品',{icon:2,shade: [0.15, 'black'],offset:'120px',time:2000});
                        return false;
                    }
                    title = '您确定将所选商品'+ title + '吗？';
                }else{

                    //单独操作
                    var id = $(this).attr('data-id');

                    title = '您确定将该商品'+title+'吗？';
                    id_arr.push(id);
                }

                if (err_msg) {
                    layer.msg( err_msg , {icon: 2,shade: [0.15, 'black'],offset:'120px',time:1000});
                    return false;
                }

                layer.confirm(title,{icon:3,offset:'50px'},function( index ){

                    layer.close(index);

                    E.ajax({
                        type:'get',
                        url:'/admin/goods/shelf',
                        data: {
                            type : type,
                            id_arr :id_arr
                        },
                        success: function (o) {
                            if(o.code == 200){
                                layer.msg(o.message,{icon:1,time:1000});
                                layui_table_reload();
                            }else{
                                layer.msg(o.message,{icon:2,time:1000});
                            }
                        }
                    });
                })
            }).on('click','.price',function(){   //修改价格

                var id = $(this).attr('data-id');

                E.ajax({
                    type : 'get',
                    url : '/admin/goods/search_price/'+ id ,
                    dataType : 'json',
                    data : {},
                    success : function(obj){

                        if(obj.code == 200 ){

                            var htmlPrice = '';

                            if( obj.data.total == 1){  //单规格

                                htmlPrice += '<div id="pop" style="margin-top:10px;" style="width: 100%">';
                                htmlPrice += '<div style="background: #ffffff">';
                                htmlPrice += '<form id="pop_form" onsubmit="return false;" class="form-horizontal" role="form">';
                                htmlPrice += '<input type="hidden" value="'+ id +'" name="goods_id">';
                                htmlPrice += '<input type="hidden" value="'+ obj.data['goods'][0].spec_id +'" name="spec_id[]">';
                                htmlPrice += '<div class="form-group" style="margin-right: 1px; margin-left: 28px;text-align: left">';
                                htmlPrice += '<label class="col-sm-12 " for="price_edit" >';
                                htmlPrice += '<span>'+ obj.data['goods'][0].goodsName +'</span></label>';
                                htmlPrice += '</div>';
                                htmlPrice += '<div class="form-group" style="margin-right: 1px; margin-left: 1px;">';
                                htmlPrice += '<label class="col-sm-3 control-label" for="price_edit">';
                                htmlPrice += '价格：</label>';
                                htmlPrice += '<div class="col-sm-8">';
                                htmlPrice += '<input class="form-control"  style="width: 150px;" type="text" id="price_edit" name="price[]" maxlength="100" value="'+obj.data['goods'][0].salePrice+'" />';
                                htmlPrice += '</div></div>';
                                htmlPrice += '</form>';
                                htmlPrice += '</div>';
                                htmlPrice += '</div>';

                            }else{              //多规格

                                htmlPrice += '<div id="pop" style="margin-top:10px;" style="width: 100%">';
                                htmlPrice += '<div style="background: #ffffff">';
                                htmlPrice += '<form id="pop_form" onsubmit="return false;" class="form-horizontal" role="form">';
                                htmlPrice += '<div class="form-group" style="margin-right: 1px; margin-left: 28px;text-align: left">';
                                htmlPrice += '<label class="col-sm-12 " for="price_edit" >';
                                htmlPrice += '<span>'+ obj.data['goods'][0].goodsName +'</span></label>';
                                htmlPrice += '</div>';

                                $.each(obj.data.goods,function( k , v ){

                                    htmlPrice += '<div class="form-group" style="margin-right: 1px; margin-left: 1px;">';
                                    htmlPrice += '<input type="hidden" value="'+ id +'" name="goods_id">';
                                    htmlPrice += '<input type="hidden" value="'+ v.spec_id +'" name="spec_id[]">';
                                    htmlPrice += '<label class="col-sm-3 control-label" for="price_edit" >'+ v.spec+'</label>';
                                    htmlPrice += '<label class="col-sm-3 control-label" for="price_edit" >销售价：</label>';
                                    htmlPrice += '<div class="col-sm-6">';
                                    htmlPrice += '<input class="form-control"  style="width: 120px;" type="text" id="price_edit" name="price[]" maxlength="100" value="'+v.salePrice+'" />';
                                    htmlPrice += '</div></div>';
                                });

                                htmlPrice += '</form>';
                                htmlPrice += '</div>';
                                htmlPrice += '</div>';

                            }

                            layer.open({
                                title:'修改价格',
                                type : 1,
                                area : ['40%','auto'],
                                move:false,
                                btnAlign:'c',
                                content : htmlPrice,
                                btn : ['确认' ,'取消'],
                                yes : function(index){

                                    var dt = E.getFormValues('pop_form');
                                    var error = '' ;
                                    $.each(dt.price ,function ( k ,v ){

                                        if( v == ''){
                                            error = '请输入商品价格' ;
                                        }

                                        if(!E.isMoney(v)){
                                            error = '请设置正确的商品价格' ;
                                        }
                                    });
                                    if( error != '' ){
                                        layer.msg( error ,{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                                        return false;
                                    }

                                    layer.close(index);

                                    E.ajax({
                                        type : 'get',
                                        url : '/admin/goods/edit_price/',
                                        dataType : 'json',
                                        data : dt ,
                                        success : function(obj){

                                            if( obj.code == 200 ){
                                                layer.msg('操作成功',{ icon : 1 ,shade: [0.15, 'black'], time : 2000 });
                                                layui_table_reload();
                                            }else{
                                                layer.msg('操作失败',{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                                            }
                                        }
                                    })
                                }
                            });
                        }else{
                            layer.msg( obj.message ,{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                        }
                    }
                });
            }).on('click','.category_search',function(){

                icheck_checked();

                if( $(this).hasClass('all')){

                    $('input[name="category_id"]').remove();
                    var dt = E.getFormValues('search-form');
                    dt.category_id = '';

                    table.reload('layui-table', {
                        where: dt
                        ,page: {
                            curr: 1 //重新从第 1 页开始
                        }
                    });

                }else {

                    var category_id = $(this).attr('data-id');

                    $('input[name="category_id"]').remove();
                    $('#search-form').append('<input type="hidden" name="category_id" value="'+ category_id +'">');
                    layui_table_reload();
                }
            }).on('click','.goods-add',function(){

                var id = $(this).attr('data-id');

                layer.open({
                    title:false,
                    type : 2,
                    closeBtn : 0 ,
                    area : ['100%','100%'],
                    content : '/admin/goods/edit/'+ id
                });

            }).on('click','.goods-upload',function(){

                var html = '';
                html += '<div style="height:35px;">';
                html += '</div>';
                html += '<div class="form-group">';
                html += '<div class="col-sm-6 download">';
                html += '<input type="text" id="file" class="form-control" style="width: 140px;display: inline-block;">';
                html += '<div class="btn btn-info" id="file-view" style="margin-left:-3px;margin-top: -3px;border-radius: 0px 4px 4px 0px;">浏览</div>';
                html += '</div>';
                html += '<div class="col-sm-6">';
                html += '<button class="btn btn-info" id="re-sale" type="button">导入商品</button>&nbsp;&nbsp;';
                html += '<button class="btn btn-info" id="re-export" type="button" onclick="dw()">模板下载</button>';
                html += '</div>';
                html += '<div style="height:35px;">';
                html += '</div>';
                html += '</div>';




                layer.open({
                    title: '导入商品' ,
                    type: 1 ,
                    closeBtn : 0 ,
                    move:false,
                    area: '600px',
                    content:  html ,
                    btn: ['关闭']
                });

                var csrf = '{{ csrf_token() }}';

                layui.use('upload', function () {
                    var $ = layui.jquery,
                            upload = layui.upload;

                    var uploadInst = upload.render({
                        elem: '#file-view' //绑定元素
                        ,url: '/admin/goods/batch_upload' //上传接口
                        ,auto: false
                        ,bindAction: '#re-sale'
                        ,accept: 'file'
                        ,data : {_token : csrf }
                        ,choose : function(obj){
                            obj.preview(function(index, file, result){
                                $('#file').val(file.name);
                            });
                        }
                        ,done: function (o) {

                            if (o.code == 200) {
                                layer.msg(o.message, {icon: 1, time: 1000},function(){
                                    layer.closeAll();
                                    layui_table_reload();
                                });
                            } else {
                                layer.msg('上传失败', {icon: 2, time: 1000});
                            }
                        }
                    });
                });
            }).on('click','.goods-export',function(){

//                window.location.href = '/admin/goods/export';

                var exportIndex = 1001;

                layer.confirm( '您确定导出商品信息吗?' ,{icon:3,offset:'50px'}, function( index ) {
                    layer.close(index);
                    com_export.download('search-form', '/admin/goods/export',exportIndex );
                });
            });

        });

        //下载模板
        function dw () {
            window.location.href = '/admin/goods/download';
        }

    </script>
@endsection