<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 9:24
 */
return [
    'module_init' => [
        app\common\behavior\Template::class,
        app\common\behavior\SettledAuth::class,
    ],
    'view_filter'   => [
        app\common\behavior\ShareCode::class,
    ],
];