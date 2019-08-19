<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;

class StAppLog extends Model
{


    protected $table = 'st_app_log';


    /**
     * 接口日志记录
     * @param $api_name string 接口名称
     * @param $args_data array 日志记录内容
     * @return mixed
     */
    public static function addLog($api_name, $args_data)
    {

        $creator = isset($args_data['creator'])
            ? $args_data['creator']
            : 'api-request';

        $app_id = isset($args_data['app_id']) && ebsig_is_int($args_data['app_id'])
            ? $args_data['app_id']
            : 0;

        $request_data = isset($args_data['request'])
            ? $args_data['request']
            : [];

        $return_data = isset($args_data['return'])
            ? $args_data['return']
            : [];

        $exec_time = isset($args_data['start_time']) && !empty($args_data['start_time'])
            ? microtime(true) - $args_data['start_time']
            : '';

        $app_log = new self;
        $app_log->creator = $creator;
        $app_log->api_name = $api_name;
        $app_log->app_id = $app_id;
        $app_log->request_data = json_encode($request_data);
        if (!empty($return_data)) {
            $app_log->return_data = $return_data;
        }
        if (!empty($exec_time)) {
            $app_log->exec_time = $exec_time * 1000;
        }
        $app_log->save();

        return $app_log->id;

    }


    /**
     * 日志修改
     * @param $log_id int 日志ID
     * @param $args_data array 日志记录内容
     * @return bool
     */
    public static function updateLog($log_id, $args_data)
    {

        $creator = isset($args_data['creator'])
            ? $args_data['creator']
            : '';

        $app_id = isset($args_data['app_id']) && ebsig_is_int($args_data['app_id'])
            ? $args_data['app_id']
            : 0;

        $return_data = isset($args_data['return'])
            ? $args_data['return']
            : [];

        $exec_time = isset($args_data['start_time']) && !empty($args_data['start_time'])
            ? microtime(true) - $args_data['start_time']
            : '';

        $app_log = self::find($log_id);
        if ($app_log) {
            $app_log->return_data = $return_data;
            if (!empty($creator)) {
                $app_log->creator = $creator;
            }
            if (!empty($app_id)) {
                $app_log->app_id = $app_id;
            }
            if (!empty($exec_time)) {
                $app_log->exec_time = $exec_time * 1000;
            }
            $app_log->save();
        }

        return true;

    }


}