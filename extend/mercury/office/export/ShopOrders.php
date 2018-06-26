<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17 0017
 * Time: 16:29
 */

namespace mercury\office\export;


use app\api\model\orders\OrdersShop;

/**
 * Class ShopOrders
 * @package mercury\office\export
 * @title 商家订单导出
 * $export = new ShopOrders();
 * $export->setDownloadFilename('商家对账')->setMap(['shop_id' => 81])->run();
 */
class ShopOrders extends Export
{
    #   列名
    protected $row_title = [
        '订单号',
        '买家',
        '下单时间',
        '付款时间',
        '收货时间',
        '付款金额',
        '第三方交易号',
        '商品金额',
        '运费金额',
        '优惠金额',
        '订单状态',
        '商品款数',
        '商品数量',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->model    = new OrdersShop();
    }

    public function run()
    {
        $p = 1;
        $k = 1;
        while (true) {
            $orders = $this->model
                ->where($this->map)
                ->relation('exportGoods,exportRefund')
                ->order('orders_shop_id asc')
                ->page($p, self::SQL_CHUNK)
                ->field('
                orders_shop_no, 
                orders_shop_create_time,
                orders_shop_pay_time,
                orders_shop_receive_time,
                buyer_user_id,
                orders_shop_state,
                orders_shop_pay_amount,
                orders_shop_trade_no,
                orders_shop_goods_edit_amount,
                orders_shop_express_edit_amount,
                goods_style_num,
                goods_count_num,
                orders_shop_discount_amount,
                orders_shop_id')
                ->select();
            if ($orders) {
                $orders = collection($orders)->toArray();
                $cnt    = count($orders);
                $this->is_export    = false;
                foreach ($orders as $key => $order) {
                    if ($cnt == ($key + 1) && (($k * self::SQL_CHUNK) == self::SINGLE_FILE_MAX_ROW || $cnt < self::SQL_CHUNK)) {
                        $k  = 0;
                        $this->is_export = true;
                    }
                    $this->yields($this->file_number, $this->is_export)->send($order);
                    if ($this->is_export) $this->file_number++;
                }
                ++$p;
                ++$k;
                if ($cnt < self::SQL_CHUNK) break;
            } else {
                break;
            }
        }
        if (!empty($orders)) {
            $this->zip_filename = $this->createZip();
            $this->down();
        } else {
            echo '无数据导出';
            //throw new \Exception('无数据导出');
        }
    }

    protected function yields($key, $is_export, $o = '')
    {
        $orders = yield;
        $orders['buyer_user_id']     = db('user')->where(['user_id' => $orders['buyer_user_id']])->cache(true)->value('user_username');
        self::getExcelObject($key)->setActiveSheetIndex(0)
            ->setCellValue("A{$this->i}", $orders['orders_shop_no'])
            ->setCellValue("B{$this->i}", $orders['buyer_user_id'])
            ->setCellValue("C{$this->i}", $orders['orders_shop_create_time'])
            ->setCellValue("D{$this->i}", $orders['orders_shop_pay_time'])
            ->setCellValue("E{$this->i}", $orders['orders_shop_receive_time'])
            ->setCellValue("F{$this->i}", $orders['orders_shop_pay_amount'])
            ->setCellValue("G{$this->i}", $orders['orders_shop_trade_no'])
            ->setCellValue("H{$this->i}", $orders['orders_shop_goods_edit_amount'])
            ->setCellValue("I{$this->i}", $orders['orders_shop_express_edit_amount'])
            ->setCellValue("J{$this->i}", $orders['orders_shop_discount_amount'])
            ->setCellValue("K{$this->i}", $orders['orders_shop_state_name'])
            ->setCellValue("L{$this->i}", $orders['goods_style_num'])
            ->setCellValue("M{$this->i}", $orders['goods_count_num']);
        ++$this->i;

        #   商品
        foreach ($orders['exportGoods'] as $k => $v) {
            self::getExcelObject($key)->setActiveSheetIndex(0)
                ->setCellValue("F{$this->i}", "商品")
                ->setCellValue("G{$this->i}", $v['goods_name'])
                ->setCellValue("H{$this->i}", $v['orders_goods_amount'])
                ->setCellValue("I{$this->i}", $v['orders_goods_num'])
                ->setCellValue("J{$this->i}", $v['orders_goods_amount'])
                ->setCellValue("K{$this->i}", $v['goods_score_multi'])
                ->setCellValue("L{$this->i}", $v['goods_score'])
                ->setCellValue("M{$this->i}", $v['goods_shopping_score_multi'])
                ->setCellValue("N{$this->i}", $v['goods_pay_shopping_score'])
                ->setCellValue("O{$this->i}", $v['goods_pay_cash']);
            ++$this->i;
        }
        #   退款
        if (!empty($orders['exportRefund'])) {
            foreach ($orders['exportRefund'] as $k => $v) {
                self::getExcelObject($key)->setActiveSheetIndex(0)
                    ->setCellValue("F{$this->i}", "退款")
                    ->setCellValue("G{$this->i}", $v['orders_refund_no'])
                    ->setCellValue("H{$this->i}", $v['orders_refund_state_name'])
                    ->setCellValue("I{$this->i}", $v['orders_refund_num'])
                    ->setCellValue("J{$this->i}", $v['orders_refund_amount'])
                    ->setCellValue("K{$this->i}", $v['orders_refund_score'])
                    ->setCellValue("L{$this->i}", $v['orders_refund_express_amount']);
                ++$this->i;
            }
        }
        if ($is_export) $this->writeExcel($key);
    }
}