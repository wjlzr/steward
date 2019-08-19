<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>商管云</title>
    <link href="http://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/bootstrap-select/1.12.2/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="/libs/layui/css/layui.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin/common.css?v=20180107000">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link href="/libs/iCheck/skins/square/blue.css" rel="stylesheet">
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
            /*overflow: hidden;*/
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
        }
        .app-title ul li.cur span {
            /*font-weight: 700;*/
            font-size:18px;
            /*color: #5f8af7;*/
            /*border-bottom: 2px solid;*/
        }
        .app-title .right-btn {
            float: right;
            padding-top: 7px;
        }

        .app-content {
            margin: 10px;
            padding: 15px;
            background:#ffffff;
            min-height: 500px;
        }

        .fl {
            float: left;
        }
        .fr {
            float: right;
        }
        .red {
            color: #ff0000;
        }

        #wrapper {
            margin: 0 15px 15px;
            padding: 15px;
            border-radius: 5px;
            min-height: 415px;
            background: #fff;
        }

        .ui-nav ul:after {
            content: "";
            display: table;
            clear: both;
        }
        .app-third-sidebar {
            width: 100%;
            height: 60px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            -webkit-transition: padding-right 0.5s;
            -moz-transition: padding-right 0.5s;
            transition: padding-right 0.5s;
        }
        .ui-nav {
            position: relative;
            border-bottom: none;
            margin-bottom: 0;
            display: block;
        }
        .ui-nav ul {
            zoom: 1;
            margin-bottom: 0;
            margin-left: 1px;
            padding: 0;
        }
        .ui-nav li {
            float: left;
        }
        .ui-nav li a {
            height: 60px;
            padding: 0 20px;
            min-width: 0;
            border: none;
            background: transparent;
            font-size: 18px;
            color: #666666;
            line-height: 60px;
            text-align: center;
            display: inline-block;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .ui-nav li span {
            display: inline-block;
        }

        .ui-nav li.active span {
            color: #01a2fd;
        }

        .top-back {
            float: right;
            position: absolute;
            right: 15px;
            top: 10px;
        }

        /* bootstrap */
        .page-header {
            margin: 0;
        }
        h4 {
            margin-top: 0;
        }
        .col-md-10 {
            text-align: right;
        }
        .form-horizontal {
            margin-top: 20px;
            padding-right: 15px;
            padding-left: 15px;
        }

        /*按钮颜色及几个主要色*/
        .btn-blue,.btn-blue:active,.btn-blue:focus{
            background-color: #01a2fd;
            border-color: #01a2fd;
            color: #fff;
        }
        .btn-blue:hover {
            background-color: #00a2d4;
            border-color: #00a2d4;
            color: #fff;
        }
        .color-red {
            color: #fb605b;
        }
        .color-blue {
            color: #01a2fd;
        }
        .color-orange {
            color: #ffa022;
        }
        .layui-laypage .layui-laypage-curr .layui-laypage-em{
            background: #00a0e9;
        }
    </style>
    @yield('css')
</head>

<body>

@yield('content')

</body>
<script>

    var Card = {

        pobj : {},

        changeCard : function(obj) {
            Card.pobj = obj;
            layer.open({
                title: '选择卡券',
                type: 2,
                offset: '30px',
                area: ['950px', '580px'],
                content: '/home/coupon/index'
            });
        },

        bind : function (data) {

            var is_selected = 0;

            $('.card_class').each(function(){
                if ($(this).attr('data_id') == data.card_id) {
                    is_selected = 1;
                }
            });

            if (is_selected) {
                layer.alert('此优惠券已经添加',{icon:2,offset:"150px",time:"1500"});
                return false;
            }

            $(Card.pobj).prev().val(data.card_name);
            $(Card.pobj).prev().attr('data_id', data.card_id);
            $(Card.pobj).prev().attr('data_type', data.card_type);
        },

        reset : function(obj){
            $(obj).prev().prev().val('');
            $(obj).prev().prev().attr('data_id', '');
            $(obj).prev().prev().attr('data_type', '');
        }

    };
</script>
<script src="/libs/jquery/jquery-2.2.2.min.js"></script>
<script src="/libs/layer-v3.0.3/layer.js"></script>
<script src="/libs/layui/layui.js" charset="utf-8"></script>
<script src="/libs/bootstrap-table-master/dist/bootstrap-table.min.js?v=20161202"></script>
<script src="/libs/bootstrap-table-master/dist/locale/bootstrap-table-zh-CN.js"></script>
<script src="/libs/bootstrap-select/dist/js/bootstrap-select.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/libs/ebsig/base.js?v=20170206"></script>
<script src="/libs/iCheck/icheck.js"> </script>
<script>
    //时间插件
    layui.use(['laydate']);

    $(function(){
        $(document).on('ifChecked', '.care_channels', function() {

            var len = $('.care_channels:checked').length;
            if (len > 1) {
                $('.care_channels:checked').iCheck('enable');
            }
            var id = $(this).val();
            $('#care_channel'+id).show();
        }).on('ifUnchecked', '.care_channels',function () {

            var len = $('.care_channels:checked').length;
            if (len == 1) {
                $('.care_channels:checked').iCheck('disable');
                var id = $(this).val();
                $('#care_channel'+id).hide();
                return;
            }
            var id = $(this).val();
            $('#care_channel'+id).hide();
        }).on('ifChecked' , '#Send_instantly' ,function(){

            $("#moni_start_time").val('');
        });
    });
</script>
@yield('js')

</html>