<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/12 0012
 * Time: 11:01
 */

namespace mercury\office\export;


use app\api\model\orders\OrdersShop;
use app\common\traits\F;

class Orders extends Export
{
    protected $model;
    protected $row_title = [
        '父订单号',
        '子订单号',
        '卖家',
        '店铺',
        '买家',
        '付款时间',
        '付款类型',
        '付款金额',
        '现金付款金额',
        '购物积分付款金额',
        '第三方交易号',
        '奖励积分',
        '优惠金额',
        '订单总金额(含运费)',
        '修改后的金额',
        '商品总金额',
        '修改后的商品总金额',
        '运费金额',
        '修改后的运费金额',
        '订单状态',
        '商品款数',
        '商品数量',
        '可使用购物积分数量'
    ];

    #   买家，卖家
    const ORDERS_COLUMN = [
        'orders_no',
        'orders_shop_no',
        'orders_shop_pay_time',
        'orders_shop_pay_type',
        'orders_shop_pay_amount',
        'orders_shop_pay_cash',
        'orders_shop_pay_shopping_score',
        'orders_shop_trade_no',
        'orders_shop_score',
        'orders_shop_discount_amount',
        'orders_shop_amount',
        'orders_shop_edit_amount',
        'orders_shop_goods_amount',
        'orders_shop_goods_edit_amount',
        'orders_shop_express_amount',
        'orders_shop_express_edit_amount',
        'orders_shop_state',
        'goods_style_num',
        'goods_count_num',
        'orders_shop_can_use_shopping_score'
    ];

    const REFUND_COLUMN = [
        'orders_refund_no',
        'orders_refund_num',
        'orders_refund_amount',
        'orders_refund_express_amount',
        'orders_refund_score'
    ];

    const GOODS_COLUMN  = [
        'orders_goods_amount',
        'orders_goods_single_amount',
        'orders_goods_num',
        'goods_name',
        'goods_images',
        'goods_sku_name',
        'goods_score_multi',
        'goods_score',
        'goods_shopping_score_multi',
        'goods_pay_shopping_score',
        'goods_pay_cash'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->model    = new OrdersShop();
    }
    
    protected function yields($key, $is_export = false, $orders)
    {
//        $orders = yield;
        $orders['shop_id']           = db('shop')->where(['shop_id' => $orders['shop_id']])->cache(true)->value('shop_name');
        $orders['seller_user_id']    = db('user')->where(['user_id' => $orders['seller_user_id']])->cache(true)->value('user_username');
        $orders['buyer_user_id']     = db('user')->where(['user_id' => $orders['buyer_user_id']])->cache(true)->value('user_username');
        self::getExcelObject($key)->setActiveSheetIndex(0)
            ->setCellValue("A{$this->i}", $orders['orders_no'])
            ->setCellValue("B{$this->i}", $orders['orders_shop_no'])
            ->setCellValue("C{$this->i}", $orders['seller_user_id'])
            ->setCellValue("D{$this->i}", $orders['shop_id'])
            ->setCellValue("E{$this->i}", $orders['buyer_user_id'])
            ->setCellValue("F{$this->i}", $orders['orders_shop_pay_time'])
            ->setCellValue("G{$this->i}", $orders['orders_shop_pay_type_name'])
            ->setCellValue("H{$this->i}", $orders['orders_shop_pay_amount'])
            ->setCellValue("I{$this->i}", $orders['orders_shop_pay_cash'])
            ->setCellValue("J{$this->i}", $orders['orders_shop_pay_shopping_score'])
            ->setCellValue("K{$this->i}", $orders['orders_shop_trade_no'])
            ->setCellValue("L{$this->i}", $orders['orders_shop_score'])
            ->setCellValue("M{$this->i}", $orders['orders_shop_discount_amount'])
            ->setCellValue("N{$this->i}", $orders['orders_shop_amount'])
            ->setCellValue("O{$this->i}", $orders['orders_shop_edit_amount'])
            ->setCellValue("P{$this->i}", $orders['orders_shop_goods_amount'])
            ->setCellValue("Q{$this->i}", $orders['orders_shop_goods_edit_amount'])
            ->setCellValue("R{$this->i}", $orders['orders_shop_express_amount'])
            ->setCellValue("S{$this->i}", $orders['orders_shop_express_edit_amount'])
            ->setCellValue("T{$this->i}", $orders['orders_shop_state_name'])
            ->setCellValue("U{$this->i}", $orders['goods_style_num'])
            ->setCellValue("V{$this->i}", $orders['goods_count_num'])
            ->setCellValue("W{$this->i}", $orders['orders_shop_can_use_shopping_score']);
        ++$this->i;
        #   商品
        foreach ($orders['exportGoods'] as $k => $v) {
            self::getExcelObject($key)->setActiveSheetIndex(0)
                ->setCellValue("F{$this->i}", "商品")
                ->setCellValue("G{$this->i}", $v['goods_name'])
                ->setCellValue("H{$this->i}", $v['orders_goods_amount'])
                ->setCellValue("I{$this->i}", $v['goods_pay_cash'])
                ->setCellValue("J{$this->i}", $v['goods_pay_shopping_score'])
                ->setCellValue("K{$this->i}", $v['goods_score_multi'])
                ->setCellValue("L{$this->i}", $v['goods_score'])
                ->setCellValue("M{$this->i}", $v['goods_shopping_score_multi'])
                ->setCellValue("N{$this->i}", $v['orders_goods_amount'])
                ->setCellValue("O{$this->i}", $v['orders_goods_num']);
            ++$this->i;
        }
        #   退款
        if (!empty($orders['exportRefund'])) {
            foreach ($orders['exportRefund'] as $k => $v) {
                self::getExcelObject($key)->setActiveSheetIndex(0)
                    ->setCellValue("F{$this->i}", "退款")
                    ->setCellValue("G{$this->i}", $v['orders_refund_no'])
                    ->setCellValue("H{$this->i}", $v['orders_refund_amount'])
                    ->setCellValue("I{$this->i}", $v['orders_refund_express_amount'])
                    ->setCellValue("J{$this->i}", $v['orders_refund_state_name'])
                    ->setCellValue("K{$this->i}", $v['orders_refund_score'])
                    ->setCellValue("L{$this->i}", $v['orders_refund_num']);
                ++$this->i;
            }
        }
        if ($is_export) $this->writeExcel($key);
    }

    /**
     * @title run
     * > you are api description
     */
    public function run()
    {
        $p = 1;
        $k = 1;

        while (true) {
            $orders = $this->model->where($this->map)
                ->relation('exportGoods,exportRefund')
//            $orders = db('orders_shop')->where($this->map)
                /*->field('
                orders_id,
            orders_no,
            orders_shop_create_time,
            shop_id,
            orders_shop_id,
            orders_shop_no,
            seller_user_id,
            buyer_user_id,
            orders_shop_pay_time,
            orders_shop_is_pay,
            orders_shop_pay_type,
            orders_shop_pay_amount,
            orders_shop_pay_cash,
            orders_shop_pay_shopping_score,
            orders_shop_trade_no,
            orders_shop_score,
            orders_shop_discount_amount,
            orders_shop_amount,
            orders_shop_edit_amount,
            orders_shop_goods_amount,
            orders_shop_goods_edit_amount,
            orders_shop_express_amount,
            orders_shop_express_edit_amount,
            orders_shop_state,
            goods_style_num,
            goods_count_num,
            orders_shop_can_use_shopping_score')*/
                ->order('orders_shop_pay_time', 'asc')
                ->page($p)
                ->limit(self::SQL_CHUNK)
                ->select();
            if ($orders) {
                $orders = collection($orders)->toArray();
                $cnt    = count($orders);
                $this->is_export    = false;
                foreach ($orders as $key => $order) {
//                                        F::gearmanLogs('export', [
//                        'orders_no' => $order['orders_no'],
//                        'shop_no'   => $order['orders_shop_no'],
//                        'trade_no'  => $order['orders_shop_trade_no']
//                    ]);
                    if ($cnt == ($key + 1) && (($k * self::SQL_CHUNK) == self::SINGLE_FILE_MAX_ROW || $cnt < self::SQL_CHUNK)) {
                        $k  = 0;
                        $this->is_export = true;
                    }

                    $this->yields($this->file_number, $this->is_export, $order);
                    if ($this->is_export) $this->file_number++;
                }
                ++$p;
                ++$k;
                if ($cnt < self::SQL_CHUNK) break;
            } else {
                break;
            }
        }
        /*
        $lastId = $this->model->where($this->map)->order('orders_shop_id', 'desc')->value('orders_shop_id');
        $this->model->where($this->map)
            ->relation('exportGoods,exportRefund')
            ->field('
            orders_no,
            orders_shop_create_time,
            shop_id,
            orders_shop_id,
            orders_shop_no,
            seller_user_id,
            buyer_user_id,
            orders_shop_pay_time,
            orders_shop_is_pay,
            orders_shop_pay_type,
            orders_shop_pay_amount,
            orders_shop_pay_cash,
            orders_shop_pay_shopping_score,
            orders_shop_trade_no,
            orders_shop_score,
            orders_shop_discount_amount,
            orders_shop_amount,
            orders_shop_edit_amount,
            orders_shop_goods_amount,
            orders_shop_goods_edit_amount,
            orders_shop_express_amount,
            orders_shop_express_edit_amount,
            orders_shop_state,
            goods_style_num,
            goods_count_num,
            orders_shop_can_use_shopping_score')
//            ->order('orders_shop_pay_time asc')
            ->chunk(self::SQL_CHUNK, function ($orders) use ($lastId) {
                if ($orders) {
                    $orders = collection($orders)->toArray();
                    $cnt    = count($orders);
                    $this->is_export    = false;
                    foreach ($orders as $key => $order) {
//                        if ($cnt == ($key + 1) && (($k * self::SQL_CHUNK) == self::SINGLE_FILE_MAX_ROW || $cnt < self::SQL_CHUNK)) {
                        #if ($cnt < self::SQL_CHUNK) {
//                            $k  = 0;
                        if ($order['orders_shop_id'] == $lastId) {
//                            dump($order);
                            $this->is_export = true;
                        }
                        $this->yields($this->file_number, $this->is_export, $order);
//                        if ($this->is_export) $this->file_number++;
                    }
                    /*
                    $orders = collection($orders)->toArray();
                    $cnt    = count($orders);
//                    if ($cnt < self::SQL_CHUNK) F::writeLog($orders);
                    $this->single_file_max_row  += self::SQL_CHUNK;
                    if ($this->single_file_max_row == self::SINGLE_FILE_MAX_ROW || $cnt < self::SQL_CHUNK) {
                        $this->single_file_max_row = 0;
                        $this->file_number++;
                    } else {
                        $this->is_export    = false;
                    }
                    foreach ($orders as $k => $order) {
                        if ($this->single_file_max_row == 0 && ($k+1 == $cnt) || ($cnt < self::SQL_CHUNK && ($k+1 == $cnt))) {
                            F::writeLog("{$this->single_file_max_row} -- {$cnt} -- {$k}");
                            $this->is_export    = true;
                        }
                        $this->yields($this->file_number, $this->is_export)->send($order);
                    }
                }
            });*/
//        echo($this->model->getLastSql());
//        exit();
        F::gearmanLogs('debug', [
            'type'  => 'export',
            'sql'   => $this->model->getLastSql()
        ]);
        $this->zip_filename = $this->createZip();
        $this->down();
    }

}