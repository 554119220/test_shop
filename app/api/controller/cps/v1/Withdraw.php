<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23 0023
 * Time: 13:57
 */

namespace app\api\controller\cps\v1;


use app\api\model\cps\CpsWithdraw;
use mercury\constants\Code;
use mercury\ResponseException;

/**
 * Class Withdraw
 * @package app\api\controller\cps\v1
 * @title CPS用户提现
 */
class Withdraw extends Init
{
    public function __construct()
    {
        $this->model    = new CpsWithdraw();
        parent::__construct();
    }

    /**
     * @title 佣金提现
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |name|string|true|hello world|-|description|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |name|string|values|description|
     * @response_example 响应示例
     * `your json code`
     * @description 接口描述
     * > your api description
     * @return array|int
     */
    public function index()
    {
        try {
            $data   = [
                'user_id'   => $this->user['user_id'],
                'erp_openid'=> $this->user['openid'],
                'openid'    => $this->data['openid'],
                'app_id'    => $this->data['app_id'],
                'cps_withdraw_amount'   => $this->data['withdraw_amount']
            ];
            $flag   = $this->model->data($data)->save();
            if (!$flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败');

            #   需要入列转换至ERP

            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}