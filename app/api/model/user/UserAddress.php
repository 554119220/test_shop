<?php
/**
 * Created by PhpStorm.
 * User: yy
 * Date: 2017/11/24
 * Time: 11:27
 */
namespace app\api\model\user;

class UserAddress extends \think\model
{
    protected $pk = 'address_id';
//    protected $append = [];
//    protected $resultSetType = 'array';
//    protected $autoWriteTimestamp = true;
//    protected $createTime = 'address_create_time';
//    protected $updateTime = 'address_update_time';
//    protected $dateFormat = 'Y-m-d H:m:s';
//    protected $auto = [];
    protected $insert = [ 'user_id', 'address_province_id', 'address_city_id', 'address_district_id', 'address_town_id', 'address_street', 'address_name', 'address_mobile', 'address_postal_code', 'address_email', 'address_tel', 'address_is_default'];

    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */

}