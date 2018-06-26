<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 10:05
 */

namespace app\seller\controller;
use mercury\factory\Factory;
use think\Session;
use app\common\traits\F;
use mercury\auth\api\AuthApi;
use think\Controller;

/**
 * Class Setting
 * @package app\seller\controller
 *
 * 店铺设置
 */
class Shopupdate extends Controller
{
    protected $shop = [];
    protected $beforeActionList = [
        'getshop' =>  ['only'=>'qualifications,industryqualification'],
    ];

    public function getshop(){
        $shop_detail = session('shop');
        if($shop_detail['is_again'] != 1) F::gotoUrl(F::domain('seller','/prompt2'));
        $shop = Factory::instance('/goods/v1/shopSettled2/detail')->run();
        if(!$shop){
            F::gotoUrl(F::domain('seller','/prompt2'));
        }
        $this->assign('shop',$shop['data']);
        $this->shop = $shop;
    }
    /**
     * 上传店铺资质
     *
     * @return \think\response\View
     */
    public function Qualifications(){
        $shop = $this->shop;
        if(empty($shop['data']['shop_settled_content']['brand'])){
            //没有选择品牌，直接进去到最后一步，填写店铺资料
            F::gotoUrl('/shopupdate/industryqualification');
        }
        $shoptype = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type']]);
        $qualifications = unserialize($shoptype['data']['shop_qualifications']);
        if(isset($qualifications['shop_type_brand1']) && isset($qualifications['shop_type_brand2'])){
            $shop_type_brand1 = implode(',',$qualifications['shop_type_brand1']);
            $shop_type_brand2 = implode(',',$qualifications['shop_type_brand2']);
            $shop_type_brand1_list = Factory::instance('/goods/v1/shopQualifications/index5')->run(['shop_qualifications_id'=>$shop_type_brand1,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type'],'type'=>'shop_type_brand1']);
            $shop_type_brand2_list = Factory::instance('/goods/v1/shopQualifications/index5')->run(['shop_qualifications_id'=>$shop_type_brand2,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type'],'type'=>'shop_type_brand2']);
        }
        if(isset($shop['data']['shop_settled_content']['brand'])){
            $brand = $shop['data']['shop_settled_content']['brand'];
            foreach ($brand as $key=>$value) {
                $brand[$key]['images1'] = again_values2($value['images1'] ?? [], $shop_type_brand1_list['data'] ?? []);
                $brand[$key]['images2'] = again_values2($value['images2'] ?? [], $shop_type_brand2_list['data'] ?? []);
            }
            $shop['data']['shop_settled_content']['brand'] = $brand;
        }
        $apiarr = array(
            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled2/save')->createHeaders(),
        );
        return view('',[
            'headers'=>$apiarr,
            'shop'=>$shop['data'],
        ]);
    }

    /**
     * 上传行业资质
     *
     * @return \think\response\View
     */
    public function IndustryQualification(){
        $shop = $this->shop;
        if(!isset($shop['data']['shop_settled_content']['category_two'])){
            F::gotoUrl('/shopupdate/choicegoods');
        }
        $category_two = $shop['data']['shop_settled_content']['category_two'];
        $category = array();
        for ($i = 0; $i < count($category_two);$i++){
            $categorydata = Factory::instance('/goods/v1/goodsCategory/detail')->run(['category_id'=>$category_two[$i]]);
            $category[$i] =$categorydata['data'];
        }
        if(isset($shop['data']['shop_settled_content']['category_images'])){
            $category = again_values($shop['data']['shop_settled_content']['category_images'],$category,'category_images','category_id');
        }
        $shoptype = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type']]);
        $qualifications = unserialize($shoptype['data']['shop_qualifications']);
        if(isset($qualifications['shop_type_industry'])){
            $shop_type_industry = implode(',',$qualifications['shop_type_industry']);
            $shop_type_industry_list = Factory::instance('/goods/v1/shopQualifications/index5')->run(['shop_qualifications_id'=>$shop_type_industry,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type'],'type'=>'shop_type_industry']);
            if(isset($shop['data']['shop_settled_content']['industry'])){
                $shop_type_industry_list['data'] = again_values($shop['data']['shop_settled_content']['industry'],$shop_type_industry_list['data'],'industry_images','shop_qualifications_id');
            }
        }
        $apiarr = array(
            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled2/save')->createHeaders(),
        );
        if(isset($shop['data']['shop_settled_content']['brand']) && !empty($shop['data']['shop_settled_content']['brand'])){
            $is_brand = 1;
        }else{
            $is_brand = 0;
        }
        return view('',[
            'category'=>$category,
            'headers'=>$apiarr,
            'industry' => $shop_type_industry_list['data'] ?? [],
            'image_domain'=>F::getImagesDomain(),
            'is_brand'=>$is_brand,
        ]);
    }

    /**
     * 选择商品类目选择商品品牌
     *
     * @return \think\response\View
     */
//    public function ChoiceGoods(){
//        //查询用户店铺信息是否存在
//        $shop = $this->shop;
//        if(!isset($shop['data']['shop_settled_content']['step_shop_type'])){
//            F::gotoUrl('/choice/choiceshoptype');
//        }
//        $shopdetail = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id' => $shop['data']['shop_settled_content']['step_shop_type']]);
//        //获取店铺类型介绍
//        $apiarr = array(
//            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled2/save')->createHeaders(),
//            'goodscategory' => AuthApi::getInstance('/goods/v1/goodsCategory/index')->createHeaders()
//        );
//        return view('',[
//            'headers'=>$apiarr,
//            'shopdetail'=>$shopdetail['data'],
//            'countcategory'=>count($shop['data']['shop_settled_content']['category_one'] ?? []),
//        ]);
//    }


}