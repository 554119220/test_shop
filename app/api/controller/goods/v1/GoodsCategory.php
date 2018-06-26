<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\Cache;
use app\api\model\goods\GoodsCategory as Model;

/**
 * @title 商品分类
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class GoodsCategory
{
    private $list = [];

    # 获取列表
    public function __construct()
    {
        $this->toRedis();
    }

    /**
     * 存储分类表到redis 请求接口用这个
     */
    private function toRedis()
    {
        $list = Fun::redis()->get(Fun::getCacheName( Cache::GOODS_CATEGORY_LIST ));
        if ( $list ) {
            $list = json_decode($list, true);
        } else {
            $list = self::toRedis2();
        }
        $this->list = $list;
    }

    /**
     * 存储分类表到redis 后台更新要调用这个
     * @return [type] [description]
     */
    static function toRedis2()
    {
        # condition
        $param['where'] = [ 'category_state' => State::STATE_NORMAL ];
        $param['order'] = 'category_sort asc,category_id asc';
        $param['field'] = '*';
        # get data
        $list = Fun::dataAll(Fun::mApi('goods', 'GoodsCategory'), $param);
        # cache
        if ( $list ) {
            Fun::redis()->set( Fun::getCacheName( Cache::GOODS_CATEGORY_LIST ), Fun::json(array_column($list, null, 'category_id')) );
        }
        return $list;
    }

    /**
     * @title 分类列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |category_id|int|false|0|---|父级id|
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
            // dump($this->list);
            # 获取缓存
            if ( empty($this->list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 无极分类
            $option = [
                'begin' => intval(request()->data['category_id'] ?? 0),
                'id'    => 'category_id',
                'pid'   => 'category_sid',
                'child' => 'child',
            ];
            # deal data
            $list = $this->list;
            foreach ($list as $key => $value) {
                $list[$key]['category_icon'] = Fun::getImages($value['category_images']);
                $list[$key]['category_icon2'] = Fun::getImages($value['category_icon']);
                $list[$key]['category_images'] = Fun::getImages($value['category_images']);
                # 是否有图片
                if ( empty($value['category_images']) ) {
                    $list[$key]['have_images'] = 0;
                } else {
                    $list[$key]['have_images'] = 1;
                }
            }
            $list = Fun::array_tree($list, $option);
            // dump($list);exit;
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 分类详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |category_id|int|true|0|---|分类id|
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
            # ...
            $detail = $this->list[intval(request()->data['category_id'] ?? 0)] ?? [];
            # dump($detail);exit;
            if ( empty($detail) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            // $detail['category_icon'] = Fun::getImages($detail['category_images']);
            // $detail['category_icon2'] = Fun::getImages($detail['category_icon']);
            // $detail['category_images'] = Fun::getImages($detail['category_images']);
            # 加入上级全名
            $pid = $detail['category_sid'];
            $upName[] = $detail['category_name'];
            while ( isset($this->list[$pid]) ) {
                array_unshift($upName, $this->list[$pid]['category_name']);
                $pid = $this->list[$pid]['category_sid'];
            }
            $detail['upName'] = $upName;
            # ...
            return $detail;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }
}