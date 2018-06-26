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
use mercury\ResponseException;
use think\Db;

/**
 * Class Cart
 * @package app\api\orders\v1
 *
 * Api 购物车
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
     * 获取购物车列表
     *
     * @param int $user_id
     * @return array
     */
    public function index()
    {
        try {
            $ids    = $this->model
                ->where(['user_id' => request()->user['user_id']])
                ->group('express_id')
                ->column('express_id');

            $data   = [];
            if (!empty($ids)) {
                foreach ($ids as $k => $v) {
                    $data[$k]['shop']   = ['shop_name' => '中睿旗舰店'];
                    $data[$k]['goods']  = $this->model->where(['express_id' => $v])->select();
                }
            }
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 添加商品至购物车
     *
     * @param int $user_id
     * @param int $goods_id
     * @param int $goods_sku_id
     * @param int $num
     * @return array|string
     */
    public function create()
    {
        //buyer
        try {
            //需要获取商品详情
            $goods  = [
                'shop_id'       => 1,
                'goods_name'    => 'xxx',
                'goods_images'  => 'FlhCiuAH7435PdTFNRBZPw28rW3d',
                'goods_sku_name'=> 'www',
                'goods_price'   => 10.9,
                'express_id'    => 2,
                'goods_service_day' => 3,
                'goods_score_multi' => 100,
            ];
            //添加商品至购物车
            $goods_num  = request()->param('goods_num', 1);
            $data   = [
                'user_id'               => request()->user['user_id'],
                'goods_id'              => request()->param('goods_id'),
                'shop_id'               => $goods['shop_id'],
                'goods_num'             => $goods_num,
                'goods_name'            => $goods['goods_name'],
                'goods_images'          => $goods['goods_images'],
                'goods_price'           => F::numberNMulti($goods['goods_price'], $goods_num, 2),
                'goods_sku_id'          => request()->param('goods_sku_id'),
                'goods_sku_name'        => $goods['goods_sku_name'],
                'express_id'            => $goods['express_id'],
                'goods_single_price'    => $goods['goods_price'],
            ];
            $this->model->data($data);
            if (false == $this->model->save($data)) throw new ResponseException(Code::CODE_OTHER_FAIL);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 购物车商品加数量
     *
     * @param int $user_id
     * @param int $cart_id
     * @return array|string
     */
    public function plusNum()
    {
        //buyer
        try {
            //为商品加数量
            $map    = [
                'user_id'   => request()->user['user_id'],
                'cart_id'   => request()->param('cart_id'),
            ];
            $goods  = $this->model->where($map)->field('goods_num,goods_price,goods_single_price')->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');
            if ($goods['goods_num'] >= 999) throw new ResponseException(Code::CODE_OTHER_FAIL, '购物车数量不能大于999件');

            $data   = [
                'goods_num'     => $goods['goods_num']+1,
                'goods_price'   => bcadd($goods['goods_price'], $goods['goods_single_price'], 2),
            ];
            $flag   = $this->model->save($data, $map);

            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 购物车商品减数量
     *
     * @param int $user_id
     * @param int $cart_id
     * @return array|string
     */
    public function lessNum()
    {
        //buyer
        try {
            //为购物车商品减数量
            $map    = [
                'user_id'   => request()->user['user_id'],
                'cart_id'   => request()->param('cart_id'),
            ];
            $goods  = $this->model->where($map)->field('goods_num,goods_price,goods_single_price')->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');
            if ($goods['goods_num'] <= 1) throw new ResponseException(Code::CODE_OTHER_FAIL, '购物车数量不能小于1件');

            $data   = [
                'goods_num'     => $goods['goods_num'] - 1,
                'goods_price'   => bcsub($goods['goods_price'], $goods['goods_single_price'], 2),
            ];
            $flag   = $this->model->save($data, $map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 购物车商品减数量
     *
     * @param int $user_id
     * @param int $cart_id
     * @param int $goods_num
     * @return array|string
     */
    public function setNum()
    {
        //buyer
        try {
            //直接更改购物车数量
            //$this->model->data(['goods_num' => request()->param('goods_num')]);
            $map    = [
                'user_id'   => request()->user['user_id'],
                'cart_id'   => request()->param('cart_id'),
            ];
            $goods  = $this->model->where($map)->field('goods_num,goods_price,goods_single_price')->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');
            $goods_num  = request()->param('goods_num');
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
     * 获取购物车已选择的商品
     *
     * @param int $user_id
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
                if ($goods[$k]) $goods[$k]['score'] = F::numberNMulti($goods[$k]['goods_price']);
            }
            #   判断商品
            if (empty($goods[0])) throw new ResponseException(Code::CODE_NO_CONTENT);
            $express_ids    = array_reduce($goods, function (&$express_ids, $val) {
                $express_ids[$val['express_id']] = $val['cart_id'];
                return $express_ids;
            });
            #   判断运费模板
            if (empty($express_ids)) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = [];
            $i  = 0;
            $goods_style_num    = 0;
            $amount             = 0;
            $score              = 0;
            foreach ($express_ids as $k => $v) {
                #   设置店铺数据
                //$data[$i]['shop']   = ['shop_name' => '中睿旗舰店'];
                $shop['express_amount'] = 0;
                $shop['goods_amount']   = 0;
                $shop['score']          = 0;
                $shop['goods_num']      = 0;
                $shop['amount']         = 0;
                $shop['cart_ids']       = '';
                $data['shop'][$i]['goods']  = array_reduce($goods, function (&$goods, $val) use ($k, &$shop, &$goods_style_num, &$amount, &$score) {
                    if ($val['express_id'] == $k) {
                        $goods[]    = $val;
                        $shop['express_amount'] += 0;
                        $shop['goods_amount']   += $val['goods_price'];
                        $shop['score']          += $val['score'];
                        $shop['goods_num']      += $val['goods_num'];
                        $shop['amount']         += $val['goods_price'];
                        $shop['cart_ids']       .= "{$val['cart_id']},";
                        $score                  += $val['score'];
                        $amount                 += $val['goods_price'];
                        ++$goods_style_num;
                    }
                    return $goods;
                });
                $data['shop'][$i]['shop']   = $shop;
                $data['shop'][$i]['shop']['shop_name']      = '中睿旗舰店';
                $data['shop'][$i]['shop']['seller_user_id'] = 1;
                $data['shop'][$i]['shop']['shop_id']        = 1;
                ++$i;
            }
            #   判断是否有data数据
            if ($goods_style_num < State::STATE_NORMAL) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data['score']  = $score;
            $data['amount'] = $amount;
            $data['goods_style_num']    = $goods_style_num;
            F::writeLog($data['shop']);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 移除购物车
     *
     * @param int $user_id
     * @param int $cart_id
     * @return array|string
     */
    public function delete()
    {
        //buyer
        try {
            //伤处购物车商品
            Db::startTrans();
            $ids    = explode(',', rtrim(request()->param('cart_ids'), ','));
            foreach ($ids as $k => $v) {
                if (false == $this->model->where(function ($query) use ($v) {
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
}