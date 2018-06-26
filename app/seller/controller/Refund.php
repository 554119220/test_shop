<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 10:03
 */

namespace app\seller\controller;

use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\factory\Factory;

/**
 * Class Refund
 * @package app\seller\controller
 *
 * 退款管理
 */
class Refund
{
    /**
     * 退款管理
     *
     * @return \think\response\View
     */
    public function index()
    {
        $ret    = Factory::instance('/orders/v1/sellerRefund/index')->run();
        return view('', ['data' => $ret]);
    }

    /**
     * 退款详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $ret    = Factory::instance('/orders/v1/sellerRefund/detail')->run();
        return view('', ['data' => $ret]);
    }


    /**
     * 同意退款
     *
     * @return \think\response\View
     */
    public function agree()
    {
        $ret    = Factory::instance('/orders/v1/sellerRefund/detail')->run();
        $address= [];
        if ($ret['code'] == Code::CODE_SUCCESS && $ret['data']['orders_refund_type'] == State::STATE_REFUNDS) {
            $address    = $address= Factory::instance('/orders/v1/shopAddress/index')->run();
        }
        $headers['agree']   = AuthApi::getInstance('/orders/v1/sellerRefund/agree')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers, 'address' => $address]);
    }

    /**
     * 确认收货
     *
     * @return \think\response\View
     */
    public function receive()
    {
        $ret    = Factory::instance('/orders/v1/sellerRefund/detail')->run();
        $headers['receive'] = AuthApi::getInstance('/orders/v1/sellerRefund/receive')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 拒绝退款
     *
     * @return \think\response\View
     */
    public function refuse()
    {
        $ret    = Factory::instance('/orders/v1/sellerRefund/detail')->run();
        $headers['refuse']  = AuthApi::getInstance('/orders/v1/sellerRefund/refuse')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 提起申诉
     *
     * @return \think\response\View
     */
    public function appeal()
    {
        $ret    = Factory::instance('/orders/v1/sellerRefund/detail')->run();
        $headers['appeal']  = AuthApi::getInstance('/orders/v1/sellerRefund/appeal')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 物流信息
     *
     * @return \think\response\View
     */
    public function logistics()
    {
        $ret    = Factory::instance('/orders/v1/sellerRefund/detail')->run();
        $params = [];
        $express_data   = [];
        if ($ret['code'] == Code::CODE_SUCCESS) {
            $express_data   = $ret['data']['OrdersRefundAddress'];
            $params['company_id']   = $express_data['express_company_id'];
            $params['express_no']   = $express_data['express_no'];
        }
        $logistics  = Factory::instance('/tools/v1/express/index')->run($params);
        return view('', ['data' => $ret, 'logistics' => $logistics, 'express' => $express_data]);
    }
}