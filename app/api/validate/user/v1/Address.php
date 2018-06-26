<?php
/**
 * Created by PhpStorm.
 * User: yy
 * Date: 2017/11/20
 * Time: 10:59
 */
namespace app\api\validate\user\v1;

class Address extends \think\Validate
{
    protected $rule = [

        'user_id' => ['require'],
        'address_id' => ['require'],
        'address_province_id' => ['require'],
        'address_city_id' => ['require'],
        'address_district_id' => ['require'],
        'address_town_id' => ['require'],
        'address_street' => ['require'],
        'address_mobile' => ['require',  'regex' => '/^1[0-9]{10}$/'],
        'address_name' => ['require'],
        'address_postal_code' => ['require', 'regex' => '/^[0-9]{6}$/'],
        'address_is_default' => ['require'],
    ];

    protected $message = [
        'user_id.require' => 'user_id不能为空',
        'address_province_id.require' => '省份ID不能为空',
        'address_city_id.require' => '城市ID不能为空',
        'address_district_id.require' => '地区id不能为空',
        'address_town_id.require' => '城镇ID不能为空',
        'address_street.require' => '街道地址不能为空',

        'address_mobile.require' => '联系手机号码不能为空',
        'address_mobile.regex' => '手机号码格式错误',

        'address_name.require' => '收件人姓名不能为空',

        'address_postal_code.require' => '邮编不能为空',
        'address_postal_code.regex' => '邮编格式错误',

        'address_id.require' => 'address_id不能为空',
        'address_is_default.require' => '是否默认不能为空',
    ];

    public $scene = [
        'index' => [ 'user_id'],
        'create' => [ 'user_id', 'address_province_id', 'address_city_id', 'address_district_id', 'address_town_id', 'address_street', 'address_mobile', 'address_name', 'address_postal_code'],
        'update' => [ 'user_id', 'address_id', 'address_province_id', 'address_city_id', 'address_district_id', 'address_town_id', 'address_street', 'address_mobile', 'address_name', 'address_postal_code'],
        'delete' => [ 'user_id', 'address_id'],
        'setDefault' => [ 'user_id', 'address_id', 'address_is_default'],
    ];
}