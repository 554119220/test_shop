<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/01/15
 * Time: 13:49
 */

namespace app\work\controller;
use app\work\controller\Commonmodules;
use app\common\traits\F;
use mercury\factory\Factory;

class Shoptypequalificationsauit extends Common
{
    public function shopqualifications(){
        $qualifications = db('shop_qualifications')->where(['shop_qualifications_status'=>1])->select();
        $qualifications2 = db('shop_type')->where(['shop_type_id'=>input('id')])->value('shop_qualifications');
        $qualifications2 = unserialize($qualifications2);
        return view('',['qualifications'=>$qualifications,'qualifications2'=>$qualifications2]);
    }
    public function update(){
        $data                   = $this->post;
//        if(!isset($data['shop_type_brand2']) || !isset($data['shop_type_brand1']) || !isset($data['shop_type_member']) || !isset($data['shop_type_industry'])){
//            return ['code'=>0,'msg'=>'每个资质最少勾选一个！'];
//        }
        $where['shop_type_id'] = $data['id'];
        unset($data['id']);
        $shop_qualifications = serialize($data);
        $r = db('shop_type')->where($where)->update(['shop_qualifications'=>$shop_qualifications,'shop_type_update_time'=>time()]);
        if($r){
            //更新缓存
            \app\api\controller\goods\v1\ShopType::toRedis1();
            \app\api\controller\goods\v1\ShopType::toRedis2($this->post['id']);
            \app\api\controller\goods\v1\ShopQualifications::toRedis3(['type'=>'shop_type_brand2','shop_type_id'=>$this->post['id'],'shop_qualifications_id'=>implode(',',$data['shop_type_brand2'] ?? [])]);
            \app\api\controller\goods\v1\ShopQualifications::toRedis3(['type'=>'shop_type_brand1','shop_type_id'=>$this->post['id'],'shop_qualifications_id'=>implode(',',$data['shop_type_brand1'] ??[])]);
            \app\api\controller\goods\v1\ShopQualifications::toRedis3(['type'=>'shop_type_member','shop_type_id'=>$this->post['id'],'shop_qualifications_id'=>implode(',',$data['shop_type_member'] ?? [])]);
            \app\api\controller\goods\v1\ShopQualifications::toRedis3(['type'=>'shop_type_industry','shop_type_id'=>$this->post['id'],'shop_qualifications_id'=>implode(',',$data['shop_type_industry'] ?? [])]);
            return ['code'=>1,'msg'=>'更新成功！'];
        }
        return ['code'=>0,'msg'=>'更新失败！'];
    }
}