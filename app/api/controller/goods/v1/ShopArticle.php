<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Cache;

/**
 * Class ShopArticle
 * @package app\api\controller\goods\v1
 *
 * @title 店铺文章
 */

class ShopArticle
{
    /**
     * 后台更新redis-详情
     * @return [type] [description]
     */
    static function toRedis2($shop_article_id = 0)
    {
        $id = !empty($shop_article_id) ? intval($shop_article_id) : 0;
        $key    = Fun::getCacheName(Cache::SHOP_ARTICLE_DETAIL . $id);
        $param['where'] = ['shop_article_id' => $id];
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $param['field'] = 'shop_article_title,shop_article_content,shop_article_images';
        $data = Fun::dataDetail('\\app\\api\\model\\goods\\ShopArticle', $param);
        Fun::redis()->set($key, serialize($data));
    }

    /**
     * @title 店铺文章详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_article_id|int|true|12|-|文章id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_article_title|string|12|标题|
     * |shop_article_content|string|asdasd|内容|
     * |shop_article_images|string|个体店|图片|
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
            $id = isset(request()->data['shop_article_id']) ? intval(request()->data['shop_article_id']) : 0;
            $key    = Fun::getCacheName(Cache::SHOP_ARTICLE_DETAIL . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'shop_article_id' => $id,'shop_is_status' => 1];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $param['field'] = 'shop_article_title,shop_article_content,shop_article_images';

                $data = Fun::dataDetail('\\app\\api\\model\\goods\\ShopArticle', $param);
                Fun::redis()->set($key, serialize($data));
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}