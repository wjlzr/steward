<?php

namespace App\Http\Controllers\Receive;

use App\Http\Controllers\Controller;
use App\Models\StApp;
use Illuminate\Http\Request;
use Wm;

class JdDjController extends Controller
{

    public function index(Request $request, $id)
    {

        $content = $request->input();
        if (!$content) {
            return response()->json(['message' => 'ok']);
        }

        if ($content['token'] != $this->config['token']) {
            response()->json([400, ' 	无效Token令牌']);
        }

        //校验签名
        if ($content['sign'] != $this->config->push_signature($content)) {
            response()->json([400, ' 	无效Sign签名']);
        }

        if ($content['app_key'] != $this->config['appSecret']) {
            response()->json([400, ' 	API参数异常']);
        }

        if (empty($content['jd_param_json'])) {
            response()->json([404, ' 	缺失必要数据']);
        }

        error_log('----------- jd 订单推送 jd ----------');
        error_log(var_export($content, true));

        $params = json_decode($content['jd_param_json'], true);

        switch (true) {

            //订单生效
            case $id == 'newOrder':
                $res = $this->create($params['billId']);
                break;

            //商户接单，订单等待出库
            case $id == 'orderWaitOutStore':
                $res = $this->receive($params['billId']);
                break;

            //订单取消
            case $id == 'userCancelOrder':
                $res = $this->cancel($params['billId']);
                break;

            //订单妥投
            case $id == 'finishOrder':
                $res = $this->finish($params['billId']);
                break;

            //用户取消申请消息
            case $id == 'applyCancelOrder':
                $res = $this->cancel_apply($params['billId']);
                break;

            //运单状态变更消息
            case $id = 'pushDeliveryStatus':
                $res = $this->waybillStatusChangePush($params, $params['statusId']);
                break;

            default:
                $res = ['code' => 200, 'message' => 'ok'];
                break;

        }

        if ($res['code'] != 200) {
            return response()->json(['code' => $res['code'], 'message' => $res['message']]);
        }

        return response()->json(['message' => 'ok']);

    }

    /**
     * 创建订单
     * @param $order_id
     * @return array
     */
    private function create($order_id)
    {

        if (!isset($order_id) || empty($order_id)) {
            return array('code' => 10005, 'msg' => '缺少参数：orderId');
        }

        $detail = Wm::send('jd.order.detail', $order_id);

        //接口查询订单详情
        if (!isset($detail['code']) || $detail['code'] != 200 || $detail['data']['code'] != 0) {
            return ['code' => $detail['code'], 'msg' => $detail['msg']];
        }

        $detail_array = json_decode($detail['data']['data'], true);
        if ($detail_array['code'] != 0) {
            return ['code' => $detail['code'], 'msg' => $detail['msg']];
        }

        $detail_array = $detail_array['result']['resultList'][0];

        $mobile = $detail_array['buyerMobile'];
        if (empty($mobile)) {
            $mobile = $detail_array['buyerTelephone'];
        }

        $invoice = 2;
        if ($detail_array['orderInvoiceOpenMark'] != 0) {
            $invoice = 1;
        }

        $wm_platform_data = WmPlatform::where('alias', $this->config['alias'])->first();

        //定义创建订单请求参数
        $args_data = array(
            'wm_id' => $wm_platform_data->id,
            'order_id' => $detail_array['orderId'],
            'operator' => 'jd-push',
            'user_fee' => $detail_array['orderBuyerPayableMoney'],
            'mall_code' => $detail_array['deliveryStationNo'],
            'send_time' => $detail_array['orderStartTime'],
            'deliver_lng' => $detail_array['buyerLng'],
            'deliver_lat' => $detail_array['buyerLat'],
            'deliver_name' => $detail_array['buyerFullName'],
            'deliver_mobile' => $mobile,
            'deliver_address' => $detail_array['buyerFullAddress'],
            'deliver_fee' => '',
            'lunch_box_fee' => '',
            'discount_fee' => $detail_array['orderDiscountMoney'],
            'need_invoice' => $invoice,
            'invoice_title' => $detail_array['orderInvoiceTitle'],
            'taxer_id' => '',
            'remark' => '',
            'wm_bill_json' => $detail,
            'goods' => array()
        );

        //检查商品
        if (!isset($detail_array['product']) || empty($detail_array['product'])) {
            return array('code' => 10015, 'message' => '没有商品信息');
        }

        foreach ($detail_array['product'] as $goods) {
            $args_data['goods'][] = array(
                'goodsName' => $goods['skuName'],       //商品名称
                'goods_number' => $goods['skuCount'],   //商品份数
                'sale_price' => $goods['skuStorePrice'], //商品售价
                'price' => $goods['skuStorePrice'],     //商品实售价
                'spec' => $goods['skuName'],
                'product_code' => $goods['skuIdIsv']    //商品编号
            );
        }

        $res = Wm::create_order($args_data);
        if ($res['code'] != 200) {
            return ['code' => 10015, 'msg' => '订单详情保存失败'];
        }

        return ['code' => 200, 'msg' => 'ok'];

    }

    /**
     * 商户接单
     * @param $order_id
     * @return array
     */
    private function receive($order_id)
    {

        if (!isset($order_id) || empty($order_id)) {
            return ['code' => 400, 'message' => '缺少参数：orderId'];
        }

        $wm_bill = WmBill::where('wm_bill_no', $order_id)->first();
        if (!$wm_bill) {
            return ['code' => 404, 'message' => '订单信息没有找到'];
        }

        $bill_master = Bill::find($wm_bill->bill_no);
        if (!$bill_master) {
            return ['code' => 404, 'message' => '订单信息没有找到'];
        }

        if ($bill_master->order_receive == 1) {
            return ['code' => 200, 'message' => 'ok'];
        }

        if ($bill_master->pay_type == 2 && $bill_master->pay_status != 1) {
            return ['code' => 200, 'message' => 'ok'];
        }

        if ($bill_master->bill_refund_status > 0) {
            return ['code' => 200, 'message' => 'ok'];
        }

        $opreator = 'jd-push';

        try {

            DB::beginTransaction();

            $bill_trace_obj = new BillTrace();
            $bill_trace_obj->uuid = makeUuid();
            $bill_trace_obj->timeStamp = Carbon::now();
            $bill_trace_obj->creator = $opreator;
            $bill_trace_obj->createTime = Carbon::now();
            $bill_trace_obj->bill_no = $bill_master->bill_no;
            $bill_trace_obj->bill_status = 1;
            $bill_trace_obj->content = '您的订单已接单' . $opreator;
            $bill_trace_obj->save();
            Bill::where('bill_no', $bill_master->bill_no)->update(['order_receive'=>1]);

            DB::commit();
            return ['code' => 200, 'message' => 'ok'];

        } catch (Exception $e) {
            DB::rollback();
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }

    }

    /**
     * 订单被取消
     * @param $order_id
     * @return array|mixed
     */
    private function cancel($order_id)
    {

        $wm_bill = WmBill::where('wm_bill_no', $order_id)->first();
        if (!$wm_bill) {
            return ['code' => 404, 'message' => '外卖订单没有找到'];
        }

        $bill_master = Bill::find($wm_bill->bill_no);
        if (!$bill_master) {
            return ['code' => 400, 'message' => '订单信息没有找到'];
        } else if ($bill_master->bill_status == 5) {
            return ['code' => 400, 'message' => '订单已经被取消'];
        }

        try {

            DB::beginTransaction();

            Bill::where('bill_no', $bill_master->bill_no)->update(['bill_status'=>5]);

            $bill_trace_obj = new BillTrace();
            $bill_trace_obj->uuid = makeUuid();
            $bill_trace_obj->timeStamp = Carbon::now();
            $bill_trace_obj->creator = 'jd-push';
            $bill_trace_obj->createTime = Carbon::now();
            $bill_trace_obj->bill_no = $bill_master->bill_no;
            $bill_trace_obj->bill_status = 5;
            $bill_trace_obj->content = '您的订单已取消';
            $bill_trace_obj->save();

            DB::commit();
            return ['code' => 200, 'message' => 'ok'];

        } catch(Exception $e) {
            DB::rollback();
            return ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }

    }

    /**
     * 订单妥投信息
     * @param $order_id
     * @return array|mixed
     */
    private function finish($order_id)
    {

        $wm_bill = WmBill::where('wm_bill_no', $order_id)->first();
        if (!$wm_bill) {
            return ['code' => 400, 'message' => '外卖订单没有找到'];
        }

        $bill_master = Bill::find($wm_bill->bill_no);
        if (!$bill_master) {
            return ['code' => 400, 'message' => '订单没有找到'];
        }

        Bill::where('bill_no', $bill_master->bill_no)->update(['bill_status' => 4]);

        return ['code' => 200, 'message' => 'ok'];

    }

    /**
     * 用户取消取消单/退单申请
     * @param $order_id
     * @param $type
     * @return array
     */
    private function cancel_apply($order_id, $type)
    {

        $wm_bill = WmBill::where('wm_bill_no', $order_id)->first();
        if (!$wm_bill) {
            return ['code'=>404, 'message'=>'外卖订单没有找到'];
        }

        $bill_master = Bill::find($wm_bill->bill_no);
        if (!$bill_master) {
            return ['code'=>404, 'message'=>'订单没有找到'];
        }

        $message = $type == 1 ? '用户取消取消单申请' : '用户取消退单申请';

        $bill_trace = new BillTrace();
        $bill_trace->uuid = makeUuid();
        $bill_trace->timeStamp = Carbon::now();
        $bill_trace->creator = $bill_master->custID;
        $bill_trace->createTime = Carbon::now();
        $bill_trace->bill_no = $bill_master->bill_no;
        $bill_trace->bill_status = $bill_master->bill_status;
        $bill_trace->content = $message;

        try {

            DB::beginTransaction();

            Bill::where('bill_no', $bill_master->bill_no)->update([
                'apply' => 0,
                'apply_id' => 0
            ]);

            $bill_trace->save();

            DB::commit();
            return ['code' => 200, 'message' => 'ok'];

        } catch(Exception $e) {
            DB::rollback();
            return ['code' => $e->getCode(), 'message' => $e->getMessage()];
        }

    }

    /**
     * 运单状态变更
     * @param $args_data
     * @param $type
     * @return array
     */
    private function waybillStatusChangePush()
    {
    }


    /**
     * 接收平台回调
     */
    public function callback(Request $request)
    {

        $content = $request->input();
        $content = '{"token":"04bd44fe-c726-4a1b-89c2-ed2db1f4fd92","expires_in":"31104000",
        "time":"1450695743010","uid":"","user_nick":"zhangsan","venderId":"72700"}';

        $temp_data = array();

        parse_str($content, $temp_data);

        $token_data = json_decode($temp_data['token'], true);

        if (empty($token_data)) {
            return response()->json(['code' => 400, 'message' => '无效Token令牌']);
        }

        StApp::where('app_id', 100004)->update([
            'access_token' => $token_data['token'],
        ]);

        return response()->json(['code' => 200, 'message' => '更新成功']);

    }


}