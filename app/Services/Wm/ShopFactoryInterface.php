<?php
namespace App\Services\Wm;

interface ShopFactoryInterface
{

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
    public function get_shop_list($args);

    /**
     * 获取店铺相关信息
     * @param $args = [
     *      'mall_code' => string 商户门店编号
     * ]
     * @return mixed
     */
    public function get_shop($args);


    /**
     * 修改店铺相关信息
     * @param $args
     * @return mixed
     */
    public function edit_shop($args);


}