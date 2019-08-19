<?php

namespace App\Services\Storage\Src;

class TxCos
{

    private static $gateway_url = 'http://region.file.myqcloud.com/files/v2/';

    private static $region = 'sh';

    private static $httpInfo = '';

    private static $curlHandler;

    //计算sign签名的时间参数
    private static $expired = 180;

    // HTTP请求超时时间
    private static $timeout = 60;

    //20M 大于20M的文件需要进行分片传输
    private $max_file_size = 20971520;

    //开发者访问 COS 服务时拥有的用户维度唯一资源标识
    private $appID = '1253722824';

    //开发者项目身份识别 ID
    private $secretID = 'AKID3SingONITRCsNggnY6RbaMxLfnjVPcBo';

    //开发者项目身份密钥
    private $secret_key = 'rEvrF0qtGysnidZVRNKHOWfoxCwkL0iR';

    private $bucket = 'wehub';

    private $container = '';

    public function __construct() {
        $this->container = explode('.', env('DOMAIN'))[0];
    }

    /**
     * 创建目录
     * @param $folder string 目录路径
     * @param null $bizAttr string 目录属性
     * @return array
     */
    public function createFolder($folder, $bizAttr = null) {

        if (!self::isValidPath($this->container.'/'.$folder)) {
            return ['code' => 400, 'message' => '文件路径错误', 'data' => []];
        }

        $folder = $this->normalizerPath($this->container.'/'.$folder, True);
        $folder = $this->cosUrlEncode($folder);
        $expired = time() + self::$expired;
        $url = $this->generateResUrl($folder);
        $signature = $this->createReusableSignature($expired);
        $data = [
            'op' => 'create',
            'biz_attr' => (isset($bizAttr) ? $bizAttr : ''),
        ];

        return $this->send([
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => json_encode($data),
            'header' => [
                'Authorization: ' . $signature,
                'Content-Type: application/json',
            ],
        ]);

    }

    /*
     * 目录列表
     * @param  string  $folder     目录路径，sdk会补齐末尾的 '/'
     * @param  int     $num      拉取的总数
     * @param  int     $order    默认正序(=0), 填1为反序,
     * @param  string  $context   透传字段,用于翻页,前端不需理解,需要往前/往后翻页则透传回来
     */
    public function listFolder($folder, $pattern = 'eListBoth', $num = 20,$order = 0, $context = null) {

        $folder = self::normalizerPath($this->container.$folder, True);

        $path = self::cosUrlEncode($folder);
        $expired = time() + self::$expired;
        $url = self::generateResUrl($path);
        $signature = self::createReusableSignature($expired);

        $data = [
            'op' => 'list',
            'pattern' => $pattern,
            'order' => $order,
            'num' => $num
        ];

        if (isset($context)) {
            $data['context'] = $context;
        }

        return $this->send([
            'url' => $url . '?' . http_build_query($data),
            'method' => 'get',
            'timeout' => self::$timeout,
            'header' => [ 'Authorization: ' . $signature,],
        ]);

    }


    /**
     * 删除目录[不能删除根目录/]
     * @param $folder string 目录路径
     * @return array
     */
    public function delFolder($folder) {

        if (empty($this->bucket) || empty($this->container.$folder)) {
            return ['code' => 400, 'message' => 'bucket or folder is empty'];
        }

        $folder = self::normalizerPath($this->container.$folder, True);
        if ($folder == '/') {
            return ['code' => 400, 'message' => '不能删除根目录'];
        }

        $path = self::cosUrlEncode($folder);
        $signature = self::createNonreusableSignature($path);
        return self::send([
            'url' => self::generateResUrl($path),
            'method' => 'post',
            'data' => json_encode(['op'=>'delete']),
            'header' => [
                'Authorization: ' . $signature,
                'Content-Type: application/json',
            ]
        ]);

    }

    /**
     * 查询目录信息
     * @param $folder string 目录路径
     * @return array
     */
    public function statFolder($folder) {

        $folder = self::normalizerPath($this->container.'/'.$folder, true);
        $path = self::cosUrlEncode($folder);
        $expired = time() + self::$expired;
        $url = self::generateResUrl($path);
        $signature = self::createReusableSignature($expired);

        return self::send([
            'url' => $url . '?' . http_build_query(['op' => 'stat']),
            'method' => 'get',
            'timeout' => self::$timeout,
            'header' => ['Authorization:'. $signature]
        ]);

    }

    /**
     * 文件上传[上传文件小于20M]
     * @param $srcPath string 文件本地路径
     * @param $dstPath string 上传的文件路径
     * @param null $bizAttr 文件属性
     * @param null $insertOnly int 是否覆盖同名文件：0 覆盖 1 不覆盖
     * @return array
     */
    public function upload($srcPath, $dstPath, $bizAttr=null, $insertOnly=null) {

        if (!file_exists($srcPath)) {
            return ['code' => 400, 'message' => '本地文件路径不存在'];
        }

        $dstPath = self::normalizerPath($this->container.'/'.$dstPath, false);
        $srcPath = realpath($srcPath);
        if (filesize($srcPath) >= $this->max_file_size) {
            return ['code' => 400, 'message' => '上传文件不能大于20M'];
        }

        $expired = time() + self::$expired;
        $url = self::generateResUrl($dstPath);
        $signature = self::createReusableSignature($expired);
        $fileSha = hash_file('sha1', $srcPath);

        $data = [
            'op' => 'upload',
            'sha' => $fileSha,
            'biz_attr' => (isset($bizAttr) ? $bizAttr : ''),
            'filecontent' => file_get_contents($srcPath)
        ];

        if (isset($insertOnly) && strlen($insertOnly) > 0) {
            $data['insertOnly'] = (($insertOnly == 0 || $insertOnly == '0' ) ? 0 : 1);
        }

        return self::send([
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => $data,
            'header' => ['Authorization: ' . $signature]
        ]);

    }

    /**
     * 查询文件信息
     * @param $path string 文件路径
     * @return array
     */
    public function stat($path) {

        $path = self::normalizerPath($path);
        $expired = time() + self::$expired;
        $url = self::generateResUrl($path);
        $signature = self::createReusableSignature($expired);

        return self::send([
            'url' => $url . '?' . http_build_query(['op' => 'stat']),
            'method' => 'get',
            'timeout' => self::$timeout,
            'header' => ['Authorization: ' . $signature]
        ]);

    }

    /**
     * 删除文件信息
     * @param $path string 文件路径
     * @return array
     */
    public function delFile($path) {

        if (empty($this->bucket) || empty($path)) {
            return array('code' => 400, 'message' => 'bucket or path is empty');
        }

        $path = self::normalizerPath($path);
        if ($path == '/') {
            return array('code' => 400, 'message' => '不能删除根目录');
        }
        $path = self::cosUrlEncode($path);
        $url = self::generateResUrl($path);
        $signature = self::createNonreusableSignature($path);

        return self::send([
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => json_encode(['op' => 'delete']),
            'header' => [
                'Authorization: ' . $signature,
                'Content-Type: application/json'
            ],
        ]);

    }

    /**
     * 下载文件
     * @param null $dirName
     * @param $fileName
     * @return array|mixed
     */
    public function downLoadFile($fileName, $dirName=null){

        if (empty($fileName)) {
            return array('code' => 400, 'message' => '请输入文件名称');
        }

        if (isset($dirName)) {
            $url = 'http://' . $this->bucket .'-'.$this->appID.'.cos'.self::$region.'.'.'myqcloud.com'.'/'.$dirName.'/'.$fileName;
        }else{
            $url = 'http://' . $this->bucket .'-'.$this->appID.'.cos'.self::$region.'.'.'myqcloud.com'.'/'.$fileName;
        }

        $req = array(
            'url' => $url,
            'method' => 'get',
            'timeout' => self::$timeout,
            'header' => array(
                'Content-Type: application/json',
            ),
        );

        return self::send($req);
    }

    /*
     * 规整文件路径
     * @param  string  $path      文件路径
     * @param  string  $isfolder  是否为文件夹
     */
    private static function normalizerPath($path, $isfolder = False) {
        if (preg_match('/^\//', $path) == 0) {
            $path = '/' . $path;
        }

        if ($isfolder == True) {
            if (preg_match('/\/$/', $path) == 0) {
                $path = $path . '/';
            }
        }

        // Remove unnecessary slashes.
        $path = preg_replace('#/+#', '/', $path);

        return $path;
    }

    /*
     * 路径编码
     * @param  string  $path 待编码路径
     */
    private static function cosUrlEncode($path) {
        return str_replace('%2F', '/',  rawurlencode($path));
    }

    /*
     * 构造URL
     * @param  string  $dstPath
     */
    private function generateResUrl($dstPath) {

        $endPoint = self::$gateway_url;
        $endPoint = str_replace('region', self::$region, $endPoint);
        return $endPoint . $this->appID . '/' . $this->bucket . $dstPath;

    }

    /**
     * 检查路径
     * @param $path
     * @return bool
     */
    private static function isValidPath($path) {

        if (strpos($path, '?') !== false) {
            return false;
        }
        if (strpos($path, '*') !== false) {
            return false;
        }
        if (strpos($path, ':') !== false) {
            return false;
        }
        if (strpos($path, '|') !== false) {
            return false;
        }
        if (strpos($path, '\\') !== false) {
            return false;
        }
        if (strpos($path, '<') !== false) {
            return false;
        }
        if (strpos($path, '>') !== false) {
            return false;
        }
        if (strpos($path, '"') !== false) {
            return false;
        }

        return true;
    }

    /**
     * 多次有效签名
     * @param $expiration
     * @param null $filepath
     * @return string
     */
    public function createReusableSignature($expiration, $filepath = null) {

        $appId = $this->appID;
        $secretId = $this->secretID;
        $secretKey = $this->secret_key;

        if (empty($this->appID) || empty($this->secretID) || empty($this->secret_key)) {
            return array('code' => 400, 'message' => '参数错误');
        }

        if (empty($filepath)) {
            return $this->createSignature($appId, $secretId, $secretKey, $expiration, null);
        } else {
            if (preg_match('/^\//', $filepath) == 0) {
                $filepath = '/' . $filepath;
            }

            return self::createSignature($appId, $secretId, $secretKey, $expiration, $filepath);
        }
    }

    /**
     * 单次有效签名
     * @param $filepath
     * @return array|string
     */
    public function createNonreusableSignature($filepath) {
        $appId = $this->appID;
        $secretId = $this->secretID;
        $secretKey = $this->secret_key;

        if (empty($appId) || empty($secretId) || empty($secretKey)) {
            return array('code' => 400, 'message' => '参数错误');
        }

        if (preg_match('/^\//', $filepath) == 0) {
            $filepath = '/' . $filepath;
        }
        $fileId = '/' . $appId . '/' . $this->bucket . $filepath;

        return $this->createSignature($appId, $secretId, $secretKey, 0, $fileId);
    }

    /**
     * 签名
     * @param $appId
     * @param $secretId
     * @param $secretKey
     * @param $expiration
     * @param $fileId
     * @return string
     */
    private function createSignature($appId, $secretId, $secretKey, $expiration, $fileId) {

        if (empty($secretId) || empty($secretKey)) {
            return array('code' => 400, 'message' => '参数错误');
        }

        $now = time();
        $random = rand();
        $plainText = "a=$appId&k=$secretId&e=$expiration&t=$now&r=$random&f=$fileId&b=$this->bucket";
        //使用 HMAC 方法生成带有密钥的哈希值
        $bin = hash_hmac('SHA1', $plainText, $secretKey, true);
        $bin = $bin.$plainText;

        $signature = base64_encode($bin);
        return $signature;

    }

    /**
     * 发送请求
     * @param $req
     * @return array|mixed
     */
    private static function send($req) {

        $rsp = self::sendRequest($req);
        if ($rsp === false) {
            return array(
                'code' => 400,
                'message' => 'network error',
            );
        }

        $ret = json_decode($rsp, true);
        if ($ret === NULL) {
            return array(
                'code' => 400,
                'message' => $rsp,
                'data' => array()
            );
        }

        return $ret;
    }

    /**
     * send http request
     * @param  array $request http请求信息
     *                   url        : 请求的url地址
     *                   method     : 请求方法，'get', 'post', 'put', 'delete', 'head'
     *                   data       : 请求数据，如有设置，则method为post
     *                   header     : 需要设置的http头部
     *                   host       : 请求头部host
     *                   timeout    : 请求超时时间
     *                   cert       : ca文件路径
     *                   ssl_version: SSL版本号
     * @return string    http请求响应
     */
    public static function sendRequest($request) {
        if (self::$curlHandler) {
            if (function_exists('curl_reset')) {
                curl_reset(self::$curlHandler);
            } else {
                curl_setopt(self::$curlHandler, CURLOPT_URL, '');
                curl_setopt(self::$curlHandler, CURLOPT_HTTPHEADER, array());
                curl_setopt(self::$curlHandler, CURLOPT_POSTFIELDS, array());
                curl_setopt(self::$curlHandler, CURLOPT_TIMEOUT, 0);
                curl_setopt(self::$curlHandler, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt(self::$curlHandler, CURLOPT_SSL_VERIFYHOST, 0);
            }
        } else {
            self::$curlHandler = curl_init();
        }

        curl_setopt(self::$curlHandler, CURLOPT_URL, $request['url']);

        $method = 'GET';
        if (isset($request['method']) &&
            in_array(strtolower($request['method']), array('get', 'post', 'put', 'delete', 'head'))) {
            $method = strtoupper($request['method']);
        } else if (isset($request['data'])) {
            $method = 'POST';
        }

        $header = isset($request['header']) ? $request['header'] : array();
        $header[] = 'Method:'.$method;
        $header[] = 'Connection: keep-alive';

        isset($request['host']) && $header[] = 'Host:' . $request['host'];
        curl_setopt(self::$curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$curlHandler, CURLOPT_CUSTOMREQUEST, $method);
        isset($request['timeout']) && curl_setopt(self::$curlHandler, CURLOPT_TIMEOUT, $request['timeout']);

        if (isset($request['data']) && in_array($method, array('POST', 'PUT'))) {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt(self::$curlHandler, CURLOPT_SAFE_UPLOAD, true);
            }

            curl_setopt(self::$curlHandler, CURLOPT_POST, true);
            array_push($header, 'Expect: 100-continue');

            if (is_array($request['data'])) {
                $arr = self::buildCustomPostFields($request['data']);
                array_push($header, 'Content-Type: multipart/form-data; boundary=' . $arr[0]);
                curl_setopt(self::$curlHandler, CURLOPT_POSTFIELDS, $arr[1]);
            } else {
                curl_setopt(self::$curlHandler, CURLOPT_POSTFIELDS, $request['data']);
            }
        }
        curl_setopt(self::$curlHandler, CURLOPT_HTTPHEADER, $header);

        $ssl = substr($request['url'], 0, 8) == "https://" ? true : false;
        if( isset($request['cert'])){
            curl_setopt(self::$curlHandler, CURLOPT_SSL_VERIFYPEER,true);
            curl_setopt(self::$curlHandler, CURLOPT_CAINFO, $request['cert']);
            curl_setopt(self::$curlHandler, CURLOPT_SSL_VERIFYHOST,2);
            if (isset($request['ssl_version'])) {
                curl_setopt(self::$curlHandler, CURLOPT_SSLVERSION, $request['ssl_version']);
            } else {
                curl_setopt(self::$curlHandler, CURLOPT_SSLVERSION, 4);
            }
        }else if( $ssl ){
            curl_setopt(self::$curlHandler, CURLOPT_SSL_VERIFYPEER,false);   //true any ca
            curl_setopt(self::$curlHandler, CURLOPT_SSL_VERIFYHOST,1);       //check only host
            if (isset($request['ssl_version'])) {
                curl_setopt(self::$curlHandler, CURLOPT_SSLVERSION, $request['ssl_version']);
            } else {
                curl_setopt(self::$curlHandler, CURLOPT_SSLVERSION, 4);
            }
        }
        $ret = curl_exec(self::$curlHandler);
        self::$httpInfo = curl_getinfo(self::$curlHandler);
        return $ret;
    }

    /**
     * bugfix:解决当php版本低于5.5，上传文件内容含@特殊字符时，上传失败的问题
     * @param $fields
     * @return array
     */
    public static function buildCustomPostFields($fields) {
        $disallow = array("\0", "\"", "\r", "\n");
        $body = array();
        foreach ($fields as $key => $value) {
            $key = str_replace($disallow, "_", $key);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$key}\"",
                '',
                filter_var($value),
            ));
        }
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));

        foreach ($body as &$part) {
            $part = "--{$boundary}\r\n{$part}";
        }
        unset($part);
        $body[] = "--{$boundary}--";
        $body[] = '';
        return array($boundary, implode("\r\n", $body));
    }

}