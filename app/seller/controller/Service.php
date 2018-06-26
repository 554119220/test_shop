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
use mercury\factory\Factory;

/**
 * Class Service
 * @package app\seller\controller
 *
 * 售后管理
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
        $ret    = Factory::instance('/orders/v1/sellerService/index')->run();
        return view('', ['data' => $ret]);
    }

    /**
     * 详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $ret    = Factory::instance('/orders/v1/sellerService/detail')->run();
        $buyer_address  = [];
        $seller_address = [];
        if ($ret['code'] == Code::CODE_SUCCESS) {
            #   商家收货地址，买家邮寄到这个地址
            if (isset($ret['data']['OrdersServiceAddress'][0])) {
                $seller_address = $ret['data']['OrdersServiceAddress'][0];
            }
            #   买家收货地址，商家邮寄到这个地址
            if (isset($ret['data']['OrdersServiceAddress'][1])) {
                $buyer_address = $ret['data']['OrdersServiceAddress'][1];
            }
        }
        return view('', ['data' => $ret, 'seller_address' => $seller_address, 'buyer_address' => $buyer_address]);
    }

    /**
     * 邮寄商品
     *
     * @return \think\response\View
     */
    public function ship()
    {
        $ret    = Factory::instance('/orders/v1/sellerService/detail')->run();
        $headers['ship']    = AuthApi::getInstance('/orders/v1/sellerService/express')->createHeaders();
        $company= Factory::instance('/tools/v1/expressCompany/group')->run();
        return view('', ['data' => $ret, 'headers' => $headers, 'company' => $company]);
    }

    /**
     * 收货
     *
     * @return \think\response\View
     */
    public function receive()
    {
        $ret    = Factory::instance('/orders/v1/sellerService/detail')->run();
        $headers['receive'] = AuthApi::getInstance('/orders/v1/sellerService/receive')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 拒绝售后
     *
     * @return \think\response\View
     */
    public function refuse()
    {
        $ret    = Factory::instance('/orders/v1/sellerService/detail')->run();
        $headers['refuse']  = AuthApi::getInstance('/orders/v1/sellerService/refuse')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }
    
    
    /**
     * 同意售后
     *
     * @return \think\response\View
     */
    public function agree()
    {
        $ret    = Factory::instance('/orders/v1/sellerService/detail')->run();
        $headers['agree']   = AuthApi::getInstance('/orders/v1/sellerService/agree')->createHeaders();
        $address= Factory::instance('/orders/v1/shopAddress/index')->run();
        return view('', ['data' => $ret, 'headers' => $headers, 'address' => $address]);
    }

    /**
     * 申诉
     *
     * @return \think\response\View
     */
    public function appeal()
    {
        $ret    = Factory::instance('/orders/v1/sellerService/detail')->run();
        $headers['appeal']  = AuthApi::getInstance('/orders/v1/sellerService/appeal')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 物流信息
     *
     * @return \think\response\View
     */
    public function logistics()
    {
        $ret    = Factory::instance('/orders/v1/sellerService/detail')->run();
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