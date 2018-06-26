<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4 0004
 * Time: 10:11
 */

namespace app\api\controller\orders\v1;


class Init
{
    protected $data = [], $user = [];

    public function __construct()
    {
        $this->user = request()->user;
        $this->data = request()->data;
    }
}