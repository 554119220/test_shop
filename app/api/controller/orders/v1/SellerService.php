<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:32
 */

namespace app\api\controller\orders\v1;


use app\api\model\orders\OrdersService;
use app\api\service\orders\v1\Address;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use mercury\async\Beanstalkd;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\ResponseException;
use think\Db;

/**
 * Class SellerService
 * @package app\api\controller\orders\v1
 *
 * @title 商家售后
 */
class SellerService extends \app\api\service\orders\v1\Service
{
    /**
     * @var string $user_key 用户角色
     * @var bool $is_seller 是否是商家
     */
    protected $user_key = 'seller_user_id', $is_seller = true;

    /**
     * @title 拒绝售后
     *
     * @param int $user_id
     * @param string $service_no
     * @param string $remark
     * @param string $images
     * @return array|string
     */
    public function refuse()
    {
        //seller
        try {
            //更改售后状态
            //记录售后日志
            Db::startTrans();
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '拒绝原因不能为空');
            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_service_state'  => State::STATE_SERVICE_NORMAL,
                'orders_service_no'     => request()->data['service_no']
            ];
            $model  = new OrdersService();
            $service= F::dataDetail($model, ['where' => $map]);
            if (!$service) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在货已完成');
            $data   = [
                'orders_service_state'  => State::STATE_SERVICE_SELLER_REFUSE,
                'orders_service_next_time'  => Times::times(Times::TIME_SERVICE_CANCEL)
            ];

            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '操作售后失败');

            #   记录日志
            $logs   = [
                'service_logs_title' => '商家拒绝售后服务',
                'orders_service_id'  => $service['orders_service_id'],
                'service_state'      => $data['orders_service_state'],
                'service_no'         => $service['orders_service_no'],
                'service_logs_images'=> request()->data['images'],
                'service_logs_remark'=> request()->data['remark']
            ];
            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            #   入列
            Beanstalkd::getInstance('service_cancel')
                ->ordersPut($service['orders_service_id'], $service['orders_service_no'], Times::times(Times::TIME_SERVICE_CANCEL, true));

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }


    /**
     * @title 同意售后
     *
     * @param int $user_id
     * @param string $service_no
     * @param int $address_id
     * @param string $remark
     * @return array|string
     */
    public function agree()
    {
        //seller
        try {
            //商家收货地址
            //更改售后状态
            //记录售后日志
            Db::startTrans();
            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_service_state'  => State::STATE_SERVICE_NORMAL,
                'orders_service_no'     => request()->data['service_no']
            ];

            $model  = new OrdersService();
            $service= F::dataDetail($model, ['where' => $map]);
            if (!$service) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在或已完成');

            $data   = [
                'orders_service_state'  => State::STATE_SERVICE_AGREE,
                'orders_service_next_time'  => Times::times(Times::TIME_SERVICE_CANCEL)
            ];

            $flag   = $model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新售后状态失败');

            #   收货地址
            $address= [
                'orders_service_id' => $service['orders_service_id'],
                'orders_service_no' => $service['orders_service_no'],
                'address_id'        => request()->data['address_id'],
                'orders_service_is_seller'  => State::STATE_NORMAL,
                'user_id'           => request()->user['user_id']
            ];

            $ret    = Address::instance()->service($address);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['smg']);

            #   记录日志
            $logs   = [
                'service_logs_title' => '商家同意售后服务',
                'orders_service_id'  => $service['orders_service_id'],
                'service_state'      => $data['orders_service_state'],
                'service_no'         => $service['orders_service_no'],
                'service_logs_images'=> request()->data['images'],
                'service_logs_remark'=> request()->data['remark'],
            ];
            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['smg']);
            //确认收货地址

            #   入列
            Beanstalkd::getInstance('service_cancel')
                ->ordersPut($service['orders_service_id'], $service['orders_service_no'], Times::times(Times::TIME_SERVICE_CANCEL, true));

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }
}