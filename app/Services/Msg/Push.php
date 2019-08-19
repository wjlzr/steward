<?php

namespace App\Services\Msg;


use App\Services\Msg\Logic\DevelopService;
use App\Services\Msg\Logic\DeviceService;

class Push
{

    /**
     * 开放接口消息推送
     * @param $push_data
     * @return array
     */
    public static function develop($push_data)
    {

        $develop = new DevelopService();
        $develop_result = $develop->send($push_data);
        return $develop_result;

    }

    /**
     * 终端设备推送
     * @param $push_data
     * @return array
     */
    public static function device($push_data)
    {

        return ['code'=>200, 'message'=>'ok'];

    }


}