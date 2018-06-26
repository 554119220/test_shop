<?php
namespace app\api\model\ads;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-03-19 10:36:30
 */
use mercury\constants\State;
use app\common\traits\F as Fun;
class AdsPosition extends \think\Model
{
    protected $pk = 'ads_position_id';
    protected $append = [ 'ads_position_type_name', 'ads_position_device_name' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ads_position_create_time';
    protected $updateTime = 'ads_position_update_time';
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
     * ads_position_example_images - 广告位示例图
     */
    protected function getAdsPositionExampleImageAttr($value, $data)
    {
        return Fun::getImages($value);
    }


    /**
     * ads_position_default_images - 缺省图片
     */
    protected function getAdsPositionDefaultImageAttr($value, $data)
    {
        return Fun::getImages($value);
    }




    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */

    function getAdsPositionTypeNameAttr($value, $data)
    {
        return State::ADS_POSITION_TYPE[$data['ads_position_type'] ?? ''] ?? '';
    }

    function getAdsPositionDeviceNameAttr($value, $data)
    {
        return State::ADS_POSITION_DEVICE[$data['ads_position_device'] ?? ''] ?? '';
    }

    /**
     * 生成日期表，用于广告模块
     * 生成最近12个月日历表
     * @param array $param
     * @param date      $param['sday']  广告开始时间
     * @param array         $param['days_use']  投放日期列表
     * @param integer   $param['isuse'] 投放日期是否已被占用
     */
    function calendar($param = []){
        if(isset($param['sday'])) {
            $sday   = explode('-', $param['sday']);
            $year   = $sday[0];
            $month  = $sday[1];
        } else {
            $year   = date('Y');
            $month  = date('m');
        }
        # 起始月份
        $date[] = [
            'year'  => $year,
            'month' => $month,
            'days'  => $param['days_use'],
            'isuse' => $param['isuse'],
        ];

        for ($i = 0; $i < 11; $i++) {
            $month++;
            if($month > 12) {
                $month = 1;
                $year++;
            }
            $date[] = [
                'year'  => $year,
                'month' => $month,
                'days'  => $param['days_use'],
                'isuse' => $param['isuse'],
            ];
        }
        foreach($date as $key => $value){
            $cal = new \lbzy\Org\Util\Calendar($value);
            $date[$key]['cal'] = $cal->display();
        }
        return $date;
    }

    /**
     * 用于广告模块,返回已使用的日期
     * @param integer $position     广告位ID
     * @param integer $sort         第n个位置
     * 返回 已使用日期
     */
    function days_use($position, $sort = 1){
        # 查询条件
        $map['ads_state']       = ['in',[State::ADS_STATE_NORMAL,State::ADS_STATE_WAIT_PAY]];
        $map['ads_position_id'] = $position;
        $map['ads_eday']        = array('gt',date('Y-m-d'));
        $map['ads_sort']        = $sort;
        # 取广告位投放中的记录
        $days_use = [];
        $list = Fun::mApi('ads','Ads')->where($map)->field('ads_days')->select();
        if($list){
            # 已投放的日期
            foreach($list as $val){
                $days_use = array_merge($days_use, @explode(',' , $val['ads_days']));
            }
            $days_use = array_unique($days_use);
        }
        return $days_use;
    }

    function check_in_use($days = [], $days_use)
    {
        return empty(array_intersect($days, $days_use)) ? true : false;
    }

    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */
    
    function listData()
    {
        $field = [
            'ads_id',
            'ads_title',
            'ads_sub_title',
            'ads_descript',
            'ads_images',
            'ads_url',
            'ads_state',
            'ads_sort',
        ];
        return 
        $this->hasMany('Ads','ads_position_id','ads_position_id')
        ->where(['ads_state' => State::STATE_NORMAL, 'ads_eday' => [ 'egt', date('Y-m-d') ]])
        ->where('find_in_set("' . date('Y-m-d') . '",ads_days)')
        ->field($field)
        ->order('ads_sort asc');
    }


}