<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\ShopGoodsCategory as Model;
/**
 * @title 店铺分类
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */
class ShopGoodsCategory
{
    /**
     * @title 列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int    |true   |0  |---    |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function index()
    {
        try {
        	# get param
        	$shop_id = intval(request()->user['user_shop_id'] ?? 0);
        	if ( State::STATE_NORMAL > $shop_id ) {
        		throw new ResponseException( Code::CODE_NO_CONTENT );
        	}
        	# get data
        	$param['where']        = [ 'shop_id' => $shop_id ];
        	$param['order']        = 'goods_category_sort desc,goods_category_id asc';
        	$category = Fun::dataAll(Fun::mApi('goods','ShopGoodsCategory'), $param);
        	# no data
        	if ( empty($category) ) {
        		throw new ResponseException( Code::CODE_NO_CONTENT );
        	}
        	# have data
        	$category = Fun::array_tree($category, [
        		'begin'   => 0,
        		'id'      => 'goods_category_id',
        		'pid'     => 'goods_category_sid',
        		'child'   => 'child',
        		'level'   => 2,
        	]);
        	# ...
        	return $category;
        } catch (ResponseException $e) {
        	return $e->getData();
        }
    }

    /**
     * @title 列表2
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int    |true   |0  |---    |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function index2()
    {
        try {
            # get param
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # get data
            $param['where']        = [ 'shop_id' => $shop_id, 'goods_category_state' => State::STATE_NORMAL ];
            $param['order']        = 'goods_category_sort desc,goods_category_id asc';
            $category = Fun::dataAll(Fun::mApi('goods','ShopGoodsCategory'), $param);
            # no data
            if ( empty($category) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # have data
            $category = Fun::array_tree($category, [
                'begin'   => 0,
                'id'      => 'goods_category_id',
                'pid'     => 'goods_category_sid',
                'child'   => 'child',
                'level'   => 2,
            ]);
            // dump($category);exit;
            # ...
            return $category;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 列表3
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |shop_id         |int    |true   |0  |---    |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function index3()
    {
        try {
            # get param
            $data = request()->data;
            $shop_id = intval($data['shop_id'] ?? 0);
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # get data
            $param['where']        = [ 'shop_id' => $shop_id, 'goods_category_state' => State::STATE_NORMAL ];
            $param['order']        = 'goods_category_sort desc,goods_category_id asc';
            $category = Fun::dataAll(Fun::mApi('goods','ShopGoodsCategory'), $param);
            # no data
            if ( empty($category) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # have data
            $category = Fun::array_tree($category, [
                'begin'   => 0,
                'id'      => 'goods_category_id',
                'pid'     => 'goods_category_sid',
                'child'   => 'child',
                'level'   => 2,
            ]);
            // dump($category);exit;
            # ...
            return $category;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 创建
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int    |true   |0  |---    |用户id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function create(){
        try {
            # get param
            $user_id = intval(request()->user['user_id'] ?? 0);
            $shop_id = intval(request()->user['user_shop_id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # 超出限制
            if ( Fun::mApi('goods','ShopGoodsCategory')->where([ 'shop_id' => $shop_id ])->count() >= State::STATE_SHOP_CATEGORY_MAX ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '最多添加' . State::STATE_SHOP_CATEGORY_MAX . '个分类' );
            }
            # insert
            $goods_category_sid = intval(request()->data['goods_category_sid'] ?? 0);
            if ( $goods_category_sid > 0 ) {
                $one = Fun::dataDetail(Fun::mApi('goods','ShopGoodsCategory'),$goods_category_sid);
                # 不存在或者不是一级类目
                if ( empty($one) || $one['goods_category_sid'] > 0 ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '父级分类不存在' );
                }
            } else {
                $goods_category_sid = 0;
            }
            $insertData = request()->data;
            $insertData['shop_id'] = $shop_id;
            $insertData['seller_user_id'] = $user_id;
            if ( false == Fun::mApi('goods','ShopGoodsCategory')->allowField(true)->isUpdate(false)->save($insertData) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 更新
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int    |true   |0  |---    |用户id|
     * |category_id     |int    |true   |0  |---    |店铺分类id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function update(){
        try {
            $updateData = request()->data;
            # get param
            $user_id        = intval(request()->user['user_id'] ?? 0);
            $shop_id        = intval(request()->user['user_shop_id'] ?? 0);
            $category_id    = intval(request()->data['category_id'] ?? 0);
            if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # have data
            $param['where']['shop_id']             = $shop_id;
            $param['where']['goods_category_id']   = $category_id;
            $info = Fun::dataDetail(Fun::mApi('goods','ShopGoodsCategory'),$param);
            if ( empty($info) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL , '分类不存在');
            }
            # sid
            if ( $info['goods_category_sid'] == 0 ) {
                $updateData['goods_category_sid'] = 0;
            }
            if ( $info['goods_category_sid'] > 0 ) {
                $sid = intval(request()->data['goods_category_sid']);
                $one = Fun::dataDetail(Fun::mApi('goods','ShopGoodsCategory'), $sid);
                if ( empty($one) || $one['goods_category_sid'] > 0 ) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '父级分类不存在' );
                }
            }
            # update
            $updateData['shop_id']          = $shop_id;
            $updateData['seller_user_id']   = $user_id;
            $map['goods_category_id']       = $category_id;
            // dump($updateData);exit;
            if ( false === Fun::mApi('goods','ShopGoodsCategory')->allowField(true)->save($updateData, $map) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '修改失败' );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 删除
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int    |true   |0  |---    |用户id|
     * |category_id     |int    |true   |0  |---    |店铺分类id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function delete(){
        try {
            # get param
            $shop_id    = intval(request()->user['user_shop_id']);
            $id         = intval(request()->data['category_id']);
            if ( State::STATE_NORMAL > $shop_id || State::STATE_NORMAL > $id) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # have data
            $param['where']['shop_id']             = $shop_id;
            $param['where']['goods_category_id']   = $id;
            $info = Fun::dataDetail(Fun::mApi('goods','ShopGoodsCategory'),$param);
            if ( empty($info) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # delete data
            $delMap = [ 'goods_category_sid' => $id ];
            if ( false == Fun::delDb('shop_goods_category')->where(['goods_category_id' => $id])->delete() || false === Fun::delDb('shop_goods_category')->where($delMap)->delete() ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * @title 详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |user_id         |int    |true   |0  |---    |用户id|
     * |category_id     |int    |true   |0  |---    |店铺分类id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
    public function detail(){
        try {
            # get param
            $shop_id    = intval(request()->user['user_shop_id']);
            $id         = intval(request()->data['category_id']);

            if ( State::STATE_NORMAL > $shop_id || State::STATE_NORMAL > $id) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # get data
            $map['where'] = [
                'shop_id'           => $shop_id,
                'goods_category_id' => $id,
            ];
            $category = Fun::dataDetail(Fun::mApi('goods','ShopGoodsCategory'),$map);
            if ( empty($category) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            // dump($category);exit;
            # ...
            return $category;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }
}