<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28 0028
 * Time: 11:59
 */
return [
    'module_init'  => [
        app\common\behavior\AppAuth::class
    ],
    'log_write'     => [
        //app\common\behavior\Logs::class,
    ],
    'response_send'  => [
        app\common\behavior\Response::class
    ],
];