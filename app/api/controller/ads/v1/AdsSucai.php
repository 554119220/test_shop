<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/03/19
 * Time: 13:47
 */

namespace app\api\controller\ads\v1;

use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
use app\api\model\ads\AdsSucai as Ad;
use app\api\model\ads\AdsSucaiAudit;

class AdsSucai
{
    /**
     * @title 素材列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |ads_sucai_user_id|int|true|12|-|用户id|
     * |ads_sucai_state|int|false|1|-|状态，0审核中，1通过，2拒绝，3...|
     * |ads_sucai_name|string|false|哈哈|-|根据素材标题搜索素材|
     * |wh|string|false|320x320|-|素材宽高|
     * |ads_sucai_width_e|int|false|320|-|素材图片宽度结束|
     * |ads_sucai_height_s|int|false|150|-|素材图片高度开始|
     * |ads_sucai_height_e|int|false|150|-|素材图片高度结束|
     * |pagesize|int|false|15|-|每页记录数，默认15|
     * |p|int|true|1|-|页码|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |ads_sucai_id|int|12|id|
     * |ads_sucai_create_time|string|2015-12-16 02:36:23|创建时间|
     * |ads_sucai_update_time|string|2015-12-16 02:36:23|更新时间|
     * |ads_sucai_name|string|哈哈|素材标题|
     * |ads_sucai_images|string|asdasd|素材图片|
     * |ads_sucai_width|int|100|素材图片宽度|
     * |ads_sucai_height|int|150|素材图片高度|
     * |ads_sucai_user_id|int|150|用户id|
     * |ads_sucai_state|int|150|状态，0审核中，1通过，2拒绝，3...|
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
    function index(){
        try{
            $param      = request()->data;
            $map = [];
            # 用户
            if ( isset($param['ads_sucai_user_id']) && $param['ads_sucai_user_id'] > 0 ) {
                $map['ads_sucai_user_id']     = $param['ads_sucai_user_id'];
            }
            # 状态
            if ( isset($param['ads_sucai_state']) ) {
                $map['ads_sucai_state']     = $param['ads_sucai_state'];
            }
            # 标题
            if ( isset($param['ads_sucai_name']) && $param['ads_sucai_name'] ) {
                $map['ads_sucai_name']  = [ 'like', '%' . trim(urldecode( $param['ads_sucai_name'] )) . '%' ];
            }
            # 宽高
            if( isset($param['wh']) && $param['wh'] ){
                $wh = explode("x", $param['wh']);
                $map['ads_sucai_width'] 	= $wh[0] ?? 0;
                $map['ads_sucai_height'] = $wh[1] ?? 0;
            }
//            if( isset($param['ads_sucai_width_e']) && !empty($param['ads_sucai_width_e']) ){
//                $map['ads_sucai_width'] = [ 'elt', (float) $param['ads_sucai_width_e'] ];
//            }
//
//            if( isset($param['ads_sucai_width_e']) && !empty($param['ads_sucai_width_e']) && isset($param['ads_sucai_width_s']) && !empty($param['ads_sucai_width_s']) ){
//                #区间查询
//                $map['ads_sucai_width'] = [['egt', (float) $param['ads_sucai_width_s']],['elt', (float) $param['ads_sucai_width_e']],'AND'];
//            }
//
//            if( isset($param['ads_sucai_height_s']) && !empty($param['ads_sucai_height_s']) ){
//                $map['ads_sucai_height'] = [ 'egt', (float) $param['ads_sucai_height_s'] ];
//            }
//            if( isset($param['ads_sucai_height_e']) && !empty($param['ads_sucai_height_e']) ){
//                $map['ads_sucai_height'] = [ 'elt', (float) $param['ads_sucai_height_e'] ];
//            }
//
//            if( isset($param['ads_sucai_height_s']) && !empty($param['ads_sucai_height_s']) && isset($param['ads_sucai_height_e']) && !empty($param['ads_sucai_height_e']) ){
//                #区间查询
//                $map['ads_sucai_height'] = [['egt', (float) $param['ads_sucai_height_s']],['elt', (float) $param['ads_sucai_height_e']],'AND'];
//            }
            $map['status'] = 1;
            # 分页设置
            $pagesize   = intval($param['pagesize'] ?? 15);
            $page       = intval($param['page'] ?? 1);
            # get data
            $list = Fun::pageList(Fun::mApi('ads', 'AdsSucai'),[
                'where'         => $map,
                'order'         => 'ads_sucai_create_time desc',
                'field'         => '*',
                'page'          => $page,
                'relation'      => 'ads_sucai_audit',
                'pagesize'      => $pagesize,
                'cache'         => false,
                'cache_time'    => \mercury\constants\Common::TIME_FIVE_MINUTE,
            ]);
            if ( empty($list) ) {
                throw new ResponseException(Code::CODE_NO_CONTENT);
            }
            # ...
            return $list;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 创建素材
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |ads_sucai_user_id|int|true|12|-|用户id|
     * |ads_sucai_name|string|false|哈哈|-|素材标题|
     * |ads_sucai_images|string|true|asdasd|-|图片地址|
     * |ads_sucai_width|int|true|12|-|宽度|
     * |ads_sucai_height|int|true|12|-|高度|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
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
    function create(){
        try{
            $param      = request()->data;
            if(!isset($param['ads_sucai_user_id']) || empty(intval($param['ads_sucai_user_id']))){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '用户必须登录才能操作！' );
            }
            $model = new Ad();
            $allowField[] = 'ads_sucai_name';
            $allowField[] = 'ads_sucai_images';
            $allowField[] = 'ads_sucai_width';
            $allowField[] = 'ads_sucai_height';
            $allowField[] = 'ads_sucai_user_id';
            $allowField[] = 'ads_sucai_state';
            if ( false == $model->allowField($allowField)->save($param) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '素材信息保存失败，请重试' );
            }
            return Code::CODE_SUCCESS;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 创建素材
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |ads_sucai_user_id|int|true|12|-|用户id|
     * |ads_sucai_name|string|false|哈哈|-|素材标题|
     * |ads_sucai_images|string|true|asdasd|-|图片地址|
     * |ads_sucai_width|int|true|12|-|宽度|
     * |ads_sucai_height|int|true|12|-|高度|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
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
    function update(){
        try{
            $param      = request()->data;
            $user_id    = intval(request()->user['user_id']);
            if(!isset($param['ads_sucai_user_id']) || empty(intval($param['ads_sucai_user_id']))){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '用户必须登录才能操作！' );
            }
            $ads_sucai_id = $param['ads_sucai_id'] ?? 0;
            if(!$ads_sucai_id){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '请选择要操作的素材！' );
            }
            $model = new Ad();
            $allowField[] = 'ads_sucai_name';
            $allowField[] = 'ads_sucai_images';
            $allowField[] = 'ads_sucai_width';
            $allowField[] = 'ads_sucai_height';
            $allowField[] = 'ads_sucai_user_id';
            $allowField[] = 'ads_sucai_state';
            #修改后将状态赋值为待审核状态
            $param['ads_sucai_state'] = 0;
            if ( false == $model->allowField($allowField)->save($param,[ 'ads_sucai_id' => $ads_sucai_id ,'ads_sucai_user_id'=>$user_id]) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '素材信息更新失败，请重试' );
            }
            return Code::CODE_SUCCESS;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 删除素材（软删除）
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |ads_sucai_user_id|int|true|12|-|用户id|
     * |ads_sucai_name|string|false|哈哈|-|素材标题|
     * |ads_sucai_images|string|true|asdasd|-|图片地址|
     * |ads_sucai_width|int|true|12|-|宽度|
     * |ads_sucai_height|int|true|12|-|高度|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
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
    function delete2(){
        try{
            $param      = request()->data;
            $user_id    = intval(request()->user['user_id']);
            if(!$user_id){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '用户必须登录才能操作！' );
            }
            $ads_sucai_id = (string) $param['ads_sucai_id'];
            if(!$ads_sucai_id){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '请选择要操作的素材！' );
            }
            $model = new Ad();
            if ( false == $model->update( [ 'ads_sucai_id' => [ 'in', $ads_sucai_id ], 'status'=>0 ])) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '素材信息删除失败，请重试！' );
            }
            return Code::CODE_SUCCESS;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 素材详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |ads_sucai_id|int|true|12|-|素材id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |ads_sucai_id|int|12|id|
     * |ads_sucai_create_time|string|2015-12-16 02:36:23|创建时间|
     * |ads_sucai_update_time|string|2015-12-16 02:36:23|更新时间|
     * |ads_sucai_name|string|哈哈|素材标题|
     * |ads_sucai_images|string|asdasd|素材图片|
     * |ads_sucai_width|int|100|素材图片高度|
     * |ads_sucai_height|int|150|素材图片宽度|
     * |ads_sucai_user_id|int|150|用户id|
     * |ads_sucai_state|int|150|状态，0审核中，1通过，2拒绝，3...|
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
    function details(){
        try{
            $param      = request()->data;
            # 素材id
            if ( !isset($param['ads_sucai_id']) || $param['ads_sucai_id'] <= 0 ) {
                throw new ResponseException(Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY);
            }

            $map['ads_sucai_id']     = $param['ads_sucai_id'];
            # get data
            $data = Fun::dataDetail(Fun::mApi('ads', 'AdsSucai'),[
                'where'         => $map,
                'field'         => '*',
                'relation'      => 'ads_sucai_audit',
                'cache'         => false,
                'cache_time'    => \mercury\constants\Common::TIME_FIVE_MINUTE,
            ]);
            if ( empty($data) ) {
                throw new ResponseException(Code::CODE_NO_CONTENT);
            }
            # ...
            return $data;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 删除素材
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |ads_sucai_id|int|true|12|-|素材id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
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
    function delete(){
        die;
        try{
            # get param
            $user_id    = intval(request()->user['user_id']);
            $ads_sucai_id   = (string) request()->data['ads_sucai_id'];
            if ( State::STATE_NORMAL > $user_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # map
            $map['ads_sucai_user_id']    = $user_id;
            $map['ads_sucai_id']          = [ 'in', $ads_sucai_id ];
            # delete
            if (!Ad::destroy($map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '您不能删除属于您个人的素材！' );
            }
            # ...
            return Code::CODE_SUCCESS;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 素材列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |ads_sucai_audit_id|int|true|12|-|素材id|
     * |ads_sucai_audit_work_id|int|true|1|-|雇员ID|
     * |ads_sucai_audit_state|int|true|1|-|状态,0未通过,1通过|
     * |ads_sucai_audit_content|string|true|审核通过|-|审核备注，审核不通过时必须填写|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
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
    function audit(){
        try{
            $param      = request()->data;
            #验证素材id
            if ( !isset($param['ads_sucai_id']) || $param['ads_sucai_id'] <= 0 ) {
                throw new ResponseException(Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY);
            }

            #验证雇员
            if(!isset($param['ads_sucai_audit_work_id']) || $param['ads_sucai_audit_work_id'] <= 0){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '雇员不能为空！' );
            }

            #验证审核状态
            if(!isset($param['ads_sucai_state']) || !in_array($param['ads_sucai_state'],[1,2])){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '审核状态必须选择！' );
            }

            #验证审核备注
            if($param['ads_sucai_state'] == 2){
                if(empty($param['reason'])){
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '审核备注必须填写！' );
                }
            }
            #更新用户素材状态
            $model = new Ad();
            $model->startTrans();
            if($param['ads_sucai_state'] == 1){
                $ads_sucai_state = 1;
            }else{
                $ads_sucai_state = 0;
            }

            $log['ads_sucai_id'] = $param['ads_sucai_id'];

            $log['ads_sucai_audit_work_id'] = $param['ads_sucai_audit_work_id'];

            $log['ads_sucai_audit_state'] = 1;
            if($param['ads_sucai_state'] == 2){
                $log['ads_sucai_audit_state'] = 0;
            }

            $log['ads_sucai_audit_content'] = $param['reason'];

            $ads_sucai['ads_sucai_state'] = $param['ads_sucai_state'];

            $AdsSucaiAudit = new AdsSucaiAudit();

            $AdsSucaiAudit->startTrans();

            if ( false == $model->allowField(true)->save($ads_sucai,['ads_sucai_id'=>$param['ads_sucai_id']]) || false == $AdsSucaiAudit->allowField(true)->save($log) ) {

                $model->rollback();

                $AdsSucaiAudit->rollback();

                throw new ResponseException( Code::CODE_OTHER_FAIL, '审核失败，请稍后重试。' );

            }

            $model->commit();
            $AdsSucaiAudit->commit();
            # ...
            return Code::CODE_SUCCESS;

        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}