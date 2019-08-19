<?php

namespace App\Http\Controllers\Develop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis as Redis;

use App\Models\User\StDevUser;


class LoginController extends Controller
{

    //登录页
    public function index ()
    {

        return view('develop/login');

    }

    //登录操作
    public function login (Request $request)
    {

        $user_id = $request->input('user_name');
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

        $user_dev = StDevUser::where('user_id', $user_id)->first();

        if (!$user_dev) {
            return response()->json(['code' => 100004, 'message' => '该用户不存在']);
        } else if ($user_dev->status == 0) {
            return response()->json(['code' => 100004, 'message' => '该用户已被禁用，请联系管理员']);
        }

        if ( $user_dev->pwd != md5($pwd)) {
            return response()->json(['code' => 100005, 'message' => '密码不正确']);
        }

        Redis::setex('ST_DEV_USER_ID_' . session()->getId(), 86400, $user_dev->user_id);

        return response()->json(['code'=>200, 'message'=>'登录成功', 'data'=>[]]);

    }

    //退出
    public function logout()
    {

        Redis::del('ST_DEV_USER_ID_' . session()->getId());
        header('Location: /develop/login');

    }
    
}
