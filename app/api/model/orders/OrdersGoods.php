<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/20 0020
 * Time: 13:55
 */

namespace app\api\model\orders;

use app\common\traits\F;
use think\Model;

/**
 * Class OrdersGoods
 * @package app\api\model
 *
 * 订单商品
 */
class OrdersGoods extends Model
{
    public function getGoodsImagesAttr($value)
    {
        return F::getImages($value);
    }
}