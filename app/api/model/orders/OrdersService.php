<?php
namespace app\api\model\orders;
use app\common\traits\F;
use mercury\constants\State;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-20 14:25:42
 */
class OrdersService extends \think\Model
{
    protected $pk = 'orders_service_id';
    protected $append = ['orders_service_state_name', 'orders_service_next_run_time'];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'orders_service_create_time';
    protected $updateTime = 'orders_service_update_time';
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
    public function getOrdersServiceStateNameAttr($value, $data)
    {
        if (isset($data['orders_service_state'])) {
            return State::STATE_SERVICE_ARRAY[$data['orders_service_state']];
        }
        return '';
    }

    /**
     * 售后倒计时
     *
     * @param $value
     * @param $data
     * @return string
     */
    public function getOrdersServiceNextRunTimeAttr($value, $data)
    {
        if (!isset($data['orders_service_next_time']) || !isset($data['orders_service_state'])) return '';
        $next_time  = $data['orders_service_next_time'] - time();
        if ($next_time <= 0) return '';
        $next_time  = F::secToTime($next_time);
        $title      = "剩余 {$next_time}";
        switch ($data['orders_service_state']) {
            case State::STATE_SERVICE_NORMAL:
                $title   = "{$title} 自动同意";
                break;
            case State::STATE_SERVICE_AGREE:
                $title   = "{$title} 自动取消";
                break;
            case State::STATE_SERVICE_BUYER_EXPRESS:
                $title   = "{$title} 商家自动确认收货";
                break;
            case State::STATE_SERVICE_SELLER_EXPRESS:
                $title   = "{$title} 买家自动确认收货";
                break;
            case State::STATE_SERVICE_SELLER_REFUSE:
                $title   = "{$title} 自动取消";
                break;
            default :
                $title = '';
        }
        return $title;
    }



    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */

    /**
     * 获取当前商品售后中的数量
     *
     * @param string $shop_no
     * @param int $goods_id
     * @param string $service_no
     * @return mixed
     */
    public function sumOldService($shop_no, $goods_id, $service_no = '')
    {
        $num    = $this->where('orders_shop_no', $shop_no)
            ->where('orders_goods_id', $goods_id)
            ->whereNotIn('orders_service_state', [State::STATE_SERVICE_CANCEL, State::STATE_SERVICE_SUCCESS])
            ->where(function ($query) use ($service_no) {
                if ($service_no) {
                    $query->where('orders_service_no', '<>', $service_no);
                }
            })->value('SUM(orders_service_num) as num');
        return $num;
    }


    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


    /**
     * 一对一关联 - orders_service_logs - 售后表
     */
    public function OrdersServiceLogs()
    {
        return $this->hasMany("OrdersServiceLogs", "orders_service_id", "orders_service_id");
    }


    /**
     * 一对多关联 - orders_service_address - 售后收货地址
     */
    public function OrdersServiceAddress()
    {
        return $this->hasMany("OrdersServiceAddress", "orders_service_id", "orders_service_id");
    }

    /**
     * 商品关联
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