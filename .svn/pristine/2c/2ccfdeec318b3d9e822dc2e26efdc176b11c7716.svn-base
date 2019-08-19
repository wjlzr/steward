<?php

namespace App\Http\Controllers\Api\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\StApp;
use App\Models\Mall\StAppMall;
use App\Services\Mall\MallSearchService;


class MallController extends Controller
{


    //门店列表搜索
    public function search(Request $request)
    {

        $mall_search = new MallSearchService();
        $mall_result = $mall_search->search($request->input());
        return response()->json(['code'=>200, 'data'=>$mall_result]);

    }


    //门店平台状态统计
    public function mallAppCount()
    {

        $app = StApp::where('enable', 1)->get();

        $mall_app_result = [];
        $url = 'http://' . $_SERVER['HTTP_HOST'];

        foreach($app as $a) {

            $open_number = StAppMall::where(['app_id' => $a->id, 'status' => 1])->count();
            $close_number = StAppMall::where(['app_id' => $a->id, 'status' => 2])->count();

            $mall_app_result[] = [
                'app_id' => app_to_int($a->id),
                'app_mame' => app_to_string($a->name),
                'app_logo' => app_to_string($url . $a->logo),
                'open' => app_to_int($open_number),
                'close' => app_to_int($close_number)
            ];
        }

        return response()->json(['code'=>200, 'data'=>$mall_app_result]);

    }


}
