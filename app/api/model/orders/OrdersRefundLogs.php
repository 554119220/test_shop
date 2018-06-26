<?php
namespace app\api\model\orders;
use app\common\traits\F;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-20 14:28:20
 */
class OrdersRefundLogs extends \think\Model
{
    protected $pk = 'refund_logs_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'refund_logs_create_time';
    protected $updateTime = 'refund_logs_update_time';
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
     * 获取图片
     *
     * @param $value
     * @return array|bool|string
     */
    public function getRefundLogsImagesAttr($value)
    {
        if (!empty($value)) {
            if (strpos($value, ',') === 0) $value = substr($value, 1);
            $value  = explode(',', $value);
            if (empty($value)) return '';
            foreach ($value as &$v) {
                $v  = F::getImages($v);
            }
            return $value;
        }
        return '';
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