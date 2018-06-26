<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 14:39
 */

namespace app\api\controller\cps\v1;


class Init
{
    protected $data = [], $user = [], $model;

    protected function __construct()
    {
        $this->data = request()->data;
        $this->user = request()->user;
    }
}