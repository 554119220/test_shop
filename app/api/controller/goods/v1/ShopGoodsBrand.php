<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
/**
 * Class ShopGoodsBrand
 * @package app\api\controller\goods\v1
 *
 * @title 店铺商品品牌
 */
class ShopGoodsBrand
{
    /**
     * @title 店铺品牌列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_shop_id|int|true|12|-|店铺id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_goods_brand_id|int|12|id|
     * |seller_user_id|int|1|商家id|
     * |brand_id|int|1|品牌id|
     * |shop_id|int|1|店铺id|
     * |shop_goods_brand_state|int|1|状态0禁用，1正常|
     * |shop_goods_brand_qualifications|int|1|授权资质图片（默认取的第一张资质图片）|
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
    public function index()
    {
        try {
        	# get param
        	$shop_id = intval(request()->user['user_shop_id'] ?? 0);
        	if ( State::STATE_NORMAL > $shop_id ) {
        		throw new ResponseException( Code::CODE_NO_CONTENT );
        	}
        	# get data
        	$param['where'] = [ 'shop_id' => $shop_id, 'shop_goods_brand_state' => State::STATE_NORMAL ];
        	// $param['cache'] = true;
        	// $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        	// $param['order'] = 'goods_category_sort desc';
            $param['relation'] = 'goods_brand';
        	$brand = Fun::dataAll(Fun::mApi('goods','ShopGoodsBrand'), $param);
        	# no data
        	if ( empty($brand) ) {
        		throw new ResponseException( Code::CODE_NO_CONTENT );
        	}
        	# ...
        	return $brand;
        } catch (ResponseException $e) {
        	return $e->getData();
        }
    }
}