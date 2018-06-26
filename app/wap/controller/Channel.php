<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30 0030
 * Time: 14:52
 */

namespace app\wap\controller;

use app\common\traits\F;
use mercury\common\Seo;
use mercury\constants\Code;
use mercury\factory\Factory;
use mercury\ResponseException;
use think\Exception;

/**
 * Class Channel
 * @package app\wap\controller
 * @title 频道
 */
class Channel
{
    public function index()
    {
        try {
            $channel    = Factory::instance('/goods/v1/Channel/detail')->run();
            if ($channel['code'] != Code::CODE_SUCCESS) throw new Exception('频道不存在');
            return view('', ['data' => $channel, 'seo' => Seo::instance($channel['data']['channel_title'])->getSeo()]);
        } catch (Exception $e) {
            F::goto404();
        }
    }

    /**
     * @title 特殊的,如每日必抢，热卖榜，9.9包邮等等
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return \think\response\View
     */
    public function special()
    {
        $type       = input('type');
        $channel    = Factory::instance('/goods/v1/Channel/detail')->run(['type' => $type]);
        $channel_condition  = [];
        if ($channel['code'] == Code::CODE_SUCCESS) {
            if (!empty($channel['data']['channel_condition'])) {
                $channel_condition  = json_decode($channel['data']['channel_condition'], true);
                if (!$channel_condition) $channel_condition = [];
            }
        } else {
            F::goto404();
        }
        $uri    = "/channel/{$type}";
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
        $get_order  = [];
        if (isset($get['order'])) {
            if ($get['order'] == 'sales') $order_active['sales'] = 'active';
            if (false !== strpos($get['order'], 'price')) $order_active['price'] = 'active';
            $get_order  = ['order' => $get['order']];
            unset($get['order']);
        } else {
            $order_active['default']    = 'active';
        }
        $order['default']  = url($uri, $get);
        return view('', [
        'order'         => $order,
        'sort'          => input('order'),
        'url'           => $url,
        'get'           => array_merge($get, $get_order),
        'price_sort'    => $price_sort,
        'active'        => $order_active,
        'pathinfo'      => $pathinfo,
        'params'        => json_encode(array_merge($get, $get_order, $channel_condition)),
        'data'  => $channel,
        'seo'   => Seo::instance($channel['data']['channel_title'])->getSeo()]);
    }
    
    /**
     * @title 每日必抢
     * @return \think\response\View
     */
    public function day()
    {
        $channel    = Factory::instance('/goods/v1/Channel/detail')->run(['type' => 'day']);
        $uri    = '/channel/day';
        $get    = input();
        $pathinfo   = str_replace('=', '/', http_build_query($get, '', '/'));
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
        $get_order  = [];
        if (isset($get['order'])) {
            if ($get['order'] == 'sales') $order_active['sales'] = 'active';
            if (false !== strpos($get['order'], 'price')) $order_active['price'] = 'active';
            $get_order  = ['order' => $get['order']];
            unset($get['order']);
        } else {
            $order_active['default']    = 'active';
        }
        $order['default']  = url($uri, $get);
        return view('', [
            'order'         => $order,
            'sort'          => input('order'),
            'url'           => $url,
            'get'           => array_merge($get, $get_order),
            'price_sort'    => $price_sort,
            'active'        => $order_active,
            'pathinfo'      => $pathinfo,
            'params'        => json_encode(array_merge($get, $get_order)),
            'data' => $channel]);
    }

    /**
     * @title 热卖
     * @return \think\response\View
     */
    public function hot()
    {
        $channel    = Factory::instance('/goods/v1/Channel/detail')->run(['type' => 'hot']);
        $uri    = '/channel/hot';
        $get    = input();
        $pathinfo   = str_replace('=', '/', http_build_query($get, '', '/'));
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
        $get_order  = [];
        if (isset($get['order'])) {
            if ($get['order'] == 'sales') $order_active['sales'] = 'active';
            if (false !== strpos($get['order'], 'price')) $order_active['price'] = 'active';
            $get_order  = ['order' => $get['order']];
            unset($get['order']);
        } else {
            $order_active['default']    = 'active';
        }
        $order['default']  = url($uri, $get);
        return view('', [
            'order'         => $order,
            'sort'          => input('order'),
            'url'           => $url,
            'get'           => array_merge($get, $get_order),
            'price_sort'    => $price_sort,
            'active'        => $order_active,
            'pathinfo'      => $pathinfo,
            'params'        => json_encode(array_merge($get, $get_order)),
            'data' => $channel]);
    }

    /**
     * @title 9.9
     * @return \think\response\View
     */
    public function nine()
    {
        $channel    = Factory::instance('/goods/v1/Channel/detail')->run(['type' => 'nine']);
        $uri    = '/channel/nine';
        $get    = input();
        $pathinfo   = str_replace('=', '/', http_build_query($get, '', '/'));
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
        $get_order  = [];
        if (isset($get['order'])) {
            if ($get['order'] == 'sales') $order_active['sales'] = 'active';
            if (false !== strpos($get['order'], 'price')) $order_active['price'] = 'active';
            $get_order  = ['order' => $get['order']];
            unset($get['order']);
        } else {
            $order_active['default']    = 'active';
        }
        $order['default']  = url($uri, $get);
        return view('', [
            'order'         => $order,
            'sort'          => input('order'),
            'url'           => $url,
            'get'           => array_merge($get, $get_order),
            'price_sort'    => $price_sort,
            'active'        => $order_active,
            'pathinfo'      => $pathinfo,
            'params'        => json_encode(array_merge($get, $get_order)),
            'data' => $channel]);
    }
}