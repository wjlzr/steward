<?php

namespace App\Services;

use App\Models\StRegion;


class LbsMapService
{


    const BD_LBS_AK = 'mGi6xeiGPBvUmcVGuys1fhvO';


    /**
     * 根据地理经纬度反查并匹配区域
     * @param $latitude
     * @param $longitude
     * @return array
     */
    public function reverseAddress($latitude, $longitude)
    {

        $request_url = 'http://api.map.baidu.com/geocoder/v2/?ak='.self::BD_LBS_AK.'&callback=renderReverse&location='
            . $latitude . ',' . $longitude.'&output=xml&pois=1';

        $request_data = file_get_contents($request_url);
        $xmlData = simplexml_load_string($request_data);

        if (!isset($xmlData->result->addressComponent)) {
            return ['code'=>400, 'message'=>'地址信息没有解析到'];
        }

        $addressComponent = $xmlData->result->addressComponent;
        $province = $this->regionIdByName($addressComponent->province, 0, 1);
        $city = $this->regionIdByName($addressComponent->city, $province[0], 2);
        $county = $this->regionIdByName($addressComponent->district, $city[0], 3);

        if (!isset($province[1]) || !isset($city[1])) {
            return ['code'=>404, 'message'=>'该地理信息没有匹配到'];
        }

        $return_result = [
            'province_id' => app_to_int($province[0]),
            'city_id' => app_to_int($city[0]),
            'county_id' => app_to_int($county[0]),
            'province' => app_to_string($province[1]),
            'city' => app_to_string($city[1]),
            'county' => app_to_string($county[1])
        ];

        return ['code'=>200, 'data'=>$return_result];

    }


    /**
     * 根据区域名称查询区域Id
     * @param $name
     * @param $p_id
     * @param int $level
     * @return array|int
     */
    private function regionIdByName($name, $p_id, $level = 1)
    {

        if (empty($name)) {
            return 0;
        }

        if ($level == 1) {
            if (mb_strrpos($name, '省')) {
                $name = mb_substr($name, 0, mb_strrpos($name, '省')).'%';
            }
        } else if ($level == 2) {
            if (mb_strrpos($name, '市')) {
                $name = mb_substr($name, 0, mb_strrpos($name, '市')).'%';
            }
        } else {
            if (mb_strrpos($name, '县')) {
                $name = mb_substr($name, 0, mb_strrpos($name, '县')).'%';
            } else if (mb_strrpos($name, '区')) {
                $name = mb_substr($name, 0, mb_strrpos($name, '区')).'%';
            }
        }

        if ($p_id > 0) {
            $region = StRegion::where(['p_id'=>$p_id, 'level'=>$level])
                ->where('name', 'like', $name)
                ->first();
        } else {
            $region = StRegion::where('level', $level)
                ->where('name', 'like', $name)
                ->first();
        }

        return $region ? [ $region->id, $region->name ] : 0;

    }


}