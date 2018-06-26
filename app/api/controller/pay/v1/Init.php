<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 13:40
 */

namespace app\api\controller\pay\v1;

/**
 * Class Init
 * @package app\api\controller\pay\v1
 *
 * 支付基类
 */
class Init
{
    protected $data = [], $user = [];

    public function __construct()
    {
        $this->data = request()->data;
        $this->user = request()->user;
    }
}