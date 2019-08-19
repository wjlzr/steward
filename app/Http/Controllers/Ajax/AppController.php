<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\App\AppSearchService;

class AppController extends Controller
{


    //应用列表查询
    public function search(Request $request)
    {

        $app_search = new AppSearchService();
        $app_result = $app_search->search($request->input());
        return response()->json(['code'=>200, 'message'=>'ok', 'data'=>$app_result]);

    }


}
