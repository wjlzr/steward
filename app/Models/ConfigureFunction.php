<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Redis as Redis;

class ConfigureFunction extends Model
{

    protected $table = 'configure_function';

    protected $primaryKey = 'function_id';

    public $timestamps = false;

    public $incrementing = false;

    /**
     * 获取redis缓存的配置信息
     * @param int $id 配置id
     * @return array|mixed|null
     */
    public static function getRedis($id)
    {
        
        $project_id = config('system.project_id');
        $redis_index = 'G_CONFIGURE_FUNCTION_DATA_' . $project_id . '_' . $id;

        //获取redis缓存的配置信息
        $config = Redis::get($redis_index);
        if ($config) {
            $config = redisTransformation($config);
            return $config;
        }

        //查询配置信息
        $config = self::find($id);
        if ($config) {

            $config = $config->toArray();

            //把配置信息缓存到redis里并返回
            if (empty($config['configure_json'])) {
                $config['configure'] = [];
            } else {
                $configure_json = json_decode($config['configure_json'], true);
                $config['configure'] = $configure_json['configure'];
            }
            Redis::setex($redis_index, 7560000, redisTransformation($config, 'set', 7560000));
            return $config;

        } else {

            $http = new \App\Services\ControlCenterService();

            $return_configure = $http->get('wdh/configure/get', ['function_id' => $id]);
            if ( $return_configure['code'] != 200) {
                return null;
            }

            $config = new self;
            $config->uuid = makeUuid();
            $config->timeStamp = \Carbon\Carbon::now();
            $config->creator = 'system';
            $config->createTime = \Carbon\Carbon::now();
            $config->function_id = $return_configure['data']['function_id'];
            $config->function_name = $return_configure['data']['function_name'];
            $config->tpl_file = $return_configure['data']['tpl_file'];
            $config->configure_json = null;
            $config->save();

            return [
                'function_id' => $config->function_id,
                'function_name' => $config->function_name,
                'tpl_file' => $config->tpl_file,
                'configure_json' => $config->configure_json,
                'configure' => [],
            ];

        }
        
    }

    /**
     * 获取redis缓存的配置信息，只返回configure值
     * @param $id
     * @return mixed|null
     */
    public static function getConfigure($id) {
        $config = self::getRedis($id);
        if ($config) {
            return $config['configure'];
        }
        return [];
    }

    /**
     * 获取redis缓存的配置信息，返回configure数组里的某一个配置字段值
     * @param int $id 配置id
     * @param string $field 配置字段
     * @param mixed $default_value 没有字段时默认返回
     * @return mixed
     */
    public static function getConfigureField($id, $field, $default_value=0) {

        $config = self::getRedis($id);
        if (isset($config['configure'][$field])) {
            return $config['configure'][$field];
        } else {
            return $default_value;
        }

    }

    
}
