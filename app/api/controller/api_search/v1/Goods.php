<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 10:25
 */

namespace app\api\controller\search\v1;
use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\factory\SearchHistory;
use mercury\ResponseException;
use mercury\search\ElasticSearch;

/**
 * Class Goods
 * @package app\api\controller\search\v1
 *
 * @title 商品搜索
 */
class Goods extends Init
{
    public function __construct()
    {
        parent::__construct();
        $this->index    = ElasticSearch::goodsInstance();
        #$this->index    = ElasticSearch::testInstance();
        $index  = config('url_domain_root') == 'zrst.com' ? 'goods1' : 'goods';
        $this->params   = $this->index->setIndex($index)->getParams();
    }

    /**
     * @title 商品搜索
     *
     * @param int $rows 返回多少行数据
     * @param string $ZR_ID 用户cookie唯一标识
     * @param string $q 关键字
     * @param int $cate 类目搜索
     * @param int $brand 品牌搜索
     * @param float $price_min 最低价格
     * @param float $price_max 最高价格
     * @param string $order 排序
     *              sales   销量
     *              price_asc 价低至高
     *              price_desc 加高至低
     * @return int|mixed
     */
    /**
     * @title 搜索列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |rows|int|false|20|-|每页行数|
     * |q|string|false|apple|-|搜索关键字|
     * |cate|int|false|1|-|类目ID|
     * |brand|int|false|1|-|品牌ID|
     * |price_min|float|false|1.00|-|最低价|
     * |price_max|float|false|1.00|-|最高价|
     * |order|string|false|sales(销量),price_asc(价低),price_desc(价高),<br />shopping_score_asc,shopping_score_desc(购物积分抵扣),<br />new(最新),reward_score_asc,reward_score_desc(奖励积分),<br />fraction(评分)|-|排序|
     * |shop_id|int|false|1|-|店铺搜索|
     * |featured|int|false|1|-|精选|
     * |preferred|int|false|1|-|优选|
     * |self|int|false|1|-|自营|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |goods_score_multi|int|100|奖励积分倍数|
     * |goods_shopping_score_multi|int|20|可使用购物积分倍数|
     * |protection_id|int|1|售后模板|
     * |goods_extend_pr|float|5.0|扩展PR|
     * |goods_max_price|float|168.00|商品最大金额|
     * |goods_pr|float|10.0|商品PR|
     * |goods_recommend_type|int|1|商品推荐类型1，优选。2，精选|
     * |goods_comment_images_num|int|1|商品评价图片数量|
     * |goods_recommend|bool|1|是否为橱窗中的商品0否，1是|
     * |goods_browse_num|int|1|商品浏览次数|
     * |package_id|int|1|包装模板|
     * |goods_comment_good_num|int|1|评价数量|
     * |goods_service_days|int|1|售后周期|
     * |goods_comment_middle_num|int|1|中评数量|
     * |shop_goods_category_ids|string|1,2|商家类目|
     * |goods_is_self|bool|1|是否自营商品0否，1是|
     * |goods_category_id|int|1|所属3级类目|
     * |goods_name|string|中礼鲜花速递|商品名称|
     * |goods_sale_num|int|1|商品销量|
     * |goods_sub_name|string|中礼鲜花速递|商副标题|
     * |goods_id|int|1|商品id|
     * |goods_images|string|https://img.zrst.com/FueKAYJE5-RMJf8512cD65SWthyI|商品主图|
     * |goods_update_time|int|1516237176|商品更新时间|
     * |goods_attention_num|int|1|商品收藏数量|
     * |shop_id|int|1|所属店铺id|
     * |express_id|int|1|所属运费模板id|
     * |goods_comment_num|int|1|商品评价数量|
     * |seller_user_id|int|1|卖家id|
     * |goods_number|string|1|商品编号|
     * |goods_state|int|1|商品状态|
     * |goods_create_time|int|1515063563|商品创建时间|
     * |goods_sku_num|int|66|商品SKU库存数量|
     * |goods_min_price|float|66|商品最低金额|
     * |shop_goods_brand_id|int|66|商品所属品牌|
     * |goods_comment_poor_num|int|66|商品差评数量|
     * |shopping_score|float|66|最多可使用购物积分|
     * |reward_score|float|66|最多可奖励积分|
     * |sku_id|float|11512|SKU ID|
     * |goods_labels|array| ["优","精","自"]|商品标签|
     * |total|int|50|总数量|
     * |current_page|int|1|当前页码|
     * |last_page|int|1|总页码|
     * @response_example 响应示例
     * `data: {
            total: 56,
            current_page: 1,
            last_page: 3,
            data: [
                {
                    goods_score_multi: 100,
                    goods_shopping_score_multi: 20,
                    protection_id: 65,
                    goods_extend_pr: 0,
                    goods_max_price: 168,
                    goods_pr: 0,
                    goods_recommend_type: 2,
                    goods_comment_images_num: 0,
                    goods_recommend: 1,
                    goods_browse_num: 0,
                    package_id: 70,
                    goods_comment_good_num: 7,
                    goods_service_days: 1,
                    goods_comment_middle_num: 0,
                    shop_goods_category_ids: "171",
                    goods_is_self: 0,
                    goods_category_id: 124,
                    goods_name: "中礼鲜花速递 33枝红玫瑰花束 全国同城鲜花花店送花【指定日期送达】",
                    goods_sale_num: 34,
                    goods_sub_name: "",
                    goods_id: 385,
                    goods_images: "https://img.zrst.com/FueKAYJE5-RMJf8512cD65SWthyI",
                    goods_update_time: 1516237176,
                    goods_attention_num: 0,
                    shop_id: 138,
                    express_id: 147,
                    goods_comment_num: 7,
                    seller_user_id: 136,
                    goods_number: "",
                    goods_state: 1,
                    goods_create_time: 1515063563,
                    goods_sku_num: 66,
                    goods_min_price: 168,
                    shop_goods_brand_id: 0,
                    goods_comment_poor_num: 0,
                    shopping_score: "3,360.00",
                    reward_score: "16,800.00",
                    sku_id: 11512,
                    goods_labels: [
                        "优"
                    ]
                }
            ]
            msg: "请求成功",
            info: "success",
            code: 20000
        }`
     * @description 接口描述
     * > you are api description
     * @return int|mixed
     */
    public function index()
    {
        #   must是完全匹配，相当于AND
        #   bool must   =
        #   bool should or
        #   bool must_not !=
        #   range between   区间
        #   关键字搜索
        if (isset($this->data['q']) && !empty($this->data['q'])) {
            $q  = $this->data['q'];
            $this->params['body']['query']['bool']['must'][]['match']   = ['goods_name' => $q];
            #   设置记录
            $user_id = isset($this->user['user_id']) ? $this->user['user_id'] : '';
            SearchHistory::instance($user_id, $this->data['ZR_ID'])->bindUserId()->put($q);
        }

        #   类目搜索
        if (isset($this->data['cate']) && !empty($this->data['cate'])) {
            $cate   = $this->data['cate'];
            if (false !== strpos($cate, ',')) $cate   = explode(',', $cate);
            if (is_array($cate)) {
                foreach ($cate as   $v) {
                    $this->params['body']['query']['bool']['must'][]['match']  = ['goods_category_id' => $v];
                }
            } else {
                $this->params['body']['query']['bool']['must'][]['match']  = ['goods_category_id' => $cate];
            }
        }
        #   二级类目
        if (isset($this->data['cate2']) && !empty($this->data['cate2'])) {
            $cate2   = $this->data['cate2'];
            $this->params['body']['query']['bool']['must'][]['match']  = ['goods_category2_id' => $cate2];
        }

        #   包邮
        if (isset($this->data['free']) && !empty($this->data['free'])) {
            $this->params['body']['query']['bool']['must'][]['match']   = ['goods_is_free' => State::STATE_NORMAL];
        }

        #   必须是上架商品
        $this->params['body']['query']['bool']['must'][]['match']   = ['goods_state' => State::STATE_NORMAL];

        #   如果有传shop ID则把当前商家所有上线的商品全部取出来
        if (isset($this->data['shop_id'])) {
            #   shop_goods_category_ids
            $this->params['body']['query']['bool']['must'][]['match']   = ['shop_id' => $this->data['shop_id']];
            if (isset($this->data['sgcid'])) $this->params['body']['query']['bool']['must'][]['match']   = ['shop_goods_category_ids' => $this->data['sgcid']];
        } else {
            #   精选
            if (isset(request()->data['featured'])) {
                $featured   = true;
                $this->params['body']['query']['bool']['must'][]['match']   = ['goods_recommend_type' => State::STATE_NORMAL];
            }
            #   优选
            if (isset(request()->data['preferred'])) {
                $preferred  = true;
                $this->params['body']['query']['bool']['must'][]['match']   = ['goods_recommend_type' => State::STATE_REFUNDS];
            }

            #   热卖
            if (isset($this->data['hot']) && !empty($this->data['hot'])) {
                $hot  = true;
                $this->params['body']['query']['bool']['must'][]['match']   = ['goods_recommend_type' => 3];
            }

            #   今日必抢
            if (isset($this->data['day']) && !empty($this->data['day'])) {
                $day  = true;
                $this->params['body']['query']['bool']['must'][]['match']   = ['goods_recommend_type' => 4];
            }

            #   精选及优选
            if (!isset($featured) && !isset($preferred) && !isset($hot) && !isset($day)) {
                $this->params['body']['query']['bool']['must_not'][]['match']   = ['goods_recommend_type' => State::STATE_DISABLED];
//            $this->params['body']['query']['bool']['should'][]['match']   = ['goods_recommend_type' => State::STATE_REFUNDS];
            }
            #   自营
            if (isset(request()->data['self'])) {
                $this->params['body']['query']['bool']['must'][]['match']   = ['goods_is_self' => State::STATE_NORMAL];
            }
        }

        #   品牌搜索
        if (isset($this->data['brand']) && !empty($this->data['brand'])) {
            $brand  = $this->data['brand'];
            $this->params['body']['query']['bool']['must'][]['match']  = ['shop_goods_brand_id' => $brand];
        }

        #   价格筛选
        #   最低价
        if (isset($this->data['price_min']) && !empty($this->data['price_min'])) {
            $price_min  = $this->data['price_min'];
            $this->params['body']['query']['bool']['must'][]['range'] = ['goods_min_price' => ['gte' => $price_min]];
            #   大于当前价格
        }
        #   最高价
        if (isset($this->data['price_max']) && !empty($this->data['price_max'])) {
            $price_max  = $this->data['price_max'];
            $this->params['body']['query']['bool']['must'][]['range'] = ['goods_min_price' => ['lt' => round($price_max + 0.01, 2)]];
            #   小于当前价格
        }

        #   价格区间
        if (isset($price_max) && isset($price_min)) {
            #   价格区间
            $this->params['body']['query']['bool']['must'][]['range'] = ['goods_min_price' => ['gte' => $price_min, 'lt' => round($price_max + 0.01, 2)]];
        }

        #   最低积分
        if (isset($this->data['lowest_shopping_score'])) {
            $this->params['body']['query']['bool']['must'][]['range'] = ['goods_shopping_score_multi' => ['gt' => $this->data['lowest_shopping_score']]];
        }

        #   排序
        if (isset($this->data['order']) && !empty($this->data['order'])) {
            #   sales
            #   price_asc
            #   price_desc
            $order  = $this->data['order'];
            switch ($order) {
                case 'sales' :
                    $this->params['body']['sort']   = ['goods_sale_num' => 'desc'];
                    break;
                case 'price_asc':
                    $this->params['body']['sort']   = ['goods_min_price' => 'asc'];
                    break;
                case 'price_desc':
                    $this->params['body']['sort']   = ['goods_min_price' => 'desc'];
                    break;
                case 'new':
                    $this->params['body']['sort']   = ['goods_create_time' => 'desc'];
                    break;
                case 'shopping_score_asc':
                    $this->params['body']['sort']   = ['goods_shopping_score_multi' => 'asc'];
                    break;
                case 'shopping_score_desc':
                    $this->params['body']['sort']   = ['goods_shopping_score_multi' => 'desc'];
                    break;
                case 'reward_score_asc':
                    $this->params['body']['sort']   = ['goods_score_multi' => 'asc'];
                    break;
                case 'reward_score_desc':
                    $this->params['body']['sort']   = ['goods_score_multi' => 'desc'];
                    break;
                case 'fraction':
                    $this->params['body']['sort']   = ['goods_comment_good_num' => 'desc'];
                    break;
                case 'comment':
                    $this->params['body']['sort']   = ['goods_comment_num' => 'desc'];
                    break;
                case 'attention':
                    $this->params['body']['sort']   = ['goods_attention_num' => 'desc'];
                    break;
            }
            #   排序
            if (isset($this->data['sort']) && !empty($this->data['sort'])) {
                $this->params['body']['sort']   = [$this->data['order'] => $this->data['sort']];
            }
        }

        if (isset($this->params['body']['sort'])) {
            $this->params['body']['sort']   = array_merge($this->params['body']['sort'], ['goods_recommend_type' => 'asc']);
        } else {
            #$this->params['body']['sort']   = ['goods_recommend_type' => 'asc'];
        }

//        else {
//            $this->params['body']['sort']   = ['goods_recommend_type' => 'asc'];
//        }

        #   分页  默认为第一页
        $page   = 1;
        if (isset($this->data['page']) && !empty($this->data['page'])) {
            $page   = intval($this->data['page']) ? : 1;
        }
        --$page;
        $size   = isset($this->data['rows']) ?
            ($this->data['rows'] > self::ROWS_MAX_SIZE ? self::ROWS_MAX_SIZE : $this->data['rows']) :
            self::ROWS_SIZE;
        $this->params['from']   = $page * $size;  #   start
        $this->params['size']   = $size;
        $res    = $this->index->search($this->params);
        if ($res['hits']['total'] > 0) return $this->parseData($res['hits']);
        return Code::CODE_NO_CONTENT;
    }

    /**
     * @title 商品推荐
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |rows|int|false|20|-|每页行数|
     * |q|string|false|apple|-|搜索关键字|
     * |cate|int|false|1|-|类目ID|
     * |brand|int|false|1|-|品牌ID|
     * |price_min|float|false|1.00|-|最低价|
     * |price_max|float|false|1.00|-|最高价|
     * |order|string|false|sales(销量),price_asc(价低),price_desc(价高),shopping_score_asc,shopping_score_desc(购物积分抵扣),new(最新),reward_score_asc,reward_score_desc(奖励积分),fraction(评分)|-|排序|
     * |shop_id|int|false|1|-|店铺搜索|
     * |featured|int|false|1|-|精选|
     * |preferred|int|false|1|-|优选|
     * |self|int|false|1|-|自营|
     * |openid|string|false|1|-|用户|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |goods_score_multi|int|100|奖励积分倍数|
     * |goods_shopping_score_multi|int|20|可使用购物积分倍数|
     * |protection_id|int|1|售后模板|
     * |goods_extend_pr|float|5.0|扩展PR|
     * |goods_max_price|float|168.00|商品最大金额|
     * |goods_pr|float|10.0|商品PR|
     * |goods_recommend_type|int|1|商品推荐类型1，优选。2，精选|
     * |goods_comment_images_num|int|1|商品评价图片数量|
     * |goods_recommend|bool|1|是否为橱窗中的商品0否，1是|
     * |goods_browse_num|int|1|商品浏览次数|
     * |package_id|int|1|包装模板|
     * |goods_comment_good_num|int|1|评价数量|
     * |goods_service_days|int|1|售后周期|
     * |goods_comment_middle_num|int|1|中评数量|
     * |shop_goods_category_ids|string|1,2|商家类目|
     * |goods_is_self|bool|1|是否自营商品0否，1是|
     * |goods_category_id|int|1|所属3级类目|
     * |goods_name|string|中礼鲜花速递|商品名称|
     * |goods_sale_num|int|1|商品销量|
     * |goods_sub_name|string|中礼鲜花速递|商副标题|
     * |goods_id|int|1|商品id|
     * |goods_images|string|https://img.zrst.com/FueKAYJE5-RMJf8512cD65SWthyI|商品主图|
     * |goods_update_time|int|1516237176|商品更新时间|
     * |goods_attention_num|int|1|商品收藏数量|
     * |shop_id|int|1|所属店铺id|
     * |express_id|int|1|所属运费模板id|
     * |goods_comment_num|int|1|商品评价数量|
     * |seller_user_id|int|1|卖家id|
     * |goods_number|string|1|商品编号|
     * |goods_state|int|1|商品状态|
     * |goods_create_time|int|1515063563|商品创建时间|
     * |goods_sku_num|int|66|商品SKU库存数量|
     * |goods_min_price|float|66|商品最低金额|
     * |shop_goods_brand_id|int|66|商品所属品牌|
     * |goods_comment_poor_num|int|66|商品差评数量|
     * |shopping_score|float|66|最多可使用购物积分|
     * |reward_score|float|66|最多可奖励积分|
     * |sku_id|float|11512|SKU ID|
     * |goods_labels|array| ["优","精","自"]|商品标签|
     * |total|int|50|总数量|
     * |current_page|int|1|当前页码|
     * |last_page|int|1|总页码|
     * @response_example 响应示例
     * `data: {
            total: 56,
            current_page: 1,
            last_page: 3,
            data: [
                {
                    goods_score_multi: 100,
                    goods_shopping_score_multi: 20,
                    protection_id: 65,
                    goods_extend_pr: 0,
                    goods_max_price: 168,
                    goods_pr: 0,
                    goods_recommend_type: 2,
                    goods_comment_images_num: 0,
                    goods_recommend: 1,
                    goods_browse_num: 0,
                    package_id: 70,
                    goods_comment_good_num: 7,
                    goods_service_days: 1,
                    goods_comment_middle_num: 0,
                    shop_goods_category_ids: "171",
                    goods_is_self: 0,
                    goods_category_id: 124,
                    goods_name: "中礼鲜花速递 33枝红玫瑰花束 全国同城鲜花花店送花【指定日期送达】",
                    goods_sale_num: 34,
                    goods_sub_name: "",
                    goods_id: 385,
                    goods_images: "https://img.zrst.com/FueKAYJE5-RMJf8512cD65SWthyI",
                    goods_update_time: 1516237176,
                    goods_attention_num: 0,
                    shop_id: 138,
                    express_id: 147,
                    goods_comment_num: 7,
                    seller_user_id: 136,
                    goods_number: "",
                    goods_state: 1,
                    goods_create_time: 1515063563,
                    goods_sku_num: 66,
                    goods_min_price: 168,
                    shop_goods_brand_id: 0,
                    goods_comment_poor_num: 0,
                    shopping_score: "3,360.00",
                    reward_score: "16,800.00",
                    sku_id: 11512,
                    goods_labels: [
                    "优"
                    ]
                }
            ]
            msg: "请求成功",
            info: "success",
            code: 20000
        }`
     * @description 接口描述
     * > you are api description
     * @return int|mixed
     */
    public function like()
    {
        try {
            #   精准
            $goods  = [];
            if (isset($this->user['user_id'])) {
                $key    = F::getCacheName(Cache::USER_BROWSE_HISTORY . $this->user['user_id']);
                $cate   = F::redis()->hgetall($key);
                if (!empty($cate)) {
                    $this->data['cate'] = $cate;
                    $ret    = $this->index();
                    if (is_array($ret)) {
                        $goods  = $ret;
                    }
                }
            }
            #   如果cate为空，则生成随机的cate
            if (empty($goods)) {
                $ret    = $this->index();
                if (!is_array($ret)) throw new ResponseException($ret);
                $goods  = $ret;
            }
            return $goods;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 记录
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
    public function push()
    {
        try {
            if (isset($this->user['user_id'])) {
                $key    = F::getCacheName(Cache::USER_BROWSE_HISTORY . $this->user['user_id']);
                if (false == F::redis()->hexists($key, $this->data['category'])) {
                    F::redis()->hset($key, $this->data['category'], $this->data['category']);
                }
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 获取历史搜索
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|false|-|-|用户|
     * |ZR_ID|string|false|-|-|用户唯一标识与openid必传一个|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |data|array|["iPhone","哈哈"]|历史搜索|
     * @response_example 响应示例
     * `{
        data: [
            "iPhone",
            "哈哈"
        ],
        msg: "请求成功",
        info: "success",
        code: 20000
        }`
     * @description 接口描述
     * > you are api description
     * @return array
     */
    public function getHistory()
    {
        try {
            $user_id = isset($this->user['user_id']) ? $this->user['user_id'] : '';
            $data   = SearchHistory::instance($user_id, $this->data['ZR_ID'])->bindUserId()->get();
            if (empty($data)) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 热搜关键字
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
    public function getHotKeywords()
    {
        try {
            $key    = Cache::SEARCH_KEYWORDS;
            $redis  = F::redis();
            $data   = $redis->zRevRange($key, 0, 19, 'WITHSCORES');
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 清空历史搜索
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |openid|string|false|-|-|用户OPENID|
     * |ZR_ID|string|false|-|-|用户唯一标识与用户OPENID必传一个|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |-|-|-|-|
     * @response_example 响应示例
     * `{
            data: [],
            msg: "请求成功",
            info: "success",
            code: 20000
        }`
     * @description 接口描述
     * > you are api description
     * @return array|int
     */
    public function flush()
    {
        $user_id = isset($this->user['user_id']) ? $this->user['user_id'] : '';
        return SearchHistory::instance($user_id, $this->data['ZR_ID'])->flush();
    }
}