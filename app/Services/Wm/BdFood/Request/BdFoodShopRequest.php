<?php
namespace App\Services\Wm\BdFood\Request;

use App\Services\Wm\ShopFactoryInterface;

class BdFoodShopRequest implements ShopFactoryInterface
{

    /**
     * BdShopRequest constructor.
     * @param $curl
     */
    public function __construct($curl)
    {
        $this->curl = $curl;
    }

    /**
     * 获取已授权店铺列表
     * @remark：传参数组固定传入，
     * 各平台可以根据实际情况决定是否使用
     * @param $args = [
     *      'page' => int 当前页码
     *      'page_size' => 每页条数 (默认20)
     * ]
     * @return mixed
     */
    public function get_shop_list($args)
    {
        return $this->curl->call([],'shop.list','post');
    }

    /**
     * 获取店铺相关信息
     * @param $args = [
     *      'mall_code' => string 商户门店编号
     * ]
     * @return mixed
     */
    public function get_shop($args)
    {

        if (!isset($args['mall_code']) || empty($args['mall_code'])) {
            return ['code' => 400, 'message' => '参数错误：mall_code'];
        }

        $request = [
            'shop_id' => $args['mall_code']
        ];

        return $this->curl->call($request, 'shop.get');

    }


    /**
     * 修改店铺相关信息
     * @param $args_data
     * @return mixed
     */
    public function edit_shop($args_data)
    {
        $supplier = $this->supplier_list();
        if ($supplier['code'] != 200) {
            return ['code' => $supplier['code'], 'message' => $supplier['message']];
        }

        //重组分类数组
        foreach ($supplier['data']['categorys'] as $k=>$v) {

        }

        if (!isset($args_data['mall_code']) || empty($args_data['mall_code'])) {
            return ['code' => 400, 'message' => '参数错误：mall_code'];
        }

        if (!isset($args_data['name']) || empty($args_data['name'])) {
            return ['code' => 400, 'message' => '参数错误：name'];
        }

        if (!isset($args_data['address']) || empty($args_data['address'])) {
            return ['code' => 400, 'message' => '参数错误：address'];
        }

        if (!isset($args_data['latitude']) || empty($args_data['latitude'])) {
            return ['code' => 400, 'message' => '参数错误：latitude'];
        }

        if (!isset($args_data['longitude']) || empty($args_data['longitude'])) {
            return ['code' => 400, 'message' => '参数错误：longitude'];
        }

        if (!isset($args_data['phone']) || empty($args_data['phone'])) {
            return ['code' => 400, 'message' => '参数错误：phone'];
        }

        if (!isset($args_data['shipping_fee']) || empty($args_data['shipping_fee'])) {
            return ['code' => 400, 'message' => '参数错误：shipping_fee'];
        }

        if (!isset($args_data['shipping_time']) || empty($args_data['shipping_time'])) {
            return ['code' => 400, 'message' => '参数错误：shipping_time'];
        }

        if (!isset($args_data['third_tag_name']) || !ebsig_is_int($args_data['third_tag_name'])) {
            return ['code' => 400, 'message' => '参数错误：third_tag_name'];
        }

        if (!isset($args_data['province']) || !ebsig_is_int($args_data['province'])) {
            return ['code' => 400, 'message' => '参数错误：province'];
        }

        if (!isset($args_data['city']) || !ebsig_is_int($args_data['city'])) {
            return ['code' => 400, 'message' => '参数错误：city'];
        }

        if (!isset($args_data['county']) || !ebsig_is_int($args_data['county'])) {
            return ['code' => 400, 'message' => '参数错误：province'];
        }

        if (!isset($args_data['service_phone']) || !ebsig_is_int($args_data['service_phone'])) {
            return ['code' => 400, 'message' => '参数错误：service_phone'];
        }

        //组装营业时间
        $business_time = [];
        $shipping_time = explode(',',$args_data['shipping_time']);
        foreach ($shipping_time as $item) {
            $shipping_time_arr[] = explode('-',$item);
        }
        $key = ['start','end'];
        foreach($shipping_time_arr as $k=>$v) {
            $business_time[$k] = array_combine($key,$v);
        }

        $request_data = array (
            'supplier_id' => $supplier['data']['supplier_id'],
            'shop_id' => $args_data['mall_code'],
            'name' => $args_data['name'],
            //'shop_logo' => 'http://api.waimai.baidu.com/WebImage/SupplierImage/2521/s09100000000.jpg',
            'province' => $args_data['province'],
            'city' => $args_data['city'],
            'county' => $args_data['county'],
            'address' => $args_data['address'],
            'brand' => $supplier['data']['brand'],
            'categorys' =>
                array (
                    0 =>
                        array (
                            'category1' => '1',
                            'category2' => '2',
                            'category3' => '3',
                        ),
                ),
            'phone' => $args_data['phone'],
            'service_phone' => $args_data['service_phone'],
            'longitude' => $args_data['longitude'],
            'latitude' => $args_data['latitude'],
            'coord_type' => 'bdll',
            'delivery_region' =>
                array (
                    0 =>
                        array (
                            'name' => '上海闵行',
                            'region' =>
                                array (
                                    0 =>
                                        array (
                                            0 =>
                                                array (
                                                    'latitude' => '121.347235',
                                                    'longitude' => '31.158366',
                                                ),
                                            1 =>
                                                array (
                                                    'latitude' => '121.283963',
                                                    'longitude' => '31.115558',
                                                ),
                                            2 =>
                                                array (
                                                    'latitude' => '121.364027',
                                                    'longitude' => '31.154318',
                                                ),
                                            3 =>
                                                array (
                                                    'latitude' => '121.363784',
                                                    'longitude' => '31.146595',
                                                ),
                                        ),
                                ),
                            'delivery_time' => '60',
                            'delivery_fee' => '0',
                            'min_buy_free' => '0',
                            'min_order_price' => '0'
                        ),
                ),
            'business_time' => $business_time,
            'book_ahead_time' => '',
            'invoice_support' => '2',
            'package_box_price' => '0.0000',
            'shop_code' => '2022',
        );

        return $this->curl->call($request_data, 'supplier.list');
    }

    /**
     * 查看供应商
     * @return mixed
     */
    public function supplier_list ()
    {
        return $this->curl->call([], 'supplier.list');
    }
}