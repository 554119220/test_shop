<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 17:57
 */

return [
    'module_init'  => [
        app\common\behavior\Template::class,
    ],
    'view_filter'   => [
        app\common\behavior\ShareCode::class,
    ],
    'log_write'     => [
        //app\common\behavior\Logs::class,
    ]
];