<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 16:52
 */

namespace app\common\behavior;

use think\Controller;

/**
 * Class Auth
 * @package app\common\behavior
 *
 * 用户登录认证
 */
class Auth extends Controller
{
    /**
     * 不需要认证登陆的控制器,请注意首字母大写
     */
    const NO_AUTH_CONTROLLERS  = [
        'Login',
        'Register'
    ];

    public function run(&$params)
    {
        //user auth code
        //判断是否登陆
//        if (!in_array(request()->controller(), self::NO_AUTH_CONTROLLERS))
//            if (!session('user')) $this->redirect('/login');
        request()->bind('user', session('user'));
    }
}