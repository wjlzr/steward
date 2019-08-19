<?php
/**
 * 华冠项目 - 商品接口类
 * @author   liudaoyang <liudaoyang@ebsig.com>
 * @version 1.0
 */

namespace App\Services\Rpc\Goods;

use App\Models\Goods\StAppGoodsSale;
use App\Models\Goods\StGoodsSale;
use App\Models\Goods\StGoodsStock;
use App\Models\Mall\StMall;
use App\Models\StApp;
use DB;
use App\Services\HttpService;
use Mockery\CountValidator\Exception;
use Wm;

class HgGoods
{

    private $http_url = '111.207.87.201:801/api/wdh/gateway.do';
    private $appid = '40fe9ad4949331a12f5f19b477133924';
    private $key = 'f7a198818328ef8ed10cf403d3c9ce91';

    public function requestApi($request_url, $api_name, $get_data = [], $post_data = [], $request_way = 'get' )
    {

        //连接接口参数
        $http_opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        );

        //实例http类
        $ebsigHttp = new HttpService();

        //请求接口
        $result_array = $ebsigHttp->post($request_url, $post_data, $http_opts);

        return $result_array;
    }

    //初始化拉取库存
    public function store()
    {

        set_time_limit(0);

        $url = $this->http_url; //门店库存接口地址

        //查询门店信息
        $st_mall = StMall::where(['status' => 1])->get();

        if ($st_mall->isEmpty()) {
            return ['code' => 100000, 'message' => '门店信息不存在'];
        }

        $page = 1;
        $rp = 100;

        while ($page > 0) {

            foreach ($st_mall as $mall) {

                //分页查询商品信息
                $goods_data = StAppGoodsSale::where(['status' => 1])
                    ->where( 'mall_id',$mall -> id )
                    ->groupBy('spec_id')
                    ->offset(($page - 1) * $rp)
                    ->limit($rp)
                    ->get()
                    ->toArray();

                if (!isset($goods_data[$rp - 1])) {
                    $page = 0;
                } else {
                    $page++;
                }

                if (!$goods_data) {
                    break;
                }

                //三级商品编码数组
                $parameters = [];

                foreach ($goods_data as &$g) {

                    $parameters[] = $g['sku'];
                }

                $param = [
                    'appId' => $this->appid,
                    'version' => '1.0',
                    'timestamp' => time(),
                    'format' => 'json',
                    'scope' => 'goods.goods.queryStock',
                    'param' => json_encode([
                        'product_code_data' => $parameters,
                        'mall_code' => $mall->code
                    ])
                ];

                $param['sign'] = $this->createSign($param, $this->key);

                //接口请求

                $store_data = $this->requestApi($url, 'store', [], $param, 'post');

                if ($store_data['code'] != 200 || empty($store_data['data']['data'])) {
                    continue;
                }

                try {

                    DB::beginTransaction();

                    foreach ($store_data['data']['data'] as $item) {

                        $st_goods_stock = StGoodsStock::where([['mall_id', $mall->id], ['sku', $item['product_code']]])->first();

                        if (!$st_goods_stock) {

                            $st_goods_stock = new StGoodsStock();
                            $st_goods_stock->creator = 'system';
                            $st_goods_stock->mall_id = $mall->id;
                            $st_goods_stock->sku = $item['product_code'];
                            $st_goods_stock->enable_number = $item['qty'];
                            $st_goods_stock->lock_number = 0 ;
                            $st_goods_stock->status = $item['qty'] < 0 ? 0 : 1 ;
                            $st_goods_stock ->save();

                        }else{
                            $enable_number = $item['qty'] - $st_goods_stock-> lock_number ;
                            $status = ($item['qty'] - $st_goods_stock-> lock_number) < 0 ? 0 : 1;
                            StGoodsStock::where([['mall_id', $mall->id], ['sku', $item['product_code']]])
                                ->update(['enable_number' => $enable_number , 'status' => $status]);
                        }
                    }

                    //同步应用平台

                    $st_app = StApp::where('enable', 1) -> get();

                    if( !$st_app -> isEmpty()){

                        foreach ( $st_app as $app ){
                            $p = 1 ;
                            $page_size = 50 ;

                            while( $p > 0 ){

                                $st_goods_stock = StGoodsStock::select('st_app_goods_sale.spec_id','st_app_goods_sale.goods_id',
                                                                        'st_goods_stock.enable_number','st_goods_stock.lock_number')
                                                                ->leftJoin('st_app_goods_sale','st_app_goods_sale.sku','=','st_goods_stock.sku')
                                                                ->where('st_goods_stock.mall_id',$mall->id)
                                                                ->where('st_app_goods_sale.app_id',$app -> id )
                                                                ->offset(($p - 1) * $page_size)
                                                                ->limit($page_size)
                                                                ->get()
                                                                ->toArray();

                                if (!$st_goods_stock) {
                                    break;
                                }

                                if (!isset($st_goods_stock[$page_size - 1])) {
                                    $p = 0;
                                } else {
                                    $p++;
                                }

                                $goods = [] ;

                                foreach ( $st_goods_stock as $stock ){

                                    $goods[$stock['goods_id']][$stock['spec_id']] = $stock['enable_number'] - $stock['lock_number'] < 0 ? 0 :$stock['enable_number'] - $stock['lock_number'] ;

                                }

                                $args_data = [
                                    'mall_id' => $mall -> id ,
                                    'goods' => $goods
                                ];

                                $res = Wm::send( $app -> id .'.goods.batch_update_stock',$args_data);

                                if( $res['code'] != 200 ){
                                    throw new Exception( $res['message'] , 10001);
                                }
                            }
                        }
                    }

                    DB::commit();
                    return ['code' => 200, 'message' => 'ok'];

                } catch (\Exception $e) {

                    DB::rollBack();
                    return ['code' => $e ->getCode() ,'message' => $e -> getMessage()];
                }
            }
        }
    }

    //指定拉取库存

    public function pull_store( $mall_ids , $sku_ids )
    {

        set_time_limit(0);

        $url = $this->http_url; //门店库存接口地址

        foreach ($mall_ids as $key => $mall_id) {

            $st_mall = StMall::find($mall_id);

            $param = [
                'appId' => $this->appid,
                'version' => '1.0',
                'timestamp' => time(),
                'format' => 'json',
                'scope' => 'goods.goods.queryStock',
                'param' => json_encode([
                    'product_code_data' => [$sku_ids[$key]],
                    'mall_code' => $st_mall->code
                ])
            ];

            $param['sign'] = $this->createSign($param, $this->key);

            //接口请求

            $store_data = $this->requestApi($url, 'store', [], $param, 'post');

            if ($store_data['code'] == 200 && !empty($store_data['data']['data'])) {

                foreach ($store_data['data']['data'] as $item) {

                    $st_goods_stock = StGoodsStock::where([['mall_id', $mall_id], ['sku', $item['product_code']]])->first();

                    $enable_number = $item['qty'] - $st_goods_stock-> lock_number < 0 ? 0 : $item['qty'] - $st_goods_stock-> lock_number;
                    $status = ($item['qty'] - $st_goods_stock-> lock_number) < 0 ? 0 : 1;

                    StGoodsStock::where([['mall_id', $mall_id], ['sku', $item['product_code']]])
                                ->update(['enable_number' => $enable_number , 'status' => $status]);

                    //同步应用平台

                    $st_goods_app = StAppGoodsSale::select('app_id','goods_id','spec_id')->where([['mall_id',$mall_id],['sku',$sku_ids[$key]]])->get();

                    if( !$st_goods_app -> isEmpty()){

                        foreach ( $st_goods_app as $app ){

                            $args_data = [
                                'mall_id' => $mall_id ,
                                'goods' => [
                                    $app -> goods_id =>[
                                        $app -> spec_id => $enable_number
                                    ]
                                ]
                            ];

                            $res = Wm::send($app-> app_id .'.goods.batch_update_stock',$args_data);

                            if( $res['code'] != 200 ){
                                return ['code' => 10001 ,'message' => $res['message'] ];
                            }
                        }
                    }
                }
            }
        }

        return ['code' => 200, 'message' => 'ok'];
    }

    //店铺指定拉取库存

    public function pull_mall_store( $mall_id , $skus )
    {

        set_time_limit(0);

        $url = $this->http_url; //门店库存接口地址


        $st_mall = StMall::find($mall_id);

        $param = [
            'appId' => $this->appid,
            'version' => '1.0',
            'timestamp' => time(),
            'format' => 'json',
            'scope' => 'goods.goods.queryStock',
            'param' => json_encode([
                'product_code_data' => $skus,
                'mall_code' => $st_mall->code
            ])
        ];

        $param['sign'] = $this->createSign($param, $this->key);

        //接口请求

        $store_data = $this->requestApi($url, 'store', [], $param, 'post');

        if ($store_data['code'] == 200 && !empty($store_data['data']['data'])) {

            foreach ($store_data['data']['data'] as $item) {

                $st_goods_stock = StGoodsStock::where([['mall_id', $mall_id], ['sku', $item['product_code']]])->first();

                $enable_number = $item['qty'] - $st_goods_stock-> lock_number < 0 ? 0 : $item['qty'] - $st_goods_stock-> lock_number ;
                $status = ($item['qty'] - $st_goods_stock-> lock_number) < 0 ? 0 : 1;

                StGoodsStock::where([['mall_id', $mall_id], ['sku', $item['product_code']]])
                    ->update(['enable_number' => $enable_number , 'status' => $status]);

                //同步应用平台

                $st_goods_app = StAppGoodsSale::select('app_id','goods_id','spec_id')->where([['mall_id',$mall_id],['sku',$item['product_code']]])->get();

                if( !$st_goods_app -> isEmpty()){

                    foreach ( $st_goods_app as $app ){

                        $args_data = [
                            'mall_id' => $mall_id ,
                            'goods' => [
                                $app -> goods_id =>[
                                    $app -> spec_id => $enable_number
                                ]
                            ]
                        ];

                        $res = Wm::send($app-> app_id .'.goods.batch_update_stock',$args_data);

                        if( $res['code'] != 200 ){
                            return ['code' => 10001 ,'message' => $res['message'] ];
                        }
                    }
                }
            }
        }

        return ['code' => 200, 'message' => 'ok'];
    }

    //指定拉取价格

    public function pull_mall_price( $mall_id , $skus )
    {

        set_time_limit(0);

        $url = $this->http_url; //门店库存接口地址

        $st_mall = StMall::find($mall_id);

        $param = [
            'appId' => $this->appid,
            'version' => '1.0',
            'timestamp' => time(),
            'format' => 'json',
            'scope' => 'goods.goods.queryPrice',
            'param' => json_encode([
                'product_code_data' => $skus,
                'mall_code' => $st_mall->code
            ])
        ];

        $param['sign'] = $this->createSign($param, $this->key);

        //接口请求

        $price_data = $this->requestApi($url, 'store', [], $param, 'post');

        if ($price_data['code'] == 200 && !empty($price_data['data']['data'])) {

            foreach ($price_data['data']['data'] as $item) {

                StAppGoodsSale::where([['mall_id', $mall_id], ['sku', $item['product_code']]])
                    ->update(['erp_price' => $item['price'],'price' => $item['price']]);

                //同步应用平台

                $st_goods_app = StAppGoodsSale::select('app_id','goods_id','spec_id')->where([['mall_id',$mall_id],['sku',$item['product_code']]])->get();

                if( !$st_goods_app -> isEmpty()){

                    foreach ( $st_goods_app as $app ){

                        $args_data = [
                            'mall_id' => $mall_id ,
                            'goods' => [
                                $app -> goods_id =>[
                                    $app -> spec_id => $item['price']
                                ]
                            ]
                        ];

                        $res = Wm::send($app-> app_id .'.goods.batch_update_price',$args_data);

                        if( $res['code'] != 200 ){
                            return ['code' => 10001 ,'message' => $res['message'] ];
                        }
                    }
                }
            }
        }

        return ['code' => 200, 'message' => 'ok'];
    }

    //拉取价格
    public function price()
    {

        set_time_limit(0);

        $url = $this->http_url; //门店价格接口地址

        //查询门店信息
        $st_mall = StMall::where(['status' => 1])->get();

        if ($st_mall->isEmpty()) {
            return ['code' => 100000, 'message' => '门店信息不存在'];
        }

        $page = 1;
        $rp = 100;

        while ($page > 0) {

            //分页查询商品信息
            $goods_data = StGoodsSale::where(['status' => 1])
                            ->offset(($page - 1) * $rp)
                            ->limit($rp)
                            ->get()
                            ->toArray();

            if (!isset($goods_data[$rp - 1])) {
                $page = 0;
            } else {
                $page++;
            }

            if (!$goods_data) {
                break;
            }

            //三级商品编码数组
            $parameters = [];

            foreach ($goods_data as &$g) {

                $parameters[] = $g['sku'];

            }

            foreach ($st_mall as $mall) {

                $param = [
                    'appId' => $this->appid,
                    'version' => '1.0',
                    'timestamp' => time(),
                    'format' => 'json',
                    'scope' => 'goods.goods.queryPrice',
                    'param' => json_encode([
                        'product_code_data' => $parameters,
                        'mall_code' => $mall->code
                    ])
                ];

                $param['sign'] = $this->createSign($param, $this->key);

                //接口请求

                $price_data = $this->requestApi($url, 'price', [], $param, 'post');

                if ($price_data['code'] != 200 || empty($price_data['data']['data'])) {
                    continue;
                }

                try {

                    DB::beginTransaction();

                    foreach ($price_data['data']['data'] as $item) {

                        StAppGoodsSale::where('sku',$item['product_code'])->update(['erp_price' => $item['price'],'price' => $item['price']]);

                    }

                    //应用平台同步
                    $st_app = StApp::where('enable', 1) -> get();

                    if( !$st_app -> isEmpty()){

                        foreach ( $st_app as $app){

                            $p = 1 ;
                            $page_size = 50 ;

                            while( $p > 0 ){

                                $st_app_goods_sale = StAppGoodsSale::select('spec_id','goods_id','erp_price','price')
                                    ->where('mall_id',$mall->id)
                                    ->where('app_id',$app -> id)
                                    ->offset(($p - 1) * $page_size)
                                    ->limit($page_size)
                                    ->get()
                                    ->toArray();

                                if (!$st_app_goods_sale) {
                                    break;
                                }

                                if (!isset($st_app_goods_sale[$page_size - 1])) {
                                    $p = 0;
                                } else {
                                    $p++;
                                }

                                $goods = [] ;

                                foreach ( $st_app_goods_sale as $sale ){

                                    $goods[$sale['goods_id']][$sale['spec_id']] = $sale['erp_price'];

                                }

                                $args_data = [
                                    'mall_id' => $mall -> id ,
                                    'goods' => $goods
                                ];

                                $res = Wm::send( $app -> id . '.goods.batch_update_price',$args_data);

                                if( $res['code'] != 200 ){
                                    throw new Exception ( $res['message'] , 10001);
                                }
                            }
                        }
                    }

                    DB::commit();
                    return ['code' => 200, 'message' => 'ok'];

                } catch (\Exception $e) {
                    DB::rollBack();
                    return ['code' => $e -> getCode() , 'message' => $e -> getMessage()];
                }
            }
        }
    }

    private function createSign( $data, $key ) {

        ksort($data);

        $sign_str = '';
        foreach ($data as $k => $v) {
            if ($v == '') {
                continue;
            }
            if ($k == 'sign') {
                continue;
            }

            if ($sign_str == '') {
                $sign_str .= $k . '=' . $v;
            } else {
                $sign_str .= '&' . $k . '=' . $v;
            }

        }
        $sign_str .= 'key=' . $key;

        return strtoupper(md5($sign_str));

    }
}

