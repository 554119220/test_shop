<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/08
 * Time: 14:13
 */

namespace app\settled\controller;
use think\Session;
use app\common\traits\F;
use mercury\factory\Factory;
class Init extends \think\Controller
{
    protected $post;
    protected $get;
    protected $param;
    protected $is_mobile; //判断是否为手机端

    public function _initialize()
    {
        //Session::Init();
        //$this->_check_login();
        //$this->_updateShop();
        //if(!in_array(request()->controller(),array('Checksettled')))$this->_check_settled();
        //$this->_check_is_settled();
        $this->post     = input('post.');
        $this->get      = input('get.');
        $this->param    = $this->request->param();
        //$this->_config();
    }

    public function _updateShop(){
        $user   = Factory::instance('/user/v1/info/index')->run();
        Session::set('user.user_shop_id',$user['data']['user_shop_id']);
    }

    public function _check_login(){
        if(session('user')){

        }else{
            if(request()->isAjax()){
                return ['code' => 0,'msg' => '请先登录！'];
            }else {
                $this->redirect(F::domain('www','/user/login'));
            }
            exit();
        }
    }


    /**
     * 检测用户是否有资格开店
     */
    public function _check_settled(){
        $user = session('user');
        if($user['erpUser']['level_id'] < 2 || $user['erpUser']['is_valid_credentials'] != 1){
            $this->redirect('/checksettled');
        }
    }

    /**
     * 检测用户是否已经开店或者进入审核阶段
     */
    public function _check_is_settled(){
        $shop = Factory::instance('/goods/v1/shopSettled/detail')->run();
        if($shop['code'] == 20000){
                //店铺已经进入审核中或者已经通过审核
            if($shop['data']['shop_settled_state'] == 1){
                //通过审核
                $this->redirect(F::domain('seller','/'));
            }elseif($shop['data']['shop_settled_state'] == 0){
                //待审核
                $this->redirect('/checksettled/process');
            }elseif($shop['data']['shop_settled_state'] == 3 || $shop['data']['shop_settled_state'] == 2){
                //编辑中，判断店铺类型是否禁止使用
                if(isset($shop['data']['shop_settled_content']['step_shop_type']) && request()->action() != 'choiceshoptype'){
                    if (!\app\api\model\goods\ShopType::where(['shop_type_state'=>1,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type']])->value('shop_type_state')) {
                        $this->redirect('/choice/choiceshoptype');
                    }
                }
            }
        }elseif($shop['code'] == 60030){
            $this->redirect(F::domain('seller','/'));
        }
    }

    /**
     * 获取配置
     */
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