<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 17:11
 */

namespace app\wap\controller;
use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;

/**
 * 收货地址
 *
 * Class Address
 * @package app\wap\controller
 */
class Address
{
    /**
     * 列表
     *
     * @return \think\response\View
     */
    public function index()
    {
        $data   = Factory::instance('/orders/v1/userAddress/index')->run();
        $token  = request()->token();
        $headers['default'] = AuthApi::getInstance('/orders/v1/userAddress/setDefault', $token)->createHeaders();
        $headers['delete']  = AuthApi::getInstance('/orders/v1/userAddress/delete', $token)->createHeaders();
        return view('', ['data' => $data, 'headers' => $headers]);
    }

    /**
     * 修改
     *
     * @return \think\response\View
     */
    public function edit()
    {
        $data   = Factory::instance('/orders/v1/userAddress/detail')->run();
        if ($data['code'] == Code::CODE_SUCCESS) {
            $data['data']['districts'] = rtrim("{$data['data']['province_name']},
            {$data['data']['city_name']},
            {$data['data']['district_name']}", ',');
        }
        $headers['modify']  = AuthApi::getInstance('/orders/v1/userAddress/modify')->createHeaders();
        $headers['town']    = AuthApi::getInstance('/tools/v1/district/index')->createHeaders();
        return view('', ['data' => $data, 'headers' => $headers]);
    }

    /**
     * 创建
     *
     * @return \think\response\View
     */
    public function create()
    {
        $headers['create'] = AuthApi::getInstance('/orders/v1/userAddress/create')->createHeaders();
        $headers['town'] = AuthApi::getInstance('/tools/v1/district/index')->createHeaders();
        request()->bind('data', ['id' => 18]);
        $towns  = Factory::instance('/tools/v1/district/index')->run();
        $tmp    = [];
        if (!empty($towns)) {
            foreach ($towns['data'] as $k => $v) {
                $tmp[$k]['id']  = $v['id'];
                $tmp[$k]['name']= $v['a_name'];
            }
        }
        return view('', ['headers' => $headers, 'towns' => $tmp]);
    }
}