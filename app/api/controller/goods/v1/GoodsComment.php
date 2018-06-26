<?php
namespace app\api\controller\goods\v1;
use app\common\traits\F;
use mercury\async\Beanstalkd;
use mercury\constants\state\Times;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\GoodsComment as Model;
use app\api\model\goods\GoodsCommentContent;
use think\Db;

/**
 * @title 分类
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class GoodsComment
{
    private $isAppend = [0,1];

    
    /**
     * @title 评价列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |goods_id|int|true|0|---|商品id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function index ()
    {
        try {
            # ...
            $goods_id = intval(request()->data['goods_id'] ?? 0);
            $map = [
                'goods_id'                  => $goods_id,
                'goods_comment_is_effect'   => State::STATE_NORMAL,
                'goods_comment_is_append'   => State::STATE_DISABLED,
            ];
            # 条件
            $evaluation = request()->data['evaluation'] ?? '';
            switch (request()->data['state'] ?? null) {
                case 'images':
                    $map['goods_comment_have_images'] = 1;
                    break;
                case 'good':
                    $map['goods_comment_evaluation'] = 1;
                    break;
                case 'middle':
                    $map['goods_comment_evaluation'] = 0;
                    break;
                case 'poor':
                    $map['goods_comment_evaluation'] = -1;
                    break;
                default:
                    # code...
                    break;
            }
            # 获取数据
            $list = Fun::dataList( Fun::mApi('goods','GoodsComment'), [
                'relation'      => 'user,goods_comment_content',
                'limit'         => intval(request()->data['pagesize'] ?? 10),
                'page'          => intval(request()->data['page'] ?? 1),
                'order'         => 'goods_comment_create_time desc',
                'where'         => $map,
            ]);
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            // dump($list);
            # 数据处理
            $list = $this->dataDeal($list);
            # ...
            return [ 'data' => $list ];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 评价统计
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |goods_id|int|true|0|---|商品id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function statistics ()
    {
        try {
            # ...
            $goods_id = intval(request()->data['goods_id'] ?? 0);
            # 统计
            $cache_time = \mercury\constants\Common::TIME_FIVE_MINUTE;
            $mapGood    = [ 'goods_id' => $goods_id , 'goods_comment_is_effect' => State::STATE_NORMAL, 'goods_comment_is_append' => State::STATE_DISABLED, 'goods_comment_evaluation'    => State::STATE_COMMENT_GOOD ];
            $mapMiddle  = [ 'goods_id' => $goods_id , 'goods_comment_is_effect' => State::STATE_NORMAL, 'goods_comment_is_append' => State::STATE_DISABLED, 'goods_comment_evaluation'    => State::STATE_COMMENT_MIDDLE ];
            $mapPoor    = [ 'goods_id' => $goods_id , 'goods_comment_is_effect' => State::STATE_NORMAL, 'goods_comment_is_append' => State::STATE_DISABLED, 'goods_comment_evaluation'    => State::STATE_COMMENT_POOR ];
            $mapImages  = [ 'goods_id' => $goods_id , 'goods_comment_is_effect' => State::STATE_NORMAL, 'goods_comment_is_append' => State::STATE_DISABLED, 'goods_comment_have_images'   => State::STATE_NORMAL ];

            $statistics['good']     = Model::where($mapGood)->cache(true, $cache_time)->count();
            $statistics['middle']   = Model::where($mapMiddle)->cache(true, $cache_time)->count();
            $statistics['poor']     = Model::where($mapPoor)->cache(true, $cache_time)->count();
            $statistics['images']   = Model::where($mapImages)->cache(true, $cache_time)->count();
            $statistics['all']      = $statistics['good'] + $statistics['middle'] + $statistics['poor'];
            # ...
            return $statistics;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 评价详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |goods_comment_id|int|true|0|---|评价id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function detail ()
    {
        try {
            # 获取
            $id = intval(request()->data['goods_comment_id'] ?? 0);
            $param['where'] = [ 'goods_comment_id' => $id ];
            $param['relation'] = 'user';
            # 查找
            $detail = Fun::dataDetail('\\app\\api\\model\\goods\\GoodsComment', $param);
            if ( empty($detail) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 处理
            $detail = $this->dataDeal([$detail])[0] ?? [];
            # ...
            return $detail;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 查询的数据处理
     * @param  [array] $list [description]
     * @return [type]       [description]
     */
    private function dataDeal ($list)
    {
        foreach ($list as $key => $value) {
            # 用户昵称
            // dump($value);exit;
            $nick = $value['user']['user_nick'] ?? '';
            if ( $value['goods_comment_is_anonymous'] && $nick ) {
                $list[$key]['user']['user_nick'] = Fun::hidden_str($nick,1,-1);
                // $list[$key]['user']['user_nick'] = '匿名';
            }
            if ($nick == '') {
                $list[$key]['user']['user_nick'] = '匿名';
            }
            $fraction   = round($value['goods_comment_service_fraction'] +
                $value['goods_comment_logistics_fraction'] +
                $value['goods_comment_description_fraction'], 0);
            $list[$key]['fraction'] = round($fraction / 3, 0);
            $list[$key]['fraction_poor']    = $list[$key]['fraction'] - 5;
            # 头像
            $list[$key]['user']['user_avatar'] = Fun::getImages($value['user']['user_avatar'] ?? '');
            # 追加评价
            // dump($value);
            $append = Fun::dataDetail(Fun::mApi('goods','GoodsComment'),[
                'relation'  => 'goods_comment_content',
                'where'     => [
                    'goods_comment_is_append'   => $value['goods_comment_id'],
                ],
            ]);
            $list[$key]['append'] = $append ? $append : '';
        }
        return $list;
    }

    /**
     * @title 创建评价
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id                             |int    |true   |0  |---    |用户id|
     * |orders_shop_no                      |string |true   |0  |---    |商家订单号|
     * |goods_sku_id                        |int    |true   |0  |---    |评价的商品库存id|
     * |goods_comment_service_fraction      |float  |true   |0  |---    |服务分数|
     * |goods_comment_logistics_fraction    |float  |true   |0  |---    |物流分数|
     * |goods_comment_description_fraction  |float  |true   |0  |---    |商品描述|
     * |goods_comment_evaluation            |int    |true   |0  |---    |评价-1差评，0中评，1好评|
     * |goods_commnet_is_anonymous          |int    |true   |0  |---    |是否匿名|
     * |goods_comment_is_effect             |int    |false  |0  |---    |是否生效|
     * |goods_comment_effect_time           |int    |false  |0  |---    |生效时间|
     * |goods_comment_is_append             |int    |true   |0  |---    |是否追加|
     * |goods_comment_content               |string |true   |0  |---    |评价内容|
     * |goods_comment_images                |string |true   |0  |---    |评价图片多个用逗号隔开|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function create()
    {
        try {
            Db::startTrans();
            $time  = time();
            $param = request()->data;
            // dump($param);dump(request()->param());exit;
            $user_id = intval(request()->user['user_id'] ?? 0);
            
            // dump(request()->user);exit;
            // $user_id = 161;
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # 检测是否存在商家订单号
            $orders_shop = Fun::dataDetail(Fun::mApi('orders', 'OrdersShop'), [
                'where' => [
                    'orders_shop_no'    => $param['shop_no'],
                    'buyer_user_id'     => $user_id,
                    'orders_shop_state' => State::STATE_ORDERS_RECEIVE
                ],
            ]);
            // dump($orders_shop);exit;
            if ( empty($orders_shop) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL , '订单不存在或已评价');
            }
            // dump($orders_shop);exit;
            # 是否存在评价商品
            $where = $orders_goods_where =  [
                'goods_sku_id' => $param['sku_id'],
                'orders_shop_no' => $param['shop_no'],
                'orders_is_comment' => State::STATE_DISABLED
            ];
            $orders_goods = db('orders_goods')->field('*')->where($where)->find();
            if ( empty($orders_goods) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL , '评价的商品不存在或已评价');
            }
            # 是否已评价过
            $is_append = (int) $param['is_append'];
            if ( false == in_array( $is_append ,$this->isAppend ) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            $where = [
                'goods_sku_id'              => (int)$param['sku_id'],
                'orders_shop_no'            => (string)$param['shop_no'],
                'goods_comment_is_append'   => $is_append,
            ];
            $comment = db('goods_comment')->field('goods_comment_id')->where($where)->find();
            if ( $comment ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL , '您已评价过，请勿重复评价');
            }
            # 关联插入数据
            $commentData = [
                'buyer_user_id'     => (int)$orders_shop['buyer_user_id'],
                'shop_id'           => (int)$orders_shop['shop_id'],
                'seller_user_id'    => (int)$orders_shop['seller_user_id'],
                'orders_shop_id'    => (int)$orders_shop['orders_shop_id'],
                'orders_shop_no'    => (string)$param['shop_no'],

                'goods_id'          => (int)$orders_goods['goods_id'],
                'goods_sku_id'      => (int)$param['sku_id'],
                'orders_goods_id'   => (int)$orders_goods['orders_goods_id'],
                'goods_comment_service_fraction'        => (int)$param['service_fraction'] ?? 5,
                'goods_comment_logistics_fraction'      => (int)$param['logistics_fraction'] ?? 5,
                'goods_comment_description_fraction'    => (int)$param['description_fraction'] ?? 5,
                'goods_comment_evaluation'              => $param['evaluation'] ?? State::STATE_NORMAL,
                'goods_comment_is_anonymous'            => $param['is_anonymous'] ?? State::STATE_NORMAL,
                'goods_comment_is_append'               => $is_append,
            ];
            #   如果是好评则立即生效
            $table_prefix   = config('database.prefix');
            if ($commentData['goods_comment_evaluation'] == State::STATE_NORMAL) {
                $commentData['goods_comment_is_effect']     = State::STATE_NORMAL;
                $contentData['goods_comment_effect_time']   = $time;
                $sql    = "UPDATE `{$table_prefix}shop` SET 
`goods_comment_num` = goods_comment_num + 1, `goods_comment_good_num` = goods_comment_good_num + 1, `shop_level_score` = shop_level_score + 1, `shop_update_time` = {$time}
WHERE `shop_id` = {$orders_shop['shop_id']}";
                if (false == Db::execute($sql)) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新商家信息失败');
            } else {
                #   30天后生效
                $commentData['goods_comment_effect_time']   = Times::times(Times::TIME_COMMENT_EFFECT);
            }
            $contentData = [
                'goods_comment_content' => $param['content'],
                'goods_comment_images' => $param['images'] ?? '',
            ];
            # 是否有图
            if ( $contentData['goods_comment_images'] ) {
                $commentData['goods_comment_have_images'] = State::STATE_NORMAL;
            }
            # 添加记录
            $Comment = new Model($commentData);
            $Content = new GoodsCommentContent($contentData);
            //$Comment->startTrans();
            if (false == $Comment->allowField(true)->save())
                throw new ResponseException(Code::CODE_OTHER_FAIL, '评价失败,请稍后重试');
            $comment_id = $Comment->getLastInsID();
            if (false == $Comment->goodsCommentContent()->save($Content))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '评价失败，请稍后重试！');

            #   将订单中的商品设置为已评价状态
            $orders_goods_data  = [
                'orders_is_comment'     => State::STATE_NORMAL,
                'orders_comment_time'   => $time,
            ];
            if (false == db('orders_goods')->where($orders_goods_where)->update($orders_goods_data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '评价失败，请稍后重试。');

            $orders_shop_data['orders_shop_state']  = State::STATE_ORDERS_RECEIVE;
            #   如果当前订单商品都已评价，则把订单改为已评价
            $where  = [
                'orders_shop_no' => $param['shop_no'],
                'orders_is_comment' => State::STATE_DISABLED,
                'goods_refund_num'  => ['gt', State::STATE_DISABLED]
            ];
            if (State::STATE_DISABLED == db('orders_goods')->where($where)->count()) {
                $orders_shop_data   = [
                    'orders_shop_comment_time'  => $time,
                    'orders_shop_state'         => State::STATE_ORDERS_COMMIT
                ];
                $where  = [
                    'orders_shop_no'    => $param['shop_no']
                ];
                if (false == db('orders_shop')->where($where)->update($orders_shop_data))
                    throw new ResponseException(Code::CODE_OTHER_FAIL, '修改订单失败，请稍后重试！');
            }
            #   为商品增加评价数量,好评的时候才做处理
            if (!$is_append && $commentData['goods_comment_evaluation'] == State::STATE_COMMENT_GOOD) {
                switch ($commentData['goods_comment_evaluation']) {
                    case State::STATE_COMMENT_GOOD :
                        $comment_key    = 'goods_comment_good_num';
                        break;
                    case State::STATE_COMMENT_MIDDLE:
                        $comment_key    = 'goods_comment_middle_num';
                        break;
                    case State::STATE_COMMENT_POOR:
                        $comment_key    = 'goods_comment_poor_num';
                }
                $sql    = "UPDATE `{$table_prefix}goods` SET `goods_comment_num` = goods_comment_num + 1, `{$comment_key}` = {$comment_key} + 1, `goods_update_time` = {$time}
WHERE `goods_id` = {$orders_goods['goods_id']}";
                if (false == db()->execute($sql)) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品增加评价数量失败');
            }
            #   创建订单日志
            $logs   = [
                'orders_logs_title' => '买家评价',
                'orders_shop_state' => $orders_shop_data['orders_shop_state'],
                'orders_shop_id'    => $orders_shop['orders_shop_id'],
                'orders_shop_no'    => $orders_shop['orders_shop_no'],
                'orders_logs_is_display'    => State::STATE_NORMAL,
                'orders_logs_create_time'   => $time,
            ];
            if (false == db('orders_logs')->insert($logs)) throw new ResponseException(Code::CODE_OTHER_FAIL, '新增日志失败');

            #   写入队列,中差评写入队列延时生效
            if ($commentData['goods_comment_evaluation'] != State::STATE_COMMENT_GOOD) {
                Beanstalkd::getInstance('comment_effect')->commentEffectPut($comment_id, Times::times(Times::TIME_COMMENT_EFFECT, true));
            }
            # ...
            $Comment->commit();
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            Db::rollback();
            return $e->getData();
        }
    }

}