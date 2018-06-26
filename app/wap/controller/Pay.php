<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:17
 */

namespace app\wap\controller;

use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;

/**
 * Class Pay
 * @package app\wap\controller
 *
 * 支付中心
 */
class Pay
{
    protected $headers;
    public function __construct()
    {
        $this->headers['pay']   = AuthApi::getInstance('/pay/v1/erp/index')->createHeaders();
//        $this->headers['pay']   = AuthApi::getInstance('/orders/v1/notify/pay', $token)->createHeaders();
        $this->headers['state'] = AuthApi::getInstance('/orders/v1/notify/state')->createHeaders();
    }
    /**
     * 但订单支付
     *
     * @return \think\response\View
     */
    public function single()
    {
        #   延时15分钟
        $ret        = Factory::instance('/orders/v1/buyerOrders/detail')->run();
        $pay_type   = Factory::instance('/pay/v1/payType/index')->run();
        return view('', ['data' => $ret, 'headers' => $this->headers, 'pay_type' => $pay_type]);
    }

    /**
     * 多订单支付
     *
     * @return \think\response\View
     */
    public function multiple()
    {
        #   延时15分钟
        $ret    = Factory::instance('/orders/v1/buyerOrders/groupOrders')->run();
        $pay_type   = Factory::instance('/pay/v1/payType/index')->run();
        return view('', ['data' => $ret, 'headers' => $this->headers, 'pay_type' => $pay_type]);
    }

    /**
     * 付款同步页面
     */
    public function payRefund()
    {
        $ret    = Factory::instance('/orders/v1/buyerOrders/state')->run();
        if ($ret['code'] == Code::CODE_SUCCESS) {
            F::gotoUrl('/pay/paySuccess');
        } else {
            F::gotoUrl('/pay/payFailed');
        }
    }

    /**
     * 付款成功页面
     *
     * @return \think\response\View
     */
    public function paySuccess()
    {
        return view('paySuccess');
    }

    /**
     * 付款失败页面
     *
     * @return \think\response\View
     */
    public function payFailed()
    {
        return view('payFailed');
    }
}