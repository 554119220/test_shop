<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31 0031
 * Time: 16:10
 */

namespace app\oauth\controller;


use mercury\constants\Code;
use app\common\traits\F;
use lbzy\sdk\erp\ErpOauth;
use mercury\app\Auth;
use mercury\constants\State;
use mercury\ResponseException;

class Index
{
    protected $app  = [], $app_id, $app_key;
    public function __construct()
    {
        try {
            $flag   = Auth::instance(request()->param())->verify();
            if (is_array($flag)) throw new ResponseException($flag['code'], $flag['msg']);
            $this->app_id   = request()->param('app_id');
            $this->app_key  = request()->param('app_key');
            $this->app  = db('app')->where(['app_id' => $this->app_id, 'app_key' => $this->app_key, 'app_state' => State::STATE_NORMAL])
                ->cache(true)->find();
            if (!$this->app) throw new ResponseException(Code::CODE_OTHER_FAIL, 'app 不存在');
        } catch (ResponseException $e) {
            exit(json_encode($e->getData()));
        }
    }
    
    public function index()
    {
        session('oauth', [
            'app_id'    => $this->app_id,
            'app_key'   => $this->app_key,
            'ret_url'   => request()->param('ret_url')
        ]);
        $o      = new ErpOauth();
        $url    = F::domain('oauth2') . '/?' . http_build_query(input());
        $o->oauthCheck($url);
        $user   = session('user');
        return view('', ['app' => $this->app, 'user' => $user, 'params' => http_build_query(request()->get())]);
    }

    /**
     * @title erp 授权
     */
    public function erpOauth()
    {
        $o      = new ErpOauth();
        $url    = F::domain('oauth2') . '/?' . http_build_query(input());
        $ret    = $o->loginOauthUrl($url);
        if ($ret) {
            return redirect($ret);
        }
        return redirect(F::domain('www'));
    }

    /**
     * @title 获取code
     * @return \think\response\Redirect
     */
    public function code()
    {
        //  生成code
        if (session('oauth')) {
            $code   = md5(uniqid(rand() . microtime(true), true));
            $key    = F::getCacheName('oauth:code:' . $code);
            F::redis()->setex($key, 300, session('user.user_id'));
            $url    = urldecode(session('oauth.ret_url'));
            $j      = strpos($url, '?') !== false ? '&' : '/?';
            $url    = "{$url}{$j}code={$code}";
            return redirect($url);
        }
        return redirect(F::domain('www'));
    }

    /**
     * @title userToken
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |code|string|true|-|-|-|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return \think\response\Json
     */
    public function userToken()
    {
        try {
            $code   = request()->param('code');
            if (!$code) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作');
            $key    = F::getCacheName('oauth:code:' . $code);
            $redis  = F::redis();
            if (!$redis->exists($key)) throw new ResponseException(Code::CODE_OTHER_FAIL, 'code不存在');
            $user_id= $redis->get($key);
            $map    = [
                'user_id'   => $user_id,
                'app_id'    => $this->app_id
            ];
            if (false == $openid = db('app_user')->where($map)->value('app_user_openid')) {
                $map['app_create_time'] = time();
                $map['app_update_time'] = $map['app_create_time'];
                $map['app_user_state']  = State::STATE_NORMAL;
                $map['app_user_openid'] = $openid = md5($code);
                $map['shop_id']         = session('user.user_shop_id') ?? 0;
                if (false == db('app_user')->insert($map))
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '授权失败');
            }
            $ret    = ['data' => ['openid' => $openid], 'code' => Code::CODE_SUCCESS, 'msg' => Code::CODE_ARRAY[Code::CODE_SUCCESS]];
        } catch (ResponseException $e) {
            $ret    = $e->getData();
        }
        return json($ret);
    }


    /**
     * @title 获取用户信息
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
     * @return \think\response\Json
     */
    public function userInfo()
    {
        try {
            $openid = request()->param('openid');
            $map    = [
                'app_user_openid'   => $openid,
                'app_id'            => $this->app_id,
                'app_user_state'    => State::STATE_NORMAL
            ];
            $user_id    = db('app_user')
                ->where($map)
                ->value('user_id');
            if (!$user_id) throw new ResponseException(Code::CODE_OTHER_FAIL, '用户未授权');
            $user   = db('user')->where(['user_id' => $user_id])
                ->field('user_shop_id,user_nick,user_avatar,user_username')
                ->find();
            if (!$user) throw new ResponseException(Code::CODE_OTHER_FAIL, '用户不存在');
            $data   = [
                'shop_id'   => $user['user_shop_id'],
                'app_last_auth_time'    => time(),
            ];
            #   更新状态
            $flag   = db('app_user')->where($map)->update($data);
            if (!$flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新数据失败');

            $ret    = [
                'openid'        => $openid,
                'is_open_shop'  => $user['user_shop_id'] > State::STATE_DISABLED ? State::STATE_NORMAL : State::STATE_DISABLED,
                'nick'          => $user['user_nick'],
                'username'      => $user['user_username'],
                'avatar'        => F::getImages($user['user_avatar'])
            ];
            $ret    = ['code' => Code::CODE_SUCCESS, 'data' => $ret, 'msg' => Code::CODE_ARRAY[Code::CODE_SUCCESS]];
        } catch (ResponseException $e) {
            $ret    = $e->getData();
        }
        return json($ret);
    }
}