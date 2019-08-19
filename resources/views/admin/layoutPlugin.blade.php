<!DOCTYPE html>
<html>
<head lang="en">
    <title>商管家</title>
    <link rel="stylesheet" href="/libs/bootstrap-3.3.5/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/libs/bootstrap-table-master/dist/bootstrap-table.min.css">
    <link rel="stylesheet" href="/libs/bootstrap-select/dist/css/bootstrap-select.css">
    <link href="/libs/iCheck/skins/square/blue.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin/common.css?v=2018011219">
    <link href="/libs/layui/css/layui.css" rel="stylesheet">
    <style>
        body {
            background: #f2f2f2;
        }
        a,a:hover,a:focus,a:active {
            text-decoration: none;
            color: #0066ff;
        }
        .app-title {
            height: 48px;
            /*background: #fff;*/
            /*overflow: hidden;s*/
            padding: 0 20px;
        }
        .app-title ul {
            float: left;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            /*overflow: hidden;*/
        }
        .app-title ul li {
            float: left;
            margin-right: 20px;
            line-height: 48px;
        }
        .app-title ul li span {
            padding-bottom: 10px;
            font-size:18px;
        }
        .app-title ul li.cur span {
            /*font-weight: 700;*/
            /*color: #5f8af7;*/
            /*border-bottom: 2px solid;*/
            font-size:18px;
        }
        .app-title .right-btn {
            float: right;
            padding-top: 7px;
        }

        /*#search-form {*/
        /*float: right;*/
        /*}*/

        .app-content {
            margin: 10px;
            padding: 15px;
            background:#ffffff;
            min-height: 500px;
        }

        .form-horizontal .form-group {
            margin-right: 0;
            margin-left: 0;
        }
        .red {
            color: red;
        }
        .col-extend-css {
            text-align: right;
        }
        /*ie7、ie8兼容性*/
        .form-inline button{
            *vertical-align: top;
            *margin-left:5px;
        }
        .form-inline .form-group{
            display: inline;
            zoom:1;
        }
        .form-inline .form-group label{
            display: inline;
            zoom:1;
        }
        .form-inline .form-group input{
            width:auto;
            display: inline;
            zoom:1;
            _line-height: 35px;
        }
        .form-control{
            *padding:0;
        }
        .pagination li {
            _float: left;
            _padding:10px 6px;
            _border:1px solid #ccc;
        }
        .pagination li.active a{
            _color:#fff;
        }

        .fixed-table-pagination{
            zoom:1;
            overflow: hidden;
        }
        .layui-laypage .layui-laypage-curr .layui-laypage-em{
            background: #00a0e9;
        }
    </style>
    @yield('css')
</head>

<body>
<div class="app-title">
    <ul>
        @yield('title')
    </ul>
    <div class="right-btn">
        @yield('title_btn')
    </div>
</div>

<div class="app-content">
    @yield('head')
    <div class="row" id="search-box">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-3">
                    @yield('btn')
                </div>
                <div class="col-md-9 col-extend-css">
                    <form class="form-inline" id="search-form" onsubmit="return false;">
                        @yield('search')
                        <button class="btn btn-blue" id="search" type="button">查询</button>
                        <button class="btn btn-default" id="re-set" type="button">重置</button>
                    </form>
                </div>
            </div>
            @yield('button')
        </div>
    </div><br>

    <div class="row">
        <div>
            @yield('tb-head')
        </div>
        <div class="col-lg-12">
            <table id="table"></table>
        </div>
    </div>

    @yield('extend-content')

</div>

</body>

<script src="/libs/jquery/jquery-1.9.1.min.js"></script>
<script src="http://cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>
<script src="/libs/layui/layui.js"></script>
<script src="/libs/bootstrap-table-master/dist/bootstrap-table.min.js?v=20161202"></script>
<script src="/libs/bootstrap-table-master/dist/locale/bootstrap-table-zh-CN.js"></script>
<script src="/libs/bootstrap-select/dist/js/bootstrap-select.js"></script>
<script src="/libs/bootstrap-3.3.5/js/bootstrap.min.js"></script>
<script src="/libs/ebsig/base.js?v=20170206"></script>
<script src="/libs/iCheck/icheck.js"></script>
<script src="/js/admin/global.js"></script>
<script>

    $(document).on('change', '#search-form select', function () {
        $('#table').bootstrapTable('refresh',{
            url: bootstrap_table_ajax_url
        });
    }).on('click', '#search', function () {
        $('#table').bootstrapTable('refresh',{
            url: bootstrap_table_ajax_url
        });
    }).on('click', '#re-set', function () {
        $('#search-form')[0].reset();
        $('#table').bootstrapTable('refresh',{
            url: bootstrap_table_ajax_url
        });
    }).on('click', '.layer-go-back', function () { //layer的iframe层关闭
        E.layerClose();
    });

    function bootstrap_table_init() {
        $('#table').bootstrapTable('refresh');
    }

    function bootstrap_table(params) {

        var table_height = $(window).height() - 48 - 25 - $('#search-box').height() - 20;

        $('#table').bootstrapTable({
            classes: 'table  table-hover', //bootstrap的表格样式
            sidePagination: 'server', //获取数据方式【从服务器获取数据】
            pagination: true, //分页
            height: table_height, //表格高度
            pageNumber: 1, //页码【第X页】
            pageSize: 10, //每页显示多少条数据
            queryParamsType: 'limit',
            queryParams: function (params) {
                var dt = E.getFormValues('search-form');
                $.extend(params, dt);
                return params;
            },
            url: bootstrap_table_ajax_url,//ajax链接
            sortName: params.sortName, //排序字段
            sortOrder: params.sortOrder,//排序方式
            columns: params.columns
        });

    }

</script>

@yield('js')

</html>