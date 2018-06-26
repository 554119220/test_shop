<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 9:37
 */

namespace mercury\rpc;


class Clients
{
    protected $clients;

    public function __construct()
    {
        $this->clients  = new \Yar_Concurrent_Client();
    }

    public function call($uri, $method, array $params, $callback = '', $a = NULL, array $opt = [])
    {
        return $this->clients->call($uri, $method, $params, $callback, $a, $opt);
    }

    public function loop($callback, $error_callback)
    {
        return $this->clients->loop($callback, $error_callback);
    }

    public function reset()
    {
        return $this->clients->reset();
    }
}