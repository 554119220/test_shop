<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Cache;
use mercury\constants\State;

/**
 * Class ShopQualifications
 * @package app\api\controller\goods\v1
 *
 * @title 店铺资质
 */

class ShopQualifications
{
    /**
     * 后台更新redis
     * @return [type] [description]
     */
    static function toRedis1($shop_type_id = 0)
    {
        $id = !empty($shop_type_id) ? intval($shop_type_id) : 0;
        $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_LIST . $id);
        $param['where'] = [ 'shop_type_id' => $id,'shop_qualifications_status'=>State::STATE_NORMAL,'type'=>0];
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
        Fun::redis()->set($key, serialize($data));
    }

    /**
     * 后台更新redis3
     * @return [type] [description]
     */
    static function toRedis3($data2)
    {
        $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_LIST.$data2['type'].':' . $data2['shop_type_id']);
        $shop_qualifications_id = 0;
        if(!empty($data2['shop_qualifications_id'])){
            $shop_qualifications_id = $data2['shop_qualifications_id'];
        }
        $param['sql'] = 'shop_qualifications_id in('.$shop_qualifications_id.')';
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
        Fun::redis()->set($key, serialize($data));
    }



    /**
     * 后台更新redis2
     * @return [type] [description]
     */
    static function toRedis2($shop_type_id = 0)
    {
        $id = !empty($shop_type_id) ? intval($shop_type_id) : 0;
        $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_TYPE_LIST . $id);
        $param['where'] = [ 'shop_type_id' => $id,'shop_qualifications_status'=>State::STATE_NORMAL,'type'=>1];
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
        Fun::redis()->set($key, serialize($data));
    }

    /**
     * 后台更新redis2
     * @return [type] [description]
     */
    static function toRedis4($shop_qualifications_id = 0)
    {
        $id = !empty($shop_qualifications_id) ? intval($shop_qualifications_id) : 0;
        $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_LIST .'new:'. $id);
        $param['where'] = [ 'shop_qualifications_id' => intval($id)];
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $data = Fun::dataDetail('\\app\\api\\model\\goods\\ShopQualifications', $param);
        Fun::redis()->set($key, serialize($data));
    }
    /**
     * @title 店铺资质详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_type_id|int|true|12|-|店铺类型id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺类型id|
     * |shop_qualifications_id|int|asdasd|id|
     * |shop_type_id|int|1|店铺类型id(已废弃)|
     * |shop_brand_type|int|1|类型，1，自有品牌；2，代理品牌|
     * |shop_qualifications_name|string|asda|资质名称|
     * |shop_qualifications_describe|string|asdas|资质描述|
     * |shop_qualifications_images|string|asdasda|资质样图|
     * |shop_is_content|int|0|是否需要文字填写，1需要；0，不需要|
     * |shop_qualifications_status|int|0|状态，1，正常；0，禁用|
     * |type|int|0|资质类型，1，行业资质；0，品牌资质；2，用户资质|
     * |is_must|int|0|是否必须：1，必须；0，选填|
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
            $id = isset($data2['shop_type_id']) ? intval($data2['shop_type_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_LIST . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'shop_type_id' => intval($data2['shop_type_id'])];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $data = Fun::dataDetail('\\app\\api\\model\\goods\\ShopQualifications', $param);
                Fun::redis()->set($key, serialize($data));
                //$param['field'] = 'shop_is_content,shop_qualifications_name,shop_article_images';
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺资质详情2
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_qualifications_id|int|true|12|-|资质id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺类型id|
     * |shop_qualifications_id|int|asdasd|id|
     * |shop_type_id|int|1|店铺类型id(已废弃)|
     * |shop_brand_type|int|1|类型，1，自有品牌；2，代理品牌|
     * |shop_qualifications_name|string|asda|资质名称|
     * |shop_qualifications_describe|string|asdas|资质描述|
     * |shop_qualifications_images|string|asdasda|资质样图|
     * |shop_is_content|int|0|是否需要文字填写，1需要；0，不需要|
     * |shop_qualifications_status|int|0|状态，1，正常；0，禁用|
     * |type|int|0|资质类型，1，行业资质；0，品牌资质；2，用户资质|
     * |is_must|int|0|是否必须：1，必须；0，选填|
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
    public function detail2(){
        try {
            $data2 = request()->data;
            $id = isset($data2['shop_qualifications_id']) ? intval($data2['shop_qualifications_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_LIST .'new:'. $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'shop_qualifications_id' => intval($data2['shop_qualifications_id'])];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $data = Fun::dataDetail('\\app\\api\\model\\goods\\ShopQualifications', $param);
                Fun::redis()->set($key, serialize($data));
                //$param['field'] = 'shop_is_content,shop_qualifications_name,shop_article_images';
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺资质列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_qualifications_id|string|true|12|-|资质id（多个用逗号隔开）|
     * |shop_type_id|int|true|12|-|店铺类型id|
     * |type|string|true|asdasad|-|资质类型，shop_type_brand1（自由品牌）shop_type_brand2（代理品牌）shop_type_industry（行业资质）shop_type_member（用户资质）|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺类型id|
     * |shop_qualifications_id|int|asdasd|id|
     * |shop_type_id|int|1|店铺类型id(已废弃)|
     * |shop_brand_type|int|1|类型，1，自有品牌；2，代理品牌|
     * |shop_qualifications_name|string|asda|资质名称|
     * |shop_qualifications_describe|string|asdas|资质描述|
     * |shop_qualifications_images|string|asdasda|资质样图|
     * |shop_is_content|int|0|是否需要文字填写，1需要；0，不需要|
     * |shop_qualifications_status|int|0|状态，1，正常；0，禁用|
     * |type|int|0|资质类型，1，行业资质；0，品牌资质；2，用户资质|
     * |is_must|int|0|是否必须：1，必须；0，选填|
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
    public function index5(){
        try {
            $data2 = request()->data;
            $id = isset($data2['shop_qualifications_id']) ? $data2['shop_qualifications_id'] : 0;
            $type = isset($data2['type']) ? $data2['type'] : 0 ;
            $shop_type_id = $data2['shop_type_id'];
            $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_LIST.$type.':' . $shop_type_id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['sql'] = 'shop_qualifications_id in('.$data2['shop_qualifications_id'].')';
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
                Fun::redis()->set($key, serialize($data));
                //$param['field'] = 'shop_is_content,shop_qualifications_name,shop_article_images';
            }
            if (is_string($data)) $data = unserialize($data);

            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺资质列表（品牌资质）
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_type_id|int|true|12|-|店铺类型id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺类型id|
     * |shop_qualifications_id|int|asdasd|id|
     * |shop_type_id|int|1|店铺类型id(已废弃)|
     * |shop_brand_type|int|1|类型，1，自有品牌；2，代理品牌|
     * |shop_qualifications_name|string|asda|资质名称|
     * |shop_qualifications_describe|string|asdas|资质描述|
     * |shop_qualifications_images|string|asdasda|资质样图|
     * |shop_is_content|int|0|是否需要文字填写，1需要；0，不需要|
     * |shop_qualifications_status|int|0|状态，1，正常；0，禁用|
     * |type|int|0|资质类型，1，行业资质；0，品牌资质；2，用户资质|
     * |is_must|int|0|是否必须：1，必须；0，选填|
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
    public function index(){
        try {
            $data2 = request()->data;
            $id = isset($data2['shop_type_id']) ? intval($data2['shop_type_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_LIST . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'shop_type_id' => intval($data2['shop_type_id']) ,'shop_qualifications_status'=>State::STATE_NORMAL,'type'=>0];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
                Fun::redis()->set($key, serialize($data));
                //$param['field'] = 'shop_is_content,shop_qualifications_name,shop_article_images';
           }
            if (is_string($data)) $data = unserialize($data);

            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺资质列表（行业资质）
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_type_id|int|true|12|-|店铺类型id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺类型id|
     * |shop_qualifications_id|int|asdasd|id|
     * |shop_type_id|int|1|店铺类型id(已废弃)|
     * |shop_brand_type|int|1|类型，1，自有品牌；2，代理品牌|
     * |shop_qualifications_name|string|asda|资质名称|
     * |shop_qualifications_describe|string|asdas|资质描述|
     * |shop_qualifications_images|string|asdasda|资质样图|
     * |shop_is_content|int|0|是否需要文字填写，1需要；0，不需要|
     * |shop_qualifications_status|int|0|状态，1，正常；0，禁用|
     * |type|int|0|资质类型，1，行业资质；0，品牌资质；2，用户资质|
     * |is_must|int|0|是否必须：1，必须；0，选填|
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
    public function index2(){
        try {
            $data2 = request()->data;
            $id = isset($data2['shop_type_id']) ? intval($data2['shop_type_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_TYPE_LIST . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'shop_type_id' => intval($data2['shop_type_id']) ,'shop_qualifications_status'=>State::STATE_NORMAL,'type'=>1];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
                Fun::redis()->set($key, serialize($data));
                //$param['field'] = 'shop_is_content,shop_qualifications_name,shop_article_images';
            }
            if (is_string($data)) $data = unserialize($data);

            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺资质列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_type_id|int|true|12|-|店铺类型id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺类型id|
     * |shop_qualifications_id|int|asdasd|id|
     * |shop_type_id|int|1|店铺类型id(已废弃)|
     * |shop_brand_type|int|1|类型，1，自有品牌；2，代理品牌|
     * |shop_qualifications_name|string|asda|资质名称|
     * |shop_qualifications_describe|string|asdas|资质描述|
     * |shop_qualifications_images|string|asdasda|资质样图|
     * |shop_is_content|int|0|是否需要文字填写，1需要；0，不需要|
     * |shop_qualifications_status|int|0|状态，1，正常；0，禁用|
     * |type|int|0|资质类型，1，行业资质；0，品牌资质；2，用户资质|
     * |is_must|int|0|是否必须：1，必须；0，选填|
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
    public function index3(){
        try {
            $data2 = request()->data;
            $id = isset($data2['shop_type_id']) ? intval($data2['shop_type_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_INDEX_LIST . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'shop_type_id' => intval($data2['shop_type_id']) ,'shop_qualifications_status'=>State::STATE_NORMAL];
                $param['cache'] = true;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
                Fun::redis()->set($key, serialize($data));
                //$param['field'] = 'shop_is_content,shop_qualifications_name,shop_article_images';
            }
            if (is_string($data)) $data = unserialize($data);

            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺资质列表（用户资质）
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_type_id|int|true|12|-|店铺类型id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_type_id|int|12|店铺类型id|
     * |shop_qualifications_id|int|asdasd|id|
     * |shop_type_id|int|1|店铺类型id(已废弃)|
     * |shop_brand_type|int|1|类型，1，自有品牌；2，代理品牌|
     * |shop_qualifications_name|string|asda|资质名称|
     * |shop_qualifications_describe|string|asdas|资质描述|
     * |shop_qualifications_images|string|asdasda|资质样图|
     * |shop_is_content|int|0|是否需要文字填写，1需要；0，不需要|
     * |shop_qualifications_status|int|0|状态，1，正常；0，禁用|
     * |type|int|0|资质类型，1，行业资质；0，品牌资质；2，用户资质|
     * |is_must|int|0|是否必须：1，必须；0，选填|
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
    public function index4(){
        try {
            $data2 = request()->data;
            $id = isset($data2['shop_type_id']) ? intval($data2['shop_type_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_QUALIFCATIONS_USER_LIST . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'shop_type_id' => intval($data2['shop_type_id']) ,'shop_qualifications_status'=>State::STATE_NORMAL,'type'=>2];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $data = Fun::dataAll('\\app\\api\\model\\goods\\ShopQualifications', $param);
                Fun::redis()->set($key, serialize($data));
                //$param['field'] = 'shop_is_content,shop_qualifications_name,shop_article_images';
            }
            if (is_string($data)) $data = unserialize($data);

            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}