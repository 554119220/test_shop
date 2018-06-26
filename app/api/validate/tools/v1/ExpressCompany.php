<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 14:52
 */

namespace app\api\validate\tools\v1;


use think\Validate;

class ExpressCompany extends Validate
{
    protected $rule = [
        'id'    => ['require', 'number']
    ];

    protected $message  = [
        'id.require'    => '快递公司不能为空',
        'id.number'     => '快递公司不存在'
    ];

    public $scene    = [
        'index' => [],
        'group' => [],
        'detail'=> ['id'],
    ];
}