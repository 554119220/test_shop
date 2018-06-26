<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/11 0011
 * Time: 9:13
 */

namespace app\wap\controller;

use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\common\Seo;
use mercury\constants\State;
use mercury\editor\UEditor;
use mercury\factory\Factory;
use mercury\weChat\Sdk;
use think\Image;

/**
 * Class Index
 * @package app\wap\controller
 *
 * wap端首页
 */
class Index
{
    public function index3()
    {
        $headers['goods']   = AuthApi::getInstance('/search/v1/goods/like')->createHeaders();
        $channel    = Factory::instance('/goods/v1/Channel/detail')->run(['type' => 'default']);
        $weChat     = (new Sdk('', ['url' => F::domain('wap', request()->url())]))->getJsApiParams();
        return view('', [
            'headers' => $headers,
            'channel' => $channel,
            'seo'     => Seo::instance('')->getSeo(),
            'weChat'  => $weChat]);
    }

    public function like()
    {
        $ret    = Factory::instance('/search/v1/goods/like')->run(['featured' => State::STATE_NORMAL, 'page' => input('page', 1)]);
        return json($ret);
    }

    public function goods()
    {
        $ret    = Factory::instance('/search/v1/goods/index')->run();
        return json($ret);
    }

    public function test()
    {
        exit();
        session('user', $user   = [
            'user_id'   => 10000,
            'user_nike' => 'mercury'
        ]);
        $confirm    = [
                   0   => [
                       'express_id'    => 1,
                       'remark'        => '留言1',
                       'cart_ids'      => '2,3',
                       'seller_user_id'=> 1,
                       'shop_id'       => 1,
                   ],
                   1   => [
                       'express_id'    => 2,
                       'remark'        => '留言2',
                       'cart_ids'      => '4,5,6',
                       'seller_user_id'=> 1,
                       'shop_id'       => 1,
                   ]
              ];

        request()->bind('data', ['confirm' => $confirm]);
        $s = microtime(true);
        $flag   = Factory::instance('/orders/v1/buyerOrders/create')->run();
        $e = microtime(true);
        echo $e - $s;
        echo '<br />';
        dump($flag);
    }

    public function test1()
    {
        exit();
        $ob = \app\api\controller\orders\v1\BuyerOrders::class;
        //$flag   = (new $ob())->close();
        request()->bind('data', ['orders_shop_no' => 'CO20171124092503189959']);
        $ret = call_user_func_array([new $ob(), 'close'], []);
        dump($ret);
    }

    public function index()
    {
        return view();
    }
}