<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\GoodsPackageTpl as Model;

/**
 * @title 包装模板
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */
class GoodsPackageTpl
{

    /**
     * @title 包装模板列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true   |0  |---    |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data": {},
            "msg": "请求成功",
            "info": "success",
            "code": 20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function index()
    {
        try {
            # get param
            $shop_id    = intval(request()->user['user_shop_id'] ?? 0);
            if (State::STATE_NORMAL > $shop_id) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # get data
            $param['where'] = [
                // 'state'     => State::STATE_NORMAL,
                'shop_id'   => $shop_id,
            ];
            $param['order'] = 'package_id desc';
            $list = Fun::dataAll(Fun::mApi('goods','GoodsPackageTpl'), $param);
            # no data
            if ( empty($list) ) {
                throw new ResponseException(Code::CODE_NO_CONTENT);
            }
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 新建模板
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int    |true   |0  |---    |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data": {},
            "msg": "请求成功",
            "info": "success",
            "code": 20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function create()
    {
        try {
            # get param
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            $user_id = intval(request()->user['user_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id || State::STATE_NORMAL > $user_id) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # 超过数量
            if ( Fun::mApi('goods','GoodsPackageTpl')->where([ 'shop_id' => $shop_id ])->count() >= State::GOODS_PACKAGE_TPL_MAX ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '模板最多创建' . State::GOODS_PACKAGE_TPL_MAX . '个' );
            }
            # create data
            $param = request()->data;
            $param['seller_user_id']    = $user_id;
            $param['shop_id']           = $shop_id;
            if ( false == Fun::mApi('goods','GoodsPackageTpl')->allowField(true)->save($param) ) {
                throw new ResponseException(Code::CODE_OTHER_FAIL);
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 更新模板
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true   |0  |---    |用户id|
     * |package_id  |int    |true   |0  |---    |模板id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data": {},
            "msg": "请求成功",
            "info": "success",
            "code": 20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function update()
    {
        try {
            # get param
            $package_id = intval(request()->data['package_id'] ?? 0);
            $shop_id    = intval(request()->user['user_shop_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id || State::STATE_NORMAL > $package_id) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # update data
            $param = request()->data;
            $map = [
                'shop_id'       => $shop_id,
                'package_id'    => $package_id,
            ];
            $field = [ 'package_name', 'package_intro' ];
            if ( false == Fun::mApi('goods','GoodsPackageTpl')->allowField($field)->save($param,$map) ) {
                throw new ResponseException(Code::CODE_OTHER_FAIL);
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 删除模板
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true   |0  |---    |用户id|
     * |package_id  |int    |true   |0  |---    |模板id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data": {},
            "msg": "请求成功",
            "info": "success",
            "code": 20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function delete()
    {
        try {
            # get param
            $package_id = intval(request()->data['package_id']);
            $shop_id    = intval(request()->user['user_shop_id']);
            # param error
            if (State::STATE_NORMAL > $package_id || State::STATE_NORMAL > $shop_id) {
                throw new ResponseException(Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY);
            }
            # 是否有商品在使用此模板
            $where = [
                'package_id'    => $package_id,
                'goods_state'   => [ 'in', array_keys(State::STATE_GOODS_USER_ARRAY) ],
            ];
            $goods = db('goods')->field('goods_id')->where($where)->find();
            if ( $goods ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '存在使用此模板的商品，不能删除' );
            }
            # delete data
            $map = [
                'package_id'=> $package_id,
                'shop_id'   => $shop_id,
            ];
            if (false == Fun::delDb('goods_package_tpl')->where($map)->delete()){
                throw new ResponseException(Code::CODE_OTHER_FAIL);
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    

    /**
     * @title 模板详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true   |0  |---    |用户id|
     * |package_id  |int    |true   |0  |---    |模板id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
            "data": {},
            "msg": "请求成功",
            "info": "success",
            "code": 20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function detail()
    {
        try {
            # get param
            $package_id = intval(request()->data['package_id'] ?? 0);
            $shop_id    = intval(request()->user['user_shop_id'] ?? 0);
            # param error
            if (State::STATE_NORMAL > $package_id)
                throw new ResponseException(Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY);
            # get detail
            $param['where'] = [
                'package_id'=> $package_id,
                // 'state'     => State::STATE_NORMAL,
                // 'shop_id'   => $shop_id,
            ];
            $detail = Fun::dataDetail(Fun::mApi('goods','GoodsPackageTpl'), $param);
            if ( empty($detail) ) {
                throw new ResponseException(Code::CODE_NO_CONTENT);
            }
            # ...
            return $detail;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}