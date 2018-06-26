<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 15:41:17
 */
class GoodsComment extends \think\Validate
{
    protected $rule = [
        'goods_id' => [ 'require', 'number' ],
        'goods_comment_id' => [ 'require', 'number' ],
        'sku_id' => [ 'require', 'number' ],
        'shop_no' => [ 'require', 'length:22' ],
        'evaluation' => [ 'require', 'in:0,1,-1' ],
        'is_append' => [ 'require', 'in:0,1' ],
        'content' => [ 'require', 'length:5,255' ],
        'is_anonymous' => [ 'require', 'in:0,1' ],
        'service_fraction'      => ['require', 'number', 'min:1', 'max:5'],
        'description_fraction'  => ['require', 'number', 'min:1', 'max:5'],
        'logistics_fraction'    => ['require', 'number', 'min:1', 'max:5'],
    ];

    protected $message = [
        'goods_id.require' => '商品id不能为空',


        'goods_comment_id.require' => '评价id不能为空',


        'sku_id.require' => '商品SKU id不能为空',


        'shop_no.require' => '商家订单号不能为空',


        'evaluation.require' => '评价不能为空',


        'is_append.require' => '是否追加评价不能为空',


        'content.require' => '评价内容不能为空',

        'is_anonymous.require' => '是匿名不能为空',

        'service_fraction.require'  => '服务评分不能为空',
        'description_fraction.require'      => '描述评分不能为空',
        'logistics_fraction.require'        => '物流评分不能为空',

        'content.length'   => '评价内容长度在5-255个文字之间',
    ];


    public $scene = [
        'index' => [ 'goods_id' ],
        'create' => [ 'sku_id', 'shop_no', 'evaluation', 'content', 'is_anonymous', 'is_append', 'service_fraction', 'description_fraction', 'logistics_fraction' ],
        'detail' => [ 'goods_comment_id' ],
        'statistics'    => [ 'goods_id' ],
    ];
}