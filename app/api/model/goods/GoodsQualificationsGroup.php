<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-01-29 09:14:39
 */
class GoodsQualificationsGroup extends \think\Model
{
    protected $pk = 'goods_qualifications_group_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'goods_qualifications_group_create_time';
    protected $updateTime = 'goods_qualifications_group_update_time';
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
     * goods_qualifications_group_value - 值
     */
    protected function getGoodsQualificationsGroupValueAttr($value, $data)
    {
        return explode(PHP_EOL, (string) $value);
    }


    /**
     * goods_qualifications_group_state - 状态
     */
    protected function getGoodsQualificationsGroupStateAttr($value, $data)
    {
        return $value;
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


}