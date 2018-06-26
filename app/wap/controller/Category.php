<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:30
 */

namespace app\wap\controller;
use mercury\factory\Factory;

/**
 * Class Category
 * @package app\wap\controller
 *
 * 商品类目
 */
class Category
{
    /**
     * 商品类目
     *
     * @return \think\response\View
     */
    public function index()
    {
    	$list = Factory::instance('/goods/v1/GoodsCategory/index')->run();
        return view('',[
        	'list' => $list['data'] ?? [],
        ]);
    }
}