<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25 0025
 * Time: 5:01
 */

namespace app\api\controller\orders\v1;


use app\api\service\orders\v1\AddressManager;

/**
 * Class ShopAddress
 * @package app\api\controller\orders\v1
 *
 * @title 商家收货地址
 */
class ShopAddress extends AddressManager
{
    protected $is_seller    = true;
}