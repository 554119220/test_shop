<?php
namespace app\api\controller\goods\v1;

use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;

/**
 * @title 参数组
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class GoodsParamsGroup
{
	/**
     * @title 参数组列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |category_id         |int    |true   |0  |---    |分类id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * ``
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
	function index(){
		try {
			# get param
			$category_id = intval(request()->data['category_id'] ?? 0);
			if ( State::STATE_NORMAL > $category_id ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# 一二级也需要加进来
			$three 	= Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), $category_id);
			$two 	= Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), $three['category_sid'] ?? 0);
			$one 	= Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), $two['category_sid'] ?? 0);
			# get data
			$map = [
				'category_id' => [ 'in', [ $three['category_id'] ?? 0, $two['category_id'] ?? 0, $one['category_id'] ?? 0 ] ],
				
			];
			$list = Fun::dataAll(Fun::mApi('goods', 'GoodsParamsGroup'),[
				'where' 		=> $map,
				'relation' 		=> 'goods_params_group_value',
			]);
			if ( empty($list) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# ...
			return $list;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}




















}