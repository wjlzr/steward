<?php

namespace App\Services\Msg\Logic;

use JPush\Client as JPush;
use Mockery\CountValidator\Exception;

class DeviceService
{


    //推送应用信息
    private $account = [
        [
            'key' => 'a48e4096e80a028237873632',
            'secret' => '9fc877a31693d91d815412b8'
        ],
        [
            'key' => '882d1017c6acea4b221be9f0',
            'secret' => 'fbd56e70b944f4759f24df10'
        ],
    ];


    //发起推送
    public function send($message, $registrationId, $extras = null) {

        foreach($this->account as $account) {

            try {

                $client = new JPush($account['key'], $account['secret']);

                if (is_null($extras)) {
                    $client->push()
                        ->setPlatform('all')
                        ->setNotificationAlert($message)
                        ->addRegistrationId($registrationId)
                        ->setOptions(100000, 3600, null, true)
                        ->send();
                } else {
                    $client->push()
                        ->setPlatform('all')
                        ->setNotificationAlert($message)
                        ->addAndroidNotification($message, $message, 1, $extras)
                        ->addIosNotification($message, $extras['ios_source'], '+0', true, 'IOS category', $extras)
                        ->addRegistrationId($registrationId)
                        ->setOptions(100000, 3600, null, true)
                        ->send();
                }

            } catch (\JPush\Exceptions\APIConnectionException $e) {

                error_log(var_export([
                    'code' => $e->getCode(),
                    'message' => '推送|APIConnectionException错误：'.$e->getMessage()
                ], true));
                continue;

            } catch (\JPush\Exceptions\APIRequestException $e) {

                error_log(var_export([
                    'code' => $e->getCode(),
                    'message' => '推送|APIRequestException错误：'.$e->getMessage()
                ], true));
                continue;

            } catch (\JPush\Exceptions\JPushException $e) {

                error_log(var_export([
                    'code' => $e->getCode(),
                    'message' => '推送|JPushException错误：'.$e->getMessage()
                ], true));
                continue;

            } catch (Exception $e) {

                error_log(var_export([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ], true));
                continue;

            }

        }

        return ['code'=>200, 'message'=>'推送成功！'];

    }

}