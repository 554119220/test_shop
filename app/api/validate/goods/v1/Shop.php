<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-20 16:13:43
 */
class Shop extends \think\Validate
{
    protected $rule = [
        'shop_name' => [ 'require' ],
        'shop_tel' => [ 'require' ],
        'shop_mobile' => [ 'require' ],
        'shop_domain' => [ 'require','alphaNum' ],
        'shop_contect_person' => [ 'require' ],
        'province_id' => [ 'require' ],
        'city_id' => [ 'require' ],
        'district_id' => [ 'require' ],
        'town_id' => [ 'require' ],
        'shop_street' => [ 'require' ],
        'shop_description' => [ 'require' ],
        'shop_type_id' => [ 'require' ],
        'shop_logo' => [ 'require' ],
        'shop_id' => [ 'require' ],
    ];


    protected $message = [
        'shop_name.require' => '店铺名称必须',


        'shop_tel.require' => '电话必须',


        'shop_mobile.require' => '手机号必须',


        'shop_domain.require' => '店铺域名必须',

        'shop_domain.alphaNum' => '店铺域名只能是数字和字母',


        'shop_contect_person.require' => '联系人必须',


        'shop_province_id.require' => '省份必须',


        'shop_city_id.require' => '城市必须',


        'shop_district_id.require' => '所在地区必须',


        'shop_town_id.require' => '乡镇必须',


        'shop_street.require' => '街道地址必须',


        'shop_description.require' => '店铺介绍必须',


        'shop_type_id.require' => '店铺类型必须',


        'shop_logo.require' => '店铺logo必须',


        'shop_id.require' => '店铺id必须',


    ];


    public $scene = [
        'create' => [ 'shop_name', 'shop_tel', 'shop_mobile', 'shop_contect_person', 'shop_province_id', 'shop_city_id', 'shop_district_id', 'shop_town_id', 'shop_street', 'shop_description', 'shop_logo' ],
        'detail' => [ 'shop_id' ],
        'update' => [ 'shop_id', 'shop_name', 'shop_tel', 'shop_mobile', 'shop_contect_person', 'shop_province_id', 'shop_city_id', 'shop_district_id', 'shop_town_id', 'shop_street', 'shop_description', 'shop_logo' ],
        'set_domain' => [ 'shop_domain' ],
        'shop_list' => [],
        'category_list' => [],
    ];
}