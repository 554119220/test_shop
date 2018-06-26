<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27 0027
 * Time: 17:45
 */

namespace app\api\controller\pay\v1;


use app\api\model\orders\OrdersShop;
use app\common\traits\F;
use lbzy\sdk\erp\Pay;
use mercury\async\Beanstalkd;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\NoPre;
use mercury\constants\State;
use mercury\ResponseException;
use think\Exception;

/**
 * Class Erp
 * @package app\api\controller\pay\v1
 * @title ERP订单付款
 */
class Erp
{
    protected $key = 'orders_shop_no', $model, $no, $callback_url = '/pay/single?orders_no=', $redis;
    public function __construct()
    {
        $this->no       = request()->data['shop_no'];
        $this->callback_url .= $this->no;
        if (strpos(request()->data['shop_no'], NoPre::NO_PRE_BY_SHOP_ORDERS) !== State::STATE_DISABLED) {
            #   总订单
            $this->key    = 'orders_no';
            $this->callback_url = "/pay/multiple?orders_no={$this->no}";
        }
        $this->model    = new OrdersShop();
        $this->redis    = F::redis();
    }

    /**
     * @title 订单支付
     * @request |参数名|参数类型|是否必传|示例值|更多限制|参数描述|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response |参数名|数据类型|返回数据|
     * |---|---|---|
     * |name|string|values|
     * @response_example `you are json code`
     * @description > you are api description
     * @return array
     */
    public function index()
    {
        try {
            $key    = F::getCacheName(Cache::IS_PINGING . $this->no);
            $key1    = F::getCacheName(Cache::IS_IN_ERP_PAY . $this->no);
            if ($this->redis->exists($key)) {
                throw new ResponseException(Code::CODE_OTHER_FAIL, '正在付款中，请稍后再试');
            } else {
                #   1分钟
                $this->redis->setex($key, 60, 1);
                #   15分钟
                $this->redis->setex($key1, 900, 1);
            }
            $orders = $this->model->where($this->key, $this->no)
                ->where('orders_shop_state', State::STATE_NORMAL)
                ->field('SUM(orders_shop_edit_amount) as amount, SUM(goods_style_num) as style_num, SUM(goods_count_num) as goods_num, SUM(orders_shop_can_use_shopping_score) as shopping_score,orders_shop_next_time')
                ->find();

            if (!$orders) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在或已付款');
            $orders = $orders->toArray();

            if (!$orders['amount']) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单不存在或已付款');

            $user = db('user')->where(['user_id' => request()->user['user_id']])->field('user_username,openid')->find();
            if (!$user) throw new ResponseException(Code::CODE_OTHER_FAIL, '用户不存在！');

            #   库存判断
            $goods  = db('orders_goods')->where([$this->key => $this->no])->field('goods_sku_id,orders_goods_num,goods_name,goods_id')->select();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '订单中无商品');
            foreach ($goods as $v) {
                $sub_goods_name = mb_substr($v['goods_name'], 0, 6);
                if (!db('goods')->where(['goods_id' => $v['goods_id'], 'goods_state' => State::STATE_NORMAL])->value('goods_id'))
                    throw new ResponseException(Code::CODE_OTHER_FAIL, "{$sub_goods_name}不存在或已下架");
                if (!db('goods_sku')
                    ->where(['goods_sku_id' => $v['goods_sku_id'], 'goods_sku_num' => ['egt', $v['orders_goods_num']]])
                    ->value('goods_sku_id')) {
                    throw new ResponseException(Code::CODE_OTHER_FAIL, "{$sub_goods_name}库存不足");
                }
            }

            #   +900秒,如果订单距离关闭时间低于30分钟的时候则设置为30分钟
            $next_time  = 1800;
            if (strtotime($orders['orders_shop_next_time']) - time() < $next_time) {
                $this->model
                    ->save(['orders_shop_next_time' => time() + $next_time], [$this->key => $this->no, 'orders_shop_state' => State::STATE_NORMAL]);
                $orders_shop    = F::dataAll($this->model, [
                    'where' => [$this->key => $this->no],
                    'field' => 'orders_shop_id,orders_shop_no'
                ]);
                if ($orders_shop) {
                    $beanstalk  = new Beanstalkd('orders_close');
                    foreach ($orders_shop as $v) {
                        $beanstalk->ordersPut($v['orders_shop_id'], $v['orders_shop_no'], $next_time);
                    }
                }
            }

            $goods_name  = db('orders_goods')->where([$this->key => $this->no])->value('goods_name');

            $subject= trim(mb_substr("{$goods_name}等共计{$orders['style_num']}款商品.", 0, 99));

            $memo   = trim(mb_substr("{$goods_name}等共计{$orders['style_num']}款{$orders['goods_num']}件商品.", 0, 199));

            $data   = [
                #   买家openid
                'buyer_openid'  => $user['openid'],
//                'seller_openid' => '',
                #   服务类型，1=即时到账，2=担保交易
                //'service'       => State::STATE_NORMAL,
                'service'       => 2,
                #   商家订单号
                'out_order_no'  => $this->no,
                #   商品标题，30个字以内
                'subject'       => $subject,
                #   商品描述，100个字以内
                'memo'          => $memo,
                #   订单金额（应付金额）
                'amount'        => F::amountCalc($orders['amount']),
                #   最多可使用购物积分数量
                'max_use_consume'   => $orders['shopping_score'],
                #   同步返回地址
                'return_url'    => F::domain('wap', "/pay/payRefund?shop_no={$this->no}"),
                #   异步返回地址
                'notify_url'    => F::domain('www', '/notify'),
                #   中断支付返回地址
                'callback_url'  => F::domain('wap', "/pay/payRefund?shop_no={$this->no}"),
                #   WAP是否显示顶部bar，1=不显示
                'nobar'         => State::STATE_NORMAL
            ];

            #   单订单
            if ($this->key == 'orders_shop_no') {
                $data['group_order_no'] = db('orders_shop')->where(['orders_shop_no' => $this->no])->value('orders_no');
            }

            $ret    = Pay::instance()->createOrders($data);
            if ($ret['code'] != State::STATE_NORMAL) throw new ResponseException(Code::CODE_OTHER_FAIL, $ret['msg']);
            $orders_data    = [
                'code'  => $ret['data']['order']['code'],
                'openid'=> $user['openid'],
            ];
            $ret    = Pay::instance()->getUrl($orders_data);
            if ($ret['code'] != State::STATE_NORMAL) throw new ResponseException(Code::CODE_OTHER_FAIL, $ret['msg']);
            return [
                'url'   => $ret['data']['url'],
            ];
        } catch (ResponseException $e) {
            return $e->getData();
        } catch (Exception $e) {
            return [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode(),
                'trace' => $e->getTrace()
            ];
        }
    }
}