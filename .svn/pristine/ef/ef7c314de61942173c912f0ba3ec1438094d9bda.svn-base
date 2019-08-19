@extends('admin.layoutList')
@section('css')
    <link href="/libs/iCheck/skins/square/blue.css" rel="stylesheet">
    <style>
        .col-extend-css {
            width:100%;
            text-align: left;
        }
        .app-content{
            min-height:0px;
        }
        .app-title{
            height:10px;
        }
    </style>
@endsection

@section('search')
    <div class="form-group" >
        <label for="mall_code">商品名称：</label>
        <input style="width: 125px" type="text" class="form-control" name="goods_name" id="goods_name">
    </div>
    <div class="form-group" >
        <label for="mall_name">商品分类：</label>
        <select class="form-control" id="bigCategory" name="bigCategory">
            <option value="0">请选择</option>
        </select>
        <select class="form-control" id="midCategory" name="midCategory" style="display: none;">
            <option value="0">请选择</option>
        </select>
        <select class="form-control" id="smallCategory" name="smallCategory" style="display: none;">
            <option value="0">请选择</option>
        </select>
    </div>

@endsection

@section('extend-content')
    <div style="text-align: center;">
        <button id="select-goods" class="btn btn-border-blue">选择</button>
        <button id="goods-cancel-btn" class="btn btn-border-blue">关闭</button>
    </div>
@endsection

@section('js')
    <script src="/libs/iCheck/icheck.js"></script>
    <script type="text/javascript">

        var layui_table_ajax_url = '/admin/plugin/search';

        layui_table({
            sort_name : 'id',
            sort_order : 'desc',
            cols: [[
                {type:'checkbox', event: 'checkbox'},
                {title: '商品名称', field: 'goods_name', align: 'center' , width:'' },
                {title: '商品价格', field: 'price', align: 'center' , width:'' },
                {title: '大分类',  field: 'big_category', align: 'center',width : '' },
                {title: '中分类', field: 'mid_category', align: 'center', width:'' },
                {title: '小分类', field: 'small_category', align: 'center', width:'' }
            ]]
        });

        $(function () {

            var category_data = JSON.parse('{!! $category_data !!}');

            if (category_data.length != 0) {
                $.each(category_data,function(k,v){
                    $('#bigCategory').append('<option value="' + v.bigCategoryID + '">' + v.categoryName + '</option>')
                });
            }

            $(document).on('change', '#bigCategory', function () { //切换商品大分类

                $('#midCategory').html('<option value="0">请选择</option>').hide();
                $('#smallCategory').html('<option value="0">请选择</option>').hide();

                var id = $(this).val();

                if (id > 0) {

                        if( !$.isEmptyObject(category_data[id]['mid'])){

                        var mid_categry_data = category_data[id]['mid'];
                        $.each(mid_categry_data, function (k, v) {
                            $('#midCategory').append('<option value="' + v.midCategoryID + '">' + v.categoryName + '</option>');
                        });

                        $('#midCategory').show();
                    }

                }

            }).on('change', '#midCategory', function () { //切换商品中分类

                $('#smallCategory').html('<option value="0">请选择</option>').hide();

                var id = $(this).val();

                if (id > 0) {

                    var big_id = $('#bigCategory').val();

                    if( !$.isEmptyObject(category_data[big_id]['mid'][id]['small'])){

                        //小分类数据
                        var small_categry_data = category_data[big_id]['mid'][id]['small'];

                        $.each(small_categry_data, function (k, v) {
                            $('#smallCategory').append('<option value="' + v.smallCategoryID + '">' + v.categoryName + '</option>');
                        });
                        $('#smallCategory').show();

                    }

                }

            });

            $(document).on('click','#re-set',function(){
                $('#midCategory').hide();
                $('#smallCategory').hide();
            }).on('click','#goods-cancel-btn',function(){
                goods.close();
            });

            var goods = {

                check_data: {},

                choose_num: 0,

                close: function () {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                }
            };

            //选择商品
            $(document).on('click','#select-goods',function(){

                var dt = {
                    'id_arr' : []
                };
                var check_status = table.checkStatus('layui-table');
                var check_status_data = check_status.data;
                if (check_status_data.length <= 0) {
                    layer.msg('请至少选择一个商品',{ icon : 2 , time : 1000 ,shade: [0.15, 'black'] });
                    return false;
                }
                $.each(check_status_data, function(k, v){
                    dt.id_arr.push(v.id);
                });

                $.ajax({
                    type:'GET',
                    url:'/admin/plugin/goods',
                    data: dt,
                    dataType: 'json',
                    success: function (obj) {
                        if(obj.code == 200){
                            $.each(obj.data,function(k,v){
                                goods.check_data[v.id] = {
                                    id: v.id,
                                    name: v.name,
                                    price: v.price,
                                    spec_type: v.spec_type,
                                    status: v.status,
                                    big_category_id: v.big_category_id,
                                    big_category_name: v.big_category_name,
                                    mid_category_id: v.mid_category_id,
                                    mid_category_name: v.mid_category_name,
                                    small_category_id:v.small_category_id,
                                    small_category_name:v.small_category_name,
                                    brand:v.brand,
                                    unit:v.unit
                                };
                                goods.choose_num = k + 1;
                            });
                        }
                    },
                    complete: function(){
                        parent.plugin.goods(goods.check_data,goods.choose_num);
                        goods.close();
                    }
                });
            });
        });

    </script>
@endsection
