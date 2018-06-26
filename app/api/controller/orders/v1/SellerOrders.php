<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:32
 */

namespace app\api\controller\orders\v1;


use app\api\model\orders\OrdersRefund;
use app\api\model\orders\OrdersShop;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\ResponseException;
use think\Db;

/**
 * Class SellerOrders
 * @package app\api\controller\orders\v1
 *
 * @title 商家订单
 */
class SellerOrders extends \app\api\service\orders\v1\Orders
{

    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key = 'seller_user_id', $is_seller = true;

    /**
     * @title 邮寄商品
     *
     * @param int $user_id
     * @param string $orders_shop_no
     * @param int $express_company_id
     * @param string $express_no
     * @param string $remark
     * @return array|string
     */
    public function ship()
    {
        //seller
        try {
            //设置为已发货状态
            //记录日志
            
            $map    = [
                $this->user_key     => request()->user['user_id'],
                'orders_shop_state' => State::STATE_ORDERS_PAY,
                'orders_shop_no'    => request()->data['shop_no'],
                'orders_shop_is_freeze' => State::STATE_DISABLED,
            ];
            $model  = new OrdersShop();
            $orders = F::dataDetail($model, ['where' => $map]);
            if (!$orders) throw new ResponseException(Code::CODE_NO_CONTENT, '订单不存在或不可发货');

            $time   = time();
            $data   = [
                'orders_shop_express_company'   => request()->data['company_id'],
                'orders_shop_express_no'        => request()->data['express_no'],
                'orders_shop_express_time'      => $time,
                'orders_shop_state'             => State::STATE_ORDERS_SHIP,
                'orders_shop_next_time'         => Times::times(Times::TIME_ORDERS_RECEIVE),
                'orders_shop_express_remark'    => request()->data['express_remark'] ?? '',
            ];

            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '发货失败');

            #   退款取消

            #   取消退款
            $ordersRefund   = new OrdersRefund();
//            $refunds        = $ordersRefund->where('orders_shop_id', $orders['orders_shop_id'])
//                ->whereNotIn('orders_refund_state', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS])->select();
            $refunds        = F::dataList($ordersRefund, [
                'where' => [
                    'orders_shop_id'    => $orders['orders_shop_id'],
                    'orders_refund_state'   => ['not in', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS]]
                ]
            ]);

            if ($refunds) {
                $flag   = db('orders_refund')->where([
                    'orders_shop_id'        => $orders['orders_shop_id'],
                    'orders_refund_state'   => ['not in', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS]]
                ])->update(['orders_refund_state' => State::STATE_REFUNDS_CANCEL, 'orders_refund_update_time' => $time]);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '取消退款失败');
                #   记录日志？？？
                $logs   = [
                    'refund_logs_title' => '商家发货取消退款',
                    'refund_state'      => State::STATE_REFUNDS_CANCEL,
                    'refund_logs_remark'=> '商家发货取消退款',
                ];
                foreach ($refunds as $v) {
                    #   记录日志
                    $logs['orders_refund_id']   = $v['orders_refund_id'];
                    $logs['refund_no']          = $v['orders_refund_no'];
                    $ret    = Logs::instance()->refund($logs);
                    if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);
                }
            }



            #   记录日志
            $logs   = [
                'orders_logs_title' => '商家邮寄商品',
                'orders_shop_id'    => $orders['orders_shop_id'],
                'orders_shop_state' => $data['orders_shop_state'],
                'orders_shop_no'    => $orders['orders_shop_no'],
                'orders_logs_is_display'    => State::STATE_NORMAL
            ];
//            F::writeLog($logs);
//            throw new ResponseException(Code::CODE_OTHER_FAIL);
            $ret    = Logs::instance()->orders($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);
            #   自动确认收货
            F::beanstalkOrdersPut('orders_receive',
                $orders['orders_shop_id'],
                $orders['orders_shop_no'],
                Times::times(Times::TIME_ORDERS_RECEIVE, true));
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 修改订单金额
     *
     * @param int $user_id
     * @param string $order_shop_no
     * @param float $goods_amount
     * @param float $express_amount
     * @return array|int
     */
    public function editPrice()
    {
        //seller
        try {
            //修改价格
            //记录日志
            Db::startTrans();
            $goods_amount   = F::amountCalc(request()->data['goods_amount']);
            $express_amount = F::amountCalc(request()->data['express_amount']);
            $map    = [
                $this->user_key     => request()->user['user_id'],
                'orders_shop_state' => State::STATE_ORDERS_NORMAL,
                'orders_shop_no'    => request()->data['shop_no']
            ];

            $model  = new OrdersShop();

            $orders = F::dataDetail($model, [
                'where' => $map,
                'relation'  => 'goods,orderGroup'
            ]);
            if (!$orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在或已付款');

            #   判断是否在收银台
            $no_key     = F::getCacheName(Cache::IS_IN_ERP_PAY . $map['orders_shop_no']);
            $s_no_key   = F::getCacheName(Cache::IS_IN_ERP_PAY . $orders['orders_no']);
            $redis      = F::redis();
            if ($redis->exists($no_key) || $redis->exists($s_no_key))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '订单正在付款，请等15分钟后在做修改！');

            #   不能高于30%，不能低于30%
            #
            #   orders_shop_edit_amount,orders_shop_goods_edit_amount,orders_shop_express_edit_amount,orders_refund_amount
            #   orders_refund_score,orders_refund_express_amount,orders_shop_can_use_shopping_score
            #   goods_refund_amount,goods_refund_score,goods_score,orders_goods_amount,orders_goods_single_amount
            #   goods_shopping_score_multi

            $orders_shop_goods_amount   = F::amountCalc($orders['orders_shop_goods_edit_amount']);
            $edit_amount                = F::amountCalc($orders_shop_goods_amount - $goods_amount);
            $edit_amount_multi          = F::amountCalc($edit_amount / $orders_shop_goods_amount, 5);

            $tmp_multi  = F::numberFormats(($orders['orders_shop_goods_amount'] - $goods_amount) / $orders['orders_shop_goods_amount']);

            if ($tmp_multi > 0.3 || $tmp_multi < -0.3)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "商品金额上下浮动不可超过30%");
            $shop_edit_amount           = F::amountCalc($goods_amount + $express_amount);


            #   修改商品金额
            $refund_score   = 0;
            $shopping_score = 0;
            $goods_cnt      = count($orders['goods']);
            $goods_all_amount   = 0;
            foreach ($orders['goods'] as $k => $v) {
                #   所占比例
                #   $goods_multi                = F::amountCalc($v['orders_goods_amount'] / $orders_shop_goods_amount, 5);
                $orders_goods_amount        = F::amountCalc(round($v['orders_goods_amount'], 2) -
                    round(($v['orders_goods_amount'] * $edit_amount_multi), 2));

                $orders_goods_single_amount = F::amountCalc(round($v['orders_goods_single_amount'], 2) -
                    round(($v['orders_goods_single_amount'] * $edit_amount_multi), 2));

                #   最后一个商品取余
                $goods_all_amount += F::numberFormats($orders_goods_amount);
                if ($k + 1 == $goods_cnt) {
                    $tmp = F::numberFormats($goods_amount - F::numberFormats($goods_all_amount)) * 100;
                    if ($tmp != 0) {
                        $orders_goods_amount        += ($tmp * 0.01);
                        $orders_goods_single_amount += ($tmp * 0.01);
                    }
                }
                $goods_score                = F::amountCalc($orders_goods_amount * $v['goods_score_multi']);
                $goods_sopping_score        = F::amountCalc($orders_goods_amount * $v['goods_shopping_score_multi']);
                if (F::amountCalc($orders_goods_amount - ($goods_sopping_score * 0.01)) < 0.5)
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '单件商品金额不能低于0.5元');
                $goods  = [
                    'goods_refund_amount'   => $orders_goods_amount,
                    'goods_refund_score'    => $goods_score,
                    'goods_score'           => $goods_score,
                    'orders_goods_amount'   => $orders_goods_amount,
                    'orders_goods_single_amount'    => $orders_goods_single_amount,
                    'goods_pay_shopping_score'      => $goods_sopping_score,
                ];
                if (false === db('orders_goods')->where(['orders_goods_id' => $v['orders_goods_id']])->update($goods)) {
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '金额未变动');
                }

                $refund_score   += $goods_score;
                $shopping_score += F::amountCalc($orders_goods_amount * $v['goods_shopping_score_multi']);
            }

            $orders_data    = [
                'orders_shop_edit_amount'           => $shop_edit_amount,
                'orders_shop_goods_edit_amount'     => $goods_amount,
                'orders_shop_express_edit_amount'   => $express_amount,
                'orders_refund_amount'              => $goods_amount,
                'orders_refund_score'               => $refund_score,
                'orders_refund_express_amount'      => $express_amount,
                'orders_shop_can_use_shopping_score'=> $shopping_score,
                'orders_shop_score'                 => $refund_score,
                'orders_shop_discount_amount'       => $orders['orders_shop_discount_amount'] + ($orders['orders_shop_edit_amount'] - $shop_edit_amount),
            ];

            if (false == $model->save($orders_data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '更新订单金额失败');

            #   父订单金额更新
            $order_data     = [
                'orders_edit_amount'    => F::amountCalc($orders['orderGroup']['orders_edit_amount'] -
                    ($orders['orders_shop_edit_amount'] - $shop_edit_amount))
            ];
            if (false == db('orders')->where(['orders_id' => $orders['orders_id']])->update($order_data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '更新父订单金额失败');

            #   记录日志
            $logs   = [
                'orders_logs_title' => '商家修改订单金额',
                'orders_shop_id'    => $orders['orders_shop_id'],
                'orders_shop_state' => $orders['orders_shop_state'],
                'orders_shop_no'    => $orders['orders_shop_no'],
                'orders_logs_is_display'    => State::STATE_DISABLED
            ];
            $ret    = Logs::instance()->orders($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 修改快递信息
     *
     * @param int $user_id
     * @param string $orders_shop_no
     * @param int $express_company_id
     * @param string $express_no
     * @return array|string
     */
    public function editShip()
    {
        //seller
        try {
            //修改订单发货信息
            //记录修改日志

            Db::startTrans();
            $map    = [
                $this->user_key     => request()->user['user_id'],
                'orders_shop_state' => State::STATE_ORDERS_SHIP,
                'orders_shop_no'    => request()->data['shop_no'],
                'orders_shop_is_freeze' => State::STATE_DISABLED,
            ];

            $model  = new OrdersShop();
            $orders = F::dataDetail($model, ['where' => $map]);
            if (!$orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在或不可编辑发货信息');

            $data   = [
                'orders_shop_express_company'   => request()->data['company_id'],
                'orders_shop_express_no'        => request()->data['express_no'],
                'orders_shop_express_time'      => time(),
                'orders_shop_express_remark'    => request()->data['express_remark']
            ];

            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新快递信息失败');

            #   记录日志
            $logs   = [
                'orders_logs_title' => '商家修改快递信息',
                'orders_shop_id'    => $orders['orders_shop_id'],
                'orders_shop_state' => $orders['orders_shop_state'],
                'orders_shop_no'    => $orders['orders_shop_no'],
                'orders_logs_is_display'    => State::STATE_DISABLED
            ];
            $ret    = Logs::instance()->orders($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }
}