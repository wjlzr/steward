<?php
namespace App\Services\Ebsig;

use GuzzleHttp\Client;

class ControlCenterService
{

    const APP_ID = 'dcf7da0be34211e4882200163e00313a';

    const APP_KEY = '5d6011fc3373d26cbc7916ff41878925';

    const GATEWAY = 'http://www.ebsig.com/api/';

    /**
     * 生成签名字符串
     * @param array $http_data http请求参数数组
     * @return string
     */
    private function createSignature($http_data)
    {

        ksort($http_data);

        $sign_str = '';
        foreach ($http_data as $k => $v) {
            if ($v === '' || $k == 'sign' || $k == '_url' || $k == 'file' || $k == 'fileKey') {
                continue;
            }
            if ($sign_str == '') {
                $sign_str .= $k . '=' . $v;
            } else {
                $sign_str .= '&' . $k . '=' . $v;
            }
        }
        $sign_str .= 'key=' . self::APP_KEY;
        return strtoupper(md5($sign_str));

    }

    /**
     * 请求接口
     * @param string $api_name 接口地址
     * @param array $http_data 业务参数数组
     * @param string $request_way 请求方式
     * @return array
     */
    public function request($api_name, $http_data = [], $request_way = 'post')
    {

        $client = new Client();

        $http_data['appId'] = self::APP_ID;
        $http_data['timestamp'] = '1516153599';
        $http_data['project_id'] = 10433;
        $http_data['sign'] = $this->createSignature($http_data);

        $gateway_url = self::GATEWAY . trim($api_name, '\/');

        if ($request_way == 'get') {
            $request = $client->get($gateway_url, ['query' => $http_data]);
        } else {
            $request = $client->request('POST', $gateway_url, [
                'form_params' => $http_data
            ]);
        }

        if ($request->getStatusCode() != 200) {
            return ['code' => $request->getStatusCode(), 'message' => '请求失败'];
        }
        $return_data = json_decode($request->getBody()->getContents(),true);

        return $return_data;
        
    }

    /**
     * GET方式请求接口
     * @param string $api_name 接口地址
     * @param array $http_data 业务参数数组
     * @return array
     */
    public function get($api_name, $http_data = [])
    {
        return $this->request($api_name, $http_data, 'get');
    }

    /**
     * POST方式请求接口
     * @param string $api_name 接口地址
     * @param array $http_data 业务参数数组
     * @return array
     */
    public function post($api_name, $http_data = [])
    {
        return $this->request($api_name, $http_data, 'post');
    }

}