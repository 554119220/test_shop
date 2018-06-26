<?php
namespace app\api\model\ads;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-03-14 13:46:40
 */
use app\common\traits\F as Fun;
class AdsSimple extends \think\Model
{
    protected $pk = 'ads_simple_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ads_simple_create_time';
    protected $updateTime = 'ads_simple_update_time';
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
     * ads_simple_image - 图片
     */
    protected function setAdsSimpleImageAttr($value, $data)
    {
        return $value;
    }


    /**
     * ads_simple_status - 状态
     */
    protected function setAdsSimpleStatusAttr($value, $data)
    {
        return $value;
    }




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * ads_simple_image - 图片
     */
    protected function getAdsSimpleImageAttr($value, $data)
    {
        return Fun::getImages($value);
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