<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 17:30
 */

namespace app\api\service\orders\v1;
use mercury\constants\Code;
use mercury\ResponseException;
use traits\think\Instance;

/**
 * Class Express
 * @package app\api\service\orders\v1
 *
 * 发货
 */
class Express
{
    use Instance;
    /**
     * 订单发货
     *
     * @param $orders_shop_id
     * @return array|int
     */
    public function orders()
    {
        try {

            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 退款发货
     *
     * @param int $orders_refund_id
     * @param int $orders_refund_no
     * @param int $express_company_id
     * @param string $express_no
     * @return array|int
     */
    public function refund(array $params)
    {
        try {
            $map    = [
                'orders_refund_id'  => $params['orders_refund_id'],
                'orders_refund_no'  => $params['orders_refund_no']
            ];

            $data   = [
                'express_company_id'    => $params['express_company_id'],
                'express_no'            => $params['express_no'],
                'orders_refund_time'    => time(),
                'orders_refund_remark'  => $params['refund_remark'],
            ];

            if (false == db('orders_refund_address')->where($map)->update($data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '邮寄商品失败');

            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 售后发货
     *
     * @param int $orders_service_id
     * @param string $orders_service_no
     * @param integer $orders_service_is_seller
     * @param int $express_company_id
     * @param string $express_no
     * @return array|int
     */
    public function service(array $params)
    {
        try {
            $map    = [
                'orders_service_id' => $params['orders_service_id'],
                'orders_service_no' => $params['orders_service_no'],
                'orders_service_is_seller'  => $params['orders_service_is_seller'],
            ];

            $data   = [
                'express_company_id'    => $params['express_company_id'],
                'express_no'            => $params['express_no'],
                'orders_service_express_time'   => time(),
                'orders_service_express_remark' => $params['express_remark']
            ];

            if (false == db('orders_service_address')->where($map)->update($data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '邮寄商品失败!');

            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}