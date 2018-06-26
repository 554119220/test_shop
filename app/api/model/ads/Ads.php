<?php
namespace app\api\model\ads;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-03-20 10:40:02
 */
use mercury\constants\State;
use app\common\traits\F as Fun;

class Ads extends \think\Model
{
    protected $pk = 'ads_id';
    protected $append = [ 'ads_state_name' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ads_create_time';
    protected $updateTime = 'ads_update_time';
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
     * ads_state - 0=未付款,1=已付款,2=违规,3=取消
     */
    protected function setAdsStateAttr($value, $data)
    {
        return $value;
    }

    /**
     * ads_images - 图片
     */
    protected function setAdsImagesAttr($value, $data)
    {
        return $data['ads_sucai']['ads_sucai_images'] ?? '';
    }
    /**
     * ads_no - 订单号
     */
    protected function setAdsNoAttr($value, $data)
    {
        return Fun::createNo('ADS');
    }

    /**
     * ads_sday - 开始日期
     */
    protected function setAdsSdayAttr($value, $data)
    {
        return $data['ads_sday'] ?? '';
    }

    /**
     * ads_eday - 结束日期
     */
    protected function setAdsEdayAttr($value, $data)
    {
        return $data['ads_eday'] ?? '';
    }




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * ads_images - 广告图片
     */
    protected function getAdsImagesAttr($value, $data)
    {
        return Fun::getImages($value);
    }


    /**
     * ads_days - 投放时段表
     */
    protected function getAdsDaysAttr($value, $data)
    {
        return $value;
    }


    /**
     * ads_pay_time - 付款时间
     */
    protected function getAdsPayTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }




    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */

    /**
     * ads_state_name - 0=未付款,1=已付款,2=违规,3=取消
     */
    protected function getAdsStateNameAttr($value, $data)
    {
        return State::ADS_STATE_ARRAY[$data['ads_state'] ?? ''] ?? '';
    }


    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */

    function position()
    {
        return $this->hasOne('AdsPosition','ads_position_id','ads_position_id');
    }

}