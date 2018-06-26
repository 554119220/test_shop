<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/9 0009
 * Time: 17:23
 */

namespace app\api\controller\orders\v1;


use app\common\traits\F;
use mercury\constants\Code;
use mercury\ResponseException;

/**
 * Class OrdersShop
 * @package app\api\controller\orders\v1
 * @title 订单查询
 */
class OrdersShop extends Init
{
    protected $model;
    public function __construct()
    {
        $this->model    = new \app\api\model\orders\OrdersShop();
        parent::__construct();
    }

    /**
     * @title 订单查询
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_no|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `{
    "data": {
    "orders_shop_id": 5490,
    "orders_shop_create_time": "2018-04-09 15:32:21",
    "orders_shop_update_time": "2018-04-09 15:32:21",
    "orders_shop_no": "CO20180409153221205228",
    "orders_no": "GO20180409153221192849",
    "shop_id": 81,
    "seller_user_id": 134,
    "buyer_user_id": 113,
    "orders_shop_pay_time": "2018-04-09 15:34:45",
    "orders_shop_is_pay": 1,
    "orders_shop_pay_type": 5,
    "orders_shop_pay_amount": "1.50",
    "orders_shop_pay_cash": "1.05",
    "orders_shop_pay_shopping_score": "45.00",
    "orders_shop_trade_no": "PA201804091540540080182514",
    "orders_shop_score": 105,
    "orders_shop_discount_amount": "0.00",
    "orders_shop_amount": "1.50",
    "orders_shop_edit_amount": "1.50",
    "orders_shop_goods_amount": "1.50",
    "orders_shop_goods_edit_amount": "1.50",
    "orders_shop_express_amount": "0.00",
    "orders_shop_express_edit_amount": "0.00",
    "orders_shop_express_time": "",
    "orders_shop_express_company": 0,
    "orders_shop_express_no": "",
    "orders_shop_express_type": 1,
    "orders_shop_express_remark": "",
    "orders_shop_receive_time": "",
    "orders_shop_comment_time": "",
    "orders_id": 4818,
    "orders_shop_state": 2,
    "orders_shop_is_freeze": 0,
    "orders_shop_close_time": "",
    "orders_shop_close_user": 0,
    "orders_refund_amount": "1.50",
    "orders_refund_num": 1,
    "orders_refund_score": "150.0000",
    "orders_refund_express_amount": "0.00",
    "goods_style_num": 1,
    "goods_count_num": 1,
    "express_id": 1,
    "orders_shop_remark": "",
    "goods_weight": "1.00",
    "orders_shop_next_time": "2018-04-10 01:34:45",
    "orders_shop_can_use_shopping_score": "45.0000",
    "orders_shop_state_name": "已付款",
    "orders_shop_express_company_name": null,
    "orders_shop_pay_type_name": "百望支付",
    "orders_shop_next_run_time": "剩余 0天7时41分5秒 自动退款"
    },
    "msg": "请求成功",
    "info": "success",
    "code": 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array
     */
    public function query()
    {
        try {
            if (!$this->data['shop_no']) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作!');
            $order_nos   = explode(',', $this->data['shop_no']);
            if (!$order_nos) throw new ResponseException(Code::CODE_OTHER_FAIL, '非法操作');
            $data   = [];
            $key    = 'orders_shop_no';
            foreach ($order_nos as $v) {
//                if (strpos($v, 'CO') !== 0 ||
//                    strlen($v) !== 22 ||
//                    strpos($v, 'GO') !== 0) continue;
                #   父订单
                if (strpos($v, 'GO') === 0) {
                    $key = 'orders_no';
                    $tmp = F::dataAll($this->model, [
                        'where' => [$key => $v]
                    ]);
                    if (!empty($tmp)) {
                        foreach ($tmp as $val) {
                            $data[] = $val;
                        }
                    }
                } else {
                    $data[] = F::dataDetail($this->model, [
                        'where' => [$key => $v]
                    ]);
                }
            }
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}