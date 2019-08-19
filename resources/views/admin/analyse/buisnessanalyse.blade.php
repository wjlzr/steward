@extends('admin.layoutData')

@section('css')
    <style type="text/css">
        .date_divs {float:left;height:45px;line-height:45px;}
        .comm_content,.items{width:99%;margin:0 auto;}

        .navbar-custom,.menu {
            border-bottom: 1px solid #e7e7e7;line-height: 45px;margin:0 10px 10px 10px;border-radius:2px;
            background: none repeat scroll 0 0 ;overflow:auto; zoom:1;text-align:center;font-size:13px;
            padding: 10px 0
        }
        .navbar-custom div,.menu li{cursor: pointer;float: left;}

        .days {
            float: left;
            color: #313131;
            padding: 0 15px;
        }

        .days.active {
            color: #00a0e9;
        }

        #change_2{
            margin-left: 180px;
        }

        .analysis-table {
            width: 100%;
        }
        .analysis-table table {
            width: 100%;
        }
        .analysis-table table tr {
            border-top: 1px solid #f1f4f9;
            font-size: 14px;
            color: #2f2f2f;
            height: 80px;
            line-height: 80px;
        }
        .analysis-table table thead tr{
            border-top: none;
            color: #999;
            height: 60px;
            line-height: 60px;
        }
        .analysis-table table tr td:first-child {
            text-align: left;
            padding-left: 20px;
        }
        .analysis-table table tr td:first-child p {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .caption h1{
            color: #fb8426;
        }
        table td.width10 {
            width: 10%;
        }
        .width12 {
            width: 12%;
        }
        table td.width35 {
            width: 28%;
            padding: 0 20px;
        }
        .width35.table-title {
            overflow: hidden;
        }
        .width35.table-title .fr label {
            font-weight: normal;
            margin-left: 15px;
            color: #2f2f2f;
        }
        .width35.table-title .fr label span {
            margin-right: 5px;
        }
        .width35 .crm-progress-box {
            width: 50%;
        }
        .crm-progress-box .crm-progress {
            display: inline-block;
            width: 70%;
            height: 8px;
            border-radius: 4px;
            position: relative;
            margin: 35px 0 0 5px;
            background: #f1f4f9;
        }
        .crm-progress-box .crm-progress .crm-progress-bar {
            position: absolute;
            left: 0;
            top: 0;
            width: 50%;
            height: 8px;
            border-radius: 4px;
        }
        .add-member .crm-progress .crm-progress-bar {
            background: #fd9501;
            box-shadow: 1px 1px 2px 2px rgba(253,149,1,.25);
        }
        .total-sales {
            text-align: right;
        }
        .total-sales .crm-progress .crm-progress-bar {
            background: #01a2fd;
            box-shadow: 1px 1px 2px 2px rgba(1,162,253,.25);
        }
        .comm_content h3{
            font-size:16px;
        }
        .data-table{
            width:100%;
        }
        .data-mt{
            margin-top:55px;
        }
        .thumbnail{
            height:240px;
            transition:all 0.5s;
        }
        .thumbnail:hover{
            transition:all 0.5s;
            box-shadow: 5px 5px 10px rgba(148,148,148,0.3);
            border:1px solid #fff
        }

    </style>
@endsection

@section('content')

    <div class="app-title">
        <ul>
            <li class="cur">
                <span>营业分析</span>
            </li>
        </ul>
        <div class="right-btn">
        </div>
    </div>

    <div class="app-content">

        <div class="comm_content" style="padding: 0px;">
            <div class="items clearfix" style="border:none;">
                <div class="navbar-custom" style="margin:10px 0 ;">
                    <div class="date_divs" style="background: none;">
                        <form id="search_form" onsubmit="return false;" method="post" class="form-inline">
                            {{csrf_field()}}
                            渠道：
                            <select id="app_id" name="app_id" class="form-control">
                                <option value="">请选择渠道</option>
                                @if( isset( $app_data ) && !empty( $app_data ) )
                                    @foreach( $app_data as $k=>$v )
                                        <option value="{{$v['id']}}">{{$v['name']}}</option>
                                    @endforeach
                                @endif
                            </select>
                            &nbsp;&nbsp;起止日期：
                            <input id="startDate" name="startDate" class="form-control" readonly="readonly" type="text" maxlength="20" style="width: 110px;margin-left: 10px;"  /> ～
                            <input id="endDate" name="endDate" class="form-control" readonly="readonly" type="text" maxlength="20" style="width: 110px;" />
                            <input id="mall_id" name="mall_id" type="hidden" value="{{$mall_id or 0}}">
                            <input type="button" class="btn btn-blue"  onclick="stat.timeclick(6);" onfocus="this.blur();" value="查询">
                            <input type="button" class="btn btn-default"  onclick="stat.reset();" onfocus="this.blur();" value="重置">
                        </form>
                    </div>

                    <div id="change_2" class="days" onclick="stat.timeclick(2);" data="2">&nbsp;&nbsp;昨天&nbsp;&nbsp;</div>
                    <div id="change_3" class="days active" onclick="stat.timeclick(3);" data="3">&nbsp;&nbsp;近7天&nbsp;&nbsp;</div>
                    <div id="change_4" class="days" onclick="stat.timeclick(4);" data="4">&nbsp;&nbsp;近30天&nbsp;&nbsp;</div>

                </div>
            </div>

            <div class="comm_content" style="padding: 0px;margin-top: 10px;width: 100%;height: 250px">

                <h3 style="margin: 10px 0px;">营业总览</h3>

                <div class="col-sm-6 col-md-4" style="padding-left: 5px;">
                    <div class="thumbnail" >
                        <div class="caption">
                            <h5 style="float: left;margin: 10px 0px;">营业额（元）</h5>
                        </div>
                        <div class="caption" style="padding-bottom: 0px;">
                            <h1 style="text-align: center;" id="a1">0</h1>
                        </div>
                        <div>
                            <h5 style="text-align: center;" id="a2">0</h5>
                        </div>
                        <table class="data-table data-mt">
                            <thead>
                            <tr>
                                <td style="text-align: center; ">商品销售</td>
                                <td style="text-align: center; ">包装收入</td>
                                <td style="text-align: center; ">配送收入</td>
                            </tr>
                            </thead>
                            <tr>
                                <td id="a3" style="text-align: center; ">0</td>
                                <td id="a4" style="text-align: center; ">0</td>
                                <td id="a5" style="text-align: center; ">0</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4" style="padding-left: 5px;">
                    <div class="thumbnail" >
                        <div class="caption">
                            <h5 style="float: left;margin: 10px 0px;">支出（元）</h5>
                        </div>
                        <div class="caption" style="padding-bottom: 0px;">
                            <h1 style="text-align: center;" id="b1">0</h1>
                        </div>
                        <div>
                            <h5 style="text-align: center;" id="b2">0</h5>
                        </div>

                        <table class="data-table data-mt">
                            <thead>
                            <tr>
                                <td style="text-align: center; ">活动补贴</td>
                                <td style="text-align: center; ">服务购买</td>
                                <td style="text-align: center; ">平台收取</td>
                            </tr>
                            </thead>
                            <tr>
                                <td id="b3" style="text-align: center; ">0</td>
                                <td id="b4" style="text-align: center; ">0</td>
                                <td id="b5" style="text-align: center; ">0</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4" style="padding-left: 5px;">
                    <div class="thumbnail" >
                        <div class="caption">
                            <h5 style="float: left;margin: 10px 0px;">净收入（元）</h5>
                        </div>
                        <div class="caption" style="padding-bottom: 0px;">
                            <h1 style="text-align: center;" id="c1">0</h1>
                        </div>
                        <div>
                            <h5 style="text-align: center;" id="c2">0</h5>
                        </div>
                    </div>
                </div>

            </div>

            <div class="comm_content" style="padding: 0px;margin-top: 10px;width: 100%;height: 250px">

                <h3 style="margin: 10px 0px;">订单数据</h3>

                <div class="col-sm-6 col-md-4" style="padding-left: 5px;">
                    <div class="thumbnail" >
                        <div class="caption">
                            <h5 style="float: left;margin: 10px 0px;">有效订单数</h5>
                        </div>
                        <div class="caption" style="padding-bottom: 0px;">
                            <h1 style="text-align: center;" id="d1">0</h1>
                        </div>
                        <div>
                            <h5 style="text-align: center;" id="d2">0</h5>
                        </div>
                        <div class="caption data-mt">
                            <h5 style="text-align: center;">平均客单价 <span id="d3">0</span></h5>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4" style="padding-left: 5px;">
                    <div class="thumbnail" >
                        <div class="caption">
                            <h5 style="float: left;margin: 10px 0px;">无效订单数</h5>
                        </div>
                        <div class="caption" style="padding-bottom: 0px;">
                            <h1 style="text-align: center;" id="e1">0</h1>
                        </div>
                        <div>
                            <h5 style="text-align: center;" id="e2">0</h5>
                        </div>
                        <div class="caption data-mt">
                            <h5 style="text-align: center;">预计损失  <span id="e3">0</span></h5>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@section('js')
    <script type="text/javascript">

        layui.use('laydate',function () {
            var laydate = layui.laydate;

            laydate.render({
                elem:'#startDate'
            });

            laydate.render({
                elem:'#endDate'
            });
        });

        var stat = {

            //时间切换
            timeclick: function (source) {

                switch (source){

                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $("#change_"+source).addClass('active').siblings().removeClass("active");
                        A.analyse.getQuickTime('startDate','endDate',source);
                        break;

                    case 6:
                        $('#change_2,#change_3,#change_4').removeClass("active");
                        break;

                }

                this.search();
            },

            //查询数据
            search:function(){

                var dt = E.getFormValues('search_form');

                if (dt.startDate == '') {
                    layer.alert('开始时间不能为空，请选择', {icon: 2, offset: '70px'});
                    return false;
                }

                if (dt.endDate == '') {
                    layer.alert('结束时间不能为空，请选择', {icon: 2, offset: '70px'});
                    return false;
                }

                if(dt.endDate < dt.startDate) {
                    layer.alert('开始时间不能大于结束时间', {icon: 2, offset: '70px'});
                    return false;
                }

                var index = layer.load();

                //获取数据
                E.ajax({
                    type: 'get',
                    url: "/admin/business/analyse/search/list",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: dt,
                    success: function (o) {

                        layer.close(index);
                        if (o.code == 200) {

                            if (o.data != '') {

                                $("#a1").html(o.data.a1);
                                $("#a2").html(o.data.a2);
                                $("#a3").html(o.data.a3);
                                $("#a4").html(o.data.a4);
                                $("#a5").html(o.data.a5);
                                $("#b1").html(o.data.b1);
                                $("#b2").html(o.data.b2);
                                $("#b3").html(o.data.b3);
                                $("#b4").html(o.data.b4);
                                $("#b5").html(o.data.b5);
                                $("#c1").html(o.data.c1);
                                $("#c2").html(o.data.c2);
                                $("#d1").html(o.data.d1);
                                $("#d2").html(o.data.d2);
                                $("#d3").html(o.data.d3);
                                $("#e1").html(o.data.e1);
                                $("#e2").html(o.data.e2);
                                $("#e3").html(o.data.e3);
                            }
                        } else {
                            $("#a1").html(0);
                            $("#a2").html(0);
                            $("#a3").html(0);
                            $("#a4").html(0);
                            $("#a5").html(0);
                            $("#b1").html(0);
                            $("#b2").html(0);
                            $("#b3").html(0);
                            $("#b4").html(0);
                            $("#b5").html(0);
                            $("#c1").html(0);
                            $("#c2").html(0);
                            $("#d1").html(0);
                            $("#d2").html(0);
                            $("#d3").html(0);
                            $("#e1").html(0);
                            $("#e2").html(0);
                            $("#e3").html(0);
                        }
                    }
                });
            },

            reset:function () {
                $('#app_id').val('');
                stat.timeclick(3);
            }
        };

        //初始化
        stat.timeclick(3);

    </script>

@endsection