<?php
namespace app\api\controller\goods\v1;

use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\GoodsParams as Model;

/**
 * @title 商品参数
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class GoodsParams
{
	/**
     * @title 商品参数详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |goods_id         |int    |true   |0  |---    |商品id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |group_name 	|string|---    |商品参数组名|
     * |group_value |string|---    |商品参数组值|
     * @response_example 响应示例
     * `{
		    "data": [
		        {
		            "group_name": "硬盘",
		            "group_value": "320G"
		        },
		        {
		            "group_name": "操作系统",
		            "group_value": "win7"
		        },
		        {
		            "group_name": "处理器",
		            "group_value": "i7"
		        }
		    ],
		    "msg": "请求成功",
		    "info": "success",
		    "code": 20000
		}`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
	function detail(){
		try {
			# get param
			$goods_id = intval(request()->data['goods_id'] ?? 0);
			if ( State::STATE_NORMAL > $goods_id ) {
				throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
			}
			# get data
			$list = Fun::dataAll(Fun::mApi('goods', 'GoodsParams'), [
				'where' => [ 'goods_id' => $goods_id ],
				'field' => 'group_name,group_value',
			]);
			if ( empty($list) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# ...
			return $list;
		} catch (ResponseException $e) {
			#...
			return $e->getData();
		}
	}




















}