<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 18:11
 */

namespace app\work\controller;
use app\work\controller\Common;
use mercury\factory\Factory;

class Goodscategory2 extends Common
{
    public function goodsCategory(){
        $data = [];
        if(isset($this->param['category_id'])) $data['category_id'] = $this->param['category_id'];
        $res   = Factory::instance('/goods/v1/GoodsCategory/index')->run($data);
        if($res['code'] == 20000){
            $res['code'] = 1;
        }
        return $res;
    }
}