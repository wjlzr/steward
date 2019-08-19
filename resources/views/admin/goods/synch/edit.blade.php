@extends('admin.layoutEdit')

@section('css')
    <link href="/libs/iCheck/skins/square/blue.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap-table/1.11.1/bootstrap-table.min.css">
    <link href="/libs/layui/css/layui.css" rel="stylesheet">
    <style>
        .select-btn{
            width:244px;
            margin-left: 12px;
            margin-bottom: 30px;
        }
        .fixed-table-container{
            width:900px;
            margin-top: 10px;
            border: 0;
        }
        .statistics{
            margin:10px;
            width:1055px;
            height:56px;
            background-color: rgba(255, 255, 204, 1);
            line-height:56px;
            padding-left: 25px;
        }
        .table-a{
            border:1px solid rgb(220, 221, 222);
        }
        .red{
            color: red;
        }
        .items{
            border-bottom:1px solid #e2e2e2;
        }
        .btn-tab{
            border: none;
            border-radius: 0;
            background-color: #fff;
            color: #00a0e9;
            border-bottom: 2px solid #00a0e9;
        }
        .tab_btn{
            background-color: #fff;
            margin-bottom:15px;
            outline: none !important;
        }
        .table_list tbody tr{
            border: 0;
        }
        .table-list tbody tr td{
            border-left:0;
            border-right: 0;
        }
        .table-list thead tr th{
            border-left:0;
            border-right: 0;
        }
        .tab_danger{
            background-color: #fff;
            border:1px solid #d9534f;
            color: #d9534f;
        }
        .btn-blue:hover{
            color: #fff !important;
        }
    </style>
@endsection

@section('title')
    <li>
        <span>@if($type == 1)同步商品至分店@else 下架线上平台商品 @endif</span>
    </li>
@endsection

@section('go-back-btn')
    <a id="go-back" style="display:inline-block;padding-top:9px;">&lt;&lt;返回同步商品</a>
@endsection

@section('content')
    <div class="items">
        <button type="button" class="select-btn btn btn-tab b-1 tab_btn">@if($type == 1)STEP1.选择商品@else STEP1.选择下架商品@endif</button>
        <button type="button" class="btn select-btn b-2 tab_btn">@if($type == 1)STEP2.选择门店@else STEP1.选择下架门店@endif</button>
        <button type="button" class="btn select-btn b-3 tab_btn">@if($type == 1)STEP3.选择线上平台@else STEP1.选择下架平台@endif</button>
        <button type="button" class="btn select-btn b-4 tab_btn">@if($type == 1)STEP4.发布商品@else STEP1.下架商品@endif</button>
    </div>
    <!--第一步选择商品-->
    <div id="select_goods">
        <div class="panel panel-default" style="margin: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">商品列表</h3>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <div class="col-sm-8 radio-box">
                        <input type="radio" class="square-radio selector_goods_type" name="selector_goods_type" value="1" checked>
                        <label class="ml5">全部商品</label>&nbsp;
                        <input type="radio" class="square-radio selector_goods_type" name="selector_goods_type" value="2">
                        <label class="ml5">部分商品</label>
                    </div>
                </div>

                <div class="form-group select-goods-div" style="display: none;" >
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-8" style="margin-top: 20px;">
                    <button type="button" class="btn btn-blue mb10" onclick="plugin.search_goods()" >添加商品</button>
                    <button type="button" class="btn btn-blue mb10" onclick="synch.import_goods(1)" >导入商品</button>
                    <button type="button" class="btn btn-danger mb10 tab_danger"  onclick="plugin.del_goods();">移除</button>
                    <table id="goods_list" class="table table-hover table-list" data-toggle="table" data-pagination="true">
                        <thead>
                        <tr>
                            <th data-field="id" class="col-md-2" data-align="center" data-visible="false"></th>
                            <th data-field="goodsName" class="col-md-3" data-align="center">商品名称</th>
                            <th data-field="price" class="col-md-2" data-align="center">商品价格</th>
                            <th data-field="big_category" class="col-md-2" data-align="center">大分类</th>
                            <th data-field="mid_category" class="col-md-2" data-align="center">中分类</th>
                            <th data-field="small_category" class="col-md-2" data-align="center">小分类</th>
                            <th data-field="operation" class="col-md-1" data-align="center" data-checkbox="true"></th>
                        </tr>
                        </thead>
                    </table>
                </div>
                </div>
            </div>
        </div>

        <div class="bottom_fixbox">
            <div class="rectbox tac" style="text-align: center;margin-top: 20px;">
                <input type="hidden" id ="dataCache" name="dataCache" value="">
                <input type="button" id="select-goods-1" class="btn btn-blue mb10" style="margin-right: 20px" value="下一步：选择门店" />
                <input type="button" id="go-back" class="btn btn-default" value="取消">
            </div>
        </div>
    </div>
    <!--第二步选择门店-->
    <div id="select_mall" style="display: none;">
        <div class="statistics goods_num">已选择：0个商品SKU</div>
        <div class="panel panel-default" style="margin: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">门店列表</h3>
            </div>
            <div class="panel-body">

                <div class="form-group">
                    <div class="col-sm-8 radio-box">
                        <input type="radio" class="square-radio" name="selector_mall_type" value="1"  checked>
                        <label class="ml5">全部门店</label>&nbsp;
                        <input type="radio" class="square-radio" name="selector_mall_type" value="2">
                        <label class="ml5">部分门店</label>
                    </div>
                </div>

                <div class="form-group select-mall-div" style="display: none;" >
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-8" style="margin-top: 20px;">
                        <button type="button" class="btn btn-blue mb10" onclick="plugin.search_mall()" >添加门店</button>
                        <button type="button" class="btn btn-blue mb10" onclick="synch.import_goods(2)" >导入门店</button>
                        <button type="button" class="btn btn-danger mb10 tab_danger"  onclick="plugin.del_mall();">移除</button>
                        <table id="mall_list" class="table table-hover table-list" data-toggle="table" data-pagination="true">
                            <thead>
                            <tr>
                                <th data-field="id" class="col-md-2" data-align="center" data-visible="false"></th>
                                <th data-field="mall_code" class="col-md-2" data-align="center">门店编号</th>
                                <th data-field="mall_name" class="col-md-4" data-align="center">门店名称</th>
                                <th data-field="city" class="col-md-2" data-align="center">城市</th>
                                <th data-field="business_time" class="col-md-3" data-align="center">营业时间</th>
                                <th data-field="operation" class="col-md-1" data-align="center" data-checkbox="true"></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom_fixbox">
            <div class="rectbox tac" style="text-align: center;margin-top: 20px;">
                <input type="hidden" id ="dataCache" name="dataCache" value="">
                <input type="button" id="select-goods-2" class="btn btn-default" style="margin-right: 20px" value="@if($type == 1)上一步：选择商品@else 上一步：选择下架商品@endif" />
                <input type="button" id="select-goods-3" class="btn btn-blue mb10" value="@if($type == 1)下一步：选择线上平台@else 下一步：选择下架平台@endif">
            </div>
        </div>
    </div>

    <!--第三步选择线上平台-->
    <div id="select_platform" style="display: none;">
        <div class="statistics mall_num">已选择：0个商品SKU、0家门店</div>
        <div class="panel panel-default" style="margin: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">线上平台列表</h3>
            </div>
            <div class="panel-body">
                <table class="table-a" border= "1" width= "100%" style="border-collapse:collapse" >

                </table>
            </div>
        </div>

        <div class="bottom_fixbox">
            <div class="rectbox tac" style="text-align: center;margin-top: 20px;">
                <input type="hidden" id ="dataCache" name="dataCache" value="">
                <input type="button" id="select-goods-4" class="btn btn-default" style="margin-right: 20px" value="@if($type == 1)上一步：选择门店@else 上一步：选择下架门店@endif"/>
                <input type="button" id="select-goods-5" class="btn btn-blue mb10" value="@if($type == 1)下一步：发布商品@else 下一步：下架商品@endif">
            </div>
        </div>
    </div>

    <!--第四步发布商品-->
    <div id="release-goods" style="display: none;">
        <div class="statistics app_num">已选择：0个商品SKU、0家门店、0个平台</div>
        <div class="panel panel-default" style="margin: 10px;">
            <div class="panel-heading">
                <h3 class="panel-title">经营渠道发布日志</h3>
            </div>
            <div class="panel-body">

            </div>
        </div>

        <div class="bottom_fixbox">
            <div class="rectbox tac" style="text-align: center;margin-top: 20px;">
                <input type="hidden" id ="dataCache" name="dataCache" value="">
                <input type="button" id="select-goods-6" class="btn btn-blue mb10" style="width:124px;" value="完成" />
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/libs/iCheck/icheck.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap-table/1.9.1/bootstrap-table.min.js"></script>
    <script src="/libs/bootstrap-table-master/dist/locale/bootstrap-table-zh-CN.js"></script>
    <script src="/libs/layui-v2.1.7/layui.js"></script>
    <script>

        $(function () {

            $('.square-radio').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });

        var type = {!! $type !!};

        $(document).on('click','#go-back',function(){
            synch.close();
        });

        //选择商品
        $(document).on('ifChecked', 'input[name="selector_goods_type"]', function () {
            if ($(this).val() == 1) {
                $('.select-goods-div').hide();
            } else {
                $('.select-goods-div').show();
            }
        }).on('ifChecked', 'input[name="selector_mall_type"]', function () {
            if ($(this).val() == 1) {
                $('.select-mall-div').hide();
            } else {
                $('.select-mall-div').show();
            }
        });

        $(document).on('click','#select-goods-1',function(){
            if ($('input[name="selector_goods_type"]:checked').val() == 2) {
                if (plugin.goods_num == 0) {
                    layer.alert('请选择商品',{icon:2,time:1500});
                    return false;
                }
            }
            $('#select_mall').show();
            $('#select_goods').hide();
            $('.b-1').removeClass('btn-tab');
            $('.b-2').addClass('btn-tab');
            var goods_type = $('input[name="selector_goods_type"]:checked').val();
            if (goods_type == 1) {
                $('.goods_num').html('已选择：全部商品');
            }else{
                $('.goods_num').html('已选择：'+plugin.goods_num+'个商品SKU');
            }
        }).on('click','#select-goods-2',function(){
            $('#select_mall').hide();
            $('#select_goods').show();
            $('.b-2').removeClass('btn-tab');
            $('.b-1').addClass('btn-tab');
        }).on('click','#select-goods-3',function(){
            if ($('input[name="selector_mall_type"]:checked').val() == 2) {
                if (plugin.mall_num == 0) {
                    layer.alert('请选择门店',{icon:2,time:1500});
                    return false;
                }
            }
            $('#select_mall').hide();
            $('#select_platform').show();
            $('.b-2').removeClass('btn-tab');
            $('.b-3').addClass('btn-tab');
            var goods_type = $('input[name="selector_goods_type"]:checked').val();
            var mall_type = $('input[name="selector_mall_type"]:checked').val();
            if (goods_type == 1) {
                if (mall_type == 1) {
                    $('.mall_num').html('已选择：全部商品、全部门店');
                }else{
                    $('.mall_num').html('已选择：全部商品、'+plugin.mall_num+'家门店');
                }
            }else{
                if (mall_type == 1) {
                    $('.mall_num').html('已选择：'+plugin.goods_num+'个商品SKU、全部门店');
                }else{
                    $('.mall_num').html('已选择：'+plugin.goods_num+'个商品SKU、'+plugin.mall_num+'家门店');
                }
            }
            synch.app();
        }).on('click','#select-goods-4',function(){
            $('#select_platform').hide();
            $('#select_mall').show();
            $('.b-3').removeClass('btn-tab');
            $('.b-2').addClass('btn-tab');
        }).on('click','#select-goods-5',function(){
            if (type == 1) {
                if ($.isEmptyObject(synch.app_data)) {
                    layer.alert('请选择线上平台',{icon:2,time:1500});
                    return false;
                }
            }else{
                if ($.isEmptyObject(synch.app_data)) {
                    layer.alert('请选择下架平台',{icon:2,time:1500});
                    return false;
                }
            }

            $('#select_platform').hide();
            $('#release-goods').show();
            $('.b-3').removeClass('btn-tab');
            $('.b-4').addClass('btn-tab');
            if (!$.isEmptyObject(synch.app_data)) {
                var html = '';
                $.each(synch.app_data,function(k,v){
                    html += '平台-'+v+'、';
                });
                html=html.substring(0,html.length-1);
                $('.app_num').text('已选择：'+plugin.goods_num+'个商品SKU、'+plugin.mall_num+'家门店、'+html);
            }
            synch.submit();
        }).on('click','#select-goods-6',function(){
            synch.close();
        }).on('ifChecked','input[name="app-info"]',function(){
            var app_id = $(this).attr('data-id');
            var app_name = $(this).val();
            synch.app_data[app_id] = app_name;
        }).on('ifUnchecked', 'input[name="app-info"]', function () {
            var app_id = $(this).attr('data-id');
            delete synch.app_data[app_id];
        });

        var synch = {

            app_data: { } ,

            goods_data: { },

            mall_data: { },

            goods_num : 0,

            mall_num : 0,

            type : 0,

            app:function(){

                E.ajax({
                    type : 'get',
                    url : '/admin/goods/synch/app',
                    dataType : 'json' ,
                    data : {} ,
                    success : function (obj){
                        if (obj.code == 200) {
                            var html = '';
                            html += '<table class="table-a" border= "1" width= "100%" style="border-collapse:collapse" >';
                            html += '<tr><td rowspan="5" style="text-align: center;line-height: 127px;">线上平台</td></tr>';
                            $.each(obj.data,function(k,v){
                                html += '<tr><td style="padding:12px;"><input type="checkbox" class="square-radio" name="app-info" data-id="'+v.id+'" value="'+v.name+'"><img src="'+v.logo+'" style="padding:2px;"/>'+v.name+'</td></tr>';
                            });
                            html += '</table>';
                            $('.table-a').replaceWith(html);
                        }

                        $('.square-radio').iCheck({
                            checkboxClass: 'icheckbox_square-blue',
                            radioClass: 'iradio_square-blue',
                            increaseArea: '20%' // optional
                        });
                    }
                });
            },

            //同步商品
            submit:function(){

                var goods_type = $('input[name="selector_goods_type"]:checked').val();

                var mall_type = $('input[name="selector_mall_type"]:checked').val();

                E.ajax({
                    type : 'get',
                    url : '/admin/goods/synch/submit',
                    dataType : 'json',
                    data : {
                        type:type,
                        goods_type:goods_type,
                        mall_type:mall_type,
                        goods_data:plugin.goods_data,
                        mall_data:plugin.mall_data,
                        app_data:synch.app_data
                    } ,
                    success : function (obj){
                        if (obj.code == 200) {
                            layer.alert(obj.message,{icon:1,time:2000});
                        }else{
                            layer.alert(obj.message,{icon:2,time:2000});
                        }
                    }
                });
            },

            //导入商品
            import_goods:function(type){

                synch.type = type;

                var title = '导入商品';
                if (type == 2) {
                    title = '导入门店';
                }

                var html = '';
                html += '<div style="height:35px;">';
                html += '</div>';
                html += '<div class="form-group">';
                html += '<div class="col-sm-6 download">';
                html += '<div class="col-sm-6">';
                html += '<button type="button" class="layui-btn" id="file-view" style="margin-left:10px;">';
                html += '<i class="layui-icon">&#xe67c;</i>'+title;
                html += '</button>';
                html += '</div>';
                html += '<div class="col-sm-6">';
                if (synch.type == 1) {
                    html += '<button type="button" class="layui-btn" onclick="synch.download(1)" style="margin-left:55px;" id="re-export">';
                    html += '<i class="layui-icon">&#xe601;</i>模板下载';
                    html += '</button>';
                }else{
                    html += '<button type="button" class="layui-btn" onclick="synch.download(2)" style="margin-left:55px;" id="re-export">';
                    html += '<i class="layui-icon">&#xe601;</i>模板下载';
                    html += '</button>';
                }
                html += '</div>';
                html += '<div style="height:35px;">';
                html += '</div>';
                html += '</div>';

                layer.open({
                    title: '导入商品' ,
                    type: 1 ,
                    closeBtn : 0 ,
                    move:false,
                    area: '500px',
                    content:  html ,
                    btn: ['关闭']
                });

                var csrf = '{{ csrf_token() }}';

                layui.use('upload', function() {

                    var upload = layui.upload;

                    if (synch.type == 1) {

                        //执行实例
                        var uploadGoods = upload.render({
                            elem: '#file-view' //绑定元素
                            , url: '/admin/goods/synch/goodsImport' //上传接口
                            , accept: 'file'
                            ,data : {_token : csrf }
                            , done: function (obj) {
                                if (obj.code == 200) {
                                    $.each(obj.data,function(k,v){
                                        synch.goods_data[v.id] = {
                                            id: v.id,
                                            price: v.price,
                                            name:v.name,
                                            big_category_name: v.big_category_name,
                                            mid_category_name: v.mid_category_name,
                                            small_category_name: v.small_category_name
                                        };
                                        synch.goods_num = k + 1;
                                    });
                                    plugin.goods(synch.goods_data,synch.goods_num);
                                    layer.closeAll();
                                }else{
                                    layer.alert(obj.message, {icon: 2, time:2000});
                                }
                            },
                            error: function () {
                                //请求异常回调
                            }
                        });
                    }else{
                        //执行实例
                        var uploadMall = upload.render({
                            elem: '#file-view' //绑定元素
                            , url: '/admin/goods/synch/mallImport' //上传接口
                            , accept: 'file'
                            ,data : {_token : csrf }
                            , done: function (obj) {
                                if (obj.code == 200) {
                                    $.each(obj.data,function(k,v){
                                        synch.mall_data[v.id] = {
                                            id: v.id,
                                            code: v.code,
                                            name: v.name,
                                            city: v.city,
                                            business_time: v.business_time
                                        };
                                        synch.mall_num = k + 1;
                                    });
                                    plugin.mall(synch.mall_data,synch.mall_num);
                                    layer.closeAll();
                                }else{
                                    layer.alert(obj.message, {icon: 2, time:2000});
                                }

                            }
                            , error: function () {
                                //请求异常回调
                            }
                        });
                    }
                });
            },

            //下载模板
            download:function(id){

                window.location.href = '/admin/goods/synch/download/'+id;
            },

            close:function(){
                window.history.back();
            }
        };

        var plugin = {

            goods_data: { } ,

            mall_data: { } ,

            goods_num : 0,

            mall_num : 0,

            //商品弹出层
            search_goods: function () {

                layer.open({
                    title: '选择商品',
                    type: 2,
                    area: ['900px', '500px'],
                    content: '/admin/plugin'
                });
            },

            goods: function( data,goods_num) {
                plugin.loadGoods( data, goods_num);
            },

            //删除商品
            del_goods: function() {

                //获取选中需删除的商品
                var ids = $.map($("#goods_list").bootstrapTable('getSelections'), function (row) {
                    //删除data中保存的商品信息
                    delete  plugin.goods_data[row.id] ;
                    plugin.goods_num --;
                    return row.id;
                    //添加商品到表格中
                });
                //从表格中移除商品列
                $("#goods_list").bootstrapTable('remove', { field: 'id', values: ids });

            },

            loadGoods: function( data,num ){
                //循环选中的商品
                $.each( data, function ( k ,v ) {

                    //判断选中的商品是否已经存在，如果不存在则添加该商品
                    if( E.isEmpty( plugin.goods_data[v.id] ) ) {
                        //向data中插入商品信息
                        plugin.goods_data[v.id] = {
                            id: v.id
                        };
                        //添加商品到表格中
                        $("#goods_list").bootstrapTable('prepend', {
                            id: v.id,
                            price: v.price,
                            goodsName: v.name,
                            big_category: v.big_category_name,
                            mid_category: v.mid_category_name,
                            small_category: v.small_category_name
                        });
                    }else{
                        num --;
                    }
                });
                plugin.goods_num += num;
            },

            search_mall: function () {

                layer.open({
                    title: '选择门店',
                    type: 2,
                    area: ['900px', '500px'],
                    content: '/admin/plugin/mall'
                });
            },

            //门店弹出层
            mall: function( data,mall_num) {
                plugin.loadMall( data, mall_num );
            },

            //删除商品
            del_mall: function() {

                //获取选中需删除的商品
                var ids = $.map($("#mall_list").bootstrapTable('getSelections'), function (row) {
                    //删除data中保存的商品信息
                    delete  plugin.mall_data[row.id] ;
                    plugin.mall_num --;
                    return row.id;
                    //添加商品到表格中
                });
                //从表格中移除商品列
                $("#mall_list").bootstrapTable('remove', { field: 'id', values: ids });

            },

            loadMall: function( data, num ){
                //循环选中的商品
                $.each( data, function ( k ,v ) {
                    if( E.isEmpty( plugin.mall_data[v.id] ) ) {
                        //向data中插入商品信息
                        plugin.mall_data[v.id] = {
                            id: v.id
                        };

                        //添加商品到表格中
                        $("#mall_list").bootstrapTable('prepend', {
                            id: v.id,
                            mall_code: v.code,
                            mall_name: v.name,
                            city: v.city,
                            business_time: v.business_time
                        });
                    }else{
                        num --;
                    }
                });
                plugin.mall_num += num;
            }

        };
    </script>
@endsection