<?php
namespace App\Services\Queue;

use App\Services\Queue\Src\GoMq;



class QueueService
{


    /**
     * 推送消息给队列服务
     * @param array $push_array 消息内容
     * @return array
     */
    public static function async( $push_array ) {

        $queue_service = new GoMq();
        $queue_result = $queue_service->async($push_array);
        return $queue_result;

    }


}
