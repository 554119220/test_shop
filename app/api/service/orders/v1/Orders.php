<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15 0015
 * Time: 11:43
 */

namespace app\api\service\orders\v1;


use app\api\model\orders\OrdersShop;
use app\common\traits\F;
use mercury\async\Beanstalkd;
use mercury\constants\Code;
use mercury\constants\NoPre;
use mercury\constants\State;
use mercury\factory\Factory;
use mercury\ResponseException;
use think\Db;

class Orders
{
    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key, $is_seller;

    /**
     * @title 订单列表
     *
     * @param int $user_id
     * @param string $orders_shop_no
     * @param string $sday
     * @param string $eday
     * @param int $state
     * @return array
     */
    public function index()
    {
        //buyer,seller
        try {

            #   角色
            $map    = [
                $this->user_key => request()->user['user_id'],
            ];

            #   状态
            if (request()->has('state') && array_key_exists(request()->param('state'), State::STATE_ORDERS_ARRAY)) {
                $map['orders_shop_state']   = request()->param('state');
            }

            if (isset(request()->data['between_time']) && !empty(request()->data['between_time'])) {
                $between_time   = 'orders_shop_' . request()->data['between_time'];
            } else {
                $between_time   = 'orders_shop_create_time';
            }

            if (isset(request()->data['buyer_user']) && !empty(request()->data['buyer_user']) && $this->is_seller) {
                $map['buyer_user_id']   = db('user')->where(['user_username' => request()->data['buyer_user']])->cache(true)->value('user_id') ? : '';
            }

            #   开始时间
            if (request()->has('sday') && !empty(request()->data['sday'])) {
                $timestamp1  = strtotime(request()->param('sday'));
                if ($timestamp1) $map[$between_time]  = ['egt', $timestamp1];
            }

            #   结束时间
            if (request()->has('eday') && !empty(request()->data['eday'])) {
                $timestamp2  = strtotime(request()->param('eday'));
                if ($timestamp2) $map[$between_time]  = ['elt', $timestamp2];
            }

            #   时间区间
            if (isset($timestamp1) && $timestamp1 && isset($timestamp2) && $timestamp2) {
                $map[$between_time]  = ['between', "{$timestamp1},{$timestamp2}"];
            }

            #   通过订单号搜索
            if (request()->has('shop_no') && !empty(request()->data['shop_no'])) {
                $map['orders_shop_no']   = request()->param('shop_no');
            }

            #   金额搜索
            #   初始金额
            if (request()->has('s_amount') && !empty(request()->data['s_amount'])) {
                $s_amount   = intval(request()->data['s_amount']);
                if ($s_amount >= 0) $map['orders_shop_amount']  = ['egt', $s_amount];
            }
            #   结束金额
            if (request()->has('e_amount') && !empty(request()->data['e_amount'])) {
                $e_amount   = intval(request()->data['e_amount']);
                if ($e_amount > 0) $map['orders_shop_amount']  = ['elt', $e_amount];
            }
            #   金额区间
            if (isset($s_amount) && $s_amount && isset($e_amount) && $e_amount) {
                $map['orders_shop_amount']   = ['between', "{$s_amount},{$e_amount}"];
            }
            $data   = F::pageList(\app\api\model\orders\OrdersShop::class, [
                'where' => $map,
                'field' => true,
                'page'  => request()->param('page', 1),
                'order' => 'orders_shop_create_time desc',
                'relation'  => 'Goods,ordersAddress',
            ]);
            #   是否无数据
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            foreach ($data['data'] as &$v) {
                $shop   = Factory::instance('/goods/v1/shop/detail', false)->run(['shop_id' => $v['shop_id']]);
                if ($shop['code'] != Code::CODE_SUCCESS) throw new ResponseException(Code::CODE_OTHER_FAIL, '商家不存在');
                $v['shop']  = $shop['data'];
                #   获取用户信息
                $user   = Factory::instance('/user/v1/info/index', false)->run(['user_id' => $v['buyer_user_id']]);
                if ($user['code'] != Code::CODE_SUCCESS) throw new ResponseException($user['code'], $user['msg']);
                $v['user']   = $user['data'];
                if ($this->is_seller) {
                    $v['is_refund']     = db('orders_refund')->where([
                        'orders_shop_id' => $v['orders_shop_id'],
                        'orders_refund_state' => ['not in', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS]]])
                        ->value('orders_refund_id') ? State::STATE_NORMAL : State::STATE_DISABLED;
                    $v['is_service']    = db('orders_service')->where([
                        'orders_shop_id' => $v['orders_shop_id'],
                        'orders_service_state' => ['not in', [State::STATE_SERVICE_CANCEL, State::STATE_SERVICE_SUCCESS]]])
                        ->value('orders_service_id') ? State::STATE_NORMAL : State::STATE_DISABLED;
                }
            }
            return $data;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 关闭订单
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return array|int
     */
    public function close()
    {
        //buyer,seller
        try {
            //关闭订单，
            //记录订单日志
            Db::startTrans();
            if (!isset(request()->data['remark']) || empty(request()->data['remark']))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '取消原因不能为空');
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_state' => State::STATE_ORDERS_NORMAL,
                'orders_shop_no'    => request()->data['shop_no'],
                'orders_shop_is_freeze' => State::STATE_DISABLED,
            ];
            $model  = new OrdersShop();
            $orders = $model->relation('goods')->where($map)->find();
            if (false == $orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在或不可关闭');
            $orders = $orders->toArray();
            $data   = [
                'orders_shop_state'      => State::STATE_ORDERS_NORMAL_CLOSE,
                'orders_shop_close_time' => time(),
                'orders_shop_close_user' => request()->user['user_id']
            ];

            $flag   = $model->update($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单关闭失败，请稍后重试!');

            #   为商品增加库存 仅在拍下件库存的时候启用
            $cps_spm        = false;
            /*
            $table_prefix   = config('database.prefix');
            foreach ($orders['goods'] as $v) {
                $sql    = "UPDATE `{$table_prefix}goods_sku` SET `goods_sku_sale_num` = goods_sku_sale_num - {$v['orders_goods_num']}, 
`goods_sku_num` = goods_sku_num + {$v['orders_goods_num']} 
WHERE `goods_sku_id` = {$v['goods_sku_id']}";
                $flag   = db()->execute($sql);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新库存失败');
                if (!empty($v['cps_spm'])) $cps_spm = true;
            }
*/
            #   记录日志
            $logs   = [
                'orders_logs_title'     => $this->is_seller ? '商家关闭订单' : '买家关闭订单',
                'orders_shop_id'        => $orders['orders_shop_id'],
                'orders_shop_state'     => $data['orders_shop_state'],
                'orders_shop_no'        => $orders['orders_shop_no'],
                'orders_logs_is_display'=> State::STATE_DISABLED,
                'orders_logs_remark'    => request()->data['remark'],
            ];
            $ret    = Logs::instance()->orders($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);

            #   通知百望客
            if ($cps_spm) {
                $beanstalk  = new Beanstalkd('ordersUpdate');
                $beanstalk->put(['id' => $orders['orders_shop_id']], 1024, 30);
            }
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }


    /**
     * @title 订单详情
     *
     * @param int $user_id
     * @param string $orders_shop_no
     * @return array
     */
    public function detail()
    {
        //seller,buyer
        try {
            //获取订单详情
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_no'=> request()->data['shop_no']
            ];
            $model  = new OrdersShop();
            $data   = $model->relation('goods,ordersAddress,logs,expressCompany')->where($map)->find();
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data->toArray();
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
     * @title 数据统计
     *
     * @param int $user_id
     * @return array|int
     */
    public function total()
    {
        try {
            $map    = [
                $this->user_key => request()->user['user_id'],
            ];
            $user_id= request()->user['user_id'];
            $prefix = config('database.prefix');
            $wait_pay   = State::STATE_ORDERS_NORMAL;
            $wait_ship  = State::STATE_ORDERS_PAY;
            $wait_receive   = State::STATE_ORDERS_SHIP;
            $wait_comment   = State::STATE_ORDERS_RECEIVE;
            $sql    = "SELECT SUM(IF(orders_shop_state = {$wait_pay}, 1, 0)) AS wait_pay,
SUM(IF(orders_shop_state = {$wait_ship}, 1, 0)) AS wait_ship,
SUM(IF(orders_shop_state = {$wait_receive}, 1, 0)) AS wait_receive,
SUM(IF(orders_shop_state = {$wait_comment}, 1, 0)) AS wait_comment
 FROM `{$prefix}orders_shop` WHERE `{$this->user_key}` = {$user_id}";
            $data   = Db::query($sql);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data[0];
            $data['wait_ship']      = $data['wait_ship'] ? : 0;
            $data['wait_pay']       = $data['wait_pay'] ? : 0;
            $data['wait_receive']   = $data['wait_receive'] ? : 0;
            $data['wait_comment']   = $data['wait_comment'] ? : 0;
            $data['refund'] = db('orders_refund')->where($map)->where(function ($query) {
                $query->where('orders_refund_state', '<>', State::STATE_REFUNDS_SUCCESS)
                    ->where('orders_refund_state', '<>', State::STATE_REFUNDS_CANCEL);
            })->count();
            $data['service']= db('orders_service')->where($map)->where(function ($query) {
                $query->where('orders_service_state', '<>', State::STATE_SERVICE_SUCCESS)
                    ->where('orders_service_state', '<>', State::STATE_SERVICE_CANCEL);
            })->count();
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 订单状态
     *
     * @param string $shop_no
     * @return array|int
     */
    public function state()
    {
        try {
            $key    = 'orders_shop_no';
            $no     = request()->data['shop_no'];
            if (strpos($no, NoPre::NO_PRE_BY_SHOP_ORDERS) !== 0) {
                $key    = 'orders_no';
            }
            $map    = [
                $key    => $no,
                'orders_shop_state' => State::STATE_ORDERS_PAY
            ];
            $model  = new OrdersShop();
            $data   = $model->where($map)->value('orders_shop_id');
            if (!$data) throw new ResponseException(Code::CODE_OTHER_FAIL, '未付款');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}