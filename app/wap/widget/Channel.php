<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/3 0003
 * Time: 11:42
 */

namespace app\wap\widget;


use app\common\traits\F;
use mercury\factory\Factory;
use think\Controller;
use think\View;

class Channel extends Controller
{
    protected $view;

//    public function __construct()
//    {
//        $this->view = new View();
//    }
    public function menu()
    {
        $channels   = Factory::instance('/goods/v1/Channel/index')->run();
        return $this->fetch(F::setWidgetTemplate('channel/menu'), ['data' => $channels]);
    }
}