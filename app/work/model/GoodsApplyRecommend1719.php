<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-02-26 16:47:04
 */
namespace app\work\model;
use think\Model;
class GoodsApplyRecommend1719 extends Model
{
    protected $table = 'zr_goods_apply_recommend1';

    protected function goods()
    {
    	return $this->hasOne('Goods619','goods_id','goods_id');
    }

    /**
     * goods_apply_recommend1_content - 申请信息
     */
    protected function setGoodsApplyRecommend1ContentAttr($value, $data)
    {
        return Fun::json($value ?? []);
    }




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * goods_apply_recommend1_content - 申请信息
     */
    protected function getGoodsApplyRecommend1ContentAttr($value, $data)
    {
        return json_decode($value, true);
    }
}
