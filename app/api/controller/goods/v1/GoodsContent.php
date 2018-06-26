<?php
namespace app\api\controller\goods\v1;

use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\GoodsContent as Model;

/**
 * @title 商品详情
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */

class GoodsContent
{

	/**
     * @title 商品详情
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |goods_id  |int    |true   |0  |---    |商品id|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
		    "data": {
		        "goods_content": "<strong style=\"margin: 0px; padding: 8px 0px 3px; display: inline-block;\">厂家服务</strong>"
		    },
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
			$detail = Fun::dataDetail(Fun::mApi('goods', 'GoodsContent'), [
				'where' => [ 'goods_id' => $goods_id ],
				'field' => 'goods_content',
				'cache' => true,
				'cache_time' => \mercury\constants\Common::TIME_MONENT,
			]);
			if ( empty($detail) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			$detail['goods_content']    = htmlspecialchars_decode($detail['goods_content']);
			# ...
			return $detail;
		} catch (ResponseException $e) {
			#...
			return $e->getData();
		}
	}




















}