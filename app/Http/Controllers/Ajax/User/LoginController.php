<?php

namespace App\Http\Controllers\Ajax\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis as Redis;

use App\Models\User\StUser;
use App\Models\User\StUserDevices;
use App\Models\Mall\StMall;


class LoginController extends Controller
{


    /**
     * 用户登录接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $mobile = $request->input('mobile');
        $password = $request->input('password');

        if ( empty($mobile) ) {
            return response()->json(['code'=>400, 'message'=>'手机号码不能为空']);
        } else if ( !isMobile($mobile) ) {
            return response()->json(['code'=>400, 'message'=>'手机号格式错误']);
        }

        $user = StUser::where('mobile', $mobile)->first();
        if (!$user) {
            return response()->json(['code'=>404, 'message'=>'用户信息没有找到']);
        } else if ($user->status == 0) {
            return response()->json(['code'=>404, 'message'=>'当前用户已禁用']);
        }

        if ($user->type == 2) {
            $mall = StMall::find($user->mall_id);
            if (!$mall) {
                return response()->json(['code'=>404, 'message'=>'门店信息没有找到']);
            }
        }

        if ( empty($password)) {
            return response()->json(['code'=>400, 'message'=>'登录密码不能为空']);
        } else if (!isPwd($password)) {
            return response()->json(['code'=>400, 'message'=>'登录密码格式不正确']);
        } else if (md5($password) != $user->pwd) {
            return response()->json(['code'=>400, 'message'=>'用户密码不正确']);
        }

        Redis::setex('ST_MALL_ID_' . session()->getId(), 86400, $user->mall_id);
        Redis::setex('ST_USER_TYPE_' . session()->getId(), 86400, $user->type);
        Redis::setex('ST_USER_ID_' . session()->getId(), 86400, $user->id);

        return response()->json(['code' => 200, 'message' => '登录成功']);

    }


    /**
     * 用户登出接口
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        Redis::del('ST_MALL_ID_' . session()->getId());
        Redis::del('ST_USER_TYPE_' . session()->getId());
        Redis::del('ST_USER_ID_' . session()->getId());

        return response()->json(['code'=>200, 'message'=>'ok']);

    }


}
