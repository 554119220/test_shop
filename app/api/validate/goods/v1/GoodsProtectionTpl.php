<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-18 16:14:15
 */
class GoodsProtectionTpl extends \think\Validate
{
    protected $rule = [
        'protection_name' => [ 'require' ],
        'protection_intro' => [ 'require' ],
    ];


    protected $message = [
        'protection_name.require' => '售后模板名称不能为空',


        'protection_intro.require' => '售后模板介绍不能为空',


    ];


    public $scene = [
        'index' => [],
        'create' => [ 'protection_name', 'protection_intro' ],
        'update' => [ 'protection_name', 'protection_intro' ],
        'delete' => [ 'protection_id' ],
        'detail' => [ 'protection_id' ],
    ];
}