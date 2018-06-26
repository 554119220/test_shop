<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/26 0026
 * Time: 18:01
 */

namespace app\wap\controller;


use app\common\traits\F;
use mercury\constants\Code;
use mercury\ResponseException;
use mercury\weChat\Sdk;
use mercury\weChat\Sign;

class Wechat
{
    public function index()
    {
        try {
            if (!isset($_GET['signature']) ||
                !isset($_GET['echostr']) ||
                !isset($_GET['timestamp']) ||
                !isset($_GET['nonce']))
                throw new ResponseException(Code::CODE_MISSING_PARAMETER);
            $bool   = Sign::instance($_GET)->check();
            if (!$bool) throw new ResponseException(Code::CODE_OTHER_FAIL, '验证失败');
            echo $_GET['echostr'];
        } catch (ResponseException $e) {
            F::goto404();
        }
    }

    public function token()
    {
        $str    = 'jsapi_ticket=HoagFKDcsGMVCIY2vOjf9nNxVhm3CSBd9bFBTT056wAb5_DxfnLq0xHeDevn3KwYLT0fco9KBJ4t4VAqep1oOQ&noncestr=FC5DC3E4F5E71CA34954F3D0F4957D1BC2AA4D24B5227B9ABA98182787FD3E88&timestamp=1519720186&url=http://wap.zrshop.com/';
        dump(sha1($str));
//        $token  = Sdk::instance('')->getAccessToken();
//        if (!$token) dump(Sdk::instance('')->getError());
//        $ticket = Sdk::instance('')->getTicket();
//        if (!$ticket) dump(Sdk::instance('')->getError());
//        $params = Sdk::instance('', ['url' => F::domain('wap', request()->url())])->getJsApiParams();
    }
}