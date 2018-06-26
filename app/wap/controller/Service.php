<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:25
 */

namespace app\wap\controller;
use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;

/**
 * Class Service
 * @package app\wap\controller
 *
 * 售后
 */
class Service
{
    /**
     * 列表
     *
     * @return \think\response\View
     */
    public function index()
    {
        if (request()->isAjax() && request()->has('page')) {
            $ret    = Factory::instance('/orders/v1/buyerService/index')->run();
            return json($ret);
        } else {
            $headers['receive'] = AuthApi::getInstance('/orders/v1/buyerService/receive')->createHeaders();
            $headers['cancel']  = AuthApi::getInstance('/orders/v1/buyerService/cancel')->createHeaders();
            return view('', ['headers' => $headers]);
        }
    }

    /**
     * 详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $ret    = Factory::instance('/orders/v1/buyerService/detail')->run();
        $headers['receive'] = AuthApi::getInstance('/orders/v1/buyerService/receive')->createHeaders();
        $headers['cancel']  = AuthApi::getInstance('/orders/v1/buyerService/cancel')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 订单信息
     *
     * @return \think\response\View
     */
    public function goods()
    {
        $ret    = Factory::instance('/orders/v1/buyerOrders/serviceOrders')->run();
        return view('', ['data' => $ret]);
    }

    /**
     * 邮寄
     *
     * @return \think\response\View
     */
    public function ship()
    {
        $ret    = Factory::instance('/orders/v1/buyerService/detail')->run();
        $headers['ship']    = AuthApi::getInstance('/orders/v1/buyerService/express')->createHeaders();
        $address_id = request()->get('id', 0);
        if ($address_id > 0) request()->bind('data', ['id' => $address_id]);
        $address    = Factory::instance('/orders/v1/userAddress/detail')->run();
        return view('', ['data' => $ret, 'headers' => $headers, 'address' => $address]);
    }

    /**
     * 申请
     *
     * @return \think\response\View
     */
    public function apply()
    {
        $ret    = Factory::instance('/orders/v1/buyerOrders/serviceApply')->run();
        $header = AuthApi::getInstance('/orders/v1/buyerService/create')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $header]);
    }

    /**
     * 修改售后
     *
     * @return \think\response\View
     */
    public function edit()
    {
//        $ret    = Factory::instance('/orders/v1/buyerService/detail')->run();
        $ret    = Factory::instance('/orders/v1/buyerService/edit')->run();
        $headers['edit']    = AuthApi::getInstance('/orders/v1/buyerService/modify')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 申诉
     *
     * @return \think\response\View
     */
    public function appeal()
    {
        $ret    = Factory::instance('/orders/v1/buyerService/detail')->run();
        $token  = request()->token();
        $headers['appeal']  = AuthApi::getInstance('/orders/v1/buyerService/appeal', $token)->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 物流信息
     *
     * @return \think\response\View
     */
    public function logistics()
    {
        $ret    = Factory::instance('/orders/v1/buyerService/detail')->run();
        $params = [];
        $express_data   = [];
        if ($ret['code'] == Code::CODE_SUCCESS) {
            if (request()->param('type') == 'buyer') {
                $express_data   = $ret['data']['OrdersServiceAddress'][0];
            } else {
                $express_data   = $ret['data']['OrdersServiceAddress'][1];
            }
            $params['company_id']   = $express_data['express_company_id'];
            $params['express_no']   = $express_data['express_no'];
        }
        $logistics  = Factory::instance('/tools/v1/express/index')->run($params);
        return view('', ['data' => $ret, 'logistics' => $logistics, 'express' => $express_data]);
    }
}