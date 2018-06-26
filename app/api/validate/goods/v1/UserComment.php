<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/18 0018
 * Time: 15:30
 */

namespace app\api\validate\goods\v1;


use think\Validate;

class UserComment extends Validate
{
    protected $rule = [
        'user_id'       => ['require'],
        'id'            => ['require', 'number'],
        'content'       => ['require', 'length:5,255'],
        'evaluation'    => ['require', 'in:-1,0,1'],
        'is_anonymous'  => ['require', 'in:0,1'],
        'service_fraction'      => ['require', 'number', 'in:1,2,3,4,5'],
        'description_fraction'  => ['require', 'number', 'in:1,2,3,4,5'],
        'logistics_fraction'    => ['require', 'number', 'in:1,2,3,4,5'],
    ];

    protected $message  = [
        'user_id.require'       => '请登录',
        'id.require'            => '评价不能为空',
        'id.number'             => '评价不存在',
        'evaluation.require'    => '评价不能为空',
        'evaluation.in'         => '评价不正确',
        'is_anonymous.require'  => '请选择是否匿名',
        'is_anonymous.in'       => '是否匿名不正确',
        'service_fraction.require'  => '服务评分不能为空',
        'service_fraction.in'       => '服务评分不正确',
        'logistics_fraction.require'    => '物流评分不能为空',
        'logistics_fraction.in'     => '物流评分不正确',
        'description_fraction.require'  => '描述评分不能为空',
        'description_fraction.in'   => '描述评分不正确',
        'content.length'    => '评价内容长度在5-255个文字之间',
        'content.require'   => '评价内容不能为空'
    ];

    public $scene   = [
        'index' => ['user_id'],
        'modify'=> ['user_id', 'id', 'content', 'evaluation', 'is_anonymous', 'service_fraction', 'description_fraction', 'logistics_fraction'],
        'detail'=> ['user_id', 'id'],
        'append'=> ['user_id', 'id', 'content']
    ];
}