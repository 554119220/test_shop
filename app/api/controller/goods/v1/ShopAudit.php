<?php
namespace app\api\controller\goods\v1;
use app\common\traits\F as Fun;
use mercury\ResponseException;
use mercury\constants\Code;
use app\api\model\goods\ShopAudit as Model;

/**
 * Class ShopAudit
 * @package app\api\controller\goods\v1
 *
 * @title 店铺操作日志
 */

class ShopAudit
{
    /**
     * @title 店铺日志列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_id|int|true|12|-|店铺id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |content|string|12|说明|
     * |is_pass|int|asdasd|操作状态（已删除，已冻结，已关闭，强制关闭，正常）|
     * |end_time|int|1234536|关闭截至时间|
     * |day|int|10|关闭时间（天）|
     * |start_time|int|1234536|关闭开始时间|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function index(){
        try {
            $data = request()->data;
            $param['where'] = [ 'shop_id' => intval($data['shop_id'])];
            $param['relation'] = 'employee';
            $param['cache'] = false;
            $param['order'] = 'id desc';
            $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
            $param['field'] = 'content,admin_id,shop_id,create_time,is_pass,end_time,start_time,day';
            return Fun::dataAll('\\app\\api\\model\\goods\\ShopAudit', $param);
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 店铺日志列表（最新一条）
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_id|int|true|12|-|店铺id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |content|string|12|说明|
     * |is_pass|int|asdasd|操作状态（已删除，已冻结，已关闭，强制关闭，正常）|
     * |end_time|int|1234536|关闭截至时间|
     * |day|int|10|关闭时间（天）|
     * |start_time|int|1234536|关闭开始时间|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function index2(){
        try {
            $data = request()->data;
            $param['where'] = [ 'shop_id' => intval($data['shop_id'])];
            //$param['relation'] = 'employee';
            $param['cache'] = false;
            $param['order'] = 'id desc';
            $param['limit'] = 1;
            $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
            $param['field'] = 'content,shop_id,is_pass,end_time,start_time,day';
            return Fun::dataList('\\app\\api\\model\\goods\\ShopAudit', $param);
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 创建日志
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_id|int|true|12|-|店铺id|
     * |day|int|true|12|-|需要关闭的天数|
     * |value|int|true|12|-|操作状态（已删除，已冻结，已关闭，强制关闭，正常）|
     * |content|string|true|12|-|关闭说明|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |content|string|12|说明|
     * |is_pass|int|asdasd|操作状态（已删除，已冻结，已关闭，强制关闭，正常）|
     * |end_time|int|1234536|关闭截至时间|
     * |day|int|10|关闭时间（天）|
     * |start_time|int|1234536|关闭开始时间|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    public function create(){
        try {
            $data = request()->data;
            $model = new Model;
            $model->startTrans();
            if(!isset($data['shop_id']) || empty($data['shop_id'])){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '未选择要操作的店铺！' );
            }
            $date = date('Y-m-d',strtotime('+'.$data['day'].' day',time()));
            $shop_update['shop_end_time'] = 0;
            if($data['value'] != 1){

                if(!isset($data['day']) || $data['day'] == ''){
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '未选择关闭店铺的天数！' );
                }

                $shop_update['shop_end_time'] = strtotime($date);
            }
                if(!isset($data['content']) || $data['content'] == ''){
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '操作说明不能为空！' );
                }

            if(!empty($data['shop_id'])){
                foreach ($data['shop_id'] as $key=>$value){
                    $shop_update['shop_state'] =$data['value'];
                    $shop_update['shop_update_time'] =time();

                    if(!\app\api\model\goods\Shop::where('shop_id',$value)->update($shop_update)){
                        $model->rollback();
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '店铺 状态更新失败！' );
                    }

                    if($data['value'] != 1){
                        $data_shop_audit[$key]['end_time'] = strtotime($date)+( 4 * 3600 );
                        $data_shop_audit[$key]['start_time'] = time();
                        $data_shop_audit[$key]['day'] = $data['day'];
                    }
                    $data_shop_audit[$key]['create_ip'] = request()->ip();
                    $data_shop_audit[$key]['is_pass'] = $data['value'];
                    $data_shop_audit[$key]['content'] = $data['content'];
                    $data_shop_audit[$key]['shop_id'] = $value;
                    $data_shop_audit[$key]['admin_id'] = $data['admin_id'];
                }
                if(false == $model->allowField(true)->saveAll($data_shop_audit, false) ){
                    $model->rollback();
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '记录写入失败！' );
                }
                //g更新店铺状态
            }



            $model->commit();
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}