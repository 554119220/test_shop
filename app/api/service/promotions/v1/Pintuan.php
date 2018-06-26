<?php
/**
 * Created by Zend Studio.
 * User: qinzichao
 * Date: 2017/12/05 0001
 * Time: 10:15
 */

namespace app\api\service\promotions\v1;

use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
use traits\think\Instance;

/**
 * Class Pintuan
 * @package app\api\service\promotions\v1
 *
 * 拼团
 */
class Pintuan
{
    use Instance;
    
    /**
     * 检测商品是否参与拼团
     *
     *
     * @param int $goods_id 商品ID
     * 
     * @return array
     */
    public static function check(array $params)
    {
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
        
        if(!isset($params["goods_id"])||!$params["goods_id"]||intval($params["goods_id"])<=0){
            $ret["msg"]="商品参数错误";
            $ret["info"]="商品参数错误";
            return $ret;
        }
        
        try {
            
            $map=[];
            $map['goods_id']=$params['goods_id'];
            $map['start_time'] =  array(['=',0],['<=',time()],'or');
            $map['end_time'] =  array(['=',0],['>=',time()],'or');
           
            $model=new \app\api\model\promotions\Pintuan();
            
            $item = $model ->where($map) ->field("tuan_price,status")->find();
//             exit($model->getLastSql());

            if ($item) {
                if($item["status"]==State::STATE_NORMAL){
                    $ret["code"]=CODE::CODE_SUCCESS;
                    $ret["data"]["tuan_price"]=$item["tuan_price"];
                    return $ret;
                }
                else{
                    $ret["msg"]="此商品拼团已取消";
                    $ret["info"]="此商品拼团已取消";
                    return $ret;
                }
            }else{
                $ret["msg"]="此商品不参与拼团";
                $ret["info"]="此商品不参与拼团";
                return $ret;
            }
            
        } catch (ResponseException $e) {
            $ret["msg"]="商品参数错误";
            $ret["info"]="商品参数错误";
            return $ret;
        }
        return $ret;
    }
    
    /**
     * 创建拼团
     *
     *
     * @param int $goods_id 商品ID 【必填】
     * @param float $tuan_price 拼团价格 【必填】
     * @param string $start_time 例如：2017-12-05 拼团开始时间 【可选】
     * @param string $end_time 例如：2017-12-06 拼团结束时间 【可选】
     * @return array
     */
    public static function create(array $params)
    {
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["goods_id"])||!$params["goods_id"]||intval($params["goods_id"])<=0){
            $ret["msg"]="商品参数错误";
            $ret["info"]="商品参数错误";
            return $ret;
        }
        if(!isset($params["tuan_price"])||!$params["tuan_price"]||intval($params["tuan_price"])<=0){
            $ret["msg"]="拼团价格参数错误";
            $ret["info"]="拼团价格参数错误";
            return $ret;
        }
        if(!isset($params["user_id"])||!$params["user_id"]||intval($params["user_id"])<=0){
            $ret["msg"]="创建者参数错误";
            $ret["info"]="创建者参数错误";
            return $ret;
        }

        try {
            
            //查询这个商品存在，不存在不能创建
            $map=[];
            $map['goods_id']=$params['goods_id'];
            $model=new \app\api\model\goods\Goods();
            $goods_item = $model ->where($map)->find();
           
            //             exit($model->getLastSql());
            if (!$goods_item) {
                $ret["msg"]="此商品不存在";
                $ret["info"]="此商品不存在";
                return $ret;
            }
            
            //查询这个商品是否已参与拼团，如果已存在，则不能再创建
            $map=[];
            $map['goods_id']=$params['goods_id'];
            $model=new \app\api\model\promotions\Pintuan();
            $item = $model ->where($map)->find();
            //             exit($model->getLastSql());
            if ($item) {
                $ret["msg"]="此商品已参与拼团";
                $ret["info"]="此商品已参与拼团";
                return $ret;
            }
            
            $data=[
                "goods_id"=>$params["goods_id"],
                "shop_id"=>$goods_item["shop_id"],
                "tuan_price"=>$params["tuan_price"],
                "start_time"=>isset($params["start_time"])&&$params["start_time"]?strtotime($params["start_time"]." 00:00:00"):0,
                "end_time"=>isset($params["end_time"])&&$params["end_time"]?strtotime($params["end_time"]." 23:59:59"):0,
                "status"=>State::STATE_NORMAL,
                "creator_user_id"=>$params["user_id"],
            ];
            
            $result=$model->save($data);
            if($result===false){
                $ret["msg"]="创建失败";
                $ret["info"]="创建失败";
                return $ret;
            }else{
                $ret["code"]=Code::CODE_SUCCESS;
                $ret["msg"]="创建成功";
                $ret["info"]="创建成功";
                return $ret;
            }
    
        } catch (ResponseException $e) {
            $ret["msg"]="创建失败";
            $ret["info"]="创建失败";
            return $ret;
        }
        return $ret;
    }

    /**
     * 更新拼团
     *
     * @param int $pintuan_id 拼团ID 【必填】
     * @param float $tuan_price 拼团价格 【可选】
     * @param string $start_time 例如：2017-12-05 拼团开始时间 【可选】
     * @param string $end_time 例如：2017-12-06 拼团结束时间 【可选】
     * 
     * @return array
     */
    public static function update(array $params)
    {
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["pintuan_id"])||!$params["pintuan_id"]||intval($params["pintuan_id"])<=0){
            $ret["msg"]="拼团ID参数错误";
            $ret["info"]="拼团ID参数错误";
            return $ret;
        }
        
        try {

            //查询这个拼团是否 已存在
            $map=[];
            $map['pintuan_id']=$params['pintuan_id'];
            $model=new \app\api\model\promotions\Pintuan();
            $item = $model ->where($map)->find();
            //             exit($model->getLastSql());
            if (!$item) {
                $ret["msg"]="拼团不存在";
                $ret["info"]="拼团不存在";
                return $ret;
            }
    
            $data=[];
            if(isset($params["tuan_price"])&&$params["tuan_price"]){
                $data["tuan_price"]=floatval($params["tuan_price"]);
            }
            if(isset($params["start_time"])&&$params["start_time"]){
                $data["start_time"]=strtotime($params["start_time"]." 00:00:00");
            }
            if(isset($params["end_time"])&&$params["end_time"]){
                $data["end_time"]=strtotime($params["end_time"]." 23:59:59");
            }
//             exit(print_r($data));
            
            $result=$model->where(["pintuan_id"=>$params["pintuan_id"]])->update($data);
            if($result===false){
                $ret["msg"]="更新失败";
                $ret["info"]="更新失败";
                return $ret;
            }
            
            $ret["code"]=Code::CODE_SUCCESS;
            $ret["msg"]="更新成功";
            $ret["info"]="更新成功";
            return $ret;
            
        } catch (ResponseException $e) {
            $ret["msg"]="更新失败";
            $ret["info"]="更新失败";
            return $ret;
        }
        return $ret;
    }
    
    /**
     * 禁用拼团
     *
     * @param int $pintuan_id 拼团ID 【必填】
     *
     * @return array
     */
    public static function disable(array $params)
    {
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["pintuan_id"])||!$params["pintuan_id"]||intval($params["pintuan_id"])<=0){
            $ret["msg"]="拼团ID参数错误";
            $ret["info"]="拼团ID参数错误";
            return $ret;
        }
    
        try {
    
            //查询这个拼团是否 已存在
            $map=[];
            $map['pintuan_id']=$params['pintuan_id'];
            $model=new \app\api\model\promotions\Pintuan();
            $item = $model ->where($map)->find();
            //             exit($model->getLastSql());
            if (!$item) {
                $ret["msg"]="拼团不存在";
                $ret["info"]="拼团不存在";
                return $ret;
            }

            $result=$model->where(["pintuan_id"=>$params["pintuan_id"]])->update(["status"=>State::STATE_DISABLED]);
            if($result===false){
                $ret["msg"]="禁用失败";
                $ret["info"]="禁用失败";
                return $ret;
            }
            
            $ret["code"]=Code::CODE_SUCCESS;
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
     * 启用拼团
     *
     * @param int $pintuan_id 拼团ID 【必填】
     *
     * @return array
     */
    public static function enable(array $params)
    {
        $ret   = [
            'code'  => Code::CODE_OTHER_FAIL,
            'msg'   => '',
            'info'  =>'',
            'data'  => [],
        ];
    
        if(!isset($params["pintuan_id"])||!$params["pintuan_id"]||intval($params["pintuan_id"])<=0){
            $ret["msg"]="拼团ID参数错误";
            $ret["info"]="拼团ID参数错误";
            return $ret;
        }
    
        try {
    
            //查询这个拼团是否 已存在
            $map=[];
            $map['pintuan_id']=$params['pintuan_id'];
            $model=new \app\api\model\promotions\Pintuan();
            $item = $model ->where($map)->find();
            //             exit($model->getLastSql());
            if (!$item) {
                $ret["msg"]="拼团不存在";
                $ret["info"]="拼团不存在";
                return $ret;
            }
    
            $result=$model->where(["pintuan_id"=>$params["pintuan_id"]])->update(["status"=>State::STATE_NORMAL]);
            if($result===false){
                $ret["msg"]="启用失败";
                $ret["info"]="启用失败";
                return $ret;
            }
    
            $ret["code"]=Code::CODE_SUCCESS;
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