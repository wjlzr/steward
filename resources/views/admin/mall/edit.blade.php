@extends('admin.layoutEdit')
@section('css')
    <link rel="stylesheet" type="text/css" href="/libs/jquery/jquery-ui.css"/>
    <link rel="stylesheet" href="/libs/css/layui.css">
    <style>
        #map {
            height: 360px;
            margin-left: 0px !important;
            width: 99%;
            background: #F4F4F4;
            font-size: 16px;
            text-align: center;
            padding-top: 170px;
        }

        #send_area_list table td {
            width: 229px;
        !important;
            height: 35px;
            cursor: pointer;
        }

        #send_area_list table td.addArea {
            text-align: center;
        }

        #send_area_list table td > a {
            color: red;
            float: right;
        }

        #send_area_list table td div {
            float: right;
        }


        .logo-img img {
            width: 80px;
            height: 80px;
        }

    </style>
@endsection

@section('title')
    <ul>
        <li class="cur">
            <span>@if($mall_id)编辑门店@else新建门店@endif</span>
        </li>
    </ul>
@endsection

@section('go-back-btn')
    <button class="btn btn-default layer-go-back" type="button">返回</button>
@endsection

@section('content')

    <div class="content">

        <form id="mall-form" class="form-horizontal" onsubmit="return false;">

            <input type="hidden" name="mall_id" id="mall_id" value="{{ $st_mall['id'] or ''}}">

            <div class="form-group">
                <label for="mallName" class="col-sm-2 control-label"><span class="red">*</span> <span
                            class="change-name">门店</span>名称：</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="请输入门店名称(15字内)" id="mall_name" name="mall_name"
                           value="{{ $st_mall['name'] or ''}}"/>
                </div>
            </div>

            <div class="form-group">
                <label for="mallCode" class="col-sm-2 control-label"><span class="red">*</span> <span
                            class="change-name">门店</span>号：</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" placeholder=" 请输入门店号" id="mall_code" name="mall_code"
                           value="{{ $st_mall['code'] or ''}}"/>
                </div>
            </div>

            <div class="form-group">
                <label for="mobile" class="col-sm-2 control-label"><span class="red">*</span> <span class="change-name">门店</span>电话：</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder=" 请输入电话或手机(至少填写一个)" id="phone" name="phone"
                           value="{{ $st_mall['phone'] or ''}}" maxlength="20"/>
                </div>
                <span class="help-block"> &nbsp;方便客户与门店人员进行联系</span>
            </div>

            <div class="form-group" id="platform">
                <label for="longitude" class="col-sm-2 control-label"><span class="red">*</span> <span
                            class="change-name">营业时间</span>：</label>
                <div class="col-sm-7 radio-box">

                    @if($mall_id)
                        <span>
                           <input type="radio" class="square-radio" name="time_type"
                                  @if($st_mall['business_time_type'] == 0)checked @endif value="0">24小时
                        </span>
                        &nbsp;&nbsp;&nbsp;
                        <span>
                            <input type="radio" class="square-radio" name="time_type"
                                   @if($st_mall['business_time_type'] == 1)checked @endif value="1">指定时间段
                        </span>
                    @else
                        <span>
                           <input type="radio" class="square-radio" name="time_type" value="0">24小时
                        </span>
                        &nbsp;&nbsp;&nbsp;
                        <span>
                            <input type="radio" class="square-radio" name="time_type" checked value="1">指定时间段
                        </span>
                    @endif

                </div>
            </div>
            <div class="time-list">
                @if($mall_id && $st_mall['business_time_type'] == 1)
                    @foreach($time_arr as $k => $v)
                        <div class="time">
                            <div class="form-group">
                                <label class="radio-inline col-sm-2" style="margin-left: 180px">
                                    <input type="text" class="form-control s_time" readonly placeholder="营业开始时间"
                                           name="business_start_time_0" id="business_start_time_0"
                                           value="{{ $v[0] or '' }}"/>
                                </label>
                                <label class="radio-inline control-label pull-left"
                                       tyle="margin-left: 0px; padding-left: 0px;">～</label>
                                <label class="radio-inline col-sm-2" style="margin-left: 0px; padding-left: 15px;">
                                    <input type="text" class="form-control e_time" readonly placeholder="营业结束时间"
                                           name="business_end_time_0" id="business_end_time_0"
                                           value="{{ $v[1] or '' }}"/>
                                </label>
                                @if($k<1)
                                    <span class="time-add"><img src="/images/admin/toggle-down.png" alt="">&nbsp; <a
                                                href="javascript:void(0)">添加时间段</a></span>
                                @else
                                    <span class="time-del"><img src="/images/admin/toggle-up.png" alt="">&nbsp; <a
                                                href="javascript:void(0)">移除时间段</a></span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="time">
                        <div class="form-group">
                            <label class="radio-inline col-sm-2" style="margin-left: 180px">
                                <input type="text" class="form-control s_time" readonly placeholder="营业开始时间"
                                       name="business_start_time_0" id="business_start_time_0"
                                       value=""/>
                            </label>
                            <label class="radio-inline control-label pull-left"
                                   tyle="margin-left: -17px; padding-left: 0px;padding-top: 15px;">～</label>
                            <label class="radio-inline col-sm-2" style="margin-left: 0px; padding-left: 15px;">
                                <input type="text" class="form-control e_time" readonly placeholder="营业结束时间"
                                       name="business_end_time_0" id="business_end_time_0"
                                       value=""/>
                            </label>
                            <span class="time-add"><img src="/images/admin/toggle-down.png" alt="">&nbsp; <a
                                        href="javascript:void(0)">添加时间段</a></span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="shar_rate" class="col-sm-2 control-label"><span class="change-name">库存</span>共享率：</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="shar_rate" name="shar_rate"
                           value="{{ $st_mall['shar_rate'] or '' }}"/>
                </div>
            </div>

            <div class="form-group">
                <label for="safety_stock" class="col-sm-2 control-label"><span class="change-name">安全</span>库存：</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="safety_stock" name="safety_stock"
                           value="{{ $st_mall['safety_stock'] or '' }}"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label"><span class="red">*</span> <span class="change-name">门店</span>详细地址：</label>
                <div class="col-sm-10 form-inline">
                    <select id="province_list" class="form-control" name="provinceid">
                        <option value="{{ $st_mall['province_id'] or ''}}">{{ $st_mall['province'] or '请选择'}}</option>
                    </select>
                    <select id="city_list" class="form-control" name="cityid">
                        <option value="{{ $st_mall['city_id'] or '' }}">{{ $st_mall['city'] or '请选择'}}</option>
                    </select>
                    <select id="county_list" class="form-control" name="countyid">
                        <option value="{{ $st_mall['county_id'] or '' }}">{{ $st_mall['county'] or '请选择'}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">&nbsp;</label>
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="请输入门店详细地址" name="address" id="address"
                               value="{{ $st_mall['address'] or '' }}"/>
                        <span class="input-group-btn">
                        <button type="button" class="btn btn-success" onclick="getCoordinate();">获取经纬度</button>
                    </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="longitude" class="col-sm-2 control-label"><span class="red">*</span> 经纬度：</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control " placeholder="经度" name="longitude" id="longitude" value="{{ $st_mall['longitude'] or ''}}"/>
                </div>
                <div class="col-sm-1" style="width: 20px;margin-left: -15px;margin-top: 6px;">～</div>
                <div class="col-sm-2">
                    <input type="text" class="form-control coordinate" placeholder="纬度" name="latitude" id="latitude" value="{{ $st_mall['latitude'] or ''}}"/>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label class="col-sm-2 control-label" for=""><span class="red">*</span>门店LOGO</label>
                    <div class="col-sm-10">
                        <div class="logo-img fl">
                            <img src="{{ $st_mall['logo'] or '' }}" alt="" id="mall_pic">
                            <input type="hidden" id="mall_logo" name="mall_logo" value="{{ $st_mall['logo'] or '' }}">
                        </div>
                        <div class="add-img fl">
                            <button class="btn btn-border-blue" id="mall_upload">上传图片</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">地图定位：</label>
                <div id="location_map" style="width:500px; height:300px"></div>
            </div>

            <input type="hidden" name="o_id" id='o_id' value="{{ $st_mall['external_no'] or '' }}">
            <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <input type="button" class="btn btn-blue" onclick="save();" value="保存"/>
                    <input type="button" style="margin-left: 20px;" class="btn btn-default layer-go-back" value="关闭">
                </div>
            </div>

        </form>

    </div>
@endsection

@section('js')
    <script src="/js/admin/photo.js"></script>
    <script src="/libs/iCheck/icheck.js"></script>
    <script src="/libs/jquery/jquery-ui.min.js"></script>
    <script src="/libs/layui-v2.1.5/layui.js"></script>
    <script src="http://api.map.baidu.com/api?v=2.0&ak=F6ab12c949c3b6dfefc63d8899cc34ea"></script>

    <script>

        $('.square-radio').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $(function () {
            var csrf = '{{ csrf_token() }}';

            layui.use('upload', function(){
                var upload = layui.upload;

                //执行实例
                var uploadInst = upload.render({
                    elem: '#mall_upload' //绑定元素
                    ,url: '/upload' //上传接口
                    ,data : { action : 'company/photo', _token : csrf }
                    ,done: function(res){
                        if( res.code == 200 ){
                            console.log(res);
                            $('#mall_pic').attr('src',res.data.url);
                            $('#mall_logo').val(res.data.url)
                        }

                    }
                    ,error: function(){
                        //请求异常回调
                    }
                });
            });
        });

        function showMap(point) {

            var myGeo = new BMap.Geocoder();
            var map = new BMap.Map('location_map');
            map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));
            map.centerAndZoom(point, 18);
            map.enableScrollWheelZoom(true);
            var baiduMarker = new BMap.Marker(point);

            //开启坐标点拖拽属性
            baiduMarker.enableDragging();

            //在地图上设置坐标点
            map.addOverlay(baiduMarker);

            //设置信息窗口
            var opts = {
                width : 0,     // 信息窗口宽度
                height: 0,     // 信息窗口高度
                enableMessage:false,
                enableCloseOnClick:false,
                title : "<span style='color:red;font-weight:bold;'>位置不对吗？？</span>"  // 信息窗口标题
            };
            var html = '<div class="infoWindow_content">快来拖动此图标到达正确位置</div>';
            var infoWindow = new BMap.InfoWindow(html,opts);
            baiduMarker.openInfoWindow(infoWindow);

            //开始拖拽
            baiduMarker.addEventListener('dragstart', function(e){
                baiduMarker.closeInfoWindow(infoWindow);
            });

            //结束拖拽
            baiduMarker.addEventListener('dragend', function(e){
                myGeo.getLocation(e.point, function(rs){
                    $('#longitude').val(e.point.lng);
                    $('#latitude').val(e.point.lat);
                });
            });

        }

        showMap('上海');

        function getCoordinate() {

            var myGeo = new BMap.Geocoder();
            var province_name = $('#province_list').find('option:selected').text();
            var city_name = $('#city_list').find('option:selected').text();
            var county_name = $('#county_list').find('option:selected').text();
            var address = E.trim($('#address').val());
            myGeo.getPoint(province_name + city_name + county_name + address, function(point) {
                if (point) {
                    $('#longitude').val(point.lng);
                    $('#latitude').val(point.lat);
                    showMap(point);
                }
            });
        }



        //layui营业时间选择组件
        layui.use('laydate', function () {
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#business_start_time_0', //指定元素
                format: 'HH:mm',
                type: 'time'
            });
        });

        layui.use('laydate', function () {
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#business_end_time_0', //指定元素
                format: 'HH:mm',
                type: 'time'
            });
        });


        //营业时间
        var id = 0;

        $(document).on('click', '.time-add', function () {

            id++;

            var html = '';
            html += '<div class="time">';
            html += '<div class="form-group">';
            html += '<label class="radio-inline col-sm-2" style="margin-left: 180px">';
            html += '<input type="text" class="form-control s_time" readonly placeholder="营业开始时间"  name="business_start_time_' + id + '" id="business_start_time_' + id + '" value="" />';
            html += '</label>';
            html += '<label class="radio-inline control-label pull-left" style="margin-left: 0px; padding-left: 0px;padding-top: 15px;">～</label>';
            html += '<label class="radio-inline col-sm-2" style="margin-left: 0px; padding-left: 15px;">'
            html += '<input type="text" class="form-control e_time" readonly placeholder="营业结束时间"  name="business_end_time_' + id + '" id="business_end_time_' + id + '" value="" />';
            html += '</label>';
            html += '<span class="time-del"><img src="/images/admin/toggle-up.png" alt="">&nbsp; <a href="javascript:void(0)">移除时间段</a></span>';
            html += '</div>';
            html += '</div>';
            $('.time-list').append(html);

            layui.use('laydate', function () {
                var laydate = layui.laydate;

                //执行一个laydate实例
                laydate.render({
                    elem: '#business_start_time_' + id, //指定元素
                    format: 'HH:mm',
                    type: 'time'
                });
            });

            layui.use('laydate', function () {
                var laydate = layui.laydate;

                //执行一个laydate实例
                laydate.render({
                    elem: '#business_end_time_' + id, //指定元素
                    format: 'HH:mm',
                    type: 'time'
                });
            });

//            $('#business_start_time_' + id + ', #business_end_time_' + id + '').timepicker({
//                timeFormat: 'HH:mm',
//                showHour: true,
//                showMinute: true
//            });
        }).on('click', '.time-del', function () {
            $(this).parent(2).remove();
        }).on('ifChecked', 'input[name="time_type"]', function () {
            var status = this.value;
            if (status == 0) {
                $('.time-list').hide();
            }
            if (status == 1) {
                $('.time-list').show();
            }
        });


        //地区三级联动
        $(function () {

            E.ajax({
                type: 'get',
                url: '/admin/mall/search_region',
                dataType: 'json',
                data: {id: 0},
                success: function (obj) {
                    if (!$.isEmptyObject(obj.data)) {

                        $.each(obj.data, function (k, v) {
                            $('#province_list').append('<option value="' + v.id + '">' + v.name + '</option>');
                        });

                    }
                }
            });

            $(document).on('change', '#province_list', function () {

                $('#city_list').html('<option value="0">请选择</option>').hide();
                $('#county_list').html('<option value="0">请选择</option>').hide();

                var city_id = $(this).val();

                if (city_id > 0) {

                    E.ajax({
                        type: 'get',
                        url: '/admin/mall/search_region',
                        dataType: 'json',
                        data: {id: city_id},
                        success: function (obj) {
                            if (!$.isEmptyObject(obj.data)) {

                                $.each(obj.data, function (k, v) {
                                    $('#city_list').append('<option value="' + v.id + '">' + v.name + '</option>');
                                });

                                $('#city_list').show();
                            }
                        }
                    });

                }

            }).on('change', '#city_list', function () {

                $('#county_list').html('<option value="0">请选择</option>').hide();

                var county_id = $(this).val();

                if (county_id > 0) {

                    E.ajax({
                        type: 'get',
                        url: '/admin/mall/search_region',
                        dataType: 'json',
                        data: {id: county_id},
                        success: function (obj) {
                            if (!$.isEmptyObject(obj.data)) {

                                $.each(obj.data, function (k, v) {
                                    $('#county_list').append('<option value="' + v.id + '">' + v.name + '</option>');
                                });

                                $('#county_list').show();
                            }
                        }
                    });
                }

            });

        });

        //保存数据
        function save() {

            var err_msg = '';
            var s_time_arr = [];
            var e_time_arr = [];
            var mall_name = $('#mall_name').val();
            var mall_code = $('#mall_code').val();
            var phone = $('#phone').val();
            var address = $('#address').val();
            var longitude = $('#longitude').val();
            var latitude = $('#latitude').val();
            var shar_rate = $('#shar_rate').val();
            var safety_stock = $('#safety_stock').val();
            var provinceid = $('#province_list').val();
            var cityid = $('#city_list').val();
            var countyid = $('#county_list').val();
            var mall_id = $('#mall_id').val();
            var mall_logo = $('#mall_logo').val();
            var o_id = $('#o_id').val();

            if (address == '') {
                err_msg += '请输入地址<br/>';
            }

            if (phone == '') {
                err_msg += '请输入联系电话<br/>';
            }

            if (mall_name == '') {
                err_msg += '请输入门店名称<br/>';
            }

            if (mall_code == '') {
                err_msg += '请输入门店号<br/>';
            }

            if (longitude == '' || latitude == '') {
                err_msg += '请输入经纬度<br/>';
            }

            var status = $('input[name="time_type"]:checked').val();

            if (status == 1) {

                $('.s_time').each(function () {

                    var s_time = $(this).val();

                    if (s_time == '') {

                        err_msg += '请设置所有的时间<br/>';
                        return false;
                    }

                    s_time_arr.push(s_time);
                });

                $('.e_time').each(function () {

                    var e_time = $(this).val();

                    if (e_time == '') {

                        err_msg += '请设置所有的时间<br/>';
                        return false;
                    }

                    e_time_arr.push(e_time);
                });
            }

            if (err_msg != '') {
                layer.msg(err_msg, {icon: 2, shade: [0.15, 'black'], offset: '120px', time: 1000});
                return false;
            }

            var dt = E.getFormValues('mall-form');
            console.log(dt);
            layer.confirm('您确认要保存门店吗？', {icon: 3, offset: '50px'}, function (index) {

                E.ajax({
                    type: 'get',
                    url: '/admin/mall/save',
                    dataType: 'json',
                    data: {
                        mall_id: mall_id,
                        mall_name: mall_name,
                        mall_code: mall_code,
                        phone: phone,
                        start_arr: s_time_arr,
                        end_arr: e_time_arr,
                        shar_rate: shar_rate,
                        safety_stock: safety_stock,
                        province_id: provinceid,
                        city_id: cityid,
                        county_id: countyid,
                        address: address,
                        latitude: latitude,
                        longitude: longitude,
                        status: status,
                        mall_logo:mall_logo,
                        o_id:o_id
                    },
                    success: function (o) {
                        if (o.code == 200) {
                            window.parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            layer.msg(o.message, {icon: 1, shade: [0.15, 'black'], offset: '120px', time: 1000});
                        } else {
                            layer.msg(o.message, {icon: 2, shade: [0.15, 'black'], offset: '120px', time: 1000});
                        }
                    }
                });
            })

        }


    </script>


@endsection






