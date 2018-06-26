<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 11:48
 */

namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Cache;

class DisabledKeyword
{
    /**
     * 后台更新redis-详情
     * @return [type] [description]
     */
    static function toRedis2($id = 0)
    {
        $id = !empty($id) ? intval($id) : 0;
        $key    = Fun::getCacheName(Cache::DISABLED_KEYWORD_DETAIL . $id);
        $param['where'] = ['id' => $id,'status'=>1];
        $param['cache'] = false;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $param['field'] = 'keyword';
        $data = Fun::dataDetail('\\app\\api\\model\\goods\\DisabledKeyword', $param);
        Fun::redis()->set($key, serialize($data));
    }
    /**
     * 获取店铺名称禁用关键词
     *
     * @return array
     */
    public function detail(){
        try {
            $data2 = request()->data;
            $id = isset($data2['id']) ? intval($data2['id']) : 0;
            $key    = Fun::getCacheName(Cache::DISABLED_KEYWORD_DETAIL . $id);
            $data   = Fun::redis()->get($key);
            if(!$data){
                $param['where'] = [ 'id' => $id,'status' => 1];
                $param['cache'] = false;
                $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
                $param['field'] = 'keyword';

                $data = Fun::dataDetail('\\app\\api\\model\\goods\\DisabledKeyword', $param);
                Fun::redis()->set($key, serialize($data));
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}