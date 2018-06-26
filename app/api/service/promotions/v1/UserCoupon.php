<?php
/**
 * Created by Zend Studio.
 * User: qinzichao
 * Date: 2017/12/05 0001
 * Time: 10:44
 */

namespace app\api\service\promotions\v1;


use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
use traits\think\Instance;


/**
 * Class UserCoupon
 * @package app\api\service\promotions\v1
 *
 * 用户优惠券
 */
class UserCoupon
{
    use Instance;

     /**
     *  领取优惠券
     *  
     * @param int $user_id 买家ID 【必填】
     * @param int $coupon_id 优惠券ID 【必填】
     * 
     * @return array
     * 
     * 
     */
    public static function create(array $params)
    {
        
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
        
        if(!isset($params["coupon_id"])||!$params["coupon_id"]||intval($params["coupon_id"])<=0){
            $ret["msg"]="优惠券参数错误";
            $ret["info"]="优惠券参数错误";
            return $ret;
        }
        if(!isset($params["user_id"])||!$params["user_id"]||intval($params["user_id"])<=0){
            $ret["msg"]="买家参数错误";
            $ret["info"]="买家参数错误";
            return $ret;
        }

        try {
            
            //查询优惠券是否可用
            $map=[];
            $map['status']=State::STATE_NORMAL;
            $map['coupon_id'] = $params['coupon_id'];
            $coupon_model=new \app\api\model\promotions\Coupon();
            $count =$coupon_model ->where($map) ->count();;
            if($count<=0){
                $ret["msg"]="此优惠券不可用";
                $ret["info"]="此优惠券不可用";
                return $ret;
            }
            
            $map=[];
            $map['buyer_user_id']=$params['user_id'];
            $map['coupon_id'] = $params['coupon_id'];
            
            //查询是否已领取过此优惠券
            $user_copon_model=new \app\api\model\promotions\UserCoupon();
            $count =$user_copon_model ->where($map) ->count();
            if($count>0){
                $ret["msg"]="已领取过此优惠券";
                $ret["info"]="已领取过此优惠券";
                return $ret;
            }
            
            $user_coupon=[
                "buyer_user_id"=>$params["user_id"],
                "coupon_id"=>$params["coupon_id"],
                "coupon_sn"=>create_no("CP",$params["user_id"]),
            ];
 
            $result=$user_copon_model->save($user_coupon);
            if($result===false){
                $ret["msg"]="领取失败";
                $ret["info"]="领取失败";
                return $ret;
            }
            
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="领取成功";
            $ret["info"]="领取成功";
            return $ret;
            
        } catch (ResponseException $e) {
            $ret["msg"]="领取失败";
            $ret["info"]="领取失败";
            return $ret;
        }
        
        return $ret;
    }
    
    
    /**
     *  我的所有优惠券
     *
     * @param int $user_id 买家ID 【必填】
     *
     * @return array
     *
     *
     */
    public static function myCoupon(array $params)
    {
    
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];

        if(!isset($params["user_id"])||!$params["user_id"]||intval($params["user_id"])<=0){
            $ret["msg"]="买家参数错误";
            $ret["info"]="买家参数错误";
            return $ret;
        }
    
        try {

            $map=[];
            $map['uc.buyer_user_id']=$params['user_id'];
            $map['c.status']=STATE::STATE_NORMAL;
            $user_copon_model=new \app\api\model\promotions\UserCoupon();
           
            $list =$user_copon_model->alias("uc")->join("coupon c","c.coupon_id=uc.coupon_id","left")
            ->field("c.coupon_id,c.coupon_name,c.discount_money,uc.coupon_sn,uc.user_coupon_id")
            ->where($map)->select();
            
            $ret["data"]=$list;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="查询成功";
            $ret["info"]="查询成功";
            return $ret;
    
        } catch (ResponseException $e) {
            $ret["msg"]="查询失败";
            $ret["info"]="查询失败";
            return $ret;
        }
    
        return $ret;
    }
    
    
    /**
     *  根据订单总额查询可用优惠券
     *
     * @param int $user_id 买家ID 【必填】
     * @param float $order_amount 订单总额 【必填】
     *
     * @return array
     *
     *
     */
    public static function myCouponByOrderAmount(array $params)
    {
    
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["user_id"])||!$params["user_id"]||intval($params["user_id"])<=0){
            $ret["msg"]="买家参数错误";
            $ret["info"]="买家参数错误";
            return $ret;
        }
        
        if(!isset($params["order_amount"])||!$params["order_amount"]||floatval($params["order_amount"])<=0){
            $ret["msg"]="订单总额参数错误";
            $ret["info"]="订单总额参数错误";
            return $ret;
        }
    
        try {
    
            $map=[];
            $map['uc.buyer_user_id']=$params['user_id'];
            $map['c.status']=STATE::STATE_NORMAL;
            
            $map['c.order_amount']=['egt',$params["order_amount"]];
            
            $user_copon_model=new \app\api\model\promotions\UserCoupon();
             
            $list =$user_copon_model->alias("uc")->join("coupon c","c.coupon_id=uc.coupon_id","left")
            ->field("c.coupon_id,c.coupon_name,c.order_amount,c.discount_money,uc.coupon_sn,uc.user_coupon_id")
            ->where($map)->select();
            
//             exit($user_copon_model->getLastSql());
    
            $ret["data"]=$list;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="查询成功";
            $ret["info"]="查询成功";
            return $ret;
    
        } catch (ResponseException $e) {
            $ret["msg"]="查询失败";
            $ret["info"]="查询失败";
            return $ret;
        }
    
        return $ret;
    }
    
    
    /**
     *  使用优惠券
     *
     * @param int $user_id 买家ID 【必填】
     *
     * @return array
     *
     *
     */
    public static function useCoupon(array $params)
    {
    
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["user_id"])||!$params["user_id"]||intval($params["user_id"])<=0){
            $ret["msg"]="买家参数错误";
            $ret["info"]="买家参数错误";
            return $ret;
        }
        if(!isset($params["coupon_sn"])||!$params["coupon_sn"]){
            $ret["msg"]="用户优惠券编号参数错误";
            $ret["info"]="用户优惠券编号参数错误";
            return $ret;
        }
    
        try {
            
            //查询用户优惠券是否存在
            $map=[];
            $map['buyer_user_id']=$params['user_id'];
            $map['coupon_sn']=$params['coupon_sn'];
            $user_copon_model=new \app\api\model\promotions\UserCoupon();
             
            $item =$user_copon_model->where($map)->field("user_coupon_id,status")->find();
            
            if(!$item){
                $ret["msg"]="不存在此优惠券";
                $ret["info"]="不存在此优惠券";
                return $ret;
            }
            
            if($item["status"]==State::STATE_NORMAL){
                $ret["msg"]="此优惠券已使用";
                $ret["info"]="此优惠券已使用";
                return $ret;
            }
    
            //优惠券设置为已使用
            $result=$user_copon_model->where(["user_coupon_id"=>$item["user_coupon_id"]])->update(["status"=>State::STATE_NORMAL]);
            if($result===false){
                $ret["msg"]="使用失败";
                $ret["info"]="使用失败";
                return $ret;
            }
            
            $ret["data"]=$result;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="使用成功";
            $ret["info"]="使用成功";
            return $ret;

        } catch (ResponseException $e) {
            $ret["msg"]="更新失败";
            $ret["info"]="更新失败";
            return $ret;
        }
    
        return $ret;
    }
        
    
}