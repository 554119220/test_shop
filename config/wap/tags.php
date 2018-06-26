<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 15:36
 */
return [
    'module_init' => [
        app\common\behavior\Template::class,
        app\common\behavior\Auth::class,
        app\common\behavior\Wap::class,
    ],
    'view_filter'   => [
        app\common\behavior\ShareCode::class,
    ],
];