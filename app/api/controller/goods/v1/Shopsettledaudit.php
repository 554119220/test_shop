<?php
namespace app\api\controller\goods\v1;
use app\common\traits\F as Fun;
use mercury\ResponseException;

/**
 * Class Shopsettledaudit
 * @package app\api\controller\goods\v1
 *
 * @title 店铺开店审核日志
 */

class Shopsettledaudit
{
    /**
     * @title 开店审核记录
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_settled_id|int|true|12|-|开店id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |shop_settled_audit_content|string|12|说明|
     * |shop_settled_audit_is_pass|int|1|是否通过开店审核 0不通过,1通过|
     * |shop_settled_audit_admin_id|int|1234536|审核雇员id|
     * |shop_settled_id|int|10|审核id|
     * |shop_settled_audit_create_ip|string|1234536|审核ip|
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
            $data = request()->data;
            $param['where'] = [ 'shop_settled_id' => intval($data['shop_settled_id'])];
            $param['relation'] = 'employee';
            $param['cache'] = false;
            $param['order'] = 'shop_settled_audit_id desc';
            $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
            $param['field'] = 'shop_settled_audit_content,shop_settled_audit_admin_id,shop_settled_id,shop_settled_audit_create_time,shop_settled_audit_is_pass';
            return Fun::dataAll('\\app\\api\\model\\goods\\ShopSettledAudit', $param);
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}