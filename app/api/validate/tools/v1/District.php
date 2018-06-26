<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 14:50
 */

namespace app\api\validate\tools\v1;


use think\Validate;

class District extends Validate
{
    protected $rule = [
        'id'    => ['require', 'number']
    ];

    protected $message  = [
        'id.require'    => '地区不能为空',
        'id.number'     => '地区不正确',
    ];

    public $scene    = [
        'index' => [],
        'detail'=> ['id']
    ];
}