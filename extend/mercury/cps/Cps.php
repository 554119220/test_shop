<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15 0015
 * Time: 18:01
 */

namespace mercury\cps;

use app\common\traits\F;

class Cps
{
    protected $data = [], $api, $response;

    public static $instance = [];

    public function __construct($api, array $data = [])
    {
        $this->api  = $api;
        $this->data = $data;
    }

    public function request()
    {
        $this->response = $this->curl($this->stitchingApi(), $this->enData());
        return $this;
    }
    
    public function toArray()
    {
        return json_decode($this->response, true);
    }

    public function __toString()
    {
        return $this->response;
    }

    public function __invoke()
    {
        return $this->toArray();
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : '';
    }

    public function __set($name, $value)
    {
        return $this->data[$name] = $value;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($name, $value)
    {
        return $this->data[$name] = $value;
    }
    
    /**
     * @title instance
     * @param $api
     * @param array $data
     * @return mixed
     */
    public static function instance($api, array $data = [])
    {
        $key    = md5($api . http_build_query($data));
        if (!isset(self::$instance[$key]) ||
        false == self::$instance[$key] instanceof self) self::$instance[$key] = new static($api, $data);
        return self::$instance[$key];
    }
    
    /**
     * @title curl
     * @param $url
     * @param $data
     * @param bool $header
     * @return mixed
     * @throws \Exception
     */
    protected function curl($url, $data, $header = true)
    {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            if ($header) {
                $content_length = strlen($data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type : application/json',
                    "Content-length : {$content_length}"
                ]);
            }
            $res    = curl_exec($ch);
            if ($res) {
                curl_close($ch);
                F::gearmanLogs('cps', [
                    'res'   => json_decode($res, true),
                    'url'   => $url,
                    'data'  => !empty($data) ? json_decode($data, true) : [],
                ], true);
                return $res;
            } else {
                $error  = curl_error($ch);
                curl_close($ch);
                F::gearmanLogs('cps_fail', [
                    'res'   => $res,
                    'url'   => $url,
                    'data'  => !empty($data) ? json_decode($data, true) : [],
                    'error' => $error
                ], true);
                throw new \Exception($error);
            }
    }

    /**
     * @title enData
     * @return string
     */
    protected function enData()
    {
        return json_encode($this->data);
    }

    /**
     * @title stitchingApi
     * @return string
     */
    protected function stitchingApi()
    {
        if (strpos($this->api, 'http') === 0) return $this->api;
        $host   = Config::HOST;
        return "{$host}{$this->api}";
    }

    /**
     * @title __call
     * @param $method
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args = [])
    {
        if (is_callable([$this, $method])) {
            return call_user_func_array([$this, $method], $args);
        }
        throw new \Exception("{$method} not in this class");
    }
}