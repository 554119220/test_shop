<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/01/10
 * Time: 16:31
 */

namespace app\work\controller;
use app\work\controller\Commonmodules;
use mercury\factory\Factory;

class Shopaudit extends Commonmodules
{
    /**
     * 雇员操作记录
     * @return \think\response\View
     */
    public function shopLog(){
        $data = [
            'openid'            => session('admin.openid'),
            'shop_id'            => $this->param['shop_id'],
        ];
        $shopAudit   = Factory::instance('/goods/v1/shopAudit/index')->run($data);
        $this->assign('logs',$shopAudit);
        return view();
    }

    public function implement(){
        return view();
    }
}