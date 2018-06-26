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
use think\Db;



/**
 * Class UserCoupon
 * @package app\api\service\promotions\v1
 *
 * 优惠券
 */
class Coupon
{
    use Instance;

     /**
     *  查询优惠券列表
     *  
     * 
     * @param int $goods_id 商品ID 【查询商品优惠券时，必填】
     * @param int $shop_id 店铺ID 【查询店铺优惠券时，必填】
     * 
     * @return array
     * 
     * 
     */
    public static function list(array $params)
    {
        
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];

        try {

            $coupon_model=new \app\api\model\promotions\Coupon();
            $coupon_model=$coupon_model->alias("c");

            $map=[];
            $map['c.status']=State::STATE_NORMAL;
            $map['c.start_time'] =  array(['=',0],['<=',time()],'or');
            $map['c.end_time'] =  array(['=',0],['>=',time()],'or');
            
            //查询此商品或者系统通用的
            if(isset($params["goods_id"])&&$params["goods_id"]&&intval($params["goods_id"])>0){
                $coupon_model=$coupon_model->join("coupon_goods cg","cg.coupon_id=c.coupon_id","left");
                
                $m1=[];
                $m1['cg.goods_id'] =  $params['goods_id'];
                $m1['c.is_system'] = 1;
                $coupon_model=$coupon_model->where(function($query)use ($m1){
                    $query->whereOr($m1);
                });
            }
            
            //查询此店铺或者系统通用的
            if(isset($params["shop_id"])&&$params["shop_id"]&&intval($params["shop_id"])>0){
                $m1=[];
                $m1['c.shop_id'] =  $params['shop_id'];
                $m1['c.is_system'] = 1;
                $coupon_model=$coupon_model->where(function($query)use ($m1){
                    $query->whereOr($m1);
                });
            }
            
            $list = $coupon_model ->where($map)->field("c.coupon_id,c.coupon_name,c.discount_money")->select();
//             exit($coupon_model->getLastSql());
            
            $ret["data"]=$list;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="查询成功";
            $ret["info"]="查询成功";
            return $ret;
            
        } catch (ResponseException $e) {
            $ret["msg"]="查询优惠券失败";
            $ret["info"]="查询优惠券失败";
            return $ret;
        }
        
        return $ret;
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
        
        if(!isset($params["coupon_name"])|| !$params["coupon_name"]){
            $ret["msg"]="优惠券名称参数错误";
            $ret["info"]="优惠券名称参数错误";
            return $ret;
        }
        
        if(!isset($params["discount_money"])|| !$params["discount_money"] || floatval($params["discount_money"])<=0){
            $ret["msg"]="优惠券价值参数错误";
            $ret["info"]="优惠券价值参数错误";
            return $ret;
        }
        
        if(!isset($params["user_id"])|| !$params["user_id"] || intval($params["user_id"])<=0){
            $ret["msg"]="创建者参数错误";
            $ret["info"]="创建者参数错误";
            return $ret;
        }

        try {
            
            $coupon_model=new \app\api\model\promotions\Coupon();

            $data=[
                "coupon_name"=>trim($params["coupon_name"]),
                "shop_id"=>isset($params["shop_id"])&&$params["shop_id"]&&intval($params["shop_id"])>0?intval($params["shop_id"]):0,
                "start_time"=>isset($params["start_time"])&&$params["start_time"]?strtotime($params["start_time"]." 00:00:00"):0,
                "end_time"=>isset($params["end_time"])&&$params["end_time"]?strtotime($params["end_time"]." 23:59:59"):0,
                "order_amount"=>isset($params["order_amount"])&&$params["order_amount"]&&floatval($params["order_amount"])>0?floatval($params["order_amount"]):0,
                "limit_num"=>isset($params["limit_num"])&&$params["limit_num"]&&intval($params["limit_num"])>0?intval($params["limit_num"]):0,
                "discount_money"=>isset($params["discount_money"])&&$params["discount_money"]&&floatval($params["discount_money"])>0?floatval($params["discount_money"]):0,
                "is_system"=>isset($params["is_system"])&&$params["is_system"]&&intval($params["is_system"])>0?intval($params["is_system"]):0,
                "creator_user_id"=>isset($params["user_id"])&&$params["user_id"]&&intval($params["user_id"])>0?intval($params["user_id"]):0,
            ];
//             exit(print_r($data));
            
            //开始事务处理
            Db::startTrans();
  
            $result=$coupon_model->save($data);
            if($result === false){
                
                //回滚事务
                DB::rollback();
                
                $ret["msg"]="创建失败";
                $ret["info"]="创建失败";
                return $ret;
            }
            
            $coupon_id=$coupon_model->coupon_id;

            //如果针对商品设置优惠券
            if(isset($params["goods_ids"])&&$params["goods_ids"]){
            
                $coupon_goods_model=new \app\api\model\promotions\CouponGoods();
                
                $goods_ids=explode(",", $params["goods_ids"]);
            
                //删除原来绑定的商品
                $coupon_goods_model->where(["goods_id"=>["in",$goods_ids]])->delete();
                
                $data=[];
                foreach ($goods_ids as $goods_id){
                    $data[]=[
                        "goods_id"=>$goods_id,
                        "coupon_id"=>$coupon_id,
                    ];
                }
                $result=$coupon_goods_model->saveAll($data);
                if($result === false){
                
                    //回滚事务
                    DB::rollback();
                
                    $ret["msg"]="设置商品优惠券失败";
                    $ret["info"]="设置商品设置优惠券失败";
                    return $ret;
                }
            }
            
            //提交事务
            Db::commit();
           
            $ret["data"]=CODE::CODE_SUCCESS;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="创建成功";
            $ret["info"]="创建成功";
            return $ret;
    
        } catch (ResponseException $e) {
            $ret["msg"]="创建失败";
            $ret["info"]="创建失败";
            return $ret;
        }
    
        return $ret;
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
     * @return array
     *
     *
     */
    public static function update(array $params)
    {
    
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        
        if(!isset($params["coupon_id"])|| !$params["coupon_id"] || intval($params["coupon_id"])<=0){
            $ret["msg"]="优惠券参数错误";
            $ret["info"]="优惠券参数错误";
            return $ret;
        }
        
        if(!isset($params["user_id"])|| !$params["user_id"] || intval($params["user_id"])<=0){
            $ret["msg"]="创建者参数错误";
            $ret["info"]="创建者参数错误";
            return $ret;
        }
    
        try {
    
            $coupon_model=new \app\api\model\promotions\Coupon();
           
            $map=[];
            $map['coupon_id']=intval($params['coupon_id']);
            $map['creator_user_id']=intval($params['user_id']);
            
            $coupon=$coupon_model->where($map)->find();
            if(!$coupon){
                $ret["msg"]="优惠券不存在";
                $ret["info"]="优惠券不存在";
                return $ret;
            }
            
            $data=[];
            if(isset($params["coupon_name"])&&$params["coupon_name"]&&trim($params["coupon_name"])){
                $data["coupon_name"]=trim($params["coupon_name"]);
            }
            if(isset($params["discount_money"])&&$params["discount_money"]&&floatval($params["discount_money"])>0){
                $data["discount_money"]=floatval($params["discount_money"]);
            }
            if(isset($params["start_time"])&&$params["start_time"]){
                $data["start_time"]=strtotime($params["start_time"]." 00:00:00");
            }
            if(isset($params["end_time"])&&$params["end_time"]){
                $data["end_time"]=strtotime($params["end_time"]." 23:59:59");
            }
            if(isset($params["order_amount"])&&$params["order_amount"]&&floatval($params["order_amount"])>0){
                $data["order_amount"]=floatval($params["order_amount"]);
            }
            if(isset($params["limit_num"])&&$params["limit_num"]&&intval($params["limit_num"])>0){
                $data["limit_num"]=intval($params["limit_num"]);
            }
            if(isset($params["is_system"])){
                $data["is_system"]=intval($params["is_system"]);
            }
//             exit(print_r($data));
            
            //开始事务处理
            Db::startTrans();
            
            $coupon_id=$params["coupon_id"];

            $result=$coupon_model->where(["coupon_id"=>$coupon_id])->update($data);
            if($result === false){
                
                //回滚事务
                DB::rollback();
                
                $ret["msg"]="更新优惠券失败";
                $ret["info"]="更新优惠券失败";
                return $ret;
            }
 
            //如果针对商品设置优惠券
            if(isset($params["goods_ids"])&&$params["goods_ids"]){
            
                $coupon_goods_model=new \app\api\model\promotions\CouponGoods();
                $goods_ids=explode(",", $params["goods_ids"]);
                
                //删除原来绑定的商品
                $coupon_goods_model->where(["goods_id"=>["in",$goods_ids]])->delete();
                
                $data=[];
                foreach ($goods_ids as $goods_id){
                    $data[]=[
                        "goods_id"=>$goods_id,
                        "coupon_id"=>$coupon_id,
                    ];
                }
                
                $result=$coupon_goods_model->saveAll($data);
                if($result === false){
        
                    //回滚事务
                    DB::rollback();
        
                    $ret["msg"]="设置商品优惠券失败";
                    $ret["info"]="设置商品设置优惠券失败";
                    return $ret;
                }
            }
            
            //提交事务
            Db::commit();
             
            $ret["data"]=CODE::CODE_SUCCESS;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="更新优惠券成功";
            $ret["info"]="更新优惠券成功";
            return $ret;
    
        } catch (ResponseException $e) {
            $ret["msg"]="更新优惠券失败";
            $ret["info"]="更新优惠券失败";
            return $ret;
        }
    
        return $ret;
    }
    
    
    /**
     *  禁用优惠券
     *
     *
     * @param int    $coupon_id 优惠券ID 【必填】
     *
     * @return array
     *
     *
     */
    public static function disable(array $params)
    {
    
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["coupon_id"])|| !$params["coupon_id"]|| intval($params["coupon_id"])<=0){
            $ret["msg"]="优惠券ID参数错误";
            $ret["info"]="优惠券ID参数错误";
            return $ret;
        }
        
        try {

            //先查询这个优惠券是否存在
            $coupon_model=new \app\api\model\promotions\Coupon();
            $coupon=$coupon_model->where(["coupon_id"=>$params["coupon_id"]])->find();
            if(!$coupon){
                $ret["msg"]="优惠券不存在";
                $ret["info"]="优惠券不存在";
                return $ret;
            }

            $result=$coupon_model->where(["coupon_id"=>$params["coupon_id"]])->update(["status"=>State::STATE_DISABLED]);
            if($result===false){
                $ret["msg"]="禁用失败";
                $ret["info"]="禁用失败";
                return $ret;
            }
             
            $ret["data"]=CODE::CODE_SUCCESS;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="禁用成功";
            $ret["info"]="禁用成功";
            return $ret;
    
        } catch (ResponseException $e) {
            $ret["msg"]="禁用失败";
            $ret["info"]="禁用失败";
            return $ret;
        }
    
        return $ret;
    }
    
    
    /**
     *  启用优惠券
     *
     *
     * @param int    $coupon_id 优惠券ID 【必填】
     *
     * @return array
     *
     *
     */
    public static function enable(array $params)
    {
    
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["coupon_id"])|| !$params["coupon_id"]|| intval($params["coupon_id"])<=0){
            $ret["msg"]="优惠券参数错误";
            $ret["info"]="优惠券参数错误";
            return $ret;
        }
    
        try {
    
            //先查询这个优惠券是否存在
            $coupon_model=new \app\api\model\promotions\Coupon();
            $coupon=$coupon_model->where(["coupon_id"=>$params["coupon_id"]])->find();
            if(!$coupon){
                $ret["msg"]="优惠券不存在";
                $ret["info"]="优惠券不存在";
                return $ret;
            }
    
            $result=$coupon_model->where(["coupon_id"=>$params["coupon_id"]])->update(["status"=>State::STATE_NORMAL]);
            if($result===false){
                $ret["msg"]="启用失败";
                $ret["info"]="启用失败";
                return $ret;
            }
             
            $ret["data"]=CODE::CODE_SUCCESS;
            $ret["code"]=CODE::CODE_SUCCESS;
            $ret["msg"]="启用成功";
            $ret["info"]="启用成功";
            return $ret;
    
        } catch (ResponseException $e) {
            $ret["msg"]="启用失败";
            $ret["info"]="启用失败";
            return $ret;
        }
    
        return $ret;
    }

    
}