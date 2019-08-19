<!DOCTYPE html>
<html>
<head lang="en">
    <title>商管云.开发管理系统</title>
    <link href="/libs/bootstrap-3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="/libs/bootstrap-table-master/dist/bootstrap-table.min.css" rel="stylesheet">
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
            background: #fff;
            overflow: hidden;
            padding: 0 20px;
        }
        .app-title ul {
            float: left;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            overflow: hidden;
        }
        .app-title ul li {
            float: left;
            margin-right: 20px;
            line-height: 48px;
        }
        .app-title ul li span {
            padding-bottom: 13px;

        }
        .app-title ul li.cur span {
            font-weight: 700;
            /*color: #ff5400;*/
            /*border-bottom: 2px solid;*/
        }
        .app-title .right-btn {
            float: right;
            padding-top: 7px;
        }

        #search-form {
            float: right;
        }

        .app-content {
            margin: 10px;
            padding: 15px;
            background:#ffffff;
            min-height: 500px;
            overflow: hidden;
        }

        .form-horizontal .form-group {
            margin-right: 0;
            margin-left: 0;
        }
        .red {
            color: red;
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

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-3">
                    @yield('btn')
                </div>
                <div class="col-md-9" style="text-align: right;">
                    <form class="form-inline" id="search-form" onsubmit="return false;">
                        @yield('search')
                        <button class="btn btn-default" id="search" type="button">查询</button>
                        <button class="btn btn-warning" id="re-set" type="button">重置</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-lg-12">
            <table id="table"></table>
        </div>
    </div>

</div>

</body>
<script src="/libs/jquery/jquery-1.9.1.min.js"></script>
<script src="/libs/layer-v3.0.3/layer.js"></script>
<script src="/libs/bootstrap-table-master/dist/bootstrap-table.min.js?v=20161202"></script>
<script src="/libs/bootstrap-table-master/dist/locale/bootstrap-table-zh-CN.js"></script>
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

        $('#table').bootstrapTable({
            classes: 'table table-hover', //bootstrap的表格样式
            sidePagination: 'server', //获取数据方式【从服务器获取数据】
            pagination: true, //分页
            height: $(window).height() - 200, //表格高度
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
