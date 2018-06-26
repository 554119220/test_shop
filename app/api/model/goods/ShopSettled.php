<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-17 14:31:56
 */
class ShopSettled extends \think\Model
{
    protected $pk = 'shop_settled_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'shop_settled_create_time';
    protected $updateTime = 'shop_settled_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    //protected $insert = [ 'shop_settled_state' ];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */



    /**
     * shop_settled_content - step详情
     */
    protected function setShopSettledContentAttr($value, $data)
    {
        return serialize($value);
    }

    /**
     * shop_settled_content_bak - step详情
     */
    protected function setShopSettledContentBakAttr($value, $data)
    {
        return serialize($value);
    }




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * shop_settled_content - step详情
     */
    protected function getShopSettledContentAttr($value, $data)
    {
        return unserialize($value);
    }

    /**
     * shop_settled_content_bak - step详情
     */
    protected function getShopSettledContentBakAttr($value, $data)
    {
        return unserialize($value);
    }





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


    /**
     * 一对一关联 - user - 用户表
     */
    public function User()
    {
        return $this->hasOne("app\\api\\model\\user\\User", "user_id", "user_id");
    }


    /**
     * 一对多关联 - shop_settled_audit - 商家入驻审核表
     */
    public function ShopSettledAudit()
    {
        return $this->hasMany("ShopSettledAudit", "shop_settled_id", "shop_settled_id");
    }


}