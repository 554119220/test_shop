<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/16 0016
 * Time: 10:57
 */

namespace app\api\validate\search\v1;


class Goods
{
    protected $rule = [
        'user_id'   => ['require', 'number'],
        'category'  => ['require', 'number']
    ];

    protected $message  = [
        'user_id.require'   => '请登录',
        'user_id.number'    => '用户不存在',
        'category.require'  => '商品类目不能为空',
        'category.number'   => '商品类目不存在',
    ];

    public $scene   = [
        'index' => [],
        'like'  => [],
        'push'  => ['user_id', 'category']
    ];
}