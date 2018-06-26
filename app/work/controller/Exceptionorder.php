<?php
/**
 * Created by PhpStorm.
 * User=> Administrator
 * Date=> 2018/3/5 0005
 * Time=> 13=>51
 */

namespace app\work\controller;
use app\common\traits\F;
use mercury\async\Beanstalkd;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\office\Excel;
use think\Exception;

/**
 * Class Exceptionorder
 * @package app\work\controller
 * @title 异常订单
 */

class Exceptionorder
{
    protected $table    = 'erp_pay_notify_', $rows = 100, $url = '';
    public function index()
    {
        $table_suffix   = request()->get('table', date('Ym'));
        $page           = request()->get('p', 1);
        $redis  = F::redis();
        $key    = 'exception_order';
        $data   = $redis->lrange($key, 0, -1);
        $tmps   = [];
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $tmp_   = explode('_', $v);
//                if (isset($tmps[$tmp_[0]]) && $tmps[$tmp_[0]] != $tmp_[1]) {
//                    $tmps["{$tmp_[0]}_{$k}"]    = $tmp_[1];
//                    continue;
//                }
                $tmps[$tmp_[0]] = $tmp_[1];
            }
        }
        dump($tmps);
        exit();
        $where          = [
            'ret.code'   => ['neq', Code::CODE_SUCCESS]
        ];
        $list   = F::mongo("{$this->table}{$table_suffix}")->where($where)->page($page, $this->rows)->order('time','desc')->select();
        if (!empty($list)) {
            $tmp    = [];
            foreach ($list as $k => $v) {
                $id = db('orders_shop')->where(['orders_shop_trade_no' => $v['code']])->cache(true)
                    ->value('orders_shop_id');
                if (!$id) {
                    $tmp[]    = "{$v['code']}_{$v['order_no']}";
                }
            }
            if (!empty($tmp)) $redis->lpush($key, $tmp);
            ++$page;
            $this->url    = F::domain('work', "/exceptionorder?p={$page}");
            sleep(1);
        }
        return view('', ['url' => $this->url]);
    }

    public function notify()
    {
        $a = array (  'code' => 'PA201803041640863260928769',
            'service' => '2',
            'lurpak' => '1019800.0000',
            'amount' => '10198.00',
            'trade_type' => '39',
            'pay_time' => '2018-03-04 16=>41=>52',
            'pay_type' => '1',
            'subject' => '【远通通讯】Apple/苹果 iPhone 8plus-A1864 64G 256G全网通苹果8p国行现货全新未激活等共计1款商品.',
            'memo' => '【远通通讯】Apple/苹果 iPhone 8plus-A1864 64G 256G全网通苹果8p国行现货全新未激活等共计1款1件商品.',
            'order_no' => 'GO20180304164132219032',
            'notify_url' => 'https=>//www.zrst.com/notify',
            'return_url' => 'https=>//wap.zrst.com/pay/payRefund?shop_no=GO20180304164132219032',
            'callback_url' => 'https=>//wap.zrst.com/pay/payRefund?shop_no=GO20180304164132219032',
            'cron_receive_time' => '0000-00-00 00=>00=>00',
            'pay_cash' => '4079.20',
            'pay_lurpak' => '407920.0000',
            'pay_consume' => '611880.0000',
            'max_use_consume' => '611880.0000',
            'discount_cash' => '0.00',
            'discount_lurpak' => '0.0000',
            'discount_ratio' => '0',
            'lurpak_to_cash_ratio' => '1',
            'commercial_tenant_id' => '2000',
            'expire_time' => '1520246630',
            'sign' => 'abd28f65034e454a5ba120862467267d');
        $a = array (  'code' => 'PA201803041940863273582611',  'service' => '2',  'lurpak' => '1249800.0000',  'amount' => '12498.00',  'trade_type' => '39',  'pay_time' => '2018-03-04 19=>27=>50',  'pay_type' => '1',  'subject' => 'Apple/苹果iPhone X 全网通4G智能手机苹果10 苹果X 256G 64G版本 全国联保现货包邮 等共计1款商品.',  'memo' => 'Apple/苹果iPhone X 全网通4G智能手机苹果10 苹果X 256G 64G版本 全国联保现货包邮 等共计1款1件商品.',  'order_no' => 'GO20180304192712765139',  'notify_url' => 'https=>//www.zrst.com/notify',  'return_url' => 'https=>//wap.zrst.com/pay/payRefund?shop_no=GO20180304192712765139',  'callback_url' => 'https=>//wap.zrst.com/pay/payRefund?shop_no=GO20180304192712765139',  'cron_receive_time' => '0000-00-00 00=>00=>00',  'pay_cash' => '4999.20',  'pay_lurpak' => '499920.0000',  'pay_consume' => '749880.0000',  'max_use_consume' => '749880.0000',  'discount_cash' => '0.00',  'discount_lurpak' => '0.0000',  'discount_ratio' => '0',  'lurpak_to_cash_ratio' => '1',  'commercial_tenant_id' => '2000',  'expire_time' => '1520300802',  'sign' => '1974f7af6d697f24e1a5d2d9737a44a2');

        $a = ["code" => "PA201803032340672887642046",
    "service" => "2",
    "lurpak" => "1089800.0000",
    "amount" => "10898.00",
    "trade_type" => "39",
    "pay_time" => "2018-03-03 23=>41=>43",
    "pay_type" => "2",
    "subject" => "Apple/苹果iPhone X 全网通4G智能手机苹果10 苹果X 256G 64G版本 全国联保现货包邮 等共计1款商品.",
    "memo" => "Apple/苹果iPhone X 全网通4G智能手机苹果10 苹果X 256G 64G版本 全国联保现货包邮 等共计1款1件商品.",
    "order_no" => "GO20180303234051185037",
    "notify_url" => "https=>//www.zrst.com/notify",
    "return_url" => "https=>//wap.zrst.com/pay/payRefund?shop_no=GO20180303234051185037",
    "callback_url" => "https=>//wap.zrst.com/pay/payRefund?shop_no=GO20180303234051185037",
    "cron_receive_time" => "0000-00-00 00=>00=>00",
    "pay_cash" => "4359.20",
    "pay_lurpak" => "435920.0000",
    "pay_consume" => "653880.0000",
    "max_use_consume" => "653880.0000",
    "discount_cash" => "0.00",
    "discount_lurpak" => "0.0000",
    "discount_ratio" => "0",
    "lurpak_to_cash_ratio" => "1",
    "commercial_tenant_id" => "2000",
    "expire_time" => "1520092623",
    "sign" => "36c40a598f888b21dc59b5984c056b18"];
//        $ret    = F::curl('https://www.zrst.com/notify', $a, false);
//        dump($ret);
    }

    public function refund()
    {
        try {
            $nos    = [
                'CO20180303235210360647',
                'CO20180303232151905007',
                'CO20180303235658301087',
                'CO20180303233609030475',
                'CO20180303232407262578',
                'CO20180303235824723363',
                'CO20180303213529895832',
                'CO20180303225232552868',
                'CO20180303235927160640',
                'CO20180303234101547975',
                'CO20180303221042920633',
                'CO20180304015920838011',
                'CO20180304000348745148',
                'CO20180303234118974273',
                'CO20180303225639227433',
                'CO20180303232220707211',
                'CO20180303232736186054',
                'CO20180303230916040329',
                'CO20180303232110258350',
                'CO20180303225755737757',
                'CO20180304000430589685',
                'CO20180303235555965154',
                'CO20180303235332676435',
                'CO20180303231926813656',
                'CO20180303234034908931',
                'CO20180303235418602061',
                'CO20180303224627414043',
                'CO20180303223343906728',
                'CO20180303124258936922',
                'CO20180303233050225420',
                'CO20180303184107019522',
                'CO20180303182905023408',
                'CO20180303112106564891',
                'CO20180301182828314407',
                'CO20180303185630423028',
                'CO20180303170551588680',
                'CO20180303165653944553',
                'CO20180303095052551712',
                'CO20180303235303978421',
                'CO20180303232248388376',
                'CO20180303083017431145',
                'CO20180303094104766975',
                'CO20180302112138932027',
                'CO20180302125813074345',
                'CO20180302124329795838',
                'CO20180302115309014691',
                'CO20180303211239018948',
                'CO20180303213852092058',
                'CO20180303223055009511',
            ];

//            foreach ($nos as $v) {
            $orders = db('orders_shop')
                ->where(['orders_shop_state' => State::STATE_ORDERS_PAY, 'orders_shop_no' => ['in', $nos]])
                ->select();
            $tmp    = [];
            if (!empty($orders)) {
//                $map    = [
//                    'orders_shop_no'    => $v
//                ];
//                $order  = db('orders_shop')
//                    ->where($map)->find();
//                if (!$order) throw new Exception('订单不存在');
                $data   = [
                    'orders_shop_state' => State::STATE_ORDERS_REFUND_CLOSE,
                    'orders_shop_close_time'    => time(),
                    'orders_shop_update_time'   => time(),
                    'orders_shop_next_time'     => Times::times(Times::TIME_ORDERS_RECEIVE),
                ];
                foreach ($orders as $v) {
                    db()->startTrans();
                    #   关闭订单
                    if (false == db('orders_shop')->where(['orders_shop_id' => $v['orders_shop_id']])->update($data))
                        throw new Exception('关闭订单失败');

                    $logs   = [
                        'orders_logs_title' => '恶意刷单，订单退款关闭',
                        'orders_shop_state' => $data['orders_shop_state'],
                        'orders_shop_id'    => $v['orders_shop_id'],
                        'orders_shop_no'    => $v['orders_shop_no'],
                        'orders_logs_is_display'    => State::STATE_NORMAL,
                        'orders_logs_create_time'   => time(),
                    ];
                    if (false == db('orders_logs')->insert($logs))
                        throw new Exception('插入日志失败');

                    if (true !== $ret = $this->createRefund($v)) {
                        throw new Exception($ret['msg'], $ret['code']);
                    };

                    F::gearmanLogs('debug', ['order' => $v, 'order_no' => $v['orders_shop_no'], 'type' => 'refund']);

                    $tmp[]  = $v['orders_shop_no'];
                    $k = array_search($v['orders_shop_no'], $nos);
                    unset($nos[$k]);
                    db()->commit();
                }
            }
            F::gearmanLogs('debug', ['nos' => $nos, 'tmp' => $tmp, 'type' => 'refund']);
//            }
            echo Code::CODE_SUCCESS;
        } catch (Exception $e) {
            db()->rollback();
            echo($e->getMessage());
//            dump($e->getData());
        }
    }

    /**
     * 创建退款订单
     *
     * @param array $orders
     * @return array|bool
     */
    protected function createRefund(array $orders)
    {
        try {
            $goods  = db('orders_goods')
                ->where('orders_shop_id', $orders['orders_shop_id'])
                ->where('goods_refund_amount', '>', State::STATE_DISABLED)
                ->select();
            if (!$goods) throw new Exception("{$orders['orders_shop_no']}-商品不存在");
            $goods_cnt  = count($goods);
            $beanstalk  = new Beanstalkd('refund_agree');
            foreach ($goods as $k => $v) {
                $data   = [
                    'orders_refund_create_time'     => time(),
                    'orders_refund_update_time'     => time(),
                    'shop_id'                       => $orders['shop_id'],
                    'orders_shop_id'                => $orders['orders_shop_id'],
                    'orders_shop_no'                => $orders['orders_shop_no'],
                    'orders_refund_state'           => State::STATE_REFUND,
                    'orders_refund_type'            => State::STATE_REFUND,
                    'seller_user_id'                => $orders['seller_user_id'],
                    'buyer_user_id'                 => $orders['buyer_user_id'],
                    'orders_refund_no'              => F::createNo('RO'),
                    'orders_refund_num'             => $v['goods_refund_num'],
                    'orders_refund_amount'          => $v['goods_refund_amount'],
                    'orders_goods_id'               => $v['orders_goods_id'],
                    'orders_refund_score'           => $v['goods_refund_score'],
                    'orders_refund_express_amount'  => State::STATE_DISABLED,
                    'orders_refund_is_ship'         => State::STATE_DISABLED,
                    'orders_refund_next_time'       => Times::times(Times::TIME_REFUND_AGREE),
                    'orders_refund_is_display'      => State::STATE_DISABLED,
                ];
                if ($k + 1 == $goods_cnt) {
                    $data['orders_refund_express_amount']   = $orders['orders_refund_express_amount'];
                }
                $refund_id   = db('orders_refund')->insertGetId($data);
                if (false == $refund_id) throw new Exception('创建退款订单失败');
                $logs   = [
                    'refund_logs_create_time'   => time(),
                    'refund_logs_update_time'   => time(),
                    'orders_refund_id'          => $refund_id,
                    'refund_state'              => $data['orders_refund_state'],
                    'refund_logs_remark'        => '商家发货超时，系统自动退款',
                    'refund_no'                 => $data['orders_refund_no'],
                    'refund_logs_title'         => '系统自动退款'
                ];
                $flag   = db('orders_refund_logs')->insert($logs);
                if (false == $flag) throw new Exception('创建退款日志失败');
                #   退款入列
                $delay  = $goods_cnt * 1;
                if (false == $beanstalk->put(
                        ['id' => $refund_id, 'no' => $data['orders_refund_no']], null, $delay))
                    throw new Exception('退款入列失败');
            }
        } catch (Exception $e) {
            return [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage(),
            ];
        }
        return true;
    }
}