<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 10:02
 */

namespace app\seller\controller;

use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use mercury\factory\Factory;
use mercury\office\export\ShopOrders;
use mercury\office\export\ShopShip;
use think\Exception;

/**
 * Class Orders
 * @package app\seller\controller
 *
 * 订单管理
 */
class Orders
{
    /**
     * 订单列表
     *
     * @return \think\response\View
     */
    public function index()
    {
//        session('user', ['user_id' => 1, 'shop_id' => 1, 'nick' => 'mercury']);
        $ret    = Factory::instance('/orders/v1/sellerOrders/index')->run();
        return view('', ['data' => $ret]);
    }

    /**
     * 订单详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $ret    = Factory::instance('/orders/v1/sellerOrders/detail')->run();
        return view('', ['data' => $ret]);
    }

    /**
     * 发货
     *
     * @return \think\response\View
     */
    public function ship()
    {
        $ret    = Factory::instance('/orders/v1/sellerOrders/detail')->run();
        $company= Factory::instance('/tools/v1/expressCompany/group')->run();
        $headers['ship']   = AuthApi::getInstance('/orders/v1/sellerOrders/ship')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers, 'company' => $company]);
    }

    /**
     * 修改价格
     *
     * @return \think\response\View
     */
    public function editPrice()
    {
        $ret    = Factory::instance('/orders/v1/sellerOrders/detail')->run();
        $headers['edit_price']  = AuthApi::getInstance('/orders/v1/sellerOrders/editPrice')->createHeaders();
        return view('editPrice', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 修改发货
     *
     * @return \think\response\View
     */
    public function editShip()
    {
        $ret    = Factory::instance('/orders/v1/sellerOrders/detail')->run();
        $company= Factory::instance('/tools/v1/expressCompany/group')->run();
        $headers['ship']   = AuthApi::getInstance('/orders/v1/sellerOrders/editShip')->createHeaders();
        return view('editShip', ['data' => $ret, 'headers' => $headers, 'company' => $company]);
    }

    /**
     * 关闭订单
     *
     * @return \think\response\View
     */
    public function close()
    {
        $ret    = Factory::instance('/orders/v1/sellerOrders/detail')->run();
        $headers['close']   = AuthApi::getInstance('/orders/v1/sellerOrders/close')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $headers]);
    }

    /**
     * 物流信息
     *
     * @return \think\response\View
     */
    public function logistics()
    {
        $ret        = Factory::instance('/orders/v1/sellerOrders/detail')->run();
        $params = [];
        if ($ret['code'] == Code::CODE_SUCCESS) {
            $params['company_id']   = $ret['data']['orders_shop_express_company'];
            $params['express_no']   = $ret['data']['orders_shop_express_no'];
        }
        $logistics  = Factory::instance('/tools/v1/express/index')->run($params);
        return view('', ['data' => $ret, 'logistics' => $logistics]);
    }

    /**
     * @title export 订单导出
     * @return \think\response\View
     */
    public function export()
    {
        return view();
    }

    public function down()
    {
        try {
            ini_set('max_execution_time', 0);
            $get    = request()->get();
            $where  = [
                'shop_id'   => session('user.user_shop_id')
            ];
            if (!empty($get['between_time'])) {
                $between    = "orders_shop_{$get['between_time']}";
            } else {
                $sday   = time() - (7 * 86400);
                $eday   = time();
                $where['orders_shop_create_time']   = ['between', "{$sday},{$eday}"];
            }

            if (isset($between)) {
                if (!empty($get['sday'])) {
                    $sday   = strtotime($get['sday']);
                } else {
                    $sday   = mktime(0, 0, 0, 0, 7);
                }

                if (!empty($get['eday'])) {
                    $eday   = strtotime($get['eday']);
                } else {
                    $eday   = time();
                }

                if ($eday - $sday > (7 * 86400))
                    exit('<p style="color: red; font-weight: bold; font-size: 24px; font-family: arial; margin-top: 300px; width: 100%;text-align: center">
最多可导出7个自然日数据!</p>');

                $where[$between]    = ['between', "{$sday},{$eday}"];

            }

            if (!empty($get['state'])) {
                $where['orders_shop_state'] = $get['state'];
            }

            if ($get['type'] == 1) {
                $obj    = new ShopOrders();
                $obj->setMap($where)->setDownloadFilename('对账订单')->run();
            } else {
                $obj    = new ShopShip();
                $obj->setMap($where)->setDownloadFilename('发货订单')->run();
            }
        } catch (Exception $e) {

        }
    }
}