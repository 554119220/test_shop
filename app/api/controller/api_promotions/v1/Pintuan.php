<?php

/**
 * Created by Zend Studio.
 * User: qinzichao
 * Date: 2017/12/4 
 * Time: 17:56
 */

namespace app\api\controller\promotions\v1;

use mercury\constants\Code;
use mercury\ResponseException;

use app\api\service\promotions\v1\Pintuan as Service;

/**
 * Class Pintuan
 * @package app\api\controller\promotions\v1
 *
 * Api 拼团
 */
class Pintuan extends Init
{
  
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 检测商品是否参与拼团
     * 
     * @param int $goods_id 商品ID  [必填]
     * 
     * @return array
     * 
     *
     ===============返回正常示例：===============
    {
      "data": 
      {
          "tuan_price": "98.00"
      },
      "msg": "请求成功",
      "info": "success",
      "code": 20000
    }
    ===============返回错误示例：===============
    {
      "code": 20040,
      "msg": "无内容返回",
      "info": "no content"
    }
     
     */
    public function check()
    {
        try {
             $result=Service::check([
                 "goods_id"=>$this->data["goods_id"],
             ]);
             
             if($result["code"]!=Code::CODE_SUCCESS){
                 throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
             }
             $data=[];
             $data["tuan_price"]=$result["data"]["tuan_price"];
             return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
        
    }
    
     /**
     * 创建拼团
     * 
     * @param int $goods_id 商品ID 【必填】
     * @param float $tuan_price 拼团价格 【必填】
     * @param string $start_time 例如：2017-12-05 拼团开始时间 【可选】
     * @param string $end_time 例如：2017-12-06 拼团结束时间 【可选】
     * @return array
     * 
     *
     ===============返回正常示例：===============
     {
      "msg": "请求成功",
      "info": "success",
      "code": 20000
    }
    
     ===============返回错误示例：===============
    {
      "code": 60030,
      "msg": "此商品已参与拼团",
      "info": "此商品已参与拼团"
    }
     
     */
    public function create()
    {
        try {
            $result=Service::create(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
        
    }
    
    /**
     * 更新拼团
     *
     * @param int $pintuan_id 拼团ID  [必填]
     * 
     * @return array
     *
     *
     ===============返回正常示例：===============
     {
     "msg": "请求成功",
     "info": "success",
     "code": 20000
     }
     ===============返回错误示例：===============
     {
     "code": 60030,
     "msg": "此拼团不存在",
     "info": "此拼团不存在"
     }
      
     */
    public function update()
    {
        try {
            $result=Service::update(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    
    }

    /**
     * 禁用拼团
     *
     * @param int $pintuan_id 拼团ID  [必填]
     * 
     * @return array
     *
     *
     ===============返回正常示例：===============
     {
     "msg": "请求成功",
     "info": "success",
     "code": 20000
     }
     ===============返回错误示例：===============
    {
      "code": 60030,
      "msg": "禁用失败",
      "info": "禁用失败"
    }
    
     */
    public function disable()
    {
        try {
            $result=Service::disable(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    
    }
    
    
    /**
     * 启用拼团
     *
     * @param int $pintuan_id 拼团ID  [必填]
     *
     * @return array
     *
     *
     ===============返回正常示例：===============
     {
     "msg": "请求成功",
     "info": "success",
     "code": 20000
     }
     ===============返回错误示例：===============
     {
     "code": 60030,
     "msg": "禁用失败",
     "info": "禁用失败"
     }
    
     */
    public function enable()
    {
        try {
            $result=Service::enable(array_merge($this->data,["user_id"=>$this->user["user_id"]]));
             
            if($result["code"]!=Code::CODE_SUCCESS){
                throw new ResponseException(Code::CODE_OTHER_FAIL,$result["msg"],$result["info"]);
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    
    }
    
  
}