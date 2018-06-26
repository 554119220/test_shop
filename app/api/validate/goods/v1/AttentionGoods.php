<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 9:36
 */

namespace app\api\validate\goods\v1;


use think\Validate;

class AttentionGoods extends Validate
{
    protected $rule = [
        'user_id'   => ['require'],
        'sku_id'    => ['require', 'number'],
        'id'        => ['require', 'number']
    ];

    protected $message  = [
        'user_id.require'   => '请登录',
        'sku_id.require'    => '商品不能为空',
        'sku_id.number'     => '商品不存在',
        'id.require'        => '删除对象不能为空',
        'id.number'         => '删除对象不存在',
    ];

    public $scene   = [
        'index'     => ['user_id'],
        'create'    => ['user_id', 'sku_id'],
        'delete'    => ['user_id', 'id'],
        'have'      => ['sku_id']
    ];
}