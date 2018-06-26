<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 15:23
 */

namespace app\seller\controller;
use mercury\factory\Factory;

class Prompt
{
    public function index(){
        //查询用户店铺冻结的原因
        $ret    = Factory::instance('/goods/v1/shopAudit/index2')->run(['shop_id'=>session('user.user_shop_id')]);
        return view('',['ret'=>$ret]);
    }
}