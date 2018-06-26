<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-01-29 09:17:11
 */
class GoodsQualifications extends \think\Model
{
    protected $pk = 'goods_qualifications_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'qualifications_create_time';
    protected $updateTime = 'qualifications_update_time';
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
     * group_value - 组值
     */
    protected function setQualificationsValueAttr($value, $data)
    {
        return json_encode(is_array($value) ? $value : (string) $value);
    }


    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
     ****************************************************************************************************
     */

    /**
     * group_value - 组值
     */
    protected function getQualificationsValueAttr($value, $data)
    {
        return json_decode($value,true);
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