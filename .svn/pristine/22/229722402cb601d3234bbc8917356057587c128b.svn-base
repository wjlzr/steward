<?php

namespace App\Services\Core\Analyse;

use App\Models\Goods\StStatGoodsAnalyse;
use App\Models\Goods\StStatGoodsCategoryAnalyse;
use App\Models\StApp;
use DB;

class GoodsService
{


    /**
     * 商品分析
     * @param $args = [
     *      'mall_id' => int 门店ID
     *      'app_id' => int 应用ID
     *      'goods_name' => string 商品名称(模糊匹配)
     *      'start_date' => string 开始日期
     *      'end_date' => string 结束日期
     *      'page' => int 当前页码【选填】(默认为1)
     *      'page_size' => int 每页数量【选填】(默认为10)
     * ]
     * @return array = [
     *      'total' => int 总条数
     *      'list' => [
     *          [
     *              'sku' => string 商家编码
     *              'upc' => string 条形码
     *              'goods_name' => string 商品名称
     *              'sales_number' => int 销量
     *              'sales_fee' => string 销售额
     *              'unit_price' => string 均单价
     *          ]
     *      ]
     * ]
     */
    public function goods($args)
    {

        $page_size = !empty($args['page_size'])
            ? $args['page_size']
            : 10;

        $where = [];

        if (isset($args['app_id']) && !empty($args['app_id'])) {
            $where[] = ['app_id', $args['app_id']];
        }

        if (isset($args['goods_name']) && !empty($args['goods_name'])) {
            $where[] = ['goods_name', 'LIKE', '%' . $args['goods_name'] . '%'];
        }

        if (isset($args['start_date']) && !empty($args['start_date'])) {
            $where[] = ['cal_date', '>=', $args['start_date']];
        }

        if (isset($args['end_date']) && !empty($args['end_date'])) {
            $where[] = ['cal_date', '<=', $args['end_date']];
        }

        if (isset($args['mall_id']) && !empty($args['mall_id'])) {
            $where[] = ['mall_id', '=', $args['mall_id']];
        }

        $sort_name = 'total_bill_money';
        if ($args['sort_name'] == 'sales_number') {
            $sort_name = 'total_num';
        } elseif ($args['sort_name'] == ' sales_fee') {
            $sort_name = 'total_bill_money';
        }

        $goods_data = DB::table('st_stat_goods_analyse')
            ->select(DB::raw('goods_name,sku,upc,SUM(total_num) AS total_num,SUM(total_bill_money)
             AS total_bill_money,(SUM(total_num)/SUM(total_bill_money)) AS unit_price'))
            ->where($where)
            ->orderBy($sort_name, $args['sort_order'])
            ->groupBy('goods_id')
            ->paginate($page_size)
            ->toArray();

        $return_result = [
            'total' => $goods_data['total'],
            'list' => []
        ];

        if ($goods_data['data']) {

            foreach ($goods_data['data'] as $row) {

                $return_result['list'][] = [
                    'sku' => app_to_string($row->sku),
                    'upc' => app_to_string($row->upc),
                    'goods_name' => app_to_string($row->goods_name),
                    'sales_number' => app_to_int($row->total_num),
                    'sales_fee' => app_to_string($row->total_bill_money),
                    'unit_price' => app_to_string(round($row->unit_price, 2))
                ];

            }
        }

        return $return_result;

    }


    /**
     * 商品类别分析
     * @param $args = [
     *      'mall_id' => int 门店ID
     *      'app_id' => int 应用ID
     *      'one_category_id' => int 一级分类ID(默认为0)【必须】
     *      'two_category_id' => int 二级分类ID(默认为0)【选填】
     *      'three_category_id' => int 三级分类ID(默认为0)【选填】
     *      'start_date' => string 开始日期
     *      'end_date' => string 结束日期
     *      'page' => int 当前页码【选填】(默认为1)
     *      'page_size' => int 每页数量【选填】(默认为10)
     * ]
     * @return array = [
     *      'total' => int 总条数
     *      'list' => [
     *          [
     *              'one_category_name' => string 一级分类名称 【必须】
     *              'two_category_name' => string  二级分类名称 【必须】(没有返回空字符串)
     *              'three_category_name' => string 三级分类名称 【必须】(没有返回空字符串)
     *              'sales_number' => int 销量【必须】
     *              'sales_fee' => string 销售额【必须】
     *              'app' => [
     *                  [
     *                      'app_name' => string 应用名称
     *                      'sales_number' => int 应用销量
     *                      'sales_fee' => string 应用销售额
     *                  ]
     *              ]
     *          ]
     *      ]
     * ]
     */
    public function category($args)
    {

        $page_size = isset($args['page_size'])
            ? $args['page_size']
            : 10;

        $where = [];

        if (isset($args['app_id']) && !empty($args['app_id'])) {
            $where[] = ['app_id', $args['app_id']];
        }

        if (isset($args['mall_id']) && !empty($args['mall_id'])) {
            $where[] = ['mall_id', $args['mall_id']];
        }

        if (isset($args['one_category_id']) && !empty($args['one_category_id'])) {
            $where[] = ['first_level_id', $args['one_category_id']];
        }

        if (isset($args['two_category_id']) && !empty($args['two_category_id'])) {
            $where[] = ['second_level_id', $args['two_category_id']];
        }

        if (isset($args['three_category_id']) && !empty($args['three_category_id'])) {
            $where[] = ['third_level_id', $args['three_category_id']];
        }

        if (isset($args['start_date']) && !empty($args['start_date'])) {
            $where[] = ['cal_date', '>=', $args['start_date']];
        }

        if (isset($args['end_date']) && !empty($args['end_date'])) {
            $where[] = ['cal_date', '<=', $args['end_date']];
        }

        $sort_name = 'total_bill_money';
        if ($args['sort_name'] == 'sales_number') {
            $sort_name = 'total_num';
        } elseif ($args['sort_name'] == ' sales_fee') {
            $sort_name = 'total_bill_money';
        }

        $category_data = DB::table('st_stat_goods_category_analyse')
            ->select(DB::raw('first_level_id,second_level_id,third_level_id,first_level_name,second_level_name,
            third_level_name,SUM(total_num) as total_num,SUM(total_bill_money) as total_bill_money'))
            ->where($where)
            ->groupBy('first_level_id', 'second_level_id', 'third_level_id')
            ->orderBy($sort_name, $args['sort_order'])
            ->paginate($page_size)
            ->toArray();

        $return_result = [
            'total' => $category_data['total'],
            'list' => []
        ];

        if ($category_data['data']) {

            foreach ($category_data['data'] as $k => $item) {

                $res ['one_category_name'] = !empty($item->first_level_name)
                    ? app_to_string($item->first_level_name)
                    : '';

                $res ['two_category_name'] = !empty($item->second_level_name)
                    ? app_to_string($item->second_level_name)
                    : '';

                $res ['three_category_name'] = !empty($item->third_level_name)
                    ? app_to_string($item->third_level_name)
                    : '';

                $res['sales_number'] = app_to_int($item->total_num);
                $res['sales_fee'] = app_to_string($item->total_bill_money);

                $other_where = [];

                if (!empty($item->first_level_id)) {
                    $other_where[] = ['first_level_id', '=', $item->first_level_id];
                }
                if (!empty($item->second_level_id)) {
                    $other_where[] = ['second_level_id', '=', $item->second_level_id];
                }
                if (!empty($item->third_level_id)) {
                    $other_where[] = ['third_level_id', '=', $item->third_level_id];
                }

                if (isset($args['start_date']) && !empty($args['start_date'])) {
                    $where[] = ['cal_date', '>=', $args['start_date']];
                }

                if (isset($args['end_date']) && !empty($args['end_date'])) {
                    $where[] = ['cal_date', '<=', $args['end_date']];
                }

                $app_data = StStatGoodsCategoryAnalyse::select(DB::raw('SUM(total_bill_money) as total_bill_money,
                SUM(total_num) as total_num,app_id'))->where($other_where)->groupBy('app_id')->get()->toArray();

                $st_app = StApp::select('id', 'name')->get()->toArray();

                $res['app'] = [];

                foreach ($app_data as $data) {

                    foreach ($st_app as $key => $app) {

                        if ($app['id'] == $data['app_id']) {

                            $app_name = $app['name'];
                            $total_num = $data['total_num'];
                            $total_bill = $data['total_bill_money'];

                            $res['app'][] = [

                                'app_name' => isset($app_name) ? app_to_string($app_name) : '',
                                'sales_number' => isset($total_num) ? app_to_int($total_num) : 0,
                                'sales_fee' => isset($total_bill) ? app_to_decimal($total_bill) : '',

                            ];
                        }



                    }
                }
                $return_result['list'][] = $res;

            }

        }

        return $return_result;

    }


}