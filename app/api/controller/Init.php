<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25 0025
 * Time: 4:41
 */

namespace app\api\controller;


trait Init
{
    /**
     * @var array $user 用户数据
     * @var array $data 用户提交数据
     * @var array $app 应用信息
     */
    protected $user = [], $data = [], $app = [];
    public function __construct()
    {
        $this->data = request()->data;
        $this->user = request()->user;
        $this->app  = request()->app;
    }
}