<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 9:36
 */

namespace app\api\validate\goods\v1;


use think\Validate;

class AttentionShop extends Validate
{
    protected $rule = [
        'user_id'   => ['require'],
        'shop_id'   => ['require', 'number'],
        'id'        => ['require', 'number']
    ];

    protected $message  = [
        'user_id.require'   => '请登录',
        'shop_id.require'   => '商家不能为空',
        'shop_id.number'    => '商家不存在',
        'id.require'        => '操作对象不能为空',
        'id.number'         => '操作对象不存在'
    ];

    public $scene   = [
        'index' => ['user_id'],
        'create'=> ['user_id', 'shop_id'],
        'delete'=> ['user_id', 'id']
    ];
}