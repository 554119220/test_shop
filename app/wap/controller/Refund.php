<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:27
 */

namespace app\wap\controller;
use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;

/**
 * Class Refund
 * @package app\wap\controller
 *
 * 退款
 */
class Refund
{
    /**
     * 退款列表
     *
     * @return \think\response\View
     */
    public function index()
    {
        if (request()->isAjax() && request()->has('page')) {
            $ret    = Factory::instance('/orders/v1/buyerRefund/index')->run();
            return json($ret);
        } else {
            $headers['cancel']  = AuthApi::getInstance('/orders/v1/buyerRefund/cancel')->createHeaders();
            return view('', ['headers' => $headers]);
        }
    }

    /**
     * 退款详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $ret    = Factory::instance('/orders/v1/buyerRefund/detail')->run();
        $headers['cancel']  = AuthApi::getInstance('/orders/v1/buyerRefund/cancel')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 订单信息
     *
     * @return \think\response\View
     */
    public function goods()
    {
        $ret    = Factory::instance('/orders/v1/buyerOrders/refundOrders')->run();
        return view('', ['data' => $ret]);
    }

    /**
     * 申请退款
     *
     * @return \think\response\View
     */
    public function apply()
    {
        $ret    = Factory::instance('/orders/v1/buyerOrders/refundApply')->run();
        $header = AuthApi::getInstance('/orders/v1/buyerRefund/create')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $header]);
    }

    /**
     * 修改退款
     *
     * @return \think\response\View
     */
    public function edit()
    {
//        $ret    = Factory::instance('/orders/v1/buyerRefund/detail')->run();
        $ret    = Factory::instance('/orders/v1/buyerRefund/edit')->run();
        $headers['edit']    = AuthApi::getInstance('/orders/v1/buyerRefund/modify')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 邮寄商品
     *
     * @return \think\response\View
     */
    public function ship()
    {
        $ret    = Factory::instance('/orders/v1/buyerRefund/detail')->run();
        $headers['ship']    = AuthApi::getInstance('/orders/v1/buyerRefund/express')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 提起申诉
     *
     * @return \think\response\View
     */
    public function appeal()
    {
        $ret    = Factory::instance('/orders/v1/buyerRefund/detail')->run();
        $headers['appeal']  = AuthApi::getInstance('/orders/v1/buyerRefund/appeal')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 物流信息
     *
     * @return \think\response\View
     */
    public function logistics()
    {
        $ret    = Factory::instance('/orders/v1/buyerRefund/detail')->run();
        $logistics= [];
        if ($ret['code'] == Code::CODE_SUCCESS) {
            $express['company_id']  = $ret['data']['OrdersRefundAddress']['express_company_id'];
            $express['express_no']  = $ret['data']['OrdersRefundAddress']['express_no'];
            $logistics  = Factory::instance('/tools/v1/express/index')->run($express);
        }
        return view('', ['data' => $ret, 'logistics' => $logistics]);
    }
}