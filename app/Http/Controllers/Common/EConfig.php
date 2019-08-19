<?php
namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\SysConfig;
use Illuminate\Support\Facades\Redis as Redis;

class EConfig extends Controller {

    /**
     * 获取配置值
     * @param $s_id
     * @param $key
     */
    public static function getVal($s_id,$key){

        $config = EConfig::getConfig($s_id);
        if( !empty($config) && !empty($config[$key]) ){
            return $config[$key];
        }

        return null;
    }

    /**
     * 获取配置信息
     * @param $s_id 配置ID
     */
    public static function getConfig($s_id){

        //先获取redis缓存数据
        $redis_cache = Redis::get('GLOBAL_CONFIG_'.$s_id);

        if( !empty($redis_cache) ){
            return json_decode($redis_cache,true);
        }

        $sys_config = SysConfig::find($s_id);

        if( empty($sys_config) ){
            return null;
        }

        $sys_config = $sys_config->toArray();

        if( empty($sys_config['s_val']) ){
            return null;
        }

        Redis::set('GLOBAL_CONFIG_'.$s_id , $sys_config['s_val']);

        Redis::expire('GLOBAL_CONFIG_'.$s_id  , 86400*2);

        return json_decode($sys_config['s_val'],true);
    }

}