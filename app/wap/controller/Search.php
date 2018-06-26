<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 16:01
 */

namespace app\wap\controller;


use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\factory\Factory;

class Search
{
    /**
     * 搜索首页
     *
     * @return \think\response\View
     */
    public function index()
    {
        $ret    = Factory::instance('/search/v1/goods/getHistory')->run();
        $hot    = Factory::instance('/search/v1/goods/getHotKeywords')->run();
        $header['flush']    = AuthApi::getInstance('/search/v1/goods/flush')->createHeaders();
        return view('', ['data' => $ret, 'headers' => $header, 'hot' => $hot]);
    }

    /**
     * 商品搜索
     *
     * @return \think\response\View
     */
    public function goods()
    {
        if (request()->isAjax() && request()->has('page')) {
            $ret    = Factory::instance('/search/v1/goods/index')->run();
            return json($ret);
        } else {
            $uri    = '/search/goods';
            $get    = input();
            $pathinfo   = str_replace('=', '/', http_build_query(searchFilter($get, 'search'), '', '/'));
            //$query  = http_build_query($get);
            //$url    = "{$uri}?{$query}";
            $url    = url($uri, $get);
            $order['sales']    = url($uri, array_merge($get, ['order' => 'sales']));
            if (isset($get['order']) && $get['order'] == 'price_asc') {
                $price_sort     = 'price_desc';
                $order['price'] = url($uri, array_merge($get, ['order' => 'price_desc']));
            } else {
                $price_sort     = 'price_asc';
                $order['price'] = url($uri, array_merge($get, ['order' => 'price_asc']));
            }
            #   标记active
            $order_active['sales']  = '';
            $order_active['price']  = '';
            $order_active['default']= '';
            if (isset($get['order'])) {
                if ($get['order'] == 'sales') $order_active['sales'] = 'active';
                if (false !== strpos($get['order'], 'price')) $order_active['price'] = 'active';
                unset($get['order']);
            } else {
                $order_active['default']    = 'active';
            }
            $order['default']  = url($uri, $get);
            return view('', [
                'order'         => $order,
                'sort'          => input('order'),
                'url'           => $url,
                'price_sort'    => $price_sort,
                'active'        => $order_active,
                'get'           => $get,
                'pathinfo'      => $pathinfo]);
        }
    }

    public function shop()
    {
        if (request()->isAjax() && request()->has('page')) {
            $ret    = Factory::instance('/search/v1/shop/index')->run();
            return json($ret);
        } else {
            return view('', [

            ]);
        }
    }
}