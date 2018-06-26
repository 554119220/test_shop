<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/14 0014
 * Time: 14:32
 */

namespace app\work\controller;


use mercury\constants\State;
use mercury\office\export\Orders;
use think\Db;
use think\Exception;

class Filterstatistics extends Common
{
    protected $eday, $sday;
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
//dump(date('Y-m-d H:i:s', mktime(0, 0, 0, intval(date('m'))-1)));
//dump(date('m'));
//        dump(request()->param());
//
        $map    = $this->parseMap();
//dump($map);
        #   订单统计
        $orders = db('orders_shop')
            ->where($map)
            ->field([
               'COUNT(*) as count',
               'SUM(IF(orders_shop_is_pay = 1, 1, 0)) AS pay_count',
               'SUM(IF(orders_shop_is_pay = 1, orders_shop_pay_amount, 0)) as pay_amount1',
               'SUM(orders_shop_pay_amount) AS pay_amount',
               'SUM(orders_shop_pay_cash) AS pay_cash',
               'SUM(orders_shop_pay_shopping_score) AS pay_shopping_score',
               'SUM(orders_shop_discount_amount) AS discount_amount',
               'SUM(orders_shop_amount) AS shop_amount'
            ])->find();
//echo db('orders_shop')->getLastSql();
//        dump($orders);

        $refund_map = $this->parseMap(true);

//
//        #   退款金额
//        dump($refund_map);
        $refund = db('orders_refund')
            ->where($refund_map)
            ->field([
                'COUNT(*) AS count',
                'SUM(IF(orders_refund_state = 100, 1, 0)) AS complete_count',
                'SUM(orders_refund_amount) AS all_refund_amount',
                'SUM(IF(orders_refund_state = 100, orders_refund_amount, 0)) AS complete_amount',
                'SUM(IF(orders_refund_state = 20, orders_refund_amount, 0)) AS cancel_amount',
                'SUM(IF(orders_refund_state != 20 && orders_refund_state != 100, orders_refund_amount, 0)) AS now_amount',
            ])->find();

        return view('', ['data' => $map, 'orders' => $orders, 'refund' => $refund, 'sday' => date('Y-m-d', $this->sday), 'eday' => date('Y-m-d', $this->eday)]);
    }

    protected function parseMap($refund = false)
    {
        $map    = [];
        if (isset($_GET['orders_shop_no'])) $map['orders_shop_no'] = $_GET['orders_shop_no'];
        if (isset($_GET['shop_id'])) $map['shop_id'] = $_GET['shop_id'];
        if (isset($_GET['seller_user_id'])) $map['seller_user_id'] = $_GET['seller_user_id'];
        if (isset($_GET['buyer_user_id'])) $map['buyer_user_id'] = $_GET['buyer_user_id'];
        //if (isset($_GET['custom_between_day'])) $map['custom_between_day'] = $_GET['custom_between_day'];

        #   时间区间
        if (isset($_GET['custom_between_day']) && !empty($_GET['custom_between_day'])) {
            $between    = $_GET['custom_between_day'];
        } else {
            $between    = 'orders_shop_create_time';
        }

        if (isset($_GET['custom_sday']) && !empty($_GET['custom_sday'])) {
            $this->sday   = strtotime($_GET['custom_sday']);
        } else {
            #   默认减一个月
            $this->sday   = strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0, intval(date('m'))-1)));
        }

        if (isset($_GET['custom_eday']) && !empty($_GET['custom_eday'])) {
            $this->eday   = strtotime($_GET['custom_eday']);
        } else {
            #   默认为当前时间
            $this->eday   = time();
        }

        if (!$refund) {
            if (isset($_GET['orders_no'])) $map['orders_no'] = $_GET['orders_no'];
            if (isset($_GET['orders_shop_is_pay'])) $map['orders_shop_is_pay'] = $_GET['orders_shop_is_pay'];
            if (isset($_GET['orders_shop_pay_type'])) $map['orders_shop_pay_type'] = $_GET['orders_shop_pay_type'];
            if (isset($_GET['orders_shop_is_freeze'])) $map['orders_shop_is_freeze'] = $_GET['orders_shop_is_freeze'];
            if (isset($_GET['orders_shop_pay_amount'])) $map['orders_shop_pay_amount'] = $_GET['orders_shop_pay_amount'];
            if (isset($_GET['orders_shop_trade_no'])) $map['orders_shop_trade_no'] = $_GET['orders_shop_trade_no'];
            if (isset($_GET['orders_shop_express_company'])) $map['orders_shop_express_company'] = $_GET['orders_shop_express_company'];
            if (isset($_GET['orders_shop_express_no'])) $map['orders_shop_express_no'] = $_GET['orders_shop_express_no'];
            if (isset($_GET['orders_shop_state'])) $map['orders_shop_state'] = $_GET['orders_shop_state'];
        } else {
            $between    = 'orders_refund_create_time';
        }

        $map[$between]   = ['between', "{$this->sday},{$this->eday}"];

        $map    = array_filter($map, function($val) {
            return $val !== '';
        });
        if (isset($map['shop_id'])) {
            $map['shop_id'] = db('shop')->where(['shop_name' => $map['shop_id']])->value('shop_id') ? : '';
        }
        if (isset($map['seller_user_id'])) {
            $map['seller_user_id'] = db('user')->where(['user_username' => $map['seller_user_id']])->value('user_id') ? : '';
        }
        if (isset($map['buyer_user_id'])) {
            $map['buyer_user_id'] = db('user')->where(['user_username' => $map['buyer_user_id']])->value('user_id') ? : '';
        }
        return $map;
    }

    public function export()
    {
        try {
            ini_set('max_execution_time', 0);
//            dump($this->parseMap());
            $obj    = new Orders();
            $obj->setDownloadFilename('对账')->setMap($this->parseMap())->run();
        } catch (Exception $e) {
            dump($e->getMessage());
        }
    }
}