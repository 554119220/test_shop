<?php
namespace app\api\model\orders;
use app\common\traits\F;
use mercury\constants\State;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-20 14:02:06
 */
class Cart extends \think\Model
{
    protected $pk = 'cart_id';
    protected $append = [];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'cart_create_time';
    protected $updateTime = 'cart_update_time';
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
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */
    public function getGoodsImagesAttr($value)
    {
        return F::getImages($value);
    }




    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */
    /**
     * 获取选中的商品
     *
     * @param $user_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function Selected($user_id)
    {
        return $this->where(function ($query) use ($user_id){
            $query->where('user_id', '=', $user_id)->where('goods_is_selected', '=', State::STATE_NORMAL);
        })->select();
    }

    /**
     * 根据商家分组获取数据
     *
     * @param $user_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function ListsByShopId($user_id)
    {
        return $this->where(function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })->group('shop_id')->select();
    }

    /**
     * 根据快递模板获取购物车数据
     *
     * @param $user_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function ListsByExpressId($user_id)
    {
        return $this->where(function ($query) use ($user_id) {
            $query->where('user_id', '=', $user_id);
        })->group('express_id')->select();
    }



    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */

    public function Goods()
    {
        
    }

    public function User()
    {
        
    }
}