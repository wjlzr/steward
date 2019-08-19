<?php
namespace App\Services\Wm\JdDj\Request;

use App\Services\Wm\OrderFactoryInterface;

class JdOrderRequest implements OrderFactoryInterface
{

    public function __construct($curl)
    {
        $this->curl = $curl;
    }

    /**
     * 获取订单详情接口
     * @param string $order_id 京东外卖订单Id
     * @return array
     */
    public function detail($order_id)
    {
        if (!isset($order_id) || empty($order_id)) {
            $this->curl->response('订单号不能为空!');
        }
        return $this->curl->call(array('orderId' => $order_id), '/order/es/query');
    }


    /**
     * 确认接单
     * @param int /string $order_id 订单号
     * @return mixed
     */
    public function accept_order($args_data)
    {
        if (!isset($args_data['order_id']) || empty($args_data['order_id'])) {
            $this->curl->response('订单号不能为空!');
        }

        if ($args_data['isAgreed'] != true || $args_data['isAgreed'] != false) {
            $this->curl->response('缺少参数或者参数错误：isAgreed');
        }

        if (!isset($args_data['operator']) || empty($args_data['operator'])) {
            $args_data['operator'] = 'system';
        }
        return $this->curl->call('ocs/orderAcceptOperate', [
            'orderId' => $args_data['order_id'],
            'isAgreed' => $args_data['isAgreed'],
            'operator' => $args_data['operator'],
        ]);

    }

    /**
     * 取消订单
     * @param int /string $order_id 订单号
     * @param array $extend_array 扩展数组
     * @return mixed
     */
    public function cancel_order($order_id, $extend_array = [])
    {

        if (!isset($order_id) || empty($order_id)) {
            $this->curl->response('订单号不能为空!');
        }

        if (!isset($extend_array['time']) || empty($extend_array['time'])) {
            $extend_array['time'] = date('Y-m-d H:i:s');
        }

        return $this->curl->call('/order/confirmReceiveGoods', [
            'orderId' => $order_id,
            'operateTime' => $extend_array['time']
        ]);

    }

    /**
     * 审核用户申请取消单/退单
     * @param int /string $order_id 订单号
     * @param bool|true $is_agree 是否同意取消/退单
     * @param string $remark 备注
     * @return mixed
     */
    public function audit_cancel_order($order_id, $is_agree = true, $remark = '')
    {
        if (!isset($order_id) || empty($order_id)) {
            $this->curl->response('订单号不能为空!');
        }
        return $this->curl->call('/ocs/orderCancelOperate', [
            'orderId' => $order_id,
            'isAgreed' => $is_agree,
            'operator' => 'system',
            'remark' => $remark
        ]);
    }

    /**
     * 订单发货(京东接口暂未找到)
     * @param int /string $order_id 订单号
     * @return mixed
     */
    public function send_out_order($order_id)
    {
    }

    /**
     * 妥投订单
     * @param int /string $order_id 订单号
     * @param array $extend_array
     * @return mixed
     */
    public function delivered_order($order_id, $extend_array = [])
    {
        if (!isset($order_id) || empty($order_id)) {
            $this->curl->response('订单号不能为空!');
        }

        if (!isset($extend_array['operate']) || empty($extend_array['operate'])) {
            $extend_array['operate'] = 'system';
        }

        if (!isset($extend_array['time']) || empty($extend_array['time'])) {
            $extend_array['time'] = date('Y-m-d H:i:s');
        }
        return $this->curl->call('/ocs/deliveryEndOrder', [
            'orderId' => $order_id,
            'operPin' => $extend_array['operate'],
            'operTime' => $extend_array['time']
        ]);
    }

    /**
     * 回复催单
     * @param array $args = [
     *      'remind_id' => int 催单id
     *      'reply_content' => string 回复内容
     * ]
     * @return mixed
     */
    public function reply_remind($args)
    {

    }

}