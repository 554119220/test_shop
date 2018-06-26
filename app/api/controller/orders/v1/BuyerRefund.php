<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:30
 */

namespace app\api\controller\orders\v1;


use app\api\model\orders\OrdersRefund;
use app\api\model\orders\OrdersShop;
use app\api\service\orders\v1\Express;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use mercury\async\Beanstalkd;
use mercury\constants\Code;
use mercury\constants\NoPre;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\ResponseException;
use think\Db;

/**
 * Class BuyerRefund
 * @package app\api\controller\orders\v1
 *
 * @title 买家退款
 */
class BuyerRefund extends \app\api\service\orders\v1\Refund
{
    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key = 'buyer_user_id', $is_seller = false;

    /**
     * @title 取消退款
     *
     * @param int $user_id
     * @param string $refund_so
     * @param string $reason
     * @return array|string
     */
    public function cancel()
    {
        //buyer
        try {
            //更改退款状态
            //记录退款日志
            Db::startTrans();
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款原因不能为空');
            #   更新退款订单信息
            $in = $this->is_seller === true ?
            [State::STATE_REFUNDS_NORMAL, State::STATE_REFUNDS_AGREE, State::STATE_REFUNDS_EXPRESS] :
            [
                State::STATE_REFUND_NORMAL,
                State::STATE_REFUNDS_AGREE,
                State::STATE_REFUNDS_EXPRESS,
                State::STATE_REFUNDS_BUYER_APPEAL,
                State::STATE_REFUNDS_SELLER_APPEAL,
                State::STATE_REFUNDS_REFUSE];

            $map    = [
                'orders_refund_no'  => request()->data['refund_no'],
                $this->user_key     => request()->user['user_id'],
                'orders_refund_state'   => ['in', $in]
            ];
            $model  = new OrdersRefund();

            $refund = F::dataDetail($model, ['where' => $map]);
            if (!$refund) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在或已完成');
            #   更新内容
            $data   = [
                'orders_refund_state'   => State::STATE_SERVICE_CANCEL
            ];

            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '操作取消退款失败');

            #   记录日志
            $logs   = [
                'refund_logs_title' => '买家取消退款',
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_remark'=> request()->data['remark'],
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 申请退款
     *
     * @param int $user_id
     * @param string $orders_shop_no
     * @param int $num
     * @param float @amount
     * @param string $remark
     * @param string $images
     * @param int $orders_goods_id
     * @param int $type
     * @param int $is_ship
     * @return array|string
     */
    public function create()
    {
        //buyer
        try {
            //记录退款申请
            //记录退款日志

            Db::startTrans();

            #   验证数据是否正确
//            $ret    = \app\api\logic\orders\v1\Refund::instance()->create(request()->param());
//            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);

            $user_id= request()->user['user_id'];
            $shop_no= request()->data['shop_no'];
            $amount = F::amountCalc(request()->data['amount']);
            $express_amount = F::amountCalc(request()->data['express_amount']);
            $num    = intval(request()->data['num']);
            $goods_id   = intval(request()->data['goods_id']);
            $type   = intval(request()->data['type']);
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款原因不能为空');

            $map    = [
                $this->user_key => $user_id,
                'orders_shop_no'=> $shop_no,
                'orders_shop_state' => ['in', [State::STATE_ORDERS_PAY, State::STATE_ORDERS_SHIP]],
                //'orders_shop_state' => State::STATE_ORDERS_SHIP,
            ];
            #   如果是退货退款则需要推数量
            if ($type == State::STATE_REFUNDS && $num <= 0)
                throw new ResponseException(Code::CODE_OTHER_FAIL, '退货退款必须填写退货数量');

            if ($amount <= 0 && $express_amount <= 0) {
                throw new ResponseException(Code::CODE_OTHER_FAIL, '退款金额，退运费金额，退款数量必填一项!');
            }
            #   订单信息
            $model  = new OrdersShop();
            $orders = $model->where($map)->relation('refundApply')->find();
            if (!$orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在');
            $orders = $orders->toArray();
            if (!$orders['refundApply']) {
                throw new ResponseException(Code::CODE_OTHER_FAIL, '订单已退完');
            }
            #   如果未发货的订单申请退款则必须填写数量
            // || $type == State::STATE_REFUNDS
            if ($orders['orders_shop_state'] == State::STATE_ORDERS_PAY) {
                if ($num <= 0) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款数量不能为空');
                $amount = F::amountCalc($num * $orders['refundApply']['orders_goods_single_amount']);
            }
            if ($type == State::STATE_REFUNDS && $num <= 0) {
                if ($num <= 0) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款数量不能为空');
            }



            $refundModel    = new OrdersRefund();
            $refund_amount  = $refundModel->sumOldRefunds($shop_no, $goods_id);
            #   判断数量
            $orders_refund_goods_num    = $orders['refundApply']['goods_refund_num'];
            $can_refund_num             = $orders_refund_goods_num - $refund_amount['num'];
            if ($num > $can_refund_num)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退 {$can_refund_num} 件");
            #   判断退款金额
            $orders_refund_amount       = F::amountCalc($orders['refundApply']['goods_refund_amount']);
            $can_refund_amount          = F::amountCalc($orders_refund_amount - $refund_amount['amount']);
            if ($amount > $can_refund_amount)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退 {$can_refund_amount} 元");

            #   判断退运费金额
            $refund_express_amount  = $refundModel->sumOldRefundsExpressAmount($shop_no, $goods_id);
            $orders_refund_express_amount   = F::amountCalc($orders['orders_refund_express_amount']);
            $can_refund_express_amount      = F::amountCalc($orders_refund_express_amount - $refund_express_amount);
            if ($express_amount > $can_refund_express_amount)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退运费 {$can_refund_express_amount} 元");


            /*
            #   判断退款数量
            $num    = $orders['refundApply']['goods_refund_num'] - (request()->data['num'] + $refund_num);
            $can_num= $orders['refundApply']['goods_refund_num'] - $refund_num;
            if ($num < 0)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退 {$can_num} 件");

            #   判断退款金额
            $amount = round($orders['refundApply']['goods_refund_amount'], 2) - round(request()->data['amount'] + $refund_amount, 2);
            $can_amount = round($orders['refundApply']['goods_refund_amount'], 2) - round($refund_amount);
            if ($amount < 0)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退 {$can_amount} 元");

            #   运费计算
            $express_amount = round($orders['orders_refund_express_amount'], 2) - round(request()->data['express_amount'] + $refund_express_amount, 2);
            $can_express_amount = round($orders['orders_refund_express_amount'], 2) - $refund_express_amount;
            if ($express_amount < 0) {
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退运费 {$can_express_amount} 元");
            }
*/
            $data   = [
                $this->user_key => $user_id,
                'shop_id'       => $orders['shop_id'],
                'orders_shop_id'=> $orders['orders_shop_id'],
                'orders_shop_no'=> $orders['orders_shop_no'],
                'seller_user_id'=> $orders['seller_user_id'],
                'orders_refund_no'      => F::createNo(NoPre::NO_PRE_BY_REFUND_ORDERS),
                'orders_refund_num'     => $num,
                'orders_refund_amount'  => $amount,
                'orders_refund_state'   => State::STATE_REFUND_NORMAL,
                'orders_refund_type'    => $type,
                'orders_goods_id'       => $goods_id,
                'orders_refund_is_ship' => request()->data['is_ship'],
                'orders_refund_next_time'   => Times::times(Times::TIME_REFUND_AGREE),
                'orders_refund_express_amount'  => $express_amount,
            ];

            $refund_model   = new OrdersRefund();
            $insert_id      = $refund_model->save($data);

            if (false == $insert_id)
                throw new ResponseException(Code::CODE_OTHER_FAIL, '提交申请失败，请稍后再试');

            $insert_id      = $refund_model->getLastInsID();
            #   记录日志
            $logs   = [
                'refund_logs_title' => '买家申请退款',
                'orders_refund_id'  => $insert_id,
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $data['orders_refund_no'],
                'refund_logs_remark'=> request()->data['remark'],
                'refund_logs_images'=> request()->data['images'],
            ];

            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);

            #   退款入列
            Beanstalkd::getInstance('refund_agree')
                ->ordersPut($insert_id, $data['orders_refund_no'], Times::times(Times::TIME_REFUND_AGREE, true));

            Db::commit();
            return Code::CODE_SUCCESS;

        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }


    /**
     * @title 邮寄商品
     *
     * @param int $user_id
     * @param int $refund_so
     * @param int $company_id
     * @param string $express_no
     * @param string $reason
     * @return array|string
     */
    public function express()
    {
        //buyer
        try {
            //更改退款状态
            //记录退款日志

            Db::startTrans();
            $model  = new OrdersRefund();
            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_refund_state'   => State::STATE_REFUNDS_AGREE,
                'orders_refund_no'      => request()->data['refund_no']
            ];

            $data   = [
                'orders_refund_state'   => State::STATE_REFUNDS_EXPRESS,
                'orders_refund_next_time'   => Times::times(Times::TIME_REFUND_RECEIVE),
            ];

            $refund = F::dataDetail($model, ['where' => $map]);
            if (!$refund) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在或已完成');

            if (false == $model->save($data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '邮寄商品失败，请稍后再试');

            #   记录快递公司及单号
            $time   = time();
            $express= [
                'orders_refund_id'  => $refund['orders_refund_id'],
                'express_company_id'=> request()->data['company_id'],
                'orders_refund_no'  => $refund['orders_refund_no'],
                'express_no'        => request()->data['express_no'],
                'orders_refund_time'=> $time,
                'refund_remark'     => request()->param('remark', ''),
            ];
            $ret    = Express::instance()->refund($express);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            #   记录日志
            $logs   = [
                'refund_logs_title' => '买家邮寄商品',
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_remark'=> request()->data['remark'],
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            #   退款入列
            Beanstalkd::getInstance('refund_receive')
                ->ordersPut($refund['orders_refund_id'], $refund['orders_refund_no'], Times::times(Times::TIME_REFUND_RECEIVE, true));

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }


    /**
     * @title 修改退款
     *
     * @param int $user_id
     * @param int $refund_so
     * @param int $express_company_id
     * @param string $express_no
     * @param string $reason
     * @return array|string
     */
    public function modify()
    {
        //buyer
        try {
            //更改退款状态
            //记录退款日志

            Db::startTrans();
            $amount     = F::amountCalc(request()->data['amount']);
            $num        = intval(request()->data['num']);
            $express_amount = F::amountCalc(request()->data['express_amount']);
            $type       = intval(request()->data['type']);
            $refund_no  = request()->data['refund_no'];
            $user_id    = request()->user['user_id'];
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款原因不能为空');

            if ($amount == 0 && $express_amount == 0) {
                throw new ResponseException(Code::CODE_OTHER_FAIL, '退款金额，退运费金额，退款数量必填一项!');
            }

            #   修改退款
            $map    = [
                $this->user_key         => $user_id,
                'orders_refund_state'   => State::STATE_REFUNDS_REFUSE,
                'orders_refund_no'      => $refund_no
            ];


            $model  = new OrdersRefund();
            $refund = $model->where($map)->relation('ordersGoods,ordersShop')->find();
            if (!$refund || !$refund->ordersGoods) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在或已完成');
            $refund = $refund->toArray();

            #   退货退款时必须填写退货数量
            if ($type == State::STATE_REFUNDS) {
                if ($num <= 0) throw new ResponseException(Code::CODE_OTHER_FAIL, '退货退款必须填写退货数量');
//                $amount = F::amountCalc($num * $refund['ordersGoods']['orders_goods_single_amount'], 2);
            }

            #   取出正在退的金额及数量
            $refund_amount  = $model->sumOldRefunds($refund['orders_shop_no'], $refund['orders_goods_id'], $refund['orders_refund_no']);

            #   退数量
            $orders_refund_goods_num    = $refund['ordersGoods']['goods_refund_num'];
            $can_refund_goods_num       = $orders_refund_goods_num - $refund_amount['num'];
            if ($num > $can_refund_goods_num)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退 {$can_refund_goods_num} 件");

            #   退金额
            $orders_refund_amount   = F::amountCalc($refund['ordersGoods']['goods_refund_amount']);
            $can_refund_amount      = F::amountCalc($orders_refund_amount - $refund_amount['amount']);
            if ($amount > $can_refund_amount)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退 {$can_refund_amount} 元");


            #   取出正在退的运费
            $express_amount_now = $model->sumOldRefundsExpressAmount($refund['orders_shop_no'], $refund['orders_refund_no']);
            #   退运费
            $orders_refund_express_amount   = F::amountCalc($refund['ordersShop']['orders_refund_express_amount']);
            $can_refund_express_amount  = F::amountCalc($orders_refund_express_amount - $express_amount_now);
            if ($express_amount > $can_refund_express_amount)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "本次最多可退运费 {$can_refund_express_amount} 元");

            $data   = [
                'orders_refund_num'     => $num,
                'orders_refund_amount'  => $amount,
                'orders_refund_state'   => State::STATE_REFUND_NORMAL,
                'orders_refund_type'    => $type,
                'orders_refund_next_time'   => Times::times(Times::TIME_REFUND_AGREE),
                'orders_refund_is_ship' => request()->data['is_ship'],
                'orders_refund_express_amount'  => $express_amount,
            ];
            $flag   = $model->update($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '修改退款记录失败');

            #   记录日志
            $logs   = [
                'refund_logs_title' => '买家修改退款',
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_images'=> request()->data['images'],
                'refund_logs_remark'=> request()->data['remark'],
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);

            #   退款入列
            Beanstalkd::getInstance('refund_agree')
                ->ordersPut($refund['orders_refund_id'], $refund['orders_refund_no'], Times::times(Times::TIME_REFUND_AGREE, true));

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 修改退款
     *
     * @return array
     */
    public function edit()
    {
        try {
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_refund_no'  => request()->data['refund_no'],
            ];

            $model  = new OrdersRefund();
            $refund = $model->where($map)->relation('OrdersGoods,ordersShop')->find();
            if (!$refund || !$refund->OrdersGoods) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在');
            $refund = $refund->toArray();
            #   取出正在进行中的退款
            $refunds= $model->sumOldRefunds($refund['orders_shop_no'], $refund['orders_goods_id'], $refund['orders_refund_no']);
            #   获取正在退的运费
            $refund_express_amount  = $model->sumOldRefundsExpressAmount($refund['orders_shop_no'], $refund['orders_refund_no']);

            #   计算可退金额
            $refund['orders_refund_express_amount']         = F::amountCalc($refund['ordersShop']['orders_refund_express_amount'] - $refund_express_amount);
            $refund['OrdersGoods']['goods_refund_amount']   -= F::amountCalc($refunds['amount']);
            $refund['OrdersGoods']['goods_refund_num']      -= $refunds['num'];
            return $refund;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}