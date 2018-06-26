<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:16
 */

namespace app\wap\controller;
use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;

/**
 * Class Orders
 * @package app\wap\controller
 *
 * 订单列表
 */
class Orders
{
    /**
     * 订单列表
     *
     * @return \think\response\View|array|string
     */
    public function index()
    {
        if (request()->isAjax() && request()->has('page')) {
            $ret    = Factory::instance('/orders/v1/buyerOrders/index')->run();
            return json($ret);
        } elseif (request()->isGet()) {
//            session('user', ['user_id' => 10000, 'nick' => 'mercury']);
            $headers['cancel']      = AuthApi::getInstance('/orders/v1/buyerOrders/close')->createHeaders();
            $headers['receive']     = AuthApi::getInstance('/orders/v1/buyerOrders/receive')->createHeaders();
            $headers['notice_ship'] = AuthApi::getInstance('/orders/v1/buyerOrders/noticeShip')->createHeaders();
            $headers['buy_again']   = AuthApi::getInstance('/orders/v1/buyerOrders/buyAgain')->createHeaders();
            return view('', ['headers' => $headers]);
        }
    }

    /**
     * 订单详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $headers['cancel']      = AuthApi::getInstance('/orders/v1/buyerOrders/close')->createHeaders();
        $headers['receive']     = AuthApi::getInstance('/orders/v1/buyerOrders/receive')->createHeaders();
        $headers['notice_ship'] = AuthApi::getInstance('/orders/v1/buyerOrders/noticeShip')->createHeaders();
        $headers['buy_again']   = AuthApi::getInstance('/orders/v1/buyerOrders/buyAgain')->createHeaders();
        $ret    = Factory::instance('/orders/v1/buyerOrders/detail')->run();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 物流信息
     *
     * @return \think\response\View
     */
    public function logistics()
    {
        $ret        = Factory::instance('/orders/v1/buyerOrders/detail')->run();
        $logistics = [];
        if ($ret['code'] == Code::CODE_SUCCESS) {
            $params['company_id']   = $ret['data']['orders_shop_express_company'];
            $params['express_no']   = $ret['data']['orders_shop_express_no'];
            $logistics  = Factory::instance('/tools/v1/express/index')->run($params);
        }
        return view('', ['data' => $ret, 'logistics' => $logistics]);
    }

    /**
     * 订单评价
     *
     * @return \think\response\View
     */
    public function comments()
    {
        $ret    = Factory::instance('/orders/v1/buyerOrders/comments')->run();
        $header = AuthApi::getInstance('/goods/v1/goodsComment/create')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $header]);
    }
}