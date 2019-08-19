@extends('admin.layoutList')

@section('css')
    <style>
        .col-extend-css {
            width: 100%;
            text-align: left;
        }
        #table a {
            padding : 6px;
        }
        #table img {
            padding:5px;
        }
        .form-border {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        #pop label {
            font-weight: 500;
        }
    </style>
@endsection

@section('title')
    <li>
        <span>库存列表</span>
    </li>
@endsection

@section('search')
    <div class="form-group">
        <span>门店名称：</span>
        <input type="text" placeholder="请输入门店名称" class="form-control w150" name="mall_name">&nbsp;
    </div>
    <div class="form-group">
        <span>商品名称：</span>
        <input type="text" placeholder="请输入商品名称" class="form-control w200" name="name">&nbsp;
    </div>
    <div class="form-group" >
        <span>商品编码：</span>
        <input type="text" placeholder="请输入商品编码" class="form-control w150" name="sku">&nbsp;
    </div>
    <div class="form-group">
        <span>商品条码：</span>
        <input type="text" placeholder="请输入商品条码" class="form-control w150" name="upc">&nbsp;
    </div>
    <div class="h10"></div>
    <div class="form-group category-list">
        <span>商品分类：</span>
    </div>
@endsection
@section('button')
    <button class="btn btn-border-blue btn-batch" onclick="stock.erp_stock('all')" disabled="disabled">批量拉取ERP库存</button>
    <button class="btn btn-border-blue btn-batch" onclick="stock.sync_app('all')" disabled="disabled">批量同步线上平台</button>
    <button class="btn btn-border-blue btn-batch inventory batch" disabled="disabled">批量修改可用库存</button>
@endsection
@section('js')
    <script>

        var layui_table_ajax_url = '/admin/stock/search';

        layui_table({
            sort_name : 'updated_at',
            sort_order : 'desc',
            cols: [[
                {type:'checkbox', event: 'checkbox'},
                {title: '操作', field: 'operation', align: 'center' , width:220 },
                {title: '门店名称', field: 'mall_name', align: 'left' , width:'' },
                {title: '商品编码/条码', field: 'sku_upc', align: 'center' , width:'' },
                {title: '商品名称',  field: 'goods_name', align: 'left',width : 200 },
                {title: '商品分类', field: 'category_name', align: 'left', width:'' },
                {title: '可用库存', field: 'enable_number', align: 'center', width:'' },
                {title: '修改时间', field: 'updated_at', align: 'center', width:'' }
            ]]
        });

        var stock = {

            //同步ERP库存
            erp_stock:function(type, mall_id, sku) {

                var  dt = {
                    sku_ids : [] ,
                    mall_ids : []
                };

                if (type != 'one') {
                    var check_status = table.checkStatus('layui-table');
                    var check_status_data = check_status.data;
                    if (check_status_data.length <= 0) {
                        layer.msg('请至少选择一个商品',{ icon : 2 , time : 2000 ,shade: [0.15, 'black'] });
                        return false;
                    }
                    $.each(check_status_data, function(k, v){
                        dt.sku_ids.push(v.sku);
                        dt.mall_ids.push(v.mall_id);
                    });
                } else {
                    dt.sku_ids.push(sku);
                    dt.mall_ids.push(mall_id);
                }

                E.ajax({
                    type : 'get',
                    url :'/admin/stock/pull_erp',
                    dataType : 'json',
                    data : dt,
                    success :function ( obj ){
                        if( obj.code == 200 ){
                            layer.msg('拉取成功',{ icon : 1 ,shade: [0.15, 'black'], time : 2000 });
                            layui_table_reload();
                        }else{
                            layer.msg( obj.message ,{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                        }
                    }
                })

            },

            //同步线上平台
            sync_app: function(type, mall_id, sku) {

                var  dt = [];

                if (type != 'one') {
                    var check_status = table.checkStatus('layui-table');
                    var check_status_data = check_status.data;
                    if (check_status_data.length <= 0) {
                        layer.msg('请至少选择一个商品',{ icon : 2 , time : 2000 ,shade: [0.15, 'black'] });
                        return false;
                    }
                    var mall_arr = [];
                    var item = {
                        'mall_id' : '',
                        'sku_id' : []
                    };
                    $.each(check_status_data, function(k, v){

                        if( $.inArray(v.mall_id ,mall_arr) == -1 ){

                            item['mall_id'] = v.mall_id ;
                            item['sku_id'].push(v.sku);
                            dt.push( item );
                            mall_arr.push(v.mall_id);
                        }else{
                            $.each(dt ,function( key ,val ){
                                if( val.mall_id == v.mall_id ){
                                    val['sku_id'].push(v.sku);
                                }
                            })
                        }
                    });
                } else {

                    var item = {'mall_id' : '' ,'sku_id' : []};
                    item['mall_id'] = mall_id;
                    item['sku_id'].push(sku) ;
                    dt.push( item );
                }

                E.ajax({
                    type : 'get',
                    url :'/ajax/repertory/app_sync',
                    dataType : 'json',
                    data : { data : dt },
                    success :function ( obj ){
                        if( obj.code == 200 ){
                            layer.msg('同步成功',{ icon : 1 ,shade: [0.15, 'black'], time : 2000 });
                            layui_table_reload();
                        }else{
                            layer.msg( obj.message ,{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                        }
                    }
                })

            }

        };

        var category = $.parseJSON( '{!! $category !!}' );
        var category_html = '';
        category_html += '<select class="form-control w150 bigCategoryID" name="bigCategoryID" id="bigCategoryID"  data-wm="0">';
        category_html += '<option value="0">请选择</option>';
        if( category != ''){

            $.each(category , function( k ,v ){

                category_html += '<option value="'+ v.id +'">'+ v.name+'</option>';
            });
        }
        category_html += '</select>&nbsp;';
        category_html += '<select class="form-control midCategoryID" name="midCategoryID" id="midCategoryID" data-wm="0" style="display: none" >';
        category_html += '</select>&nbsp;';
        category_html += '<select class="form-control smallCategoryID" name="smallCategoryID" id="smallCategoryID" data-wm="0" style="display: none;">';
        category_html += '</select>&nbsp;';

        $('.category-list').append(category_html);

        $(function(){

            $(document).on('change','.bigCategoryID',function(){

                var _this = $(this);
                //隐藏三级分类
                _this.next().next().html('').hide();
                //隐藏二级
                _this.next().hide().html('');

                var bigCategoryID = _this.val();

                if( bigCategoryID != 0  ) {

                    $.each(category  ,function ( k ,v ){

                        if (v.id == bigCategoryID ){
                            if(v.children != ''){

                                var midHtml = '<option value="0">请选择</option>' ;
                                $.each(v.children ,function ( km, vm ){
                                    midHtml +='<option value="' + vm.id + '" >' + vm.name + '</option>' ;
                                });

                                _this.next().show().html(midHtml).css('display','inline-block');
                            }else{
                                //隐藏三级分类
                                _this.next().next().html('').hide();
                                //隐藏二级
                                _this.next().hide().html('');
                            }
                        }
                    })
                }
            }).on('click','.inventory',function(){   //修改库存

                var id = $(this).attr('data-id');

                if($(this).hasClass('batch')){

                    var spec_num = 0;
                    var sku_ids = [];
                    var mall_ids = [];

                    $('.layui-table-body').find('.layui-form-checked').each(function(){
                        spec_num ++ ;
                    });

                    if( !spec_num ){
                        layer.msg( '请至少选择一个商品' ,{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                        return false;
                    }

                    $('.layui-table-body').find('.layui-form-checked').each(function(){

                        var sku = $(this).parents('tr').find('.inventory').attr('data-id');
                        var mall_id = $(this).parents('tr').find('.inventory').attr('data-mall');

                        sku_ids.push(sku);
                        mall_ids.push(mall_id);
                    });

                    var html = '';
                    html += '<div id="pop" style="margin-top:10px;" style="width: 100%">';
                    html += '<div style="background: #ffffff">';
                    html += '<form id="enable_number_form" onsubmit="return false;" class="form-horizontal" role="form">';
                    html += '<div class="form-group" style="">';
                    html += '<div class="col-sm-3">';
                    html += '</div>';
                    html += '<label class="col-sm-8 " for="price_edit" >';
                    html += '<span>共选择了<span style="color: red;"> '+ spec_num +'</span>件商品</span></label>';
                    html += '</div>';
                    html += '<div class="form-group" style="margin-right: 1px; margin-left: 1px;">';
                    html += '<div class="col-sm-3">';
                    html += '</div>';
                    html += '<div class="col-sm-8">';
                    html += '<input type="radio" class="square-radio infinite" checked name="set_enable_number" value="999999">无限</br></br>';
                    html += '<input type="radio" class="square-radio noinfinite" id="set_enable_number" name="set_enable_number" value="1">具体库存数';
                    html += '</div>';
                    html += '</div></div>';
                    html += '</form>';
                    html += '</div>';

                    layer.open({
                        title:'修改库存',
                        type : 1,
                        area : ['40%','240px'],
                        move:false,
                        btnAlign:'c',
                        content : html,
                        btn : ['确认' ,'取消'],
                        yes : function(index){

                            var dt = E.getFormValues('enable_number_form');
                            var error = '' ;

                            if( dt.set_enable_number == 1){

                                if(dt.enable_number == ''){
                                    error = '请输入商品库存' ;
                                }
                                if(!E.isInt(dt.enable_number)){
                                    error = '请设置正确的商品库存' ;
                                }
                            }else{
                                dt.enable_number = 9999;
                            }

                            dt.sku_ids = sku_ids;
                            dt.mall_ids = mall_ids;

                            if( error != '' ){
                                layer.msg( error ,{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                                return false;
                            }

                            layer.close(index);

                            E.ajax({
                                type : 'get',
                                url : '/admin/stock/edit',
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

                    addcheck();

                    if($('.noinfinite').is(':checked')){

                        $(this).after('<input type="text" class="form-control" value="" name="enable_number" style="margin-top: -7px;width:80px;margin-left: 102px;">');
                    }else{
                        $(this).after('');
                    }

                    $('.infinite ,.noinfinite').on('ifClicked',function(){

                        var val = $(this).val();
                        if( val == 1){
                            $('input[name="enable_number"]').remove();
                            $('#set_enable_number').after('<input type="text" class="form-control" value="" name="enable_number" style="margin-top: -7px;width:80px;margin-left: 102px;">')
                        }else{
                            $('input[name="enable_number"]').remove();
                        }
                    });

                }else{

                    var id = $(this).attr('data-id');
                    var mall_id = $(this).parents('tr').find('.inventory').attr('data-mall');

                    var enable_number = $(this).prev().text();
                    var goods_name = $(this).attr('data-name');

                    var html = '';

                    html += '<div id="pop" style="margin-top:10px;" style="width: 100%">';
                    html += '<div style="background: #ffffff">';
                    html += '<form id="pop_form" onsubmit="return false;" class="form-horizontal" role="form">';
                    html += '<input type="hidden" value="'+ id +'" name="sku_ids[]">';
                    html += '<input type="hidden" value="'+ mall_id +'" name="mall_ids[]">';
                    html += '<div class="form-group" style="margin-right: 1px; margin-left: 28px;text-align: left">';
                    html += '<label class="col-sm-12 " for="price_edit" >';
                    html += '<span>'+ goods_name +'</span></label>';
                    html += '</div>';
                    html += '<div class="form-group" style="margin-right: 1px; margin-left: 1px;">';
                    html += '<label class="col-sm-3 control-label" for="price_edit">';
                    html += '库存：</label>';
                    html += '<div class="col-sm-8">';
                    html += '<input class="form-control"  style="width: 150px;display: inline-block;" type="text" id="price_edit" name="enable_number" maxlength="100" value="'+enable_number+'" />';
                    html += '<a href="javescript:void(0)" class="reset">    沽清 | </a>';
                    html += '<a href="javescript:void(0)" class="infinite">无限</a>';
                    html += '</div></div>';
                    html += '</form>';
                    html += '</div>';
                    html += '</div>';

                    layer.open({
                        title:'修改库存',
                        type : 1,
                        area : ['40%','auto'],
                        move:false,
                        btnAlign:'c',
                        content : html,
                        btn : ['确认' ,'取消'],
                        yes : function(index){

                            var dt = E.getFormValues('pop_form');
                            var error = '' ;

                            if( dt.enable_number == ''){
                                error = '请输入商品库存' ;
                            }

                            if(!E.isInt(dt.enable_number)){
                                error = '请设置正确的商品库存' ;
                            }

                            if( error != '' ){
                                layer.msg( error ,{ icon : 2 ,shade: [0.15, 'black'], time : 2000 });
                                return false;
                            }

                            layer.close(index);

                            E.ajax({
                                type : 'get',
                                url : '/admin/stock/edit',
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
                }
            }).on('click','.reset',function(){
                $(this).prev().val(0);
            }).on('click','.infinite',function(){
                $(this).prev().prev().val(9999);
            });
        });

        function addcheck(){

            $('.square-radio').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        }

        function layui_checkbox_call(obj) {
            console.log(obj.checked); //当前是否选中状态
            console.log(obj.data); //选中行的相关数据
            console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
        }


    </script>
@endsection
