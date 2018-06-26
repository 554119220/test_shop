<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-17 14:12:44
 */
class ShopType extends \think\Validate
{
    protected $rule = [
        'shop_type_icon' => [ 'require' ],
        'shop_type_suffix' => [ 'require' ],

        'shop_type_id' => [ 'require' ],
    ];


    protected $message = [
        'shop_type_icon.require' => '店铺类型图标必须',


        'shop_type_suffix.require' => '店铺类型后缀必须',

        'shop_type_id.require' => '店铺类型id必须',
    ];


    public $scene = [
        'create' => [ 'shop_type_icon', 'shop_type_suffix' ],
        'index' => [],
        'detail' => [ 'shop_type_id' ],
    ];
}