<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:15
 */

namespace app\wap\controller;

use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;
use think\Controller;

/**
 * Class Cart
 * @package app\wap\controller
 *
 * 购物车
 */
class Cart extends Controller
{
    /**
     * 购物车首页
     *
     * @return \think\response\View
     */
    public function index()
    {
        $ret    = Factory::instance('/orders/v1/cart/index')->run();
        $headers['plus']    = AuthApi::getInstance('/orders/v1/cart/plusNum')->createHeaders();
        $headers['less']    = AuthApi::getInstance('/orders/v1/cart/lessNum')->createHeaders();
        $headers['delete']  = AuthApi::getInstance('/orders/v1/cart/delete')->createHeaders();
        session('cart_ids', null);
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 提交订单页面
     *
     * @return \think\response\View
     */
    public function confirm()
    {
        if (request()->isPost() || session('cart_ids')) {
            if (request()->post('cart_ids')) {
                session('cart_ids', request()->post('cart_ids'));
            }
            request()->bind('data', ['cart_ids' => session('cart_ids')]);
            $ret    = Factory::instance('/orders/v1/cart/confirm')->run();
            $address    = [];
            if ($ret['code'] == Code::CODE_SUCCESS) {
                $address = $ret['data']['address'];
            } else {
                F::gotoUrl('/cart');
            }
            $header     = AuthApi::getInstance('/orders/v1/buyerOrders/create')->createHeaders();
            return view('', ['data' => $ret, 'address' => $address, 'headers' => $header]);
        } else {
            $this->redirect('/cart');
        }
    }
}