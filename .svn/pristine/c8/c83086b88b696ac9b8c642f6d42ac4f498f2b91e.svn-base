<?php

namespace App\Http\Controllers\Admin\Analyse;

use DB;

use Log;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\StApp;

use App\Models\Goods\StStatGoodsCategoryAnalyse;

use App\Http\Controllers\Common\EbsigMsgPush;

use Illuminate\Support\Facades\Redis as Redis;

use App\Http\Controllers\Controller;

class categoryController extends Controller
{


    /**
     *  商品类别分析列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categoryList( Request $request )
    {

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $first_level_id = $request->input('first_level_id');
        $second_level_id = $request->input('second_level_id');
        $third_level_id = $request->input('third_level_id');

        $where = [];

        if ( !empty( $first_level_id ) ) {
            $where[] = ['first_level_id','=',$first_level_id];
        }
        if ( !empty( $second_level_id ) ) {
            $where[] = ['second_level_id','=',$second_level_id];
        }
        if ( !empty( $third_level_id ) ) {
            $where[] = ['third_level_id','=',$third_level_id];
        }
        if ( !empty( $startDate ) ) {
            $where[] = ['cal_date','>=',$startDate];
        }
        if ( !empty( $endDate ) ) {
            $where[] = ['cal_date','<=',$endDate];
        }

        $category = DB::table('st_stat_goods_category_analyse')
            ->select(DB::raw('first_level_id,second_level_id,third_level_id,first_level_name,second_level_name,third_level_name,SUM(total_bill_money) as total_bill_money'))
            ->where($where)
            ->groupBy('first_level_id','second_level_id','third_level_id')
            ->orderBy($request->input('sort'), $request->input('order'))
            ->paginate($request->input('limit'))
            ->toArray();

        //返回数组
        $return = [
            'count' => $category['total'],
            'code' => 0,
            'data' => []
        ];

        if ( $category['data'] ) {

            foreach ( $category['data'] as $k=>$item ) {

                $other_where = [];

                if ( !empty( $item->first_level_id ) ) {
                    $other_where[] = ['first_level_id','=',$item->first_level_id];
                }
                if ( !empty( $item->second_level_id ) ) {
                    $other_where[] = ['second_level_id','=',$item->second_level_id];
                }
                if ( !empty( $item->third_level_id ) ) {
                    $other_where[] = ['third_level_id','=',$item->third_level_id];
                }
                if ( !empty( $startDate ) ) {
                    $other_where[] = ['cal_date','>=',$startDate];
                }
                if ( !empty( $endDate ) ) {
                    $other_where[] = ['cal_date','<=',$endDate];
                }

                $category_id = StStatGoodsCategoryAnalyse::select(DB::raw('SUM(total_bill_money) as total_bill_money,app_id'))->where($other_where) ->groupBy('app_id')->get()->toArray();

                $redis_data = Redis::get('GLOBAL_APP_*');
                $redis_array = [];
                if ( !isset( $redis_data ) ) {

                    $redis_data = StApp::all();
                    $redis_data = $redis_data->toArray();
                }

                foreach ( $redis_data as $redis ){
                    $redis_array[$redis['id']] = $redis['id'];
                }

                $bill_money = [];

                if ( $category_id ) {

                     foreach ( $category_id as $v ) {

                        if ( isset($redis_array[$v['app_id']]) ) {

                            $bill_money[$v['app_id']]['bill_money'] = $v['total_bill_money'];
                            $bill_money[$v['app_id']]['rate'] = $v['total_bill_money']/$item->total_bill_money;
                        }
                     }
                }

                $level = '';
                if ( !empty( $item->first_level_name ) ) {
                    $level.= $item->first_level_name;
                }
                if ( !empty( $item->second_level_name ) ) {
                    $level.= '->'.$item->second_level_name;
                }
                if ( !empty( $item->third_level_name ) ) {
                    $level.= '->'.$item->third_level_name;
                }

                $money = [];
                foreach ( $bill_money as $kk=>$vv ) {

                    $rate = round( $vv['rate']*100 ,2).'%';

                    $money[$kk] = '<span class="fl">'.$vv['bill_money'].'&nbsp;</span><div class="progress" style="width: 60px;"><div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '.$rate.';color: #F90C0C;background:#01a2fd;">'.$rate.'</div></div>';
                }

                $return['data'][] = [
                    'level' => $level,
                    'total_bill_money' => number_format($item->total_bill_money,2,'.',''),
                    'BdFood' => isset( $money[1] ) ? $money[1] : 0,
                    'EleMe' => isset( $money[2] ) ? $money[2] : 0,
                    'MtFood' => isset( $money[3] ) ? $money[3] : 0,
                    'JdDj' => isset( $money[4] ) ? $money[4] : 0
                ];

            }
        }

        return $return;

    }


    /**
     *  商管家商品类别分析导出
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export( Request $request )
    {

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $first_level_id = $request->input('first_level_id');
        $second_level_id = $request->input('second_level_id');
        $third_level_id = $request->input('third_level_id');
        $exportIndex = $request->input('exportIndex','');

        if ( empty( $exportIndex ) ) {
            return response()->json(['code'=>10000,'message'=>'缺少导出索引']);
        }

        $EbsigMsgPush = new EbsigMsgPush();

        //执行任务
        $push_array = [
            'call_url' => $request->getSchemeAndHttpHost() . '/app/Http/Controllers/Async/Stat_goods_cate_export.php',//任务链接
            'startDate' => $startDate,
            'endDate' => $endDate,
            'first_level_id' => $first_level_id,
            'second_level_id' => $second_level_id,
            'third_level_id' => $third_level_id,
            'exportIndex' => $exportIndex
        ];

        $export_result = $EbsigMsgPush->ebsigAsyncPush($push_array);

        return response()->json($export_result);
    }
}