<?php

namespace App\Services\Core\Analyse;

use App\Models\Goods\StStatMallAnalyse;
use DB;

class BusinessService
{

    /**
     * 营业分析
     * @param $args = [
     *      'mall_id' => int 门店ID
     *      'app_id' => int 应用ID
     *      'start_date' => string 开始日期
     *      'end_date' => string 结束日期
     * ]
     * @return array = [
     *      'turnover_board' => [
     *          'turnover' => [
     *              'turnover' => string 营业额
     *              'front_turnover' => string 前几日营业额：昨日/前日/前7日/前30日
     *              'goods_sell' => string 商品销售
     *              'pack_income' => string 包装收入
     *              'send_income' => string 配送收入
     *          ],
     *          'pay' => [
     *              'pay' => string 总支出金额
     *              'front_pay' => string 前几日总支出
     *              'act_subsidy' => string 活动补贴
     *              'service_cost' => string 服务费,即平台收取费用
     *          ],
     *          'income' => [
     *             'income' => string 净收入
     *             'front_income' => string 前几日净收入
     *          ]
     *      ]，
     *      'orders_data' => [
     *          'valid' => [
     *              'valid' => int 有效订单数
     *              'front_valid' => int 前几日有效订单数
     *              'avg_price' => string 平均客单价
     *          ],
     *          'invalid' => [
     *              'invalid' => int 无效订单数
     *              'front_invalid' => int 前几日无效订单数
     *              'loss' => string 预计损失金额
     *          ]
     *      ]
     * ]
     */
    public function business($args)
    {

        $where = [];

        if (isset($args['app_id']) && !empty($args['app_id'])) {
            $where[] = ['app_id', $args['app_id']];
        }

        if (isset($args['mall_id']) && !empty($args['mall_id'])) {
            $where[] = ['mall_id', $args['mall_id']];
        }

        if (isset($args['start_date']) && !empty($args['start_date'])) {
            $where[] = ['cal_date', '>=', $args['start_date']];
        }

        if (isset($args['end_date']) && !empty($args['end_date'])) {
            $where[] = ['cal_date', '<=', $args['end_date']];
        }

        $mall_data = StStatMallAnalyse::select(

            DB::raw('SUM(total_bill_num) as total_bill_num,
                     SUM(total_sale_bill_num) as total_sale_bill_num,
                     SUM(total_cancel_bill_num) as total_cancel_bill_num,
                     SUM(total_user_fee) as total_user_fee,
                     SUM(total_mall_fee) as total_mall_fee,
                     SUM(total_goods_money) as total_goods_money,
                     SUM(package_income) as package_income,
                     SUM(delivery_income) as delivery_income,
                     SUM(activity_expense) as activity_expense,
                     SUM(service_expense) as service_expense,
                     SUM(app_expense) as app_expense,
                     SUM(total_cancel_mall_fee) as total_cancel_mall_fee'))

            ->where($where)
            ->get()
            ->toArray();

        $days = (strtotime($args['end_date'])-strtotime($args['start_date']))/86400+1;
        $next_start_date = date('Y-m-d',strtotime('-'. $days .' day',strtotime($args['start_date'])));
        $next_end_date = date('Y-m-d',strtotime('-'. $days .' day',strtotime($args['end_date'])));

        $return_result = [];

        if ($mall_data) {

            $mall_data = $mall_data[0];

            $other_where = [];

            if (!empty($args['app_id'])) {
                $other_where[] = ['app_id', '=', $args['app_id']];
            }
            if (!empty($args['mall_id'])) {
                $where[] = ['mall_id', '=', $args['mall_id']];
            }
            if (!empty($args['start_date'])) {
                $other_where[] = ['cal_date', '>=', $next_start_date];
            }
            if (!empty($args['end_date'])) {
                $other_where[] = ['cal_date', '<=', $next_end_date];
            }

            $next_data = StStatMallAnalyse::select(

                DB::raw('SUM(total_bill_num) as total_bill_num,
                        SUM(total_sale_bill_num) as total_sale_bill_num,
                        SUM(total_cancel_bill_num) as total_cancel_bill_num,
                        SUM(total_user_fee) as total_user_fee,
                        SUM(total_mall_fee) as total_mall_fee,
                        SUM(total_goods_money) as total_goods_money,
                        SUM(package_income) as package_income,
                        SUM(delivery_income) as delivery_income,
                        SUM(activity_expense) as activity_expense,
                        SUM(service_expense) as service_expense,
                        SUM(app_expense) as app_expense,
                        SUM(total_cancel_mall_fee) as total_cancel_mall_fee'))

                ->where($other_where)
                ->get()
                ->toArray();

            $next_data = $next_data[0];

            if ( !$next_data ) {
                $next_data['activity_expense'] = 0;
                $next_data['service_expense'] = 0;
                $next_data['app_expense'] = 0;
                $next_data['total_user_fee'] = 0;
                $next_data['total_mall_fee'] = 0;
            }

            $expense = number_format($mall_data['activity_expense'] + $mall_data['service_expense'] + $mall_data['app_expense'], 2);
            $next_expense = number_format($next_data['activity_expense'] + $next_data['service_expense'] + $next_data['app_expense'], 2);

            $return_result = [
                'turnover_board' => [
                    'turnover' => [
                        'turnover' => isset( $mall_data['total_user_fee'] ) ?app_to_string($mall_data['total_user_fee']) : '0.00',
                        'front_turnover' => isset( $next_data['total_user_fee'] ) ?app_to_string($next_data['total_user_fee']) :  '0.00',
                        'goods_sell' => isset( $mall_data['total_goods_money'] ) ? app_to_string($mall_data['total_goods_money']) :  '0.00',
                        'pack_income' => isset( $mall_data['package_income'] ) ? app_to_string($mall_data['package_income']) :  '0.00',
                        'send_income' => isset( $mall_data['delivery_income'] ) ? app_to_string($mall_data['delivery_income']) :  '0.00',
                    ],
                    'pay' => [
                        'pay' => !empty( $expense ) ? app_to_string($expense) :  '0.00',
                        'front_pay' => !empty( $next_expense ) ? app_to_string($next_expense) :  '0.00',
                        'act_subsidy' => isset( $mall_data['activity_expense'] ) ? app_to_string($mall_data['activity_expense']) :  '0.00',
                        'service_cost' => isset( $mall_data['app_expense'] ) ? app_to_string($mall_data['app_expense']) :  '0.00',
                    ],
                    'income' => [
                        'income' => isset( $mall_data['total_mall_fee'] ) ? app_to_string($mall_data['total_mall_fee']) :  '0.00',
                        'front_income' => isset( $next_data['total_mall_fee'] ) ? app_to_string($next_data['total_mall_fee']) :  '0.00',
                    ]
                ],


                'orders_data' => [
                    'valid' => [
                        'valid' => isset( $mall_data['total_sale_bill_num'] ) ? app_to_int($mall_data['total_sale_bill_num']) :  '0.00',
                        'front_valid' => isset( $next_data['total_sale_bill_num'] ) ? app_to_int($next_data['total_sale_bill_num']) :  0,
                        'avg_price' => $mall_data['total_sale_bill_num']>0 ? app_to_string(number_format( $mall_data['total_user_fee']/$mall_data['total_sale_bill_num'] ,2)) : '0.00',
                    ],
                    'invalid' => [
                        'invalid' => isset( $mall_data['total_cancel_bill_num'] ) ? app_to_int($mall_data['total_cancel_bill_num']) :  '0.00',
                        'front_invalid' => isset( $next_data['total_cancel_bill_num'] ) ? app_to_int($next_data['total_cancel_bill_num']) :  0,
                        'loss' => isset( $mall_data['total_cancel_mall_fee'] ) ? app_to_string(number_format( $mall_data['total_cancel_mall_fee'],2)) :  '0.00'
                    ]
                ]
            ];

        }

        return $return_result;

    }


}