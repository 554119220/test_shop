<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-24 14:03:16
 */
use mercury\constants\State;

class GoodsSkuGroup extends \think\Validate
{
    protected $rule = [
        'sku_group_name'    => [ 'require' ],
        'sku_group_value'   => [ 'require', 'array', 'checkValue' => '' ],
    ];


    protected $message = [
        'sku_group_name.require'        => '属性组名称必须',

        'sku_group_value.require'       => '属性组值必须添加',
        'sku_group_value.array'         => '属性组值不正确',
        'sku_group_value.checkValue'    => '属性组值错误',
    ];


    public $scene = [
        'create' => [ 'sku_group_name', 'sku_group_value', 'sku_group_album' ],
    ];

    function checkValue($value,$rule)
    {
        // dump($value);exit;
        # 值是否重复
        if ( count($value) != count(array_unique($value)) ) {
            return '属性值重复';
        }
        # 是否有属性选值
        foreach ($value as $key => $group_value) {
            if ( empty($group_value) && $group_value != '0' ) {
                return '请选择一个属性值';
            }
        }
        # 长度
        if ( count($value) > State::STATE_GOODS_SKU_GROUP_VALUE_MAX ) {
            return '每个属性组只能选个属性值';
        }
        return true;
    }

    function checkAlbum($value,$rule)
    {
        # 是否有属性选值
        foreach ($value as $imgArr) {
            if ( empty($imgArr) || false == isset(array_values($imgArr)[0]) ) {
                return '请上传属性值图片';
            }
            # 长度
            if ( count($imgArr) > State::STATE_GOODS_SKU_GROUP_VALUE_IMAGES_MAX ) {
                return '每个属性值只能上传' . State::STATE_GOODS_SKU_GROUP_VALUE_IMAGES_MAX . '个属性值图片';
            }
        }
        
        return true;
    }
}