<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-21 10:18:37
 */
use mercury\constants\State;
use app\common\traits\F as Fun;
class ShopGoodsCategory extends \think\Model
{
    protected $pk = 'goods_category_id';
    protected $append = [ 'goods_category_state_name', 'goods_category_icon_url' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'goods_category_create_time';
    protected $updateTime = 'goods_category_update_time';
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




    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */

    /**
     * goods_category_state_name
     */
    function getGoodsCategoryStateNameAttr($value,$data)
    {
        return ($data['goods_category_state'] ?? '') == State::STATE_NORMAL ? '正常' : '禁用';
    }

    /**
     * goods_category_icon_url
     */
    function getGoodsCategoryIconUrlAttr($value,$data)
    {
        return Fun::getImages($data['goods_category_icon'] ?? '');
    }

    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


}