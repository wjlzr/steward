<?php

namespace App\Services\Core\Mall;


use App\Models\Mall\StMall;
use App\Models\StRegion;
use App\Services\Ebsig\ControlCenterService;
use DB;
use PHPUnit\Runner\Exception;

class MallEditService
{

    /**
     * 门店信息编辑和新增
     * @param $args
     * @return array
     */
    public function edit($args)
    {

        $mall_id = isset($args['o_id']) && !empty($args['o_id'])
            ? $args['o_id']
            : '';

        if (!isset($args['mall_name']) || empty($args['mall_name'])) {
            return response()->json(['code' => 400, 'message' => '门店名称不能为空']);
        }

        if (!isset($args['mall_code']) || empty($args['mall_code'])) {
            return response()->json(['code' => 400, 'message' => '门店号不能为空']);
        }

        if (!isset($args['phone']) || empty($args['phone'])) {
            return response()->json(['code' => 400, 'message' => '门店电话不能为空']);
        }

        if (!isset($args['status']) || !in_array($args['status'], [0, 1])) {
            return response()->json(['code' => 400, 'message' => '营业时间类型不能为空']);
        }

        if ($args['status'] == 1) {

            $start_data = $args['start_arr'];
            $end_data = $args['end_arr'];

            if (empty($start_data) || empty($end_data)) {
                return response()->json(['code' => 400, 'message' => '请选择营业时间']);
            }
            $time_data = array_combine($start_data, $end_data);
            $time_str = '';

            foreach ($time_data as $s_time => $e_time) {
                $time_str .= $s_time . '-' . $e_time . ',';
            }
        }

        if (!isset($args['latitude']) || empty($args['latitude'])) {
            return response()->json(['code' => 400, 'message' => '纬度不能为空']);
        }

        if (!isset($args['longitude']) || empty($args['longitude'])) {
            return response()->json(['code' => 400, 'message' => '经度不能为空']);
        }

        if (!isset($args['province_id']) || empty($args['province_id'])) {
            return response()->json(['code' => 400, 'message' => '请选择门店地址']);
        }

        if (!isset($args['city_id']) || empty($args['city_id'])) {
            return response()->json(['code' => 400, 'message' => '请选择门店地址']);
        }

        if (!isset($args['address']) || empty($args['address'])) {
            return response()->json(['code' => 400, 'message' => '详细地址不能为空']);
        }

        if (!isset($args['mall_logo']) || empty($args['mall_logo'])) {
            return response()->json(['code' => 400, 'message' => '门店logo不能为空']);
        }

        $province_obj = StRegion::find($args['province_id']);
        $city_obj = StRegion::find($args['city_id']);
        if (empty($province_obj) || empty($city_obj)) {
            return response()->json(['code' => 400, 'message' => '地区数据未找到']);
        }

        if (isset($args['county_id']) && !empty($args['county_id'])) {
            $county_obj = StRegion::find($args['county_id']);
        }

        if (isset($args['mall_id']) && !empty($args['mall_id'])) {
            $st_obj = StMall::find($args['mall_id']);
            if (!$st_obj) {
                return response()->json(['code' => 404, 'message' => '门店信息不存在']);
            }
        } else {
            $st_obj = new StMall();
        }

        $st_obj->creator = 'system';
        $st_obj->name = $args['mall_name'];
        $st_obj->code = $args['mall_code'];
        $st_obj->province = $province_obj->name;
        $st_obj->city = $city_obj->name;
        $st_obj->county = !empty($county_obj->name) ? $county_obj->name : '';
        $st_obj->province_id = $args['province_id'];
        $st_obj->city_id = $args['city_id'];
        $st_obj->county_id = $args['county_id'] ? $args['county_id'] : '';
        $st_obj->address = $args['address'];
        $st_obj->latitude = $args['latitude'];
        $st_obj->longitude = $args['longitude'];
        $st_obj->address = $args['address'];
        $st_obj->phone = $args['phone'];
        $st_obj->business_time_type = $args['status'];
        $st_obj->business_time = $args['status'] == 1 ? rtrim($time_str, ',') : '00::00-23::59';
        $st_obj->status = $args['status'];
        $st_obj->logo = $args['mall_logo'];
        $st_obj->shar_rate = $args['shar_rate'] ? $args['shar_rate'] : '';
        $st_obj->safety_stock = $args['safety_stock'] ? $args['safety_stock'] : '';

        $control_service = new ControlCenterService();

        try {

            //开启事务
            DB::beginTransaction();

            $st_obj->save();

            $control_result = $control_service->post('mall/edit', [
                'mallID' => $mall_id,
                'mallName' => $args['mall_name'],
                'mallCode' => $args['mall_code'],
                'provinceid' => $args['province_id'],
                'provinceName' => $province_obj->name,
                'cityid' => $args['city_id'],
                'cityName' => $city_obj->name,
                'countyid' => $args['county_id'] ? $args['county_id'] : '',
                'countyName' => !empty($county_obj->name) ? $county_obj->name : '',
                'address' => $args['address'],
                'project_id' => env('PROJECT_ID'),
                'latitude' => $args['latitude'],
                'longitude' => $args['longitude'],
                'phone' => $args['phone'],
                'mallType' => 1,
            ]);

            if ($control_result['code'] != 200) {
                throw new Exception($control_result['message'], $control_result['code']);
            }

            StMall::where('id',$st_obj->id)->update(['external_no' => $control_result['data']['id']]);

            DB::commit();

            return ['code' => 200, 'message' => '操作成功'];


        }catch (Exception $e) {
            DB::rollback();
            return ['code'=>$e->getCode(), 'message'=>$e->getMessage()];
        }

    }
}