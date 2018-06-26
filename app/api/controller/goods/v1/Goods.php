<?php
namespace app\api\controller\goods\v1;
use app\common\traits\F;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\Cache;
use app\api\model\goods\Goods as Model;

/**
 * @title 商品
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class Goods
{
    protected $createField = [
        'goods_category_id',//商品类目

        'goods_name',//商品名称
        'goods_sub_name',//副标题
        'goods_number',//商品编号
        'goods_service_days',
        'goods_images',//主图
        'goods_state',//状态
        'goods_recommend',//推荐

        'shop_goods_category_ids',//店铺分类

        'shop_goods_brand_id',//品牌
        'express_id',//运费模板
        'package_id',//包装模板
        'protection_id',//售后模板
        
        'goods_sku_num',//库存
        'goods_max_price',//最大价格
        'goods_min_price',//最小价格
        'shop_id',//店铺id
        'seller_user_id',//卖家id
        'goods_is_self',//是否自营
        'goods_recommend_type',//橱窗类型
        'goods_have_qualifications',//是否有资质
        'goods_is_free',//是否包邮
        'goods_category2_id',//商品二级类目
    ];
    
    protected $updateField = [
        'goods_category_id',//商品类目

        'goods_name',//商品名称
        'goods_sub_name',//副标题
        'goods_number',//商品编号
        //'goods_shopping_score_multi',//购物积分比例
        'goods_service_days',
        'goods_images',//主图
        'goods_state',//状态
        'goods_recommend',//推荐

        'shop_goods_category_ids',//店铺分类

        'shop_goods_brand_id',//品牌
        'express_id',//运费模板
        'package_id',//包装模板
        'protection_id',//售后模板
        
        'goods_sku_num',//库存
        'goods_max_price',//最大价格
        'goods_min_price',//最小价格
        'shop_id',//店铺id
        'seller_user_id',//卖家id
        'goods_is_self',//是否自营
        'goods_recommend_type',//橱窗类型
        'goods_have_qualifications',//是否有资质
        'goods_is_free',//是否包邮
        'goods_category2_id',//商品二级类目

        'goods_score_multi'//购物积分倍数
        
    ];

    /**
     * @title 买家-商品列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_id     |int    |false  |0  |-  |店铺id|
     * |sgcid       |int    |false  |0  |-  |商品分类id|
     * |q           |string |false  |0  |-  |搜索关键字|
     * |p           |int    |false  |0  |-  |当前页|
     * |pagesize    |int    |false  |0  |-  |每页显示数量|
     * |min_price   |float  |false  |0  |-  |最小价格|
     * |mzx_price   |float  |false  |0  |-  |最大价格|
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
            # get param
            $param      = request()->data;
            # 设置搜索条件...
            $map['goods_state']     = State::STATE_GOODS_NORMAL;
            $map['goods_sku_num']   = [ 'gt', 0 ];
            # 店铺
            if ( isset($param['shop_id']) && $param['shop_id'] > 0 ) {
                $map['shop_id']     = $param['shop_id'];
            }
            # 名字
            if ( isset($param['q']) && $param['q'] ) {
                $map['goods_name']  = [ 'like', '%' . trim(urldecode( $param['q'] )) . '%' ];
            }
            # 价格区间
            if( isset($param['min_price']) && $param['min_price'] ){
                $map['goods_min_price'] = [ 'egt', (float) $param['min_price'] ];
            }
            if( isset($param['max_price']) && $param['max_price'] ){
                $map['goods_max_price'] = [ 'elt', (float) $param['max_price'] ];
            }
            # 店铺商品分类
            if ( isset($param['sgcid']) && preg_match('/^[0-9]+$/', $param['sgcid']) ) {
                $map[] = [ 'exp' , 'find_in_set(' . $param['sgcid'] . ', shop_goods_category_ids)' ];
            }
            # 分页设置
            $pagesize   = intval($param['pagesize'] ?? 15);
            $page       = intval($param['page'] ?? 1);
            # get data
            $list = Fun::pageList(Fun::mApi('goods', 'Goods'),[
                'where'         => $map,
                'order'         => 'goods_create_time desc',
                'relation'      => 'shop,user,goods_sku,goods_express_tpl,goods_category',
                'field'         => '*',
                'page'          => $page,
                'pagesize'      => $pagesize,
                'cache'         => false,
                'cache_time'    => \mercury\constants\Common::TIME_FIVE_MINUTE,
            ]);
            // dump($list);exit;
            # no data
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 处理数据
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 卖家-商品列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id                 |int    |true   |0  |-  |用户id|
     * |sgcid                   |int    |false  |0  |-  |商品分类id|
     * |goods_recommend_type    |int    |false  |0  |-  |橱窗类型|
     * |state                   |int    |false  |0  |-  |状态 1 上架 2 下架 3违规 4严重违规|
     * |q                       |string |false  |0  |-  |搜索关键字|
     * |p                       |int    |false  |0  |-  |当前页|
     * |pagesize                |int    |false  |0  |-  |每页显示数量|
     * |min_price               |float  |false  |0  |-  |最小价格|
     * |mzx_price               |float  |false  |0  |-  |最大价格|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function index2()
    {
        try {
            # get param
            $param      = request()->data;
            $shop_id    = intval(request()->user['user_shop_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 状态
            if ( isset($param['state']) ) {
                if ( isset(State::STATE_GOODS_USER_ARRAY[$param['state']]) ) {
                    $map['goods_state'] = $param['state'];
                }
                if ( $param['state'] == 'recommend' ) {
                    $map['goods_recommend'] = State::STATE_NORMAL;
                    $map['goods_state'] = [ 'in' , array_keys(State::STATE_GOODS_USER_ARRAY) ];
                }
            } else {
                $map['goods_state'] = [ 'in', array_keys(State::STATE_GOODS_USER_ARRAY) ];
            }
            // dump($map);exit;
            # 店铺
            $map['shop_id']         = $shop_id;
            # 搜商品名字
            if ( isset($param['q']) && $param['q'] ) {
                $map['goods_name']  = [ 'like', '%' . trim(urldecode( $param['q'] )) . '%' ];
            }
            # 价格区间
            if( isset($param['min_price']) && $param['min_price'] ){
                $map['goods_min_price'] = [ 'egt', (float) $param['min_price'] ];
            }
            if( isset($param['max_price']) && $param['max_price'] ){
                $map['goods_max_price'] = [ 'elt', (float) $param['max_price'] ];
            }
            # 店铺商品分类
            if ( isset($param['sgcid']) && preg_match('/^[0-9]+$/', $param['sgcid']) ) {
                $map[] = [ 'exp' , 'find_in_set(' . $param['sgcid'] . ', shop_goods_category_ids)' ];
            }
            # 商品橱窗类型
            if ( isset($param['goods_recommend_type']) && preg_match('/^[0-9]+$/', $param['goods_recommend_type']) ) {
                $map['goods_recommend_type'] = $param['goods_recommend_type'];
            }
            # 分页设置
            $pagesize   = intval($param['pagesize'] ?? 15);
            $page       = intval($param['page'] ?? 1);
            # get data
            $list = Fun::pageList(Fun::mApi('goods', 'Goods'),[
                'where'         => $map,
                'order'         => 'goods_create_time desc',
                'relation'      => 'shop,user,goods_sku,goods_express_tpl,goods_category',
                'field'         => '*',
                'page'          => $page,
                'pagesize'      => $pagesize,
                'cache'         => false,
                'cache_time'    => \mercury\constants\Common::TIME_FIVE_MINUTE,
            ]);
            // dump($list);exit;
            # no data
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 处理数据
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 买家-商品详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |id  |int    |true   |0  |-  |商品库存id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function detail()
    {
        try {
            # get param
            $param          = request()->data;
            $goods_sku_id   = intval(request()->data['id']);
            if ( State::STATE_NORMAL > $goods_sku_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # have cache
            $cacheKey   = Fun::getCacheName(Cache::GOODS_SKU_DETAIL) . $goods_sku_id;
            $cache      = Fun::redis()->get( $cacheKey );
            if ( $cache ) {
                return json_decode($cache,true);
            }
            # get data
            $detail = Fun::dataDetail(Fun::mApi('goods','GoodsSku'), [
                'relation'      => 'goods,goods_params',
                'where'         => [ 'goods_sku_id' => $goods_sku_id ],
            ]);
            // return $detail;
            if ( empty($detail) || $detail['goods']['goods_state'] != State::STATE_GOODS_NORMAL ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            ## 属性组
            $sku_group = Fun::dataAll(Fun::mApi('goods','GoodsSkuGroup'), [
                // 'order'         => 'sku_group_name asc',
                'field'         => 'sku_group_id,sku_group_name,sku_group_value',
                'where'         => [ 'goods_id' => $detail['goods_id'] ],
            ]);
            $detail['goods_sku_group'] = Fun::array_divide_group($sku_group, 'sku_group_name', 'sku_group_value', 'sku_group_id');
            $detail['goods_sku_group_ids'] = explode(',', $detail['goods_sku_group_ids']);
            ## 属性
            $goods_sku = Fun::dataAll(Fun::mApi('goods','goodsSku'),[
                'field'         => 'goods_sku_id,goods_sku_group_ids',
                'where'         => [ 'goods_id' => $detail['goods_id'] ],
            ]);
            // dump($goods_sku);exit;
            $detail['goods_sku'] = array_column($goods_sku, 'goods_sku_group_ids', 'goods_sku_id');
            # set cache
            if ( empty($cache) ) {
                Fun::redis()->setex($cacheKey, \mercury\constants\Common::TIME_MONENT, json_encode($detail));
            }
            # ...
            return $detail;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 批量上架商品
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int        |true   |0  |-  |用户id|
     * |goods_id    |string     |true   |0  |-  |商品id，多个用逗号隔开|
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
    public function batchShelves()
    {
        try {
            # get param
            $user_id    = request()->user['user_id'];
            $goods_id   = (string) request()->data['goods_id'];
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            $shop = db('shop')->field('shop_id,shop_state')->find(request()->user['user_shop_id'] ?? 0);
            if (false == isset($shop['shop_state']) || $shop['shop_state'] != State::STATE_SHOP_NORMAL ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL , '不允上架商品' );
            }
            # map
            $map['seller_user_id']    = $user_id;
            $map['goods_id']          = [ 'in', $goods_id ];
            $map['goods_state']       = [ 'in', array_keys(State::STATE_GOODS_SHELVES_ARRAY) ];
            # update
            if ( false === Fun::mApi('goods','Goods')->save([ 'goods_state' => State::STATE_GOODS_NORMAL ],$map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 批量下架商品
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int        |true   |0  |-  |用户id|
     * |goods_id    |string     |true   |0  |-  |商品id，多个用逗号隔开|
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
    public function batchUnder()
    {
        try {
            # get param
            $user_id    = request()->user['user_id'];
            $goods_id   = (string) request()->data['goods_id'];
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # 精选商品不能下架
            $haveRecommend1 = Fun::dataAll(Fun::mApi('goods','Goods'),[
                'where' => [
                    'seller_user_id'        => $user_id,
                    'goods_id'              => [ 'in', $goods_id ],
                    'goods_recommend_type'  => State::STATE_GOODS_RECOMMEND_TYPE_TWO,
                ],
            ]);
            if ($haveRecommend1) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不能下架' );
            }
            # map
            $map['seller_user_id']    = $user_id;
            $map['goods_id']          = [ 'in', $goods_id ];
            $map['goods_state']       = State::STATE_GOODS_NORMAL;
            # update
            if ( false === Fun::mApi('goods','Goods')->save([ 'goods_state' => State::STATE_GOODS_UNDER ],$map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            ### 删除旧缓存，商品详情的
            self::deleteDetailCache($goods_id);
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 批量更新商品所属店铺类目
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id                     |int        |true   |0  |-  |用户id|
     * |goods_id                    |string     |true   |0  |-  |商品id，多个用逗号隔开|
     * |shop_goods_category_ids     |array      |true   |0  |-  |店铺类目|
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
    public function batchUpdateCategory()
    {
        try {
            // dump(request()->data['shop_goods_category_ids']);exit;
            # get param
            $user_id                    = intval(request()->user['user_id']);
            $goods_id                   = (string) request()->data['goods_id'];
            $shop_goods_category_ids    = request()->data['shop_goods_category_ids'];
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # update
            $updateMap['seller_user_id']    = $user_id;
            $updateMap['goods_id']          = [ 'in', $goods_id ];
            $updateArr['shop_goods_category_ids'] = $shop_goods_category_ids;
            // dump($updateArr);dump($updateMap);exit;
            if ( false === Fun::mApi('goods','Goods')->save($updateArr, $updateMap) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    
    /**
     * @title 商品批量修改运费模板
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int        |true   |0  |-  |用户id|
     * |goods_id    |string     |true   |0  |-  |商品id，多个用逗号隔开|
     * |express_id  |int        |true   |0  |-  |运费模板id|
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
    public function batchUpdateExpress()
    {
        try {
            # get param
            $user_id       = intval(request()->user['user_id']);
            $goods_id      = (string) request()->data['goods_id'];
            $express_id    = intval(request()->data['express_id']);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # map
            
            if (strpos($goods_id, ',')) {
                $map['goods_id'] = [ 'in', $goods_id ];
            }
            # update
            $map = [
                'seller_user_id'    => $user_id,
                'goods_id'          => [ 'in', $goods_id ],
                'goods_state'       => [ 'in', array_keys(State::STATE_GOODS_USER_ARRAY) ],
            ];
            $updateArr['express_id'] = $express_id;
            if ( false === Fun::mApi('goods','Goods')->save($updateArr,$map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 设置橱窗
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int        |true   |0  |-  |用户id|
     * |goods_id        |string     |true   |0  |-  |商品id，多个用逗号隔开|
     * |goods_recommend |int        |true   |0  |-  |是否推荐|
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
    public function batchRecommend()
    {
        try {
            # get param
            $user_id    = request()->user['user_id'];
            $goods_id   = (string) request()->data['goods_id'];
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # 最大数量
            $mapNew = [
                'seller_user_id'    => $user_id,
                'goods_state'       => [ 'in', array_keys(State::STATE_GOODS_USER_ARRAY) ],
                'goods_recommend'   => State::STATE_NORMAL,
            ];
            if ( State::STATE_GOODS_RECOMMEND_NUMS < db('goods')->where($mapNew)->count() + count(explode(",", $goods_id)) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '最多创建' . State::STATE_GOODS_RECOMMEND_NUMS . '个橱窗商品' );
            }
            # map
            $map['seller_user_id']    = $user_id;
            $map['goods_id']          = [ 'in', $goods_id ];
            $map['goods_recommend']   = State::STATE_DISABLED;
            // dump($goods_id);
            # update
            if ( false === Fun::mApi('goods','Goods')->save([ 'goods_recommend' => State::STATE_NORMAL ],$map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 移除橱窗
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int        |true   |0  |-  |用户id|
     * |goods_id        |string     |true   |0  |-  |商品id，多个用逗号隔开|
     * |goods_recommend |int        |true   |0  |-  |是否推荐|
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
    public function batchRemoveRecommend()
    {
        try {
            # get param
            $user_id    = request()->user['user_id'];
            $goods_id   = (string) request()->data['goods_id'];
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # map
            $map['seller_user_id']    = $user_id;
            $map['goods_id']          = [ 'in', $goods_id ];
            $map['goods_recommend']   = State::STATE_NORMAL;
            // dump($goods_id);
            # update
            if ( false === Fun::mApi('goods','Goods')->save([ 'goods_recommend' => State::STATE_DISABLED ],$map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 批量删除商品
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int        |true   |0  |-  |用户id|
     * |goods_id        |string     |true   |0  |-  |商品id，多个用逗号隔开|
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
    public function batchDelete()
    {
        try {
            # get param
            $user_id    = intval(request()->user['user_id']);
            $goods_id   = (string) request()->data['goods_id'];
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # 精选商品不能删除
            $haveRecommend1 = Fun::dataAll(Fun::mApi('goods','Goods'),[
                'where' => [
                    'seller_user_id'        => $user_id,
                    'goods_id'              => [ 'in', $goods_id ],
                    'goods_recommend_type'  => State::STATE_GOODS_RECOMMEND_TYPE_TWO,
                ],
            ]);
            if ($haveRecommend1) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不能删除' );
            }
            # map
            $map['seller_user_id']    = $user_id;
            $map['goods_id']          = [ 'in', $goods_id ];
            $map['goods_state']       = [ 'in', array_keys(State::STATE_GOODS_USER_ARRAY) ];
            # update
            if ( false === Fun::mApi('goods','Goods')->save([ 'goods_state' => State::STATE_GOODS_DELETE ],$map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            ### 删除旧缓存，商品详情的
            self::deleteDetailCache($goods_id);
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 发布商品
     * @param $openid
     * 商品
     * @param goods_name 商品标题
     * @param goods_sub_name 商品副标题
     * @param goods_max_price 商品最大价格
     * @param goods_min_price 商品最小价格
     * @param goods_images 商品主图
     * @param goods_state 商品状态
     * @param goods_recommend 是否推荐
     * @param express_id 运费模板
     * @param package_id 包装模板
     * @param protection_id 售后模板
     * @param shop_goods_category_ids[] 所属店铺分类ID
     * @param shop_goods_brand_id 所属店铺品牌
     * @param goods_category_id 所属商品分类
     * 商品详情内容
     * @param goods_content 商品内容
     * 商品属性组
     * @param goods_sku_group[$i][sku_group_name]   属性名
     * @param goods_sku_group[$i][sku_group_value][]  属性值
     * 商品库存
     * @param goods_sku[$i][goods_sku_price] 价格
     * @param goods_sku[$i][goods_sku_market_price] 市场价
     * @param goods_sku[$i][goods_sku_cost_price] 成本价
     * @param goods_sku[$i][goods_sku_num] 库存数
     * @param goods_sku[$i][goods_sku_sale_num] 销量
     * @param goods_sku[$i][goods_sku_group_ids] 库存组ids
     * @param goods_sku[$i][goods_sku_group_values] 库存组的值
     * @param goods_sku[$i][goods_sku_album] 相册,多个用逗号隔开
     * @param goods_sku[$i][goods_weight] 重量
     * 商品参数
     * @param goods_params[$i][group_id] 组id
     * @param goods_params[$i][group_name] 组名
     * @param goods_params[$i][group_value_ids] 所选参数id，多个用逗号隔开
     * @param goods_params[$i][group_value][] 组值
     * @return array|mixed|string
     */
    public function create()
    {
        try {
            # get param
            $param = request()->data;
            $user_id = intval(request()->user['user_id'] ?? 0);
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            $goods              = request()->data;
            $goods_content      = (string)(request()->data['goods_content'] ?? '');
            $goods_sku_group    = request()->data['goods_sku_group'] ?? [];
            $goods_sku          = request()->data['goods_sku'] ?? [];
            $goods_params       = request()->data['goods_params'] ?? [];
            # 检测用户和店铺
            // $user_id = 1;
            // $shop_id = 1;
            if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY  );
            }
            ## 店铺是否正常
            $shop = db('shop')->field('shop_id,shop_state,shop_type_id')->find($shop_id);
            if (false == isset($shop['shop_state']) || $shop['shop_state'] != State::STATE_SHOP_NORMAL ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL , '不允许发布商品' );
            }
            # 是否为自营
            if ( $shop['shop_type_id'] == 11 ) {
                $goods['goods_is_self'] = 1;
            } else {
                $goods['goods_is_self'] = 0;
            }
            # 验证sku是否是sku_group生成
            foreach ($goods_sku_group as $key => $value) {
                $lie[] = $value['sku_group_value'];
                $lieNew[] = $value['sku_group_value'];
                # 属性name：value,
                $lieNew2Temp = [];
                foreach ($value['sku_group_value'] as $ko => $vo) {
                    $lieNew2Temp[] = $value['sku_group_name'] . ':' . $vo;
                }
                $lieNew2[] = $lieNew2Temp;
            }
            ## 属性组合
            $lie = Fun::array_combine($lie);
            foreach ($lie as $key => $value) {
                $lie[$key] = implode(',', $value);
            }
            ## 验证长度一致
            if ( count($lie) != count($goods_sku) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '商品属性错误1' );
            }

            ## 验证是顺序和值否一致和相等
            foreach ($goods_sku as $key => $value) {
                // dump($value['goods_sku_group_values']);dump($goods_sku);
                if ($value['goods_sku_group_values'] != $lie[$key]) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '商品属性错误2' );
                }
            }
            ## 验证价格
            if ( false == Fun::vApi('goods','Goods')->checkMulti2($goods_sku) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '商品价格过低' );
            }
            # 开始添加记录
            $model = new Model;
            $model->startTrans();
            $goodsContent   = new \app\api\model\goods\GoodsContent;
            $goodsSkuGroup  = new \app\api\model\goods\GoodsSkuGroup;
            $goodsSku       = new \app\api\model\goods\GoodsSku;
            $goodsParams    = new \app\api\model\goods\GoodsParams;
            ## 添加商品记录
            ### 计算 库存和销量,最大最小价格
            $param['goods_sku_num']     = array_sum(array_column($goods_sku, 'goods_sku_num'));
            $param['goods_max_price']   = max(array_column($goods_sku, 'goods_sku_price'));
            $param['goods_min_price']   = min(array_column($goods_sku, 'goods_sku_price'));
            ### 添加
            $param['shop_id']           = $shop_id;
            $param['seller_user_id']    = $user_id;
            $param['goods_category2_id']= db('goods_category')->where(['category_id' => $param['goods_category_id']])->value('category_sid');
            $param['goods_is_free']     = db('goods_express_tpl')->where(['express_id' => $param['express_id']])->value('express_is_free');
            $param['goods_have_qualifications'] = null;
            if ( false == $model->allowField($this->createField)->isUpdate(false)->save($param) ) {
                $model->rollback();
                throw new ResponseException( Code::CODE_OTHER_FAIL, '发布商品失败1' );
            }
            ## 添加商品详情记录
            $goodsContentData = [
                'goods_content' => $goods_content,
                'goods_id' => (int)$model->goods_id,
            ];
            if ( false == $goodsContent->allowField(true)->isUpdate(false)->save($goodsContentData) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '发布商品失败2' );
            }
            ## 添加属性库存组和属性库存记录
            $goodsSkuGroupData = [];
            foreach ($goods_sku_group as $key => $value) {
                foreach ($value['sku_group_value'] as $ko => $vo) {
                    $goodsSkuGroupData[] = [
                        'sku_group_name'    => $value['sku_group_name'],
                        'sku_group_value'   => $vo,
                        'sku_group_album'   => implode(',', $value['sku_group_album'][$ko] ?? []),
                        'seller_user_id'    => $user_id,
                        'shop_id'           => $shop_id,
                        'goods_id'          => $model->goods_id,
                    ];
                }
            }
            ### 获得库存组ids
            $goodsSkuGroupIds = [];
            foreach ($goodsSkuGroupData as $key => $value) {
                if ( false == $goodsSkuGroup->allowField(true)->isUpdate(false)->save($value) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '发布商品失败3' );
                }
                $goodsSkuGroupData[$key]['sku_group_id'] = $goodsSkuGroup->sku_group_id;
                # 获取了主键必须设置null,否则主键冲突 循环添加不进去
                $goodsSkuGroup->sku_group_id = null;
            }
            ### 获得 sku_group_id => sku_group_value 数组
            $goodsSkuGroupData = array_column($goodsSkuGroupData, 'sku_group_id');
            ### 获得 $lieNew 替换为id
            $i = 0;
            foreach ($lieNew as $key => $value) {
                $push = [];
                foreach ($value as $ko => $vo) {
                    $push[] = $goodsSkuGroupData[$i++];
                }
                $lieNew[$key] = $push;
            }
            ### 组合出了ids
            $lieNew = Fun::array_combine($lieNew);
            $lieNew2 = Fun::array_combine($lieNew2);
            ### 赋值ids
            foreach ($goods_sku as $key => $value) {
                $goods_sku[$key]['goods_sku_group_ids'] = implode(',', $lieNew[$key]);
                $goods_sku[$key]['goods_sku_group_name_values'] = implode(',', $lieNew2[$key]);
                $goods_sku[$key]['goods_id'] = $model->goods_id;
            }
            ### 添加属性库存记录
            foreach ($goods_sku as $key => $value) {
                if ( false == $goodsSku->allowField(true)->isUpdate(false)->save($value) ){
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '发布商品失败4' );
                }
                if ($key == 0) $goods_sku_id = $goodsSku->goods_sku_id;
                $goodsSku->goods_sku_id = null;
            }
            ## 添加商品参数
            foreach ($goods_params as $key => $value) {
                $value['seller_user_id']    = $user_id;
                $value['shop_id']           = $shop_id;
                $value['goods_id']          = $model->goods_id;
                if ( false == $goodsParams->allowField(true)->isUpdate(false)->save($value) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '发布商品失败5' );
                }
                $goodsParams->goods_params_id = null;
            }
            ## 添加商品资质
            foreach ($param['goods_qualifications'] ?? [] as $key => $value) {
                $value['seller_user_id']    = $user_id;
                $value['shop_id']           = $shop_id;
                $value['goods_id']          = $model->goods_id;
                if ( false == Fun::mApi('goods','GoodsQualifications')->allowField(true)->isUpdate(false)->save($value) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '发布商品失败6' );
                }
            }
            # ...
            $model->commit();
            return [
                'code'      => Code::CODE_SUCCESS,
                'data'      => [
                    'goods_id'  => $model->goods_id,
                    'url'       => Fun::domain('wap', "/goods?id={$goods_sku_id}")
                ]
            ];
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 更新商品
     * @param openid
     * @param goods_id
     * @param 其他参数同发布商品
     * @return array|mixed|string
     */
    public function update()
    {
        try {
            # get param
            $param = request()->data;
            $user_id = intval(request()->user['user_id'] ?? 0);
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            $goods              = request()->data;
            $goods_content      = (string)(request()->data['goods_content'] ?? '');
            $goods_sku_group    = request()->data['goods_sku_group'] ?? [];
            $goods_sku          = request()->data['goods_sku'] ?? [];
            $goods_params       = request()->data['goods_params'] ?? [];
            # 检测用户和店铺
            // $user_id = 1;
            // $shop_id = 1;
            if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            ## 店铺是否正常
            $shop = db('shop')->field('shop_id,shop_state,shop_type_id')->find($shop_id);
            if (false == isset($shop['shop_state']) || $shop['shop_state'] != State::STATE_SHOP_NORMAL ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL , '不允许编辑商品' );
            }
            # 是否为自营
            if ( $shop['shop_type_id'] == 11 ) {
                $goods['goods_is_self'] = 1;
            } else {
                $goods['goods_is_self'] = 0;
            }
            # 验证sku是否是sku_group生成
            foreach ($goods_sku_group as $key => $value) {
                $lie[] = $value['sku_group_value'];
                $lieNew[] = $value['sku_group_value'];
                # 属性name：value,
                $lieNew2Temp = [];
                foreach ($value['sku_group_value'] as $ko => $vo) {
                    $lieNew2Temp[] = $value['sku_group_name'] . ':' . $vo;
                }
                $lieNew2[] = $lieNew2Temp;
            }
            ## 属性组合
            $lie = Fun::array_combine($lie);
            foreach ($lie as $key => $value) {
                $lie[$key] = implode(',', $value);
            }
            ## 验证长度一致
            // dump($lie);
            if ( count($lie) != count($goods_sku) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '商品属性错误1' );
            }
            ## 验证是顺序和值否一致和相等
            foreach ($goods_sku as $key => $value) {
                if ($value['goods_sku_group_values'] != $lie[$key]) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '商品属性错误2' );
                }
            }
            // $param['goods_shopping_score_multi'] = 20;
            ## 验证价格
            if ( false == Fun::vApi('goods','Goods')->checkMulti2($goods_sku,$param['goods_shopping_score_multi']) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '商品价格过低' );
            }
            # 开始添加记录
            $model = new Model;
            $model->startTrans();
            $model1 = \think\Db::connect(config('database1'));
            $model1->startTrans();
            $goodsContent   = new \app\api\model\goods\GoodsContent;
            $goodsSkuGroup  = new \app\api\model\goods\GoodsSkuGroup;
            $goodsSku       = new \app\api\model\goods\GoodsSku;
            $goodsParams    = new \app\api\model\goods\GoodsParams;
            # 修改商品记录
            ## 计算 库存和销量,最大最小价格
            $param['goods_sku_num']     = array_sum(array_column($goods_sku, 'goods_sku_num'));
            $param['goods_max_price']   = max(array_column($goods_sku, 'goods_sku_price'));
            $param['goods_min_price']   = min(array_column($goods_sku, 'goods_sku_price'));
            ### 商品存在且可编辑
            $goods = Fun::dataDetail($model, intval($param['goods_id']));
            if ( empty($goods) || $goods['seller_user_id'] != $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '商品不存在' );
            }
            if ( false == isset(State::STATE_GOODS_USER_ARRAY[$goods['goods_state']]) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '当前商品不能编辑' );
            }
            # 精选商品不能下架
            if ($goods['goods_recommend_type'] == State::STATE_GOODS_RECOMMEND_TYPE_TWO) {
                if ( $param['goods_state'] == State::STATE_GOODS_UNDER ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不能下架' );
                }
            }
            ## 修改商品
            $param['shop_id']           = $shop_id;
            $param['seller_user_id']    = $user_id;
            $param['goods_have_qualifications'] = null;
            $param['goods_category2_id']= db('goods_category')->where(['category_id' => $param['goods_category_id']])->value('category_sid');
            $param['goods_is_free']     = db('goods_express_tpl')->where(['express_id' => $param['express_id']])->value('express_is_free');
            if ( empty($goods) || false == is_int($model->allowField($this->updateField)->isUpdate(true)->save($param)) ) {
                $model->rollback();
                throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败1' );
            }
            ## 修改商品详情
            $goodsContentData = [
                'goods_content' => $goods_content,
                'goods_id'      => (int)$model->goods_id,
            ];
            if ( false == is_int($goodsContent->allowField(true)->isUpdate(true)->save($goodsContentData)) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败2' );
            }
            ## 删除商品 旧的 库存组、参数
            $deleteMap['goods_id'] = intval($param['goods_id']);
            $countGoodsSkuGroup = $goodsSkuGroup->where([ 'goods_id' => $param['goods_id'] ])->count();
            
            if ( $countGoodsSkuGroup != $model1->table('zr_goods_sku_group')->where($deleteMap)->delete() ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败3' );                
            }
            
            ## 添加属性库存组和属性库存记录
            $goodsSkuGroupData = [];
            foreach ($goods_sku_group as $key => $value) {
                foreach ($value['sku_group_value'] as $ko => $vo) {
                    $goodsSkuGroupData[] = [
                        'sku_group_name'    => $value['sku_group_name'],
                        'sku_group_value'   => $vo,
                        'sku_group_album'   => implode(',', $value['sku_group_album'][$ko] ?? []),
                        'seller_user_id'    => $user_id,
                        'shop_id'           => $shop_id,
                        'goods_id'          => $model->goods_id,
                    ];
                }
            }
            ### 获得库存组ids
            $goodsSkuGroupIds = [];
            foreach ($goodsSkuGroupData as $key => $value) {
                $value['sku_group_create_time'] = time();
                $value['sku_group_update_time'] = time();
                if ( false == $model1->table('zr_goods_sku_group')->insert($value) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败6' );
                }
                $goodsSkuGroupData[$key]['sku_group_id'] = $model1->table('zr_goods_sku_group')->getLastInsID();
            }
            // dump($goodsSkuGroupData);exit;
            ### 获得 sku_group_id => sku_group_value 数组
            $goodsSkuGroupData = array_column($goodsSkuGroupData, 'sku_group_id');
            ### 获得 $lieNew 替换为id
            $i = 0;
            foreach ($lieNew as $key => $value) {
                $push = [];
                foreach ($value as $ko => $vo) {
                    $push[] = $goodsSkuGroupData[$i++];
                }
                $lieNew[$key] = $push;
            }
            ### 组合出了ids
            $lieNew = Fun::array_combine($lieNew);
            $lieNew2 = Fun::array_combine($lieNew2);
            ### 赋值ids
            foreach ($goods_sku as $key => $value) {
                $goods_sku[$key]['goods_sku_group_ids'] = implode(',', $lieNew[$key]);
                $goods_sku[$key]['goods_sku_group_name_values'] = implode(',', $lieNew2[$key]);
                $goods_sku[$key]['goods_id'] = $model->goods_id;
            }
            ### 旧的库存id记录
            $oldSkuIds = [];
            $oldSku = db('goods_sku')->where([ 'goods_id' => $param['goods_id'] ])->select();
            $oldSkuIds = array_column($oldSku, 'goods_sku_id','goods_sku_id');
            $oldSkuNew = array_column($oldSku, null, 'goods_sku_id');
            # 精选修改检测
            if ( $goods['goods_recommend_type'] == State::STATE_GOODS_RECOMMEND_TYPE_TWO ) {
                // dump($goods['goods_state']);dump(State::STATE_GOODS_RECOMMEND_TYPE_TWO);
                foreach ($goods_sku as $key => $value) {
                    # 是否更改了属性
                    if ( false == isset($value['goods_sku_id']) ) {
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不允许添加和删除属性');
                    } else {
                        # 库存id检测
                        if ( false == isset($oldSkuNew[$value['goods_sku_id']]) ) {
                            # 出现重复或不存在的sku_id
                            throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不允许添加和删除属性');
                        } else {
                            # 精选不允许修改价格
                            if ( $value['goods_sku_price'] != $oldSkuNew[$value['goods_sku_id']]['goods_sku_price'] ) {
                                throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不允许更改销售价');
                            }
                            # 精选不允许减少库存
                            if ( $value['goods_sku_num'] < $oldSkuNew[$value['goods_sku_id']]['goods_sku_num'] ) {
                                // dump($value);dump($oldSkuNew);
                                throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不允许减少库存');
                            }
                            # 删除，最后检测是否刚刚好为0
                            unset($oldSkuNew[$value['goods_sku_id']]);
                        }
                    }
                }
                # 还有剩？，是不是删了一些库存
                if ( count($oldSkuNew) > 0 ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '精选商品不允许添加和删除属性');
                }
            }
            ### 添加属性库存记录
            foreach ($goods_sku as $key => $value) {
                # 处理属性
                $GoodsSkuAlbum = explode(',',trim((string)$value['goods_sku_album'], ','));
                foreach ($GoodsSkuAlbum as $ko => $vo) {
                    if ( empty($vo) ){
                        unset($GoodsSkuAlbum[$ko]);
                    }
                }
                $value['goods_sku_album'] = $GoodsSkuAlbum ? implode(',', $GoodsSkuAlbum) : '';
                
                $value['goods_sku_update_time'] = time();
                if ( isset($value['goods_sku_id']) ) {
                    $map = [
                        'goods_id'      => $model->goods_id,
                        'goods_sku_id'  => $value['goods_sku_id'],
                    ];
                    # 删除ids
                    unset($oldSkuIds[$value['goods_sku_id']]);
                    unset($value['goods_sku_id']);
                    # 循环用db的更新方法
                    $value['goods_sku_update_time'] = time();
                    // dump($value);exit;
                    if ( false === $model1->table('zr_goods_sku')->where($map)->update($value) ) {
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败70' );
                    }
                } else {
                    $value['goods_sku_update_time'] = time();
                    if ( false == $model1->table('zr_goods_sku')->insert($value) ){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败71' );
                    }
                }
            }
            # 删除不存在的旧库存
            if (count($oldSkuIds) > 0){
                if ( count($oldSkuIds) != $model1->table('zr_goods_sku')->where([ 'goods_sku_id' => ['in', $oldSkuIds] ])->delete() ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败4' );                
                }
            }
            ## 删除旧的商品参数
            $countGoodsParams = $goodsParams->where([ 'goods_id' => $param['goods_id'] ])->count();
            // dump($countGoodsParams);
            if ( $countGoodsParams != $model1->table('zr_goods_params')->where($deleteMap)->delete() ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败5' );                
            }
            ## 添加商品参数
            foreach ($goods_params as $key => $value) {
                $value['seller_user_id']    = $user_id;
                $value['shop_id']           = $shop_id;
                $value['goods_id']          = $model->goods_id;
                $value['params_create_time']= time();
                $value['params_update_time']= time();
                $group_value = $value['group_value'] ?? '';
                $value['group_value'] = is_array($group_value) ? implode(',', $group_value) : (string)$group_value;
                if ( false === $model1->table('zr_goods_params')->insert($value) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败8' );
                }
            }
            ## 删除旧资质和添加新商品资质
            $countGoodsQualifications = Fun::mApi('goods','GoodsQualifications')->where([ 'goods_id' => $param['goods_id'] ])->count();
            if ( $countGoodsQualifications != $model1->table('zr_goods_qualifications')->where([ 'goods_id' => $param['goods_id'] ])->delete() ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败9' );
            }
            foreach ($param['goods_qualifications'] ?? [] as $key => $value) {
                $value['seller_user_id']    = $user_id;
                $value['shop_id']           = $shop_id;
                $value['goods_id']          = $model->goods_id;
                $value['qualifications_create_time']    = time();
                $value['qualifications_update_time']    = time();
                $value['qualifications_value']          = json_encode(is_array($value['qualifications_value']) ? $value['qualifications_value'] : (string) $value['qualifications_value']);
                if ( false == $model1->table('zr_goods_qualifications')->insert($value) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '编辑商品失败10' );
                }
            }
            ### 删除旧缓存，商品详情的
            self::deleteDetailCache($model->goods_id);
            # ...
            $model->commit();
            $model1->commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            $model->rollback();
            $model1->rollback();
            return $e->getData();
        }
    }

    /**
     * @title 商家-商品详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int        |true   |0  |-  |用户id|
     * |goods_id        |int        |true   |0  |-  |商品id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function detail2()
    {
        try {
            # get param
            $id         = intval(request()->data['id']);
            $user_id    = intval(request()->user['user_id']);
            if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # get data
            $detail = Fun::dataDetail(Fun::mApi('goods','Goods'),[
                'relation'  => 'goods_sku2,goods_sku_group,goods_category,goods_content,goods_params',
                'where'     => [
                    'goods_id'          => $id,
                    'seller_user_id'    => $user_id,
                ],
            ]);
            if ( empty($detail) || $detail['goods_state'] == State::STATE_GOODS_DELETE ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            $detail['goods_sku_group1'] = Fun::array_divide_group($detail['goods_sku_group'], 'sku_group_name', 'sku_group_value', 'sku_group_id');
            // dump($detail['goods_sku_group']);exit;
            $detail['goods_sku_group2'] = Fun::array_divide_group($detail['goods_sku_group'], 'sku_group_name', 'sku_group_album', 'sku_group_id');
            # ...
            return $detail;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 商家-商品统计
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int        |true   |0  |-  |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function statistics()
    {
        try {
            # param
            $user_id = intval(request()->user['user_id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # ...
            $list = [];
            $cache_time = \mercury\constants\Common::TIME_MONENT;
            $list = [
                'up'                => db('goods')->cache(true, $cache_time)->where([ 'seller_user_id' => $user_id, 'goods_state' => State::STATE_GOODS_NORMAL ])->count(),
                'down'              => db('goods')->cache(true, $cache_time)->where([ 'seller_user_id' => $user_id, 'goods_state' => State::STATE_GOODS_UNDER ])->count(),
                'violation'         => db('goods')->cache(true, $cache_time)->where([ 'seller_user_id' => $user_id, 'goods_state' => State::STATE_GOODS_VIOLATION ])->count(),
                'very_violation'    => db('goods')->cache(true, $cache_time)->where([ 'seller_user_id' => $user_id, 'goods_state' => State::STATE_GOODS_VERY_VIOLATION ])->count(),
                'recommend'         => db('goods')->cache(true, $cache_time)->where([ 'seller_user_id' => $user_id, 'goods_state' => [ 'in' , array_keys(State::STATE_GOODS_USER_ARRAY) ], 'goods_recommend' => 1 ])->count(),
            ];
            # ...
            return $list;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 发布商品检测
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int        |true   |0  |-  |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function createCheck()
    {
        try {
            # get param
            $user_id = intval(request()->user['user_id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            $param['where'] = [
                'seller_user_id' => $user_id,
            ];
            $param2['where'] = [
                'seller_user_id'        => $user_id,
                'goods_category_state'  => State::STATE_NORMAL,
            ];
            # res
            $res = [
                [
                    'name'  => '运费模板',
                    'is'    => intval( false == empty(Fun::dataDetail(Fun::mApi('goods','GoodsExpressTpl'),$param))),
                    'url'   => '/express',
                ],
                [
                    'name'  => '包装模板',
                    'is'    => intval( false == empty(Fun::dataDetail(Fun::mApi('goods','GoodsPackageTpl'),$param))),
                    'url'   => '/package',
                ],
                [
                    'name'  => '售后模板',
                    'is'    => intval( false == empty(Fun::dataDetail(Fun::mApi('goods','GoodsProtectionTpl'),$param))),
                    'url'   => '/protection',
                ],
                [
                    'name'  => '店铺分类',
                    'is'    => intval( false == empty(Fun::dataDetail(Fun::mApi('goods','ShopGoodsCategory'),$param2))),
                    'url'   => '/category',
                ],
            ];
            # ...
            return $res;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 清除商品详情的缓存
     * @param string|array 商品id,逗号隔开或数组
     * @return [type] [description]
     */
    static function deleteDetailCache($goods_id)
    {
        $sku = Fun::dataAll(Fun::mApi('goods','GoodsSku'),[
            'where' => [ 'goods_id' => [ 'in', $goods_id ] ],
            'field' => 'goods_sku_id',
        ]);
        $skuIds = array_column($sku, 'goods_sku_id');
        foreach ($skuIds as $skuId) {
            Fun::redis()->del( Fun::getCacheName( Cache::GOODS_SKU_DETAIL ) . $skuId );
        }
    }
    
}