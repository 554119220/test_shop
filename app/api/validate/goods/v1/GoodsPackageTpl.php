<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-18 16:08:45
 */
class GoodsPackageTpl extends \think\Validate
{
    protected $rule = [
        'package_name' => [ 'require' ],
        'package_intro' => [ 'require' ],
    ];


    protected $message = [
        'package_name.require' => '包装模板名称必须',


        'package_intro.require' => '包装模板介绍必须',


    ];


    public $scene = [
        'index'     => [],
        'create'    => [ 'package_name', 'package_intro' ],
        'update'    => [ 'package_name', 'package_intro' ],
        'delete'    => [ 'package_id' ],
        'detail'    => [ 'package_id' ],
    ];
}