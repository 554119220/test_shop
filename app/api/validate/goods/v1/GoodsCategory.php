<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 15:16:08
 */
class GoodsCategory extends \think\Validate
{
    protected $rule = [
        'category_id' => [ 'require' ],
        'category_name' => [ 'require' ],
        'category_images' => [ 'require' ],
        'category_icon' => [ 'require' ],
    ];


    protected $message = [
        'category_id' => '类目id必须',

        'category_name.require' => '类目名称必须',


        'category_images.require' => '类目图片必须',


        'category_icon.require' => '类目icon必须',


    ];


    public $scene = [
        'index' => [],
        'create' => [ 'category_name', 'category_icon', 'category_images' ],
        'update' => [ 'category_id', 'category_name', 'category_icon', 'category_images' ],
        'delete' => [ 'category_id' ],
        'detail' => [ 'category_id' ],
    ];
}