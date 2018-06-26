<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/16
 * Time: 15:09
 */

namespace app\work\controller;
use app\common\traits\Async;
use app\work\controller\Commonmodules;
use app\common\traits\F;
use mercury\factory\Factory;
class shopsettledaudit extends Common
{
    /**
     * 商家资料
     * Create by lazycat
     * 2017-08-07
     */
    public function detail(){
        $data['shop_settled_id'] = $this->param['id'];
        $data['openid'] = session('admin.openid');
        $data['is_work'] = 1;
        $shop   = Factory::instance('/goods/v1/shopSettled/detail')->run($data);
        if(isset($shop['data']['shop_settled_content']['step_shop_type'])){
            $shoptype = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type']]);
            if($shoptype['code'] == 20000){
                $shoptypename = $shoptype['data']['shop_type_suffix'];
            }
        }
        if(isset($shop['data']['shop_settled_content']['step_shop_info']['shop_province_id'])){
            $shop_province = Factory::instance('/tools/v1/district/detail')->run(['id'=>$shop['data']['shop_settled_content']['step_shop_info']['shop_province_id']]);
            $shop_province_name = $shop_province['a_name'];
        }

        if(isset($shop['data']['shop_settled_content']['step_shop_info']['shop_city_id'])){
            $shop_city = Factory::instance('/tools/v1/district/detail')->run(['id'=>$shop['data']['shop_settled_content']['step_shop_info']['shop_city_id']]);
            $shop_city_name = $shop_city['a_name'];
        }
        $this->assign('shop_city_name',$shop_city_name ?? '');
        $this->assign('shop_province_name',$shop_province_name ?? '');
        $this->assign('shoptypename',$shoptypename ?? '');
        //$this->assign('image_domain',F::getImages());
        $this->assign('res',$shop['data']);
        $logs   = Factory::instance('/goods/v1/Shopsettledaudit/index')->run(['shop_settled_id'=>$shop['data']['shop_settled_id']]);
        $this->assign('logs',$logs);
        return view();
    }

    /**
     * 审核记录
     * create by Lazycat
     * 2017-08-08
     */
    public function auditAdd(){
        $data                   = $this->post;
        if($data['shop_settled_state'] == 1 || $data['shop_settled_state'] ==2){
            if($data['shop_settled_state'] == 2){
                if(!isset($data['shop_settled_step'])){
                    return json(['code'=>0,'msg'=>'必须选择审核不通过的步骤']);
                }
                if(!$data['reason']){
                    return json(['code'=>0,'msg'=>'必须填写审核不通过的原因']);
                }
            }
        }else{
            return json(['code'=>0,'msg'=>'审核状态错误']);
        }
        if(!session('admin.id')){
            return json(['code'=>0,'msg'=>'请重新登录！']);
        }
        $data['admin_id'] = session('admin.id');
        $res    = Factory::instance('/goods/v1/shopSettled/audit')->run($data);
        if($res['code'] == 20000){
            //发送短信
            $user_id = db('shop_settled')->cache(true)->where(['shop_settled_id'=>intval($data['shop_settled_id']) ?? 0])->value('user_id');
            $user_mobile = db('user')->cache(false)->where(['user_id'=>$user_id])->value('user_mobile');
            $content = $data['sms_content'];
            if($data['shop_settled_state'] != 1){
                $content = $data['sms_content2'];
            }
            $msg = ',短信发送失败！';
            if(Async::gearmanSms($user_mobile,$content)){
                $msg = ',短信发送成功！';
            }
            return json(['code'=>1,'msg'=>$res['msg'].$msg]);
        }else{
            return json(['code'=>0,'msg'=>$res['msg']]);
        }
    }
}