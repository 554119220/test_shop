<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/18 0018
 * Time: 9:13
 */

namespace app\wap\controller;


use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\factory\Factory;

class Comment
{
    /**
     * 评价管理
     *
     * @return \think\response\View
     */
    public function index()
    {
        $ret    = Factory::instance('/goods/v1/userComment/index')->run();
        $headers['comment'] = AuthApi::getInstance('/goods/v1/userComment/index')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 评价详情
     *
     * @return \think\response\View
     */
//    public function detail()
//    {
//        return view();
//    }

    /**
     * 修改评价
     *
     * @return \think\response\View
     */
    public function edit()
    {
        $ret    = Factory::instance('/goods/v1/userComment/detail')->run();
        $headers['edit']  = AuthApi::getInstance('/goods/v1/userComment/modify')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 追加评价
     *
     * @return \think\response\View
     */
    public function append()
    {
        $ret    = Factory::instance('/goods/v1/userComment/detail')->run();
        $headers['append']  = AuthApi::getInstance('/goods/v1/userComment/append')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }
}