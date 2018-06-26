<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 10:10
 */
return [
    'email' => [
        'host'  => 'smtp.ym.163.com',
        'port'  => 25,
        'user'  => 'bwft@lwzg.net.cn',
        'pass'  => 'BWFT2018',
        'to'    => '1053008099@qq.com,mercury@jozhi.com.cn',  //默认接收人，多个使用逗号隔开
    ],

/*
    'sms'   => [
        'host'      => 'http://39.108.150.216:8808/Index.aspx',
        'name'   	=> 'bwshch',
        'pwd'  		=> '123456',
        'type'    	=> 'pt',
        'userid'    => 670
    ],
	*/
	'sms'   => [
		'host'      => 'http://39.108.150.216:8808/sms.aspx',
        'account'   => 'bwshch',
        'password'  => 'Aou45gy0Oe',
        'action'    => 'send',
        'userid'    => 670
	],
];