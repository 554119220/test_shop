<?php
namespace app\api\controller\goods\v1;
use app\api\model\goods\GoodsSku;
use app\api\model\goods\UserAttentionGoods;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;

/**
 * Class AttentionGoods
 * @package app\api\controller\user\v1
 *
 * @title 商品收藏
 * 需要加入权重算法
 */
class AttentionGoods
{
    /**
     * @var object UserAttentionGoods
     * @var array $map
     */
    protected $model, $map = [];
    public function __construct()
    {
        $this->model    = new UserAttentionGoods();
        $this->map['user_id']   = request()->user['user_id'];
    }

    /**
     * @title 收藏列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id|int|true|0|-|用户id|
     * |p|int|false|1|-|第几页，默认1|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function index()
    {
        try {
            $this->map['user_attention_goods_state'] = State::STATE_NORMAL;
            $data   = F::dataList($this->model, [
                'where' => $this->map,
                'order' => 'user_attention_id desc',
                'relation'  => 'sku,goods',
                'page'  => request()->param('page', 1)
            ]);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 收藏商品
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id|int|true|0|-|用户id|
     * |sku_id|int|true|1|-|商品库存id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data":{},
            "msg":"请求成功",
            "info":"success",
            "code":20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function create()
    {
        try {
            $model  = new GoodsSku();
            $goods  = $model->relation('goods')->where('goods_sku_id', request()->data['sku_id'])->find();
            if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在');

            #   判断用户是否已经关注过当前商品
            $this->map['goods_id']  = $goods['goods_id'];
            $attention  = $this->model->where($this->map)->column('user_attention_goods_state');
            $is_attention   = State::STATE_NORMAL;
            if ($attention) {
                $user_attention_goods_state = $attention[0];
                switch ($user_attention_goods_state) {
                    case State::STATE_NORMAL:
                        $state  = State::STATE_CANCEL;
                        $is_attention   = State::STATE_DISABLED;
                        break;
                    default :
                        $state  = State::STATE_NORMAL;
                }
                $flag   = $this->model->save(['user_attention_goods_state' => $state], $this->map);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败，请稍后重试');
                return [ 'is_attention' => $is_attention ];
            }

            $data   = [
                'goods_id'      => $goods['goods_id'],
                'goods_price'   => $goods['goods_sku_price'],
                'user_id'       => request()->user['user_id'],
                'goods_sku_id'  => request()->data['sku_id'],
                'shop_id'       => $goods['goods']['shop_id'],
                'seller_user_id'=> $goods['goods']['seller_user_id'],
            ];

            $flag   = $this->model->save($data);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '收藏失败，请稍后重试');
            return [ 'is_attention' => $is_attention ];
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 删除收藏
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id|int|true|0|-|用户id|
     * |id|int|true|1|-|收藏id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data":{},
            "msg":"请求成功",
            "info":"success",
            "code":20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function delete()
    {
        try {
            $this->map['user_attention_id'] = request()->data['id'];
            $flag   = $this->model->where($this->map)->delete();
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '删除失败，请稍后再试');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 是否收藏有该商品
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id|int|true|0|-|用户id|
     * |sku_id|int|true|1|-|商品库存id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data":{},
            "msg":"请求成功",
            "info":"success",
            "code":20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    function have(){
        try {
            # get param
            $user_id = intval(request()->user['user_id'] ?? 0);
            // dump(request()->user);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # get data
            $param = [
                'where' => [
                    'user_attention_goods_state'    => State::STATE_NORMAL,
                    'user_id'                       => $user_id,
                    'goods_sku_id'                  => intval(request()->data['sku_id']),
                ],
                'field' => 'user_attention_id',
            ];
            if ( empty( F::dataDetail(F::mApi('goods', 'UserAttentionGoods'), $param ) ) ){
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }
}