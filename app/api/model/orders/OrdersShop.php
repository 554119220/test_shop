<?php
namespace app\api\model\orders;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\factory\Factory;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-16 17:31:35
 */
class OrdersShop extends \think\Model
{
    protected $pk = 'orders_shop_id';
    protected $append = ['orders_shop_state_name', 'orders_shop_express_company_name', 'orders_shop_pay_type_name', 'orders_shop_next_run_time'];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'orders_shop_create_time';
    protected $updateTime = 'orders_shop_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];
    #   可退运费
    protected $express_amount   = 0, $refund_num = 0, $refund_amount = 0;


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
     * @param $value
     * @return mixed
     *
     * 获取状态
     */
//    public function getOrdersShopStateAttr($value)
//    {
//        return State::STATE_ORDERS_ARRAY[$value];
//    }

    public function getOrdersShopStateNameAttr($value, $data)
    {
        return isset($data['orders_shop_state']) ? State::STATE_ORDERS_ARRAY[$data['orders_shop_state']] : '';
    }

    /**
     * 获取下次执行时间
     *
     * @param $value
     * @param $data
     * @return string
     */
    public function getOrdersShopNextRunTimeAttr($value, $data)
    {
        if (!isset($data['orders_shop_next_time']) || !isset($data['orders_shop_state'])) return '';
        $next_time  = $data['orders_shop_next_time'] - time();
        if ($next_time <= 0) return '';
        $next_time  = F::secToTime($next_time);
        $title      = "剩余 {$next_time}";
        switch ($data['orders_shop_state']) {
            case State::STATE_ORDERS_NORMAL:
                $title   = "{$title} 自动关闭";
                break;
            case State::STATE_ORDERS_PAY:
                $title   = "{$title} 自动退款";
                break;
            case State::STATE_ORDERS_SHIP:
                $title   = "{$title} 自动确认收货";
                break;
            case State::STATE_ORDERS_RECEIVE:
                $title   = "{$title} 自动评价";
                break;
            default :
                $title = '';
        }
        return $title;
    }

    public function getOrdersShopNextTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getOrdersShopExpressTimeAttr($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 获取支付时间
     *
     * @param $value
     * @return false|string
     */
    public function getOrdersShopPayTimeAttr($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

    public function getOrdersShopReceiveTimeAttr($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

    public function getOrdersShopCommentTimeAttr($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

    public function getOrdersShopCloseTimeAttr($value)
    {
        if (!$value) return '';
        return date('Y-m-d H:i:s', $value);
    }

    public function getOrdersShopExpressNoAttr($value)
    {
        return $value ? : '';
    }

    public function getOrdersShopTradeNoAttr($value)
    {
        if (!$value) return '';
        return $value;
    }

    public function getOrdersShopPayTypeNameAttr($value, $data)
    {
        if (!isset($data['orders_shop_pay_type']) || !$data['orders_shop_pay_type']) return '';
        $data   = Factory::instance('/pay/v1/payType/detail')->run(['id' => $data['orders_shop_pay_type']]);
        if ($data['code'] == Code::CODE_SUCCESS) return $data['data']['pay_type_name'];
        return '付款方式不存在';
    }

    /**
     * 获取快递公司名称
     *
     * @param $value
     * @param $data
     * @return mixed|string
     */
    protected function getOrdersShopExpressCompanyNameAttr($value, $data)
    {
        if (!isset($data['orders_shop_express_company'])) return '';
        return db('express_company')->where(['company_id' => $data['orders_shop_express_company']])
            ->cache(true)->value('company_name');
    }

    /**
     * 获取已退运费
     *
     * @return mixed
     */
    public function getExpressAmount()
    {
        return $this->express_amount;
    }

    /**
     * 获取已退款数量
     *
     * @return int
     */
    public function getRefundNum()
    {
        return $this->refund_num;
    }

    /**
     * 获取已退款金额
     *
     * @return int
     */
    public function getRefundAmount()
    {
        return $this->refund_amount;
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
    public function ordersAddress()
    {
        return $this->hasOne('OrdersAddress', 'orders_id', 'orders_id');
    }

    public function Buyer()
    {
        return $this->hasOne('');
    }

    public function Shop()
    {
        return $this->hasOne('');
    }

    public function Seller()
    {
        return $this->hasOne('');
    }

    public function goods()
    {
        return $this->hasMany('OrdersGoods', 'orders_shop_id', 'orders_shop_id')->order('orders_goods_id asc');
    }

    public function receiveGoods()
    {
        return $this->hasMany('OrdersGoods', 'orders_shop_id', 'orders_shop_id')->where('goods_service_num', '>', State::STATE_DISABLED);
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function commentGoods()
    {
        return $this->hasMany('OrdersGoods', 'orders_shop_id', 'orders_shop_id')
            ->where('orders_is_comment', State::STATE_DISABLED)
            ->where('goods_refund_num', '>', State::STATE_DISABLED);
        //return $this->hasMany('OrdersGoods', 'orders_shop_id', 'orders_shop_id');
    }

    public function logs()
    {
        return $this->hasMany('OrdersLogs', 'orders_shop_id', 'orders_shop_id');
    }

    /**
     * 列出当前订单可以退所有商品
     *
     * @return \think\model\relation\HasMany
     */
    public function refundOrders()
    {
        return $this->hasMany('OrdersGoods', 'orders_shop_id', 'orders_shop_id')->where(function ($query) {
//            $query->where('goods_refund_amount', '>', State::STATE_DISABLED);
        });
    }

    /**
     * 列出当前申请的商品
     *
     * @return \think\model\relation\HasOne
     */
    public function refundApply()
    {
        $refund_logs_model  = new OrdersRefund();
        $map    = [
            'orders_shop_no'    => request()->data['shop_no'],
            'orders_goods_id'   => request()->data['goods_id'],
            'orders_refund_state'   => ['not in', [State::STATE_REFUNDS_SUCCESS, State::STATE_REFUNDS_CANCEL]]
        ];
        $refunds= $refund_logs_model->where($map)->field('SUM(orders_refund_amount) as amount, SUM(orders_refund_num) as num, SUM(orders_refund_express_amount) as express_amount')
            ->find()->toArray();
        $refunds['amount']  = $refunds['amount'] ? : 0;
        $refunds['num'] = $refunds['num'] ? : 0;
        $refunds['express_amount'] = $refunds['express_amount'] ? : 0;
        $this->refund_amount    = $refunds['amount'];
        $this->express_amount   = $refunds['express_amount'];
        $this->refund_num       = $refunds['num'];
        return $this->hasOne('OrdersGoods', 'orders_shop_id', 'orders_shop_id')->where(function ($query) use ($refunds) {
            $query->where('orders_goods_id', request()->data['goods_id']);
//            ->where('goods_refund_amount', '>', $refunds['amount']);
        });
    }

    /**
     * 列出当前订单可以退所有商品
     *
     * @return \think\model\relation\HasMany
     */
    public function serviceOrders()
    {
//        $service_model  = new OrdersService();
//        $map    = [
//            'orders_shop_no'    => request()->data['shop_no'],
////            'orders_goods_id'   => request()->data['goods_id'],
//            'orders_service_state'   => ['not in', [State::STATE_SERVICE_CANCEL, State::STATE_SERVICE_SUCCESS]]
//        ];
        //$service= $service_model->where($map)->field('SUM(orders_service_num) as num')->find()->toArray();
        //$service['num'] = $service['num'] > 0 ? $service['num'] : 0;
        return $this->hasMany('OrdersGoods', 'orders_shop_id', 'orders_shop_id')
            ->where('goods_service_num', '>', 0)->where('goods_service_last_day', '>=', time());
        /**
         * ->chunk(100, function ($goods) {
        if ($goods) {
        //                array_filter($goods, function ($val) {
        //                    F::writeLog($val);
        //                });
        }
        })
         */
    }

    /**
     * 列出当前申请的商品
     *
     * @return \think\model\relation\HasOne
     */
    public function serviceApply()
    {
        return $this->hasOne('OrdersGoods', 'orders_shop_id', 'orders_shop_id')
            ->where('orders_goods_id', request()->param('goods_id'))
            ->where('goods_service_num', '>', 0)
            ->where('goods_service_last_day', '>=', time());
    }

    /**
     * 获取快递公司
     *
     * @return \think\model\relation\HasOne
     */
    public function expressCompany()
    {
        return $this->hasOne(\app\api\model\tools\ExpressCompany::class, 'company_id', 'orders_shop_express_company');
    }

    /**
     * 父订单
     *
     * @return \think\model\relation\HasOne
     */
    public function orderGroup()
    {
        return $this->hasOne('Orders', 'orders_id', 'orders_id');
    }

    /**
     * @title exportRefund
     * @return \think\model\relation\HasMany
     */
    public function exportRefund()
    {
        return $this->hasMany('OrdersRefund', 'orders_shop_id', 'orders_shop_id')
            ->where(['orders_refund_state' => State::STATE_REFUND_SUCCESS])
            ->field('
            orders_shop_id,
            orders_shop_no,
            orders_refund_state,
            orders_refund_type,
            orders_refund_no,
            orders_refund_num,
            orders_refund_amount,
            orders_refund_score,
            orders_refund_express_amount
           ');
    }

    /**
     * @title exportGoods
     * @return \think\model\relation\HasMany
     */
    public function exportGoods()
    {
        return $this->hasMany('OrdersGoods', 'orders_shop_id', 'orders_shop_id')
            ->field('
            orders_shop_id,
            orders_shop_no,
            goods_id,
            orders_goods_amount,
            orders_goods_single_amount,
            orders_goods_num,
            goods_name,
            goods_images,
            goods_sku_name,
            goods_score_multi,
            goods_score,
            goods_shopping_score_multi,
            goods_pay_shopping_score,
            goods_pay_cash');
    }
}