<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19 0019
 * Time: 11:57
 */

namespace app\api\controller\pay\v1;

use mercury\constants\Code;
use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\State;
use mercury\ResponseException;

/**
 * Class PayType
 * @package app\api\controller\pay\v1
 *
 * @title 付款方式
 */
class PayType extends Init
{
    protected $model;
    public function __construct()
    {
        parent::__construct();
        $this->model    =  new \app\api\model\pay\PayType();
    }

    /**
     * @title 付款方式
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |-|-|-|-|-|-|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |pay_type_id|int|5|-|
     * |pay_type_name|string|百望支付|-|
     * |pay_type_icon|string|http://oz3fjflhn.bkt.clouddn.com/FuwYF1Ni8z-GkDSiEJ9-xKv7tWe|-|
     * |pay_type_desc|string|可使用余额，云积分，购物积分等|-|
     * @response_example 响应示例
     * `{
        data: [
                {
                    pay_type_id: 5,
                    pay_type_name: "百望支付",
                    pay_type_icon: "http://oz3fjflhn.bkt.clouddn.com/FuwYF1Ni8z-GkDSiEJ9-xKv7tWe8",
                    pay_type_desc: "可使用余额，云积分，购物积分等"
                },
                {
                    pay_type_id: 9,
                    pay_type_name: "支付宝",
                    pay_type_icon: "http://oz3fjflhn.bkt.clouddn.com/FobLFc36P368MLu_ShqhbE6l6tES",
                    pay_type_desc: "单日限额5W"
                },
                {
                    pay_type_id: 12,
                    pay_type_name: "微信支付",
                    pay_type_icon: "http://oz3fjflhn.bkt.clouddn.com/FrhAUYsY2NzdrslsW16rLPmmdVnE",
                    pay_type_desc: "单日限额1W"
                }
            ],
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
            $key    = F::getCacheName(Cache::PAY_TYPE_ALL);
            $data   = F::redis()->get($key);
            if (!$data) {
                $map    = [
                    'pay_type_state'    => State::STATE_NORMAL
                ];
                $data   = F::dataAll($this->model, [
                    'where' => $map,
                    'order' => 'pay_type_sort asc, pay_type_id asc',
                    'field' => 'pay_type_id,pay_type_name,pay_type_icon,pay_type_desc'
                ]);
                if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
                F::redis()->set($key, serialize($data));
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 支付方式详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |id|int|true|5|-|-|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |pay_type_id|int|5|-|
     * |pay_type_name|string|百望支付|-|
     * |pay_type_icon|string|http://oz3fjflhn.bkt.clouddn.com/FuwYF1Ni8z-GkDSiEJ9-xKv7tWe8|-|
     * |pay_type_state_wap|bool|1|0禁用，1正常|
     * |pay_type_state_pc|bool|1|0禁用，1正常|
     * |pay_type_state_app|bool|1|0禁用，1正常|
     * |pay_type_desc|string|可使用余额，云积分，购物积分等|-|
     * @response_example 响应示例
     * `{
            data: {
                    pay_type_id: 5,
                    pay_type_state: 1,
                    pay_type_create_time: "1970-01-01 08:00:00",
                    pay_type_update_time: "1970-01-01 08:00:00",
                    pay_type_name: "百望支付",
                    pay_type_icon: "http://oz3fjflhn.bkt.clouddn.com/FuwYF1Ni8z-GkDSiEJ9-xKv7tWe8",
                    pay_type_sort: 0,
                    pay_type_config: "",
                    pay_type_state_wap: 1,
                    pay_type_state_pc: 1,
                    pay_type_state_app: 1,
                    pay_type_desc: "可使用余额，云积分，购物积分等"
                },
                msg: "请求成功",
                info: "success",
                code: 20000
            }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function detail()
    {
        try {
            $id = $this->data['id'];
            $key= F::getCacheName(Cache::PAY_TYPE_DETAIL . $id);
            $data   = F::redis()->get($key);
            if (!$data) {
                $map    = [
                    'pay_type_state'    => State::STATE_NORMAL,
                    'pay_type_id'       => $id
                ];
                $data  = F::dataDetail($this->model, [
                    'where' => $map,
                    'field' => 'pay_type_id,pay_type_name,pay_type_icon,pay_type_state_wap,pay_type_state_pc,pay_type_state_app,pay_type_desc'
                ]);
                if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
                F::redis()->set($key, serialize($data));
            }
            if (is_string($data)) $data = unserialize($data);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}