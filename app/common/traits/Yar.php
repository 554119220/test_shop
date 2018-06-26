<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 17:47
 */

namespace app\common\traits;
use mercury\rpc\Client;
use mercury\rpc\Server;

/**
 * Trait Yar
 * @package app\common\traits
 *
 * RPC框架服务
 */
trait Yar
{
    /**
     * RPC服务端
     *
     * @param $obj
     * @return Server
     */
    public static function yarServer($obj)
    {
        return new Server($obj);
    }

    /**
     * 客户端
     *
     * @param $uri
     * @return Client
     */
    public static function yarClient($uri)
    {
        return new Client($uri);
    }
}