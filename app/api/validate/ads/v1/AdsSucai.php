<?php
namespace app\api\validate\ads\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2018-03-19 11:51:37
 */
class AdsSucai extends \think\Validate
{
    protected $rule = [
        'ads_sucai_name' => [ 'chsDash', 'require' ],
        'ads_sucai_images' => [ 'alphaDash', 'require' ],
        'ads_sucai_width' => [ 'require', 'number' ],
        'ads_sucai_height' => [ 'require', 'number' ],
    ];

    protected $field = [
        'ads_sucai_name'=>'素材标题',
        'ads_sucai_images' => '素材地址',
        'ads_sucai_width'  => '素材宽度',
        'ads_sucai_height' => '素材高度',
    ];

    public $scene = [
        'create' => [ 'ads_sucai_name', 'ads_sucai_images','ads_sucai_width','ads_sucai_height' ],
        'update' => [ 'ads_sucai_name', 'ads_sucai_images','ads_sucai_width','ads_sucai_height' ],
    ];
}