<?php
namespace app\api\model\orders;

use app\common\traits\F;
use mercury\constants\State;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-20 14:13:42
 */
class OrdersRefund extends \think\Model
{
    protected $pk = 'orders_refund_id';
    protected $append = ['orders_refund_state_name', 'orders_refund_type_name', 'orders_refund_next_run_time'];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'orders_refund_create_time';
    protected $updateTime = 'orders_refund_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */

    /**
     * 获取退款状态
     *
     * @param $value
     * @param $data
     * @return string
     */
    public function getOrdersRefundStateNameAttr($value, $data)
    {
        if (isset($data['orders_refund_state'])) {
            return State::STATE_REFUNDS_ARRAY[$data['orders_refund_state']];
        }
        return '';
    }

    /**
     * 获取下次执行时间
     *
     * @param $value
     * @param $data
     * @return string
     */
    public function getOrdersRefundNextRunTimeAttr($value, $data)
    {
        if (!isset($data['orders_refund_next_time']) || !isset($data['orders_refund_state'])) return '';
        $next_time  = $data['orders_refund_next_time'] - time();
        if ($next_time <= 0) return '';
        $next_time  = F::secToTime($next_time);
        $title      = "剩余 {$next_time}";
        switch ($data['orders_refund_state']) {
            case State::STATE_REFUNDS_NORMAL:
                $title   = "{$title} 自动同意";
                break;
            case State::STATE_REFUNDS_AGREE:
                $title   = "{$title} 自动取消";
                break;
            case State::STATE_REFUNDS_EXPRESS:
                $title   = "{$title} 自动确认收货";
                break;
            case State::STATE_REFUNDS_REFUSE:
                $title   = "{$title} 自动取消";
                break;
            default :
                $title = '';
        }
        return $title;
    }

    /**
     * 获取退款类型
     *
     * @param $value
     * @param $data
     * @return string
     */
    public function getOrdersRefundTypeNameAttr($value, $data)
    {
        return isset($data['orders_refund_type']) ?
            State::STATE_REFUND_TYPE[$data['orders_refund_type']] :
            '';
    }

    /**
     * 获取正则退款的金额，数量，运费
     *
     * @param string $orders_shop_no
     * @param int $orders_goods_id
     * @param string $refund_no
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function sumOldRefunds($orders_shop_no, $orders_goods_id, $refund_no = '')
    {
        $data   = $this->where('orders_shop_no', $orders_shop_no)
            ->where('orders_goods_id', $orders_goods_id)
            ->whereNotIn('orders_refund_state', [State::STATE_REFUNDS_SUCCESS, State::STATE_REFUNDS_CANCEL])
            ->where(function ($query) use ($refund_no) {
                if ($refund_no) {
                    $query->where('orders_refund_no', '<>', $refund_no);
                }
            })
            ->field('SUM(orders_refund_num) as num, SUM(orders_refund_amount) as amount, SUM(orders_refund_express_amount) as express_amount')->find()->toArray();
        $data['num']    = $data['num'] ?  : 0;
        $data['amount'] = $data['amount'] ? F::amountCalc($data['amount']) : 0;
        $data['express_amount'] = $data['express_amount'] ? F::amountCalc($data['express_amount']) : 0;
        return $data;
    }

    /**
     * 获取正在退的运费
     *
     * @param string $shop_no
     * @param string $refund_no
     * @return float
     */
    public function sumOldRefundsExpressAmount($shop_no, $refund_no = '')
    {
        $amount = $this->where('orders_shop_no', $shop_no)
            ->whereNotIn('orders_refund_state', [State::STATE_REFUNDS_SUCCESS, State::STATE_REFUNDS_CANCEL])
            ->where(function($query) use ($refund_no) {
                if ($refund_no) {
                    $query->where('orders_refund_no', '<>', $refund_no);
                }
            })
            ->value('SUM(orders_refund_express_amount) as express_amount');
        $amount = $amount ? F::amountCalc($amount) : 0;
        return $amount;
    }

    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */

    /**
     * 关联订单商品
     *
     * @return \think\model\relation\HasOne
     */
//    public function ordersGoods()
//    {
//        return $this->hasOne('OrdersGoods', 'orders_goods_id', 'orders_goods_id');
//    }


    /**
     * 一对一关联 - orders_refund_address - 退货地址管理
     */
    public function OrdersRefundAddress()
    {
        return $this->hasOne("OrdersRefundAddress", "orders_refund_id", "orders_refund_id");
    }


    /**
     * 一对多关联 - orders_refund_logs - 退款日志记录表
     */
    public function OrdersRefundLogs()
    {
        return $this->hasMany("OrdersRefundLogs", "orders_refund_id", "orders_refund_id");
    }

    /**
     * 订单商品
     *
     * @return \think\model\relation\HasOne
     */
    public function OrdersGoods()
    {
        return $this->hasOne('OrdersGoods', 'orders_goods_id', 'orders_goods_id');
    }

    /**
     * 关联订单
     *
     * @return \think\model\relation\HasOne
     */
    public function ordersShop()
    {
        return $this->hasOne('OrdersShop', 'orders_shop_id', 'orders_shop_id');
    }

}