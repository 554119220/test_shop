<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\Cache;
use app\api\model\goods\ShopType as Model;

/**
 * Class ShopType
 * @package app\api\controller\goods\v1
 *
 * @title 店铺类型
 */

class ShopType
{

    /**
     * 后台更新redis
     * @return [type] [description]
     */
    static function toRedis1()
    {
        $param['where'] = [ 'shop_type_state' => State::STATE_NORMAL ];
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $param['order'] = (new Model)->getPk() . ' asc';
        $param['field'] = 'shop_type_id,shop_type_icon,shop_type_suffix,shop_band_num,shop_category_one_num,shop_category_two_num,shop_user_type,is_user_level,shop_qualifications';
        $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopType', $param);
        Fun::redis()->set(Fun::getCacheName( Cache::SHOP_TYPE_LIST ), serialize($data));
    }

    /**
     * 后台更新redis-详情
     * @return [type] [description]
     */
    static function toRedis2($shop_type_id = 0)
    {
        $id = !empty($shop_type_id) ? intval($shop_type_id) : 0;
        $key    = Fun::getCacheName(Cache::SHOP_TYPE_DETAIL . $id);
        $param['where'] = ['shop_type_id' => $id];
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $param['field'] = 'shop_type_id,shop_type_icon,shop_type_suffix,shop_band_num,shop_category_one_num,shop_category_two_num,shop_introduce,shop_user_type,is_user_level,shop_qualifications';
        $data = Fun::dataDetail('\\app\\api\\model\\goods\\ShopType', $param);
        Fun::redis()->set($key, serialize($data));
    }
    /**
     * @title 店铺类型列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺域名前缀|
     * |shop_type_icon|string|asdasd|店铺icon|
     * |shop_type_suffix|string|个体店|店铺后缀|
     * |shop_band_num|int|1|店铺可填品牌数|
     * |shop_category_one_num|int|0|店铺可填一级类目|
     * |shop_category_two_num|int|2|店铺可填二级类目|
     * |shop_user_type|int|1|开店的用户类型，1，企业用户；0，个人用户；2，个人和企业都可以|
     * |is_user_level|int|0|要求用户级别，0，不限制；1，消费商；2，盛客以上；3，盛投以上；|
     * |shop_qualifications|string|个体店|店铺资质类型|
     * |shop_introduce|string|个体店|店铺类型介绍|
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
	function index ()
	{
		try {
		    $data = Fun::redis()->get(Fun::getCacheName( Cache::SHOP_TYPE_LIST ));
		    if(!$data){
                $param['where'] = [ 'shop_type_state' => State::STATE_NORMAL ];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $param['order'] = (new Model)->getPk() . ' asc';
                $param['field'] = 'shop_type_id,shop_type_icon,shop_type_suffix,shop_band_num,shop_category_one_num,shop_category_two_num,shop_user_type,is_user_level,shop_qualifications';
                $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopType', $param);
                Fun::redis()->set(Fun::getCacheName( Cache::SHOP_TYPE_LIST ), serialize($data));
            }
            if (is_string($data)) $data = unserialize($data);
			return $data;
		} catch (ResponseException $e) {
			return $e->getData();
		}
	}

    /**
     * @title 店铺详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_type_id|int|true|12|-|店铺id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺域名前缀|
     * |shop_type_icon|string|asdasd|店铺icon|
     * |shop_type_suffix|string|个体店|店铺后缀|
     * |shop_band_num|int|1|店铺可填品牌数|
     * |shop_category_one_num|int|0|店铺可填一级类目|
     * |shop_category_two_num|int|2|店铺可填二级类目|
     * |shop_user_type|int|1|开店的用户类型，1，企业用户；0，个人用户；2，个人和企业都可以|
     * |is_user_level|int|0|要求用户级别，0，不限制；1，消费商；2，盛客以上；3，盛投以上；|
     * |shop_qualifications|string|个体店|店铺资质类型|
     * |shop_introduce|string|个体店|店铺类型介绍|
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
	function create ()
	{
		try {
			$param = request()->param();
			if ( false == (new Model)->allowField(true)->save($param)  ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL );
			}
			return [];
		} catch (ResponseException $e) {
			return $e->getData();
		}
	}

    /**
     * @title 店铺详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_type_id|int|true|12|-|店铺id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺域名前缀|
     * |shop_type_icon|string|asdasd|店铺icon|
     * |shop_type_suffix|string|个体店|店铺后缀|
     * |shop_band_num|int|1|店铺可填品牌数|
     * |shop_category_one_num|int|0|店铺可填一级类目|
     * |shop_category_two_num|int|2|店铺可填二级类目|
     * |shop_user_type|int|1|开店的用户类型，1，企业用户；0，个人用户；2，个人和企业都可以|
     * |is_user_level|int|0|要求用户级别，0，不限制；1，消费商；2，盛客以上；3，盛投以上；|
     * |shop_qualifications|string|个体店|店铺资质类型|
     * |shop_introduce|string|个体店|店铺类型介绍|
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
	public function detail(){
        try {
            $data2 = request()->data;
            $id = isset(request()->data['shop_type_id']) ? intval(request()->data['shop_type_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_TYPE_DETAIL . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = ['shop_type_id' => intval($data2['shop_type_id'])];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $param['field'] = 'shop_type_id,shop_type_icon,shop_type_suffix,shop_band_num,shop_category_one_num,shop_category_two_num,shop_introduce,shop_user_type,is_user_level,shop_qualifications';
                $data = Fun::dataDetail('\\app\\api\\model\\goods\\ShopType', $param);
                Fun::redis()->set($key, serialize($data));
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }







}