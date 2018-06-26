<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:17
 */

namespace app\wap\controller;
use mercury\auth\api\AuthApi;
use mercury\factory\Factory;
use lbzy\sdk\erp\ErpOauth;
use lbzy\sdk\erp\Erp;
use app\common\traits\F as Fun;
use think\Exception;

class User
{


   private function login_old(){
     // function login(){
        return view('loginbase',[
            'login' => AuthApi::getInstance('/user/v1/login/erp2')->createHeaders(),
        ]);
    }


    /**
     * 用户登陆1
     *
     * @return \think\response\View
     */
    function login()
    {
        # APP 登录？
        // Factory::instance('/user/v1/login/appRemember')->run();

        // dump(session('user.erpUser')['level_name']);exit;
        // dump(cookie('loginRefererUrl'));
        # 登录检测
        if($this->isLogin()){
            return redirect('/member');
        }
        # 设置referer cookie
        $this->loginReferer('set');
        # ...
        return view();
    }

    /**
     * 用户登陆2,erp回调的地址
     *
     * @return \think\response\View
     */
    function login2()
    {
        # 授权是否成功
        $oauth  = new ErpOauth;
        if ($oauth->oauthCheck(Fun::domain('wap','/user/login2'))){
            # 是否设置了referer cookie
            $referer = $this->loginReferer('get');
            $referer = $referer ? $referer : '/member';
            return redirect($referer);
        }
        # ...
        return view('login');
    }

    /**
     * 授权登陆跳转
     */
    function oauth()
    {
        $oauth  = new ErpOauth;
        $url    = $oauth->loginOauthUrl(Fun::domain('wap','/user/login2'));
        $url    = $url ? $url : '/user/login';
        return redirect($url);
    }

    /**
     * 用户注册
     *
     * @return \think\response\View
     */
    public function register()
    {
        $erp = new ErpOauth;
        return redirect($erp->registerUrl('wap'));
    }

    /**
     * 用户退出
     *
     * @return \think\response\View
     */
    public function logout()
    {
        session('user',null);
        return redirect('/',302);
    }

    /**
     * 注册成功
     *
     * @return \think\response\View
     */
    public function regSuccess()
    {
        return view();
    }

    /**
     * 登录检测
     */
    private function isLogin()
    {
        return empty(session('user')) ? false: true;
    }

    private function loginReferer($t)
    {
        $refererPath = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_PATH);
        // dump($_SERVER);dump($refererPath);exit;
        $refererHost = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);
        if ( $t == 'set' ) {
            if ( strpos($refererPath, '/user') === 0 || $refererHost != 'wap.' . config('url_domain_root')  ) {
                return null;
            } else {
                return cookie('loginRefererUrl', $_SERVER['HTTP_REFERER']);
            }
        }
        if ( $t == 'get' ) {
            $referer = cookie('loginRefererUrl');
            cookie('loginRefererUrl', null);
            return $referer;
        }
        return null;
    }

    /**
     * app 登录
     * @return [type] [description]
     */
    function appLogin()
    {
        
        # 加载load图
        echo    '<!DOCTYPE html>
                <html>
                <head>
                    <title>正在跳转...</title>
                </head>
                <body>
                    <div style="width:100%;text-align: center;">
                        <img style="margin:auto;max-width:100%" src="/static/wap/images/lazyloading.gif" />
                    </div>
                </body>
                </html>';
        # 验证sign
        $msg   = '';
        try {
            $s = microtime(true);
            $data   = request()->param();
            $author = new \mercury\app\Auth($data);
            $flag = $author->verify();
            if ( true !== $flag ) throw new Exception('签名错误');
            # 获取erp数据
            $openid = request()->param('openid');
            $erp = new Erp;
            $erp->timeout = 3;
            $ss = microtime(true);
            $erpUser = $erp->api('/pc.v1.user.user/getUser',['openid' => $openid]);
            if ( $erpUser['code'] != 1 ) throw new Exception(is_array($erpUser) ? $erpUser['msg'] : json_encode($erpUser));
            $ee = microtime(true);
            # 设置erpUser
            session('erpUser', $erpUser['data']);
            session('is_app',1);
            $session_ee = microtime(true);
            # 登录
            $login = Factory::instance('/user/v1/login/erp')->run();
            if ( $login['code'] != 20000 ) throw new Exception(is_array($login) ? $login['msg'] : json_encode($login));
            $e = microtime(true);
            Fun::gearmanLogs('app_auth', [
                'code_run_time'     => $e - $s,
                'erp_run_time'      => $ee - $ss,
                'login_run_time'    => $e - $ee,
                'session_run_time'  => $session_ee - $ee,
                'sql_run_time'      => $e - $session_ee,
                'openid'            => $openid,
            ]);
        } catch (Exception $e) {
            $msg    = $e->getMessage();
            Fun::writeLogByMongoDb('app_login',['msg'=>$msg,'url'=>$this->appLoginCallback('/', $msg)]);
        }

        # 成功
        return redirect($this->appLoginCallback('/', $msg));
    }

    /**
     * 获取回调链接
     * @return [type] [description]
     */
    private function appLoginCallback($default = '/', $msg = '')
    {
    	$url = request()->param()['callback_url'] ?? '';
    	return $url ? "{$url}&msg={$msg}" : "{$default}?msg={$msg}";
    }
    
}