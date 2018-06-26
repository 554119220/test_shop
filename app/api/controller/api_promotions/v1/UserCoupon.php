<?php
/**
 * Created by Zend Studio.
 * User: qinzichao
 * Date: 2017/12/05 
 * Time: 17:57
 */

namespace app\api\controller\promotions\v1;

use mercury\constants\Code;
use mercury\ResponseException;

use app\api\service\promotions\v1\UserCoupon as Service;


/**
 * Class Coupon
 * @package app\api\service\promotions\v1
 *
 * Api 用户优惠券
 */
class UserCoupon extends Init
{

    public function __construct()
    {
        parent::__construct();
    }


     /**
     *  领取优惠券
     *  
     * @param int $coupon_id 优惠券ID 【必填】
     * 
     * @return array|string
     * 
     *
    ======================成功结果======================
    {
      "msg": "请求成功",
      "info": "success",
      "code": 20000
    }
    ======================失败结果======================
    {
      "code": 60030,
      "msg": "已领取过此优惠券",
      "info": "已领取过此优惠券"
    }
     * 
     * 
     */
    public function create()
    {
        
        try {
            $result=Service::create([
                "user_id"=>$this->user["user_id"],
                "coupon_id"=>$this->data["coupon_id"],
            ]);
            
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            
            return Code::CODE_SUCCESS;
            
        } catch (ResponseException $e) {
            return $e->getData();
        }

    }
    
    /**
     * 我的所有优惠券
     * 
    ======================成功结果======================
     {
      "data": {
        "list": [
          {
            "coupon_id": 6,
            "coupon_name": "订单满100减50",
            "order_amount": "100.00",
            "discount_money": "50.00",
            "coupon_sn": "CP20171205111000033712603",
            "user_coupon_id": 9
          },
          {
            "coupon_id": 90,
            "coupon_name": "test2",
            "order_amount": "200.00",
            "discount_money": "15.00",
            "coupon_sn": "CP20171206151000096736142",
            "user_coupon_id": 16
          }
        ]
      },
      "msg": "请求成功",
      "info": "success",
      "code": 20000
    }
    ======================失败结果======================
    {
      "code": 60030,
      "msg": "已领取过此优惠券",
      "info": "已领取过此优惠券"
    }
     */
    public function myCoupon()
    {
        try {
     
            $result=Service::myCoupon([
                "user_id"=>$this->user["user_id"],
            ]);
        
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
        
            $data=[];
            $data["list"]=$result["data"];
            return $data;
        
        } catch (ResponseException $e) {
            return $e->getData();
        }

    }
    
    /**
     * 根据订单总额查询可用优惠券
     * 
     * @param int $user_id 买家ID 【必填】
     * @param float $order_amount 订单总额 【必填】
     *
     ======================成功结果======================
   {
      "data": {
        "list": [
          {
            "coupon_id": 6,
            "coupon_name": "订单满100减50",
            "order_amount": "100.00",
            "discount_money": "50.00",
            "coupon_sn": "CP20171205111000033712603",
            "user_coupon_id": 9
          },
          {
            "coupon_id": 90,
            "coupon_name": "test2",
            "order_amount": "200.00",
            "discount_money": "15.00",
            "coupon_sn": "CP20171206151000096736142",
            "user_coupon_id": 16
          }
        ]
      },
      "msg": "请求成功",
      "info": "success",
      "code": 20000
    }
     ======================失败结果======================
     {
     "code": 60030,
     "msg": "已领取过此优惠券",
     "info": "已领取过此优惠券"
     }
     */
    public function myCouponByOrderAmount()
    {
        try {
             
            $result=Service::myCouponByOrderAmount(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
    
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
    
            $data=[];
            $data["list"]=$result["data"];
            return $data;
    
        } catch (ResponseException $e) {
            return $e->getData();
        }

    }
    
    /**
     * 使用我的优惠券
     * 
    ======================成功结果======================
    {
      "msg": "请求成功",
      "info": "success",
      "code": 20000
    }
    ======================失败结果======================
    {
      "code": 60030,
      "msg": "此优惠券已使用",
      "info": "此优惠券已使用"
    }
     */
    public function useCoupon()
    {
        try {
             
            $result=Service::useCoupon(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
    
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
    
            return Code::CODE_SUCCESS;
    
        } catch (ResponseException $e) {
            return $e->getData();
        }

    }

    
}