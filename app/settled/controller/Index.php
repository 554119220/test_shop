<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 9:18
 */

namespace app\settled\controller;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;
use app\common\traits\F;

/**
 * Class Index
 * @package app\settled\controller\v1
 *
 * 招商入住
 */
class Index
{
    /**
     * 入住首页
     *
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }
}