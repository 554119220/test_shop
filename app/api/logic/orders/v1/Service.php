<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 18:59
 */

namespace app\api\logic\orders\v1;

use mercury\constants\Code;
use mercury\ResponseException;
use traits\think\Instance;

/**
 * Class Service
 * @package app\api\logic\orders\v1
 *
 * 售后服务
 */
class Service
{
    use Instance;

    /**
     * 售后数量检测
     *
     * @param array $params
     * @param int $goods_id
     * @param string $orders_shop_no
     * @param int $num
     * @return array|int
     */
    public function create(array $params)
    {
        try {
            $map    = [
                'orders_goods_id'   => $params['goods_id'],
                'orders_shop_no'    => $params['orders_shop_no'],
            ];
            $data   = db('orders_goods')->where($map)->find();
            if (!$data) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在！');

            if ($data['goods_service_last_day'] < time())
                throw new ResponseException(Code::CODE_OTHER_FAIL, '售后期限已过！');

            if ($data['service_num'] < $params['num'])
                throw new ResponseException(Code::CODE_OTHER_FAIL, "只能申请 {$data['service_num']} 件");

            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}