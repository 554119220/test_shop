<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:31
 */

namespace app\api\controller\orders\v1;


use app\api\model\orders\OrdersService;
use app\api\model\orders\OrdersShop;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use mercury\async\Beanstalkd;
use mercury\constants\Code;
use mercury\constants\NoPre;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\ResponseException;
use think\Db;

/**
 * Class BuyerService
 * @package app\api\controller\orders\v1
 *
 * @title 买家售后
 */
class BuyerService extends \app\api\service\orders\v1\Service
{
    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key = 'buyer_user_id', $is_seller = false;

    /**
     * @title 申请售后
     *
     * @param int $user_id
     * @param int $orders_goods_id
     * @param string $orders_shop_no
     * @param int $goods_num
     * @param string $remark
     * @param string $images
     * @return array|string
     */
    public function create()
    {
        //buyer
        try {
            //创建售后申请
            //记录售后日志
            Db::startTrans();
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后原因不能为空');
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_no'=> request()->data['shop_no'],
                'orders_shop_state' => ['in', [State::STATE_ORDERS_RECEIVE, State::STATE_ORDERS_COMMIT]]
            ];

            $model  = new OrdersShop();
            $orders = $model->where($map)->relation('serviceApply')->find();
            if (!$orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在');
            $orders = $orders->toArray();

            #   判断售后数量
            $serviceModel   = new OrdersService();
            $service_num    = $serviceModel->sumOldService(request()->data['shop_no'], $orders['serviceApply']['orders_goods_id']);

            $orders_goods_service_num   = $orders['serviceApply']['goods_service_num'];
            $can_service_num            = $orders_goods_service_num - $service_num;
            if (request()->data['num'] > $can_service_num)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "最多可申请{$can_service_num}件");

            /*
            #   最多可申请件数判断
            $max_num_map    = [
                'orders_shop_no'    => request()->data['shop_no'],
                $this->user_key     => request()->user['user_id'],
                'orders_goods_id'   => request()->data['orders_goods_id'],
                'orders_service_state'  => ['not in', [State::STATE_SERVICE_SUCCESS, State::STATE_SERVICE_CANCEL]]
            ];
            $max_num    = db('orders_service')->where($max_num_map)->value('SUM(orders_service_num) as num');
            if ($orders['serviceApply']['goods_service_num'] - ($max_num + request()->data['num'])  < 0) throw new ResponseException(Code::CODE_OTHER_FAIL, "最多可申请{$orders['serviceApply']['goods_service_num']}件");
*/
            $data   = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_no'=> $orders['orders_shop_no'],
                'orders_shop_id'=> $orders['orders_shop_id'],
                'shop_id'       => $orders['shop_id'],
                'seller_user_id'=> $orders['seller_user_id'],
                'orders_service_no'     => F::createNo(NoPre::NO_PRE_BY_SERVICE_ORDERS),
                //'goods_sku_id'  => request()->data['goods_sku_id'],
                'orders_service_num'    => request()->data['num'],
                'orders_service_state'  => State::STATE_SERVICE_NORMAL,
                'orders_goods_id'       => request()->data['goods_id'],
                'orders_service_next_time'  => Times::times(Times::TIME_SERVICE_AGREE)
            ];
            $model_service  = new OrdersService();
            $insert_id  = $model_service->save($data);
            if (false == $insert_id)
                throw new ResponseException(Code::CODE_OTHER_FAIL, '提交售后申请失败，请稍后再尝试');

            $insert_id  = $model_service->getLastInsID();

            #   记录日志
            $logs   = [
                'service_logs_title' => '买家申请售后服务',
                'orders_service_id'  => $insert_id,
                'service_state'      => $data['orders_service_state'],
                'service_no'         => $data['orders_service_no'],
                'service_logs_remark'=> request()->data['remark'],
                'service_logs_images'=> request()->data['images'],
            ];
            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            #   入列
            Beanstalkd::getInstance('service_agree')
                ->ordersPut($insert_id, $data['orders_service_no'], Times::times(Times::TIME_SERVICE_AGREE, true));

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 修改售后
     *
     * @param string $service_no
     * @param int $num
     * @param string $remark
     * @param string $images
     * @return array|string
     */
    public function modify()
    {
        //buyer
        try {
            //创建售后申请
            //记录售后日志
            Db::startTrans();
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后原因不能为空');
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_service_state'  => State::STATE_SERVICE_SELLER_REFUSE,
                'orders_service_no'     => request()->data['service_no'],
            ];
            $model  = new OrdersService();
            $service= $model->relation('OrdersGoods,ordersShop')->where($map)->find();
            if (!$service || !$service->OrdersGoods) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在或已完成');
            $service= $service->toArray();
            $service_num    = $model->sumOldService($service['orders_shop_no'], $service['OrdersGoods']['orders_goods_id'], $service['orders_service_no']);
            $orders_goods_service_num   = $service['OrdersGoods']['goods_service_num'];
            $can_service_goods_num      = $orders_goods_service_num - $service_num;
            if (request()->data['num'] > $can_service_goods_num)
                throw new ResponseException(Code::CODE_OTHER_FAIL, "当前最多可申请 {$can_service_goods_num} 件");

            $data   = [
                'orders_service_num'    => request()->data['num'],
                'orders_service_state'  => State::STATE_SERVICE_NORMAL,
                'orders_service_next_time'  => Times::times(Times::TIME_SERVICE_AGREE)
            ];

            if (false == $model->save($data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '修改售后申请失败');

            #   记录日志
            $logs   = [
                'service_logs_title'    => '买家修改售后申请',
                'orders_service_id'     => $service['orders_service_id'],
                'service_state'         => $data['orders_service_state'],
                'service_no'            => $service['orders_service_no'],
                'service_logs_images'   => request()->data['images'],
                'service_logs_remark'   => request()->data['remark'],
            ];
            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            #   入列
            Beanstalkd::getInstance('service_agree')
                ->ordersPut($service['orders_service_id'], $service['orders_service_no'], Times::times(Times::TIME_SERVICE_AGREE, true));

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 取消售后
     *
     * @param int $user_id
     * @param string $service_no
     * @param string $remark
     * @return array|int
     */
    public function cancel()
    {
        try {
            Db::startTrans();
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_service_state'  => ['in',
                    [
                        State::STATE_SERVICE_NORMAL,
                        State::STATE_SERVICE_SELLER_REFUSE,
                        State::STATE_SERVICE_AGREE,
//                        State::STATE_SERVICE_SELLER_RECEIVE,
//                        State::STATE_SERVICE_BUYER_EXPRESS
                    ]
                ],
                'orders_service_no'     => request()->data['service_no']
            ];

            $model  = new OrdersService();
            $service= F::dataDetail($model, [
                'where' => $map
            ]);
            if (!$service) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在或已完成');

            $data   = [
                'orders_service_state'  => State::STATE_SERVICE_CANCEL
            ];

            if (false == $model->save($data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '操作取消售后失败，请稍后再试');


            #   减数量
//            if (false == db('orders_goods')->where(
//                ['orders_shop_no' => request()->param('shop_no'), 'orders_goods_id' => request()->param('goods_id')]
//                )->setDec('goods_service_num', request()->param('num'))) {
//                throw new ResponseException(Code::CODE_OTHER_FAIL, '减去数量失败');
//            }


            #   记录日志
            $logs   = [
                'service_logs_title'    => '买家取消售后服务',
                'orders_service_id'     => $service['orders_service_id'],
                'service_state'         => $data['orders_service_state'],
                'service_no'            => $service['orders_service_no'],
                //'service_logs_images'   => request()->data['images'],
                'service_logs_remark'   => request()->data['remark'],
            ];
            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 修改售后
     *
     * @return array
     */
    public function edit()
    {
        try {
            $map    = [
                $this->user_key     => request()->user['user_id'],
                'orders_service_no' => request()->data['service_no'],
            ];
            $model  = new OrdersService();
            $service= $model->relation('OrdersGoods,ordersShop')->where($map)->find();
            if (!$service || !$service->OrdersGoods) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在或已完成');
            $service= $service->toArray();
            $service_num    = $model->sumOldService($service['orders_shop_no'], $service['OrdersGoods']['orders_goods_id'], $service['orders_service_no']);
            $service['OrdersGoods']['goods_service_num']    -= $service_num;
            return $service;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}