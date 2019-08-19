<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\StRegion;

class RegionController extends Controller {

    
    //获取省份信息
    public function getProvince()
    {

        $region_data = [];

        $province_data = StRegion::where(['p_id'=>0, 'level'=>1])->get();
        if ( !$province_data ) {
            return response()->json(['message' => '没有省份数据', 'code' => 10001]);
        }

        foreach ( $province_data as $province ) {
            $region_data[] = [
                'id' => app_to_int($province->id),
                'name' => app_to_string($province->name)
            ];
        }

        return response()->json(['message'=>'ok','code'=>200, 'data'=>$region_data]);

    }


    //获取市区信息
    public function getCity( $province_id )
    {

        if (!ebsig_is_int( $province_id )) {
            return response()->json(['message'=>'参数错误', 'code'=>10000]);
        }

        $region_data = [];

        $city_data = StRegion::where(['p_id'=>$province_id, 'level'=>2])->get();
        if ( !$city_data ) {
            return response()->json(['message'=>'没有市区数据', 'code'=>10001]);
        }

        foreach ( $city_data as $city ) {
            $region_data[$city['id']] = [
                'id' => app_to_int($city->id),
                'name' => app_to_string($city->name)
            ];
        }

        return response()->json(['message'=>'ok','code'=>200, 'data'=>$region_data]);

    }


    //获取地区信息
    public function getCounty( $city_id )
    {

        if (!ebsig_is_int( $city_id )) {
            return response()->json(['message'=>'参数错误','code'=>10000]);
        }

        $region_data = [];

        $county_data = StRegion::where(['p_id'=>$city_id, 'level'=>3])->get();
        if ( empty($county_data) ) {
            return response()->json(['message'=>'没有地区数据', 'code'=>10001]);
        }

        foreach ( $county_data as $county ) {
            $region_data[$county['id']] = [
                'id' => app_to_int($county->id),
                'name' => app_to_string($county->name)
            ];
        }

        return response()->json(['message'=>'ok','code'=>200,'data'=>$region_data]);
    }


}