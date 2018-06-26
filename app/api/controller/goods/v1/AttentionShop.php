<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/29 0029
 * Time: 9:18
 */

namespace app\api\controller\goods\v1;

use app\api\model\goods\Shop;
use app\api\model\goods\UserAttentionShop;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;

/**
 * Class AttentionShop
 * @package app\api\controller\user\v1
 *
 * @title 店铺收藏
 */
class AttentionShop
{
    /**
     * @var object UserAttentionShop
     * @var array $map
     */
    protected $model, $map = [];
    public function __construct()
    {
        $this->model    = new UserAttentionShop();
        $this->map['user_id']   = request()->user['user_id'];
    }

    /**
     * @title 收藏列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|openid|
     * |p|int|false|1|-|分页|
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
            $this->map['user_attention_shop_state'] = State::STATE_NORMAL;
            $data   = F::dataList($this->model, [
                'where' => $this->map,
                'relation' => 'goods,shop',
                'order' => 'user_attention_id desc',
                'page'  => request()->param('page', 1)
            ]);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            foreach ($data as &$v) {
                foreach ($v['goods'] as &$val) {
                    $val['sku_id']   = db('goods_sku')->where('goods_id', $val['goods_id'])
                        ->order('goods_sku_price asc')->cache(3600)
                        ->limit(1)->value('goods_sku_id');
                }
            }
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 收藏店铺
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|openid|
     * |shop_id|int|true|-|-|店铺id|
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
    public function create()
    {
        try {
            $model  = new Shop();
            $shop   = $model->where('shop_id', request()->data['shop_id'])->where('shop_state', State::STATE_NORMAL)->find();
            if (!$shop) throw new ResponseException(Code::CODE_OTHER_FAIL, '店铺不存在或已关闭');
            #   判断用户是否有收藏
            $this->map['shop_id']   = request()->data['shop_id'];
            $attention  = $this->model->where($this->map)->column('user_attention_shop_state');
            if ($attention) {
                $user_attention_shop_state  = $attention[0];
                switch ($user_attention_shop_state) {
                    case State::STATE_NORMAL:
                        $state  = State::STATE_CANCEL;
                        break;
                    default:
                        $state  = State::STATE_NORMAL;
                }
                $flag   = $this->model->save(['user_attention_shop_state' => $state], $this->map);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败，请稍后重试');
                return Code::CODE_SUCCESS;
            }

            $data   = [
                'shop_id'   => request()->data['shop_id'],
                'user_id'   => $this->map['user_id'],
            ];
            $flag   = $this->model->save($data);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '关注商家失败，请稍后重试');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 删除收藏
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|openid|
     * |id|int|true|-|-|id|
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
        try {
            $this->map['user_attention_id'] = request()->data['id'];
            $flag   = $this->model->where($this->map)->delete();
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '删除失败，请稍后重试');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 判断是否已收藏
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|openid|
     * |shop_id|int|true|-|-|店铺id|
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
    public function isAttention()
    {
        try {
            $this->map['shop_id']   = request()->data['shop_id'];
            $this->map['user_attention_shop_state'] = State::STATE_NORMAL;
            $data = $this->model->where($this->map)->column('user_attention_shop_state,shop_id,user_id');
            if(empty($data)){
                throw new ResponseException(Code::CODE_NO_CONTENT);
            }
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 取消收藏
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|openid|
     * |shop_id|int|true|-|-|店铺id|
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
    public function cancel()
    {
        try {
            $this->map['shop_id']   = request()->data['shop_id'];
            $state  = State::STATE_CANCEL;
            $flag   = $this->model->save(['user_attention_shop_state' => $state], $this->map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败，请稍后重试');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}