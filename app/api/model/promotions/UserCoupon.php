<?php
namespace app\api\model\promotions;
/**
 * 用户优惠券模型
 * 
 * @author qinzichao
 * @date 2017-12-4 10:46
 */
class UserCoupon extends \think\Model
{
    protected $pk = 'user_coupon_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'user_coupon_create_time';
    protected $updateTime = 'user_coupon_update_time';
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


    



}
