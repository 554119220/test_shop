<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-01-30 09:39:24
 */
use mercury\constants\State;
class GoodsApplyRecommend extends \think\Model
{
    protected $pk = 'goods_apply_recommend_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'goods_apply_recommend_create_time';
    protected $updateTime = 'goods_apply_recommend_update_time';
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
     * goods_ids - 商品
     */
    protected function getGoodsIdsAttr($value, $data)
    {
        return explode(',',(string)$value);
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

    protected function audit()
    {
        return $this->hasMany('GoodsApplyRecommendAudit', 'goods_apply_recommend_id','goods_apply_recommend_id')->order('goods_apply_recommend_audit_id desc');
    }

    protected function goods()
    {
        return $this->hasOne('Goods', 'goods_id','goods_id');
    }
}