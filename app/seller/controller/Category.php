<?php
namespace app\seller\controller;
use mercury\factory\Factory;
use app\common\traits\F as Fun;
use mercury\constants\State;
use mercury\auth\api\AuthApi;
use mercury\editor\UEditor;

/**
 * 商品管理
 * @author Lzy
 * @datetime 2017-12-07 14:00:00
 */

class Category
{
    /**
     * 商品管理
     * @return \think\response\View
     */
    public function index()
    {
        // dump(Factory::instance('/goods/v1/ShopGoodsCategory/index')->run());exit;
        return view('',[
            'list'      => Factory::instance('/goods/v1/ShopGoodsCategory/index')->run()['data'] ?? [],
            'headers'   => [
                'headers0' => AuthApi::getInstance('/goods/v1/ShopGoodsCategory/delete')->createHeaders(),
            ],
        ]);
    }

    /**
     * 新增
     * @return [type] [description]
     */
    public function create(){
        return view('',[
            'list'      => Factory::instance('/goods/v1/ShopGoodsCategory/index')->run()['data'] ?? [],
            'headers'   => [
                'headers0' => AuthApi::getInstance('/goods/v1/ShopGoodsCategory/create')->createHeaders(),
            ],
        ]);
    }

    /**
     * 修改
     * @return [type] [description]
     */
    public function update(){
        // dump(Factory::instance('/goods/v1/ShopGoodsCategory/detail')->run());
        return view('',[
            'list'      => Factory::instance('/goods/v1/ShopGoodsCategory/index')->run()['data'] ?? [],
            'detail'    => Factory::instance('/goods/v1/ShopGoodsCategory/detail')->run()['data'] ?? [],
            'headers'   => [
                'headers0' => AuthApi::getInstance('/goods/v1/ShopGoodsCategory/update')->createHeaders(),
            ],
        ]);
    }
}