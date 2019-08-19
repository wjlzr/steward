<?php
namespace App\Services\Wm\MtFood\Request;

use App\Services\Wm\ShopFactoryInterface;

class MtFoodShopRequest implements ShopFactoryInterface
{

    /**
     * MtShopRequest constructor.
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
        return $this->curl->call([], 'poi/getids');
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

        $mall_code = implode(',',$args);

        $request = [
            'app_poi_codes' => $mall_code
        ];

        return $this->curl->call($request, 'poi/mget');

    }

    //创建门店
    public function createShop() {

        $args_data = [
            'app_poi_code' => '2222',
            'name' => '宝虹中心饭店',
            'address' => '中春路7705号宝虹中心501',
            'latitude' => '31.152363',
            'longitude' => '121.347512',
            'phone' => '010-10101010',
            'shipping_fee' => 0,
            'shipping_time' => '7:00-12:00,13:30-23:00',
            'open_level' => 1,
            'is_online' => 1,
            'third_tag_name' => '肉夹馍'
        ];

        $result = $this->curl->call($args_data, 'poi/save','post');

        error_log('========//创建门店');
        error_log(var_export($result,true));

        return $result;
    }

    public function third() {

        $result = $this->curl->call([], 'poiTag/list','post');

        error_log('========//创建门店');
        error_log(var_export($result,true));
    }

    /**
     * 修改店铺相关信息
     * @param $args_data
     * @return mixed
     */
    public function edit_shop($args_data)
    {

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

        $request = [
            'app_poi_code' => $args_data['mall_code'],
            'name' => $args_data['name'],
            'address' => $args_data['address'],
            'latitude' => $args_data['latitude'],
            'longitude' => $args_data['longitude'],
            'phone' => $args_data['phone'],
            'shipping_fee' => $args_data['shipping_fee'],
            'shipping_time' => $args_data['shipping_fee'],
            'open_level' => 1,
            'is_online' => 1,
            'third_tag_name' => $args_data['third_tag_name']
        ];

        return $this->curl->call($request, 'poi/save','post');

    }
}