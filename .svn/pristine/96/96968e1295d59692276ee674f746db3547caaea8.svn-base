<?php

namespace App\Http\Controllers\Api\Eoa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Eoa\App;
use App\Models\Eoa\AppPush;


class MessageController extends Controller
{


    /**
     * 应用消息推送
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function push(Request $request)
    {

        $project_id = $request->input('project_id');
        $action = $request->input('action');

        if (empty($project_id)) {
            return response()->json(['code'=>400, 'message'=>'缺少必要参数：project_id']);
        }

        if (empty($action)) {
            return response()->json(['code'=>400, 'message'=>'缺少必要参数：action']);
        }

        $app_array = [];

        $app = App::where(['project_id'=>$project_id, 'enable'=>1])->get();

        if ($app->count() > 0) {

            foreach($app as $a) {

                $app_push = AppPush::where('action', $action)
                    ->where('app_id', $a->id)
                    ->get();

                if( !$app_push -> isEmpty()){
                    foreach($app_push as $push) {
                        $app_array[] = [
                            'app_id' => app_to_int($push->app_id),
                            'secret' => app_to_string($a->secret),
                            'push_url' => app_to_string($a->push_url)
                        ];
                    }
                }
            }
        }

        return response()->json(['code'=>200, 'message'=>'ok', 'data'=>$app_array]);

    }

}
