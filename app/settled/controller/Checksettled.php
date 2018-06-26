<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/09
 * Time: 13:48
 */

namespace app\settled\controller;
use app\common\traits\F;
use mercury\factory\Factory;
use think\Session;

class Checksettled
{
    public function index(){
        return view('',[
            'erp_user_url'=>F::erpDomain('user'),
        ]);
    }

    /**
     * @return bool
     */
    public function msg()
    {
        return view();
    }

    /**
     * 入驻要求
     * @return \think\response\View
     */
    public function applysettled(){
        $business_notice = Factory::instance('/goods/v1/shopArticle/detail')->run(['shop_article_id'=>17]); //商家须知
        $shoptype = Factory::instance('/goods/v1/shopType/index')->run();
        foreach ($shoptype['data'] as $key=>$value){
            $shop_qualifications = unserialize($value['shop_qualifications']);
            $shoptype['data'][$key]['brand1'] = Factory::instance('/goods/v1/ShopQualifications/index5')->run(['shop_qualifications_id'=>implode(',',$shop_qualifications['shop_type_brand1'] ?? []) ?? 0,'shop_type_id'=>$value['shop_type_id'],'type'=>'shop_type_brand1']);
            $shoptype['data'][$key]['brand2'] = Factory::instance('/goods/v1/ShopQualifications/index5')->run(['shop_qualifications_id'=>implode(',',$shop_qualifications['shop_type_brand2'] ?? []) ?? 0,'shop_type_id'=>$value['shop_type_id'],'type'=>'shop_type_brand2']);
            $shoptype['data'][$key]['user'] = Factory::instance('/goods/v1/ShopQualifications/index5')->run(['shop_qualifications_id'=>implode(',',$shop_qualifications['shop_type_member'] ?? []) ?? 0,'shop_type_id'=>$value['shop_type_id'],'type'=>'shop_type_member']);
            //$shoptype['data'][$key]['industry'] = Factory::instance('/goods/v1/ShopQualifications/index5')->run(['shop_qualifications_id'=>implode(',',$shop_qualifications['shop_type_industry'] ?? []) ?? 0,'shop_type_id'=>$value['shop_type_id'],'type'=>'shop_type_industry']);
        }
        return view('',[
            'erp_user_url'=>F::erpDomain('user'),
            'business_notice'=>$business_notice,
            'shoptype'=>$shoptype,
            'user'=>Session::get('user') ?? [],
        ]);
    }

    /**
     * 入驻指南
     * @return \think\response\View
     */
    public function settledguide(){
        $business_notice = Factory::instance('/goods/v1/shopArticle/detail')->run(['shop_article_id'=>13]); //百望商家入驻指南
        $business_notice2 = Factory::instance('/goods/v1/shopArticle/detail')->run(['shop_article_id'=>16]); //店铺资质以及指南
        return view('',[
            'business_notice'=>$business_notice,
            'business_notice2' => $business_notice2,
        ]);
    }

    /**
     *
     * 流程流程
     * @return \think\response\View
     */
    public function Process(){
        if(!Session::get('user')){
            F::gotoUrl(F::domain('www','/user/login'));
        }
        $shop = Factory::instance('/goods/v1/shopSettled/detail')->run();
        if($shop['code'] != 20000) exit('非法操作！');
        if($shop['data']['shop_settled_state'] == 1){
            F::gotoUrl(F::domain('seller','/'));
        }
        $shop_settled_step = explode(',',$shop['data']['shop_settled_step']);
        if($shop['data']['shop_settled_state'] == 3){
            $tpl = 'process2';
        }else{
            $tpl = 'process';
        }
        return view($tpl,[
                'shop_settled_step'=>$shop_settled_step,
                'shop' => $shop['data'] ?? [],
            ]);
    }
}