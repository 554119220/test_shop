<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6 0006
 * Time: 18:09
 */

namespace app\api\validate\tools\v1;


use think\Validate;

class Express extends Validate
{
    protected $rule = [
        'company_id'    => ['require', 'number'],
        'express_no'    => ['require', 'length:6,32', 'alphaNum'],
    ];

    protected $message  = [
        'company_id.require'    => '快递公司不能为空',
        'company_id.number'     => '快递公司不存在',
        'express_no.require'    => '快递单号不能为空',
        'express_no.length'     => '快递单号不正确',
        'express_no.alphaNum'   => '快递单号不正确',
    ];

    public $scene   = [
        'index' => ['company_id', 'express_no']
    ];
}