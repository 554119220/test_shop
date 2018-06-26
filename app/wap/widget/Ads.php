<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/23 0023
 * Time: 10:52
 */

namespace app\wap\widget;


use app\common\traits\F as Fun;
use mercury\factory\Factory;
use think\Controller;

/**
 * Class Ads
 * @package app\wap\widget
 *
 * 广告
 */
class Ads extends Controller
{
    
    protected function _initialize() {

    }

    /**
     * 获取单个广告位的广告
     * {:widget('Ads/index',[ 'position_id' => 5, 'template' => 'ads/test' ])}
     * @param  integer $position_id
     * @param  string $template
     * @return string
     */
    public function index($position_id, $template)
    {
        $param = [
            'position_id' => intval($position_id),
        ];
        $data = Factory::instance('/ads/v1/ads/index')->run($param)['data'] ?? [];
        return $this->fetch(Fun::setWidgetTemplate($template), ['data' => array_shift($data)]);
    }

    
}