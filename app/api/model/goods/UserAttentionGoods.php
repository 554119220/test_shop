<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-29 09:30:16
 */
class UserAttentionGoods extends \think\Model
{
    protected $pk = 'user_attention_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'user_attention_create_time';
    protected $updateTime = 'user_attention_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [ 'goods_id', 'goods_price' ];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */


    /**
     * user_attention_goods_id - 关注的商品ID
     */
//    protected function setUserAttentionGoodsIdAttr($value, $data)
//    {
//        return $value;
//    }


    /**
     * user_attention_goods_price - 关注时商品的价格
     */
//    protected function setUserAttentionGoodsPriceAttr($value, $data)
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
    /**
     * 关联商品
     *
     * @return \think\model\relation\HasOne
     */
    public function goods()
    {
        return $this->hasOne('Goods', 'goods_id', 'goods_id');
    }

    /**
     * 关联SKU
     *
     * @return $this|\think\model\relation\HasOne
     */
    public function sku()
    {
        return $this->hasOne('GoodsSku', 'goods_id', 'goods_id')->limit(1)->order('goods_sku_price asc');
    }
    
}