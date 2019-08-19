<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\User\StUser;
use App\Models\Order\StOrder;
use Wm;

class OrderController extends Controller
{

    /**
     * 订单消息提醒
     * @return \Illuminate\Http\JsonResponse
     */
    public function push()
    {

        //推送新订单
        $new_order = StOrder::where([['hang_up',0],['status',0]]) -> get();

        $new_mall_id_array = [];

        if( !$new_order -> isEmpty()){

            foreach ( $new_order as $new){
                $new_mall_id_array[] = $new -> mall_id;
            }

            $mall_user = StUser::where('status', 1)
                ->where(function($query) use ($new_mall_id_array) {
                    $query->whereIn('mall_id', $new_mall_id_array)
                        ->orWhere('type', 1);
                })->get();

            if ($mall_user->count() > 0) {
                Wm::push_message($mall_user, '您有新订单，请注意查收', 1);
            }
        }

        //推送催单
        $remind_mall_id_array = [];
        $remind_order = StOrder::where([['hang_up',0],['apply',3]]) -> get();

        if( !$remind_order -> isEmpty()){

            foreach ( $remind_order as $remind){
                $remind_mall_id_array[] = $remind -> mall_id;
            }

            $mall_user = StUser::where('status', 1)
                ->where(function($query) use ($remind_mall_id_array) {
                    $query->whereIn('mall_id', $remind_mall_id_array)
                        ->orWhere('type', 1);
                })->get();

            if ($mall_user->count() > 0) {
                Wm::push_message($mall_user, '您有催单啦，请及时处理', 2);
            }
        }

        //推送退单

        $return_mall_id_array = [];
        $return_order = StOrder::where('hang_up',0)->whereIn('apply',[1, 2])-> get();

        if( !$return_order -> isEmpty()){

            foreach ( $return_order as $return){
                $return_mall_id_array[] = $return -> mall_id;
            }

            $mall_user = StUser::where('status', 1)
                ->where(function($query) use ($return_mall_id_array) {
                    $query->whereIn('mall_id', $return_mall_id_array)
                        ->orWhere('type', 1);
                })->get();

            if ($mall_user->count() > 0) {
                Wm::push_message($mall_user, '您有退单啦，请及时处理', 3);
            }
        }

        return response() -> json(['code' => 200 ,'message' => 'ok']);

    }

}
