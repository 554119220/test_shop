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

/**
 * Class Setting
 * @package app\seller\controller
 *
 * 店铺设置
 */
class Setting
{
    /**
     * 设置店铺信息
     *
     * @return \think\response\View
     */
    public function index()
    {
        $shop_id = Session::get('user.user_shop_id');
        $ret    = Factory::instance('/goods/v1/shop/detail')->run(['shop_id'=>$shop_id]);
        $res = [];
        if(isset($ret['data'])){
            if($ret['data']['shop_province_id']){
                $res['province'] = $ret['data']['shop_province_id'];
            }
            if($ret['data']['shop_city_id']){
                $res['city'] = $ret['data']['shop_city_id'];
            }

            if($ret['data']['shop_district_id']){
                $res['district'] = $ret['data']['shop_district_id'];
            }

            if($ret['data']['shop_town_id']){
                $res['town'] = $ret['data']['shop_town_id'];
            }
        }
        $district   = F::selectDistrict($res);//省市区选择
        $apiarr = array(
            'district' => AuthApi::getInstance('/tools/v1/district/index')->createHeaders(),
            'set_shop' => AuthApi::getInstance('/goods/v1/shop/update')->createHeaders(),
        );
        return view('',[
            'headers'=>$apiarr,
            'district'=>$district,
            'image_domain'=>F::getImagesDomain(),
            'shop'=>$ret['data']
        ]);
    }

    /**
     * 处理店铺信息
     */
    public function AjaxSetting(){
        $data = input('post.');
        $data['shop_province_id'] = $data['province_id'];
        $data['shop_city_id'] = $data['city_id'];
        $data['shop_district_id'] = $data['district_id'];
        $data['shop_town_id'] = $data['town_id'];
        $shop_logo = explode('/',$data['shop_logo']);
        $shop_logo_count = count($shop_logo);
        $data['shop_logo'] = $shop_logo[$shop_logo_count-1];
        $data['shop_id'] = Session::get('user.user_shop_id');
        $res = Factory::instance('/goods/v1/shop/update')->run($data);
        return json($res);
    }

    /**
     * 设置店铺域名
     *
     * @return \think\response\View
     */
    public function domain()
    {
        $shop_id = Session::get('user.user_shop_id');
        $ret    = Factory::instance('/goods/v1/shop/detail')->run(['shop_id'=>$shop_id]);
        $apiarr = array(
            'set_domain' => AuthApi::getInstance('/goods/v1/shop/set_domain')->createHeaders(),
        );
        return view('',[
            'shop'=>$ret['data'],
            'headers'=>$apiarr,
            ]);
    }

    /**
     * 设置域名前缀
     *
     * @return \think\response\Json
     */
    public function Ajaxdomain(){
        $data = input('post.');
        $res = Factory::instance('/goods/v1/shop/set_domain')->run($data);
        return json($res);
    }
}