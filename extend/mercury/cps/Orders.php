<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15 0015
 * Time: 18:04
 */

namespace mercury\cps;


class Orders extends Cps
{
    #   订单同步
    #   /v1.0/order/sync.json
    public function synchronize()
    {
        $data   = [
            #   商家openid
            'openid'    => '',
            #   商城订单号（子）
            'order_no'  => '',
            #   商品id
            'good_no'   => '',
            #   订购数量
            'good_num'  => '',
            #   商品价格
            'good_price'=> '',
            #   订单总额
            'order_amount'  => '',
            #   cps识别码
            'rn'        => '',
            #   订单状态，0未付款，1已付款，2已收货，-1已关闭
            'status'    => '',
        ];
        return $this->request();
    }

    public function update()
    {
        $data   = [
            #   订单编号
            'order_no'  => '',
            #   订单状态
            'status'    => '',
            #   第三方交易号
            'trade_no'  => '',
            #   第三方交易时间
            'trade_at'  => '',
            #   收货时间
            'received_at'   => '',
            #   退货时间
            'refund_at'     => '',
        ];
        return $this->request();
    }
}