<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15 0015
 * Time: 11:36
 */

namespace app\api\controller\orders\v1;

use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\factory\Factory;
use mercury\ResponseException;
use think\Db;

/**
 * Class Cart
 * @package app\api\orders\v1
 *
 * @title 购物车
 */
class Cart
{
    /**
     * @var \app\api\model\orders\Cart 模型
     */
    protected $model;

    public function __construct()
    {
        $this->model    = new \app\api\model\orders\Cart();
    }

    /**
     * @title 购物车
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|-|
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
    public function index()
    {
        try {
            $ids    = $this->model
                ->where(['user_id' => request()->user['user_id']])
                ->group('shop_id')  #   express_id
                ->column('shop_id');    #   express_id
            $data   = [];
            if (!empty($ids)) {
                foreach ($ids as $k => $v) {
                    $shop   = Factory::instance('/goods/v1/shop/detail')->run(['shop_id' => $v]);
                    if ($shop['code'] != Code::CODE_SUCCESS) throw new ResponseException($shop['code'], $shop['msg']);
                    $data[$k]['shop']   = [
                        'shop_name' => $shop['data']['shop_name'],
                        'shop_logo' => $shop['data']['shop_logo'],
                        'shop_id'   => $shop['data']['shop_id'],
                        ];
//                    $data[$k]['goods']  = $this->model->where(['shop_id' => $v])->select(); #   express_id
                    $data[$k]['goods']  = F::dataList($this->model, [
                        'where' => ['shop_id' => $v, 'user_id' => request()->user['user_id']],
                    ]);
                    foreach ($data[$k]['goods'] as $key => &$val) {
                        #   属性修改，如价格，购物积分需要把修改后的值修改至购物车
                        $shopping_score  = db('goods')->where(['goods_id' => $val['goods_id'], 'goods_state' => State::STATE_NORMAL])
                            ->value('goods_shopping_score_multi');
                        if ($shopping_score) {  #   如果能查到记录
                            $up_data    = [];
                            $sku    = db('goods_sku')
                                ->where([
                                    'goods_sku_id' => $val['goods_sku_id'],
                                    'goods_sku_num' => ['egt', $val['goods_num']]
                                ])->field('goods_sku_price,goods_sku_num')->find();
                            if ($sku) { #   如果有库存
                                if ($shopping_score != $val['goods_shopping_score_multi']) {
                                    $val['shopping_score_change']   = sprintf('购物积分抵扣由%d修改至%d', $val['goods_shopping_score_multi'], $shopping_score);
                                    $up_data['goods_shopping_score_multi'] = $val['goods_shopping_score_multi']  = $shopping_score;
                                }
                                if ($sku['goods_sku_price'] != $val['goods_single_price']) {
                                    $val['sku_price_change']    = sprintf('商品单价由%g修改至%g', $val['goods_single_price'], $sku['goods_sku_price']);
                                    $up_data['goods_single_price'] = $val['goods_single_price'] = $sku['goods_sku_price'];
                                    $up_data['goods_price'] = $val['goods_price'] = round($up_data['goods_single_price'] * $val['goods_num'], 2);
                                }
                                if (isset($up_data) && !empty($up_data)) {
                                    $up_data['cart_update_time']    = time();
                                    $flag   = db('cart')->where(['cart_id' => $val['cart_id']])
                                        ->update($up_data);
                                    if (!$flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新购物车失败!');
                                }
                                $val['can_buy'] = State::STATE_NORMAL;
                            } else {    #   如果没库存
                                $val['can_buy'] = State::STATE_DISABLED;
                            }
                        } else {    #   如果不能查到记录
                            $val['can_buy'] = -1;
                        }

//                        if (db('goods')->where(['goods_id' => $val['goods_id'], 'goods_state' => State::STATE_NORMAL])->value('goods_id')) {
//                            $val['can_buy'] = db('goods_sku')->where([
//                                'goods_sku_id' => $val['goods_sku_id'],
//                                'goods_sku_num' => ['egt', $val['goods_num']]
//                            ])->value('goods_sku_num') ? : State::STATE_DISABLED;
//                        } else {
//                            $val['can_buy'] = -1;
//                        }
                    }
                }
            }
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 添加商品
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
     * @param bool $is_buy
     * @return array|int
     */
    public function create($is_buy = false)
    {
        //buyer
        try {
            #   判断购物车是否存在当前商品
            //添加商品至购物车
//            if (!session('admin')) throw new ResponseException(Code::CODE_OTHER_FAIL, '商家备货中，暂不能交易。');
            $goods_num  = request()->param('goods_num', 1);
            $map    = [
                'user_id'       => request()->user['user_id'],
                'goods_sku_id'  => request()->data['goods_sku_id'],
                'goods_id'      => request()->data['goods_id']
            ];
            $cart   = $this->model->where($map)->field('cart_id,goods_single_price,goods_single_weight')->find();
            if ($cart) {
                $cart_data  = [
                    'goods_num'         => $goods_num,
                    'goods_price'       => bcmul($goods_num, $cart['goods_single_price'], 2),
                    'goods_weight'      => bcmul($goods_num, $cart['goods_single_weight'], 2),
                    'cart_update_time'  => time(),
                    'cps_spm'           => request()->data['cps_spm'] ?? '',
                ];
                $flag   = db('cart')->where(['cart_id' => $cart['cart_id']])->update($cart_data);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新数量失败');
                if ($is_buy) {
                    return [
                        'code'  => Code::CODE_SUCCESS,
                        'data'  => [
                            'id'    => $cart['cart_id'],
                        ]
                    ];
                }
                return Code::CODE_SUCCESS;
            }
            #   需要获取商品详情
//            $goods  = [
//                'shop_id'       => 1,
//                'goods_name'    => 'xxx',
//                'goods_images'  => 'FlhCiuAH7435PdTFNRBZPw28rW3d',
//                'goods_sku_name'=> 'www',
//                'goods_price'   => 10.9,
//                'express_id'    => 2,
//                'goods_service_day' => 3,
//                'goods_score_multi' => 100,
//            ];
            $goods  = Factory::instance('/goods/v1/goods/detail')->run(['id' => $map['goods_sku_id']]);
            if ($goods['code'] != Code::CODE_SUCCESS) throw new ResponseException($goods['code'], $goods['msg']);
            $goods  = $goods['data'];
            if ($goods['goods_sku_num'] <= 0) throw new ResponseException(Code::CODE_OTHER_FAIL, '库存不足！');
            if ($goods['goods']['seller_user_id'] == request()->user['user_id'])
                throw new ResponseException(Code::CODE_OTHER_FAIL, '不能购买自己的商品！');
            $images = $goods['goods_sku_album'] ?
                substr($goods['goods_sku_album'][0], strripos($goods['goods_sku_album'][0], '/') + 1) :
                substr($goods['goods']['goods_images'], strripos($goods['goods']['goods_images'], '/') + 1);

            $goods  = [
                'shop_id'       => $goods['goods']['shop_id'],
                'goods_name'    => $goods['goods']['goods_name'],
                'goods_images'  => $images,
                'goods_sku_name'=> $goods['goods_sku_group_name_values'],
                'goods_price'   => $goods['goods_sku_price'],
                'express_id'    => $goods['goods']['express_id'],
                'goods_service_day' => $goods['goods']['goods_service_days'],
                'goods_score_multi' => $goods['goods']['goods_score_multi'],
                'goods_shopping_score_multi'   => $goods['goods']['goods_shopping_score_multi'],
                'goods_weight'      => $goods['goods_sku_weight'],
            ];
            $data   = [
                'user_id'               => request()->user['user_id'],
                'goods_id'              => request()->data['goods_id'],
                'shop_id'               => $goods['shop_id'],
                'goods_num'             => $goods_num,
                'goods_name'            => $goods['goods_name'],
                'goods_images'          => $goods['goods_images'],
                'goods_price'           => F::numberNMulti($goods['goods_price'], $goods_num, 2),
                'goods_sku_id'          => request()->data['goods_sku_id'],
                'goods_sku_name'        => $goods['goods_sku_name'],
                'express_id'            => $goods['express_id'],
                'goods_single_price'    => F::amountCalc($goods['goods_price']),
                'goods_score_multi'     => $goods['goods_score_multi'],
                'goods_weight'          => $goods['goods_weight'] * $goods_num,
                'goods_single_weight'   => $goods['goods_weight'],
                'cps_spm'               => request()->data['cps_spm'] ?? '',
                'goods_shopping_score_multi'    => $goods['goods_shopping_score_multi'],
            ];
            if (false == $this->model->save($data)) throw new ResponseException(Code::CODE_OTHER_FAIL);
            if ($is_buy) {
                $id = $this->model->getLastInsID();
                return [
                    'code'  => Code::CODE_SUCCESS,
                    'data'  => [
                        'id'    => $id,
                    ]
                ];
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 加数量
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
    public function plusNum()
    {
        //buyer
        try {
            //为商品加数量
            $map    = [
                'user_id'   => request()->user['user_id'],
                'cart_id'   => request()->data['cart_id'],
            ];
            $goods  = $this->model->where($map)->field('goods_num,goods_price,goods_single_price,goods_sku_id,goods_single_weight,goods_weight')->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');
            if ($goods['goods_num'] >= 999) throw new ResponseException(Code::CODE_OTHER_FAIL, '购物车数量不能大于999件');

            $sku    = Factory::instance('/goods/v1/goods/detail')->run(['id' => $goods['goods_sku_id']]);
            if ($sku['code'] != Code::CODE_SUCCESS) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');
            $goods_num  = $goods['goods_num']+1;
            if ($sku['data']['goods_sku_num'] < $goods_num) throw new ResponseException(Code::CODE_OTHER_FAIL, '库存不足');

            $data   = [
                'goods_num'     => $goods_num,
                'goods_price'   => bcadd($goods['goods_price'], $goods['goods_single_price'], 2),
                'goods_weight'   => bcadd($goods['goods_weight'], $goods['goods_single_weight'], 2),
            ];
            $flag   = $this->model->save($data, $map);

            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 减数量
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
    public function lessNum()
    {
        //buyer
        try {
            //为购物车商品减数量
            $map    = [
                'user_id'   => request()->user['user_id'],
                'cart_id'   => request()->data['cart_id'],
            ];
            $goods  = $this->model->where($map)->field('goods_num,goods_price,goods_single_price,goods_single_weight,goods_weight')->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');
            if ($goods['goods_num'] <= 1) throw new ResponseException(Code::CODE_OTHER_FAIL, '购物车数量不能小于1件');

            $goods['goods_num'] -= 1;
            $data   = [
                'goods_num'     => $goods['goods_num'],
                'goods_price'   => bcsub($goods['goods_price'], $goods['goods_single_price'], 2),
                'goods_weight'  => bcsub($goods['goods_weight'], $goods['goods_single_weight'], 2),
            ];
            $flag   = $this->model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 设置数量
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
    public function setNum()
    {
        //buyer
        try {
            //直接更改购物车数量
            //$this->model->data(['goods_num' => request()->param('goods_num')]);
            $map    = [
                'user_id'   => request()->user['user_id'],
                'cart_id'   => request()->data['cart_id'],
            ];
            $goods  = $this->model->where($map)->field('goods_num,goods_price,goods_single_price')->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');
            $goods_num  = request()->data['goods_num'];
            $data   = [
                'goods_num'     => $goods_num,
                'goods_price'   => F::numberNMulti($goods['goods_single_price'], $goods_num, 2),
            ];
            $flag   = $this->model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 确认订单
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
    public function confirm()
    {
        //buyer
        try {
            //确认订单页面，获取选中的商品
            $ids    = explode(',', rtrim(request()->data['cart_ids'], ','));
            $goods  = [];
            foreach ($ids as $k => $v) {
                $goods[$k]   = $this->model->where(function ($query) use ($v) {
                    $query ->where('user_id', request()->user['user_id'])
                        ->where('cart_id', $v);
                })->find($v);
                if (!$goods[$k]) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在！');
                $goods[$k]  = $goods[$k]->toArray();
                if ($goods[$k]) $goods[$k]['score'] = F::numberNMulti($goods[$k]['goods_price'],$goods[$k]['goods_score_multi']);
            }
            #   判断商品
            if (empty($goods[0])) throw new ResponseException(Code::CODE_NO_CONTENT);
            $express_ids    = array_reduce($goods, function (&$express_ids, $val) {
                $express_ids[$val['shop_id']] = $val['shop_id'];
                return $express_ids;
            });
            #   判断运费模板
            if (empty($express_ids)) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = [];
            $i  = 0;
            $goods_style_num    = 0;
            $amount             = 0;
            $score              = 0;

            #   收货地址
            $address_id = request()->param('id', 0);
            $address    = Factory::instance('/orders/v1/userAddress/detail')->run(['id' => $address_id]);
            $address_tmp= [];
            if ($address['code'] == Code::CODE_SUCCESS) $address_tmp = $address['data'];

            foreach ($express_ids as $k => $v) {
                #   设置店铺数据
                //$data[$i]['shop']   = ['shop_name' => '百望旗舰店'];
                $shop['express_ems_amount'] = 0;
                $shop['express_amount'] = 0;
                $shop['goods_amount']   = 0;
                $shop['score']          = 0;
                $shop['shopping_score'] = 0;
                $shop['goods_num']      = 0;
                $shop['amount']         = 0;
                $shop['cart_ids']       = '';
                $tmp_express_amount     = 0;
                $data['shop'][$i]['goods']  = array_reduce($goods, function (&$goods, $val) use ($k, &$shop, &$goods_style_num, &$amount, &$score, $address_tmp, $tmp_express_amount) {
                    if ($val['shop_id'] == $k) {
                        $goods[]    = $val;
                        $shopping_score = F::matchShoppingScore($val['goods_price'], $val['goods_shopping_score_multi']);
                        $shop['goods_amount']   += $val['goods_price'];
                        $shop['score']          += $val['score'];
                        $shop['shopping_score'] += $shopping_score;
                        $shop['goods_num']      += $val['goods_num'];
                        $shop['cart_ids']       .= "{$val['cart_id']},";
                        $score                  += $val['score'];
                        if (!empty($address_tmp)) {
                            $params = [
                                'sku_id'    => $val['goods_sku_id'],
                                'city_id'   => $address_tmp['address_city_id'],
                                'num'       => $val['goods_num'],
                                'weight'    => $val['goods_weight'],
                                'express_type'  => State::STATE_NORMAL
                            ];
                            $express_calc   = Factory::instance('/goods/v1/goodsExpressTpl/courier_fees')->run($params);
                            if ($express_calc['code'] === Code::CODE_SUCCESS) {
                                $shop['express_amount'] += $tmp_express_amount = $express_calc['data']['fees'];
                                $shop['express_ems_amount'] += $express_calc['data']['ems_fees'];
                            } else {
                                throw new ResponseException($express_calc['code'], $express_calc['msg']);
                            }
                        }
                        $amount += $val['goods_price'] + $tmp_express_amount;
                        $shop['amount'] += $val['goods_price'] + $tmp_express_amount;
                        ++$goods_style_num;
                    }
                    return $goods;
                });
                #   判断快递是否包邮
                $shop_tmp   = Factory::instance('/goods/v1/shop/detail')->run(['shop_id' => $v]);
                if ($shop_tmp['code'] != Code::CODE_SUCCESS) throw new ResponseException($shop_tmp['code'], $shop_tmp['msg']);
                $data['shop'][$i]['shop']   = $shop;
                $data['shop'][$i]['shop']['shopping_score_multi']   = F::numberFormats(F::amountCalc(($shop['shopping_score'] / $shop['goods_amount'])) * F::getShoppingScoreRatio());
                $data['shop'][$i]['shop']['shop_name']      = $shop_tmp['data']['shop_name'];
                $data['shop'][$i]['shop']['seller_user_id'] = $shop_tmp['data']['user_id'];
                $data['shop'][$i]['shop']['shop_id']        = $v;
                $data['shop'][$i]['shop']['shop_logo']      = $shop_tmp['data']['shop_logo'];
                ++$i;
            }
            #   判断是否有data数据
            if ($goods_style_num < State::STATE_NORMAL) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data['score']  = $score;
            $data['amount'] = $amount;
            $data['address']= $address_tmp;
            $data['goods_style_num']    = $goods_style_num;
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 删除商品
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
    public function delete()
    {
        //buyer
        try {
            //伤处购物车商品
            Db::startTrans();
            $ids    = explode(',', rtrim(request()->data['cart_ids'], ','));
//            F::writeLog(request()->data['cart_ids']);
            foreach ($ids as $k => $v) {
                $v  = intval($v);
                if ($v == 0) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作');
                if (false == db('cart', config('database1'))->where(function ($query) use ($v) {
                        $query ->where('user_id', request()->user['user_id'])
                            ->where('cart_id', $v);
                    })->delete()) {
                    throw new ResponseException(Code::CODE_OTHER_FAIL,'删除失败');
                }
            }
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 立即购买
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
    public function buyNow()
    {
        try {
            $ret    = $this->create(true);
            if ($ret['code'] != Code::CODE_SUCCESS) throw new ResponseException($ret['code'], $ret['msg']);
            request()->bind('data', ['cart_ids' => $ret['data']['id']]);
            $data   = $this->confirm();
            if (!isset($data['code'])) $data['id']  = $ret['data']['id'];
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}