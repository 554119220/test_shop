<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-29 09:31:38
 */
class UserHistory extends \think\Model
{
    protected $pk = 'user_history_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'user_history_create_time';
    protected $updateTime = 'user_history_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [ 'user_history_day', 'user_history_goods_id', 'user_id' ];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */


    /**
     * user_history_day - 浏览日期
     */
    protected function setUserHistoryDayAttr($value, $data)
    {
        return time();
    }


    /**
     * user_history_goods_id - 商品ID
     */
//    protected function setUserHistoryGoodsIdAttr($value, $data)
//    {
//        return $value;
//    }


    /**
     * user_id - 所属用户
     */
//    protected function setUserIdAttr($value, $data)
//    {
//        return $value;
//    }




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
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