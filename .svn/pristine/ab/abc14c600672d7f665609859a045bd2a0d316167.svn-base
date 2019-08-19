<?php

/**
 * 生成UUID（唯一）
 * @return string
 */
function makeUuid()
{
    $address = strtolower('localhost' . '/' . '127.0.0.1');
    list ( $usec, $sec ) = explode(" ", microtime());
    $time = $sec . substr($usec, 2, 3);
    $random = rand(0, 1) ? '-' : '';
    $random = $random . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(100, 999) . rand(100, 999);
    $uuid = strtoupper(md5($address . ':' . $time . ':' . $random));
    $uuid = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20);
    $uuid = str_replace("-", "", $uuid);
    return $uuid;
}

/**
 * 检测变量是否是手机号码
 * 手机号码必须是11位的数字，第一位数字必须为1，第二数字必须是34568中的任意一个
 * @param string $val 手机号码
 * @return bool
 */
function isMobile($val) {
    return preg_match('/^1\d{10}$/', $val);
}

/**
 * 检测变量是否是座机号码
 * 3-4位区号，7-8位直播号码，1－4位分机号
 * @param string $val 座机号码
 * @return bool
 */
function isPhone($val) {
    return preg_match('/^(0[0-9]{2,3}-)?([2-9][0-9]{6,7})+(-[0-9]{1,4})?$/', $val);
}

/**
 * 检测变量是否是密码
 * 密码只能是6-30位英文、数字及“_”、“-”组成
 * @author 刘道健
 * @param string $val 密码
 * @return bool
 */
function isPwd($val) {
    return preg_match('/^[\w-]{6,30}$/', $val);
}

/**
 * 检测变量是否是邮件地址
 * @author 刘道健
 * @param string $email email
 * @return bool
 */
function isEmail($email) {
    return preg_match('/^[\w-]+(\.[\w-]+)*\@[A-Za-z0-9]+((\.|-|_)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/', $email);
}

/**
 * 把一些预定义的字符转换为 HTML 实体
 * @param string $str
 * @return string
 */
function convertVar($str) {

    if (!isset($str) || empty($str))
        return null;
    return htmlspecialchars(trim($str));
}


/**
 * 隐藏手机号码中间四位数字
 * @param int $mobile 手机号码
 * @return mixed
 */
function hideMobile($mobile) {

    return substr_replace($mobile, '****', 3, -4);
}

/**
 * 检查是否是整数
 * @param string $val 值
 * @param int $type 默认为空【1.大于0的整数 2.大于等于的整数 3.小于0的整数 4.小于等于0的整数】
 * @return bool
 */
function ebsig_is_int($val, $type = 1) {

    if (ceil($val) != $val)
        return false;

    if ($type == 1 && $val <= 0)
        return false;
    else if ($type == 2 && $val < 0)
        return false;
    else if ($type == 3 && $val >= 0)
        return false;
    else if ($type == 4 && $val > 0)
        return false;

    return true;
}



/**
 * 检测变量是否是金额
 * @param string $val 金额
 * @param int $type 默认为空【1.大于0的金额 2.大于等于的金额 3.小于0的金额 4.小于等于0的金额】
 * @return bool
 */
function isMoney( $val, $type = null ) {

    if (!preg_match('/^\d*(\.\d{1,2})?$/', $val))
        return false;

    if ($type == 1 && $val <= 0)
        return false;
    else if ($type == 2 && $val < 0)
        return false;
    else if ($type == 3 && $val >= 0)
        return false;
    else if ($type == 4 && $val > 0)
        return false;

    return true;

}

/**
 * 检测变量是否是日期或日期+时间
 * @param $val
 * @return bool
 */
function is_true_date( $val ) {
    return preg_match('/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/', $val);
}

/**
 * 判断是否是正确的url
 * @param string $url
 * @return bool
 */
function is_url($url) {

    return preg_match('/^http[s]?:\/\/'.
        '(([0-9]{1,3}\.){3}[0-9]{1,3}'. // IP形式的URL- 199.194.52.184
        '|'. // 允许IP和DOMAIN（域名）
        '([0-9a-z_!~*\'()-]+\.)*'. // 三级域验证- www.
        '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.'. // 二级域验证
        '[a-z]{2,6})'.  // 顶级域验证.com or .museum
        '(:[0-9]{1,4})?'.  // 端口- :80
        '((\/\?)|'.  // 如果含有文件对文件部分进行校验
        '(\/[0-9a-zA-Z_!~\*\'\(\)\.;\?:@&=\+\$,%#-\/]*)?)$/',
        $url) == 1;

}

/**
 * 中国GCJ02坐标---->百度地图BD09坐标
 * @param double $lat 纬度
 * @param double $lng 经度
 * @return array
 */
function convertDCJ02ToBD09($lat, $lng) {
    $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    $x = $lng;
    $y = $lat;
    $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
    $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
    $lng = $z * cos($theta) + 0.0065;
    $lat = $z * sin($theta) + 0.006;
    return array( $lat, $lng);
}

/**
 * redis数组转换
 * @param array|string $data
 * @param string $type
 * @param int $timeout 过期时间
 * @return mixed
 */
function redisTransformation($data, $type='get', $timeout = 0) {

    if (empty($data)) {
        return null;
    }
    if (!in_array($type, ['get', 'set'])) {
        return null;
    }

    if ($type == 'get') {
        $data = \GuzzleHttp\json_decode($data, true);
        $sc_timestamp = $data['SC_TIMESTAMP'];
        $sc_timeout = $data['SC_TIMEOUT'];
        if (($sc_timeout == -1 || $sc_timeout == 0) || ($sc_timeout > 0 && time() - $sc_timestamp <= $sc_timeout)) {
            return $data['SC_VALUE'];
        } else {
            return null;
        }
    } else {
        $tmp = [
            'SC_TIMESTAMP' => time(),
            'SC_TIMEOUT' => $timeout,
            'SC_VALUE' => $data
        ];
        return \GuzzleHttp\json_encode($tmp);
    }

}

/**
 * 生成任意长度的随机字符串
 * @param int $len 长度
 * @param string $format 格式 mixed：混合 number：数字
 * @return string
 */
function get_random_string( $len, $format = 'mixed' ) {

    if ($format == 'number') {
        $chars = array(
            '0', '1', '2', '3', '5', '6', '7', '8', '9'
        );
    } else {
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', '0', '1', '2', '3', '5', '6', '7',
            '8', '9'
        );
    }

    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱

    $output = '';
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }

    return $output;

}

/**
 * 生成流水号
 * @param string $name 流水号名称
 * @param null|string $prefix 前缀
 * @param bool $is_transaction 使用事务
 * @return null|string
 */
function generate_seqno($name, $prefix=null, $is_transaction = false) {

    $seq_no = null;
    $seq_no_ge = null;

    if (!$is_transaction) {
        App\Models\SysSeqno::where(['name'=>$name])->increment('seqno', 1);
        $seq_no_data = $seqno_data = App\Models\SysSeqno::where('name',$name)->first();
        if ( !is_null($seq_no_data) ) {
            $seq_no = $seq_no_data['seqno'];
        }
    } else {

        try {
            //开始事务
            DB::beginTransaction();

            App\Models\SysSeqno::where(['name'=>$name])->increment('seqno', 1);
            $seq_no_data = $seqno_data = App\Models\SysSeqno::where('name',$name)->first();
            if ( !is_null($seq_no_data) ) {
                $seq_no = $seq_no_data['seqno'];
            }

            //提交事务
            DB::commit();
        } catch (Exception $e) {

            //事务回滚
            DB::rollBack();

            $seq_no = null;
        }
    }

    if ( is_null($seq_no) ) {
        return null;
    }

    switch ($name) {
        case 'seqno_bill_goods':
            $seq_no_ge = date('Ymd') . str_repeat('0', 10 - strlen($seq_no)) . $seq_no;
            break;

        case 'seqno_card_no':
            $seq_no_ge = date('ymd') . str_repeat('0', 7 - strlen($seq_no)) . $seq_no;
            break;

        case 'seqno_bill':
            $seq_no_ge = date('Ymd') . str_repeat('0', 10 - strlen($seq_no)) . $seq_no;
            break;

        case 'seqno_refund':
            $project_id = defined('G_PROJECT_ID') ? G_PROJECT_ID : '1';
            $seq_no_ge = $project_id . str_repeat('0', 7 - strlen($seq_no)) . $seq_no;
            break;

        default:
            $seq_no_ge = $seq_no;
            break;
    }
    if (!is_null($prefix))
        $seq_no_ge = $prefix . $seq_no_ge;

    return $seq_no_ge;

}

/**
 * 返回int类型的值
 * @param mixed $val   数据
 * @param int $default 数据默认值
 * @return int
 */
function app_to_int($val, $default = 0) {
    if (!isset($val)) {
        return $default;
    } else {
        return intval($val);
    }
}

/**
 * 返回string类型的值
 * @param mixed $val  	数据
 * @param string $default 默认值
 * @return string
 */
function app_to_string($val, $default = '') {

    if (!isset($val)) {
        return $default;
    } else {
        if (is_null($val) || $val === '') {
            return $default;
        }
        return strval($val);
    }

}

/**
 * 返回string类型的值
 * @param mixed $val  	   数据
 * @param float $default  默认值
 * @param int   $length   格式化数据长度
 * @return float
 */
function app_to_decimal($val, $default = 0.00, $length = 2) {
    if (!isset($val)) {
        return $default;
    } else {
        return floatval(round($val,$length));
    }
}

/**
 * 返回float类型的值
 * @param mixed $val  		数据
 * @param float $default   默认值
 * @return float
 */
function app_to_float($val,$default=0.0) {
    if (!isset($val)) {
        return $default;
    } else {
        return floatval($val);
    }
}

/**
 * 返回bool类型的值
 * @param mixed $val  	 数据
 * @param bool $default 数据默认值
 * @return bool
 */
function app_to_bool($val, $default = false) {
    if (!isset($val)) {
        return $default;
    } else {
        if (empty($val)) {
            return false;
        } else {
            return true;
        }
    }
}

/**
 * 获取会员登录信息
 * @param string $session_id session_id
 * @param int $platform 平台 6、小程序
 * @return array|null
 */
function get_customer_login($session_id, $platform) {

    if ($platform == 6) { //小程序登录信息
        $customer_redis = app('redis')->get('WX_APPLET_USER_' . $session_id);
        if ( $customer_redis ) {
            $customer = json_decode($customer_redis,true);
            if ($customer['pcustID']) {
                return [
                    'pcustID' => $customer['pcustID'],
                    'custID' => ''
                ];
            }
        }
    } else {
        $customer_redis = redisTransformation(app('redis')->get($session_id . 'LOGINSESSION'));
        
        if ($customer_redis) {
            return [
                'pcustID' => $customer_redis['pcustID'],
                'custID' => $customer_redis['custID'],
            ];
        }
    }

    return [
        'pcustID' => 0,
        'custID' => ''
    ];

}

/**
 * 获取会员号
 * @param string $session_id session_id
 * @param int $platform 平台 6、小程序
 * @return mixed
 */
function get_customer_id($session_id, $platform) {
    $customer_login = get_customer_login($session_id, $platform);
    return $customer_login['pcustID'];
}

/**
 * 获取会员名
 * @param string $session_id session_id
 * @param int $platform 平台 6、小程序
 * @return mixed
 */
function get_customer_name($session_id, $platform) {
    $customer_login = get_customer_login($session_id, $platform);
    return $customer_login['custID'];
}

/**
 * 获取系统配置
 * @param string $key 配置索引
 * @param mixed $default 未找到配置默认返回
 * @return null
 */
function get_config($key, $default=null) {

    $value = app('redis')->get('wdh.config.' . $key);
    if (!is_null($value)) {
        return $value;
    }

    $config = \App\Models\System\SystemConfig::select('config_value')->where('config_key', $key)->first();
    if ($config) {
        app('redis')->setex('wdh.config.' . $key, 604800, $config->config_value);
        return $config->config_value;
    }

    return $default;

}

/**
 * 获取客户端ip地址
 * @return string
 */
function get_ip() {
    if ( getenv( "HTTP_REMOTEIP" ) && strcasecmp( getenv( "HTTP_REMOTEIP" ), "unknown" ) ) {
        $ip = getenv( "HTTP_REMOTEIP" );
    } else if ( getenv( "HTTP_CLIENT_IP" ) && strcasecmp( getenv( "HTTP_CLIENT_IP" ), "unknown" ) ) {
        $ip = getenv( "HTTP_CLIENT_IP" );
    } else if ( getenv( "HTTP_X_FORWARDED_FOR" ) && strcasecmp( getenv( "HTTP_X_FORWARDED_FOR" ), "unknown" ) ) {
        $ip = getenv( "HTTP_X_FORWARDED_FOR" );
    } else if (getenv( "REMOTE_ADDR" ) && strcasecmp( getenv( "REMOTE_ADDR" ), "unknown" ) ) {
        $ip = getenv( "REMOTE_ADDR" );
    } else if ( isset( $_SERVER[ 'REMOTE_ADDR' ] ) && $_SERVER[ 'REMOTE_ADDR' ]
        && strcasecmp( $_SERVER[ 'REMOTE_ADDR' ], "unknown" ) ) {
        $ip = $_SERVER[ 'REMOTE_ADDR' ];
    } else {
        $ip = "unknown";
    }
    if ( strpos( $ip, ',' ) ){
        $ipArr = explode( ',', $ip );
        $ip = $ipArr[ 0 ];
    }
    return( $ip );
}

/**
 * 生成pc端商品详情页链接地址
 * @param int $id 商品网购号
 * @return string
 */
function set_goods_link($id) {
    return '/shop/goods/' . $id . '.html';
}

/**
 * 生成wap端商品详情页链接地址
 * @param int $id 商品网购号
 * @return string
 */
function set_wap_goods_link($id) {
    return '/wap/goods-' . $id . '.html';
}

/**
 * 分页程序
 * @param int $pageIndex 当前页数
 * @param int $count 总数量
 * @param int $limit 每页显示数量
 * @param string $link 分页链接，链接里的页码部分用%d代替，在本方法中会用sprintf函数替换%d为页码。
 * @param string $tpl 分页模板
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
 */
function page($pageIndex, $count, $limit, $link, $tpl = 'page.page') {

    $pageCount = ceil($count / $limit);
    if ($pageCount == 1)
        return null;

    $pageLinks = [];
    if ($pageIndex > 1) {
        $pageLinks['previous']['link'] = sprintf($link, $pageIndex - 1);
        $pageLinks['previous']['page'] = $pageIndex - 1;
    }
    if ($pageIndex < $pageCount) {
        $pageLinks['next']['link'] = sprintf($link, $pageIndex + 1);
        $pageLinks['next']['page'] = $pageIndex + 1;
    }
    $i = 1;
    while ($i <= $pageCount) {
        $pageLinks['link'][] = array('href' => sprintf($link, $i), 'text'=> $i);
        if ($pageIndex - 3 > $i) {
            $pageLinks['link'][] = array('href' => '', 'text'=> '...');
            $i = $pageIndex - 3;
        } else if ($i < $pageCount && $pageIndex + 3 < $i && $pageCount - 1 > $i) {
            $pageLinks['link'][] = array('href'=>'', 'text'=>'...');
            $i = $pageCount - 1;
        }
        $i++;
    }
    $pageLinks['pageIndex'] = $pageIndex;
    $pageLinks['total'] = $count;
    $pageLinks['pageCount'] = $pageCount;
    $pageLinks['skip_link'] = $link;

    return view($tpl, ['pageLinks'=>$pageLinks]);

}

/**
 * 判断是否来自客户端访问(.NET)
 * @return bool
 */
function is_client() {
    if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], '.NET CLR')) {
        return true;
    } else {
        return false;
    }
}