<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;

/**
 * @title 商品品牌
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class GoodsBrand
{
    /**
     * @title 品牌列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
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
            $list = Fun::dataAll( '\\app\\api\\model\\goods\\GoodsBrand');
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            return $list;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 品牌详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |goods_brand_id         |int        |true   |0  |-  |品牌id|
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
            $detail = Fun::dataDetail('\\app\\api\\model\\goods\\GoodsBrand', (int)request()->data['goods_brand_id']);
            if ( empty($detail) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            return $detail;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}