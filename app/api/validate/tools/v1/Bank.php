<?php

namespace app\api\validate\tools\v1;

use think\Validate;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 14:47
 */
class Bank extends Validate
{
    protected $rule = [
        'id'    => ['require', 'number']
    ];

    protected $message  = [
        'id.require'    => '银行不能为空',
        'id.number'     => '银行不正确',
    ];

    public $scene    = [
        'detail'    => ['id'],
        'index'     => [],
    ];
}