<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\GoodsExpressTpl as Model;
use mercury\factory\Factory;

/**
 * @title 运费模板
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */
class GoodsExpressTpl
{

    /**
     * @title 运费模板列表
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
            $user_id = intval(request()->user['user_id'] ?? 0);
            if (State::STATE_NORMAL > $user_id) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # get data
            $list = Fun::dataAll(Fun::mApi('goods','GoodsExpressTpl'), [
                'where' => [ 'seller_user_id' => $user_id ],
                'order' => 'express_id asc',
            ]);
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
     * @title 新建运费模板
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
    public function create()
    {
        try {
            // dump(request()->user);exit;
            # get param
            $param = request()->data;
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            $user_id = intval(request()->user['user_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY, '请先开店' );
            }
            # 超过数量
            if ( Fun::mApi('goods','GoodsExpressTpl')->where([ 'shop_id' => $shop_id ])->count() >= State::GOODS_EXPRESS_TPL_MAX ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '模板最多创建' . State::GOODS_EXPRESS_TPL_MAX . '个' );
            }
            # 检测 不包邮
            if ($param['express_is_free'] == State::GOODS_EXPRESS_NO_FREE) {
                $vApi = Fun::vApi('goods','GoodsExpressTpl');
                if ( false == $vApi->scene('create2')->check($param) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL , $vApi->getError() );
                }
            }
            # 检测content
            if ( isset($param['express_content']) ) {
                $vApi = Fun::vApi('goods','GoodsExpressTpl');
                if ( false == $vApi->scene('check_content')->check($param) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL , $vApi->getError() );
                }
            } else {
                $param['express_content'] = [];
            }
            # create data
            $param['seller_user_id']    = $user_id;
            $param['shop_id']           = $shop_id;
            $param['express_city_id']   = [];
            // dump($param);exit();
            if ( false == Fun::mApi('goods','GoodsExpressTpl')->allowField(true)->save($param) ) {
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
     * @title 更新运费模板
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true   |0  |---    |用户id|
     * |express_id  |int    |true   |0  |---    |模板id|
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
            $param = request()->data;
            $express_id = intval(request()->data['express_id'] ?? 0);
            $shop_id    = intval(request()->user['user_shop_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id || State::STATE_NORMAL > $express_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # 检测 不包邮
            if ($param['express_is_free'] == State::GOODS_EXPRESS_NO_FREE) {
                $vApi = Fun::vApi('goods','GoodsExpressTpl');
                if ( false == $vApi->scene('create2')->check($param) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL , $vApi->getError() );
                }
            }
            # 检测content
            if ( isset($param['express_content']) ) {
                $vApi = Fun::vApi('goods','GoodsExpressTpl');
                if ( false == $vApi->scene('check_content')->check($param) ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL , $vApi->getError() );
                }
            } else {
                $param['express_content'] = [];
            }
            # update data
            $map = [
                'shop_id'       => $shop_id,
                'express_id'    => $express_id,
            ];
            $param['express_city_id']   = [];
            $field = [
                'express_name',
                'express_remark',
                'express_city_id',
                'express_is_free',
                'express_ship_time',
                'express_type',
                'express_first_price',
                'express_continue_price',
                'express_content',
                'express_ship_province_id',
                'express_ship_city_id',
            ];
            if ( false === Fun::mApi('goods','GoodsExpressTpl')->allowField($field)->save($param, $map) ) {
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
     * @title 删除运费模板
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true   |0  |---    |用户id|
     * |express_id  |int    |true   |0  |---    |模板id|
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
    public function delete ()
    {
        try {
            # get param
            $express_id = intval(request()->data['express_id']);
            $shop_id    = intval(request()->user['user_shop_id']);
            if (State::STATE_NORMAL > $express_id || State::STATE_NORMAL > $shop_id) {
                throw new ResponseException(Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY);
            }
            # 是否有商品在使用此模板
            $where = [
                'express_id'    => $express_id,
                'goods_state'   => [ 'in', array_keys(State::STATE_GOODS_USER_ARRAY) ],
            ];
            $goods = db('goods')->field('goods_id')->where($where)->find();
            if ( $goods ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '存在使用此模板的商品，不能删除' );
            }
            # delete data
            $map = [
                'express_id'    => $express_id,
                'shop_id'       => $shop_id,
            ];
            if (false == Fun::delDb('goods_express_tpl')->where($map)->delete()){
                throw new ResponseException(Code::CODE_OTHER_FAIL, '删除失败');
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 运费模板详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id     |int    |true   |0  |---    |用户id|
     * |express_id  |int    |true   |0  |---    |模板id|
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
            $express_id = intval(request()->data['express_id']);
            $shop_id    = intval(request()->user['user_shop_id']);
            if (State::STATE_NORMAL > $express_id)
                throw new ResponseException(Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY);
            # get detail
            $detail = Fun::dataDetail(Fun::mApi('goods','GoodsExpressTpl'), [
                'where' => [ 
                    'express_id'    => $express_id,
                    // 'shop_id'       => $shop_id,
                ],
            ]);
            if (empty($detail)) {
                throw new ResponseException(Code::CODE_NO_CONTENT);
            }
            
            // dump($area);exit;
            # ...
            return $detail;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 快递费用计算 按重量或者按件数
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |sku_id  |string    |true   |0  |---    |商品库存id，多个用逗号隔开|
     * |city_id |string    |true   |0  |---    |城市地址，多个用逗号隔开|
     * |num     |string    |true   |0  |---    |数量，多个用逗号隔开|
     * |weight  |string    |true   |0  |---    |数量，多个用逗号隔开|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |fees        |float    |---|快递运费|
     * |ems_fees    |float    |---|ems运费|
     * @response_example 响应示例
     * `{
            "data": {
                "fees": 0,
                "ems_fees": 0,
                "priceArr": {
                    "fees": [
                        0
                    ],
                    "ems_fees": [
                        0
                    ]
                }
            },
            "msg": "请求成功",
            "info": "success",
            "code": 20000
        }`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function courier_fees()
    {
        try {
            # get param
            $sku_id         = explode(',',(string)(request()->data['sku_id']));
            $city_id        = intval(request()->data['city_id']);
            $num            = explode(',',(string)(request()->data['num']));
            $weight         = explode(',',(string)(request()->data['weight']));
            if ( State::STATE_NORMAL > $sku_id || State::STATE_NORMAL > $city_id || State::STATE_NORMAL > $num || $weight <= 0 ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            if ( count($sku_id) != count($num) || count($num) != count($weight) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # 库存集合
            $sku        = Fun::dataAll(Fun::mApi('goods',"GoodsSku"), [
                'where'     => [ 'goods_sku_id' => [ 'in', $sku_id ] ],
                'relation'  => 'goods',
            ]);
            // dump($sku);
            if ( count($sku) != count(array_unique($sku_id)) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '商品错误' );
            }
            $sku = array_column($sku,null,'goods_sku_id');
            # 运费模板集合
            foreach ($sku as $key => $value) {
                $expressIds[$value['goods_sku_id']] = $value['goods']['express_id'] ?? 0;
            }
            $expressArr    = Fun::dataAll(Fun::mApi('goods','GoodsExpressTpl'), [
                'where' => [
                    'express_id' => [ 'in', $expressIds ],
                ],
            ]);
            // dump(count(($expressArr)));
            # 没有找到对应的express ids记录
            if ( empty($expressArr) || count(array_unique($expressIds)) != count($expressArr) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '无法计算运费1' );
            }
            $expressArr    = array_column($expressArr, null,'express_id');
            # 计算运费,计算多个计费方式
            $priceArr   = [];
            $fees       = State::GOODS_EXPRESS_WAYS_ARRAYS_NEW[State::GOODS_EXPRESS_WAYS_NORMAL];
            $ems_fees   = State::GOODS_EXPRESS_WAYS_ARRAYS_NEW[State::GOODS_EXPRESS_WAYS_EMS];
            # 循环计算运费
            foreach ($sku_id as $key => $id) {
                # 找到对应的模板
                $express = $expressArr[$sku[$id]['goods']['express_id']];
                # 按重量或者按件数
                $number = null;
                switch ($express['express_type']) {
                    case State::GOODS_EXPRESS_TYPE_NUMBER :
                        $number = $num[$key];
                        break;
                    case State::GOODS_EXPRESS_TYPE_WEIGHT :
                        $number = $weight[$key];
                        break;
                    default:
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '无法计算运费' );
                        break;
                }
                # 不包邮
                if ( $express['express_is_free'] == State::GOODS_EXPRESS_NO_FREE ) {
                    # 查找计费方式
                    $ways = [];
                    foreach (State::GOODS_EXPRESS_WAYS_ARRAYS as $ko => $vo) {
                        $waysKey = State::GOODS_EXPRESS_WAYS_ARRAYS_NEW[$ko];
                        # 是否存在特殊的计费方式
                        $tempArr    = $express['express_content'][$ko] ?? [];
                        foreach ($tempArr as $k => $v) {
                            if ( in_array($city_id,$v['express_city_id']) ) {
                                $ways[$waysKey] = [
                                    'express_first_price'       => $v['express_first_price'],
                                    'express_continue_price'    => $v['express_continue_price'],
                                ];
                            }
                        }
                        # 无特殊 使用默认计费方式
                        if ( false == isset($ways[$waysKey]) ){
                            $ways[$waysKey] = [
                                'express_first_price'       => $express['express_first_price'],
                                'express_continue_price'    => $express['express_continue_price'],
                            ];
                        }
                    }
                    # 循环计算两种方式
                    foreach ($ways as $ko => $vo) {
                        # 件数或重量不足1
                        if ($number >= 0 && $number < 1 ){
                            $priceArr[$ko][] = $vo['express_first_price'];
                        }
                        # 件数或重量超过1
                        if ( $number >= 1 ) {
                            $priceArr[$ko][] = round($vo['express_first_price'] + strval(($number - 1) * $vo['express_continue_price']),2);
                        }
                    }
                } else {
                    # 包邮
                    $priceArr[$fees][] = 0;
                    $priceArr[$ems_fees][] = 0;
                }
            }
            return [
                'fees'      => array_sum($priceArr[$fees]),
                'ems_fees'  => array_sum($priceArr[$ems_fees]),
                'priceArr'  => $priceArr,
            ];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }
}