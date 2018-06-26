<?php
namespace app\api\controller\goods\v1;
use app\api\model\goods\GoodsCpsData;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\Cache;

/**
 * @title 商品2
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class Goods2
{
    private $selectField = [
        'goods_id',
        'goods_name',
        'goods_sub_name',
        'goods_images',
        'goods_max_price',
        'goods_min_price',
        'goods_sale_num',
        'goods_sku_num',
        'goods_state',
        'goods_category_id',
        'shop_goods_brand_id',
        'goods_comment_num',
        'goods_comment_good_num',
    ];

    /**
     * @title 商品列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true |0|-|用户|
     * |goods_name  |string |false|0|-|商品名，模糊查找|
     * |page        |int    |false|0|-|当前页，默认1|
     * |pagesize    |int    |false|0|-|每页记录数，默认15|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |total           |string|---|总记录数|
     * |per_page        |string|---|每页记录数|
     * |current_page    |string|---|当前页|
     * |last_page       |string|---|最后页数|
     * |goods_id        |string|---|商品id|
     * |goods_name      |string|---|商品名|
     * |goods_sub_name  |string|---|商品副标题|
     * |goods_images    |string|---|商品图片|
     * |goods_max_price |string|---|最大价格|
     * |goods_min_price |string|---|最小价格|
     * |goods_sale_num  |string|---|销量|
     * |goods_sku_num   |string|---|库存|
     * |goods_state     |string|---|状态|
     * |goods_state_name|string|---|状态名|
     * |goods_images_key|string|---|图片key|
     * |goods_category_id|string|---|分类id|
     * |shop_goods_brand_id|string|---|品牌id|
     * |goods_comment_num|string|---|评价数量|
     * |goods_comment_good_num|string|---|好评数量|
     * |category|string|---|分类|
     * |brand|string|---|品牌|
     * @response_example 响应示例
     * `{
            data:{
                "total": 1,
                "per_page": 15,
                "current_page": 1,
                "last_page": 1,
                "data":[
                    {
                        "goods_id": "316",
                        "goods_name": "宁美国度i5 6500升7500四核办公台式电脑主机DIY组装机全套整机宁美国度",
                        "goods_sub_name": "",
                        "goods_images": "https://img.zrst.com/FiU3RtEh0HdNfF1tYTbhUsOT_6Zp",
                        "goods_max_price": "4.00",
                        "goods_min_price": "4.00",
                        "goods_sale_num": "30",
                        "goods_sku_num": "81",
                        "goods_state": "1",
                        "goods_state_name": "上架",
                        "goods_images_key": "FiU3RtEh0HdNfF1tYTbhUsOT_6Zp"
                    }
                ]
            },
            "msg":"请求成功",
            "info":"success",
            "code":20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    function index()
    {
        try {
            # get param
            $user_id = intval(request()->user['user_id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # condition
            $map = [
                'seller_user_id'    => $user_id,
                'goods_state'       => State::STATE_GOODS_NORMAL,
            ];
            if ( isset(request()->data['goods_name']) ) {
                $map['goods_name'] = ['like','%' . (string) request()->data['goods_name'] . '%'];
            }
            # get data
            $list = Fun::pageList(Fun::mApi('goods','Goods'),[
                'page'      => intval(request()->data['page'] ?? 1),
                'pagesize'  => intval(request()->data['pagesize'] ?? 15),
                'where'     => $map,
                'field'     => $this->selectField,
                'relation'  => 'category,brand',
            ]);
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 商品详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |goods_id|int|true|0|-|商品id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |goods_id        |string|---|商品id|
     * |goods_name      |string|---|商品名|
     * |goods_sub_name  |string|---|商品副标题|
     * |goods_images    |string|---|商品图片|
     * |goods_max_price |string|---|最大价格|
     * |goods_min_price |string|---|最小价格|
     * |goods_sale_num  |string|---|销量|
     * |goods_sku_num   |string|---|库存|
     * |goods_state     |string|---|状态|
     * |goods_state_name|string|---|状态名|
     * |goods_images_key|string|---|图片key|
     * |goods_category_id|string|---|分类id|
     * |shop_goods_brand_id|string|---|品牌id|
     * |goods_comment_num|string|---|评价数量|
     * |goods_comment_good_num|string|---|好评数量
     * |category|string|---|分类|
     * |brand|string|---|品牌|
     * @response_example 响应示例
     * `{
            "data": {
                "goods_id": "320",
                "goods_name": "Asus/华硕 轻薄本 U4100UN 八代i7独显便携超极本商务办公笔记本",
                "goods_sub_name": "",
                "goods_images": "https://img.zrst.com/FgeSc47MaOMV_ebJC04SsjoJF35g",
                "goods_max_price": "999.00",
                "goods_min_price": "4.00",
                "goods_sale_num": "18",
                "goods_sku_num": "882",
                "goods_state": "1",
                "goods_state_name": "上架",
                "goods_images_key": "FgeSc47MaOMV_ebJC04SsjoJF35g"
            },
            "msg": "请求成功",
            "info": "success",
            "code": 20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    function detail()
    {
        try {
            # get param
            $goods_id = intval(request()->data['goods_id'] ?? 0);
            if ( State::STATE_NORMAL > $goods_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # get data
            $detail = Fun::dataDetail(Fun::mApi('goods','Goods'),[
                'where'     => [
                    'goods_id'      => $goods_id,
                    'goods_state'   => State::STATE_NORMAL,
                ],
                'field'     => $this->selectField,
                'relation'  => 'category,brand',
            ]);
            if ( empty($detail) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # ...
            return $detail;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title cps
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|true|-|-|用户|
     * |goods_id|int|true|-|-|商品id|
     * |rate|float|true|-|-|比例|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |-|-|-|-|
     * @response_example 响应示例
     * `{
            msg: "操作成功",
            info: "success",
            code: 20000
        }`
     * @description 接口描述
     * > your api description
     * @return array|int
     */
    public function cps()
    {
        try {
            $model  = new GoodsCpsData();
            $goods_id   = request()->data['goods_id'];
            $seller_user_id = request()->user['user_id'];
            $shop_id    = request()->user['user_shop_id'];
            $cps_commission_rate    = request()->data['rate'];
            if ($shop_id < State::STATE_NORMAL) throw new ResponseException(Code::CODE_OTHER_FAIL, '当前用户未开店');
            $map    = [
                'goods_id'  => $goods_id,
                'shop_id'   => $shop_id,
                'seller_user_id'    => $seller_user_id,
            ];
            $id     = $model->where($map)->value('cps_id');
            #   修改
            if ($id) {
                $map['cps_id']  = $id;
                $flag   = $model->save([
                    'cps_commission_rate'   => $cps_commission_rate
                ], $map);
                $msg    = '修改失败';
            } else {
                #   新增
                $goods  = db('goods')
                    ->where(['seller_user_id' => $seller_user_id, 'goods_state' => State::STATE_NORMAL, 'goods_id' => $goods_id])
                    ->value('goods_id');
                if (!$goods) throw new ResponseException(Code::CODE_OTHER_FAIL, '商品不存在或已下架');
                $map['cps_commission_rate'] = $cps_commission_rate;
                $flag   = $model->save($map);
                $msg    = '创建失败';
            }
            if (!$flag) throw new ResponseException(Code::CODE_OTHER_FAIL, $msg);
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    
}