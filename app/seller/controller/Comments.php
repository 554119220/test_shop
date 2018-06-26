<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5 0005
 * Time: 16:15
 */

namespace app\seller\controller;


use app\common\traits\F;
use mercury\factory\Factory;
use mercury\auth\api\AuthApi;

class Comments
{
    /**
     * 评价管理
     *
     * @return \think\response\View
     */
    public function index()
    {
        $ret    = Factory::instance('/goods/v1/comment/index')->run();
        // dump($ret);exit;
        return view('', ['data' => $ret, 'headers'   => [
            'headers0'  => AuthApi::getInstance('/goods/v1/Comment/reply')->createHeaders(),
        ]]);
    }

    /**
     * 评价申诉
     *
     * @return \think\response\View
     */
    public function appeal()
    {
        // return view();
    }

    /**
     * 评价回复
     *
     * @return \think\response\View
     */
    public function reply()
    {
        request()->get(['goods_comment_id' => input('id')]);
        // dump(Factory::instance('/goods/v1/Comment/detail')->run());
        return view('',[
            'detail' => Factory::instance('/goods/v1/Comment/detail')->run()['data'] ?? [],
            'headers'   => [
                'headers0'  => AuthApi::getInstance('/goods/v1/Comment/reply')->createHeaders(),
            ],
        ]);
    }

    /**
     * 评价详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        request()->get(['goods_comment_id' => input('id')]);
        // dump(Factory::instance('/goods/v1/Comment/detail')->run());
        return view('',[
            'detail' => Factory::instance('/goods/v1/Comment/detail')->run()['data'] ?? [],
            'headers'   => [
                // 'headers0'  => AuthApi::getInstance('/goods/v1/Comment/reply')->createHeaders(),
            ],
        ]);
    }

    public function ajaxReply()
    {
        return view('', [
            'detail' => Factory::instance('/goods/v1/Comment/detail')->run()['data'] ?? [],
        ]);
    }
}