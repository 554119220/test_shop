<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 9:34
 */

namespace mercury\app;


use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;

class Auth
{
    protected $data = [], $redis, $app;
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->redis= F::redis();
    }

    /**
     * @title instance
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @param array $data
     * @return static
     */
    public static function instance(array $data)
    {
        return new static($data);
    }

    /**
     * @title verify
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return array|bool
     */
    public function verify()
    {
        try {
            $this->app  = $this->verifyApp();
            if (!$this->app) throw new ResponseException(Code::CODE_APP_DOES_NOT_EXIST);
            if ($this->app['app_verify_access_token'] == State::STATE_NORMAL && request()->controller() != 'Tools.v1.token') {
                if (!$this->verifyAccessToken()) throw new ResponseException(Code::CODE_TOKEN_FAIL);
            }
            if ($this->app['app_verify_sign'] == State::STATE_NORMAL && request()->controller() != 'Tools.v1.token') {
                if (!$this->verifySign()) throw new ResponseException(Code::CODE_SIGN_FAIL);
            }
            if ($this->app['app_verify_permissions'] == State::STATE_NORMAL) {
                if (!$this->verifyPermissions()) throw new ResponseException(Code::CODE_METHOD_NOT_ALLOWED);
            }
            return true;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 验证access token
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return bool
     */
    protected function verifyAccessToken()
    {
        $key    = F::getCacheName(Cache::TOOLS_ACCESS_TOKEN . $this->data['access_token']);
        return $this->redis->exists($key);
    }

    /**
     * @title 验证签名
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return bool
     */
    protected function verifySign()
    {
        $sign   = $this->data['sign'];
//        $this->data['app_secret']   = $this->app['app_secret'];
        unset($this->data['sign']);
        ksort($this->data);

        $arr    = [
            '&amp%3B',
            '&quot%3B',
            '&#039%3B',
            '&apos%3B',
            '&lt%3B',
            '&gt%3B',
            '%26amp%3B',
            '%26quot%3B',
            '%26#039%3B',
            '%26apos%3B',
            '%26lt%3B',
            '%26gt%3B'
        ];
//
        $to = [
            '&',
            '"',
            '\'',
            '\'',
            '<',
            '>',
            '&',
            '"',
            '\'',
            '\'',
            '<',
            '>',
        ];
        $str    = str_replace($arr, $to, http_build_query($this->data)) . $this->app['app_secret'];
        if (isset($this->data['callback_url'])) F::gearmanLogs('debug',
            array_merge($this->data, ['query' => $str, 'query1' => http_build_query($this->data) . $this->app['app_secret']]));
        return md5($str) === $sign;
    }

    /**
     * @title 验证APP
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return bool
     */
    protected function verifyApp()
    {
        $key    = "app:id:{$this->data['app_id']}:{$this->data['app_key']}";
        $app    = $this->redis->get($key);
        if (!$app) {
            $app    = db('app')
                ->where([
                    'app_id'    => $this->data['app_id'],
                    'app_key'   => $this->data['app_key'],
                    'app_state' => State::STATE_NORMAL
                ])->find();
            if (!$app) return false;
            $this->redis->setex($key, 86400, serialize($app));
        }
        if (is_string($app)) $app = unserialize($app);
        return $app;
    }

    /**
     * @title 验证APP权限
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return bool
     */
    protected function verifyPermissions()
    {
        return true;
    }
}