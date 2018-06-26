<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 16:52
 */

namespace app\common\behavior;


use mercury\app\User;
use mercury\constants\Code;
use app\common\traits\F;
use mercury\constants\State;
use mercury\required\Validation;
use mercury\ResponseException;
use think\Exception;
use think\Request;

/**
 * Class ApiAuth
 * @package app\common\behavior
 *
 * Api调用认证
 */
class AppAuth
{
    /**
     * 不需要认证token的控制器
     */
    const NO_AUTH_TOKEN_CONTROLLERS    = [
        'Token'
    ];

    /**
     * 不需要认证登陆的控制器
     */
    const NO_AUTH_CONTROLLERS  = [
        'Token'
    ];

    /**
     * 不需要认证签名的控制器
     */
    const NO_AUTH_SIGN_CONTROLLERS  = [

    ];


    public function run(&$params)
    {
        //app auth code
        //判断APP是否存在
        //判断签名
        //判断token
        //判断是否需要登陆
        //频繁请求限制
        try {
            $controller = request()->controller();
//            F::gearmanLogs('debug', array_merge(request()->param(), $data));
            //绑定提交数据
            $isCheck    = true;
            if (strpos($controller, 'Work') === 0 || strpos($controller, 'analyze') !== false) {
                $isCheck= false;
                $data   = request()->param();
            } else {
                $data   = json_decode(htmlspecialchars_decode(request()->param('body')), true);
                if (!isset($data['app_id']) || empty($data['app_id']) ||
                    !isset($data['app_key']) || empty($data['app_key']) ||
//                    ($controller != 'Tools.v1.token' && (!isset($data['access_token']) || empty($data['access_token']))) ||
                    ($controller != 'Tools.v1.token' && (!isset($data['sign']) || empty($data['sign']))))
                    throw new ResponseException(Code::CODE_MISSING_PARAMETER);
            }
            #   过滤空值参数
//            request()->bind('data', array_filter($data, function ($val) {
//                if ($val !== '') return $val;
//            }));
            request()->bind('data', $data);
            if ($isCheck) {
                $verify = \mercury\app\Auth::instance(request()->data)->verify();
                if ($verify !== true) throw new ResponseException($verify['code'], $verify['msg']);
            }
F::writeLog($data);
            config('default_return_type', $data['format'] ?? 'json');
            //绑定用户数据
            if ($isCheck && isset($data['openid'])) {
                if (!session('user')) {
                    $user   = User::instance($data['app_id'], $data['openid'])->getUserInfo();
                    if (isset($user['code']) && $user['code'] != Code::CODE_SUCCESS)
                        throw new ResponseException($user['code'], $user['msg']);
                    request()->bind('user', $user);
                }
            }

            //参数验证
//            if (isset(request()->data['body'])) request()->bind('data', request()->data['body']);
            $verify = Validation::getInstance(request()->controller(), request()->action())->check(request()->data);
            if (true !== $verify) {
                if ($verify == Code::CODE_UNAUTHORIZED) throw new ResponseException($verify);
                throw new ResponseException(\mercury\constants\Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY, $verify);
            }

        } catch (Exception $e) {
            //有可能需要xml格式返回
            $data   = ['code' => $e->getCode(),
                'msg' => $e->getMessage()];
            exit(json_encode($data, JSON_UNESCAPED_UNICODE));
        } catch (ResponseException $e) {
            exit(json_encode($e->getData(), JSON_UNESCAPED_UNICODE));
        }
    }
}