<?php
/**
 * Created by Zend Studio.
 * User: qinzichao
 * Date: 2017/12/4 
 * Time: 17:57
 */

namespace app\api\controller\promotions\v1;

use mercury\constants\Code;
use mercury\ResponseException;

use app\api\service\promotions\v1\Coupon as Service;

/**
 * Class Coupon
 * @package app\api\controller\promotions\v1
 *
 * Api 优惠券
 */
class Coupon extends Init
{

    public function __construct()
    {
        parent::__construct();
    
    }

    /**
     *  查询优惠券列表
     *  
     * @param int $goods_id 商品ID 【查询商品优惠券时，必填】
     * @param int $shop_id 店铺ID 【查询店铺优惠券时，必填】
     * 
     * @return array 
     * 
     * 
        ===============正常结果===============
        {
          "data": [
            {
              "coupon_id": 6,
              "coupon_name": "订单满100减50",
              "discount_money": "50.00"
            },
            {
              "coupon_id": 9,
              "coupon_name": "新手减5",
              "discount_money": "5.00"
            }
          ],
          "msg": "请求成功",
          "info": "success",
          "code": 20000
        }
        ===============异常结果：===============
        {
          "code": 20040,
          "msg": "无内容返回",
          "info": "no content"
        }
     */
    public  function list()
    {
         try {
             $result=Service::list($this->data);
             
             if($result["code"]!=Code::CODE_SUCCESS){
                 throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
             }
             return $result["data"];
        } catch (ResponseException $e) {
            return $e->getData();
        }
        return [];
    }
    
    /**
     *  创建优惠券
     *
     * @param string $coupon_name 优惠券 【必填】
     * @param float  $discount_money 优惠券的价值  【必填】
     * @param string $goods_ids 适用于哪些商品，商品ID串 ，多个可用英文逗号分隔【可选】
     * @param int    $shop_id 适用于哪个店铺，店铺ID【可选】
     * @param string $start_time 优惠券开始时间，如：2017-12-05 【可选】
     * @param string $end_time 优惠券结束时间，如：2017-12-06  【可选】
     * @param float  $order_amount 订单满多少才可使用  【可选】
     * @param int    $limit_num 限制发行张数  【可选】
     * @param int    $is_system 是否系统通用：0否，1是  【可选】
     * 
     * @return array | string
     *
     *
     ===============正常结果===============
     {
      "msg": "请求成功",
      "info": "success",
      "code": 20000
    }
     ===============异常结果：===============
     {
      "code": 60030,
      "msg": "商品已设置了优惠券,创建失败",
      "info": "商品已设置了优惠券,创建失败"
    }
     */
    public function create()
    {
        try {
            $result=Service::create(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return $result["data"];
        } catch (ResponseException $e) {
            return $e->getData();
        }
        return [];
    }
    
 /**
     *  更新优惠券
     *
     * @param int    $coupon_id 优惠券ID 【必填】
     * @param string $coupon_name 优惠券 【可选】
     * @param float  $discount_money 优惠券的价值  【可选】
     * @param string $goods_ids 适用于哪些商品，商品ID串 ，多个可用英文逗号分隔【可选】
     * @param string $start_time 优惠券开始时间，如：2017-12-05 【可选】
     * @param string $end_time 优惠券结束时间，如：2017-12-06  【可选】
     * @param float  $order_amount 订单满多少才可使用  【可选】
     * @param int    $limit_num 限制发行张数  【可选】
     * @param int    $is_system 是否系统通用：0否，1是  【可选】
     *
     * @return array | string
     *
     *
     ===============正常结果===============
     {
     "msg": "请求成功",
     "info": "success",
     "code": 20000
     }
     ===============异常结果：===============
     {
     "code": 60030,
     "msg": "更新失败",
     "info": "更新失败"
     }
     */
    public function update()
    {
        try {
            $result=Service::update(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return $result["data"];
        } catch (ResponseException $e) {
            return $e->getData();
        }
        return [];
    }

    
    /**
     *  禁用优惠券
     *
     * @param int    $coupon_id 优惠券ID 【必填】

     *
     * @return array | string
     *
     *
     ===============正常结果===============
     {
     "msg": "请求成功",
     "info": "success",
     "code": 20000
     }
     ===============异常结果：===============
     {
     "code": 60030,
     "msg": "更新失败",
     "info": "更新失败"
     }
     */
    public function disable()
    {
        try {
            $result=Service::disable(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return $result["data"];
        } catch (ResponseException $e) {
            return $e->getData();
        }
        return [];
    }
    
    
    /**
     *  启用优惠券
     *
     * @param int    $coupon_id 优惠券ID 【必填】
    
     *
     * @return array | string
     *
     *
     ===============正常结果===============
     {
     "msg": "请求成功",
     "info": "success",
     "code": 20000
     }
     ===============异常结果：===============
     {
     "code": 60030,
     "msg": "更新失败",
     "info": "更新失败"
     }
     */
    public function enable()
    {
        try {
            $result=Service::enable(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return $result["data"];
        } catch (ResponseException $e) {
            return $e->getData();
        }
        return [];
    }
    
    
}