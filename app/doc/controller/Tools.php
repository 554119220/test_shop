<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2 0002
 * Time: 9:11
 */

namespace app\doc\controller;

use app\common\traits\F;
use app\doc\tools\ParseDocument;
use mercury\constants\Code;
use mercury\ResponseException;
use mercury\sdk\Sdk;

/**
 * Class Tools
 * @package app\doc\controller
 * @title 工具
 */
class Tools
{
    public function index()
    {
        $group  = ParseDocument::instance()->files();
        $token  = SDK::instance('')->getAccessToken();
        return view('', ['data' => $group, 'token' => $token]);
    }

    public function post()
    {
        if (request()->isPost()) {
            try {
                $router = input('router');
                if (!$router) throw new ResponseException(Code::CODE_OTHER_FAIL, '路由不能为空');
                $data   = request()->post();
                $body   = $data['body'] ? json_decode(htmlspecialchars_decode($data['body']), true) : [];
                $body   = $body ? $body : [];
                unset($data['router'], $data['body']);
                $data   = array_merge($data, $body);
                $ret    = Sdk::instance($router, $data)->request()->toArray();
            } catch (ResponseException $e) {
                $ret    = $e->getData();
            }
            return json($ret);
        }
    }
}