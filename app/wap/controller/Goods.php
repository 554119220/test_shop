<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:15
 */

namespace app\wap\controller;
use app\common\traits\F;
use mercury\common\Seo;
use mercury\factory\Factory;
use mercury\auth\api\AuthApi;
use mercury\weChat\Sdk;

/**
 * Class Goods
 * @package app\wap\controller
 *
 * 商品
 */
class Goods
{
    /**
     * 商品详情
     *
     * @return \think\response\View
     */
    public function index()
    {
    	$id = input('id', 0, 'int');
    	$detail = Factory::instance('/goods/v1/goods/detail')->run()['data'] ?? [];
        // print_r($detail);exit;
        // print_r(Factory::instance('/goods/v1/GoodsProtectionTpl/detail')->run(['protection_id' => $detail['goods']['protection_id'] ?? 0]));exit;
        // print_r(Factory::instance('/goods/v1/GoodsExpressTpl/detail')->run(['express_id' => $detail['goods']['express_id'] ?? 0]));exit;
        if ( empty($detail) ) {
            return redirect('/goods/empty');
        } else {
            return view('', [
                'id'            => $id,
                'data'          => $detail,
                'goods_id'      => $detail['goods_id'] ?? 0,
                'shop_id'       => $detail['goods']['shop_id'] ?? 0,
                'express'       => Factory::instance('/goods/v1/GoodsExpressTpl/detail')->run(['express_id' => $detail['goods']['express_id'] ?? 0])['data'] ?? [],
                'package'       => Factory::instance('/goods/v1/GoodsPackageTpl/detail')->run(['package_id' => $detail['goods']['package_id'] ?? 0])['data'] ?? [],
                'protection'    => Factory::instance('/goods/v1/GoodsProtectionTpl/detail')->run(['protection_id' => $detail['goods']['protection_id'] ?? 0])['data'] ?? [],
                'headers'       => [
                    'headers0'      => AuthApi::getInstance('/goods/v1/goodsContent/detail')->createHeaders(),
                    'headers1'      => AuthApi::getInstance('/goods/v1/goodsParams/detail')->createHeaders(),
                    'headers2'      => AuthApi::getInstance('/goods/v1/GoodsExpressTpl/courier_fees')->createHeaders(),
                    'headers3'      => AuthApi::getInstance('/goods/v1/Shop/detail')->createHeaders(),
                    'headers4'      => AuthApi::getInstance('/goods/v1/GoodsComment/index')->createHeaders(),
                    'headers5'      => AuthApi::getInstance('/goods/v1/Goods/detail')->createHeaders(),
                    'headers6'      => AuthApi::getInstance('/goods/v1/AttentionGoods/create')->createHeaders(),
                    'headers7'      => AuthApi::getInstance('/goods/v1/AttentionGoods/have')->createHeaders(),
                    'buy_now'       => AuthApi::getInstance('/orders/v1/cart/buyNow')->createHeaders(),
                    'add_cart'      => AuthApi::getInstance('/orders/v1/cart/create')->createHeaders(),
                    'statistics'    => AuthApi::getInstance('/goods/v1/GoodsComment/statistics')->createHeaders(),
                ],
                'seo'   => Seo::instance($detail['goods']['goods_name'], $detail['goods']['goods_name'], $detail['goods']['goods_name'])->getSeo(),
                'weChat'=> (new Sdk('', ['url' => F::domain('wap', request()->url())]))->getJsApiParams()
            ]);
        }
    }

    public function empty(){
        return view();
    }

    /**
     * 商品评价
     */
    public function comment(){
        $goods_id = input('goods_id', 0, 'int');
        return view('', [
            'goods_id'      => $goods_id,
            'headers'       => [
                'headers1'      => AuthApi::getInstance('/goods/v1/GoodsComment/index')->createHeaders(),
                'statistics'    => AuthApi::getInstance('/goods/v1/GoodsComment/statistics')->createHeaders(),
            ],
        ]);
    }
}