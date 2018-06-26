<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/8 0008
 * Time: 11:42
 */

namespace app\welcome\controller;
use mercury\factory\Factory;
use app\common\traits\F as Fun;
use mercury\constants\State;
use mercury\auth\api\AuthApi;
use lbzy\sdk\erp\Erp;
use lbzy\sdk\erp\ErpOauth;

class User
{
    public function login()
    {
        // dump(session('user.erpUser')['level_name']);exit;
        # 登录检测
        if($this->isLogin()){
            return redirect('/');
        }
        # 授权是否成功
        $oauth  = new ErpOauth;
        if ($oauth->oauthCheck(Fun::domain('www','/user/login'))){
            // dump(session('user'));exit;
            return redirect('/');
        }
        // dump(session('user'));exit;
        return view('',[
            'headers' => [
                'headers0' => AuthApi::getInstance('/user/v1/Login/index')->createHeaders(),
            ],
        ]);
    }

    /**
     * 授权登陆跳转
     */
    public function oauth()
    {
        //session('a',1);
        $oauth  = new ErpOauth;
        $url    = $oauth->loginOauthUrl(Fun::domain('www','/user/login'));
        //$url    = $url ? $url : '/user/login';
        $url    = strpos($url, '?') === 0 ? "/user/login{$url}" : $url;
        return redirect($url);
    }

    public function register()
    {
        $erp = new ErpOauth;
        return redirect($erp->registerUrl('www'));
    }

    public function forgot()
    {
        return view('',[
            'headers' => [
                'headers0' => AuthApi::getInstance('/user/v1/Setting/forgot_password')->createHeaders(),
                'headers1' => AuthApi::getInstance('/tools/v1/NoticeTpl/code')->createHeaders(),
            ],
        ]);
    }

    public function logout(){
        session('user',null);
        return redirect('/');
    }

    /**
     * 登录检测
     */
    public function isLogin()
    {
        if ( session('user') ) {
            return true;
        }
    }
}
