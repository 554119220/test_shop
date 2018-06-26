<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\api\service\orders\v1\Address;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use app\work\controller\Commonmodules;
use mercury\async\Beanstalkd;
use mercury\constants\State;
use think\Db;
use think\Exception;

class Ordersservice extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 652;  //表单模板ID
        $this->module_name  = '售后管理';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);
        $btns   = 'return "<a class=\'btn blue btn-outline btn-block md10\' href=\'/ordersservice/edit/orders_service_id/$val[orders_service_id]\'>修改</a>
<div data-id=\'$val[orders_service_id]\' class=\'btn red btn-outline btn-block\' onclick=\'extra_tr_view($(this))\'>详情</div>";';
        $btns   = [$btns];
        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],$btns,1,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);
        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteSelect(){
        $res = $this->_deleteSelect();
        return $res;
    }

    /**
     * 批量设置状态
     */
    public function setStatus(){
        $res = $this->_setStatus();
        return $res;
    }

    /**
     * 修改
     */
    public function edit(){
        $res = $this->_edit();
        return view();
    }

    /**
     * 保存修改
     */
    public function edit_save(){
        $res = $this->_edit_save();
        return $res;
    }

    /**
     * 新增
     */
    public function add(){
        $res = $this->_add();
        return view();
    }
    /**
     * 保存新增
     */
    public function add_save(){
        $res = $this->_add_save();
        return $res;
    }
    /**
     * 转移目录
     */
    public function change2Category(){
        $res = $this->_change2Category();
        return $res;
    }

    /**
     * 退款详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $id     = intval(input('id'));
        $data   = [];
        if ($id > 0) {
            $data   = F::dataDetail(F::apiModel('OrdersService', 'orders'), [
                'where'     => ['orders_service_id' => $id],
                'relation'  => 'OrdersServiceLogs,OrdersServiceAddress,OrdersGoods,ordersShop'
            ]);
        }
        if (!empty($data)) {
            $data['seller'] = F::dataDetail(F::apiModel('User', 'user'), $data['seller_user_id']);
            $data['user'] = F::dataDetail(F::apiModel('User', 'user'), $data['buyer_user_id']);
            $data['shop'] = F::dataDetail(F::apiModel('Shop', 'goods'), $data['shop_id']);
        }
        return view('', ['data' => $data]);
    }

    /**
     * 审判
     *
     * @return array
     */
    public function referee()
    {
        try {
            Db::startTrans();
            $id = intval(input('id'));
            if ($id <= 0) throw new \Exception('售后订单不能为空');
            #   0原判，1卖家胜诉，2商家胜诉
            $referee_result = input('referee_result');
            if ($referee_result == '') throw new \Exception('判决类型不能为空');
            if (empty(input('remark'))) throw new \Exception('判决原因不能为空');
            $result_arr = [0,1,2];
            if (!in_array($referee_result, $result_arr)) throw new \Exception('判决结果错误');

            $service= F::dataDetail(F::apiModel('OrdersService', 'orders'), [
                'where'     => ['orders_service_id' => $id, 'orders_service_state' => ['in', [State::STATE_SERVICE_BUYER_APPEAL, State::STATE_SERVICE_SELLER_APPEAL]]],
                'relation'  => 'OrdersServiceLogs,OrdersServiceAddress,OrdersGoods,ordersShop'
            ]);
            if (!$service) throw new \Exception('售后订单不存在或未申诉');
            #   买家申诉
            $title  = '雇员判决：';
            $state  = '';
            $next_time  = '';
            $times  = config('site.orders');
            if ($service['orders_service_state'] == State::STATE_SERVICE_BUYER_APPEAL) {
                #   商家拒绝售后
                #   商家收到商品长时间不发货
                #   未收到商品

                #   取出上步骤
                array_pop($service['OrdersServiceLogs']);
                $log    = end($service['OrdersServiceLogs']);
                $current_state  = $log['service_state'];
                #   判断执行程序
                switch ($referee_result) {
                    case 0: #   维持原判
                        switch ($current_state) {
                            case State::STATE_SERVICE_SELLER_REFUSE:    #   商家拒绝售后
                                $title  = "{$title}维持原判，商家拒绝售后";
                                $state  = State::STATE_SERVICE_SELLER_REFUSE;
                                $next_time  = time() + $times['time_service_cancel'];
                                $tube       = 'service_cancel';
                                break;
                            case State::STATE_SERVICE_SELLER_RECEIVE:   #   商家长时间不发货
                                $title  = "{$title}维持原判，商家已收货";
                                $state  = State::STATE_SERVICE_SELLER_RECEIVE;
                                break;
                            case State::STATE_SERVICE_SELLER_EXPRESS:   #   未收到商家邮寄的商品
                                $title  = "{$title}维持原判，商家已邮寄商品";
                                $state  = State::STATE_SERVICE_SELLER_EXPRESS;
                                $next_time  = time() + $times['time_service_buyer_receive'];
                                $tube   = 'service_buyer_receive';
                                break;
                        }
                        break;
                    case 1: #   买家胜诉
                        switch ($current_state) {
                            case State::STATE_SERVICE_SELLER_REFUSE:    #   商家拒绝售后
                                $title  = "{$title}买家胜诉，商家同意售后";
                                $state  = State::STATE_SERVICE_AGREE;
                                $next_time  = time() + $times['time_service_cancel'];
                                #   取出商家收货地址
                                #   收货地址
                                $address= [
                                    'orders_service_id' => $service['orders_service_id'],
                                    'orders_service_no' => $service['orders_service_no'],
                                    'address_id'        =>
                                        db('shop_address')->where(['user_id' => $service['seller_user_id']])->order('address_is_default desc')->value('address_id'),
                                    'orders_service_is_seller'  => State::STATE_NORMAL,
                                    'user_id'           => $service['seller_user_id']
                                ];
                                $ret    = Address::instance()->service($address);
                                if (is_array($ret)) throw new \Exception($ret['smg']);
                                $tube   = 'service_cancel';
                                break;
                            case State::STATE_SERVICE_SELLER_RECEIVE:   #   商家长时间不发货
                                $title  = "{$title}买家胜诉，商家已收货";
                                $state  = State::STATE_SERVICE_SELLER_RECEIVE;
                                #   对商家进行扣分处罚
                                break;
                            case State::STATE_SERVICE_SELLER_EXPRESS:   #   未收到商家邮寄的商品
                                $title  = "{$title}商家胜诉，商家已邮寄商品";
                                $state  = State::STATE_SERVICE_SELLER_EXPRESS;
                                #   对商家进行扣分处罚
                                $next_time  = time() + $times['time_service_buyer_receive'];
                                $tube   = 'service_buyer_receive';
                                break;
                        }
                        break;
                    case 2: #   商家胜诉
                        switch ($current_state) {
                            case State::STATE_SERVICE_SELLER_REFUSE:    #   商家拒绝售后
                                $title  = "{$title}商家胜诉，售后取消";
                                $state  = State::STATE_SERVICE_CANCEL;
                                break;
                            case State::STATE_SERVICE_SELLER_RECEIVE:   #   商家长时间不发货
                                $title  = "{$title}商家胜诉，商家已收货";
                                $state  = State::STATE_SERVICE_SELLER_RECEIVE;
                                break;
                            case State::STATE_SERVICE_SELLER_EXPRESS:   #   未收到商家邮寄的商品
                                $title  = "{$title}商家胜诉，售后已完成";
                                $state  = State::STATE_SERVICE_SUCCESS;
                                break;
                        }
                        break;
                }
            } else {    #   商家申诉
                #   商家未收到商品
                switch ($referee_result) {
                    case 0: #   维持原判
                        $title  = "{$title}维持原判，买家邮寄商品";
                        $state  = State::STATE_SERVICE_BUYER_EXPRESS;
                        $next_time  = time() + $times['time_service_seller_receive'];
                        break;
                    case 1: #   买家胜诉
                        $title  = "{$title}买家胜诉，商家收到商品";
                        $state  = State::STATE_SERVICE_SELLER_RECEIVE;
                        break;
                    case 2: #   商家胜诉
                        $title  = "{$title}商家胜诉，售后取消";
                        $state  = State::STATE_SERVICE_CANCEL;
                        break;
                }
            }

            $data   = [
                'orders_service_state'  => $state
            ];
            if ($next_time) $data['orders_service_next_time']   = $next_time;

            $flag   = db('orders_service')
                ->where(['orders_service_id' => $service['orders_service_id']])
                ->update($data);
            if (false == $flag) throw new Exception('更新售后数据失败');

            #   如果售后完成，则把相应数量减去
            if ($state == State::STATE_SERVICE_SUCCESS) {
                $table_prefix   = config('database.prefix');
                $sql    = "UPDATE `{$table_prefix}orders_goods` SET `goods_service_num` = goods_service_num - {$service['orders_service_num']} 
WHERE `orders_goods_id` = {$service['OrdersGoods']['orders_goods_id']}";
//                $flag   = db('orders_goods')->where(['orders_goods_id' => $service['OrdersGoods']['orders_goods_id']])
//                    ->dec('goods_service_num', $service['orders_service_num']);
                $flag   = db()->execute($sql);
                if (false == $flag) throw new Exception('更新可售后数量失败!');
            }

            #   记录日志
            $logs   = [
                'service_logs_title' => $title,
                'orders_service_id'  => $service['orders_service_id'],
                'service_state'      => $data['orders_service_state'],
                'service_no'         => $service['orders_service_no'],
                'service_logs_remark'=> input('remark'),
            ];
            $ret    = Logs::instance()->service($logs);
            if (is_array($ret)) throw new \Exception($ret['code'], $ret['msg']);

            #   入列
            if (isset($tube)) {
                Beanstalkd::getInstance($tube)
                    ->ordersPut($service['orders_service_id'],
                        $service['orders_service_no'],
                        $next_time - time());
            }

            Db::commit();
            return [
                'code'  => State::STATE_NORMAL,
                'msg'   => '操作成功',
            ];
        } catch (\Exception $e) {
            Db::rollback();
            return [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage(),
            ];
        }
    }
}
