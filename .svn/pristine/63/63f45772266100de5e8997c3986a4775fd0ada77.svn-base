<?php

namespace App\Services\Wm\MtFood;


class Config
{
    public $getWay = 'http://waimaiopen.meituan.com/api/';
    public $testGetWay = 'http://test.waimaiopen.meituan.com/api/';
    public static $callbackWay = 'http://wdh.ebsig.com/api/takeout/meituan/gateway.do?_func=';
    public $testCallbackWay = 'http://wdh.ebsig.com/api/takeout/meituan/gateway.do?_func=';
    public $appKey;
    public $appSecret;
    public static $version = 'v1';
    public $alias = 'MtFood';
    public $platform = 31;
    public $userAgent = 'ebsig-meituan-api';
    public $debug = 0;
    public $wmID = 3;
    public $operator = 'mt-api';

    //默认属性重载
    public function _cover_attribute()
    {

        $attr_arr = [];

        if ($this->debug) {
            $attr_arr['getWay'] = $this->testGetWay;
            $attr_arr['callbackWay'] = $this->testCallbackWay;
        }

        return $attr_arr;

    }

}