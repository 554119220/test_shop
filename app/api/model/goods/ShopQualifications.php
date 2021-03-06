<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-12-28 10:14:23
 */
class ShopQualifications extends \think\Model
{
    protected $pk = 'shop_qualifications_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 和 自动完成 **************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
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


    /**
     * 一对一关联 - shop_type - 店铺类型
     */
    public function ShopType()
    {
        return $this->hasOne("ShopType", "shop_type_id", "shop_type_id");
    }


}