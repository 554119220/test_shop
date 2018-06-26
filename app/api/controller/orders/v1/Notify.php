<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/18 0018
 * Time: 14:41
 */

namespace app\api\controller\orders\v1;


use app\api\model\orders\OrdersLogs;
use app\api\model\orders\OrdersShop;
use app\common\traits\F;
use lbzy\sdk\erp\Pay;
use mercury\async\Beanstalkd;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\NoPre;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\ResponseException;
use think\Db;
use think\Exception;

/**
 * Class Notify
 * @package app\api\controller\orders\v1
 *
 * @title 异步通知
 */
class Notify
{
    /**
     * @var string $key
     */
    protected $key = 'orders_shop_no', $model, $no;
    public function __construct()
    {
        $this->model    = new OrdersShop();
    }

    /**
     * @title 接收异步
     *
     * @param string $orders_shop_no
     * @param float $pay_amount
     * @param int $pay_type
     * @param string $trade_no
     * @return array|int
     */
    public function pay()
    {
        $this->no   = request()->data['order_no'];
        if (strpos(request()->data['order_no'], NoPre::NO_PRE_BY_SHOP_ORDERS) !== 0) {
            #   总订单
            $this->key  = 'orders_no';
        }
        try {
            $pay_amount = round(request()->data['amount'], 2);
            //$pay_type   = request()->data['pay_type'];
            $pay_type   = 5;
            $trade_no   = request()->data['code'];
            $orders_shop_no = $this->no;
            $use_shopping_score = request()->data['pay_consume'];       #   使用购物积分
            $use_amount         = request()->data['pay_cash'];          #   使用现金
            Db::startTrans();
            $state_in   = [State::STATE_ORDERS_NORMAL, State::STATE_ORDERS_NORMAL_CLOSE];
            $map    = [$this->key => $orders_shop_no, 'orders_shop_state' => ['in', $state_in]];
            $data   = db('orders_shop')->where($map)->order('orders_shop_id asc')->select();
            if (!$data) throw new ResponseException(Code::CODE_SUCCESS, '订单不存在或已付款');
            if (!in_array($data[0]['orders_shop_state'], $state_in)) throw new ResponseException(Code::CODE_SUCCESS, "订单状态为{$data[0]['orders_shop_state']}");
            $amount = db('orders_shop')->where($map)->column('SUM(orders_shop_edit_amount) as amount');
            $amount = round($amount[0], 2);
            if ($amount != $pay_amount)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "付款金额不对{$amount}_{$pay_amount}");

            #   店铺更新销量
            #   商品更新销量

            #   更新订单状态
            $prefix = config('database.prefix');
            $time   = time();
            $state  = State::STATE_ORDERS_PAY;
            $is_pay = State::STATE_NORMAL;
            $next_time  = Times::times(Times::TIME_ORDERS_SHIP);
            $close_sec  = Times::times(Times::TIME_ORDERS_SHIP, true);

            #   发送短信通知商家
            $logs   = [];
            $sub_order  = [];
            foreach ($data as $k => $v) {
                #   更新订单金额,orders_shop_pay_cash,orders_shop_pay_shopping_score
                #   算钱，算分
                $order_use_shopping_score   = 0;    #   当前订单使用的购物积分数量
                $order_use_amount           = $v['orders_shop_edit_amount']; #   当前订单支付金额
                $order_score                = 0; #   当前订单支付金额
                //$calc   = $this->calc(request()->data['amount'], request()->data['pay_cash'], request()->data['pay_consume'], $v['orders_shop_amount']);
                $v_amount   = round($v['orders_shop_edit_amount'], 2);
                $mobile = db('shop')->where(['shop_id' => $v['shop_id']])->cache(true)->value('shop_mobile');
                $nick   = db('user')->where(['user_id' => $v['buyer_user_id']])->cache(true)->value('user_username');
                $sub_order[$k]  = [
                        'seller_openid' => db('user')->where(['user_id' => $v['seller_user_id']])->cache(true)->value('openid'),
                        'order_no'      => $v['orders_shop_no'],
                        'pay_cash'      => $v['orders_shop_edit_amount'],
                        'goods_price'   => $v['orders_shop_goods_edit_amount'],
                        'express_price' => $v['orders_shop_express_edit_amount'],
                        'max_use_consume'   => $v['orders_shop_can_use_shopping_score'],
                    ];
                #   库存不足怎么办？直接退款？
                #   更新库存
                $goods  = db('orders_goods')->where(['orders_shop_id' => $v['orders_shop_id']])->select();

                $cps_spm= false;

                foreach ($goods as $val) {
                    $sku_num= db('goods_sku')->where(['goods_sku_id' => $val['goods_sku_id']])->value('goods_sku_num');
                    //if ($val['orders_goods_num'] > $sku_num) throw new ResponseException(Code::CODE_OTHER_FAIL, "商品[{$val['goods_name']}]库存不足!");
                    $val['orders_goods_num']    = $val['orders_goods_num'] > $sku_num ? $val['orders_goods_num'] - $sku_num : $val['orders_goods_num'];
                    #   为库存更新销量
                    $sql    = "UPDATE `{$prefix}goods_sku` SET `goods_sku_sale_num` = goods_sku_sale_num + {$val['orders_goods_num']},
`goods_sku_num` = goods_sku_num - {$val['orders_goods_num']}
WHERE `goods_sku_id` = {$val['goods_sku_id']}";
                    F::gearmanLogs('test', ['sku_num' => $sku_num, 'sql' => $sql]);
                    $flag   = db()->execute($sql);
                    #   为商品添加销量
                    if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新商品SKU销量失败');
                    $sku_num    = db('goods')->where(['goods_id' => $val['goods_id']])->value('goods_sku_num');
                    $sku_num    = $sku_num - $val['orders_goods_num'];
                    $sku_num    = $sku_num >= 0 ? $sku_num : 0;
                    $sql    = "UPDATE `{$prefix}goods` SET `goods_sale_num` = goods_sale_num + {$val['orders_goods_num']},
`goods_sku_num` = {$sku_num}
WHERE `goods_id` = {$val['goods_id']}";
                    $flag   = db()->execute($sql);
                    if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, "更新商品销量失败__{$sql}");
                    $sub_order[$k]['goods'][]   = [
                                'goods_id'  => $val['goods_id'],
                                'goods_cash'=> $val['orders_goods_amount'],
                                'price'     => $val['orders_goods_single_amount'],
                                'num'       => $val['orders_goods_num'],
                                'goods_name'=> $val['goods_name'],
                                'sku_name'  => $val['goods_sku_name'],
                                'sku_id'    => $val['goods_sku_id'],
                                'score'     => $val['goods_score'],
                                'score_multi'   => $val['goods_score_multi'] * 0.01,
                                'use_consume_ratio' => $val['goods_shopping_score_multi'] * 0.01,
                            ];


                    #   默认使用的购物积分为0
                    $goods_use_shopping_score = 0;
                    #   如果可以使用购物积分并且用户使用的购物积分大于0
                    if ($val['goods_pay_shopping_score'] > 0 && $use_shopping_score > 0) {
                        $use_shopping_score -= $val['goods_pay_shopping_score'];
                        #   如果用户使用的购物积分大于当前商品可用的购物积分
                        if ($use_shopping_score > 0) {
                            $goods_use_shopping_score = $val['goods_pay_shopping_score'];
                        } else {    #   否则取尾数
                            $goods_use_shopping_score = ($use_shopping_score + $val['goods_pay_shopping_score']);
                        }
                    }

                    /*
                    #   如果还有购物积分则为当前订单加上购物积分
                    $goods_use_shopping_score   = $val['orders_goods_amount'] * $val['goods_shopping_score_multi'];

                    $can_use_shopping_score     = $use_shopping_score - $goods_use_shopping_score;  #   可使用的购物积分数量

                    $use_shopping_score         -= $goods_use_shopping_score;

                    #   如果可退款的积分数量大于0
                    if ($can_use_shopping_score < 0) {
                        $goods_use_shopping_score   += $can_use_shopping_score;
                    }
                    */
                    $order_use_shopping_score   += $goods_use_shopping_score;
                    $val['goods_score']         -= $goods_use_shopping_score * $val['goods_score_multi'] * 0.01;  #商品奖励积分
                    $goods_use_amount           = F::amountCalc($val['orders_goods_amount'] - ($goods_use_shopping_score * 0.01)); #   商品使用购物积分
                    $order_use_amount           -= $goods_use_shopping_score * 0.01;    #   订单使用金额
                    #   奖励积分
                    $order_score                += $val['goods_score'];

                    $sql    = "UPDATE `{$prefix}orders_goods` SET `goods_score` = {$val['goods_score']},
`goods_pay_shopping_score` = {$goods_use_shopping_score}, `goods_pay_cash` = {$goods_use_amount} WHERE `orders_goods_id` = {$val['orders_goods_id']}";
                    if (false == db()->execute($sql)) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新订单商品失败');

                    #   是否含有百望客推广订单
                    if (!empty($val['cps_spm'])) $cps_spm = true;
                }

                $sql    = "UPDATE `{$prefix}orders_shop` SET
                      `orders_shop_pay_type` = {$pay_type}, `orders_shop_pay_amount` = orders_shop_edit_amount,
                      `orders_shop_pay_time` = {$time}, `orders_shop_trade_no` = '{$trade_no}',
                      `orders_shop_is_pay` = {$is_pay}, `orders_shop_state` = {$state}, `orders_shop_next_time` = {$next_time},
                      `orders_shop_pay_cash` = '{$order_use_amount}', `orders_shop_pay_shopping_score` = '{$order_use_shopping_score}',
                      `orders_shop_score` = '{$order_score}'
                      WHERE `orders_shop_id` = '{$v['orders_shop_id']}'";

                if (false == db()->execute($sql))
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '更新订单失败');

                #   创建订单日志
                $logs[]   = [
                    'orders_logs_title' => '买家支付',
                    'orders_shop_state' => $state,
                    'orders_shop_id'    => $v['orders_shop_id'],
                    'orders_shop_no'    => $v['orders_shop_no'],
                    'orders_logs_is_display'    => State::STATE_NORMAL,
                    'orders_logs_create_time'   => time(),
                ];

                $content= "{$nick}在您的店铺购买了{$v['goods_style_num']}款{$v['goods_count_num']}件商品，总额{$v_amount}元，订单号：{$v['orders_shop_no']}";
                #   异步发送短信
                F::gearmanSms($mobile, $content);

                #   未发货关闭并退款
                F::beanstalkOrdersPut('orders_ship', $v['orders_shop_id'], $v['orders_shop_no'], $close_sec);


                #   如果含有百望客的商品则推送过去
                if ($cps_spm) {
                    $beanstalkd = new Beanstalkd('ordersUpdate');
                    $beanstalkd->put(['id' => $v['orders_shop_id']], 1024, 30);
                }
            }

            $logs_model = new OrdersLogs();
            if (false == $logs_model->insertAll($logs))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '添加日志失败');


            #   如果beanstalk挂了则直接curl过去
            $beanstalk  = new Beanstalkd(Beanstalkd::ERP_PAY);
            $ret        = [
                'out_order_no'  => $this->no,
                'sub_orders'    => json_encode($sub_order),
            ];
            $put_flag = true;
            if ($beanstalk->getConnection()) {
                $put_flag   = $beanstalk->put($ret);
            }
            if (!$put_flag) {
                $flag   = F::redis()->lpush(Cache::ERP_API_PAY_FAILS, serialize($ret));
                if (false == $flag) F::gearmanSms('', "入列失败，{$ret['out_order_no']}");
                #   curl
//                Pay::instance()->createGroupOrder($ret);
            }
            F::gearmanLogs('erp_createGroupOrder', ['out_order_no' => $this->no, 'put' => $put_flag, 'sub_orders' => $sub_order], true);

            #   通知erp创建订单
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        } catch (Exception $e) {
            Db::rollback();
            return ['code' => Code::CODE_OTHER_FAIL, 'data' => $e->getData()];
        }
    }

    /**
     * @title 订单状态
     *
     * @param string $orders_shop_no
     * @return array|int
     */
    public function state()
    {
        try {
            #   总订单
            $this->no   = request()->data['shop_no'];
            if (strpos(request()->data['shop_no'], NoPre::NO_PRE_BY_SHOP_ORDERS) !== 0) {
                $this->key  = 'orders_no';
            }
            $map    = [
                $this->key          => $this->no,
                'orders_shop_state' => State::STATE_ORDERS_PAY
            ];
            $data   = $this->model->where($map)->find();
            if (!$data) throw new ResponseException(Code::CODE_OTHER_FAIL, '还未付款');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 计算金额
     *
     * @param $total_amount
     * @param $total_score
     * @param $orders_total
     * @return mixed
     */
    protected function calc($total_amount, $pay_amount, $pay_score, $orders_total)
    {
//        $total          = F::numberNMulti($total_amount);
//        $orders_total   = F::numberNMulti($orders_total);
//        $pay_amount     = F::numberNMulti($pay_amount);
        $total      = F::numberNMulti($total_amount);
        $pay_amount = F::numberNMulti($pay_amount);
        $orders_total   = F::numberNMulti($orders_total);
        $multi          = (int) (round(F::numberBcDiv($orders_total, $total, 3), 2) * 100);
        $tmp['amount']        = round($multi * $pay_amount * 0.0001, 2);
        $tmp['score']         = round($multi * $pay_score * 0.01, 2);
        return $tmp;
    }
}