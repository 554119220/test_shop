<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 14:51:43
 */
class GoodsBrand extends \think\Validate
{
    protected $rule = [
        'goods_brand_id' => [ 'require' ],
    ];


    protected $message = [
        'goods_brand_id.require' => '品牌id必须',
    ];


    public $scene = [
        'index' => [],
        'detail' => [ 'goods_brand_id' ],
    ];
}