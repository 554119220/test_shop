<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/14 0014
 * Time: 14:46
 */

namespace app\api\controller\orders\v1;


use app\common\traits\F;
use mercury\constants\State;

class Refund
{
    protected $request  = [], $is_refunds = false, $num, $amount, $express_amount, $data;
    public function __construct()
    {
        $this->request  = request()->data;
        $this->num              = intval($this->request['num']);
        $this->amount           = F::numberFormats($this->request['amount']);
        $this->express_amount   = F::numberFormats($this->request['express_amount']);
        #   如果用户时退货并退款或者商家未发货的情况下均要退货并退款
        if ($this->request['type'] == State::STATE_REFUNDS || $this->request['is_ship'] == State::STATE_DISABLED) {
            $this->is_refunds   = true;
        } else {
            #   仅退款将数量设置为0
            $this->num  = State::STATE_DISABLED;
        }
    }

    /**
     * 检测数据
     *
     * @return bool
     */
    protected function checkData()
    {
        #   退货并退款时数量不能为空
        if ($this->is_refunds) {
            if ($this->num <= State::STATE_DISABLED) return false;
        } else {
            #   仅退款时运费及退款金额必须有一个不为空
            if ($this->amount <= State::STATE_DISABLED && $this->express_amount <= State::STATE_DISABLED) return false;
        }
        return true;
    }

    protected function getData()
    {
        #   取出退款数据
    }

    protected function getCanRefundGoods()
    {
        #   取出可退商品
    }
}