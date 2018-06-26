<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 16:30
 */

namespace app\api\controller\orders\v1;


use app\api\model\orders\OrdersRefund;
use app\api\model\orders\OrdersService;
use app\api\model\orders\OrdersShop;
use app\api\service\orders\v1\Logs;
use app\common\traits\F;
use lbzy\sdk\erp\ErpOauth;
use mercury\async\Beanstalkd;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\NoPre;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\factory\Factory;
use mercury\ResponseException;
use think\Db;

/**
 * Class BuyerOrders
 * @package app\api\controller\orders\v1
 *
 * @title 买家订单
 */
class BuyerOrders extends \app\api\service\orders\v1\Orders
{

    /**
     * @var string $user_key 用户条件KEY
     * @var bool $is_seller 是否为商家
     */
    protected $user_key = 'buyer_user_id', $is_seller = false;

    /**
     * *
     * array (
    'address_id' => '62',
    'data' =>
    array (
    0 =>
    array (
    'remark' => '快点发货，谢谢',
    'coupon' => '',
    'express_id' => '1',
    'shop_id' => '1',
    'seller_user_id' => '1',
    'cart_ids' => '2,3,',
    ),
    1 =>
    array (
    'remark' => '比较急。谢谢。',
    'coupon' => '',
    'express_id' => '1',
    'shop_id' => '1',
    'seller_user_id' => '1',
    'cart_ids' => '5,6,7,8,9,10,11,',
    ),
    ),
    )
     */

    /**
     * @title 创建订单
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
     * @return array
     */
    public function create()
    {
        //buyer
        try {
            //创建父订单
            //设置收货地址
            //创建子订单
            //记录订单日志
            Db::startTrans();
            $database1  = Db::connect(config('database1'));
            $database1->startTrans();
            #   提交的订单数据
            $orders_model           = new \app\api\model\orders\Orders();
            $orders_shop_model      = new \app\api\model\orders\OrdersShop();
            $orders_goods_model     = new \app\api\model\orders\OrdersGoods();
            $orders_logs_model      = new \app\api\model\orders\OrdersLogs();
            $orders_address_model   = new \app\api\model\orders\OrdersAddress();
            $cart_model             = new \app\api\model\orders\Cart();
            $confirm    = request()->data['data'];
            if (empty($confirm)) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作!!!');
            $time   = time();

            #   取出cart_id
            $cart_ids_tmp   = '';
            array_filter(array_column($confirm, 'cart_ids'), function ($val) use (&$cart_ids_tmp) {
                $val    = trim($val, ',');
                $cart_ids_tmp   = "{$cart_ids_tmp},{$val}";
            });
            $cart_ids_tmp   = explode(',', trim($cart_ids_tmp, ','));
            if (empty($cart_ids_tmp)) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作！');
            #   查询购物车
            foreach ($cart_ids_tmp as $k => $v) {
                $goods[$k]   = $cart_model->where(function ($query) use ($v) {
                    $query ->where('user_id', request()->user['user_id'])
                        ->where('cart_id', $v);
                })->find($v);
                if (!$goods[$k]) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单已生成');
                $goods[$k]   = $goods[$k]->toArray();
                if ($goods[$k]) $goods[$k]['score'] = F::numberNMulti($goods[$k]['goods_price']);
            }
            #   判断商品
            if (empty($goods[0])) throw new ResponseException(Code::CODE_NO_CONTENT);
//            $express_goods = array_reduce($goods, function (&$express_goods, $val) use ($confirm) {
//                foreach ($confirm as $value) {
//                    if ($value['shop_id'] == $val['shop_id']) {
//                        $val['remark']  = $value['remark'];
//                        $val['remark']  = $value['remark'];
//                        $val['remark']  = $value['remark'];
//                        $val['remark']  = $value['remark'];
//                        $val['remark']  = $value['remark'];
//                    }
//                }
//                $express_goods[$val['express_id']] = $val;
//                return $express_goods;
//            });
            #   判断运费模板分类
            #if (empty($express_goods)) throw new ResponseException(Code::CODE_NO_CONTENT);

//            throw new ResponseException(Code::CODE_OTHER_FAIL);
            #   取出选中的商品,根据运费模板或者商家id分组
            #   父订单信息
            $group_orders_no    = F::createNo(NoPre::NO_PRE_BY_GROUP_ORDERS);
            $group_orders_data  = [
                $this->user_key => request()->user['user_id'],
                'orders_state'  => State::STATE_NORMAL,
                'orders_no'     => $group_orders_no
            ];
            $group_orders_data2 = [
                'orders_amount' => 0,   //最终付款金额
                'orders_score'  => 0,   //最终赠送积分
                'orders_edit_amount'    => 0,
            ];
            $orders_model->data($group_orders_data);
            if (false == $orders_model->save()) throw new ResponseException(Code::CODE_OTHER_FAIL, '创建订单失败');
            $group_orders_id    = $orders_model->getLastInsID();
            #   收货地址信息
            $address_params = [
                'orders_id' => $group_orders_id,
                'orders_no' => $group_orders_no,
                'address_id'=> request()->data['address_id'],
                'user_id'   => request()->user['user_id'],
            ];
            $ret    = \app\api\service\orders\v1\Address::instance()->orders($address_params);
            if ($ret['code'] !== Code::CODE_SUCCESS) throw new ResponseException($ret['code'], $ret['msg']);
            $address_tmp    = $ret['data'];

            #   创建子订单
            $goods_data = [];
            $logs       = [];
            $table_prefix   = config('database.prefix');

//            F::writeLog($confirm);
//            throw new ResponseException(Code::CODE_OTHER_FAIL);
            foreach ($confirm as $k => $v) {
                #   订单
                #   是否含有推广订单
                $cps_spm            = false;
                $orders_shop_no     = F::createNo(NoPre::NO_PRE_BY_SHOP_ORDERS);
                $orders_shop_data   = [
                    $this->user_key => $group_orders_data[$this->user_key],
                    'orders_shop_no'=> $orders_shop_no,
                    'orders_no'     => $group_orders_no,
                    'orders_id'     => $group_orders_id,
                    'shop_id'       => $v['shop_id'],
                    'seller_user_id'=> $v['seller_user_id'],
                    'express_id'    => $v['express_id'],
                    'orders_shop_state' => State::STATE_ORDERS_NORMAL,
                    'orders_shop_remark'=> $v['remark'],
                    'orders_shop_create_time'   => $time,
                    'orders_shop_update_time'   => $time,
                    'orders_shop_next_time'     => Times::times(Times::TIME_ORDERS_CLOSE),
                    'goods_shopping_score_ratio'=> F::getShoppingScoreRatio(),  #   当前购物积分使用基数
                ];
                #   订单金额
                $orders_shop_data2  = [
                    'orders_shop_score'                 => 0,   //最终赠送积分
                    'orders_shop_discount_amount'       => 0,   //优惠金额
                    'orders_shop_amount'                => 0,   //最终付款金额
                    'orders_shop_edit_amount'           => 0,   //修改后的金额
                    'orders_shop_goods_amount'          => 0,   //商品总金额
                    'orders_shop_goods_edit_amount'     => 0,   //修改后的商品金额
                    'orders_shop_express_amount'        => 0,   //快递费用
                    'orders_shop_express_edit_amount'   => 0,   //修改后的快递费用
                    'orders_refund_amount'              => 0,   //可退款金额，最终付款金额
                    'orders_refund_score'               => 0,   //可退款积分，最终赠送积分
                    'orders_refund_express_amount'      => 0,   //可退款运费
                    'goods_style_num'                   => 0,   //商品总款数
                    'goods_count_num'                   => 0,   //商品总数量
                    'goods_weight'                      => 0,   //商品总重量
                    'orders_shop_can_use_shopping_score'=> 0,   //购物积分
                    'orders_refund_num'                 => 0,   #   可退款数量
                ];

                #   优惠信息    需要获取到总金额

                #   促销信息    需要获取到总金额
                //$orders_shop_model->data($orders_shop_data);
                //dump($orders_shop_model->getData());

                if (false == $orders_shop_model->insert($orders_shop_data))
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '创建商家订单失败!');
                #   最后插入的ID
                $orders_shop_id = $orders_shop_model->getLastInsID();
                #   关闭订单入列
                F::beanstalkOrdersPut('orders_close', $orders_shop_id, $orders_shop_no, Times::times(Times::TIME_ORDERS_CLOSE, true));
                $cart_ids   = explode(',', rtrim($v['cart_ids'], ','));
                if (empty($cart_ids)) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作!');

                $goods = $tmp = [];
                foreach ($cart_ids as $ck => $cv) {
                    $goods[$ck] = db('cart')->where([
                        'cart_id'   => $cv,
                        //'express_id'=> $v['express_id'],
                    ])->find();
                    if (!$goods[$ck]) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作...');
                }
                foreach ($goods as $gk => $gv) {
                    #   计算运费
                    $goods_score    = F::numberNMulti($gv['goods_price'], $gv['goods_score_multi'], 2);
                    $goods_id       = db('goods')->where(['goods_id' => $gv['goods_id'], 'goods_state' => State::STATE_NORMAL])->value('goods_id');
                    $sub_goods_name = mb_substr($gv['goods_name'], 0, 6);
                    if (!$goods_id) throw new ResponseException(Code::CODE_OTHER_FAIL, "{$sub_goods_name}已下架或不存在");
                    $goods_sku_id   = db('goods_sku')
                        ->where(['goods_sku_id' => $gv['goods_sku_id'], 'goods_sku_num' => ['egt', $gv['goods_num']]])
                        ->value('goods_sku_id');
                    if (!$goods_sku_id) throw new ResponseException(Code::CODE_OTHER_FAIL, "{$sub_goods_name}库存不足");
                    $goods_data[] = $tmp = [
                        'orders_id'                 => $group_orders_id,
                        'orders_no'                 => $group_orders_no,
                        'orders_shop_id'            => $orders_shop_id,
                        'orders_shop_no'            => $orders_shop_no,
                        'goods_id'                  => $gv['goods_id'],
                        'orders_goods_amount'       => F::amountCalc($gv['goods_price']),
                        'orders_goods_single_amount'=> F::amountCalc($gv['goods_single_price']),
                        'orders_goods_num'          => $gv['goods_num'],
                        'goods_sku_id'              => $gv['goods_sku_id'],
                        'goods_name'                => $gv['goods_name'],
                        'goods_images'              => $gv['goods_images'],
                        'goods_sku_name'            => $gv['goods_sku_name'],
                        'goods_refund_num'          => $gv['goods_num'],
                        'goods_refund_amount'       => F::amountCalc($gv['goods_price']),
                        'goods_refund_score'        => $goods_score,
                        'goods_service_num'         => $gv['goods_num'],
                        'cps_spm'                   => $gv['cps_spm'],
//                        'goods_service_last_day'    => $time + ($gv['goods_service_day'] * 86400),
                        'goods_score_multi'         => $gv['goods_score_multi'],
                        'goods_single_weight'       => $gv['goods_single_weight'],
                        'goods_weight'              => $gv['goods_weight'],
                        'goods_score'               => $goods_score,
                        'goods_shopping_score_multi'=> $gv['goods_shopping_score_multi'],
//                        'goods_pay_shopping_score'  => F::amountCalc($gv['goods_price'] * $gv['goods_shopping_score_multi'])
                        'goods_pay_shopping_score'  => F::matchShoppingScore($gv['goods_price'], $gv['goods_shopping_score_multi'])
                    ];
                    $tmp['express_amount']          = State::STATE_DISABLED;
                    #   删除购物车的商品
                    if (false == $database1->table('zr_cart')->delete($gv['cart_id']))
                        throw new ResponseException(Code::CODE_OTHER_FAIL, '删除购物车失败');

                    #   最多可使用购物积分
                    $use_max_shopping_score = F::matchShoppingScore($gv['goods_price'], $gv['goods_shopping_score_multi']);

                    if (!empty($address_tmp)) {
                        $params = [
                            'sku_id'    => $gv['goods_sku_id'],
                            'city_id'   => $address_tmp['address_city_id'],
                            'num'       => $gv['goods_num'],
                            'weight'    => $gv['goods_weight'],
                            'express_type'  => $v['express_type']
                        ];
                        $express_calc   = Factory::instance('/goods/v1/goodsExpressTpl/courier_fees')->run($params);
                        if ($express_calc['code'] === Code::CODE_SUCCESS) {
                            $tmp['express_amount'] += $express_calc['data']['fees'];
                        } else {
                            throw new ResponseException($express_calc['code'], $express_calc['msg']);
                        }
                    }


                    #   拍下减库存
//                    $sql    = "UPDATE `{$table_prefix}goods` SET ``";

                    #   商家订单数据
                    $orders_shop_data2['orders_shop_discount_amount']   += 0;   //优惠金额
                    $orders_shop_data2['orders_shop_edit_amount']       += F::amountCalc($tmp['orders_goods_amount'] + $tmp['express_amount']);
                    $orders_shop_data2['orders_shop_goods_amount']      += F::amountCalc($tmp['orders_goods_amount']);
                    $orders_shop_data2['orders_shop_goods_edit_amount'] += F::amountCalc($tmp['orders_goods_amount']);
                    $orders_shop_data2['orders_shop_express_amount']    += F::amountCalc($tmp['express_amount']);    //计算运费
                    $orders_shop_data2['orders_shop_express_edit_amount']  += F::amountCalc($tmp['express_amount']);
                    $orders_shop_data2['orders_refund_amount']          += F::amountCalc($tmp['orders_goods_amount']);
                    $orders_shop_data2['orders_refund_score']           += $tmp['goods_refund_score'];
                    $orders_shop_data2['orders_refund_express_amount']  += F::amountCalc($tmp['express_amount']);   //可退运费
                    $orders_shop_data2['orders_shop_score']             += $tmp['goods_refund_score'];
                    $orders_shop_data2['orders_shop_amount']            += F::amountCalc($tmp['orders_goods_amount'] + $tmp['express_amount']);
                    $orders_shop_data2['goods_count_num']               += $tmp['orders_goods_num'];
                    $orders_shop_data2['goods_style_num']               += State::STATE_NORMAL;
                    $orders_shop_data2['goods_weight']                  += $gv['goods_weight'];
                    $orders_shop_data2['orders_refund_num']             += $tmp['orders_goods_num'];
                    $orders_shop_data2['orders_shop_can_use_shopping_score']    += $use_max_shopping_score;


                    #   拍下减库存,如果是促销中的商品则拍下减库存
                    /*
                    $sql    = "UPDATE `{$table_prefix}goods_sku` SET `goods_sku_sale_num` = goods_sku_sale_num + {$tmp['orders_goods_num']},
`goods_sku_num` = goods_sku_num - {$tmp['orders_goods_num']}
WHERE `goods_sku_id` = {$gv['goods_sku_id']}";
                    $flag   = db()->execute($sql);
                    if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新库存失败');*/
                    #   如果存在cps_spm则改为true
                    if (!empty($tmp['cps_spm'])) $cps_spm = true;
                }

                #   总订单数据
                $group_orders_data2['orders_amount']+= F::amountCalc($orders_shop_data2['orders_shop_amount']);
                $group_orders_data2['orders_score'] += $orders_shop_data2['orders_shop_score'];
                $group_orders_data2['orders_edit_amount'] += F::amountCalc($orders_shop_data2['orders_shop_amount']);
                #   更新商家订单金额

//                if (false == $orders_shop_model->save($orders_shop_data2, ['orders_shop_id' => $orders_shop_id])) {
                if (false == db('orders_shop')->where(['orders_shop_id' => $orders_shop_id])->update($orders_shop_data2)) {
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '更新商家订单金额失败');
                }

                #   推广订单入列
                if ($cps_spm) {
                    $beanstalk  = new Beanstalkd('cps_ordersCreate');
                    $beanstalk->put(['id' => $orders_shop_id], 1024, 30);
                }

                #   创建订单日志
                $logs[]   = [
                    'orders_logs_title' => '创建订单',
                    'orders_shop_state' => $orders_shop_data['orders_shop_state'],
                    'orders_shop_id'    => $orders_shop_id,
                    'orders_shop_no'    => $orders_shop_no,
                    'orders_logs_is_display'    => State::STATE_NORMAL,
                    'orders_logs_create_time'   => $time,
                ];
            }
//            throw new ResponseException(Code::CODE_OTHER_FAIL);

            /*
            foreach ($selected as $k => $v) {
                #   订单
                $orders_shop_no     = F::createStr(NoPre::NO_PRE_BY_SHOP_ORDERS);
                $orders_shop_data   = [
                    $this->user_key => $group_orders_data[$this->user_key],
                    'orders_shop_no'=> $orders_shop_no,
                    'orders_no'     => $group_orders_no,
                    'orders_id'     => $group_orders_id,
                    'shop_id'       => $v['shop_id'],
                    'seller_user_id'=> $v['seller_user_id'],
                    'orders_shop_state' => State::STATE_ORDERS_NORMAL,
                ];

                #   订单金额
                $orders_shop_data2  = [
                    'orders_shop_score'                 => 0,   //最终赠送积分
                    'orders_shop_discount_amount'       => 0,   //优惠金额
                    'orders_shop_amount'                => 0,   //最终付款金额
                    'orders_shop_edit_amount'           => 0,   //修改后的金额
                    'orders_shop_goods_amount'          => 0,   //商品总金额
                    'orders_shop_goods_edit_amount'     => 0,   //修改后的商品金额
                    'orders_shop_express_amount'        => 0,   //快递费用
                    'orders_shop_express_edit_amount'   => 0,   //修改后的快递费用
                    'orders_refund_amount'              => 0,   //可退款金额，最终付款金额
                    'orders_refund_score'               => 0,   //可退款积分，最终赠送积分
                    'orders_refund_express_amount'      => 0,   //可退款运费
                    'goods_style_num'                   => 0,   //商品总款数
                    'goods_count_num'                   => 0,   //商品总数量
                ];

                #   优惠信息    需要获取到总金额

                #   促销信息    需要获取到总金额
                $orders_shop_model->data($orders_shop_data);
                $orders_shop_id = $orders_shop_model->save();
                if (false == $orders_shop_id)
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '创建商家订单失败');

                #   获取商品并且创建订单中的商品
                $goods  = $tmp = [];
                foreach ($goods as $key => $val) {
                    #   计算运费
                    $goods_data[] = $tmp = [
                        'orders_id'                 => $group_orders_id,
                        'orders_no'                 => $group_orders_no,
                        'orders_shop_id'            => $orders_shop_id,
                        'orders_shop_no'            => $orders_shop_no,
                        'goods_id'                  => $val['goods_id'],
                        'orders_goods_amount'       => $val['goods_amount'],
                        'orders_goods_single_amount'=> $val['goods_single_amount'],
                        'orders_goods_num'          => $val['goods_num'],
                        'goods_sku_id'              => $val['goods_sku_id'],
                        'goods_name'                => $val['goods_name'],
                        'goods_images'              => $val['goods_images'],
                        'goods_sku_name'            => $val['goods_sku_name'],
                        'goods_refund_num'          => $val['goods_num'],
                        'goods_refund_amount'       => $val['goods_amount'],
                        'goods_refund_score'        => $val['goods_amount'] * $val['goods_score_multi'],
                        'goods_service_num'         => $val['goods_num'],
                        'goods_service_last_day'    => time() + ($val['goods_service_day'] * 86400),
                        'goods_score_multi'         => $val['goods_score_multi'],
                        'express_id'                => $val['express_id'],
                        'goods_score'               => $val['goods_amount'] * $val['goods_score_multi'],
                        'goods_express_amount'      => '',  //运费金额
                        'goods_refund_express_amount'   => '',  //可退运费金额
                        'orders_shop_create_time'   => time(),
                    ];

                    #   删除购物车的商品
                    if (false == db('cart')->delete($val['cart_id']))
                        throw new ResponseException(Code::CODE_OTHER_FAIL, '删除购物车失败');

                    #   商家订单数据
                    $orders_shop_data2['orders_shop_discount_amount']   += 0;   //优惠金额
                    $orders_shop_data2['orders_shop_edit_amount']       += $tmp['orders_goods_amount'];
                    $orders_shop_data2['orders_shop_goods_amount']      += $tmp['orders_goods_amount'];
                    $orders_shop_data2['orders_shop_goods_edit_amount'] += $tmp['orders_goods_amount'];
                    $orders_shop_data2['orders_shop_express_amount']    += $tmp['goods_express_amount'];    //计算运费
                    $orders_shop_data2['orders_refund_amount']          += $tmp['orders_goods_amount'];
                    $orders_shop_data2['orders_refund_score']           += $tmp['goods_refund_score'];
                    $orders_shop_data2['orders_refund_express_amount']  += $tmp['goods_refund_express_amount'];   //可退运费
                    $orders_shop_data2['orders_shop_score']             += $tmp['goods_refund_score'];
                    $orders_shop_data2['orders_shop_amount']            += $tmp['orders_goods_amount'];
                    $orders_shop_data2['goods_count_num']               += $tmp['orders_goods_num'];
                    $orders_shop_data2['goods_style_num']               += 1;
                }

                #   总订单数据
                $group_orders_data2['orders_price'] += $orders_shop_data2['orders_shop_score'];
                $group_orders_data2['orders_score'] += $orders_shop_data2['orders_shop_amount'];

                #   更新商家订单金额
                if (false == db('orders_shop')->where(['orders_shop_id' => $orders_shop_id])->update($orders_shop_data2))
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '更新商家订单金额失败');

                #   创建订单日志
                $logs[]   = [
                    'orders_logs_title' => '创建订单',
                    'orders_shop_state' => $orders_shop_data['orders_shop_state'],
                    'orders_shop_id'    => $orders_shop_id,
                    'orders_shop_no'    => $orders_shop_no,
                    'orders_logs_is_display'    => State::STATE_NORMAL,
                    'orders_logs_create_time'   => time(),
                ];
            }*/



            #   更新父订单金额
            if (false == $orders_model->save($group_orders_data2, ['orders_id' => $group_orders_id]))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '更新父订单金额失败');


            #   创建商品
            if (false == $orders_goods_model->insertAll($goods_data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '创建订单商品失败');

            #   创建订单日志
            if (false == $orders_logs_model->insertAll($logs))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '创建订单日志失败！');
            Db::commit();
            $database1->commit();
            return ['orders_no' => $group_orders_no];
        } catch (ResponseException $e) {
            Db::rollback();
            if (isset($database1)) $database1->rollback();
            return $e->getData();
        }
    }


    /**
     * @title 买家收货
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|-|
     * |shop_no|string|true|-|-|-|
     * |pay_password|string|true|-|-|-|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |-|-|-|-|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return array|int
     */
    public function receive()
    {
        //buyer
        try {
            //买家确认收货，更改订单状态
            //记录订单日志
            Db::startTrans();
            $isAuth = ErpOauth::instance()->checkPayPassword(['openid' => request()->user['openid'], 'safe_psw' => request()->data['pay_password']]);
            if (true !== $isAuth) throw new ResponseException(Code::CODE_OTHER_FAIL, $isAuth);
            $map    = [
                $this->user_key     => request()->user['user_id'],
                'orders_shop_state' => State::STATE_ORDERS_SHIP,
                'orders_shop_no'    => request()->data['shop_no'],
                'orders_shop_is_freeze' => State::STATE_DISABLED,   #   非冻结状态
            ];
            $orders_shop_model  = new OrdersShop();
            $orders = $orders_shop_model->where($map)->relation('receiveGoods')->find();
            if (!$orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在或已冻结');
            $orders = $orders->toArray();
            $time   = time();
            $data   = [
                'orders_shop_state'         => State::STATE_ORDERS_RECEIVE,
                'orders_shop_receive_time'  => $time,
                'orders_shop_next_time'     => Times::times(Times::TIME_ORDERS_COMMENT)
            ];

            #   如果可退商品为0
            $title  = '买家确认收货';
            if ($orders['orders_refund_num'] <= State::STATE_DISABLED) {
                $title  = '订单已无商品，系统标为已评价';
                $data['orders_shop_state']  = State::STATE_ORDERS_COMMIT;
            }
            $orders_shop_model  = new OrdersShop();
            if (false == $orders_shop_model->save($data, $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '确认收货失败，请稍后再试');

            #   更新商品售后时间
            if (!empty($orders['receiveGoods'])) {
                foreach ($orders['receiveGoods'] as $v) {
                    $service_time   = db('goods')->where([
                        'goods_id' => $v['goods_id'],
                        'goods_service_days' => ['gt', State::STATE_DISABLED]])->value('goods_service_days');
                    if ($service_time) {
                        $service_time   = $time + ($service_time * 86400);
                        $flag   = db('orders_goods')->where(['orders_goods_id' => $v['orders_goods_id']])->update(['goods_service_last_day' => $service_time]);
                        if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '写入售后时间出错');
                    }
                }
            }


            #   取消退款
            $ordersRefund   = new OrdersRefund();
//            $refunds        = $ordersRefund->where('orders_shop_id', $orders['orders_shop_id'])
//                ->whereNotIn('orders_refund_state', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS])->select();
            $refunds        = F::dataList($ordersRefund, [
                'where' => [
                    'orders_shop_id'    => $orders['orders_shop_id'],
                    'orders_refund_state'   => ['not in', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS]]
                ]
            ]);
            if ($refunds) {
                $flag   = $ordersRefund->save(['orders_refund_update_time' => $time, 'orders_refund_state' => State::STATE_REFUNDS_CANCEL], [
                        'orders_shop_id'    => $orders['orders_shop_id'],
                        'orders_refund_state'   => ['not in', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS]]
                    ]);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '取消退款失败');
                $logs   = [
                    'refund_logs_title' => '确认收货取消退款',
                    'refund_state'      => State::STATE_REFUNDS_CANCEL,
                    'refund_logs_remark'=> '确认收货取消退款',
                ];
                foreach ($refunds as $v) {
                    #   记录日志
                    $logs['orders_refund_id']   = $v['orders_refund_id'];
                    $logs['refund_no']          = $v['orders_refund_no'];
                    $ret    = Logs::instance()->refund($logs);
                    if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);
                }
            }

            $beanstalk  = new Beanstalkd('erp_receive');
            $flag       = $beanstalk->put([
                'order_no'  => request()->data['shop_no'],
                'openid'    => request()->user['openid'],
                'safe_psw'  => '',
                'is_auto'   => State::STATE_NORMAL,
            ]);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '入列失败');

            #   记录日志
            $logs   = [
                'orders_logs_title' => $title,
                'orders_shop_id'    => $orders['orders_shop_id'],
                'orders_shop_state' => $data['orders_shop_state'],
                'orders_shop_no'    => $orders['orders_shop_no'],
                'orders_logs_is_display'    => State::STATE_NORMAL
            ];
            $ret    = Logs::instance()->orders($logs);
            if (is_array($ret)) throw new ResponseException($ret['code'], $ret['message']);

            //异步通知erp赠送积分
            #   评价时间
            F::beanstalkOrdersPut('orders_comment',
                $orders['orders_shop_id'],
                $orders['orders_shop_no'],
                Times::times(Times::TIME_ORDERS_COMMENT, true));
            #   通知ERP赠送积分
            F::beanstalkErpReceivePut('erp_receive', [
                'openid'    => db('user')->where(['user_id' => $orders['buyer_user_id']])->cache(true)->value('openid'),
                'order_no'  => $orders['orders_shop_no'],
                'safe_psw'  => '',
                'is_auto'   => State::STATE_NORMAL
            ]);

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 父订单信息
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|-|
     * |orders_no|string|true|-|-|-|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function groupOrders()
    {
        //buyer
        try {
            //获取父订单详情
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_no'     => request()->param('orders_no')
            ];
            $orders_model   = new \app\api\model\orders\Orders();
            $data   = $orders_model->where($map)->relation(false)->find();
            //总订单信息，子订单信息，收货地址信息

            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data->toArray();
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 提醒商家发货
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|-|
     * |shop_no|string|true|-|-|-|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |-|-|-|-|
     * @response_example 响应示例
     * `you are json code`
     * @description 接口描述
     * > you are api description
     * @return array|int
     */
    public function noticeShip()
    {
        try {
            $shop_no= request()->data['shop_no'];
            $key    = F::getCacheName(Cache::NOTICE_ORDERS_SHIP . $shop_no);
            if (F::redis()->exists($key))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '您在6个小时内有通知过，请稍后再通知！');
            $map    = [
                'orders_shop_state' => State::STATE_ORDERS_PAY,
                'orders_shop_no'    => $shop_no,
                'orders_shop_is_freeze' => State::STATE_DISABLED,
            ];
            $model  = new OrdersShop();
            $orders = F::dataDetail($model, [
                    'where' => $map,
                    'field' => 'shop_id,orders_shop_no'
                ]);
            if (!$orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在或不可发送提醒');
            $shop   = Factory::instance('/goods/v1/shop/detail')->run(['shop_id' => $orders['shop_id']]);
            if ($shop['code'] != Code::CODE_SUCCESS)
                throw new ResponseException($shop['code'], $shop['msg']);
            $content    = "订单号：{$orders['orders_shop_no']}请尽快发货，超过三天未发货则扣除基础积分！";
            F::gearmanSms($shop['data']['shop_mobile'], $content);
            F::redis()->setex($key, 3600 * 6, 1);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 再次购买
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
    public function buyAgain()
    {
        try {
            $shop_no    = request()->data['shop_no'];
            $map    = [
                'orders_shop_no'    => $shop_no,
                'buyer_user_id'     => request()->user['user_id']
            ];
            $model  = new OrdersShop();
            $goods  = F::dataDetail($model, [
                'where'     => $map,
                'relation'  => 'goods',
            ]);
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 退款订单
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
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function refundOrders()
    {
        try {
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_no'=> request()->data['shop_no'],
                'orders_shop_state' => ['in', [State::STATE_ORDERS_PAY, State::STATE_ORDERS_SHIP]]
            ];
            $model  = new OrdersShop();
            $data   = $model->relation('refundOrders')->where($map)->find();
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data->toArray();
            #   过滤
            $refund_model   = new OrdersRefund();
            $goods          = [];
            $goods_cnt      = count($data['refundOrders']);
            foreach ($data['refundOrders'] as $k => $v) {
                $map        = [
                    'orders_shop_no'    => $v['orders_shop_no'],
                    'orders_goods_id'   => $v['orders_goods_id'],
                    'orders_refund_state'   => ['not in', [State::STATE_REFUNDS_CANCEL, State::STATE_REFUNDS_SUCCESS]]
                ];
                $refunds    = $refund_model->where($map)->field('SUM(orders_refund_amount) as amount,SUM(orders_refund_express_amount) as express_amount, SUM(orders_refund_num) as num')->find();
                if ($refunds) {
                    $refunds    = $refunds->toArray();
                } else {
                    $refunds['num']     = 0;
                    $refunds['amount']  = 0;
                    $refunds['express_amount']  = 0;
                }
                $refunds['amount']          = F::amountCalc($refunds['amount']);
                $v['goods_refund_amount']   = F::amountCalc($v['goods_refund_amount']);
                $data['orders_refund_express_amount']   -= $refunds['express_amount'];
                if ($v['goods_refund_amount'] > $refunds['amount']) $goods[] = $v;
                if ($k + State::STATE_NORMAL == $goods_cnt &&
                    $data['orders_refund_express_amount'] > State::STATE_DISABLED &&
                    empty($goods)) {
                    $goods[] = $v;
                }
            }
            if (empty($goods)) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data['goods']  = $goods;
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 退款申请
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
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function refundApply()
    {
        try {
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_no'=> request()->data['shop_no'],
                'orders_shop_state' => ['in', [State::STATE_ORDERS_PAY, State::STATE_ORDERS_SHIP]]
            ];
            $model  = new OrdersShop();
            $data   = $model->where($map)->relation('refundApply')->find();
            if (!$data || !$data->refundApply) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data->toArray();
//            if (!$data['refundApply']) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单已退完或已申请');
            $refundModel    = new OrdersRefund();
            $express_amount = $refundModel->sumOldRefundsExpressAmount($data['orders_shop_no'], $data['refundApply']['orders_goods_id']);
            $refund_amount  = $refundModel->sumOldRefunds($data['orders_shop_no'], $data['refundApply']['orders_goods_id']);

            #   重新计算运费
            $data['orders_refund_express_amount']       -= F::amountCalc($express_amount);
            $data['refundApply']['goods_refund_num']    -= F::amountCalc($refund_amount['num']);
            $data['refundApply']['goods_refund_amount'] -= F::amountCalc($refund_amount['amount']);
            #   是否可退货，
            if ($data['orders_shop_state'] > State::STATE_ORDERS_PAY) {
                $data['orders_refund_is_ship'] = State::STATE_NORMAL; #   是
            } else {
                $data['orders_refund_is_ship'] = State::STATE_DISABLED;   #   否
            }
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 售后订单
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
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function serviceOrders()
    {
        try {
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_no'=> request()->data['shop_no'],
                'orders_shop_state' => ['in', [State::STATE_ORDERS_RECEIVE, State::STATE_ORDERS_COMMIT]]
            ];

            $model  = new OrdersShop();
            $data   = $model->where($map)->relation('serviceOrders')->find();
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data->toArray();
            foreach ($data['serviceOrders'] as $k => $v) {
                $apply_num  = db('orders_service')->where([
                    'orders_shop_no'        => $v['orders_shop_no'],
                    'orders_goods_id'       => $v['orders_goods_id'],
                    'orders_service_state'  => ['not in', [State::STATE_SERVICE_SUCCESS, State::STATE_SERVICE_CANCEL]]
                ])->value('SUM(orders_service_num) as num');
                #   如果已经申请满了则删除当前商品
                if (($v['goods_service_num'] - $apply_num) <= 0) {
                    unset($data['serviceOrders'][$k]);
                }
            }
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 售后申请
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
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function serviceApply()
    {
        try {
            $map    = [
                $this->user_key => request()->user['user_id'],
                'orders_shop_no'=> request()->data['shop_no'],
                'orders_shop_state' => ['in', [State::STATE_ORDERS_RECEIVE, State::STATE_ORDERS_COMMIT]]
            ];
            $model  = new OrdersShop();
            $data   = $model->where($map)->relation('serviceApply')->find();
            if (!$data || !$data->serviceApply) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data->toArray();
            #   取出正在售后的数量
            $serviceModel   = new OrdersService();
            $can_service_num= $serviceModel->sumOldService(request()->data['shop_no'], $data['serviceApply']['orders_goods_id']);
//
//
//            $service_map    = [
//                'orders_goods_id'   => $data['serviceApply']['orders_goods_id'],
//                'orders_shop_no'    => $data['serviceApply']['orders_shop_no'],
//                'orders_service_state'  => ['not in', [State::STATE_SERVICE_SUCCESS, State::STATE_SERVICE_CANCEL]]
//            ];
//            $num    = db('orders_service')->where($service_map)->value('SUM(orders_service_num) as num');
//            $num    = $num ? : 0;
            $data['serviceApply']['goods_service_num']  -= $can_service_num;
            if (!$data['serviceApply']) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单已退完或已申请');
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 可评价订单商品
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
     * @return array
     */
    public function comments()
    {
        try {
            $map    = [
                $this->user_key     => request()->user['user_id'],
                'orders_shop_no'    => request()->data['shop_no'],
            ];
            $model  = new OrdersShop();
//            $data   = F::dataDetail($model, [
//                'where'     => $map,
//                'relation'  => 'commentGoods'
//            ]);
            $data   = $model->where($map)->relation('commentGoods')->find()->toArray();
            if (!$data || empty($data['commentGoods'])) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}