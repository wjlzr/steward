@extends('admin.layoutEdit')

@section('css')
    <style>
        .item-top{
            overflow: hidden;
            margin-bottom: 55px;
            margin-top:22px;
        }
        .items{
            width:48%;
            float: left;
            background:#fff;
            border-radius: 5px;
            margin-bottom:15px;
            padding: 10px;
            -webkit-box-shadow: 5px 5px 5px rgba(148,148,148,0.2);
            -moz-box-shadow: 5px 5px 5px rgba(148,148,148,0.2);
            box-shadow: 5px 5px 5px rgba(148,148,148,0.2);
        }
        .items2{
            position: relative;
            right:-37px;
            vertical-align: top;
        }
        .itemsCont,.itemsRight{
            display: inline-block;
        }
        .itemsRight{
            width: 226px;
            margin-left:28px;
            padding-top:25px;
        }
        .itemsCont p{
            color: #fff;
        }
        .synch{
            width: 33px;
            height: 26px;
            margin-bottom:10px;
        }
        .offline{
            width: 31px;
            height: 26px;
            margin-bottom:10px;
        }
        .back-radius{
            display: block;
            text-align: center;
            float: left;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            overflow: hidden;
        }
        .back-blue{
            background: url('/images/admin/gradient-blue.png') no-repeat center;
            -webkit-background-size: 100%;
            background-size: 100%;
        }
        .back-orange{
            background: url('/images/admin/gradient-orange.png') no-repeat center;
            -webkit-background-size: 100%;
            background-size: 100%;
        }
        .back-radius .itemsCont{
            margin:15px 15px;
        }
        .layui-table-box, .layui-table-view{
            border:none;
        }
        .layui-table tr{
            background: none !important;
        }
        .layui-table tr th{
            font-weight: 600;
        }
        .layui-table td, .layui-table th{
            line-height: 30px !important;
        }
        .layui-table-view .layui-table td, .layui-table-view .layui-table th{
            border-right:none;
        }
        .app-title{
            height:0;
        }
        .layui-table-view .layui-table td{
            padding: 30px 0;
        }
        .layui-table-view .layui-table th{
            padding: 3px 0 13px;
        }

    </style>
@endsection

@section('content')
    <div class="item-top">
        <div class="items">
            <a href="/admin/goods/synch/edit/1" class="back-radius back-blue">
                <div class="itemsCont">
                    <img class="synch" src="/images/admin/app/u4948.png"/>
                    <p>同步商品至线上平台</p>
                </div>
            </a>
            <p class="itemsRight">新增或更新部分商品至线上平台，其他商品均不变</p>
        </div>
        <div class="items items2">
            <a href="/admin/goods/synch/edit/2" class="back-radius back-orange">
                <div class="itemsCont">
                    <img class="offline" src="/images/admin/app/u4950.png"/>
                    <p>下架线上平台商品</p>
                </div>
            </a>
            <p class="itemsRight">下架线上平台部分商品，其他商品均不变</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div style="border-bottom:1px solid #d6d6d6;padding-bottom:10px;">
                <span style="line-height: 32px;font-weight:600">操作记录</span>
                <button class="btn btn-blue fr" onclick=layui_table_reload()>刷新操作记录</button>
            </div>
            <table id="table"></table>
        </div>
    </div>
@endsection

@section('js')
<script src="/libs/layui-v2.2.5/layui.js"></script>
    <script>
        var table;
        layui.use('table', function(){
            table = layui.table;
            table.render({
                elem: '#table',
                id:'layui-table',
                height: 315,
                limit: 10,
                url: '/admin/goods/synch/search', //数据接口
                page: true, //开启分页
                initSort:{
                    field:'sDesc',
                    type:'DESC'
                },
                size:'sm',
                cols: [[ //表头
                    {field: 'sid', title: '内容',align: 'left'},
                    {field: 'sDesc', title: '时间',align: 'center'},
                    {field: 'releaseTime', title: '操作人',align: 'center'},
                    {field: 'status', title: '状态',align: 'center'},
                    {field: 'testStatus', title: '结果',align: 'center'}
                ]]
            });

        });

        function layui_table_reload() {
            table.reload('layui-table', {
                page:{
                    curr:1
                }
            });
        }

        //搜索条件的判断
        var goods = {

        };

    </script>
@endsection

