<?php

namespace App\Services\Goods;

use App\Models\Goods\StAppGoodsSale;
use App\Models\Goods\StGoodsSale;
use App\Models\Goods\StGoodsStock;
use App\Models\StApp;
use Wm;


class RepertoryService
{


    /**
     * 商品库存数据查询
     * @param $args
     * @return array
     */
    public function search($args)
    {

        $page_size = isset($args['page_size'])
            ? $args['page_size']
            : 10;

        $where = [];

        if (isset($args['mall_name']) && !empty($args['mall_name'])) {
            $where[] = ['st_goods_stock.mall_name', 'like', '%' . $args['mall_name'] . '%'];
        }

        if (isset($args['name']) && !empty($args['name'])) {
            $where[]= ['st_goods_sale.name', 'like',  '%' . $args['name'] . '%'];
        }

        if (isset($args['mall_id']) && ebsig_is_int($args['mall_id'])) {
            $where[] = ['st_goods_stock.mall_id', $args['mall_id']];
        }

        if (isset($args['sku']) && !empty($args['sku'])) {
            $where[] = ['st_goods_stock.sku', $args['sku']];
        }

        if (isset($args['upc']) && !empty($args['upc'])) {
            $where[] = ['st_goods_sale.upc', $args['upc']];
        }

        if (isset($args['bigCategoryID']) && ebsig_is_int($args['bigCategoryID'])) {
            $where[]= ['st_goods_sale.big_category_id', $args['bigCategoryID']];
        }

        if (isset($args['midCategoryID']) && ebsig_is_int($args['midCategoryID'])) {
            $where[]= ['st_goods_sale.mid_category_id', $args['midCategoryID']];
        }

        if (isset($args['smallCategoryID']) && ebsig_is_int($args['smallCategoryID'])) {
            $where[]= ['st_goods_sale.small_category_id', $args['smallCategoryID']];
        }

        $stock_list = StGoodsStock::select(
                            'st_goods_sale.name', 'st_goods_sale.sku', 'st_goods_sale.upc', 'st_goods_sale.big_category_name',
                            'st_goods_sale.mid_category_name', 'st_goods_sale.small_category_name', 'st_goods_sale.id',
                            'st_goods_stock.mall_id', 'st_goods_stock.mall_name', 'st_goods_stock.enable_number',
                            'st_goods_stock.lock_number', 'st_goods_stock.updated_at', 'st_goods_sale.goods_id'
                        )
                        ->leftJoin('st_goods_sale', 'st_goods_sale.sku', '=', 'st_goods_stock.sku')
                        ->where($where)
                        ->orderBy('st_goods_stock.updated_at', 'desc')
                        ->orderBy('st_goods_sale.goods_id', 'desc')
                        ->paginate($page_size)
                        ->toArray();

        $current_page = isset($order_list['current_page'])
            ? $order_list['current_page']
            : 1;

        $stock_result = [
            'total' => $stock_list['total'],
            'page' => $current_page,
            'list' => []
        ];

        foreach($stock_list['data'] as $stock) {

            $stock_result['list'][] = [
                'updated_at' => app_to_string($stock['updated_at']),
                'goods_id' => app_to_int($stock['goods_id']),
                'speck_id' => app_to_int($stock['id']),
                'goods_name' => app_to_string($stock['name']),
                'big_category_name' => app_to_string($stock['big_category_name']),
                'mid_category_name' => app_to_string($stock['mid_category_name']),
                'small_category_name' => app_to_string($stock['small_category_name']),
                'mall_name' => app_to_string($stock['mall_name']),
                'mall_id' => app_to_string($stock['mall_id']),
                'lock_number' => app_to_int($stock['lock_number']),
                'enable_number' => app_to_int($stock['enable_number']),
                'sku' => app_to_string($stock['sku']),
                'upc' => app_to_string($stock['upc'])
            ];

        }

        return $stock_result;

    }


    /**
     * 应用商品库存同步
     * @param $args = [
     *      [
     *          'mall_id' => int 门店ID
     *          'sku_id' => [ array 商品sku数组
     *              $sku1,
     *              $sku2
     *          ]
     *      ],
     * ]
     * @param int $app_id
     * @return array
     */
    public function appAsync($args, $app_id = 0)
    {

        $app_array = [];
        if (empty($app_id)) {
            $app_array = StApp::where('enable', 1)->get();
        } else {
            $app_array[] = StApp::find($app_id);
        }

        foreach($app_array as $app) {

            foreach ( $args as $arg ){

                $goods = [];
                foreach ( $arg['sku_id'] as $sku){

                    $st_goods_stock = StGoodsStock::where([['sku',$sku],['mall_id',$arg['mall_id']]]) -> first();
                    $app_enable_number = 0 ;

                    if( $st_goods_stock ){
                        $app_enable_number = $st_goods_stock -> enable_number - $st_goods_stock -> lock_number < 0
                            ? 0 : $st_goods_stock -> enable_number - $st_goods_stock -> lock_number ;
                    }
                    $st_app_goods_sale = StAppGoodsSale::where([['sku',$sku],['mall_id',$arg['mall_id']],['app_id',$app->id]]) -> first();

                    if( $st_app_goods_sale ){
                        $goods[ $st_app_goods_sale -> goods_id ][$st_app_goods_sale -> spec_id] =  $app_enable_number ;
                    }
                }
                if ( !empty($goods)){
                    $args_data = [
                        'mall_id' => $arg['mall_id'] ,
                        'goods' => $goods
                    ];

                    $res = Wm::send( $app -> id .'.goods.batch_update_stock' ,$args_data );

                    if( $res['code'] != 200 ){
                        return ['code' => 400 ,'message' => $res['message']];
                    }
                }
            }
        }

        return ['code'=>200, 'message'=>'ok'];

    }


}