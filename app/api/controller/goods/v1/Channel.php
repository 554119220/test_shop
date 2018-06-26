<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1 0001
 * Time: 17:29
 */

namespace app\api\controller\goods\v1;
use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;

/**
 * Class Channel
 * @package app\api\controller\goods\v1
 * @title 频道
 */
class Channel
{
    protected $model;
    public function __construct()
    {
        $this->model    = new \app\api\model\goods\Channel();
    }


    /**
     * @title 频道列表
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
    public function index()
    {
        try {
            $key    = F::getCacheName(Cache::CHANNEL_ALL);
            $channels   = F::redis()->get($key);
            if (!$channels) {
                $channels   = db('channel')
                    ->where(['channel_state' => State::STATE_NORMAL, 'channel_bind_category' => ['gt', State::STATE_DISABLED]])
                    ->column('channel_name,channel_title');
                if (!$channels) throw new ResponseException(Code::CODE_NO_CONTENT);
                F::redis()->set($key, serialize($channels));
            }
            if (is_string($channels)) $channels = unserialize($channels);
            return $channels;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 频道详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |type|string|false|-|-|频道名称|
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
    public function detail()
    {
        try {
            $type   = request()->data['type'];
            $key    = F::getCacheName(Cache::CHANNEL_ROW . $type);
            $data   = F::redis()->get($key);
            if (!$data) {
                $where['channel_state'] = State::STATE_NORMAL;
                $relation   = 'channel_slider';
                if ($type) {
                    $where['channel_name']  = $type;
                    $relation   = "{$relation},GoodsCategory";
                }
                $data   = F::dataDetail($this->model, [
                    'where'     => $where,
                    'relation'  => $relation,
                    'field'     => 'channel_id,channel_title,channel_name,channel_bind_category,
                    channel_icon,channel_images,channel_condition,channel_result_sort',
                ]);
                if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
                F::redis()->set($key,serialize($data));
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 统计点击次数
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
    public function countClick()
    {
        try {
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}