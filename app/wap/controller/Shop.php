<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:15
 */

namespace app\wap\controller;
use app\wap\widget\Like;
use mercury\common\Seo;
use mercury\factory\Factory;
use mercury\weChat\Sdk;
use think\Session;
use app\common\traits\F;
use mercury\auth\api\AuthApi;

class Shop
{
    /**
     * 店铺首页
     *
     * @return \think\response\View
     */
    public function index()
    {
        $param = input('get.');
        $data['shop_id'] = intval($param['id']) ?? 0;
        $sgcid = isset($param['sgcid']) ? $param['sgcid'] : '';
        if($sgcid){
            $data2['sgcid'] = $sgcid;
        }
        $is_shop = Factory::instance('/goods/v1/attentionShop/isAttention')->run(['shop_id'=>$data['shop_id']]);
        $data2['shop_id'] = $data['shop_id'];
        $data2['page'] = isset($param['page']) ? $param['page'] : 1;
        if (request()->isAjax() && request()->has('page')) {
            $ret    = Factory::instance('/search/v1/goods/index')->run($data2);
            if(!empty($ret['data']['data'])){
                foreach ($ret['data']['data'] as $key=>$val){
                    if(!empty($val['goods_sku'])){
                        $ret['data']['data'][$key]['sku_id'] = $val['goods_sku'][0]['goods_sku_id'];
                    }
                    //$ret['data']['data'][$key]['goods_images2'] = F::getImagesDomain().$val['goods_images'];
                }
            }
            return json($ret);
        }else{
            $header['attention']    = AuthApi::getInstance('/goods/v1/attentionShop/create')->createHeaders();
            $header['del_attention']    = AuthApi::getInstance('/goods/v1/attentionShop/cancel')->createHeaders();
            $header['goods_category']    = AuthApi::getInstance('/goods/v1/shopGoodsCategory/index3')->createHeaders();
            $shop   = Factory::instance('/goods/v1/shop/detail')->run($data);
            if($shop['code'] != 20000){
                return redirect('/shop/empty');
            }
            $shoptype = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id'=>$shop['data']['shop_type_id']]);
            $shop['data']['shop_type_name'] = $shoptype['data']['shop_type_suffix'];
            return view('', [
                'headers'   => $header,
                'shop'      => $shop,
                'shop_id'   => $data['shop_id'],
                'sgcid'     => $sgcid,
                'is_shop'   => $is_shop,
                'seo'       => Seo::instance($shop['data']['shop_name'],$shop['data']['shop_description'],$shop['data']['shop_description'])->getSeo(),
                'weChat'    => (new Sdk('', ['url' => F::domain('wap', request()->url())]))->getJsApiParams()]);
        }

    }

    /**
     * 商家介绍
     *
     * @return \think\response\View
     */
    public function intro()
    {
        $param = input('get.');
        $data['shop_id'] = intval($param['id']) ?? 0;
        $shop   = Factory::instance('/goods/v1/shop/detail')->run($data);
        $shoptype = Factory::instance('/goods/v1/shopType/detail')->run(['shop_type_id'=>$shop['data']['shop_type_id']]);
        $shop['data']['shop_type_name'] = $shoptype['data']['shop_type_suffix'];
        return view('',['shop' => $shop]);
    }

    public function empty(){
        return view();
    }

    /**
     * 查看营业执照
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function businesslicense(){
        $param = input('get.');
        $shop_id = isset($param['id']) ? intval($param['id']) : 0;
        if(!$shop_id){
            return redirect('/');
        }
        $header['businesslicense']    = AuthApi::getInstance('/goods/v1/ShopSettled/detail')->createHeaders();
        return view('',['headers'   => $header]);
    }

    public function codey(){
        $param = input('post.');
        if(!isset($param['shop_id'])){
            return json(['code'=>0,'msg'=>'错误！']);
        }

        if(!captcha_check($param['code'])){
            return json(['code'=>0,'msg'=>'验证码输入错误！']);
        }

        $shop_user_id = db('shop')->cache(true)->where(['shop_id'=>$param['shop_id']])->value('user_id');
        $shop_businesslicense = db('shop_settled')->cache(true)->where(['user_id'=>$shop_user_id])->value('licence_Photo');

        return json(['code'=>20000,'msg'=>'请求成功！','data'=>['url'=>$shop_businesslicense]]);
    }

    /**
     * 联系商家
     *
     * @return \think\response\View
     */
    public function connect()
    {
        return view();
    }

    /**
     * 商品列表
     *
     * @return \think\response\View
     */
    public function goods()
    {
        return view();
    }

    /**
     * 评价列表
     *
     * @return \think\response\View
     */
    public function comments()
    {
        return view();
    }
}