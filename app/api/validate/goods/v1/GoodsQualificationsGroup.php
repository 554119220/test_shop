<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-12-13 10:49:52
 */
class GoodsQualificationsGroup extends \think\Validate
{
    protected $rule = [
        'category_id' => [ 'require' ],
    ];


    protected $message = [
        'category_id.require' => '分类id必须',


    ];


    public $scene = [
        'index' => [ 'category_id' ],
    ];
}