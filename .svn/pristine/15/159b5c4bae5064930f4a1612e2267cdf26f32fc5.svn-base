<?php

namespace App\Http\Controllers\Eoa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis as Redis;

use App\Models\Eoa\User;


class UserController extends Controller
{


    //登录操作
    public function login (Request $request)
    {

        $user_id = $request->input('username');
        $pwd = $request->input('password');

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

        $token = session()->getId();
        Redis::setex('ST_EOA_USER_ID_' . $token, 86400, $user_id);

        return response()->json(['code'=>200, 'message'=>'登录成功', 'data'=>[
            'token' => $token
        ]]);

    }


    //退出
    public function logout(Request $request)
    {

        $token = $request->input('token');
        Redis::del('ST_EOA_USER_ID_' . $token);
        return response()->json(['code'=>200, 'message'=>'ok']);

    }


    //获取用户登录信息
    public function loginInfo(Request $request)
    {

        $token = $request->input('token');

        if (empty($token)) {
            return response()->json(['code'=>1000001, 'message'=>'缺少必要的token']);
        }

        $user_id = Redis::get('ST_EOA_USER_ID_'.$token);
        $user = User::where('user_id', $user_id)->first();
        if (!$user) {
            return response()->json(['code'=>404, 'message'=>'用户没有找到']);
        }

        return response()->json(['code'=>200, 'message'=>'ok', 'data'=>[
            'name' => $user->user_id,
            'avatar' => '',
            'roles' => []
        ]]);

    }


}
