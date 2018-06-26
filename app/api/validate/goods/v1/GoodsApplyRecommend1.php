<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2018-02-26 14:42:31
 */
use app\common\traits\F as Fun;
class GoodsApplyRecommend1 extends \think\Validate
{
    protected $rule = [
        'score_multi'       => [ 'require', 'egt' => 20, 'elt' => 60, 'integer' ],
        'tmall_link' 		=> [ 'require', 'url' ],
        'jd_link' 			=> [ 'require', 'url' ],
        'reason'            => [ 'require' ],
        'goods_sku'         => [ 'require', 'array', 'checkGoodsSku' => '' ],

        'goods_sku_id'              => [ 'require', 'number', 'max' => 10, 'gt' => 0, ],
        'goods_sku_market_price'    => [ 'require', 'number', 'max' => 10, 'gt' => 0, ],
        'goods_sku_price'           => [ 'require', 'number', 'max' => 10, 'gt' => 0, ]
    ];

    protected $field = [
    	'goods_ids' 		=> '商品id',
        'active_price'      => '活动价',
        'market_price'      => '市场价',
        'score_multi'       => '购物积分比例',
        'tmall_link'        => '天猫链接',
        'jd_link'           => '京东链接',
        'reason'            => '报名精选理由',
        'goods_sku'         => '商品属性',

        'goods_sku_id'              => '库存属性id',
        'goods_sku_market_price'    => '市场价',
        'goods_sku_price'           => '价格',
    ];

    


    public $scene = [
        'create' => [
            'goods_ids',
            'tmall_link',
            'jd_link',
            'reason',
            'active_price',
            'market_price',
            'score_multi',
            'goods_sku'
        ],
        'check_goods_sku' => [
            'goods_sku_id',
            'goods_sku_market_price',
            'goods_sku_price',
        ],
    ];

    function checkGoodsSku($value,$data)
    {
        # 基本校验
        // dump($value);exit;
        foreach ($value as $goods_sku) {
            $v = Fun::vApi('goods','GoodsApplyRecommend1');
            if ( false == $v->scene('check_goods_sku')->check($goods_sku) ) {
                return $v->getError();
            }
        }
        # 检测比例是否过大
        if ( false == (new Goods)->checkMulti2($value, request()->param()['score_multi'] ?? 0) ) {
            return '商品价格过低';
        }
        # 是否是商品的属性id
        $goodsSku = Fun::dataAll(Fun::mApi('goods','GoodsSku'),[
            'where' => [
                'goods_id' => request()->data['goods_ids'] ?? 0,
            ],
            'field' => 'goods_sku_id,goods_sku_group_values,goods_sku_price,goods_sku_market_price,goods_id',
        ]);
        if ( count($goodsSku) != count($value)) {
            return '商品错误';
        }
        $goodsSku   = array_column($goodsSku, null, 'goods_sku_id');
        $value      = array_column($value, null, 'goods_sku_id');
        foreach ($goodsSku as $sku_id => $sku) {
            if ( false == isset($value[$sku_id]) ) {
                return '商品错误1';
            }
            $value[$sku_id]['goods_sku_group_values'] = $sku['goods_sku_group_values'];
        }
        # 重新绑定
        $data = request()->data;
        $data['goods_sku'] = array_values($value);
        request()->bind('data', $data);
        # ...
        return true;
    }
}