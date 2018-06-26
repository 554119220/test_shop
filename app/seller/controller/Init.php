<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 9:49
 */

namespace app\seller\controller;
use app\common\traits\F;
use mercury\auth\api\AuthApi;
use mercury\factory\Factory;
use think\Controller;
use think\Session;

class Init extends Controller
{
    public function _initialize()
    {
        //实时更新用户店铺信息
        $this->_check_login();
        $this->_updateShop();
        $this->assign('logouturl',F::domain('www','/user/logout'));
        $this->assign('wwwurl',F::domain('www','/'));
        $this->_config();
    }

    public function _updateShop(){
        $user   = Factory::instance('/user/v1/info/index')->run();
        Session::set('user.user_shop_id',$user['data']['user_shop_id']);
    }

    public function _check_login(){
        if(!session('user')){
            if(request()->isAjax()){
                return ['code' => 0,'msg' => '请先登录！'];
            }else {
                $this->redirect(F::domain('www','/user/login'));
            }
            exit();
        }
    }

    public function _config(){
        $list = db('config_category')->cache('site_config')->where(['status' => 1,'upid' => ['gt',0]])->field('group_name,config')->select();
        $cfg = [];
        foreach($list as $key => $val){
            if($val['config']){
                $val['config'] = unserialize(html_entity_decode($val['config']));
            }
            $cfg[$val['group_name']] = $val['config'];
        }
        config('cfg',$cfg);
        return $cfg;
    }

}