<?php
namespace app\api\controller\ads\v1;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
/**
 * @title 广告位
 * @author Lzy
 * @date 2018-03-19 10:00:00
 */
class AdsPosition
{

	function index()
	{
		try {
			# get param
			$param 		= request()->data;
			# 条件
			$map['ads_position_state'] = State::STATE_NORMAL;
			if ( isset($param['ads_position_name']) && $param['ads_position_name']) {
				$map['ads_position_name'] = [ 'like', '%' .(string) $param['ads_position_name'] . '%' ];
			}
			if ( isset($param['wh']) && $param['wh']) {
				$wh = explode("x", $param['wh']);
				$map['ads_position_width'] 	= $wh[0] ?? 0;
				$map['ads_position_height'] = $wh[1] ?? 0;
			}
			if ( isset($param['w']) && $param['w'] >= 0 ) {
				$map['ads_position_width'] = $param['w'];
			}
			if ( isset($param['h']) && $param['h'] >= 0 ) {
				$map['ads_position_height'] = $param['h'];
			}
			# data
			$list = Fun::pageList(Fun::mApi('ads','AdsPosition'), [
				'where' 	=> $map,
				'order'		=> 'ads_position_update_time desc,ads_position_id desc',
				'pagesize' 	=> intval($param['pagesize'] ?? 15),
				'page' 		=> intval($param['page'] ?? 1),
			]);
			if ( empty($list) ) {
				// throw new ResponseException(Code::CODE_OTHER_FAIL);
				throw new ResponseException(Code::CODE_NO_CONTENT);
			}
			return $list;
		} catch (ResponseException $e) {
			return $e->getData();
		}
	}

	function info()
	{
		try {
			# get param
			$id 	= intval(request()->data['id'] ?? 0);
			$sort 	= intval(request()->data['sort'] ?? 1);
			if ( State::STATE_NORMAL > $id ) {
				throw new ResponseException(Code::CODE_NO_CONTENT);
			}
			# data
			$info = Fun::dataDetail(Fun::mApi('ads','AdsPosition'),$id);
			if ( empty($info) ) {
				throw new ResponseException(Code::CODE_NO_CONTENT);
			}
			# 检测sort
			if ( $sort > $info['ads_position_num'] || $sort < 0 ) {
				throw new ResponseException(Code::CODE_NO_CONTENT);
			}
			# 获取日期
			$days_use 	= Fun::mApi('ads','AdsPosition')->days_use($id, $sort);
			// dump($days_use);exit;
			# 生成最近一年日历
        	$calendar 	= Fun::mApi('ads','AdsPosition')->calendar(['days_use' => $days_use, 'isuse'=>1]);
			# ...
			return [
				'data' 		=> $info,
				'calendar' 	=> $calendar,
				'days_use' 	=> $days_use,
			];
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}


	

}