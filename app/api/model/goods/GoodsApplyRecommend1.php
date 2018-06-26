<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-02-26 09:51:22
 */
use app\common\traits\F as Fun;
class GoodsApplyRecommend1 extends \think\Model
{
    protected $pk = 'goods_apply_recommend1_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'goods_apply_recommend1_create_time';
    protected $updateTime = 'goods_apply_recommend1_update_time';
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
     * 一对一关联 - goods - 商品表
     */
    public function Goods()
    {
        return $this->hasOne("Goods", "goods_id", "goods_id");
    }


    /**
     * 一对多关联 - goods_apply_recommend1_audit - 申请精选审核
     */
    public function audit()
    {
        return $this->hasMany("GoodsApplyRecommend1Audit", "goods_apply_recommend1_id", "goods_apply_recommend1_id")->order('goods_apply_recommend1_audit_id desc');
    }


}