@extends('admin.layoutEdit')

@section('css')
    <link  rel="stylesheet" href="/css/admin/order/order.css?v=20180124001">
    <link  rel="stylesheet" href="/css/admin/order/detail.css?v=20180124002">
    <style>
        .layui-form-radio {display:block !important; }
        .layui-form-radio i{
            vertical-align: top !important;
            padding-top:3px !important;
            zoom:1;
        }
        .layui-form-radio span{
            width:240px !important;
        }
        .ebsig-page-nav .pagination{ float: right}
    </style>
@endsection

@section('title')
    <li class="cur bill-detail"><span>订单详情</span></li>
@endsection

@section('go-back-btn')
    <button class="btn btn-default layer-go-back" type="button" onclick="back()">返回</button>
@endsection

@section('content')

    <div class="order-content">
        <ul class="order-list order-list-detail">
            <li>
                <div class="order-info">
                    <div class="detail-th" style="overflow: hidden;">
                        <div class="top-item fl">
                            @if(isset($app_logo))<span><img src="{{$app_logo}}"/></span>@endif
                            <span><img src="{{$send_logo}}"/></span>
                            <span class="ml10 word-color">#{{$day_sequence}}</span>
                            <span class="ml10">{{$send_time}}</span>
                            <span class="order-num pl10">单号：
                                <em class="word-color">{{$app_order_id}}</em>
                            </span>
                        </div>
                        <div class="fr">
                            <div class="word-color"><div class="order-top-right" id="status">{{$status_name}}</div></div>
                        </div>
                    </div>
                    <div class="left-info">
                        <div class="left-item">
                            @if(!empty($send_time))
                                <div class="focus-th detail-lh">所属门店：{{$mall_name}}</div>
                            @endif
                        </div>
                        @if(!empty($remark))
                        <p class="notice detail-mark">备注：{{$remark}}</p>
                        @endif
                    </div>

                    <div class="mid-info">
                        <p>下单时间：{{$created_at}}</p>
                        <p>接单时间：{{$accept_at or '暂无'}}</p>
                    </div>

                    <div class="mid-info">
                        <p>客户实付款：<span class="fnw">{{$user_fee}}</span></p>
                        <p>预计收入：<span class="fnc">{{$mall_fee}}</span></p>
                    </div>
                    <div class="right-info">
                        <div id="cancel-layer" style="display: none; margin-left:20px;text-align:left; margin-bottom: 20px;">
                            <div class="layui-form">
                                @foreach($cancel_reason as $reason)
                                    <input type="radio" lay-filter="cancel-filter" title="{{$reason['name']}}" name="cancel-name" value="{{$reason['id']}}">
                                @endforeach
                            </div>
                            <textarea id="other-cancel-content" style="width: 230px;height:85px;margin-left:30px;margin-top:10px;display: none;" placeholder="请填写自定义原因"></textarea>
                        </div>

                        @if($apply == 3)
                            <div id="reply-layer" style="display: none; margin-left:20px;text-align:left; margin-bottom: 20px;">
                                <div class="layui-form">
                                    @foreach($remind_reply as $reply)
                                        <input type="radio" lay-filter="remind-filter" title="{{$reply}}" name="reply" value="{{$reply}}">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div id="refuse-layer" style="display: none;text-align:center;margin-bottom: 20px;">
                            <textarea id="refuse-reason" style="width: 311px;height:100px;margin-top:10px;" placeholder="请输入拒绝原因"></textarea>
                        </div>

                        <div id="express-layer" style="display: none; margin-left:20px; margin-bottom: 20px;">
                            <div class="layui-form" style="text-align:left;">
                                @foreach($express as $e)
                                    <input type="radio" lay-filter="express-filter" title="{{$e['name']}}" name="express" value="{{$e['id']}}">
                                @endforeach
                            </div>
                        </div>
                        <div class="submit-btn" style="margin-top:0">

                            @if($hang_up != 1)

                                @if($status == 0)
                                    <button class="btn btn-default" id="cancel-btn" data-val="{{$order_id}}" type="button">取消</button>
                                    <button class="btn btn-border-blue" id="receive-btn" data-val="{{$order_id}}" type="button">接单</button>
                                @endif

                                @if($apply == 1 || $apply == 2)
                                    <button type="button" class="btn btn-default" id="disagree-refund-btn" data-val="{{$order_id}}">拒绝</button>
                                    @if($apply == 1)
                                        <button type="button" class="btn btn-border-blue" id="agree-refund-btn" data-val="{{$order_id}}">同意取消</button>
                                    @elseif($apply == 2)
                                        <button type="button" class="btn btn-border-blue" id="agree-refund-btn" data-val="{{$order_id}}">同意退单</button>
                                    @endif
                                @else
                                    <!--处理催单-->
                                    @if(in_array($status, [1, 2, 3, 7, 8]) && $apply == 3)
                                        <button type="button" class="btn btn-default" id="remind-reply-btn" data-val="{{$order_id}}">回复用户</button>
                                    @endif

                                    <!--处理配货-->
                                    @if($status == 1)
                                        <button type="button" class="btn btn-border-blue" id="packs-btn" data-val="{{$order_id}}">配货</button>
                                    @endif

                                    <!--发货-->
                                    @if($status == 8)
                                        <button type="button" class="btn btn-border-blue" id="delivery-btn" data-val="{{$order_id}}" data-send-type="{{$send_type}}">发货</button>
                                    @endif

                                    <!--配货完成-->
                                    @if($status == 7)
                                        <button type="button" class="btn btn-border-blue" id="packs-finish-btn" data-val="{{$order_id}}">配货完成</button>
                                    @endif

                                    <!--完成-->
                                    @if(in_array($status, [2, 3]))
                                        <button type="button" class="btn btn-border-blue" id="finish-btn" data-val="{{$order_id}}">完成</button>
                                    @endif

                                    @if(in_array($status, [0, 1, 2, 3, 7, 8]))
                                        <button type="button" class="btn btn-default" id="hang-up-btn" data-val="{{$order_id}}">挂起</button>
                                    @endif

                                @endif
                                                    
                                @if($app_client != 0 )
                                    <button type="button" class="btn btn-default print-btn"  data-val="{{$order_id}}">打印</button>
                                @endif

                            @else
                                <button type="button" class="btn btn-border-blue" id="cancel-hang-up-btn" data-val="{{$order_id}}">取消异常</button>
                            @endif

                        </div>

                    </div>
                </div>
            </li>
        </ul>

        <div class="order-detail-content">
            <div class="shop-list">
                <div class="body-title goods-list">商品清单（{{$total_goods_number}}）</div>
                <ul class="shop-tab" style="overflow:hidden;">
                    <li>
                        <span class="top-th" style="width:350px">商品</span>
                        <span class="top-th">规格</span>
                        <span class="top-th">单价</span>
                        <span class="top-th">数量</span>
                        <span class="top-th">小计</span>
                    </li>
                    @foreach($goods as $k=>$g_v)
                        <li>
                            <div class="list-shop-li"  style="width:350px">
                                <div class="goods-pic goods-item">
                                    <span>
                                        <img src="{{$g_v['goods_image']}}" width="80px;" height="80px;" onerror='this.src="/images/admin/steward-error.png"'>
                                    </span>
                                </div>
                                <div class="goods-name goods-item">
                                    <span class="goods-name-spec">{{$g_v['goods_name']}}</span>
                                </div>
                            </div>
                            <div class="list-shop-li">
                                <div class="goods-pic goods-item">
                                    <span>{{$g_v['goods_spec']}}</span>
                                </div>
                            </div>
                            <div class="list-shop-li">
                                <div class="goods-pic goods-item">
                                    <span>￥{{$g_v['goods_price']}}</span>
                                </div>
                            </div>
                            <div class="list-shop-li">
                                <div class="goods-pic goods-item">
                                    <span>{{$g_v['goods_number']}}</span>
                                </div>
                            </div>
                            <div class="list-shop-li">
                                <div class="goods-pic goods-item">
                                    <span>￥{{$g_v['total_price']}}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="total">
                    <p>
                        <span class="text">商品金额</span>
                        <span class="value">￥{{$total_fee}}</span>
                    </p>
                    <p>
                        <span class="text">配送费</span>
                        <span class="value">￥{{$send_fee}}</span>
                    </p>
                    <p>
                        <span class="text">包装费</span>
                        <span class="value">￥{{$package_fee}}</span>
                    </p>
                    <p>
                        <span class="text">客户支付</span>
                        <span class="value">￥{{$user_fee}}</span>
                    </p>
                    <p>
                        <span class="text">预计收入</span>
                        <span class="value word-color fnc">￥{{$mall_fee}}</span>
                    </p>
                    @if($balance_fee > 0 )
                        <p>
                            <span class="text">余额支付</span>
                            <span class="value">￥{{$balance_fee}}</span>
                        </p>
                    @endif
                    @if($points_fee > 0)
                        <p>
                            <span class="text">积分抵扣</span>
                            <span class="value">￥{{$points_fee}}</span>
                        </p>
                    @endif
                    <p>
                        <span class="text">整单优惠</span>
                        <span class="value word-color">￥{{$discount_fee}}</span>
                    </p>
                    <p>
                        <span style="font-size: 12px;float: left;color:#999999;margin-top:10px">(平台优惠￥{{$app_act_fee}},商家优惠￥{{$mall_act_fee}})</span>
                    </p>
                </div>

            </div>
            <div class="cart-list">
                <div class="body-title">配送信息</div>
                <div class="distru-box">
                    <p>
                        <span class="name">{{$deliver_name}}</span>
                        <span class="word-color">第{{$order_number}}次下单</span>
                    </p>
                    <p><span class="phone">{{$deliver_mobile}}</span></p>
                    <p class="address">{{$deliver_address}}</p>
                </div>
                <div class="logistics">
                    <ul class="logistics-list">
                        @foreach($trace as $t_k=>$t_v)
                            <li @if(count($trace)-1 == $t_k)class="on"@endif>
                                <span class="border-line-top"></span>
                                <span class="border-line-bot"></span>
                                <span class="circle"></span>
                                <div class="text-box">
                                    <p class="active">{{$t_v['content']}}</p>
                                    <p class="gray-text-sm active">操作人：{{$t_v['creator']}}</p>
                                    <p class="gray-text-sm">{{$t_v['created_at']}}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/js/admin/order.event.js?v=201801240000"></script>
@endsection