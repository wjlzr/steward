<?php

namespace App\Http\Controllers\Admin\Goods;

use App\Models\Goods\StAppGoodsSale;
use App\Models\Goods\StCategory;
use App\Models\Goods\StGoodsStock;
use App\Models\Mall\StMall;
use App\Services\Goods\RepertoryService;
use App\Services\Rpc\Goods\HgGoods;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;

use App\Services\Goods\GoodsStockService;
use Wm;

class StockController extends Controller
{

    /**
     * 库存列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $st_category = StCategory::orderBy('sort','ASC')->get();

        if( !$st_category -> isEmpty()){

            $st_category = $this -> getTree( $st_category->toArray() , 0 );
        }

        return view('/admin/goods/stock/index',['category' => json_encode($st_category)]);
    }

    /**
     * 查询库存列表
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {

        $goods_stock = new GoodsStockService();
        $stock_result = $goods_stock->search($request->input());

        $return_result = [
            'code' => 0,
            'count' => $stock_result['total'],
            'data' => []
        ];

        foreach($stock_result['list'] as $stock) {

            $operation = '<a href="javascript:void(0)" onclick="stock.erp_stock(\'one\', '.$stock['mall_id'].', \''.$stock['sku'].'\')">拉取ERP库存</a>';
            $operation .= '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="stock.sync_app(\'one\', '.$stock['mall_id'].', \''.$stock['sku'].'\')">同步上线平台</a>';

            $category_name = $stock['big_category_name'];
            $category_name = !empty($stock['mid_category_name'])
                ? $category_name . '->' . $stock['mid_category_name']
                : $category_name;
            $category_name = !empty($stock['small_category_name'])
                ? $category_name . '-> ' . $stock['small_category_name']
                : $category_name;

            $sku_upc = !empty($stock['upc'])
                ? $stock['sku'].'/'.$stock['upc']
                : $stock['sku'];

            $images = !empty($stock['images'])
                ? '<img src="' .explode(',', $stock['images'])[0] . '" class="w30 h30 ml10">'
                : '';

            $stock_number = '<span>' . $stock['enable_number'] . '</span>';
            $stock_number .= '<a href="#" class="inventory" data-id="' . $stock['sku'] . '" data-name="' . $stock['goods_name'] . '" data-mall="'.$stock['mall_id'].'">';
            $stock_number .= '<img src="/images/admin/updates.png" width="30px;" style="margin-top:-3px;">';
            $stock_number .= '</a>';

            $return_result['data'][] = [
                'mall_id' => $stock['mall_id'],
                'sku' => $stock['sku'],
                'operation' => $operation,
                'mall_name' => $stock['mall_name'],
                'sku_upc' => $sku_upc,
                'goods_name' => $images . $stock['goods_name'],
                'category_name' => $category_name,
                'enable_number' => $stock_number,
                'updated_at' => $stock['updated_at']
            ];


        }

        return $return_result;

    }

    /**
     * 修改商品库存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( Request $request )
    {

        $mall_id = Redis::get('ST_MALL_ID_' . session()->getId()) ? Redis::get('ST_MALL_ID_' . session()->getId()) : 0 ;
        $st_mall = StMall::find($mall_id);

        $sku_ids = $request -> input('sku_ids' ,'');
        $mall_ids = $request -> input('mall_ids' ,'');
        $enable_number = $request -> input('enable_number' ,'');

        if(empty($sku_ids)){
            return response()->json(['code' => 400 , 'message' => '缺少参数']);
        }

        if(empty($mall_ids)){
            return response()->json(['code' => 400 , 'message' => '缺少参数']);
        }

        foreach ( $mall_ids as $key => $mall_id ){

            StGoodsStock::where([['mall_id',$mall_id] , ['sku',$sku_ids[$key]]]) -> update(['enable_number' => $enable_number]);

            $st_goods_stock = StGoodsStock::where([['mall_id',$mall_id] , ['sku',$sku_ids[$key]]])->first();

            //应用同步
            $app_enable_number = $enable_number - $st_goods_stock -> lock_number < 0 ? 0 : $enable_number - $st_goods_stock -> lock_number;
            $st_goods_app = StAppGoodsSale::select('app_id','goods_id','spec_id')->where([['mall_id',$mall_id],['sku',$sku_ids[$key]]])->get();

            if( !$st_goods_app -> isEmpty()){

                foreach ( $st_goods_app as $app ){

                    $args_data = [
                        'mall_id' => $mall_id ,
                        'goods' => [
                            $app -> goods_id =>[
                                $app -> spec_id => $app_enable_number
                            ]
                        ]
                    ];

                    $res = Wm::send($app -> app_id . '.goods.batch_update_stock',$args_data);
                }
            }
        }

        return response()->json(['code' => 200 ,'message' => '操作成功']);

    }

    /**
     * 同步库存到平台
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync( Request $request )
    {

        $mall_ids = $request -> input( 'mall_ids','');
        $sku_ids = $request -> input( 'sku_ids','');


        $args = [];
        $args_mall_array = [];
        foreach ($mall_ids as $key => $mall_id ) {

            $st_goods_stock = StGoodsStock::where([['mall_id', $mall_id], ['sku', $sku_ids[$key]]])->first();

            //同步应用平台

            if( in_array($mall_id ,$args_mall_array)){

                foreach (  $args as &$item ){

                    if( $item['mall_id'] == $mall_id ) {

                        $item['sku_id'][] = $sku_ids[$key];
                    }
                }

            }else{
                $args[] = [
                    'mall_id' => $mall_id ,
                    'sku_id' => [
                        $sku_ids[$key]
                    ]
                ];

                $args_mall_array[] = $mall_id ;
            }

//            $st_goods_app = StAppGoodsSale::select('app_id','goods_id','spec_id')->where([['mall_id',$mall_id],['sku',$sku_ids[$key]]])->get();
//
//            $app_enable_number = $st_goods_stock -> enable_number - $st_goods_stock -> lock_number < 0 ? 0 : $st_goods_stock -> enable_number - $st_goods_stock -> lock_number;
//            if( !$st_goods_app -> isEmpty()){
//
//                foreach ( $st_goods_app as $app ){
//
//                    $args_data = [
//                        'mall_id' => $mall_id ,
//                        'goods' => [
//                            $app -> goods_id =>[
//                                $app -> spec_id => $app_enable_number
//                            ]
//                        ]
//                    ];
//
//                    $res = Wm::send($app -> app_id .'.goods.batch_update_stock',$args_data);
//                }
//            }
        }

        $repertory_service = new RepertoryService();
        $repertory_result = $repertory_service->appAsync( $args );

        return response()->json($repertory_result);
    }

    /**
     * 拉取erp库存并同步平台
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pullErp( Request $request)
    {

        $mall__ids = $request -> input('mall_ids','');
        $sku_ids = $request -> input('sku_ids','');

        $hg_goods = new HgGoods();

        $res = $hg_goods -> pull_store($mall__ids ,$sku_ids);

        if( $res['code'] != 200 ){
            return response()->json(['code' => 400 , 'message' => $res['message']]);
        }

        return response()->json(['code' => 200,'message' => 'ok']);
    }
    /**
     * 数据结构转换
     * @param $data
     * @param $pId
     * @return array|string
     */
    private function getTree($data, $pId)
    {
        $tree = '';

        foreach ($data as $k => $v){
            if($v['p_id'] == $pId)
            {
                $v['children'] = $this->getTree($data, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }
}