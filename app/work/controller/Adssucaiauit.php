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
class Adssucaiauit extends Common
{
    /**
     * 商家资料
     * Create by lazycat
     * 2017-08-07
     */
    public function detail(){
        $data['ads_sucai_id'] = $this->param['id'];
        $data['openid'] = session('admin.openid');
        $data['is_work'] = 1;
        $ads   = Factory::instance('/ads/v1/AdsSucai/details')->run($data);
        $this->assign('res',$ads['data'] ?? []);
        if(isset($ads['data']['ads_sucai_audit']) && !empty($ads['data']['ads_sucai_audit'])){
            foreach ($ads['data']['ads_sucai_audit'] as $key=>$value){
                $work = db('employee')->cache(true,36000000)->where(['id'=>$value['ads_sucai_audit_work_id']])->field('name,account')->find();
                $ads['data']['ads_sucai_audit'][$key]['work_name'] = $work['name'];
                $ads['data']['ads_sucai_audit'][$key]['work_account'] = $work['account'];
            }
        }
        $this->assign('logs',$ads['data']['ads_sucai_audit'] ?? []);
        return view();
    }

    /**
     * 审核记录
     * create by Lazycat
     * 2017-08-08
     */
    public function auditAdd(){
        $data                   = $this->post;
        if($data['ads_sucai_state'] == 1 || $data['ads_sucai_state'] ==2){
            if($data['ads_sucai_state'] == 2){
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
        $data['ads_sucai_audit_work_id'] = session('admin.id');
        $res    = Factory::instance('/ads/v1/AdsSucai/audit')->run($data);
        if($res['code'] == 20000){
            return json(['code'=>1,'msg'=>$res['msg']]);
        }else{
            return json(['code'=>0,'msg'=>$res['msg']]);
        }
    }
}