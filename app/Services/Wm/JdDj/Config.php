<?php

namespace App\Service\Wm\Jd;


class Config
{

    public $getWay = 'https://openo2o.jd.com/djapi';
    public $testGetWay = 'https://openo2o.jd.com/mockapi';
    public $token;
    public $version = 'v1';
    public $alias = 'JdDj';
    public $appId = 100004;
    public $userAgent = 'ebsig-jd-api';
    public $debug = 1;

    //Ĭ����������
    public function _cover_attribute()
    {

        $attr_arr = [];

        if ($this->debug) {
            $attr_arr['getWay'] = $this->testGetWay;
        }

        return $attr_arr;

    }

}