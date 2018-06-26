<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/4 0004
 * Time: 14:35
 */

namespace app\api\validate\orders\v1;


use think\Validate;

class Address extends Validate
{
    protected $rule = [
        'user_id'       => ['require'],
        'province_id'   => ['require', 'number'],
        'city_id'       => ['require', 'number'],
        'district_id'   => ['require', 'number'],
        'town_id'       => ['number'],
        'street'        => ['require', 'length:5,200'],
        'mobile'        => ['require', 'regex:^1\d{10}'],
        'name'          => ['require', 'length:1,20'],
        'postal_code'   => ['length:6', 'number'],
        'is_default'    => ['in:0,1'],
        'id'            => ['require', 'number'],
    ];

    protected $message  = [
        'user_id.require'       => '请登录',
        'province_id.require'   => '省份不能为空',
        'province_id.number'    => '省份不正确',
        'city_id.require'       => '城市不能为空',
        'city_id.number'        => '城市不正确',
        'district_id.require'   => '地区不能为空',
        'district_id.number'    => '地区不正确',
        'town_id.number'        => '乡镇不正确',
        'is_default.in'         => '默认不正确',
        'mobile.require'        => '手机号码不能为空',
        'mobile.regex'          => '手机号码不正确',
        'name.require'          => '收货人姓名不能为空',
        'postal_code.length'    => '邮政编码为6位数',
        'postal_code.number'    => '邮政编码为6位数字',
        'name.length'           => '收货人姓名长度为1-20个文字',
        'id.require'            => '收货地址不能为空',
        'id.number'             => '收货地址不正确',
        'street.require'        => '详细地址不能为空',
        'street.length'         => '详细地址文本长度在5-200个文字内'
    ];

    public $scene   = [
        'index'         => ['user_id'],
        'create'        => ['user_id', 'province_id', 'city_id', 'district_id', 'town_id', 'street',
            'mobile', 'name', 'postal_code', 'is_default'],
        'modify'        => ['user_id', 'id', 'province_id', 'city_id', 'district_id', 'town_id', 'mobile',
            'street', 'name', 'postal_code', 'is_default'],
        'detail'        => ['user_id'],
        'setdefault'    => ['user_id', 'id'],
        'delete'        => ['user_id', 'id']
    ];
}