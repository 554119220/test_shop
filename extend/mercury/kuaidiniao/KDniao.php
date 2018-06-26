<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 16:27
 */

namespace mercury\kuaidiniao;


abstract class KDniao
{
    protected $com, $code, $config = [];
    const RESPONSE_JSON = 2;
    const RESPONSE_XML  = 1;
    const RESULT_ARRAY  = true;
    public static $instance;
    public function __construct(array $config = [])
    {
        $this->config['app_id']     = isset($config['app_id']) ? $config['app_id'] : Config::APP_ID;
        $this->config['app_key']    = isset($config['app_key']) ? $config['app_key'] : Config::APP_KEY;
        $this->config['app_url']    = isset($config['app_url']) ? $config['app_url'] : Config::API_URL;
    }

    /**
     * 获取单例
     *
     * @param array $config
     * @return static
     */
    public static function instance(array $config = [])
    {
        if (false == self::$instance instanceof self) self::$instance = new static($config);
        return self::$instance;
    }

    /**
     * request api
     *
     * @param $url
     * @param array $data
     * @return string
     */
    protected function request($url, array $data = [])
    {
        $url    = strpos('http', $url) === 0 ? $url : Config::API_URL . $url;
        $tmp    = [];
        foreach ($data as $key => $value) {
            $tmp[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $tmp);
        $url_info = parse_url($url);
        if(!isset($url_info['port']) || empty($url_info['port'])) $url_info['port']=80;

        $http_header    = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $http_header   .= "Host:" . $url_info['host'] . "\r\n";
        $http_header   .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $http_header   .= "Content-Length:" . strlen($post_data) . "\r\n";
        $http_header   .= "Connection:close\r\n\r\n";
        $http_header   .= $post_data;
        $fd     = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $http_header);
        $res    = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $res   .= fread($fd, 128);
        }
        fclose($fd);
        if (self::RESULT_ARRAY) $res = json_decode($res, true);
        return $res;
    }
    
    /**
     * 获取请求json
     *
     * @return string
     */
    protected function getRequestJson($encode = true)
    {
        $data   = [
            'OrderCode'     => '',
            'ShipperCode'   => $this->com,
            'LogisticCode'  => $this->code
        ];
        $data   = json_encode($data);
        if ($encode) $data  = urlencode($data);
        return $data;
    }
    

    /**
     * 加密
     *
     * @param $data
     * @param $app_key
     * @return string
     */
    protected function encrypt()
    {
        return urlencode(base64_encode(md5($this->getRequestJson(false) . $this->config['app_key'])));
    }
}