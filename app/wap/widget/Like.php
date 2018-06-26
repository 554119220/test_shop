<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/23 0023
 * Time: 10:52
 */

namespace app\wap\widget;


use app\common\traits\F;
use mercury\factory\Factory;
use think\Controller;

/**
 * Class Like
 * @package app\wap\widget
 *
 * 猜你喜欢
 */
class Like extends Controller
{
    protected $data = [];
    protected function _initialize() {

    }
    /**
     * 猜你喜欢,根据用户喜好来获取数据
     *
     * @return string
     */
    public function index($cate = '')
    {
        $data   = [];
        if ($cate) $data['cate'] = $cate;
        $this->data = Factory::instance('/search/v1/goods/like')->run($data, true);
        return $this->fetch(F::setWidgetTemplate('like/index'), ['data' => $this->data, 'title' => '猜你喜欢']);
    }

    /**
     * 商家商品
     *
     * @param $shop_id
     * @return mixed
     */
    public function shop($shop_id)
    {
        $this->data = Factory::instance('/search/v1/goods/index')->run(['shop_id' => $shop_id], true);
        return $this->fetch(F::setWidgetTemplate('like/index'), ['data' => $this->data, 'title' => '推荐']);
    }
    
    /**
     * 推荐-商品详情
     *
     * @return mixed
     */
    public function recommend()
    {
        $this->data = Factory::instance('/search/v1/goods/like')->run();
        return $this->fetch(F::setWidgetTemplate('like/recommend'), ['data' => $this->data]);
    }
}