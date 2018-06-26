<?php
namespace lbzy\sdk\erp;
use app\common\traits\F as Fun;
use app\common\traits\F;
use mercury\constants\Cache;

class Erp
{

    protected $token      	= '';
    public static $instance;
    public $timeout = 30;

    /**
     * 获取单例
     *
     * @return static
     */
    public static function instance()
    {
        if (false == self::$instance instanceof self) self::$instance = new static();
        return self::$instance;
    }

    /**
     * 获取config
     * @return [type] [description]
     */
    public function config()
    {
        return ErpConfig::get();
    }

    /**
     * 获取api请求url
     * @return [type] [description]
     */
    public function apiUrl()
    {
        return ErpConfig::get('apiUrl');
    }

    /**
     * 获取签名配置
     * @return [type] [description]
     */
    public function signConfig()
    {
        return ErpConfig::get('sign');
    }

    /**
     * 生成token
     */
    protected function token()
    {
        $key 	= Fun::getCacheName( Cache::ERP_API_TOKEN ) . session_id();
        $token 	= Fun::redis()->get($key);
        // $token  = '';
        if( empty($token) ) {
            $apiUrl = $this->apiUrl() . '/pc.v1.auth/token';
            $data   = $this->signConfig();
            $res    = json_decode($this->curlPost($apiUrl, $this->sign($data)), true);
            if($res['code'] == 1){
            	$token = $res['data']['token'];
                Fun::redis()->setex($key, 300, $token);
            }
        }
        return $token;
    }

    /**
     * API 请求
     * @param string $api
     * @param array $data
     * @param array $nosign
     * @return mixed
     */
    public function api($api, $data, $nosign = [])
    {
        $apiUrl         = $this->apiUrl() . $api;
        $data['token']  = $this->token();
        return json_decode($this->curlPost($apiUrl, $this->sign($data)), true);
    }

    /**
     * 数据签名
     * @param array $data
     * @param array $nosign   不参与签名字段
     * @return array
     */
    private function sign($data, $nosign = [])
    {
        $nosign	= array_unique(array_merge(['random'], $nosign));
        $data 	= (array) $data;
        foreach($data as $key => $value){
            if(in_array($key, $nosign)){
            	unset($data[$key]);
            }
        }
        ksort($data);
        $data['sign']   = md5( urldecode(http_build_query($data) . '&' . $this->signConfig()['sign_code']) );
        $data['random'] = session_id();
        return $data;
    }

    /**
     * @title verifySign 签名判断
     * @param $data
     * @param array $nosign
     * @return bool
     */
    public function verifySign($data, $nosign = [])
    {
        if (!isset($data['sign'])) return false;
        $data_sign  = $data['sign'];
        unset($data['sign']);
        $sign   = $this->sign($data, $nosign)['sign'];
        return $sign == $data_sign ? true : $sign;
    }

    /**
     * CURL POST
     * @param $url
     * @param $data
     * @param null $param
     * @param int $timeout
     * @return mixed
     */
    private function curlPost($url, $data)
    {
    	// 要访问的地址
        $curl = curl_init($url);
        //curl_setopt($curl, CURLOPT_REFERER, $param['referer']);
        // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 MSIE 8.0');
        //SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        // 过滤HTTP头
        curl_setopt($curl, CURLOPT_HEADER, 0 );
        // 显示输出结果
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // post传输数据
        curl_setopt($curl, CURLOPT_POST,true);
        // post传输数据
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);

        $res = curl_exec($curl);
        F::gearmanLogs('web_erp', array_merge($data, ['api' => $url, 'ret' => json_decode($res, true)]), true);
        //如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
        //var_dump( curl_error($curl) );
        curl_close($curl);
        return $res;
    }

}