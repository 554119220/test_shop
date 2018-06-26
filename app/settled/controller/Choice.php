<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/07
 * Time: 16:17
 */

namespace app\settled\controller;

use mercury\ResponseException;
use app\common\traits\F;
use mercury\factory\Factory;
use mercury\auth\api\AuthApi;
use mercury\constants\Code;
use think\Controller;
use think\Session;
class Choice extends Controller
{
    protected $shop = [];
    protected $beforeActionList = [
        'getshop' =>  ['only'=>'qualifications,industryqualification,choicegoods,setshop'],
    ];

    public function index2(){
        if(session('shop_settled')){


        F::gotoUrl('/checksettled/process');

        }else{
            F::gotoUrl('/choice/index');
        }
    }

    /**
     * 选择开店类型（）
     *
     * @return \think\response\View
     */
    public function index(){
        $notice = Factory::instance('/goods/v1/shopArticle/detail')->run(['shop_article_id'=>5]); //开店须知
        $agreement = Factory::instance('/goods/v1/shopArticle/detail')->run(['shop_article_id'=>9]); //开店协议
        $service_agreement = Factory::instance('/goods/v1/shopArticle/detail')->run(['shop_article_id'=>21]); //百望商城卖家服务协议
        $apiarr = array(
            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled/save')->createHeaders(),
            'service_agreement' => AuthApi::getInstance('/goods/v1/shopArticle/detail')->createHeaders(),
        );
        return view('',[
            'headers'=>$apiarr,
            'agreement'=>$agreement,
            'notice'=>$notice,
            'service_agreement'=>$service_agreement,
        ]);
    }

    public function getshop(){
        $shop = Factory::instance('/goods/v1/shopSettled/detail')->run();
        if(!$shop) exit('非法操作！');
        $this->assign('shop',$shop['data']);
        $this->shop = $shop;
    }


    /**
     * 选择开店类型
     *
     * @return \think\response\View
     *
     */
    public function ChoiceShopType(){
        $user = Session::get('user');
        $shoptype = Factory::instance('/goods/v1/shopType/index')->run();
        $apiarr = array(
            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled/save')->createHeaders(),
        );
        return view('',[
            'headers'=>$apiarr,
            'shoptype'=>$shoptype['data'],
            'image_domain'=>F::getImagesDomain(),
            'enterprise'=>$user['erpUser']['is_enterprise'],
            'level_id'=>$user['erpUser']['level_id'],
        ]);
    }

    /**
     * 申请入驻
     *
     * @return \think\response\View
     */
    public function ApplySettled(){
        return view();
    }

    /**
     * 入驻指南
     *
     * @return \think\response\View
     */
    public function SettledGuide(){
        return view();
    }


    /**
     * 上传店铺资质
     *
     * @return \think\response\View
     */
    public function Qualifications(){
        $shop = $this->shop;
        if(!isset($shop['data']['shop_settled_content']['step_shop_type'])){
            F::gotoUrl('/choice/choiceshoptype');
        }
        if(empty($shop['data']['shop_settled_content']['brand'])){
            //没有选择品牌，直接进去到最后一步，填写店铺资料
            F::gotoUrl('/choice/industryqualification');
        }
        $shoptype = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type']]);
        $qualifications = unserialize($shoptype['data']['shop_qualifications']);

        if(isset($qualifications['shop_type_brand1'])){
            $shop_type_brand1 = implode(',',$qualifications['shop_type_brand1']);
            $shop_type_brand1_list = Factory::instance('/goods/v1/shopQualifications/index5')->run(['shop_qualifications_id'=>$shop_type_brand1,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type'],'type'=>'shop_type_brand1']);
        }
        if(isset($qualifications['shop_type_brand2'])){
            $shop_type_brand2 = implode(',',$qualifications['shop_type_brand2']);
            $shop_type_brand2_list = Factory::instance('/goods/v1/shopQualifications/index5')->run(['shop_qualifications_id'=>$shop_type_brand2,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type'],'type'=>'shop_type_brand2']);
        }

/*
        if(isset($qualifications['shop_type_brand1']) || isset($qualifications['shop_type_brand2'])){
            $shop_type_brand1 = implode(',',$qualifications['shop_type_brand1']);
            $shop_type_brand2 = implode(',',$qualifications['shop_type_brand2']);
            $shop_type_brand1_list = Factory::instance('/goods/v1/shopQualifications/index5')->run(['shop_qualifications_id'=>$shop_type_brand1,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type'],'type'=>'shop_type_brand1']);
            $shop_type_brand2_list = Factory::instance('/goods/v1/shopQualifications/index5')->run(['shop_qualifications_id'=>$shop_type_brand2,'shop_type_id'=>$shop['data']['shop_settled_content']['step_shop_type'],'type'=>'shop_type_brand2']);
        }
*/
        if(isset($shop['data']['shop_settled_content']['brand'])){
            $brand = $shop['data']['shop_settled_content']['brand'];
            foreach ($brand as $key=>$value) {
                $brand[$key]['images1'] = again_values2($value['images1'] ?? [], $shop_type_brand1_list['data'] ?? []);
                $brand[$key]['images2'] = again_values2($value['images2'] ?? [], $shop_type_brand2_list['data'] ?? []);
            }
            $shop['data']['shop_settled_content']['brand'] = $brand;
        }
        $apiarr = array(
            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled/save')->createHeaders(),
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
            F::gotoUrl('/choice/choicegoods');
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
            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled/save')->createHeaders(),
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
     * 开店认证
     *
     * @return \think\response\View
     */
    public function ChoiceAuth(){
        return view();
    }

    /**
     * 选择商品类目选择商品品牌
     *
     * @return \think\response\View
     */
    public function ChoiceGoods(){
        //查询用户店铺信息是否存在
        $shop = $this->shop;
        if(!isset($shop['data']['shop_settled_content']['step_shop_type'])){
            F::gotoUrl('/choice/choiceshoptype');
        }
        $shopdetail = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id' => $shop['data']['shop_settled_content']['step_shop_type']]);
        //获取店铺类型介绍
        $apiarr = array(
            'record_step' => AuthApi::getInstance('/goods/v1/shopSettled/save')->createHeaders(),
            'goodscategory' => AuthApi::getInstance('/goods/v1/goodsCategory/index')->createHeaders()
        );
        return view('',[
            'headers'=>$apiarr,
            'shopdetail'=>$shopdetail['data'],
            'countcategory'=>count($shop['data']['shop_settled_content']['category_one'] ?? []),
        ]);
    }

    /**
     * 上传品牌凭证
     *
     * @return \think\response\View
     */
    public function ChoiceBand(){
        return view();
    }


    /**
     * 设置店铺信息（最后一步）保存
     *
     * @return \think\response\View
     */
    public function SetShop(){
        $shop = $this->shop;
        if(!isset($shop['data']['shop_settled_content']['category_images'])){
            F::gotoUrl('/choice/industryqualification');
        }
        $res = [];
        if(isset($shop['data']['shop_settled_content']['step_shop_info'])){
            if($shop['data']['shop_settled_content']['step_shop_info']['shop_province_id']){
                $res['province'] = $shop['data']['shop_settled_content']['step_shop_info']['shop_province_id'];
            }
            if($shop['data']['shop_settled_content']['step_shop_info']['shop_city_id']){
                $res['city'] = $shop['data']['shop_settled_content']['step_shop_info']['shop_city_id'];
            }

            if($shop['data']['shop_settled_content']['step_shop_info']['shop_district_id']){
                $res['district'] = $shop['data']['shop_settled_content']['step_shop_info']['shop_district_id'];
            }

            if($shop['data']['shop_settled_content']['step_shop_info']['shop_town_id']){
                $res['town'] = $shop['data']['shop_settled_content']['step_shop_info']['shop_town_id'];
            }
        }
        $shopdetail = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id' => $shop['data']['shop_settled_content']['step_shop_type']]);
        $district   = F::selectDistrict($res);//省市区选择
        //获取店铺名称禁止关键词
        $disabled_keyword = Factory::instance('/goods/v1/disabledKeyword/detail')->run(['id' => 1027]);
        $keyword = explode(',',$disabled_keyword['data']['keyword']);
        $count_keyword = count($keyword);
        $apiarr = array(
            'district' => AuthApi::getInstance('/tools/v1/district/index')->createHeaders(),
            'set_shop' => AuthApi::getInstance('/goods/v1/shopSettled/save')->createHeaders(),
        );
        return view('',[
            'headers'=>$apiarr,
            'disabled_keyword'=>$keyword,
            'count_keyword'=>$count_keyword,
            'shopdetail'=>$shopdetail,
            'shop_brand'=>$shop['data']['shop_settled_content']['brand'][0]['name'],
            'district'=>$district,
            'image_domain'=>F::getImagesDomain(),
        ]);
    }

    /**
     * 选择商店类型tpl
     * @param int $shop_type
     * @return \think\response\View
     */
    public function BrandTpl($shop_type = 0){
        switch ($shop_type) {
            //店铺类型
            case 1 :
                //个人店铺
                $tpl = 'personalshop';
                break;
            case 2 :
                //自营店
                $tpl = 'selfsupportshop';
                break;
            case 3 :
                //旗舰店
                $tpl = 'flagshipshop';
                break;
            case 4 :
                //专营店
                $tpl = 'monopolyshop';
                break;
        }
        return view($tpl);
    }

    /**
     * ajax处理用户设置店铺信息的提交数据
     *
     * @return array
     */
    public function AjaxSetShop(){
        $data = input('post.');
        $disabled_keyword = Factory::instance('/goods/v1/disabledKeyword/detail')->run(['id' => 1027]);
        $keyword = explode(',',$disabled_keyword['data']['keyword']);
        $count_keyword = count($keyword);
        for ($i = 0;$i < $count_keyword; $i++){
            if(strpos($data['shop_name'],$keyword[$i])){
                return json(['code'=>Code::CODE_OTHER_FAIL,'msg'=>'店铺名称出现了“'.$keyword[$i].'”字眼，请修改！']);
            }
        }
        $chcke_shop = F::vApi('goods','ShopSettled');
        if(  true !== $chcke_shop->scene('auth_shop')->check($data)){
            return json(['code'=>Code::CODE_OTHER_FAIL,'msg'=>$chcke_shop->getError()]);
        }
        $data['step'] = 'step_shop_info';
        $arr = Factory::instance('/goods/v1/shopSettled/save')->run($data);
        return json($arr);
    }

    /**
     * 获取下级城市列表
     *
     * @return \think\response\Json
     */
    public function AjaxCity(){
        $params = $this->param;
        $arr = Factory::instance('/tools/v1/district/index')->run(['id' => $params['province']]);
        return json($arr);
    }
}