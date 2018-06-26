<?php

namespace app\api\controller\goods\v1;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\ResponseException;
use mercury\constants\State;
/**
 * @title 商家-评论
 * Class AttentionGoods
 *
 * 需要加入权重算法
 */
class Comment
{
    protected $model;
    public function __construct()
    {
        $this->model    = new \app\api\model\goods\GoodsComment();
    }

    /**
     * @title 买家评价列表
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
            $map    = [
                'seller_user_id'    => request()->user['user_id'],
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
            }
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 评价回复
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id|int|true|0|-|用户id|
     * |goods_comment_id|int|true|-|-|商品评价id|
     * |goods_comment_reply_content|int|true|-|-|回复内容|
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
    public function reply()
    {
        try {
            # param
            $param = request()->data;
            $user_id    = intval(request()->user['user_id'] ?? 0);
            $id         = intval(request()->data['goods_comment_id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '请先登录' );
            }
            # have data
            $comment = F::dataDetail(F::mApi('goods', 'GoodsComment'), [
                'where' => [
                    'goods_comment_id'  => $id,
                    'seller_user_id'    => $user_id,
                ],
                'relation' => 'goods_comment_content',
            ]);
            // dump($comment);exit;
            if ( empty($comment) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '评价不存在' );
            }
            if ( $comment['goods_comment_content']['goods_comment_reply_content'] != '' ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '此评价已回复' );
            }
            # update
            $map['goods_comment_id']            = $id;
            $param['goods_comment_reply_time']  = time();
            $allow = [ 'goods_comment_reply_time', 'goods_comment_reply_content' ];
            if ( false == F::mApi('goods','GoodsCommentContent')->allowField($allow)->save($param,$map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '回复失败' );
            }
            #...
            return Code::CODE_SUCCESS;
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
     * |user_id|int|true|0|-|用户id|
     * |goods_comment_id|int|true|-|-|商品评价id|
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
    public function detail ()
    {
        try {
            # 获取
            $user_id    = intval(request()->user['user_id'] ?? 0);
            $id = intval(request()->data['goods_comment_id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '请先登录' );
            }
            $param['where'] = [
                'goods_comment_id'  => $id,
                'seller_user_id'    => $user_id,
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
}