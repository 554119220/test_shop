<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/18 0018
 * Time: 10:02
 */

namespace app\api\controller\goods\v1;

use app\api\model\goods\GoodsCommentContent;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\ResponseException;
use mercury\constants\State;
use think\Db;

/**
 * Class UserComment
 * @package app\api\controller\goods\v1
 * @title 用户评价
 */
class UserComment
{
    protected $model;

    public function __construct()
    {
        $this->model    = new \app\api\model\goods\GoodsComment();
    }

    /**
     * @title 评价列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|openid|
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
            $map    = [
                'buyer_user_id' => request()->user['user_id'],
                'goods_comment_is_append'   => State::STATE_DISABLED,   #   取出非追加的评价
            ];
            $data   = F::pageList($this->model, [
                'where' => $map,
                'page'  => request()->param('page', 1),
                'order' => 'goods_comment_create_time desc',
                'relation' => 'goods_comment_content',
            ]);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $images_domain  = F::getImagesDomain();
            foreach ($data['data'] as &$v) {
                $v['sku']   = db('orders_goods')->where(['orders_goods_id' => $v['orders_goods_id']])->cache(true)->find();
                if (!empty($v['sku'])) $v['sku']['goods_images']    = "{$images_domain}{$v['sku']['goods_images']}";
                $v['append']    = F::dataDetail($this->model, [
                    'where' => [
                        'goods_comment_is_append'   => $v['goods_comment_id'],
                    ],
                    'relation'  => 'goods_comment_content',
                ]);
                $fraction   = round($v['goods_comment_service_fraction'] +
                    $v['goods_comment_logistics_fraction'] +
                    $v['goods_comment_description_fraction'], 0);
                $v['fraction'] = (int) ceil($fraction / 3);
                $v['fraction_poor']    = 5 - $v['fraction'];
            }
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 评价详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|openid|
     * |id|int|true|-|-|评价id|
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
    public function detail ()
    {
        try {
            # 获取
            $user_id    = intval(request()->user['user_id'] ?? 0);
            $id = intval(request()->data['id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '请先登录' );
            }
            $param['where'] = [
                'goods_comment_id'  => $id,
                'buyer_user_id'    => $user_id,
            ];
            $param['relation'] = 'user,sku,orders_goods,goods_comment_content';
            # 查找
            $detail = F::dataDetail('\\app\\api\\model\\goods\\GoodsComment', $param);
            if ( empty($detail) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 处理
            // $detail = $this->dataDeal([$detail])[0] ?? [];
            # ...
            return $detail;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 修改评价
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
    public function modify()
    {
        try {
            Db::startTrans();
            $time       = time();
            $user_id    = request()->user['user_id'];
            $comment_id = request()->data['id'];
            $param      = request()->data;
            $map    = [
                'buyer_user_id'     => $user_id,
                'goods_comment_id'  => $comment_id,
                'goods_comment_is_effect'   => State::STATE_DISABLED,   #   未生效评价及未修改评价才能修改
                'goods_comment_is_change'   => State::STATE_DISABLED
            ];
            #   判断是否可追平
            $comment    = F::dataDetail($this->model, ['where' => $map]);
            if (!$comment) throw new ResponseException(Code::CODE_OTHER_FAIL, '当前评价不存在或不可修改');

            # 关联插入数据
            $commentData = [
                'goods_comment_service_fraction'        => (int)$param['service_fraction'] ?? 5,
                'goods_comment_logistics_fraction'      => (int)$param['logistics_fraction'] ?? 5,
                'goods_comment_description_fraction'    => (int)$param['description_fraction'] ?? 5,
                'goods_comment_evaluation'              => $param['evaluation'] ?? State::STATE_NORMAL,
                'goods_comment_is_anonymous'            => $param['is_anonymous'] ?? State::STATE_NORMAL,
                'goods_comment_is_append'               => State::STATE_DISABLED,
                'goods_comment_is_change'               => State::STATE_NORMAL,
                'goods_comment_change_time'             => $time,
                'goods_comment_is_effect'               => State::STATE_NORMAL,
                'goods_comment_effect_time'             => $time
            ];
            #   修改的直接生效
            $table_prefix   = config('database.prefix');
            switch ($commentData['goods_comment_evaluation']) {
                case State::STATE_COMMENT_MIDDLE:
                    $key    = 'goods_comment_middle_num';
                    $level_score    = State::STATE_COMMENT_MIDDLE;
                    break;
                case State::STATE_COMMENT_POOR:
                    $key    = 'goods_comment_poor_num';
                    $level_score    = State::STATE_COMMENT_POOR;
                    break;
                default:
                    $key    = 'goods_comment_good_num';
                    $level_score    = State::STATE_COMMENT_GOOD;
                    break;
            }
            $sql    = "UPDATE `{$table_prefix}shop` SET 
`goods_comment_num` = goods_comment_num + 1, `{$key}` = {$key} + 1, `shop_level_score` = shop_level_score + {$level_score}, `shop_update_time` = {$time}
WHERE `shop_id` = {$comment['shop_id']}";
            if (false == Db::execute($sql)) throw new ResponseException(Code::CODE_OTHER_FAIL, '更新商家信息失败');

            $contentData = [
                'goods_comment_content' => $param['content'],
                'goods_comment_images' => $param['images'] ?? '',
            ];
            # 是否有图
            if ( $contentData['goods_comment_images'] ) {
                $commentData['goods_comment_have_images'] = State::STATE_NORMAL;
            }
            # 添加记录
            $Comment = $this->model;
            $Comment->data($commentData);
            $Content = new GoodsCommentContent();
            //$Comment->startTrans();
            if (false == $this->model->save($Comment->getData(), $map))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '修改评价失败,请稍后重试');

            if (false == $Content->save($contentData, ['goods_comment_id' => $comment_id]))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '修改评价内容失败，请稍后重试！');

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 追加评价
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
    public function append()
    {
        try {
            Db::startTrans();
            $user_id    = request()->user['user_id'];
            $comment_id = request()->data['id'];
            $param      = request()->data;
            $map    = [
                'buyer_user_id'     => $user_id,
                'goods_comment_id'  => $comment_id,
                'goods_comment_is_effect'   => State::STATE_NORMAL, #   已生效评价才可追平
            ];
            #   判断是否可追平
            $comment    = F::dataDetail($this->model, ['where' => $map]);
            if (!$comment) throw new ResponseException(Code::CODE_OTHER_FAIL, '当前评价不存在或不可追平');
            #   判断是否已追平
            $is_append  = db('goods_comment')->where($map)->where(['goods_comment_is_append' => State::STATE_NORMAL])->value('goods_comment_id');
            if ($is_append) throw new ResponseException(Code::CODE_OTHER_FAIL, '您已追平过，不可再次追加');

            # 关联插入数据
            $commentData = [
                'buyer_user_id'     => (int)$comment['buyer_user_id'],
                'shop_id'           => (int)$comment['shop_id'],
                'seller_user_id'    => (int)$comment['seller_user_id'],
                'orders_shop_id'    => (int)$comment['orders_shop_id'],
                'orders_shop_no'    => (string)$comment['orders_shop_no'],
                'goods_id'          => (int)$comment['goods_id'],
                'goods_sku_id'      => (int)$comment['goods_sku_id'],
                'orders_goods_id'   => (int)$comment['orders_goods_id'],
                'goods_comment_service_fraction'        => (int)$comment['goods_comment_service_fraction'],
                'goods_comment_logistics_fraction'      => (int)$comment['goods_comment_logistics_fraction'],
                'goods_comment_description_fraction'    => (int)$comment['goods_comment_description_fraction'],
                'goods_comment_evaluation'              => $comment['goods_comment_evaluation'],
                'goods_comment_is_anonymous'            => $comment['goods_comment_is_anonymous'],
                'goods_comment_is_append'               => $comment_id,
                'goods_comment_is_effect'               => State::STATE_NORMAL,
                'goods_comment_effect_time'             => time()
            ];

            $contentData = [
                'goods_comment_content' => $param['content'],
                'goods_comment_images' => $param['images'] ?? '',
            ];
            # 是否有图
            if ( $contentData['goods_comment_images'] ) {
                $commentData['goods_comment_have_images'] = State::STATE_NORMAL;
            }
            # 添加记录
            $Comment = $this->model;
            $Comment->data($commentData);
            $Content = new GoodsCommentContent($contentData);
            //$Comment->startTrans();
            if (false == $this->model->allowField(true)->save())
                throw new ResponseException(Code::CODE_OTHER_FAIL, '追加评价失败,请稍后重试');
            if (false == $Comment->goodsCommentContent()->save($Content))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '追加评价内容失败，请稍后重试！');

            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
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
                $list[$key]['user']['user_nick'] = F::hidden_str($nick,0,2);
            }
            if ($nick == '') {
                $list[$key]['user']['user_nick'] = '未知买家';
            }
            $fraction   = round($value['goods_comment_service_fraction'] +
                $value['goods_comment_logistics_fraction'] +
                $value['goods_comment_description_fraction'], 0);
            $list[$key]['fraction'] = round($fraction / 3, 0);
            $list[$key]['fraction_poor']    = $list[$key]['fraction'] - 5;
            # 头像
            $list[$key]['user']['user_avatar'] = F::thumbnail($value['user']['user_avatar'] ?? '',40);

        }
        return $list;
    }
}