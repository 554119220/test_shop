<?php
namespace app\api\validate\orders\v1;

/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 11:09:35
 */
class Cart extends \think\Validate
{
    protected $rule = [
        'goods_id'      => [ 'require', 'number' ],
        'goods_sku_id'  => [ 'require', 'number' ],
        'goods_num'     => [ 'number' , 'require', 'between' => [1,999]],
        'user_id'       => ['require', 'number'],
        'cart_id'       => ['require', 'number'],
        'cart_ids'      => ['require'],
        'shop_id'       => ['require', 'number', 'gt:0']
    ];


    protected $message = [
        'goods_id.require'      => '商品不能为空',
        'goods_id.number'       => '商品不存在',
        'goods_sku_id.require'  => '商品属性不能为空',
        'goods_sku_id.number'   => '商品库存不存在',
        'goods_num.number'  => '购买数量必须为数字',
        'goods_num.require'  => '数量不能为空',
        'goods_num.between'  => '数量介于1-999之间',
        'user_id.require'   => '请登录',
        'user_id.number'    => '用户不存在',
        'cart_id.require'   => '操作对象不能为空',
        'cart_id.number'    => '操作对象不正确',
        'cart_ids.require'  =>  '请选择商品',
        'shop_id.require'   => '商家不能为空',
        'shop_id.number'    => '商家不存在',
        'shop_id.gt'        => '商家不存在'
    ];


    public $scene = [
        'create'    => ['goods_id', 'goods_sku_id', 'goods_num', 'user_id', 'shop_id'],
        'index'     => ['user_id'],
        'plusnum'   => ['user_id', 'cart_id'],
        'lessnum'   => ['user_id', 'cart_id'],
        'setnum'    => ['user_id', 'cart_id', 'goods_num'],
        'confirm'   => ['user_id', 'cart_ids'],
        'delete'    => ['user_id', 'cart_ids'],
        'buyNow'    => ['user_id', 'goods_id', 'goods_num', 'goods_sku_id']
    ];
}