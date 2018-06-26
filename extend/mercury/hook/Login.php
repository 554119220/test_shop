<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13 0013
 * Time: 9:15
 */

namespace mercury\hook;


class Login
{
    public function index(&$params)
    {
        echo '我是登录钩子';
    }
}