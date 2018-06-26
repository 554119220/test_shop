<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\Cache;

/**
 * 商家申请商品优选
 * @author Lzy
 * @date 2017-01-13 10:00:00
 */
class GoodsApplyRecommend
{

	/**
	 * 列表
	 * @return [type] [description]
	 */
	function index()
	{
		try {
			# param
			$param = request()->data;
			$user_id = intval(request()->user['user_id'] ?? 0);
			if ( State::STATE_NORMAL > $user_id ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# get data
			$map['seller_user_id'] = $user_id;
			if ( isset($param['state']) && preg_match('/^[0-9]+$/', $param['state']) ) {
				$map['goods_apply_recommend_is_audit'] = intval($param['state']);
			}
			$list = Fun::pageList(Fun::mApi('goods','GoodsApplyRecommend'),[
				'order'		=> 'goods_apply_recommend_update_time desc',
				'where' 	=> $map,
				'relation' 	=> 'audit,goods',
				'page' 		=> intval(request()->data['page'] ?? 0),
			]);
			# ...
			return $list;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	/**
	 * 可以申请的商品列表
	 * @return [type] [description]
	 */
	function canApplyGoodsList()
	{
		try {
			# param
			$user_id = intval(request()->user['user_id'] ?? 0);
			$auditList = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommend'),[
				'where' => [
					'seller_user_id' 					=> $user_id,
					'goods_apply_recommend_is_audit'	=> State::STATE_DISABLED,
				],
				'field' => 'goods_id',
			]);
			# get data
			$list = Fun::pageList(Fun::mApi('goods', 'Goods'),[
				'where' => [
					'seller_user_id' 			=> $user_id,
					'goods_state' 				=> State::STATE_NORMAL,
					'goods_have_qualifications'	=> State::STATE_NORMAL,
					'goods_recommend_type'		=> State::STATE_GOODS_RECOMMEND_TYPE_ZERO,
					'goods_id'					=> [ 'not in' , array_column($auditList, 'goods_id') ],
				],
				'order' => 'goods_id desc',
				'page' 	=> intval(request()->data['page'] ?? 0),
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

	/**
	 * 待审核的商品
	 */
	function noAudit()
	{
		try {
			# param
			$user_id = intval(request()->user['user_id'] ?? 0);
			# get data
			$list = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommend'),[
				'where' => [
					'seller_user_id' 					=> $user_id,
					'goods_apply_recommend_is_audit'	=> State::STATE_DISABLED,
				],
				'field' => 'goods_id',
			]);
			if ( empty($list) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# ...
			return array_column($list, 'goods_id');
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	/**
	 * 申请优选
	 * @param  $openid
	 * @param  goods_ids
	 */
	function create()
	{
		try {
			# param
			$user_id 	= intval(request()->user['user_id'] ?? 0);
			$goods_ids 	= (string)(request()->data['goods_ids'] ?? '');
			if ( State::STATE_NORMAL > $user_id || empty($goods_ids) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# 验证是否是自己的商品
			$goods_ids = explode(",", $goods_ids);
			$goodsList = Fun::dataAll(Fun::mApi('goods','Goods'),[
				'where' => [
					'goods_id' 			=> [ 'in', $goods_ids ],
					'seller_user_id'	=> $user_id,
				],
				'field' => 'goods_id,goods_state,goods_recommend_type',
			]);
			if ( count($goodsList) != count($goods_ids) ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL, '操作失败' );
			}
			# 是否上架 和常规
			foreach ($goodsList as $key => $value) {
				if ( $value['goods_state'] != State::STATE_NORMAL ) {
					throw new ResponseException( Code::CODE_OTHER_FAIL, '只有上架的商品才可申请优选' );
				}
				if ( $value['goods_recommend_type'] != State::STATE_GOODS_RECOMMEND_TYPE_ZERO ) {
					throw new ResponseException( Code::CODE_OTHER_FAIL, '只有常规商品才能申请优选' );
				}
			}
			# 是否已经在申请
			$applyList = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommend'),[
				'where' => [
					'seller_user_id' 					=> $user_id,
					'goods_apply_recommend_is_audit' 	=> State::STATE_DISABLED,
				],
			]);
			// dump($applyList);
			foreach ($applyList as $key => $value) {
				if ( in_array($value['goods_id'], $goods_ids) ) {
					throw new ResponseException( Code::CODE_OTHER_FAIL, '商品ID为' . $value['goods_id'] . '的商品正在申请优选中' );
				}
			}
			# 超出今日申请数量
			$nums_key = \app\common\traits\Cache::getCacheName(Cache::GOODS_APPLY_RECOMMEND_NUMS) . $user_id;

			$nums_cache = Fun::redis()->get($nums_key);
			// dump($nums_cache);exit;
			if ( $nums_cache ) {
				if ( $nums_cache + count($goods_ids) > (config('site.goods')['goods_apply_num'] ?? 0) ) {
					throw new ResponseException(Code::CODE_OTHER_FAIL, '超出每日可申请优选数量');
				}
				$nums_cache = $nums_cache + count($goods_ids);
				Fun::redis()->setex($nums_key,strtotime(date('Y-m-d')) + 24 * 3600 - time(),$nums_cache);
			} else {
				if ( $nums_cache  > (config('site.goods')['goods_apply_num'] ?? 0) ) {
					throw new ResponseException(Code::CODE_OTHER_FAIL, '超出每日可申请优选数量');
				}
				Fun::redis()->setex($nums_key,strtotime(date('Y-m-d')) + 24 * 3600 - time(),count($goods_ids));
			}
			# 添加或修改记录
			db()->startTrans();
			foreach ($goods_ids as $key => $value) {
				# 资质检测
				$qualifications = Fun::dataDetail(Fun::mApi('goods','GoodsQualifications'), [
					'where' => [
						'goods_id' => $value,
					],
					'field' => 'goods_id'
				]);
				if ( empty($qualifications) ) {
					throw new ResponseException( Code::CODE_OTHER_FAIL, '编号' . $value . '的商品 缺少资质' );
				}
				# ...
				$apply = Fun::dataDetail(Fun::mApi('goods','GoodsApplyRecommend'),[
					'where' => [
						'goods_id' => $value,
					],
				]);
				if ( $apply ) {
					// dump($apply);
					# 修改记录
					$applyData = [
						'seller_user_id' 					=> $user_id,
						'goods_id' 							=> $value,
						'goods_apply_recommend_is_audit' 	=> State::STATE_DISABLED,
					];
					$map = [
						'goods_apply_recommend_id' => $apply['goods_apply_recommend_id'],
					];
					if ( false == Fun::mApi('goods','GoodsApplyRecommend')->isUpdate(true)->save($applyData, $map) ) {
						throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败，请稍后重试');
					}
					db('goods_apply_recommend')->where($map)->update(['goods_apply_recommend_create_time' => time()]);
				} else {
					# 增加记录
					$applyData = [
						'seller_user_id' 	=> $user_id,
						'goods_id' 			=> $value,
					];
					if ( false == Fun::mApi('goods','GoodsApplyRecommend')->isUpdate(false)->save($applyData) ) {
						throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败，请稍后重试');
					}
				}
			}
			# ...
			db()->commit();
			return Code::CODE_SUCCESS;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}






}