<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:43
 */

namespace app\api\service\orders\v1;


use app\api\model\orders\OrdersRefund;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\factory\Factory;
use mercury\ResponseException;
use think\Db;

class Refund
{

    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key, $is_seller;


    /**
     * @title 退款列表
     *
     * @param int $user_id,buyer_user_id,seller_user_id
     * @param string $refund_no
     * @param string $orders_shop_no
     * @param string $sday
     * @param string $eday
     * @param int $state
     * @return array
     */
    public function index()
    {
        //seller,buyers
        //buyer,seller
        try {

            #   角色
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_refund_is_display'  => State::STATE_NORMAL
            ];

            #   状态
            if (request()->has('state')) {
                $state  = request()->param('state');
                switch ($state) {
                    case 'seller' :
                        $state  = ['in', [State::STATE_SERVICE_BUYER_EXPRESS, State::STATE_SERVICE_SELLER_RECEIVE, State::STATE_REFUNDS_NORMAL]];
                        break;
                    case 'buyer' :
                        $state  = ['in', [State::STATE_SERVICE_AGREE, State::STATE_SERVICE_SELLER_REFUSE, State::STATE_SERVICE_SELLER_EXPRESS]];
                        break;
                    case 'appeal':
                        $state  = State::STATE_REFUNDS_BUYER_APPEAL;
                        break;
                }
                $map['orders_refund_state']   = $state;
            }
            #   开始时间
            if (request()->has('sday') && !empty(request()->data['sday'])) {
                $timestamp1  = strtotime(request()->param('sday'));
                if ($timestamp1) $map['orders_refund_create_time']  = ['egt', $timestamp1];
            }

            #   结束时间
            if (request()->has('eday') && !empty(request()->data['eday'])) {
                $timestamp2  = strtotime(request()->param('eday'));
                if ($timestamp2) $map['orders_refund_create_time']  = ['elt', $timestamp2];
            }

            #   时间区间
            if (isset($timestamp1) && $timestamp1 && isset($timestamp2) && $timestamp2) {
                $map['orders_refund_create_time']  = ['between', "{$timestamp1},{$timestamp2}"];
            }

            #   通过订单号搜索
            if (request()->has('refund_no') && !empty(request()->data['refund_no'])) {
                $map['orders_refund_no']   = request()->param('refund_no');
            }

            #   通过订单号搜索
            if (request()->has('shop_no') && !empty(request()->data['shop_no'])) {
                $map['orders_shop_no']   = request()->param('shop_no');
            }
            $model  = new OrdersRefund();
            $data   = F::pageList($model, [
                'where'     => $map,
                'relation'  => 'OrdersGoods',
                'order'     => 'orders_refund_create_time desc',
                'page'      => request()->param('page', 1),
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
     * @title 发起申诉
     *
     * @param int $user_id
     * @param string $refund_no
     * @param string $remark
     * @param string $images
     * @return array|string
     */
    public function appeal()
    {
        //seller,buyer
        try {
            //用户提起申诉
            Db::startTrans();
            if (empty(request()->data['remark'])) throw new ResponseException(Code::CODE_OTHER_FAIL, '申诉原因不能为空');
            if (true === $this->is_seller) {
                $title  = '商家提起申诉';
                $in     = State::STATE_REFUNDS_EXPRESS; //买家邮寄商品的情况下
                $state  = State::STATE_REFUNDS_SELLER_APPEAL;
            } else {
                $title  = '买家提起申诉';
                $in     = State::STATE_REFUNDS_REFUSE;  //商家拒绝退款的情况下
                $state  = State::STATE_REFUNDS_BUYER_APPEAL;
            }

            $model  = new OrdersRefund();

            $map    = [
                $this->user_key         => request()->user['user_id'],
                'orders_refund_state'   => $in,
                'orders_refund_no'      => request()->data['refund_no']
            ];

            $refund = F::dataDetail($model, ['where' => $map]);
            if (!$refund) throw new ResponseException(Code::CODE_OTHER_FAIL, '退款不存在或已完成');

            $data   = [
                'orders_refund_state'   => $state,
            ];

            if (false == $model->save($data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '提交申诉失败，请稍后重试');

            #   记录日志
            $logs   = [
                'refund_logs_title' => $title,
                'orders_refund_id'  => $refund['orders_refund_id'],
                'refund_state'      => $data['orders_refund_state'],
                'refund_no'         => $refund['orders_refund_no'],
                'refund_logs_remark'=> request()->data['remark'],
                'refund_logs_images'=> request()->data['images'],
            ];
            $ret    = Logs::instance()->refund($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['msg']);

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }


    /**
     * @title 退款详情
     *
     * @param int $user_id
     * @param string $refund_no
     * @return array
     */
    public function detail()
    {
        //seller,buyer
        try {
            //获取订单详情
            $map    = [
                $this->user_key     => request()->user['user_id'],
                'orders_refund_no'  => request()->data['refund_no']
            ];

            $model  = new OrdersRefund();
            $data   = F::dataDetail($model, [
                'where'     => $map,
                'relation'  => 'OrdersGoods,OrdersRefundLogs,OrdersRefundAddress'
            ]);

            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data['count_refund_amount']    = F::numberBcDiv(
                F::numberNMulti($data['orders_refund_express_amount']) +
                F::numberNMulti($data['orders_refund_amount']));
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


}