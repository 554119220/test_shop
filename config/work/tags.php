<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 15:19
 */
return [
    'module_init'   => [
        app\common\behavior\WorkTemplate::class,
    ],
    'action_begin'  => [
        app\work\behavior\Power::class
    ],
    'response_send'  => [
        app\common\behavior\AdminLog::class
    ],
];