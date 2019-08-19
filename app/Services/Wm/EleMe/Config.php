<?php

namespace App\Services\Wm\EleMe;

class Config
{

    public $getWay = 'https://open-api.shop.ele.me';
    public $testGetWay = 'https://open-api-sandbox.shop.ele.me';
    public $callback = '/api/open/eleme/callback';
    public $token;
    public $version = 'v1';
    public $alias = 'EleMe';
    public $appId = 100002;
    public $userAgent = 'ebsig-ele-api';
    public $uuid;
    public $debug = 1;

    //默认属性重载
    public function _cover_attribute()
    {

        $attr_arr = [];

        if ($this->debug) {
            $attr_arr['getWay'] = $this->testGetWay;
        }

        if (empty($this->uuid)) {
            $attr_arr['uuid'] = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }

        if (!empty($this->callback)) {
            $host = 'http://' . $_SERVER['HTTP_HOST'];
            $attr_arr['callback'] = $host . $this->callback;
        }

        return $attr_arr;

    }

}