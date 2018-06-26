<?php
namespace app\api\model\promotions;
/**
 * 优惠券模型
 * 
 * @author qinzichao
 * @date 2017-12-4 10:46
 */
class Coupon extends \think\Model
{
    protected $pk = 'coupon_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'coupon_create_time';
    protected $updateTime = 'coupon_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */


   
    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


    

//     /**
//      * 一对一关联 - goods -商品表
//      */
//     public function Goods()
//     {
//         return $this->belongsTo("\\app\api\\model\\goods\\Goods", "goods_id", "goods_id");
//     }
    
//     /**
//      * 一对一关联 - shop -店铺表
//      */
//     public function Shop()
//     {
//         return $this->belongsTo("\\app\api\\model\\goods\\Shop", "shop_id", "shop_id");
//     }

}
