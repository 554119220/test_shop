<?php
namespace app\api\model\goods;
use app\common\traits\F;
use mercury\constants\State;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-02-01 17:40:47
 */
class Channel extends \think\Model
{
    protected $pk = 'channel_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'channel_create_time';
    protected $updateTime = 'channel_update_time';
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
    protected function getChannelImagesAttr($value)
    {
        if (!$value) return '';
        return F::getImages($value);
    }

    protected function getChannelIconAttr($value)
    {
        if (!$value) return '';
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


    /**
     * 一对多关联 - channel_slider - 频道轮播图
     */
    public function ChannelSlider()
    {
        return $this->hasMany("ChannelSlider", "channel_id", "channel_id")
            ->where([
                'channel_state' => State::STATE_NORMAL,
                ])
            ->order('channel_sort asc, channel_slider_id asc')
            ->field('channel_images,channel_title,channel_redirect');
    }


    /**
     * 一对多关联 - goods_category - 商品类目
     */
    public function GoodsCategory()
    {
        return $this->hasMany("GoodsCategory", "category_sid", "channel_bind_category")
            ->where([
                'category_state' => State::STATE_NORMAL,
                'category_sid' => ['gt', State::STATE_DISABLED,
                'category_images' => ['neq' => '']]])
            ->order('category_sort asc, category_id asc')
            ->field('category_icon,category_name,category_id,category_images');
    }


}