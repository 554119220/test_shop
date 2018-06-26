<?php
namespace app\seller\controller;
use mercury\factory\Factory;
use app\common\traits\F as Fun;
use mercury\constants\State;
use mercury\auth\api\AuthApi;
use mercury\editor\UEditor;

/**
 * 售后模板
 * @author Lzy
 * @datetime 2017-12-07 14:00:00
 */

class Protection
{
    /**
     * 列表
     * @return \think\response\View
     */
    public function index()
    {
        // dump(Factory::instance('/goods/v1/GoodsProtectionTpl/index')->run());
        return view('',[
            'Protection' => Factory::instance('/goods/v1/GoodsProtectionTpl/index')->run()['data'] ?? [],
            'headers' => [
                'headers0' => AuthApi::getInstance('/goods/v1/GoodsProtectionTpl/delete')->createHeaders(),
            ],
        ]);
    }

    /**
     * 创建
     * @return \think\response\View
     */
    public function create()
    {
        return view('',[
            'headers' => [
                'headers0' => AuthApi::getInstance('/goods/v1/GoodsProtectionTpl/create')->createHeaders(),
            ],
        ]);
    }

    /**
     * 修改
     * @return \think\response\View
     */
    public function edit()
    {
        return view('',[
            'Protection' => Factory::instance('/goods/v1/GoodsProtectionTpl/detail')->run()['data'] ?? [],
            'headers' => [
                'headers0' => AuthApi::getInstance('/goods/v1/GoodsProtectionTpl/update')->createHeaders(),
            ],
        ]);
    }
}