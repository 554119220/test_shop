<?php
namespace app\api\model\goods;
use mercury\constants\State;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-15 11:39:47
 */
class GoodsComment extends \think\Model
{
    protected $pk = 'goods_comment_id';
    protected $append = [ 'goods_comment_evaluation_name', 'goods_comment_is_effect_name'];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'goods_comment_create_time';
    protected $updateTime = 'goods_comment_update_time';
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
     * goods_commnet_is_anonymous - 是否匿名，0否，1是
     */
    protected function setGoodsCommentIsAnonymousAttr($value, $data)
    {
        return (int) $value ? 1 : 0;
    }


    /**
     * goods_comment_service_fraction - 服务分数
     */
    protected function setGoodsCommentServiceFractionAttr($value, $data)
    {
        $value = (float) $value;
        if ( $value > 5 || $value < 0 ) {
            $value = 5;
        }
        return $value;
    }


    /**
     * goods_comment_logistics_fraction - 物流分数
     */
    protected function setGoodsCommentLogisticsFractionAttr($value, $data)
    {
        $value = (float) $value;
        if ( $value > 5 || $value < 0 ) {
            $value = 5;
        }
        return $value;
    }


    /**
     * goods_comment_description_fraction - 商品描述
     */
    protected function setGoodsCommentDescriptionFractionAttr($value, $data)
    {
        $value = (float) $value;
        if ( $value > 5 || $value < 0 ) {
            $value = 5;
        }
        return $value;
    }


    /**
     * goods_comment_evaluation - 评价-1差评，0中评，1好评
     */
    protected function setGoodsCommentEvaluationAttr($value, $data)
    {
        $sArr = \mercury\constants\state\Comment::STATE_COMMENT_ARRAYS;

        return isset($sArr[$value]) ? $value : \mercury\constants\state\Comment::STATE_COMMENT_GOOD;
    }


    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * goods_comment_effect_time - 生效时间
     */
    protected function getGoodsCommentEffectTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }


    /**
     * 设置评价描述
     * @return [type] [description]
     */
    function getGoodsCommentEvaluationNameAttr($value, $data)
    {
        $sArr = \mercury\constants\State::STATE_COMMENT_ARRAYS;
        return $sArr[$data['goods_comment_evaluation']] ?? '好评';
    }

    /**
     * 是否生效
     *
     * @param $value
     * @param $data
     * @return string
     */
    public function getGoodsCommentIsEffectNameAttr($value, $data)
    {
        if (!isset($data['goods_comment_is_effect'])) return '';
        return State::STATE_COMMENT_EFFECT_ARRAYS[$data['goods_comment_is_effect']] ? : '';
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
     * 一对一关联 - user - 用户表
     */
    public function User()
    {
        return $this->hasOne("\\app\\api\\model\\user\\User", "user_id", "buyer_user_id")->field([ 'user_id', 'user_nick', 'user_avatar' ]);
    }


    /**
     * 一对一关联 - shop - 店铺表
     */
    public function Shop()
    {
        return $this->hasOne("Shop", "shop_id", "shop_id");
    }


    /**
     * 一对多关联 - goods_comment_content - 评价内容管理表
     */
    public function GoodsCommentContent()
    {
        return $this->hasOne("GoodsCommentContent", "goods_comment_id", "goods_comment_id");
    }

    /**
     * 商品详情
     *
     * @return \think\model\relation\HasOne
     */
    public function goods()
    {
        return $this->hasOne('Goods', 'goods_id', 'goods_id');
    }

    /**
     * 商品详情
     *
     * @return \think\model\relation\HasOne
     */
    public function OrdersGoods()
    {
        return $this->hasOne('\\app\\api\\model\\orders\\OrdersGoods', 'orders_goods_id', 'orders_goods_id');
    }

    /**
     * goods sku
     *
     * @return \think\model\relation\HasOne
     */
    public function sku()
    {
        return $this->hasOne('GoodsSku', 'goods_sku_id', 'goods_sku_id');
    }
}
