<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22 0022
 * Time: 10:05
 */

namespace app\doc\controller;


use app\doc\tools\ParseDocument;
use mercury\constants\Code;

class Wiki
{
    public function index()
    {
        $group  = ParseDocument::instance()->files();
        $doc    = ParseDocument::instance()->find(request()->get('route', '/pay/v1/PayType/index'));
        return view('', ['data' => $group, 'doc' => $doc]);
    }

    /**
     * @title 返回编码
     * @return \think\response\View
     */
    public function code()
    {
        $group  = ParseDocument::instance()->files();
        return view('', ['data' => $group, 'code' => Code::CODE_ARRAY]);
    }
}