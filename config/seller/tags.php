<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 14:26
 */
return [
    'module_init'   => [
        \app\common\behavior\Template::class,
        \app\common\behavior\SellerAuth::class,
    ],
    'view_filter'   => [
        app\common\behavior\ShareCode::class,
    ],
];