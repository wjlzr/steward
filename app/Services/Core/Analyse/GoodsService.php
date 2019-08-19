<?php

namespace App\Services\Core\Analyse;

use App\Models\Goods\StStatGoodsAnalyse;
use App\Models\Goods\StStatGoodsCategoryAnalyse;
use App\Models\StApp;
use DB;

class GoodsService
{


    /**
     * ��Ʒ����
     * @param $args = [
     *      'mall_id' => int �ŵ�ID
     *      'app_id' => int Ӧ��ID
     *      'goods_name' => string ��Ʒ����(ģ��ƥ��)
     *      'start_date' => string ��ʼ����
     *      'end_date' => string ��������
     *      'page' => int ��ǰҳ�롾ѡ�(Ĭ��Ϊ1)
     *      'page_size' => int ÿҳ������ѡ�(Ĭ��Ϊ10)
     * ]
     * @return array = [
     *      'total' => int ������
     *      'list' => [
     *          [
     *              'sku' => string �̼ұ���
     *              'upc' => string ������
     *              'goods_name' => string ��Ʒ����
     *              'sales_number' => int ����
     *              'sales_fee' => string ���۶�
     *              'unit_price' => string ������
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
     * ��Ʒ������
     * @param $args = [
     *      'mall_id' => int �ŵ�ID
     *      'app_id' => int Ӧ��ID
     *      'one_category_id' => int һ������ID(Ĭ��Ϊ0)�����롿
     *      'two_category_id' => int ��������ID(Ĭ��Ϊ0)��ѡ�
     *      'three_category_id' => int ��������ID(Ĭ��Ϊ0)��ѡ�
     *      'start_date' => string ��ʼ����
     *      'end_date' => string ��������
     *      'page' => int ��ǰҳ�롾ѡ�(Ĭ��Ϊ1)
     *      'page_size' => int ÿҳ������ѡ�(Ĭ��Ϊ10)
     * ]
     * @return array = [
     *      'total' => int ������
     *      'list' => [
     *          [
     *              'one_category_name' => string һ���������� �����롿
     *              'two_category_name' => string  ������������ �����롿(û�з��ؿ��ַ���)
     *              'three_category_name' => string ������������ �����롿(û�з��ؿ��ַ���)
     *              'sales_number' => int ���������롿
     *              'sales_fee' => string ���۶���롿
     *              'app' => [
     *                  [
     *                      'app_name' => string Ӧ������
     *                      'sales_number' => int Ӧ������
     *                      'sales_fee' => string Ӧ�����۶�
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