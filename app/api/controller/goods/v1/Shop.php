<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\Shop as Model;
use app\api\model\goods\ShopGoodsBrand;
use app\api\model\goods\ShopGoodsCategory;

/**
 * Class Shop
 * @package app\api\controller\goods\v1
 *
 * @title 店铺
 */
class Shop
{



    /**
     * @title 店铺详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_id|int|true|5|-|-|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_id|int|5|-|
     * |shop_create_time|string|2017-12-24 18:24:30|店铺创建时间|
     * |shop_update_time|string|2018-01-23 12:19:55|店铺更新时间|
     * |shop_state|int|1|0已删除，1正常，2已冻结，3已关闭，4已被强制关闭|
     * |shop_name|string|再次测试来着|店铺名称|
     * |shop_tel|string|13178865989|店铺电话|
     * |shop_mobile|string|18576380995|店铺手机|
     * |shop_domain|string|18576380995|店铺域名前缀|
     * |shop_contect_person|string|18576380995|联系人|
     * |shop_province_id|int|866|身份id|
     * |shop_city_id|int|867|城市id|
     * |shop_district_id|int|874|县区id|
     * |shop_town_id|int|12927|街道id|
     * |shop_street|string|手电收点多多多多dsfsd|街道地址|
     * |shop_email|string|13178865989|邮箱|
     * |shop_description|string|介绍|店铺介绍|
     * |user_id|int|12|用户id|
     * |shop_type_id|int|12|店铺类型ID|
     * |shop_service_fraction|string|4.80|店铺服务评分|
     * |shop_logistics_fraction|string|4.80|店铺物流评分|
     * |shop_description_fraction|string|4.80|店铺描述评分|
     * |shop_synthesis_fraction|string|4.80|店铺中和评分|
     * |shop_goods_num|int|120|店铺商品总数量|
     * |shop_logo|string|http://oz3fjflhn.bkt.clouddn.com/FpjLinxhBub_g3VIooZfxfRxMSbo|店铺logo|
     * |goods_category_level1|string|80,12|开店一级类目|
     * |goods_category_ids|string|4,80|开店二级类目|
     * |shop_qq|int|4.80|店铺qq|
     * |shop_aliname|string|asdasdas|阿里旺旺|
     * |goods_comment_num|int|0|评价数量|
     * |goods_comment_good_num|int|0|好评数量|
     * |goods_comment_middle_num|int|0|中评数量|
     * |goods_comment_poor_num|int|0|差评数量|
     * |goods_browse_num|int|12|商品总浏览数量|
     * |shop_browse_num|int|0|店铺总浏览数量|
     * |shop_attention_num|int|0|店铺总收藏数量|
     * |goods_attention_num|int|0|商品总收藏数量|
     * |goods_num|int|0|商品总数量|
     * |goods_sales_num|int|0|商品总销量|
     * |goods_in_sales_num|int|0|在售商品数量|
     * |shop_level|int|0|商家等级|
     * |shop_pr|string|4.80|商家PR|
     * |shop_extend_pr|string|4.80|扩展PR|
     * |shop_basis_score|int|4.80|商家目前分数，默认为48分,扣完关店|
     * |shop_level_score|int|0|店铺等级分数，好评+1分，差评减一分，中评保持不变|
     * |shop_window_num|int|0|橱窗位，默认30个|
     * |orders_wait_ship_num|int|0|待发货数量|
     * |orders_wait_pay_num|int|0|待付款数量|
     * |orders_wait_comment_num|int|0|待评价数量|
     * |orders_wait_receive_num|int|0|待收货数量|
     * |goods_wait_sales_num|int|0|店铺商品待上架数量|
     * |goods_window_num|int|0|橱窗中数量|
     * |goods_violation_num|int|0|违规数量|
     * |shop_end_time|int|01315646|店铺关闭截至时间|
     * @response_example 响应示例
     * `{
    "data": {
    "shop_id": 47,
    "shop_create_time": "2017-12-24 18:24:30",
    "shop_update_time": "2018-01-23 12:19:55",
    "shop_state": 1,
    "shop_name": "再次测试来着",
    "shop_tel": "13178865989",
    "shop_mobile": "18576380995",
    "shop_domain": "adasd234fd",
    "shop_contect_person": "你猜猜",
    "shop_province_id": 866,
    "shop_city_id": 867,
    "shop_district_id": 874,
    "shop_town_id": 12927,
    "shop_street": "手电收点多多多多dsfsdf",
    "shop_email": "13178865989#qq.com",
    "shop_description": " 鞍山市所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所所",
    "user_id": 98,
    "shop_type_id": 2,
    "shop_service_fraction": "4.80",
    "shop_logistics_fraction": "4.80",
    "shop_description_fraction": "4.80",
    "shop_synthesis_fraction": "4.80",
    "shop_goods_num": 0,
    "shop_logo": "http:\/\/oz3fjflhn.bkt.clouddn.com\/FpjLinxhBub_g3VIooZfxfRxMSbo",
    "goods_category_level1": "7",
    "goods_category_ids": "26",
    "shop_qq": 234234234,
    "shop_aliname": "asdasdas",
    "goods_comment_num": 0,
    "goods_comment_good_num": 0,
    "goods_comment_middle_num": 0,
    "goods_comment_poor_num": 0,
    "goods_browse_num": 0,
    "shop_browse_num": 0,
    "shop_attention_num": 0,
    "goods_attention_num": 0,
    "goods_num": 0,
    "goods_sales_num": 0,
    "goods_in_sales_num": 0,
    "shop_level": 0,
    "shop_pr": "0.00",
    "shop_extend_pr": "0.00",
    "shop_basis_score": 48,
    "shop_level_score": 0,
    "shop_window_num": 30,
    "orders_wait_ship_num": 0,
    "orders_wait_pay_num": 0,
    "orders_wait_comment_num": 0,
    "orders_wait_receive_num": 0,
    "goods_wait_sales_num": 0,
    "goods_window_num": 0,
    "goods_violation_num": 0,
    "is_lock": 0,
    "shop_end_time": 0,
    "province_name": "上海",
    "city_name": "市辖区",
    "district_name": "闸北区",
    "town_name": "大宁路街道",
    "shop_url": "\/shop?id=47"
    },
    "msg": "请求成功",
    "info": "success",
    "code": 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function detail()
    {
        try {
            # get param
            $shop_id = intval(request()->data['shop_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # find data
            $shop  = Fun::dataDetail(Fun::mApi('goods', 'Shop'), $shop_id);
            if ( empty($shop) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 结果处理
            $shop['shop_url']   = Fun::shop_url($shop['shop_id'], $shop['shop_domain']);
            # ...
            return $shop;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 修改店铺信息
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_id|int|true|5|-|店铺id|
     * |shop_name|string|true|5|-|店铺店铺名称|
     * |shop_tel|int|true|1317445465|-|店铺手机号码|
     * |shop_contect_person|string|true|5|-|联系人|
     * |shop_province_id|int|true|5|-|省份id|
     * |shop_city_id|int|true|5|-|城市id|
     * |shop_district_id|int|true|5|-|县区id|
     * |shop_town_id|int|false|5|-|镇/街道id|
     * |shop_street|string|true|5|-|详细地址|
     * |shop_email|string|true|5|-|邮箱地址|
     * |shop_description|string|true|5|-|店铺介绍|
     * |shop_logo|string|true|FuwYF1Ni8z-GkDSiEJ9-xKv7tWe8|-|店铺logo|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function update()
    {
        try {
            # get param
            $param = request()->data;
            $shop_id = intval(request()->user['user_shop_id']) ?? 0;
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # ...
            $param['shop_province_id'] = $param['province_id'];
            $param['shop_city_id'] = $param['city_id'];
            $param['shop_district_id'] = $param['district_id'];
            $param['shop_town_id'] = $param['town_id'];
            $allowField[] = 'shop_tel';
            $allowField[] = 'shop_mobile';
            $allowField[] = 'shop_contect_person';
            $allowField[] = 'shop_province_id';
            $allowField[] = 'shop_city_id';
            $allowField[] = 'shop_district_id';
            $allowField[] = 'shop_town_id';
            $allowField[] = 'shop_street';
            $allowField[] = 'shop_email';
            $allowField[] = 'shop_description';
            $allowField[] = 'shop_logo';
            $allowField[] = 'shop_aliname';
            $allowField[] = 'shop_qq';
            $allowField[] = 'shop_domain';
            if ( false === (new Model)->allowField($allowField)->save($param,['shop_id' => $shop_id]) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }


    /**
     * @title 设置域名前缀
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_shop_id|int|true|5|-|店铺id|
     * |shop_domain|string|true|5|-|店铺域名前缀|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function set_domain ()
    {
        try {
            # get param
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            $shop_domain = request()->data['shop_domain'] ?? '';
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            $data['shop_domain'] = $shop_domain;
            $chcke_shop_domain = Fun::vApi('goods','Shop');
            if(  true !== $chcke_shop_domain->scene('set_domain')->check($data)){
                throw new ResponseException( Code::CODE_OTHER_FAIL,$chcke_shop_domain->getError() );
            }
            # ...
            //$is = Fun::dataDetail('\\app\\api\\model\\goods\\Shop', [ 'where' => [ 'shop_domain' => $shop_domain ]]);
            $model = new Model;
            $model->startTrans();
            # 是否已存在
            $is = $model->where([ 'shop_domain' => $shop_domain ])->field('shop_id')->find();
            if ( $is ) {
                $model->rollback();
                throw new ResponseException( Code::CODE_OTHER_FAIL , '域名已存在');
            }
            $allowField[] = 'shop_domain';
            $param = request()->data;
            # 修改失败
            if ( false === $model->allowField($allowField)->save(['shop_domain' => $shop_domain],['shop_id' => $shop_id]) ) {
                $model->rollback();
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            $model->commit();
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |pagesize|int|false|5|-|分页大小|
     * |page|int|true|5|-|页码|
     * |order|string|false|5|-|排序|
     * |q|string|false|5|-|关键词搜索|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    function shop_list()
    {
        try {
            # ...
            $param = request()->data;
            # 查询条件处理
            $param['where'] = [
                'shop_state'        => State::STATE_NORMAL,
                'shop_goods_num'    => [ 'gt', State::STATE_DISABLED ],
            ];
            if ( isset($param['q']) ) {
                $param['where']['shop_name'] = [ 'like', $param['q'] ];
            }
            $param['cache']         = true;
            $param['cache_time']    = \mercury\constants\Common::TIME_MONENT;
            $param['order']         = $param['order'] ?? 'shop_service_fraction desc';
            $param['pagesize']      = intval($param['pagesize'] ?? 10);
            $param['relation']      = 'Goods';
            $list = Fun::pageList('\\app\\api\\model\\goods\\Shop', $param);
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 地区信息
            $area = [];
            # 结果处理
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['shop_logo']    = Fun::thumbnail($value['shop_logo'], $param['imgsize'] ?? 100 );
                $list['data'][$key]['province']     = $area[$value['shop_province_id']] ?? '';
                $list['data'][$key]['city']         = $area[$value['shop_city_id']] ?? '';
                $list['data'][$key]['shop_url']     = Fun::shop_url($value['shop_id'], $value['shop_domain']);
            }
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 开店 - 分类列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_shop_id|int|true|5|-|店铺id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    function category_list(){
        try {
            # get param
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            // $shop_id = 1;
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # get data
            $shop = Fun::dataDetail(Fun::mApi('goods','Shop'),$shop_id);
            if ( empty($shop) || empty($shop['goods_category_ids']) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            $list = Fun::dataAll(Fun::mApi('goods','GoodsCategory'),[
                'field' => 'category_id,category_sid,category_name',
                'order' => 'category_sort desc,category_id asc',
                'cache' => true,
                'where' => [
                    'category_state'    => State::STATE_NORMAL,
                    'category_id'       => [ 'in', array_merge(explode(",", $shop['goods_category_ids']), explode(",", $shop['goods_category_level1'])) ],
                ],
                'cache_time' => \mercury\constants\Common::TIME_MONENT,
            ]);
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            $list = Fun::array_tree($list,[
                'id'    => 'category_id',
                'pid'   => 'category_sid',
                'level' => 2,
            ]);
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 店铺昨日数据统计
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_shop_id|int|true|5|-|店铺id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function yesToDayTotal()
    {
        try {
            $data   = db('shop_statistics')->where(['shop_id' => request()->user['user_shop_id']])
                ->order('statistics_day desc')->field('statistics_update_time,statistics_create_time,seller_user_id,shop_id,statistics_id,statistics_day', true)->cache(true)->find();
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}