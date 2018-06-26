<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 18:59
 */

namespace app\api\logic\orders\v1;

use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
use traits\think\Instance;

/**
 * Class Refund
 * @package app\api\logic\orders\v1
 *
 * 退款
 */
class Refund
{
    use Instance;

    /**
     * 退款数据验证
     *
     * @param array $params
     * @param int $goods_id
     * @param string $orders_shop_no
     * @param int $num
     * @param float $refund_amount
     * @param float $refund_express_amount
     * @return array|int
     */
    public function create(array $params)
    {
        try {
            $map    = [
                'orders_goods_id'   => $params['goods_id'],
                'orders_shop_no'    => $params['orders_shop_no'],
            ];

            #   退款中的数据
            $cnt  = db('orders_refund')->query(function ($query) use ($map) {
                $query->where($map)
                    ->where('orders_refund_state', '<>', State::STATE_REFUNDS_CANCEL)
                    ->where('orders_refund_state', '<>', State::STATE_REFUNDS_SUCCESS)
                    ->field("SUM(orders_refund_score) AS score, 
                    SUM(orders_refund_amount) AS amount, 
                    SUM(orders_refund_num) AS num, 
                    SUM(orders_refund_express_amount) as express");
            })->find();
            #   取得当前退款的订单商品
            $data   = db('orders_goods')->where($map)->find();
            $scores = $data['goods_score_multi'] * 100 * $params['refund_amount'];
            $data['goods_refund_num']     += $cnt['num'] ? $cnt['num'] : 0;
            $data['goods_refund_amount']  += $cnt['amount'] ? $cnt['amount'] : 0;
            $scores                       += $cnt['express'] ? $cnt['express'] : 0;

            #   判断可退数量
            if ($data['goods_refund_num'] < $params['num'])
                throw new ResponseException(Code::CODE_OTHER_FAIL, "您只能退 {$data['goods_refund_num']} 件商品");

            #   判断可退金额
            if ($data['goods_refund_amount'] < $params['refund_amount'])
                throw new ResponseException(Code::CODE_OTHER_FAIL, "您只能退 {$data['goods_refund_amount']} 金额");

            #   判断可退积分
            if ($data['goods_refund_score'] < $scores)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "积分计算错误");

            #   判断是否可以退运费
            if (isset($params['refund_express_amount']) && $params['refund_express_amount'] > 0) {
                $express_amount = db('orders_shop')->where(['orders_shop_no' => $params['orders_shop_no']])->field('orders_shop_express_amount')->find();
                $express_amount['orders_shop_express_amount'] += $cnt['express'] ? $cnt['express'] : 0;
                if ($express_amount['orders_shop_express_amount'] < $params['refund_express_amount'])
                    throw new ResponseException(Code::CODE_OTHER_FAIL, "您最多最能退 {$express_amount['orders_shop_express_amount']} 运费");
            }

            #   需要取出正在进行中的退款【不含已完成及已取消】
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}