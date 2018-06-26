<?php
namespace app\api\controller\ads\v1;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
/**
 * @title 简单的广告
 * @author Lzy
 * @date 2018-03-14 10:00:00
 */
class AdsSimple
{


	/**
     * @title 简单的广告列表
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |group_id         |int    |true   |0  |---    |广告组id，多个逗号隔开|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * |ads_simple_title 	|string|---    |标题|
     * |ads_simple_sub_title 	|string|---    |副标题|
     * |ads_simple_image |string|---    |图片|
     * |ads_simple_url |string|---    |链接|
     * @response_example 响应示例
     * `{
		    "data": [
		    	list:{
					{
			            "ads_simple_title": "商品1",
			            "ads_simple_sub_title": "商品1副标题",
			            "ads_simple_image": "商品1图片",
			            "ads_simple_url": "商品1链接",
			        },
			        {
			            "ads_simple_title": "商品2",
			            "ads_simple_sub_title": "商品2副标题",
			            "ads_simple_image": "商品2图片",
			            "ads_simple_url": "商品2链接",
			        },
		    	},
		        "group":{
			    	ads_simple_group_id:"组id",
					ads_simple_group_name:"组标题",
					ads_simple_group_sub_name:"组副标题",
					ads_simple_group_descript:"组描述",
			    },
		    ],
		    "msg": "请求成功",
		    "info": "success",
		    "code": 20000
		}`
     * @description 接口描述
     * > 无
     * @return array|mixed|string
     */
	function index()
	{
		try {
			# param
			$group_id = request()->data['group_id'] ?? '';
			if ( empty($group_id) ) {
				throw new ResponseException(Code::CODE_NO_CONTENT);
			}
			$group_id = explode("_", $group_id);
			# get data
			$list  = Fun::dataList(Fun::mApi('ads','AdsSimpleGroup'),[
				'where' => [
					'ads_simple_group_id' 	=> ['in', $group_id],
				],
				'relation' => 'list_data',
				'limit' => count($group_id),
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



}