<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/14 0014
 * Time: 9:37
 */
$data   = [
    // 用户名
    'username'        => 'lwsh01',
    // 密码
    'password'        => 'liaow188',
];
$data1  = include CONF_PATH . "/database.php";
return array_merge($data1, $data);