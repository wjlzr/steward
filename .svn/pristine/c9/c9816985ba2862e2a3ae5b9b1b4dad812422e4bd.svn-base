<?php

namespace App\Http\Controllers\Api\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Analyse\GoodsService;
use App\Services\Analyse\BusinessService;
use App\Services\Analyse\MallService;
use App\Services\Analyse\SellService;


class AnalyseController extends Controller
{


    //营业分析接口
    public function business(Request $request)
    {
        $business_service = new BusinessService();
        $business_result = $business_service->business($request->input());
        return response()->json(['code'=>200, 'data'=>$business_result]);
    }


    //销售分析接口
    public function sell(Request $request)
    {
        $sell_service = new SellService();
        $sell_result = $sell_service->sell($request->input());
        return response()->json(['code'=>200, 'data'=>$sell_result]);
    }


    //销售看板
    public function sellBoard(Request $request)
    {
        $sell_service = new SellService();
        $sell_result = $sell_service->sellBoard($request->input());
        return response()->json(['code'=>200, 'data'=>$sell_result]);
    }


    //商品分析接口
    public function goods(Request $request)
    {
        $goods_service = new GoodsService();
        $goods_result = $goods_service->goods($request->input());
        return response()->json(['code'=>200, 'data'=>$goods_result]);
    }


    //商品类别分析接口
    public function goodsCategory(Request $request)
    {
        $goods_service = new GoodsService();
        $goods_result = $goods_service->category($request->input());
        return response()->json(['code'=>200, 'data'=>$goods_result]);
    }


    //门店分析接口
    public function mall(Request $request)
    {
        $mall_service = new MallService();
        $mall_result = $mall_service->mall($request->input());
        return response()->json(['code'=>200, 'data'=>$mall_result]);
    }


}
