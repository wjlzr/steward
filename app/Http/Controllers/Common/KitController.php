<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use Gregwar\Captcha\PhraseBuilder;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis as Redis;

class KitController extends Controller
{

    /**
     * 生成图片验证码
     * @param Request $request
     */
    public function captcha(Request $request)
    {

        $len = $request->input('len', 4);
        $width = $request->input('w', 100);
        $height = $request->input('h', 40);
        $session_id = $request->input('session_id');
        if (!$session_id) {
            $session_id = session()->getId();
        }

        $phrase = new PhraseBuilder;

        //生成图片验证码内容
        $code = $phrase->build($len);

        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder($code, $phrase);

        //可以设置图片宽高及字体
        $builder->build($width, $height, $font = null);

        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        Redis::set($session_id . 'yzm', redisTransformation(md5(strtolower($phrase)), 'set'));

        //生成图片
        header('Content-Type: image/jpeg');
        $builder->output();

    }

}
