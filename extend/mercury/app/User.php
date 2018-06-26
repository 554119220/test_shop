<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 16:54
 */

namespace mercury\app;


use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;

class User
{
    protected $map  = [];
    public function __construct($app_id, $openid)
    {
        $this->map['app_user_openid']   = $openid;
        $this->map['app_id']            = $app_id;
        $this->map['app_user_state']    = State::STATE_NORMAL;
    }

    public static function instance($app_id, $openid)
    {
        return new static($app_id, $openid);
    }

    /**
     * @title getUserInfo
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
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getUserInfo()
    {
        try {
            $user_id= db('app_user')
                ->where($this->map)
                ->cache(true)
                ->value('user_id');
            if (!$user_id) throw new ResponseException(Code::CODE_SIGN_FAIL, '用户不存在');
            $user   = db('user')
                ->where(['user_id' => $user_id])
                ->find();
            if (!$user) throw new ResponseException(Code::CODE_SIGN_FAIL, '用户不存在!');
            return $user;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}