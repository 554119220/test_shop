<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-15 10:09:25
 */
use app\common\traits\F as Fun;
use mercury\constants\State;
class Goods extends \think\Model
{
    protected $pk = 'goods_id';
    protected $append = [ 'goods_state_name', 'goods_images_key' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'goods_create_time';
    protected $updateTime = 'goods_update_time';
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
     * goods_state - 商品状态0已删除，1正常，2已下架，3已违规
     */
    protected function setGoodsStateAttr($value, $data)
    {
        $arr = State::STATE_GOODS_ARRAY;
        return isset($arr[$value]) ? $value : State::STATE_GOODS_UNDER;
    }

    /**
     * goods_recommend - 是否橱窗
     */
    protected function setGoodsRecommendAttr($value, $data)
    {
        $arr = State::STATE_GOODS_RECOMMEND_ARRAY;
        return isset($arr[$value]) ? $value : State::STATE_GOODS_NO_RECOMMEND;
    }

    

    /**
     * goods_have_qualifications - 是否含有资质
     */
    protected function setGoodsHaveQualificationsAttr($value, $data)
    {
        return empty(request()->data['goods_qualifications'] ?? '')  ? State::STATE_DISABLED: State::STATE_NORMAL;
    }

    /**
     * shop_goods_category_ids - 店铺分类
     */
    protected function setShopGoodsCategoryIdsAttr($value, $data)
    {
        return is_array($value) ? implode(',', $value) : (string)$value;
    }


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

    protected function getGoodsImagesAttr($value,$data){
        return Fun::getImages((string) $value );
    }

    protected function getGoodsImagesKeyAttr($value,$data){
        return ($data['goods_images'] ?? '') ;
    }

    /**
     * goods_state - 商品状态0已删除，1正常，2已下架，3已违规
     */
    protected function getGoodsStateNameAttr($value, $data)
    {
        $arr = \mercury\constants\state\Goods::STATE_GOODS_ARRAY;
        return $arr[intval($data['goods_state'] ?? 0)] ?? '';
    }

    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


    /**
     * 一对一关联 - shop - 店铺表
     */
    public function Shop()
    {
        return $this->hasOne("Shop", "shop_id", "shop_id")->field('*');
    }


    /**
     * 一对一关联 - user - 用户表
     */
    public function User()
    {
        return $this->hasOne("\\app\api\\model\\user\\User", "user_id", "seller_user_id");
    }

    /**
     * 一对一关联 - goods_content - 商品详情
     */
    public function GoodsContent()
    {
        return $this->hasOne("GoodsContent", "goods_id", "goods_id");
    }

    /**
     * 一对多关联 - goods_sku - 商品库存表
     */
    public function GoodsSku()
    {
        return $this->hasMany("GoodsSku", "goods_id", "goods_id")->field('*')->limit(1)->order('goods_sku_price asc');
    }

    /**
     * 一对多关联 - goods_sku - 商品库存表
     */
    public function GoodsSku2()
    {
        return $this->hasMany("GoodsSku", "goods_id", "goods_id")->field('*')->order('goods_sku_id asc');
    }

    /**
     * 一对多关联 - goods_sku_group - 商品库存组表
     */
    public function GoodsSkuGroup()
    {
        return $this->hasMany("GoodsSkuGroup", "goods_id", "goods_id")->field('*')->order('sku_group_id asc');
    }

    /**
     * 一对一关联 - goods_express_tpl - 商品运费模板
     */
    public function GoodsExpressTpl()
    {
        return $this->hasOne("GoodsExpressTpl", "express_id", "express_id");
    }


    /**
     * 一对一关联 - goods_package_tpl - 包装模板
     */
    public function GoodsPackageTpl()
    {
        return $this->hasOne("GoodsPackageTpl", "package_id", "package_id");
    }


    /**
     * 一对一关联 - goods_protection_tpl - 售后保障模板
     */
    public function GoodsProtectionTpl()
    {
        return $this->hasOne("GoodsProtectionTpl", "protection_id", "protection_id");
    }

    /**
     * 一对一关联 - goods_category - 商品分类
     */
    public function GoodsCategory()
    {
        return $this->hasOne("GoodsCategory", "category_id", "goods_category_id");
    }

    /**
     * 一对多关联 - goods_params - 商品参数表
     */
    public function GoodsParams()
    {
        return $this->hasMany("GoodsParams", "goods_id", "goods_id");
    }

    /**
     * 一对一关联 - goods_category - 商品分类
     */
    public function brand()
    {
        return $this->hasOne("GoodsBrand", "goods_brand_id", "shop_goods_brand_id")->field('goods_brand_name');
    }

    /**
     * 一对一关联 - goods_category - 商品分类
     */
    public function category()
    {
        return $this->hasOne("GoodsCategory", "category_id", "goods_category_id")->field('category_name');
    }

}