<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\api\service\orders\v1\Address;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use app\work\controller\Commonmodules;
use mercury\async\Beanstalkd;
use mercury\constants\Cache;
use mercury\constants\State;
use think\Db;
use think\Exception;

class Ordersrefund extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 649;  //表单模板ID
        $this->module_name  = '退款管理';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);

        $btns   = 'return "<a class=\'btn blue btn-outline btn-block md10\' href=\'/ordersrefund/edit/orders_refund_id/$val[orders_refund_id]\'>修改</a>
<div data-id=\'$val[orders_refund_id]\' class=\'btn red btn-outline btn-block\' onclick=\'extra_tr_view($(this))\'>详情</div>";';
        $btns   = [$btns];
        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],$btns,1,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteSelect(){
        $res = $this->_deleteSelect();
        return $res;
    }

    /**
     * 批量设置状态
     */
    public function setStatus(){
        $res = $this->_setStatus();
        return $res;
    }

    /**
     * 修改
     */
    public function edit(){
        $res = $this->_edit();
        return view();
    }

    /**
     * 保存修改
     */
    public function edit_save(){
        $res = $this->_edit_save();
        return $res;
    }

    /**
     * 新增
     */
    public function add(){
        $res = $this->_add();
        return view();
    }
    /**
     * 保存新增
     */
    public function add_save(){
        $res = $this->_add_save();
        return $res;
    }
    /**
     * 转移目录
     */
    public function change2Category(){
        $res = $this->_change2Category();
        return $res;
    }

    /**
     * 退款详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $id = intval(input('id'));
        $data   = [];
        if ($id > 0) {
            $data   = F::dataDetail(F::apiModel('OrdersRefund', 'orders'), [
                'where' => ['orders_refund_id' => $id],
                'relation'  => 'OrdersRefundAddress,OrdersRefundLogs,OrdersGoods,ordersShop'
            ]);
        }
        if (!empty($data)) {
            $data['seller'] = F::dataDetail(F::apiModel('User', 'user'), $data['seller_user_id']);
            $data['user'] = F::dataDetail(F::apiModel('User', 'user'), $data['buyer_user_id']);
            $data['shop'] = F::dataDetail(F::apiModel('Shop', 'goods'), $data['shop_id']);
        }
//        dump(config('site'));
        return view('', ['data' => $data]);
    }

    /**
     * 申诉裁判
     *
     * @return array
     */
    public function referee()
    {
        try {
            Db::startTrans();
            $id = intval(input('id'));
            if ($id <= State::STATE_DISABLED) throw new \Exception('退款订单不能为空');
            #   0原判，1卖家胜诉，2商家胜诉
            $referee_result = input('referee_result');
            if ($referee_result == '') throw new \Exception('判决类型不能为空');
            if (empty(input('remark'))) throw new \Exception('判决原因不能为空');
            $result_arr = [0,1,2];
            if (!in_array($referee_result, $result_arr)) throw new \Exception('判决结果错误');
            $refund = F::dataDetail(F::apiModel('OrdersRefund', 'orders'), [
                'where' => ['orders_refund_id' => $id, 'orders_refund_state' => ['in', [State::STATE_REFUNDS_BUYER_APPEAL, State::STATE_REFUNDS_SELLER_APPEAL]]],
                'relation'  => 'OrdersRefundAddress,OrdersRefundLogs,OrdersGoods,ordersShop'
            ]);
            if (!$refund) throw new \Exception('退款订单不存在或未申诉');

            #   买家申诉
            $time   = time();
            $title  = '雇员判决：';
            $state  = '';
            $next_time  = '';
            $queue  = false;
            $times  = config('site.orders');
            $amount_express = 0;
            if ($refund['orders_refund_state'] == State::STATE_REFUNDS_BUYER_APPEAL) {
                #   商家拒绝退款，买家申诉
                switch ($referee_result) {
                    case 0: #   原判
                        $title  = "{$title}维持原判";
                        $state  = State::STATE_REFUNDS_REFUSE;
                        $next_time  = $time + $times['time_refund_cancel'];
                        $tube   = 'refund_cancel';
                        break;
                    case 1: #   买家胜诉
                        #   仅退款
                        if ($refund['orders_refund_type'] == State::STATE_REFUND) {
                            $title  = "{$title}商家同意退款，退款完成";
                            $state  = State::STATE_REFUNDS_SUCCESS;
                            $queue  = true;
                            $amount_express = $this->updateRefundData($refund);
                            if (is_array($amount_express)) throw new Exception($amount_express['msg']);
                        } elseif ($refund['orders_refund_type'] == State::STATE_REFUNDS) {
                            #   退货退款
                            $title  = "{$title}商家同意退款";
                            $state  = State::STATE_REFUNDS_AGREE;
                            #   写入商家收货地址
                            $address= [
                                'orders_refund_id'  => $refund['orders_refund_id'],
                                'orders_refund_no'  => $refund['orders_refund_no'],
                                'address_id'        =>
                                    db('shop_address')->where(['user_id' => $refund['seller_user_id']])->order('address_is_default desc')->value('address_id'),
                                'user_id'           => $refund['seller_user_id']
                            ];
                            $ret    = Address::instance()->refund($address);
                            if (is_array($ret)) throw new \Exception($ret['msg']);
                            $next_time  = $time + $times['time_refund_cancel'];
                            $tube   = 'refund_cancel';
                        }
                        break;
                    case 2: #   商家胜诉
                        $title  = "{$title}商家胜诉，退款取消";
                        $state  = State::STATE_REFUNDS_CANCEL;
                        break;
                }
            } else {    #   商家申诉
                #   商家未收到商品，商家申诉
                switch ($referee_result) {
                    case 0: #   原判
                        $title  = "{$title}维持原判";
                        $state  = State::STATE_REFUNDS_EXPRESS;
                        $next_time  = $time + $times['time_refund_receive'];
                        $tube   = 'refund_receive';
                        break;
                    case 1: #   买家胜诉
                        $title  = "{$title}买家胜诉，退款完成";
                        $state  = State::STATE_REFUNDS_SUCCESS;
                        $queue  = true;
                        $amount_express = $this->updateRefundData($refund);
                        if (is_array($amount_express)) throw new Exception($amount_express['msg']);
                        #   退款给买家，异步处理
                        break;
                    case 2: #   商家胜诉
                        $title  = "{$title}商家胜诉，退款取消";
                        $state  = State::STATE_REFUNDS_CANCEL;
                        break;
                }
            }

            $data   = [
                'orders_refund_state'   => $state,
            ];
            if ($next_time) $data['orders_refund_next_time']    = $next_time;
            $flag   = db('orders_refund')->where(['orders_refund_id' => $refund['orders_refund_id']])->update($data);
            if (false == $flag) throw new \Exception('更新退款订单失败');
/*
            #   如果退款成功则需要把数量/金额/运费减去
            $table_prefix   = config('database.prefix');
            if ($state == State::STATE_REFUNDS_SUCCESS) {
                #   如果运费大于0则需要修改运费可退金额
                $orders_refund_express_amount   = round($refund['orders_refund_express_amount'], 2);
                $orders_refund_amount           = round($refund['orders_refund_amount'], 2);

                #   更新订单商品
                $sql    = "UPDATE `{$table_prefix}orders_goods` SET `goods_refund_num` = goods_refund_num - {$refund['orders_refund_num']}, 
`goods_refund_amount` = goods_refund_amount - {$orders_refund_amount}, 
`goods_service_num` = goods_service_num - {$refund['orders_refund_num']} WHERE `orders_goods_id` = {$refund['OrdersGoods']['orders_goods_id']}";
                $flag   = db()->execute($sql);
                if (false == $flag) throw new Exception('更新订单商品可退款金额失败');

                #   更新订单
                $sql    = "UPDATE `{$table_prefix}orders_shop` SET `orders_refund_amount` = orders_refund_amount - {$orders_refund_amount}, 
`orders_refund_express_amount` = orders_refund_express_amount - {$orders_refund_express_amount}, 
`orders_shop_update_time` = '{$time}' 
WHERE `orders_shop_id` = {$refund['orders_shop_id']}";
                if (false == db()->execute($sql)) throw new Exception('更新订单退款金额失败');

                #   如果金额，运费全部退完则关闭订单
            }
*/
            #   记录日志
            $logs   = [
                'refund_logs_title' => $title,
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_remark'=> input('remark'),
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new \Exception($ret['msg']);

            if ($queue) {
                #   异步退款,如果失败则数据回滚
                $amount_express += $refund['orders_refund_express_amount'];
                $flag   = $this->put($refund, F::numberFormats($amount_express));
                if (false == $flag) throw new Exception('提交至ERP失败');
            }

            #   入列
            if (isset($tube)) {
                Beanstalkd::getInstance($tube)
                    ->ordersPut($refund['orders_refund_id'],
                        $refund['orders_refund_no'],
                        $next_time - time());
            }

            Db::commit();
            return [
                'code'  => State::STATE_NORMAL,
                'msg'   => '操作成功',
            ];
        } catch (\Exception $e) {
            Db::rollback();
            return [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage(),
            ];
        }
    }

    /**
     * 更新订单
     *
     * @param array $refund
     * @return array|float|int
     */
    protected function updateRefundData(array $refund)
    {
        try {
            #   减去退款金额
            $table_prefix   = config('database.prefix');
            $ordersShop     = new \app\api\model\orders\OrdersShop();
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
                        if (false == $flag) throw new Exception('更新退运费金额失败');
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
            if (false == $flag) throw new Exception('更新退款金额失败');

            #   退款数量减1
            if ($refund['orders_refund_num'] > 0 || $refund['orders_refund_amount'] > 0) {
                $sql    = "UPDATE `{$table_prefix}orders_goods` SET `goods_refund_num` = goods_refund_num - {$refund['orders_refund_num']}, 
`goods_refund_amount` = goods_refund_amount - {$refund_amount}, 
`goods_service_num` = goods_service_num - {$refund['orders_refund_num']} WHERE `orders_goods_id` = {$refund['orders_goods_id']}";
                $flag   = db()->execute($sql);
                if (false == $flag) throw new Exception('订单商品退款数量更新失败');
            }

            return $excess_express_amount;
        } catch (Exception $e) {
            return [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage()
            ];
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
}
