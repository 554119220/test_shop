<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 17:22
 */

namespace lbzy\sdk\erp;


class Pay extends Erp
{
    /**
     * 创建订单
     *
     * @param array $params
     * @return mixed
     */
    public function createOrders(array $params)
    {
        return $this->api('/mall.v1.order/createOrder', $params);
    }

    /**
     * 获取快捷付款url地址
     *
     * @param array $params
     *              $code       收银台订单号
     *              $openid     买家openid
     * @return mixed
     */
    public function getUrl(array $params)
    {
        return $this->api('/mall.v1.order/quickPayUrl', $params);
    }

    /**
     * 创建多订单
     *
     * @param array $params
     *              $out_order_no
     *              $sub_orders     json
     * @return mixed
     */
    public function createGroupOrder(array $params)
    {
        return $this->api('/mall.v1.order/createGroupOrder', $params);
    }

    /**
     * 订单查询
     *
     * @param $openid
     * @param $order_no
     * @return mixed
     */
    public function queryOrder($openid, $order_no)
    {
        $params = [
            'openid'    => $openid,
            'order_no'  => $order_no
        ];
        return $this->api('/mall.v1.order/queryOrder', $params);
    }

    /**
     * 确认收货
     *
     * @param $openid
     * @param $order_no
     * @param $safe_psw
     * @param bool $is_auto
     * @return mixed
     */
    public function receiveOrder($params = [])
    {
        return $this->api('/mall.v1.order/confirmOrder', $params);
    }

    /**
     * 退货退款
     *
     * @param array $params
     *  order_no
     *  openid
     *  express_price
     *  sku_id
     *  goods_price
     *  safe_psw
     *  is_auto
     * @return mixed
     */
    public function refund(array $params)
    {
        return $this->api('/mall.v1.refund/refundAdd', $params);
    }
}