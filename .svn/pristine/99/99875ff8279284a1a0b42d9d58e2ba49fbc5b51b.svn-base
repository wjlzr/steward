<?php

namespace App\Http\Controllers\Eoa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis as Redis;

use App\Models\Eoa\User;


class LoginController extends Controller
{


    //登录操作
    public function login (Request $request)
    {

        $user_id = $request->input('username');
        $pwd = $request->input('pwd');

        if ( !isset($user_id) || empty($user_id) ) {
            return response()->json(['code' => 100001, 'message' => '登录账号不能为空']);
        }

        if ( !isset($pwd) || empty($pwd)) {
            return response()->json(['code' => 100002, 'message' => '登录密码不能为空']);
        }

        if ( isset($pwd) && !isPwd($pwd)) {
            return response()->json(['code' => 100003, 'message' => '登录密码格式不正确']);
        }

        $user = User::where('user_id', $user_id)->first();

        if (!$user) {
            return response()->json(['code' => 100004, 'message' => '该用户不存在']);
        } else if ($user->status == 0) {
            return response()->json(['code' => 100004, 'message' => '该用户已被禁用，请联系管理员']);
        }

        if ( $user->pwd != md5($pwd)) {
            return response()->json(['code' => 100005, 'message' => '密码不正确']);
        }

        Redis::setex('ST_DEV_USER_ID_' . session()->getId(), 86400, $user->user_id);

        return response()->json(['code'=>200, 'message'=>'登录成功', 'data'=>[]]);

    }


}
