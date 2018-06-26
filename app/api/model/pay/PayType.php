<?php
namespace app\api\model\pay;
use app\common\traits\F;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-12-19 12:01:21
 */
class PayType extends \think\Model
{
    protected $pk = 'pay_type_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'pay_type_create_time';
    protected $updateTime = 'pay_type_update_time';
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
    public function getPayTypeIconAttr($value)
    {
        return F::getImages($value);
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