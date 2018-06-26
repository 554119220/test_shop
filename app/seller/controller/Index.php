<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 9:57
 */

namespace app\seller\controller;

use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\factory\Factory;
use think\Session;

/**
 * Class Index
 * @package app\seller\controller
 *
 * 商家中心首页
 */
class Index
{
    /**
     * 商家首页
     *
     * @return \think\response\View
     */
    public function index()
    {
        //session_id('65b13264b2mjjtdggjh8erb5s6');
//        $_COOKIE['PHPSESSID']   = '65b13264b2mjjtdggjh8erb5s6';
//        $str    = F::headerAuth('user', 'login');
//        dump($str);
//        dump(session_id());
//        //session('user', ['nick' => 'mercury', 'mobile' => '18576380995']);
//        dump(session('user'));
        //$headers = AuthApi::getInstance('/user/v1/login')->createHeaders();
        //echo(session('__token__'));
        //echo '<br />';
        //Factory::instance('/user/v1/login/register');
        //$domain = request()->server('HTTP_HOST');
        //dump(substr($domain, 0, strpos($domain, '.')));
        //dump($headers);
//        $token   = '7ED12B86457F8E4D0DABA717961E4ACFB3CD2BD3A4BF8F239995EB27E9F261A1';
//        $flag    = AuthApi::getInstance('s3R23X-pcdx9dKOwhntvYpmXjrCyZomusJPc171jo9s')->verifyAccessToken($token);
//        dump($flag);
//        dump($headers);
//        //dump(token());
//        dump(session('__token__'));
//        dump($_SESSION);
        //echo session_id();

        $shop = Session::get('shop');
        $shop['shop_url'] = F::domain('wap') . F::shop_url($shop['shop_id']);
        // dump($shop);
        if(!isset($shop['goods_category_ids_name'])){
            $category   = [];
            if(isset($shop['goods_category_ids'])) {
                foreach (explode(',', $shop['goods_category_ids']) as $key => $value) {
                    $category[$key] = Factory::instance('/goods/v1/goodsCategory/detail')->run(['category_id' => $value]);
                }
            }
            Session::set('shop.goods_category_ids_name',$category);
        }
        if(!isset($shop['shop_type_name'])){
            $shopType = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id'=>$shop['shop_type_id']]);
            Session::set('shop.shop_type_name',$shopType['data']['shop_type_suffix'] ?? '');
        }

        return view('', [
            'user'          => Session::get('user'),
            'shop'          => $shop,
            'headers'       => [
                'headers0'      => AuthApi::getInstance('/goods/v1/Goods/statistics')->createHeaders(),
                'orders_total'  => AuthApi::getInstance('/orders/v1/sellerOrders/total')->createHeaders(),
                'shop_total'    => AuthApi::getInstance('/goods/v1/shop/yesToDayTotal')->createHeaders(),
            ],
        ]);
    }
}