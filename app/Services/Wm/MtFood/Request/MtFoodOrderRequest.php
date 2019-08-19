<?php
namespace App\Services\Wm\MtFood\Request;

use App\Services\Wm\OrderFactoryInterface;

class MtFoodOrderRequest implements OrderFactoryInterface
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

        return $this->curl->call(['order_id' => $args_data['order_id']], 'order/confirm');
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

        $request = [
            'order_id' => $args_data['order_id'],
            'reason' => isset($args_data['remark'])?$args_data['remark']:'',
            'reason_code' => isset($args['type']) ? $args['type'] : '2007'
        ];

        return $this->curl->call($request, 'order/cancel');
    }

    /**
     * 订单配送中【上行】
     * @param $args_data
     * @return mixed
     */
    public function send_out_order($args_data)
    {

        if (!isset($args_data['order_id']) || empty($args_data['order_id'])) {
            return $this->curl->response('缺少必要参数：orderId');
        }

        $courier_name = isset($args_data['courier_name'])?$args_data['courier_name']:null;

        $courier_phone = isset($args_data['courier_phone'])?$args_data['courier_phone']:null;

        $request = ['order_id'=>$args_data['order_id']];
        if (!is_null($courier_name)) {
            $request['courier_name'] = $courier_name;
        }
        if (!is_null($courier_phone)) {
            $request['courier_phone'] = $courier_phone;
        }
        return $this->curl->call($request, 'order/delivering');

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

        return $this->curl->call(['order_id' => $args_data['order_id']], 'order/arrived');
    }

    /**
     * 审核用户申请取消单/退单
     * @param $args_data
     * @return mixed
     */
    public function audit_cancel_order($args_data)
    {

        if (!ebsig_is_int($args_data['order_id'])) {
            return $this->curl->response('参数错误：order_id');
        }

        if (!isset($args_data['is_agree']) || !is_bool($args_data['is_agree'])) {
            $args_data['is_agree'] = true;
        }

        if (!isset($args_data['remark'])) {
            if ($args_data['is_agree']) {
                $reason = '同意退款';
            }else{
                $reason = '拒绝退款';
            }
        }else{
            $reason = $args_data['remark'];
        }

        $request = [
            'order_id' => $args_data['order_id'],
            'reason' => $reason
        ];

        if ($args_data['is_agree']) {
            $result = $this->curl->call($request, 'order/refund/agree');

            error_log('=======//同意退款');
            error_log(var_export($result,true));

            return $result;
        }else{
            return $this->curl->call($request, 'order/refund/reject');
        }
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