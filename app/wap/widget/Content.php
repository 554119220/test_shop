<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/23 0023
 * Time: 10:54
 */

namespace app\wap\widget;


use app\common\traits\F;
use think\Controller;

class Content extends Controller
{

    /**
     * 无数据，无数据的时候需要显示的内容
     *
     * @param string $text
     * @return string
     */
    public function no($text = '')
    {
        return $this->fetch(F::setWidgetTemplate('content/no'), ['text' => $text]);
    }

    /**
     * 无订单
     *
     * @param string $text
     * @return mixed
     */
    public function noOrders($text = '')
    {
        return $this->fetch(F::setWidgetTemplate('content/noOrders'), ['text' => $text]);
    }
}