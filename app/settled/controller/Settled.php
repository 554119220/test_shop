<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 9:20
 */

namespace app\settled\controller;

use app\common\traits\F;
use think\Controller;
use app\settled\controller\Init;

/**
 * Class Settled
 * @package app\settled\controller\v1
 *
 * 入住
 */
class Settled extends Init
{

    public function _initialize()
    {
        if (!session('user')) {
            //$this->redirect(F::domain('user', '/login'));
        }
    }

    /**
     * 等级检测
     *
     * @return \think\response\View
     */
    public function level()
    {
        $data['level']  = 2;
        $data['auth']   = 1;
        return view('', ['data' => $data]);
    }

    /**
     * 类目
     *
     * @return \think\response\View
     */
    public function category()
    {
        return view();
    }

    /**
     * 品牌资质
     *
     * @return \think\response\View
     */
    public function brand()
    {
        return view();
    }

    /**
     * 商家信息
     *
     * @return \think\response\View
     */
    public function shopInfo()
    {
        return view();
    }

    /**
     * 申请成功
     *
     * @return \think\response\View
     */
    public function complete()
    {
        return view();
    }

    /**
     * 步骤列表
     *
     * @return \think\response\View
     */
    public function steps()
    {
        return view();
    }
}