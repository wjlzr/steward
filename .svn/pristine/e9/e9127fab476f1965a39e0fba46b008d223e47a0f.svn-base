@extends('admin.layoutEdit')
@section('css')
    <link  rel="stylesheet" href="/css/admin/web/tbgoods.css?v=2018011217">
@endsection

@section('title')
    <li class="cur bill-detail"><span>同步商品至分店</span></li>
@endsection

@section('go-back-btn')
    <button class="btn btn-default layer-go-back" type="button" onclick="back()">返回同步商品</button>
@endsection

@section('content')
    <div class="main">
        <div class="step-box">
            <ul>
                <li class="cur-step"><a href="javascript:;">STEP1.选择商品</a></li>
                <li><a href="javascript:;">STEP2.选择门店</a></li>
                <li><a href="javascript:;">STEP3.选择上线平台</a></li>
                <li><a href="javascript:;">STEP4.发布商品</a></li>
            </ul>
        </div>

        <!--step1-->
        <div class="step-1 hide">
            <p class="step-title">商品列表</p>
            <div class="radio-box">
                <input type="radio" class="square-radio" value="0"><span>全部商品</span>
                <input type="radio" class="square-radio" value="1"><span>部分商品</span>
            </div>
            <div class="btn-box">
                <button class="btn btn-blue">+ 添加商品</button>
                <button class="btn btn-blue">导入商品</button>
                <button class="btn btn-default btn-delete">移除</button>
            </div>
            <div class="table-box">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>商家编码</td>
                            <td>条形码</td>
                            <td class="width40">商品名称</td>
                            <td>商品分类</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>210955671 </td>
                            <td>2093284491011</td>
                            <td class="width40"><span class="name">可口可乐300ML</span></td>
                            <td>饮料酒类-饮料</td>
                            <td>
                                <input type="checkbox" class="square-radio" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td>210955671 </td>
                            <td>2093284491011</td>
                            <td class="width40"><span class="name">可口可乐300ML</span></td>
                            <td>饮料酒类-饮料</td>
                            <td>
                                <input type="checkbox" class="square-radio" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td>210955671 </td>
                            <td>2093284491011</td>
                            <td class="width40"><span class="name">可口可乐300ML</span></td>
                            <td>饮料酒类-饮料</td>
                            <td>
                                <input type="checkbox" class="square-radio" value="0">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="page-box">
                <div class="left-page">
                    总共<span>11</span>条记录,每页显示
                    <select class="form-control">
                        <option value="">10</option>
                        <option value="">9</option>
                        <option value="">8</option>
                        <option value="">7</option>
                    </select>
                    条记录
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!--step2-->
        <div class="step-2 hide">
            <p class="has-choose">已选择：<span class="orange">425</span>个商品SKU</p>
            <p class="step-title">门店列表</p>
            <div class="radio-box">
                <input type="radio" class="square-radio" value="0"><span>全部门店</span>
                <input type="radio" class="square-radio" value="1"><span>部分门店</span>
            </div>
            <div class="btn-box">
                <button class="btn btn-blue">+ 添加门店</button>
                <button class="btn btn-blue">导入门店</button>
                <button class="btn btn-default btn-delete">移除</button>
            </div>
            <div class="table-box">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td class="width20">门店编码</td>
                        <td class="width30">门店名称</td>
                        <td class="width20">城市</td>
                        <td class="width20">营业时间</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>210955671 </td>
                            <td>五道口燕郊店</td>
                            <td>北京</td>
                            <td>8:00~22:00</td>
                            <td><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                        <tr>
                            <td>210955671 </td>
                            <td>五道口燕郊店</td>
                            <td>北京</td>
                            <td>8:00~22:00</td>
                            <td><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                        <tr>
                            <td>210955671 </td>
                            <td>五道口燕郊店</td>
                            <td>北京</td>
                            <td>8:00~22:00</td>
                            <td><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                        <tr>
                            <td>210955671 </td>
                            <td>五道口燕郊店</td>
                            <td>北京</td>
                            <td>8:00~22:00</td>
                            <td><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="page-box">
                <div class="left-page">
                    总共<span>11</span>条记录,每页显示
                    <select class="form-control">
                        <option value="">10</option>
                        <option value="">9</option>
                        <option value="">8</option>
                        <option value="">7</option>
                    </select>
                    条记录
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!--step3-->
        <div class="step-3 hide">
            <p class="has-choose">已选择：<span class="orange">425</span>个商品SKU、<span class="orange">25</span>家门店</p>
            <p class="step-title">线上平台列表</p>
            <div class="table-box">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>
                                <div class="platf">
                                    <span class="platf-logo" style="background-image: url(/images/admin/icon/p-logo.png)"></span>
                                    <p class="platf-name">京东到家</p>
                                </div>
                            </td>
                            <td class="width5"><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="platf">
                                    <span class="platf-logo" style="background-image: url(/images/admin/icon/p-logo.png)"></span>
                                    <p class="platf-name">美团外卖</p>
                                </div>
                            </td>
                            <td class="width5"><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="platf">
                                    <span class="platf-logo" style="background-image: url(/images/admin/icon/p-logo.png)"></span>
                                    <p class="platf-name">饿了么</p>
                                </div>
                            </td>
                            <td class="width5"><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="platf">
                                    <span class="platf-logo" style="background-image: url(/images/admin/icon/p-logo.png)"></span>
                                    <p class="platf-name">百度外卖</p>
                                </div>
                            </td>
                            <td class="width5"><input type="checkbox" class="square-radio" value="0"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--step4-->
        <div class="step-4">
            <p class="has-choose">已选择：<span class="orange">425</span>个商品SKU、<span class="orange">25</span>家门店、平台<span class="orange">京东到家</span>、平台<span class="orange">美团外卖</span></p>
            <p class="step-title">经营渠道发布日志</p>
            <div class="platf-box">
                <div class="platf-inner">
                    <div class="platf">
                        <span class="platf-logo" style="background-image: url(/images/admin/icon/p-logo.png)"></span>
                        <p class="platf-name">京东到家</p>
                    </div>
                    <ul class="rat-list">
                        <li>
                            <span>链接经营渠道</span>
                            <span class="line"></span>
                            <span>成功链接</span>
                        </li>
                        <li>
                            <span>发布商品信息</span>
                            <span class="line"></span>
                            <span>进行中</span>
                        </li>
                        <li>
                            <span>断开经营渠道</span>
                            <span class="line"></span>
                            <span>已断开</span>
                        </li>
                        <li>
                            <span>共发布商品数</span>
                            <span class="line"></span>
                            <span><span class="blue">320</span>个SKU</span>
                        </li>
                    </ul>
                </div>
                <div class="platf-inner">
                    <div class="platf">
                        <span class="platf-logo" style="background-image: url(/images/admin/icon/p-logo.png)"></span>
                        <p class="platf-name">京东到家</p>
                    </div>
                    <ul class="rat-list">
                        <li>
                            <span>链接经营渠道</span>
                            <span class="line"></span>
                            <span>成功链接</span>
                        </li>
                        <li>
                            <span>发布商品信息</span>
                            <span class="line"></span>
                            <span>进行中</span>
                        </li>
                        <li>
                            <span>断开经营渠道</span>
                            <span class="line"></span>
                            <span>已断开</span>
                        </li>
                        <li>
                            <span>共发布商品数</span>
                            <span class="line"></span>
                            <span><span class="blue">320</span>个SKU</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    <!--底部按钮-->
    <div class="bottom-btn">
        <div class="step-1 hide">
            <button class="btn btn-blue mr10">下一步</button>
            <button class="btn btn-default">取消</button>
        </div>
        <div class="step-2 hide">
            <button class="btn btn-default mr10">上一步</button>
            <button class="btn btn-blue">下一步</button>
        </div>
        <div class="step-4">
            <button class="btn btn-blue">完成</button>
        </div>
    </div>
@endsection

@section('js')
    <script>
        //icheck插件
        $('.square-radio').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    </script>
@endsection