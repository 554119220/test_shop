<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17 0017
 * Time: 16:30
 */

namespace mercury\office\export;


use app\api\model\orders\OrdersShop;

class ShopShip extends Export
{
    #   列名
    protected $row_title    = [
        '订单编号',
        '下单时间',
        '付款时间',
        '买家账号',
        '订单状态',
        '收货姓名',
        '收货电话',
        '收货地址',
        '快递公司',
        '快递单号',
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
                ->relation('ordersAddress,exportGoods,exportRefund')
                ->order('orders_shop_id asc')
                ->page($p, self::SQL_CHUNK)
                ->field('
                orders_shop_no, 
                orders_shop_create_time,
                orders_shop_pay_time,
                buyer_user_id,
                orders_shop_state,
                orders_shop_express_company,
                orders_shop_express_no,
                orders_id,orders_shop_id')
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
        $this->zip_filename = $this->createZip();
        $this->down();
    }

    protected function yields($key, $is_export, $o = '')
    {
        $orders = yield;
        $orders['buyer_user_id']     = db('user')->where(['user_id' => $orders['buyer_user_id']])->cache(true)->value('user_username');
        self::getExcelObject($key)->setActiveSheetIndex(0)
            ->setCellValue("A{$this->i}", $orders['orders_shop_no'])
            ->setCellValue("B{$this->i}", $orders['orders_shop_create_time'])
            ->setCellValue("C{$this->i}", $orders['orders_shop_pay_time'])
            ->setCellValue("D{$this->i}", $orders['buyer_user_id'])
            ->setCellValue("E{$this->i}", $orders['orders_shop_state_name'])
            ->setCellValue("F{$this->i}", $orders['ordersAddress']['orders_address_name'])
            ->setCellValue("G{$this->i}", $orders['ordersAddress']['orders_address_connect'])
            ->setCellValue("H{$this->i}", $orders['ordersAddress']['orders_address'])
            ->setCellValue("I{$this->i}", $orders['orders_shop_express_company_name'])
            ->setCellValue("J{$this->i}", $orders['orders_shop_express_no']);
        ++$this->i;

        #   商品
        foreach ($orders['exportGoods'] as $k => $v) {
            self::getExcelObject($key)->setActiveSheetIndex(0)
                ->setCellValue("B{$this->i}", "商品")
                ->setCellValue("C{$this->i}", $v['goods_name'])
                ->setCellValue("D{$this->i}", "商品SKU：{$v['goods_sku_name']}")
                ->setCellValue("E{$this->i}", "购买数量：{$v['orders_goods_num']} 件")
                ->setCellValue("F{$this->i}", "商品总价：{$v['orders_goods_amount']} 元")
                ->setCellValue("G{$this->i}", "商品单价：{$v['orders_goods_single_amount']} 元");
            ++$this->i;
        }
        #   退款
        if (!empty($orders['exportRefund'])) {
            foreach ($orders['exportRefund'] as $k => $v) {
                self::getExcelObject($key)->setActiveSheetIndex(0)
                    ->setCellValue("B{$this->i}", "退款")
                    ->setCellValue("C{$this->i}", "退款单号：{$v['orders_refund_no']}")
                    ->setCellValue("D{$this->i}", "退款状态：{$v['orders_refund_state_name']}")
                    ->setCellValue("E{$this->i}", "退款数量：{$v['orders_refund_num']} 件")
                    ->setCellValue("F{$this->i}", "退款金额：{$v['orders_refund_amount']} 元")
                    ->setCellValue("G{$this->i}", "退运费：{$v['orders_refund_express_amount']} 元");
                ++$this->i;
            }
        }
        #   写入到excel
        if ($this->is_export) $this->writeExcel($key);
    }
}