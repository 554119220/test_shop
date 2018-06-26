<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-12-13 10:42:04
 */
class GoodsContent extends \think\Validate
{
    protected $rule = [
        'goods_id' => [ 'require' ],
    ];


    protected $message = [
        'goods_id.require' => '商品id必须',


    ];


    public $scene = [
        'detail' => [ 'goods_id' ],
    ];
}