@extends('admin.layoutData')

@section('css')
    <style type="text/css">

        .date_divs {float:left;height:45px;line-height:45px;margin-left:15px;}
        .comm_content,.items{width:99%;margin:0 auto;}

        .navbar-custom,.menu {
            border: 1px solid #e7e7e7;line-height: 45px;margin:0 10px 10px 10px;border-radius:2px;
            background: none repeat scroll 0 0 #f8f8f8;overflow:auto; zoom:1;text-align:center;font-size:13px;
        }
        .navbar-custom div,.menu li{cursor: pointer;float: left;padding: 0 10px;}

        .navbar-custom div:hover, .navbar-custom div.selected,.menu li:hover,.menu li.curMenu{background: #e7e7e7;}

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
            height: 30px;
            line-height: 30px;
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
        .width10 {
            width: 10%;
        }
        .width12 {
            width: 11%;
        }
        table td.width35 {
            width: 23%;
            padding: 0 5px;
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
        .width35 .fl {
            width: 85px;
        }

        p {
            padding: 0;
        }

        .layui-table-header table thead tr th .layui-table-cell{
            height: 28px;
        }

        .layui-table-body table tbody tr td .layui-table-cell {
            padding: 0;
            height: auto;
        }

        .layui-table-body table tbody tr td .layui-table-cell .p {
            height: 30px;
            padding: 0 5px;
            /*border-bottom: 1px solid #e2e2e2;*/
        }

        .layui-table-body table tbody tr td .laytable-cell-1-cal_date {
            vertical-align: middle;
        }

        .p .fl {
            width: 85px;
            height:24px;
        }
        .width35 .progress {
            width: 120px;
            margin-top: 5px;
            margin-bottom: 5px;
            height:24px;
        }

        .p .progress {
            width: 120px;
            margin-top: 3px;
            margin-bottom: 3px;
            height:24px;
        }

    </style>
@endsection

@section('content')

    <div class="app-title">
        <ul>
            <li class="cur">
                <span>销售分析</span>
            </li>
        </ul>
        <div class="right-btn">
            <button class="btn btn-blue top-right-btn" type="button" onclick="stat.statExport();">导出</button>
        </div>
    </div>

    <div class="app-content">

        <div class="comm_content" style="padding: 0px;">
            <div class="items clearfix" style="border:none;">
                <div class="navbar-custom" style="margin:10px 0 ;">
                    <div class="date_divs" style="background: none;">
                        <form id="search_form" onsubmit="return false;" method="post" class="form-inline layui-form">
                            {{csrf_field()}}
                            起止日期：
                            <input id="startDate" name="startDate" class="form-control layui-input" readonly="readonly" type="text" maxlength="20" style="width: 110px;margin-left: 10px;"  /> ～
                            <input id="endDate" name="endDate" class="form-control layui-inpu" readonly="readonly" type="text" maxlength="20" style="width: 110px;" />
                            <input  id ="mall_id" name="mall_id" value="0" type="hidden">
                            <input type="button" class="btn btn-blue search" id="search" onclick="stat.timeclick(6);" onfocus="this.blur();" value="查询">
                        </form>
                    </div>
                    <div id="change_2" onclick="stat.timeclick(2);" class="search" data="2">&nbsp;&nbsp;昨天&nbsp;&nbsp;</div>
                    <div id="change_3" class="selected" onclick="stat.timeclick(3);" data="3">&nbsp;&nbsp;最近7天&nbsp;&nbsp;</div>
                    <div id="change_4"  onclick="stat.timeclick(4);" class="search" data="4">&nbsp;&nbsp;最近30天&nbsp;&nbsp;</div>

                </div>
            </div>

            <div class="comm_content" style="padding: 0px;margin-top: 10px;width: 100%;height: 132px">

                <div class="col-sm-6 col-md-3" style="padding-left: 5px;">
                    <div class="thumbnail" style="background-color: #FF6666">
                        <div class="caption">
                            <h3 style="text-align: center;margin: 10px 0px;">总营业额</h3>
                            <h3 style="text-align: center" id="total_money">0</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail" style="background-color: #FFFF33">
                        <div class="caption">
                            <h3 style="text-align: center;margin: 10px 0px;">预计收入</h3>
                            <h3 style="text-align: center" id="total_income">0</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail" style="background-color: #33CCFF">
                        <div class="caption">
                            <h3 style="text-align: center;margin: 10px 0px;">有效订单数</h3>
                            <h3 style="text-align: center;" id="total_bill">0</h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-3" style="padding-right: 5px;">
                    <div class="thumbnail" style="background-color: #00FF66">
                        <div class="caption">
                            <h3 style="text-align: center;margin: 10px 0px;">客单价</h3>
                            <h3 style="text-align: center;" id="cust_price">0</h3>
                        </div>
                    </div>
                </div>

            </div>

            <div class="comm_content have-data layui" style="padding: 0;height: 50px;">

                <div class="layui-form col-sm-offset-6 col-sm-6">
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <input type="radio" id="check_1" name="dataType" value="1" checked title="总营业额">
                            <input type="radio" id="check_2" name="dataType" value="2" title="预计收入">
                            <input type="radio" id="check_3" name="dataType" value="3" title="有效订单数">
                        </div>
                    </div>
                </div>

            </div>
            <div class="comm_content have-data" style="padding: 0px;height: 400px;">
                <div id="axisChart" style="width: 100%;height:400px;"></div>
            </div>

            <div class="comm_content" style="padding: 0;margin-bottom: 20px;margin-top: 10px;">
                <div class="col-lg-12" style="padding: 0;">
                    <table id="table" lay-filter="table-filter"></table>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('js')

    <script>

        var table;

        $(document).on('click', '.search', function () {
            layui_table_reload();
        }).on('click', '#re-set', function () {
            $('#search_form')[0].reset();
            layui_table_reload();
        }).on('click', '.layer-go-back', function () {
            E.layerClose();
        });

        layui.use(['laydate','form'],function () {
            var laydate = layui.laydate;
            var form = layui.form;

            laydate.render({
                elem:'#startDate'
            });

            laydate.render({
                elem:'#endDate'
            });

            /*
             * 切换数据
             * */
            form.on('radio', function(data){

                if( choseData !='' ) {

                    var dataType = data.value;//被点击的radio的value值
                    app_series = [];

                    if( dataType == 1 ) {

                        axischart_option.legend.data = new Array();
                        axischart_option.title.text = '总营业额';
                        axischart_option.xAxis[0].data = new Array();
                        axischart_option.series = new Array();

                        $.each(choseData,function(k,v){

                            axischart_option.xAxis[0].data.push(k);

                            $.each(v,function (t,n) {

                                var app_index = $.inArray(t,app_series);

                                if ( app_index == -1 ) {

                                    app_series.push(t);

                                    axischart_option.legend.data.push(n.title);
                                    axischart_option.series.push(
                                        {
                                            name:n.title,
                                            type:'bar',
                                            yAxisIndex: 1,
                                            data:[]
                                        }
                                    );
                                }

                                var push_index = $.inArray(t,app_series);

                                //取t-1 匹配series数组的键值
                                axischart_option.series[push_index].data.push(n.num_1);

                            });

                        });

                    } else if( dataType == 2 ) {

                        axischart_option.legend.data = new Array();
                        axischart_option.title.text = '预计收入';
                        axischart_option.xAxis[0].data = new Array();
                        axischart_option.series = new Array();

                        $.each(choseData,function(k,v){

                            axischart_option.xAxis[0].data.push(k);

                            $.each(v,function (t,n) {

                                var app_index = $.inArray(t,app_series);

                                if ( app_index == -1 ) {

                                    app_series.push(t);

                                    axischart_option.legend.data.push(n.title);
                                    axischart_option.series.push(
                                        {
                                            name:n.title,
                                            type:'bar',
                                            yAxisIndex: 1,
                                            data:[]
                                        }
                                    );
                                }

                                var push_index = $.inArray(t,app_series);

                                //取t-1 匹配series数组的键值
                                axischart_option.series[push_index].data.push(n.num_2);

                            });

                        });

                    } else if( dataType == 3 ) {

                        axischart_option.legend.data = new Array();
                        axischart_option.title.text = '有效订单数';
                        axischart_option.xAxis[0].data = new Array();
                        axischart_option.series = new Array();

                        $.each(choseData,function(k,v){

                            axischart_option.xAxis[0].data.push(k);

                            $.each(v,function (t,n) {

                                var app_index = $.inArray(t,app_series);

                                if ( app_index == -1 ) {

                                    app_series.push(t);

                                    axischart_option.legend.data.push(n.title);
                                    axischart_option.series.push(
                                        {
                                            name:n.title,
                                            type:'bar',
                                            yAxisIndex: 1,
                                            data:[]
                                        }
                                    );
                                }

                                var push_index = $.inArray(t,app_series);

                                //取t-1 匹配series数组的键值
                                axischart_option.series[push_index].data.push(n.num_3);

                            });

                        });

                    }

                    axischart.setOption(axischart_option);
                }

            });

        });

        function layui_table(params) {

            layui.use('table', function () {

                table = layui.table;
                var dt = E.getFormValues('search_form');
                var render = {
                    elem: '#table',
                    id:'layui-table',
//                    height: 400,
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
                    }
                };
                for(var key in params) {
                    if (key != 'initSort') {
                        render[key] = params[key];
                    }
                }

                table.render(render);
            });

        }

        function layui_table_reload() {

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

            table.reload('layui-table', {
                where:dt,
                page:{
                    curr:1
                }
            });
        }

        var layui_table_ajax_url = '/admin/business/analyse/1/list';

        layui_table({
            sort_name : 'cal_date',
            sort_order : 'desc',
            method : 'POST',
            cols: [[
                {title: '日期', field: 'cal_date', align: 'center', style:'vertical-align: middle', width:'10%' },
                {title: '渠道', field: 'app_name', align: 'left' , width:'10%' },
                {title: '总营业额（元）', field: 'sale_money', align: 'center' , width:'23%' },
                {title: '预计收入（元）',  field: 'expect_income', align: 'left',width : '23%' },
                {title: '有效订单数（单）', field: 'useful_bill', align: 'left', width:'23%' },
                {title: '客单价（元）', field: 'cust_price', align: 'center', width:'11%' }
            ]]
        });


    </script>

    <script type="text/javascript">

        var choseData = '';
        var app_series = [];

        //柱状-折线混合图
        var axischart = echarts.init(document.getElementById('axisChart'));
        var axischart_option = {
            title : {
                text: '',
                subtext: ''
            },
            tooltip: {
                trigger: 'axis'
            },
            toolbox: {
                feature: {
                    magicType: {show: true, type: ['bar', 'bar']},
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            legend: {
                data:['微电汇','饿了么','京东到家']
            },
            dataZoom:{
                orient:"horizontal", //水平显示
                show:true//显示滚动条
            },
            xAxis: [
                {
                    type: 'category',
                    data: [],
                    axisLabel:{
                        interval:0,
                        rotate:30,
                        margin:2,
                        textStyle:{
                            color:"#222"
                        }
                    }
                }
            ],
            yAxis: [
                {
                    type: 'value',
                    name: '个数',
                    axisLabel: {formatter: '{value}'}
                },
                {
                    type: 'value',
                    name: '个数',
                    axisLabel: {formatter: '{value}'}
                }
            ],
            series: [
                {
                    name:'微电汇',
                    type:'bar',
                    data:[]
                },
                {
                    name:'饿了么',
                    type:'bar',
                    data:[]
                },
                {
                    name:'京东到家',
                    type:'bar',
                    yAxisIndex: 1,
                    data:[]
                }
            ]
        };

        //柱状-折线混合图
        axischart.setOption(axischart_option);

        var stat = {

            rp : 5,

            //时间切换
            timeclick: function (source) {

                switch (source){

                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $("#change_"+source).addClass('selected').siblings().removeClass("selected");
                        A.analyse.getQuickTime('startDate','endDate',source);
                        break;

                    case 6:
                        $('#change_2,#change_3,#change_4').removeClass("selected");
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
                    type: 'POST',
                    url: "/admin/business/analyse/1/report",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: dt,
                    success: function (o) {

                        $(".have-data").hide();

                        layer.close(index);
                        choseData = o.report;
                        app_series = [];

                        if ( o.total ) {
                            $("#total_money").html(Number(o.total.total_money).toFixed(2));
                            $("#total_income").html(Number(o.total.total_income).toFixed(2));
                            $("#total_bill").html(o.total.useful_bill);
                            $("#cust_price").html(Number(o.total.cust_price).toFixed(2));
                        }

                        $("#check_2").iCheck('uncheck');
                        $("#check_3").iCheck('uncheck');
                        $("#check_1").iCheck('check');

                        axischart_option.legend.data = new Array();
                        axischart_option.title.text = '有效订单数';
                        axischart_option.xAxis[0].data = new Array();
                        axischart_option.series = new Array();

                        if ( choseData != '') {

                            $("#axisChart").show();

                            $.each(choseData,function(k,v){

                                axischart_option.xAxis[0].data.push(k);

                                $.each(v,function (t,n) {

                                    var app_index = $.inArray(t,app_series);

                                    if ( app_index == -1 ) {

                                        app_series.push(t);

                                        axischart_option.legend.data.push(n.title);
                                        axischart_option.series.push(
                                            {
                                                name:n.title,
                                                type:'bar',
                                                yAxisIndex: 1,
                                                data:[]
                                            }
                                        );
                                    }

                                    var push_index = $.inArray(t,app_series);

                                    //取t-1 匹配series数组的键值
                                    axischart_option.series[push_index].data.push(n.num_1);

                                });

                            });

                            //柱状-折线混合图
                            axischart.setOption(axischart_option);
                            $(".have-data").show();

                        }

                    }
                });
            },

            statExport:function () {

                var exportIndex = 1129;

                layer.confirm( '您确定导出销售分析信息吗?' ,{icon:3,offset:'50px'}, function( index ) {
                    layer.close(index);
                    com_export.download('search_form', '/admin/goods/analyse/export',exportIndex );
                });
            },


        };

        /*获取对象、数组的长度、元素个数
         *obj 要计算长度的元素，可以为object、array、string
         */
        function count(obj){
            var objType = typeof obj;
            if(objType == "string"){
                return obj.length;
            }else if(objType == "object"){
                var objLen = 0;
                for(var i in obj){
                    objLen++;
                }
                return objLen;
            }
            return false;
        }

        //初始化
        stat.timeclick(3);

        $(document).ready(function(){
            $(".have-data").hide();
        });

        $(document).on('click', '.pagination a', function(){ /*分页事件*/
            var page = $(this).attr('data-paging');
            if (page) {
                if ( page === '...' ) { /*点击无效*/
                    return false;
                }
                stat.getdata(page);
            }
        })


    </script>
@endsection