<?php
namespace App\Services\Wm\BdFood\Request;

use App\Services\Wm\OrderFactoryInterface;

class BdFoodOrderRequest implements OrderFactoryInterface
{

    public function __construct($curl)
    {
        $this->curl = $curl;
    }

    /**
     * 确认订单接口[上行]
     * @param $args_data
     * @return mixed
     */
    public function accept_order($args_data)
    {

        if (!isset($args_data['order_id']) || empty($args_data['order_id'])) {
            return $this->curl->response('缺少必要参数：orderId');
        }

        return  $this->curl->call(['order_id'=>$args_data['order_id']], 'order.confirm') ;
    }

    /**
     * 订单取消[上行]
     * @param $args_data
     * @return mixed
     */
    public function cancel_order($args_data)
    {

        if (!isset($args_data['order_id']) || empty($args_data['order_id'])) {
            return $this->curl->response('缺少必要参数：orderId');
        }

        $args_data = [
            'order_id' => $args_data['order_id'],
            'reason' => isset($args['remark']) ? $args['remark'] : '',
            'type' => !empty($args['type']) ? $args['type'] : '-1'
        ];

        return $this->curl->call($args_data, 'order.cancel');
    }

    /**
     * 订单发货
     * @param $args_data
     * @return mixed
     */
    public function send_out_order($args_data)
    {
        return $this->curl->response('ok',200);
    }

    /**
     * 订单完成[上行]
     * @param $args_data
     * @return mixed
     */
    public function delivered_order($args_data)
    {

        if (!isset($args_data['order_id']) || empty($args_data['order_id'])) {
            return $this->curl->response('缺少必要参数：orderId');
        }

        return $this->curl->call(['order_id'=>$args_data['order_id']], 'order.complete');
    }

    /**
     * 审核用户申请取消单/退单
     * @param $args_data
     * @return mixed
     */
    public function audit_cancel_order($args_data)
    {
        return $this->curl->response('ok',200);
    }

    /**
     * 回复催单
     * @param array $args_data
     * @return mixed
     */
    public function reply_remind($args_data)
    {
        return $this->curl->response('ok', 200);
    }

}