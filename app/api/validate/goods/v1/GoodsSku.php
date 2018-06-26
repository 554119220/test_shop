<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-24 14:23:20
 */
use app\common\traits\F as Fun;

class GoodsSku extends \think\Validate
{
    protected $rule = [
        'goods_sku_price'           => [ 'require', 'float', 'gt' => 0.5 ],
        'goods_sku_market_price'    => [ 'require', 'float', 'gt' => 0.5 ],
        'goods_sku_cost_price'      => [ 'require', 'float', 'gt' => 0.5 ],
        'goods_sku_num'             => [ 'require', 'integer', 'egt' => 0 ],
        // 'goods_sku_sale_num'        => [ 'require', 'integer', 'egt' => 0 ],
        'goods_sku_group_values'    => [ 'require' ],
        // 'goods_sku_album'           => [ 'require' ],
        'goods_sku_weight'          => [ 'require', 'float', 'egt' => 0 , 'checkGoodsSkuId' => '' ],
    ];


    protected $message = [
        
        'goods_sku_price.require'           => '库存销售价必须',
        'goods_sku_price.float'             => '库存销售价错误',
        'goods_sku_price.gt'                => '库存销售价必须大于0.5',

        'goods_sku_market_price.require'    => '库存市场价必须',
        'goods_sku_market_price.float'      => '库存市场价错误',
        'goods_sku_market_price.gt'         => '库存市场价必须大于0.5',

        'goods_sku_cost_price.require'      => '库存成本价必须',
        'goods_sku_cost_price.float'        => '库存成本价错误',
        'goods_sku_cost_price.gt'           => '库存成本价必须大于0.5',

        'goods_sku_num.require'             => '库存数量必须',
        'goods_sku_num.integer'             => '库存数量必须是整数',
        'goods_sku_num.egt'                 => '库存数量不能小于0',

        // 'goods_sku_sale_num.require'        => '库存销量价必须',
        // 'goods_sku_sale_num.integer'        => '库存销量必须是整数',
        // 'goods_sku_sale_num.egt'            => '库存销量不能小于0',

        'goods_sku_group_values.require'    => '商品库存名称必须',

        // 'goods_sku_album.require'           => '商品相册必须',

        'goods_sku_weight.require'          => '库存重量必须',
        'goods_sku_weight.float'            => '库存重量错误',
        'goods_sku_weight.egt'              => '库存重量不能小于0',
        'goods_sku_weight.checkGoodsSkuId'  => '商品库存错误',
    ];


    public $scene = [
        'create' => [
            'goods_sku_price',
            'goods_sku_market_price',
            'goods_sku_cost_price',
            'goods_sku_num',
            'goods_sku_group_values',
            'goods_sku_sale_num',
            'goods_sku_images',
            'goods_sku_album',
            'goods_sku_weight',
        ],
    ];

    /**
     * 检测goods_sku_id ，编辑时检测
     * @param  [type] $value [description]
     * @param  [type] $rule  [description]
     * @return [type]        [description]
     */
    function checkGoodsSkuId($value,$rule,$data)
    {
        $id = intval($data['goods_sku_id'] ?? 0);
        // dump($data);exit;
        if ( $id < 0 ) {
            return false;
        }
        if ( $id > 0 ) {
            $sku = Fun::dataDetail(Fun::mApi('goods','GoodsSku'), $id);
            # 不存在或者不是用户的
            if ( empty($sku) || $sku['goods_id'] != (request()->data['goods_id'] ?? -1) ){
                return '商品库存错误';
            }
        }
        return true;
    }
}