<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:24
 */

namespace app\api\service\orders\v1;


use app\api\model\orders\OrdersGoods;
use app\api\model\orders\OrdersService;
use app\common\traits\F;
use lbzy\sdk\erp\ErpOauth;
use mercury\async\Beanstalkd;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\factory\Factory;
use mercury\ResponseException;
use think\Db;

class Service
{

    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key, $is_seller;

    /**
     * @title 售后列表
     *
     * @param int $user_id
     * @param string $service_no
     * @param string $orders_shop_no
     * @param string $sday
     * @param string $eday
     * @param int $state
     * @return array
     */
    public function index()
    {
        //buyer/seller
        try {
            //根据条件获取数据列表

            #   角色
            $map    = [
                $this->user_key => request()->user['user_id'],
            ];

            #   状态
            if (request()->has('state')) {
                $state  = request()->param('state');
                switch ($state) {
                    case 'seller' :
                        $state  = ['in', [State::STATE_SERVICE_BUYER_EXPRESS, State::STATE_SERVICE_NORMAL]];
                        break;
                    case 'appeal':
                        $state  = State::STATE_SERVICE_BUYER_APPEAL;
                        break;
                    case 'buyer' :
                        $state  = ['in', [State::STATE_SERVICE_AGREE, State::STATE_SERVICE_SELLER_REFUSE, State::STATE_SERVICE_SELLER_EXPRESS]];
                        break;
                }
                $map['orders_service_state']   = $state;
            }

            #   开始时间
            if (request()->has('sday') && !empty(request()->data['sday'])) {
                $timestamp1  = strtotime(request()->param('sday'));
                if ($timestamp1) $map['orders_service_create_time']  = ['egt', $timestamp1];
            }

            #   结束时间
            if (request()->has('eday') && !empty(request()->data['eday'])) {
                $timestamp2  = strtotime(request()->param('eday'));
                if ($timestamp2) $map['orders_service_create_time']  = ['elt', $timestamp2];
            }

            #   时间区间
            if (isset($timestamp1) && $timestamp1 && isset($timestamp2) && $timestamp2) {
                $map['orders_service_create_time']  = ['between', "{$timestamp1},{$timestamp2}"];
            }

            #   通过订单号搜索
            if (request()->has('shop_no') && !empty(request()->data['shop_no'])) {
                $map['orders_shop_no'] = request()->param('shop_no');
            }

            #   通过售后单号搜索
            if (request()->has('service_no') && !empty(request()->data['service_no'])) {
                $map['orders_service_no']   = request()->param('service_no');
            }

            $model  = new OrdersService();
            $data   = F::pageList($model, [
                'where'     => $map,
                'relation'  => 'OrdersGoods',
                'page'      => request()->param('page', 1),
                'order'     => 'orders_service_id desc'
            ]);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            foreach ($data['data'] as &$v) {
                $shop   = Factory::instance('/goods/v1/shop/detail', false)->run(['shop_id' => $v['shop_id']]);
                if ($shop['code'] != Code::CODE_SUCCESS) throw new ResponseException(Code::CODE_OTHER_FAIL, '商家不存在');
                $v['shop']  = $shop['data'];
                #   获取用户信息
                $user   = Factory::instance('/user/v1/info/index', false)->run(['user_id' => $v['buyer_user_id']]);
                if ($user['code'] != Code::CODE_SUCCESS) throw new ResponseException($user['code'], $user['msg']);
                $v['user']   = $user['data'];
            }
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 售后详情
     *
     * @param int $user_id
     * @param string $service_no
     * @return array
     */
    public function detail()
    {
        //seller，buyer
        try {
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_service_no' => request()->data['service_no']
            ];

            $model  = new OrdersService();
            $data   = F::dataDetail($model, [
                'where'     => $map,
                'relation'  => 'OrdersGoods,OrdersServiceLogs,OrdersServiceAddress'
            ]);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            #   获取商家信息
            $shop   = Factory::instance('/goods/v1/shop/detail', false)->run(['shop_id' => $data['shop_id']]);
            if ($shop['code'] != Code::CODE_SUCCESS) throw new ResponseException(Code::CODE_OTHER_FAIL, '商家不存在');
            #   获取用户信息
            $user   = Factory::instance('/user/v1/info/index', false)->run(['user_id' => $data['buyer_user_id']]);
            if ($user['code'] != Code::CODE_SUCCESS) throw new ResponseException($user['code'], $user['msg']);
            $data['user']   = $user['data'];
            $data['shop']   = $shop['data'];
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 发起申诉
     *
     * @param int $user_id
     * @param string $service_no
     * @param string $remark
     * @return array|string
     */
    public function appeal()
    {
        try {
            //商家只能在买家发货但是收不到货的情况下才能发起申诉
            //买家只能在商家拒绝售后服务,商家长时间不邮寄商品,商家已发货但是未收到商品的情况下才能申请售后
            Db::startTrans();
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '申诉原因不能为空');
            if (true === $this->is_seller) {
                $in     = State::STATE_SERVICE_BUYER_EXPRESS;
                $title  = '商家提起申诉';
                $state  = State::STATE_SERVICE_SELLER_APPEAL;
            } else {
                $in     = ['in',
                    [
                        State::STATE_SERVICE_SELLER_REFUSE,
                        State::STATE_SERVICE_SELLER_EXPRESS,
                        State::STATE_SERVICE_SELLER_RECEIVE
                    ]
                ];
                $title  = '买家提起申诉';
                $state  = State::STATE_SERVICE_BUYER_APPEAL;
            }

            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_service_state'  => $in,
                'orders_service_no'     => request()->data['service_no'],
            ];
            $model  = new OrdersService();
            $service= F::dataDetail($model, [
                'where' => $map,
            ]);

            if (!$service) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在货已完成');
            $data   = [
                'orders_service_state'  => $state,
            ];

            if (false == $model->save($data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '提交申诉失败，请稍后尝试！');

            $logs   = [
                'service_logs_title' => $title,
                'orders_service_id'  => $service['orders_service_id'],
                'service_state'      => $data['orders_service_state'],
                'service_no'         => $service['orders_service_no'],
                'service_logs_images'=> request()->data['images'],
                'service_logs_remark'=> request()->data['remark']
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
     * @title 邮寄商品
     * 商家邮寄商品/买家邮寄商品
     *
     * @param int $user_id
     * @param string $service_no
     * @param string $remark
     * @param string $images
     * @param int $company_id
     * @param string $express_no
     * @return array|string
     */
    public function express()
    {
        //seller
        try {
            //更改售后状态
            //记录售后日志

            Db::startTrans();
            if (true === $this->is_seller) {
                $in     = State::STATE_SERVICE_SELLER_RECEIVE;
                $title  = '商家邮寄商品';
                $state  = State::STATE_SERVICE_SELLER_EXPRESS;
            } else {
                $in     = State::STATE_SERVICE_AGREE;
                $title  = '买家邮寄商品';
                $state  = State::STATE_SERVICE_BUYER_EXPRESS;
            }


            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_service_state'  => $in,
                'orders_service_no'     => request()->data['service_no'],
            ];

            $model  = new OrdersService();
            $service= F::dataDetail($model, [
                'where' => $map,
            ]);

            if (!$service) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在或不允许邮寄商品！');

            #   如果是买家则需要添加收货地址
            if (!$this->is_seller) {
                #   添加收货地址
                $address= [
                    'orders_service_id' => $service['orders_service_id'],
                    'orders_service_no' => $service['orders_service_no'],
                    'address_id'        => request()->data['address_id'],
                    'orders_service_is_seller'  => State::STATE_DISABLED,
                    'user_id'           => request()->user['user_id'],
//                    'orders_service_express_remark' =>
                ];
                if (request()->has('express_remark')) $address['orders_service_express_remark'] = request()->data['express_remark'];
                $ret    = Address::instance()->service($address);
                if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);
            }

            $data   = [
                'orders_service_state'  => $state,
                'orders_service_next_time'  => $this->is_seller ?
                    Times::times(Times::TIME_SERVICE_BUYER_RECEIVE) :
                    Times::times(Times::TIME_SERVICE_SELLER_RECEIVE)
            ];

            if (false == $model->save($data, $map)) {
                throw new ResponseException(Code::CODE_OTHER_FAIL, '邮寄商品失败!');
            }

            #   邮寄数据
            $express= [
                'orders_service_id' => $service['orders_service_id'],
                'orders_service_no' => $service['orders_service_no'],
                'express_company_id'=> request()->data['company_id'],
                'express_no'        => request()->data['express_no'],
                'orders_service_is_seller'      => $this->is_seller ? State::STATE_DISABLED : State::STATE_NORMAL,
                'express_remark'    => request()->param('remark', ''),
            ];
            $ret    = Express::instance()->service($express);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            #   记录操作日志
            $logs   = [
                'service_logs_title' => $title,
                'orders_service_id'  => $service['orders_service_id'],
                'service_state'      => $data['orders_service_state'],
                'service_no'         => $service['orders_service_no'],
                'service_logs_images'=> request()->data['images'],
                'service_logs_remark'=> request()->data['remark']
            ];

            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            if ($this->is_seller) {
                #   入列,买家收货
                Beanstalkd::getInstance('service_buyer_receive')
                    ->ordersPut(
                        $service['orders_service_id'],
                        $service['orders_service_no'],
                        Times::times(Times::TIME_SERVICE_SELLER_RECEIVE, true));
            } else {
#               #   入列，商家收货
                Beanstalkd::getInstance('service_seller_receive')
                    ->ordersPut(
                        $service['orders_service_id'],
                        $service['orders_service_no'],
                        Times::times(Times::TIME_SERVICE_BUYER_RECEIVE, true));
            }

            Db::commit();
            return ['orders_service_no' => $service['orders_service_no']];
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 确认收货
     * 商家确认收货/买家确认收货
     *
     * @param int $user_id
     * @param string $service_no
     * @param string $pay_password
     * @return array|string
     */
    public function receive()
    {
        //seller
        try {
            //更改售后状态
            //记录售后日志
            Db::startTrans();
            //$isAuth = ErpOauth::instance()->checkPayPassword(['openid' => request()->user['openid'], 'safe_psw' => request()->data['pay_password']]);
            //if (true !== $isAuth) throw new ResponseException(Code::CODE_OTHER_FAIL, $isAuth);
            if (true === $this->is_seller) {
                $in     = State::STATE_SERVICE_BUYER_EXPRESS;
                $title  = '商家确认收货';
                $state  = State::STATE_SERVICE_SELLER_RECEIVE;
                #   减数
            } else {
                $in     = State::STATE_SERVICE_SELLER_EXPRESS;
                $title  = '买家确认收货';
                $state  = State::STATE_SERVICE_SUCCESS;
            }

            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_service_state'  => $in,
                'orders_service_no'     => request()->data['service_no'],
            ];
            $model  = new OrdersService();
            $service= F::dataDetail($model, [
                'where' => $map
            ]);
            if (!$service) throw new ResponseException(Code::CODE_OTHER_FAIL, '售后不存在或已完成');

            $data   = [
                'orders_service_state'  => $state,
                'orders_service_next_time'  => Times::times(Times::TIME_SERVICE_BUYER_RECEIVE)
            ];

            if (false == $model->save($data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '确认收货失败，请稍后重试！');

            #   买家确认收货
            $table_prefix   = config('database.prefix');
            if (!$this->is_seller) {
//                $flag   = db('orders_goods')->where(['orders_goods_id' => $service['orders_goods_id']])
//                    ->dec('goods_service_num', $service['orders_service_num']);
                $ordersGoods    = new OrdersGoods();
                //$flag   = $ordersGoods->where('orders_goods_id', $service['orders_goods_id'])->dec('goods_service_num', $service['orders_service_num']);
                $sql    = "UPDATE `{$table_prefix}orders_goods` SET `goods_service_num` = goods_service_num - {$service['orders_service_num']} 
WHERE `orders_goods_id` = {$service['orders_goods_id']}";
                $flag   = $ordersGoods->execute($sql);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新订单商品失败');
            }
//            throw new ResponseException(Code::CODE_OTHER_FAIL, $service['orders_service_num']);

            #   记录操作日志
            $logs   = [
                'service_logs_title' => $title,
                'orders_service_id'  => $service['orders_service_id'],
                'service_state'      => $data['orders_service_state'],
                'service_no'         => $service['orders_service_no'],
                'service_logs_images'=> request()->data['images'] ?? '',
                'service_logs_remark'=> request()->data['remark'] ?? ''
            ];

            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }
}