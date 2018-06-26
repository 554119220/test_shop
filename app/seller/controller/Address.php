<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 18:30
 */

namespace app\seller\controller;


use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;

class Address
{
    /**
     * 收货地址列表
     *
     * @return \think\response\View
     */
    public function index()
    {
        $ret    = Factory::instance('/orders/v1/shopAddress/index')->run();
        $headers['delete']  = AuthApi::getInstance('/orders/v1/shopAddress/delete')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 创建收货地址
     *
     * @return \think\response\View
     */
    public function create()
    {
        $token      = request()->token();
        $headers['district']    = AuthApi::getInstance('/tools/v1/district/index', $token)->createHeaders();
        $headers['address']     = AuthApi::getInstance('/orders/v1/shopAddress/create', $token)->createHeaders();
        $district   = F::selectDistrict();
        return view('', ['headers' => $headers, 'district' => $district]);
    }

    /**
     * 修改收货地址
     *
     * @return \think\response\View
     */
    public function edit()
    {
        $ret    = Factory::instance('/orders/v1/shopAddress/detail')->run();
        $params = [];
        if ($ret['code'] == Code::CODE_SUCCESS) {
            $params = [
                'province'  => $ret['data']['address_province_id'],
                'city'      => $ret['data']['address_city_id'],
                'district'  => $ret['data']['address_district_id'],
                'town'      => $ret['data']['address_town_id'],
            ];
        }
        $district   = F::selectDistrict($params);
        $token  = request()->token();
        $headers['district']    = AuthApi::getInstance('/tools/v1/district/index', $token)->createHeaders();
        $headers['address']     = AuthApi::getInstance('/orders/v1/shopAddress/modify', $token)->createHeaders();
        return view('', ['district' => $district, 'data' => $ret, 'headers' => $headers]);
    }
}