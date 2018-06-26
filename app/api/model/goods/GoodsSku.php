<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-22 16:02:06
 */
use app\common\traits\F as Fun;
class GoodsSku extends \think\Model
{
    protected $pk = 'goods_sku_id';
    protected $append = [ 'goods_sku_album_key' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'goods_sku_create_time';
    protected $updateTime = 'goods_sku_update_time';
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
     * 图片去掉js join生成的,
     * @param [type] $value [description]
     * @param [type] $data  [description]
     */
    function setGoodsSkuAlbumAttr($value,$data)
    {
        $value = explode(',',trim((string)$value, ','));
        foreach ($value as $ko => $vo) {
            if ( empty($vo) ){
                unset($value[$ko]);
            }
        }
        return $value ? implode(',',$value) : '';
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

    function getGoodsSkuAlbumAttr($value,$data){
        // dump($value);exit;
        if ( $value ) {
            $value = explode(",", (string) $value);
            foreach ($value as $k => $img) {
                $value[$k] = Fun::getImages($img);
            }
        } else {
            $value = [];
        }

        return $value;
    }

    function getGoodsSkuAlbumKeyAttr($value,$data){
        return empty($data['goods_sku_album']) ? [] : explode(",", $data['goods_sku_album'] );
    }


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
        return $this->hasOne("Goods", "goods_id", "goods_id")->field('goods_create_time,goods_update_time',true);
    }

    /**
     * 一对多关联 - goods_params - 商品参数表
     */
    public function GoodsParams()
    {
        return $this->hasMany("GoodsParams", "goods_id", "goods_id")->field('goods_params_id,group_name,group_value');
    }
}