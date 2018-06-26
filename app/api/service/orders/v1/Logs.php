<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 11:24
 */

namespace app\api\service\orders\v1;


use app\api\model\orders\OrdersLogs;
use app\api\model\orders\OrdersRefundLogs;
use app\api\model\orders\OrdersServiceLogs;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\ResponseException;
use traits\think\Instance;

/**
 * 订单/退款/售后日子记录类
 *
 * Class Logs
 * @package app\api\service\orders\v1
 */
class Logs
{
    use Instance;

    /**
     * 订单操作日志
     *
     * @return array|int
     */
    public function orders(array $params)
    {
        try {
            $orders_logs_model  = new OrdersLogs();
            $orders_logs_model->data($params);
            $flag   = $orders_logs_model->save();
            if (false == $flag)
                throw new ResponseException(Code::CODE_OTHER_FAIL, '记录日志失败');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 退款操作日志
     *
     * @return array|int
     */
    public function refund(array $params)
    {
        try {
            if (empty($params['refund_logs_images'])) unset($params['refund_logs_images']);
            if (empty($params['refund_logs_remark'])) unset($params['refund_logs_remark']);
            $orders_refund_logs_model   = new OrdersRefundLogs();
            $orders_refund_logs_model->data($params);
            if (false == $orders_refund_logs_model->save())
                throw new ResponseException(Code::CODE_OTHER_FAIL, '记录日志失败');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 售后操作日志
     *
     * @return array|int
     */
    public function service(array $params)
    {
        try {
            if (empty($params['service_logs_images'])) unset($params['service_logs_images']);
            if (empty($params['service_logs_remark'])) unset($params['service_logs_remark']);
            $orders_service_logs_model  = new OrdersServiceLogs();
            $orders_service_logs_model->data($params);
            if (false == $orders_service_logs_model->save())
                throw new ResponseException(Code::CODE_OTHER_FAIL, '记录日志失败');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}