<?php
namespace app\api\validate\promotions\v1;



/**
 * 拼团
 * 
 * @author qinzichao
 * @date 2017-12-4 10:46
 */
class Pintuan extends \think\Validate
{
    protected $rule = [
        'goods_id'      => [ 'require', 'number','gt:0' ],
        'tuan_price'      => [ 'require','gt:0' ],
        'pintuan_id'      => [ 'require', 'number','gt:0' ],
    ];


    protected $message = [
        'goods_id.require'      => '商品不能为空',
        'goods_id.number'       => '商品不存在',
        'goods_id.gt'       => '商品ID必须大于0',
        
        'tuan_price.require'       => '拼团价格不能为空',
        'tuan_price.gt'       => '拼团价格必须大于0',
        
        'pintuan_id.require'      => '拼团ID不能为空',
        'pintuan_id.number'       => '拼团ID非数字',
        'pintuan_id.gt'       => '拼团ID必须大于0',
    ];


    public $scene = [
        'check' => ['goods_id'],
        'create' => ['goods_id','tuan_price'],
        'update' => ['pintuan_id'],
        'drop' => ['pintuan_id'],
    ];
}