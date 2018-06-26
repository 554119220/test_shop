<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-01-31 14:23:51
 */
namespace app\work\model;
use think\Model;
class GoodsApplyRecommend690 extends Model
{
    protected $table = 'zr_goods_apply_recommend';

    protected function goods()
    {
    	return $this->hasOne('Goods619','goods_id','goods_id');
    }
}
