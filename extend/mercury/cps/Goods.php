<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15 0015
 * Time: 18:19
 */

namespace mercury\cps;


class Goods extends Cps
{
    public function synchronize()
    {
        $data   = [
            #   商品id
            'goods_id'          => '',
            #   所属类目
            'goods_category_id' => '',
            #   商品名称
            'goods_name'        => '',
            #   商品主图
            'goods_images'      => '',
            #   商品价格
            'goods_min_price'   => '',
            #   商品库存
            'goods_sku_num'     => '',
            #   商品销量
            'goods_sale_num'    => '',
            #   商品状态，1正常，0下架
            'goods_state'       => '',
        ];
    }
}