<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 14:24
 */

namespace app\common\behavior;


class Session
{
    public function run(&$params)
    {
        session_start();
    }
}