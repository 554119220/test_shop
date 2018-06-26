<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 11:39
 */
return [
    // 数据库类型
    'type'            => '\think\mongo\Connection',
    // 服务器地址
    //'hostname'        => '192.168.10.104',
    'hostname'        => '192.168.1.235',
    // 数据库名
    'database'        => 'lwshop',
    // 用户名
    #'username'        => 'zrmall',
    'username'        => 'lwsh01',
    // 密码
    #'password'        => '134115',
    'password'        => 'lwerp188',
    // 端口
    'hostport'        => 38000,
    // 数据库编码默认采用utf8
    'charset'         => 'utf8',
    // 数据库表前缀
    'prefix'          => 'zr_',
    // 数据库调试模式
    'debug'           => true,
];