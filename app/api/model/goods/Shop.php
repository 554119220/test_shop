<?php
namespace app\api\model\goods;
use app\common\traits\F;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-20 16:37:56
 */
class Shop extends \think\Model
{
    protected $pk = 'shop_id';
    protected $append = [ 'province_name','city_name', 'district_name', 'town_name','shop_logo_key' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'shop_create_time';
    protected $updateTime = 'shop_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    //protected $insert = [ 'shop_state', 'shop_name', 'shop_domain' ];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */


    /**
     * shop_state - 店铺状态，0已删除，1正常，2已冻结，3已关闭，4已被强制关闭
     */
    protected function setShopStateAttr($value, $data)
    {
        return \mercury\constants\state\Shop::STATE_SHOP_NORMAL;
    }


    /**
     * shop_name - 店铺名称
     */
    protected function setShopNameAttr($value, $data)
    {
        return $value.db('shop_type')->where([ 'shop_type_id' => (int) $data['shop_type_id'] ])->value('shop_type_suffix') ?? '';
    }


    /**
     * shop_domain - 域名
     */
    protected function setShopDomainAttr($value, $data)
    {
        return $value;
    }

    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */
    /**
     * 获取商家logo
     *
     * @param $value
     * @return string
     */
    protected function getShopLogoAttr($value)
    {
        return F::getImages($value);
    }



    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */

    /**
     * province_name - 省份名字
     */
    protected function getProvinceNameAttr($value,$data)
    {
        return db('area')->where([ 'id' => (int) $data['shop_province_id'] ])->value('a_name') ?? '未知省份名字';
    }

    /**
     * city_name - 城市名字
     */
    protected function getCityNameAttr($value,$data)
    {
        return db('area')->where([ 'id' => (int) $data['shop_city_id'] ])->value('a_name') ?? '未知城市名字';
    }

    /**
     * district_name - 地区名字
     */
    protected function getDistrictNameAttr($value,$data)
    {
        return db('area')->where([ 'id' => (int) $data['shop_district_id'] ])->value('a_name') ?? '未知地区名字';
    }

    /**
     * town_name - 乡镇名字
     */
    protected function getTownNameAttr($value,$data)
    {
        return db('area')->where([ 'id' => (int) $data['shop_town_id'] ])->value('a_name') ?? '未知乡镇名字';
    }


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
        return $this->hasOne("app\\api\\model\\user\\User", "user_id", "user_id");
    }

    /**
     * 一对一关联 - shoptype - 用户表
     */
    public function Shoptype()
    {
        return $this->hasOne("app\\api\\model\\user\\ShopType", "shop_type_id", "shop_type_id")->field('shop_type_suffix');
    }


    /**
     * 一对多关联 - shop_goods_brand - 商家入住品牌
     */
    public function ShopGoodsBrand()
    {
        return $this->hasMany("ShopGoodsBrand", "shop_id", "shop_id");
    }


    /**
     * 一对多关联 - shop_goods_category - 商家商品类目
     */
    public function ShopGoodsCategory()
    {
        return $this->hasMany("ShopGoodsCategory", "shop_id", "shop_id");
    }

    protected function getShopLogoKeyAttr($value,$data){
        return ($data['shop_logo'] ?? '') ;
    }

}