<?php
namespace app\api\model\tools;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-16 15:04:25
 */
class Bank extends \think\Model
{
    protected $pk = 'id';
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
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * is_lock - 是否上锁:1、上锁；0、未上锁
     */
    protected function getIsLockAttr($value, $data)
    {
        return $value;
    }


    /**
     * status - 状态
     */
    protected function getStatusAttr($value, $data)
    {
        return $value;
    }


    /**
     * upid - 上级ID
     */
    protected function getUpidAttr($value, $data)
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