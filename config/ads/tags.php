<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17 0017
 * Time: 18:17
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