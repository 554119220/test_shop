<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:32
 */

namespace app\api\controller\orders\v1;


use app\api\model\orders\OrdersGoods;
use app\api\model\orders\OrdersRefund;
use app\api\model\orders\OrdersShop;
use app\api\service\orders\v1\Address;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use lbzy\sdk\erp\ErpOauth;
use mercury\async\Beanstalkd;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\ResponseException;
use think\Db;

/**
 * Class SellerRefund
 * @package app\api\controller\orders\v1
 *
 * @title 商家退款
 */
class SellerRefund extends \app\api\service\orders\v1\Refund
{
    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key = 'seller_user_id', $is_seller = true;

    /**
     * @title 同意退款
     *
     * @param int $user_id
     * @param string $refund_no
     * @param string $pay_password
     * @param string $remark
     * @return array|string
     */
    public function agree()
    {
        //seller
        try {
            //更改退款状态
            //记录退款日志
            //如果为退货并退款则取出商家收货地址
            //$isAuth = ErpOauth::instance()->checkPayPassword(['openid' => request()->user['openid'], 'safe_psw' => request()->data['pay_password']]);
            //if (true !== $isAuth) throw new ResponseException(Code::CODE_OTHER_FAIL, $isAuth);
            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_refund_state'   => State::STATE_REFUND_NORMAL,
                'orders_refund_no'      => request()->data['refund_no'],
            ];
            $model  = new OrdersRefund();
            $refund = F::dataDetail($model, ['where' => $map]);
            if (!$refund) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在或已完成');

            if ($refund['orders_refund_type'] == State::STATE_REFUND) {  //只退款
                #   直接退钱
                #   异步退钱至买家账户
                $state  = State::STATE_REFUNDS_SUCCESS;
                $title  = '商家同意退款，退款已完成';
                #   余额已退完则关闭订单
                $ret    = $this->refundOverClose($refund);
                if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

                #   同意退货后更新SKU库存
                $ret_sku = $this->updateSku($refund);
                if (true !== $ret_sku) {
                    throw new ResponseException(Code::CODE_OTHER_FAIL, $ret_sku);
                }

                #   运费加上剩余运费
                $refund_express_amount  = F::amountCalc($ret + $refund['orders_refund_express_amount']);
                if (false == $this->put($refund, $refund_express_amount))
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '退款至ERP失败');

            } else {   //退货退款
                //填写收货地址
                //添加收货地址
                if (intval(request()->data['address_id']) <= 0) throw new ResponseException(Code::CODE_OTHER_FAIL, '收货地址不能为空');
                $state  = State::STATE_REFUNDS_AGREE;
                $address= [
                    'orders_refund_id'  => $refund['orders_refund_id'],
                    'orders_refund_no'  => $refund['orders_refund_no'],
                    'address_id'        => request()->data['address_id'],
                    'user_id'           => request()->user['user_id']
                ];
                $ret    = Address::instance()->refund($address);
                if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);
                $title  = '商家同意退款';
                #   退款取消入列
                Beanstalkd::getInstance('refund_agree')
                    ->ordersPut($refund['orders_refund_id'], $refund['orders_refund_no'], Times::times(Times::TIME_REFUND_CANCEL, true));
            }

            $data   = [
                'orders_refund_state'   => $state,
                'orders_refund_next_time'   => Times::times(Times::TIME_REFUND_CANCEL)
            ];
            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新订单状态失败');

            #   记录日志
            $logs   = [
                'refund_logs_title' => $title,
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_images'=> request()->data['images'],
                'refund_logs_remark'=> request()->data['remark'],
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }


    /**
     * @title 拒绝退款
     *
     * @param int $user_id
     * @param string $pay_password
     * @param string $refund_no
     * @return array|string
     */
    public function refuse()
    {
        //seller
        try {
            //更改退款状态
            //记录退款日志
            Db::startTrans();
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '拒绝原因不能为空');
            //$isAuth = ErpOauth::instance()->checkPayPassword(['openid' => request()->user['openid'], 'safe_psw' => request()->data['pay_password']]);
            //if (true !== $isAuth) throw new ResponseException(Code::CODE_OTHER_FAIL, $isAuth);
            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_refund_state'   => State::STATE_REFUND_NORMAL,
                'orders_refund_no'      => request()->data['refund_no'],
            ];
            $model  = new OrdersRefund();
            $refund = F::dataDetail($model, ['where' => $map]);
            if (!$refund) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在或已完成');
            if ($refund['orders_refund_is_ship'] == State::STATE_DISABLED) throw new ResponseException(Code::CODE_OTHER_FAIL, '未发货订单不可拒绝退款！');
            $data   = [
                'orders_refund_state'   => State::STATE_REFUNDS_REFUSE,
                'orders_refund_next_time'   => Times::times(Times::TIME_REFUND_CANCEL)
            ];

            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新退款订单状态失败');

            #   记录日志
            $logs   = [
                'refund_logs_title' => '商家拒绝退款',
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_images'=> request()->data['images'],
                'refund_logs_remark'=> request()->data['remark'],
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            #   退款取消入列
            Beanstalkd::getInstance('refund_cancel')
                ->ordersPut($refund['orders_refund_id'], $refund['orders_refund_no'], Times::times(Times::TIME_REFUND_CANCEL, true));

            Db::commit();
            return Code::CODE_SUCCESS;

        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 商家收货
     *
     * @param int $user_id
     * @param string $pay_password
     * @param string $refund_no
     * @return array|string
     */
    public function receive()
    {
        //seller
        try {
            //更改退款状态
            //记录退款日志
            //$isAuth = ErpOauth::instance()->checkPayPassword(['openid' => request()->user['openid'], 'safe_psw' => request()->data['pay_password']]);
            //if (true !== $isAuth) throw new ResponseException(Code::CODE_OTHER_FAIL, $isAuth);
            Db::startTrans();
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_refund_state'   => State::STATE_REFUNDS_EXPRESS,
                'orders_refund_no'      => request()->data['refund_no']
            ];

            $model  = new OrdersRefund();
            $refund = F::dataDetail($model, ['where' => $map]);
            if (!$refund) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在或已完成');
            $data   = [
                'orders_refund_state'   => State::STATE_REFUNDS_SUCCESS
            ];
            #   更新订单及订单商品
            $ret    = $this->refundOverClose($refund);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            //余额已退完则关闭订单
            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新退款状态失败');

            #   同意退货后更新SKU库存
            if (true !== $ret_sku = $this->updateSku($refund)) throw new ResponseException(Code::CODE_OTHER_FAIL, $ret_sku);


            #   记录日志
            $logs   = [
                'refund_logs_title' => '商家确认收货，退款已完成',
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_images'=> request()->data['images'],
                'refund_logs_remark'=> request()->data['remark'],
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);
            #   入列退至erp
            if (false == $this->put($refund, $refund['orders_refund_express_amount']))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '退款至ERP失败');

            //异步退款至买家账户
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }


    /**
     * 商品及订单操作
     *
     * @param array $refund
     * @return array|int
     */
    protected function refundOverClose(array $refund)
    {
        #   如果金额退完则关闭订单
        try {
            #   减去退款金额
            $table_prefix   = config('database.prefix');
            $ordersShop     = new OrdersShop();
            #   商品金额退完后把运费退了
            #   如果未发货退款买家仅退商品金额则需要把运费也一起退了且关闭订单
            #   如果已发货退款买家将商品金额及运费都退完则直接关闭订单

            $orders   = $ordersShop->where('orders_shop_id', $refund['orders_shop_id'])
                ->field('orders_refund_amount,orders_refund_express_amount,orders_refund_num')
                ->find();
            $orders_refund_amount           = F::amountCalc($orders['orders_refund_amount']);           #   可退余额
            $orders_refund_express_amount   = F::amountCalc($orders['orders_refund_express_amount']);   #   可退运费
            $refund_amount                  = F::amountCalc($refund['orders_refund_amount']);           #   申请余额
            $refund_express_amount          = F::amountCalc($refund['orders_refund_express_amount']);   #   申请运费
            $excess_express_amount          = 0;    #   剩余可退运费

            #   仅退款,余额退款得退运费
            #   数量退完则不需要评价
            $is_close   = false;
            #   是否需要评价


            if ($refund['orders_refund_is_ship'] == State::STATE_DISABLED) {
                #   如果未发货退款买家仅退商品金额则需要把运费也一起退了且关闭订单
                #   如果退款金额减去可退金额小于等于0
                if (F::amountCalc($orders_refund_amount - $refund_amount) <= 0) {
                    $excess_express_amount  = F::amountCalc($orders_refund_express_amount - $refund_express_amount);
                    if ($excess_express_amount > 0) {
                        #   更新退运费金额
                        $flag   = db('orders_refund')->where(['orders_refund_id' => $refund['orders_refund_id']])
                            ->update(['orders_refund_express_amount' => $orders_refund_express_amount]);
                        if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新退运费金额失败');
                    }
                    $is_close   = true;
                    $refund_express_amount  = $orders_refund_express_amount;
                }
                $is_comment = State::STATE_ORDERS_PAY;
            } elseif ($refund['orders_refund_is_ship'] == State::STATE_NORMAL) {
                $is_comment = State::STATE_ORDERS_SHIP;
                #   退货退款，余额退完得退运费
                if (F::amountCalc($orders_refund_amount - $refund_amount) <= 0 &&
                    F::amountCalc($orders_refund_express_amount - $refund_express_amount) <= 0) $is_close = true;
            }
            #   如果没有了商品数量则关闭设为评价
//            if ($orders['orders_refund_num'] - $refund['orders_refund_num'] <= 0) {
//                $is_comment = State::STATE_ORDERS_COMMIT;
//            }

            #   是否需要关闭订单
            if ($is_close) {
                $is_comment   = State::STATE_ORDERS_REFUND_CLOSE;
                $sql    = "UPDATE `{$table_prefix}orders_shop` SET `orders_refund_amount` = orders_refund_amount - {$refund_amount}, 
`orders_shop_state` = {$is_comment}, `orders_refund_express_amount` = orders_refund_express_amount - {$refund_express_amount}, 
`orders_refund_num` = orders_refund_num - {$refund['orders_refund_num']} 
WHERE `orders_shop_id` = {$refund['orders_shop_id']}";
                #   关闭订单日志
            } else {
                $sql    = "UPDATE `{$table_prefix}orders_shop` SET `orders_refund_amount` = orders_refund_amount - {$refund_amount} 
, `orders_refund_express_amount` = orders_refund_express_amount - {$refund_express_amount}, 
`orders_refund_num` = orders_refund_num - {$refund['orders_refund_num']}, `orders_shop_state` = {$is_comment} 
WHERE `orders_shop_id` = {$refund['orders_shop_id']}";
            }
            $flag   = db()->execute($sql);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新退款金额失败');

            #   退款数量减1
            if ($refund['orders_refund_num'] > 0 || $refund['orders_refund_amount'] > 0) {
                $sql    = "UPDATE `{$table_prefix}orders_goods` SET `goods_refund_num` = goods_refund_num - {$refund['orders_refund_num']}, 
`goods_refund_amount` = goods_refund_amount - {$refund_amount}, 
`goods_service_num` = goods_service_num - {$refund['orders_refund_num']} WHERE `orders_goods_id` = {$refund['orders_goods_id']}";
                $flag   = db()->execute($sql);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单商品退款数量更新失败');
            }

            return $excess_express_amount;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * ERP退款
     *
     * @param array $refund
     * @param $express_amount
     * @return bool|int
     */
    protected function put(array $refund, $express_amount)
    {
        #   退款入列
        $beanstalk  = new Beanstalkd(Beanstalkd::ERP_REFUND);
        $put_flag = true;
        $put_data   = [
            'order_no'  => $refund['orders_shop_no'],
            'openid'    => db('user')->where(['user_id' => $refund['buyer_user_id']])->cache(true)->value('openid'),
            'sku_id'    => db('orders_goods')->where(['orders_goods_id' => $refund['orders_goods_id']])->cache(true)->value('goods_sku_id'),
            'sale_psw'  => '',
            'refund_no' => $refund['orders_refund_no'],
            'is_auto'   => State::STATE_NORMAL,
            'goods_price'   => $refund['orders_refund_amount'],
            'express_price' => $express_amount,
        ];
        if ($beanstalk->getConnection()) {
            $put_flag   = $beanstalk->put($put_data);
        }
        if (!$put_flag) {
            $flag   = F::redis()->lpush(Cache::ERP_API_REFUND_FAILS, serialize($put_data));
            if (false == $flag) F::gearmanSms('', "退款入列失败，{$refund['orders_refund_no']}");
            #   curl
        }
        return $put_flag;
    }

    /**
     * 更新库存
     *
     * @param array $refund
     * @return bool|string
     */
    protected function updateSku(array $refund)
    {
        #   如果退货数量不为0，则恢复到SKU
        if ($refund['orders_refund_num'] > 0) {
            $ordersGoods = db('orders_goods')->where([
                'orders_goods_id' => $refund['orders_goods_id']
            ])->field('goods_id,goods_sku_id')->find();
            if (!db('goods')->where([
                'goods_id' => $ordersGoods['goods_id']
            ])->setInc('goods_sku_num', $refund['orders_refund_num'])) {
                return '更新商品库存失败';
            }

            if (!db('goods_sku')->where([
                'goods_sku_id' => $ordersGoods['goods_sku_id']
            ])->setInc('goods_sku_num', $refund['orders_refund_num'])) {
                return '更新SKU库存失败';
            }
        }
        return true;
    }
}