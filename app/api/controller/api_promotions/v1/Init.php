<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4 0004
 * Time: 10:15
 */

namespace app\api\controller\promotions\v1;


class Init
{
    /**
     * @var array|mixed|null request数据
     * @var array 用户数据
     */
    protected $data = [], $user = [];

    public function __construct()
    {
        $this->data = request()->data;
        $this->user = request()->user;
    }
}