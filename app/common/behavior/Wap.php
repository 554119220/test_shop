<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25 0025
 * Time: 2:53
 */

namespace app\common\behavior;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\factory\Cookies;
use mercury\ResponseException;

/**
 * Class Wap
 * @package app\common\behavior
 *
 * wap端
 */
class Wap
{
    /**
     * 需要登陆的控制器,首字母请大写
     */
    const AUTH_LOGIN = [
        'Account',
        'Orders',
        'Cart',
        'Member',
        'Pay',
        'Refund',
        'Service',
        'Address',
        'Comment'
    ];

    /**
     * 不需要header的页面 首字母大写
     */
    const NO_HEADER = [

    ];

    /**
     * 不需要导航条的页面 首字母大写
     */
    const NO_NAV    = [
        'Goods/index',
        'Cart/confirm',
        'Pay/single',
        'Pay/multiple',
        'Refund/*',
        'Orders/*',
        'Service/*',
        'Shop/*',
        'Recharge/*',
        'Lists/*',
        'Promotions/*',
        'Coupon/*',
        'Search/*',
    ];

    /**
     * @param $params
     */
    public function run(&$params)
    {
//        session('user', ['user_id' => 10000, 'nick' => 'mercury']);
        #   判断是否需要登陆
        if (!session('user') && in_array(request()->controller(), self::AUTH_LOGIN)) {
            #   是否为Ajax请求
            if (request()->isAjax()) {
                try {
                    throw new ResponseException(Code::CODE_UNAUTHORIZED);
                } catch (ResponseException $e) {
                    exit(json_encode($e->getData()));
                }
            } else {
                //需要登陆的控制器
                F::gotoUrl('/user/login');
            }
        }

        $controller = ucfirst(request()->controller());
        $action     = request()->action();
        $path_info  = "{$controller}/{$action}";

        #   判断是否不需要导航
        $checkNav   = $this->check(self::NO_NAV, $controller, $action);
        if (request()->has('noNav') || !empty($checkNav)) {
            request()->bind('noNav', 1);
        }

        #   判断是否不需要header
        $checkHeader    = $this->check(self::NO_HEADER, $controller, $action);
        if (request()->has('noHeader') || !empty($checkHeader)) {
            request()->bind('noHeader', 1);
        }

        Cookies::instance(session('user.user_id'))->setId();

    }


    /**
     * @param array $data
     * @param $controller
     * @param $action
     * @return array
     */
    protected function check(array $data, $controller, $action)
    {
        return array_filter($data, function ($val) use ($controller, $action) {
            if (strpos($val, $controller) === 0) {
                if (strpos($val, '/*') !== false || strpos($val, "/{$action}") !== false) {
                    return $val;
                }
            }
            return false;
        });
    }
}