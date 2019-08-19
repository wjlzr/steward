<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('open-receive/export/push', 'Receive\Functions\ExportController@index');

Route::any('open-receive/push_test', 'Admin\IndexController@pushTest');

Route::middleware(['receive.service'])->group(function () {

    Route::any('/open-receive/100002/push', 'Receive\EleMeController@index'); //ele push url
    Route::get('/open-receive/100002/call', 'Receive\EleMeController@call');  //ele callback url
    Route::get('/open-receive/100004/push/{id}', 'Receive\JdDjController@index'); //jd push url
    Route::get('/open-receive/100004/call', 'Receive\JdDjController@call');  //jd callback url
    Route::any('/open-receive/100003/push/{id}', 'Receive\MtFoodController@index');
    Route::any('/open-receive/100001/push', 'Receive\BdFoodController@index');

});


Route::middleware(['api.service'])->group(function () {

    Route::get('/user/login', 'Api\Home\UserController@login');
    Route::get('/user/edit_pwd', 'Api\Home\UserController@editPwd');
    Route::get('/device/work', 'Api\Home\DeviceController@work');

    //首页数据接口

    Route::get('index/sales_profile', 'Api\Home\IndexController@salesProfile');
    Route::get('index/app_orders_sales', 'Api\Home\IndexController@appOrdersSales');
    Route::get('index/goods_mall_act_sales', 'Api\Home\IndexController@goodsMallActSales');
    Route::get('index/hot_sell_goods_rank', 'Api\Home\IndexController@hotSellGoodsRank');
    Route::get('index/hot_sale_category_rank', 'Api\Home\IndexController@hotSaleCategoryRank');
    Route::get('index/mall_revenue_rank', 'Api\Home\IndexController@mallRevenueRank');
    Route::get('index/mall_order_efficiency_rank', 'Api\Home\IndexController@mallOrderEfficiencyRank');
    Route::get('index/sales_rank', 'Api\Home\IndexController@salesRank');
    Route::get('index/order_efficiency_rank', 'Api\Home\IndexController@orderEfficiencyRank');
    Route::get('index/order_status_count', 'Api\Home\IndexController@orderStatusCount');
    Route::get('index/mall_data', 'Api\Home\IndexController@mallData');

    Route::get('/order/index', 'Api\Home\OrderController@index');
    Route::get('/order/search', 'Api\Home\OrderController@search');
    Route::get('/order/detail', 'Api\Home\OrderController@detail');
    Route::get('/order/receive', 'Api\Home\OrderController@receive');
    Route::get('/order/agree_refund', 'Api\Home\OrderController@agreeRefund');
    Route::get('/order/disagree_refund', 'Api\Home\OrderController@disagreeRefund');
    Route::get('/order/delivery', 'Api\Home\OrderController@delivery');
    Route::get('/order/cancel', 'Api\Home\OrderController@cancel');
    Route::get('/order/complete', 'Api\Home\OrderController@complete');
    Route::get('/order/reply_remind', 'Api\Home\OrderController@replyRemind');
    Route::get('/order/hang_up', 'Api\Home\OrderController@hangUp');
    Route::get('/order/cancel_hang_up', 'Api\Home\OrderController@cancelHangUp');
    Route::get('/order/packs', 'Api\Home\OrderController@packs');
    Route::get('/order/complete_packs', 'Api\Home\OrderController@completePacks');
    Route::get('/order/prints', 'Api\Home\OrderController@prints');
    Route::post('/order/auto_receive', 'Api\Home\OrderController@autoReceive');
    Route::get('/order/search_condition', 'Api\Home\OrderController@searchCondition');

    //门店服务类
    Route::get('/mall/search', 'Api\Home\MallController@search');
    Route::get('/mall/mall_app_count', 'Api\Home\MallController@mallAppCount');

    Route::get('/analyse/sell', 'Api\Home\AnalyseController@sell'); //销售分析接口
    Route::get('/analyse/sell_board', 'Api\Home\AnalyseController@sellBoard'); //销售看板接口
    Route::get('/analyse/business', 'Api\Home\AnalyseController@business'); //营业分析接口
    Route::get('/analyse/goods', 'Api\Home\AnalyseController@goods'); //商品分析接口
    Route::get('/analyse/category', 'Api\Home\AnalyseController@goodsCategory'); //商品类别分析接口
    Route::get('/analyse/mall', 'Api\Home\AnalyseController@mall'); //门店分析

    //通用服务类
    Route::get('/app/search', 'Api\Home\AppController@search');


});



/*
|--------------------------------------------------------------------------
| 开放接口路由
|--------------------------------------------------------------------------
*/

Route::domain('open-api.{env}.ebsig.com')->middleware(['api.open'])->group(function() {
    Route::get('goods/add/batch', 'Api\Open\GoodsController@batchAdd'); //批量新增商品
    Route::get('goods/price/batch', 'Api\Open\GoodsController@batchPrice'); //批量修改门店商品价格
    Route::get('goods/store/batch', 'Api\Open\GoodsController@batchStore'); //批量修改门店商品库存
    Route::get('order/get', 'Api\Open\OrderController@get'); //获取订单
});


/*
|--------------------------------------------------------------------------
| EOA接口路由
|--------------------------------------------------------------------------
*/
Route::domain('eoa.{env}.ebsig.com')->group(function() {
    Route::get('message/push/app', 'Api\Eoa\MessageController@push'); //消息推送
});