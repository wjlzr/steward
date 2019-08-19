<?php

namespace App\Http\Controllers\Api\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Services\App\AppSearchService;


class AppController extends Controller
{
    
    //查询应用列表
    public function search(Request $request)
    {

        $app_search = new AppSearchService();
        $user_result = $app_search->search($request->input());
        return response()->json(['code' => 200, 'data' => $user_result]);

    }

}

