<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\Cache;
use mercury\factory\Factory;

/**
 * 商家申请商品精选
 * @author Lzy
 * @date 2017-01-13 10:00:00
 */
class GoodsApplyRecommend1
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
				$map['goods_apply_recommend1_is_audit'] = intval($param['state']);
			}
			$list = Fun::pageList(Fun::mApi('goods','GoodsApplyRecommend1'),[
				'order'		=> 'goods_apply_recommend1_update_time desc',
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
			$auditList = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommend1'),[
				'where' => [
					'seller_user_id' 					=> $user_id,
					'goods_apply_recommend1_is_audit'	=> State::STATE_DISABLED,
				],
				'field' => 'goods_id',
			]);
			# get data
			$list = Fun::pageList(Fun::mApi('goods', 'Goods'),[
				'where' => [
					'seller_user_id' 			=> $user_id,
					'goods_state' 				=> State::STATE_NORMAL,
					'goods_have_qualifications'	=> State::STATE_NORMAL,
					'goods_recommend_type'		=> State::STATE_GOODS_RECOMMEND_TYPE_THREE,
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
			$list = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommend1'),[
				'where' => [
					'seller_user_id' 					=> $user_id,
					'goods_apply_recommend1_is_audit'	=> State::STATE_DISABLED,
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
	 * 精选申请
	 * @param  $openid
	 * @param  goods_id
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
			# 是否开放
			if( empty(config('site.goods')['apply_goods_recommend1_open'] ?? 0) ){
				throw new ResponseException( Code::CODE_OTHER_FAIL, '精选申请已暂时关闭' );
			}
			# 验证是否是自己的商品
			$goods = Fun::dataDetail(Fun::mApi('goods','Goods'),[
				'where' => [
					'goods_id' 			=> $goods_ids,
					'seller_user_id'	=> $user_id,
				],
				'field' => 'goods_id,goods_state,goods_recommend_type',
			]);
			if ( empty($goods) ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL, '操作失败' );
			}
			# 商家是否符合申请条件
			$shopDetail = Factory::instance('/goods/v1/Shop/Detail')->run(['shop_id' => request()->user['user_shop_id'] ?? 0]);
			if ( ($shopDetail['data']['shop_basis_score'] ?? 0) < State::STATE_AYPPLY_RECOMMEND1_MIN_SHOP_SCORE ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL, '店铺积分不符合条件' );
			}
			$statistics = Factory::instance('/goods/v1/Goods/statistics')->run();
			if ( ($statistics['data']['up'] ?? 0) < State::STATE_AYPPLY_RECOMMEND1_MIN_GOODS_UP_NUM ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL, '在售商品数不符合条件' );
			}
			# 是否上架 和常规
			if ( $goods['goods_state'] != State::STATE_NORMAL ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL, '只有上架的商品才可申请精选' );
			}
			if ( $goods['goods_recommend_type'] != State::STATE_GOODS_RECOMMEND_TYPE_THREE ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL, '只有优选商品才能申请精选' );
			}
			
			# 是否已经在申请
			$applyList = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommend1'),[
				'where' => [
					'seller_user_id' 					=> $user_id,
					'goods_apply_recommend1_is_audit' 	=> State::STATE_DISABLED,
				],
			]);
			// dump($applyList);
			foreach ($applyList as $key => $value) {
				if ( $value['goods_id'] == $goods_ids ) {
					throw new ResponseException( Code::CODE_OTHER_FAIL, '商品ID为' . $value['goods_id'] . '的商品正在申请精选中' );
				}
			}
			# 超出今日申请数量
			$nums_key = \app\common\traits\Cache::getCacheName(Cache::GOODS_APPLY_RECOMMEND1_NUMS) . $user_id;
			$nums_cache = Fun::redis()->get($nums_key);
			if ( $nums_cache ) {
				if ( $nums_cache >= (config('site.goods')['goods_apply1_num'] ?? 0) ) {
					throw new ResponseException(Code::CODE_OTHER_FAIL, '每日可申请精选数量已用完');
				}
				$nums_cache = $nums_cache + 1;
				Fun::redis()->setex($nums_key,strtotime(date('Y-m-d')) + 24 * 3600 - time(),$nums_cache);
			} else {
				Fun::redis()->setex($nums_key,strtotime(date('Y-m-d')) + 24 * 3600 - time(),1);
			}
			# 资质检测
			$qualifications = Fun::dataDetail(Fun::mApi('goods','GoodsQualifications'), [
				'where' => [
					'goods_id' => $goods_ids,
				],
				'field' => 'goods_id'
			]);
			if ( empty($qualifications) ) {
				throw new ResponseException( Code::CODE_OTHER_FAIL, '编号' . $goods_ids . '的商品 缺少资质' );
			}
			# 添加或修改记录
			db()->startTrans();
			# ...
			$apply = Fun::dataDetail(Fun::mApi('goods','GoodsApplyRecommend1'),[
				'where' => [
					'goods_id' => $goods_ids,
				],
			]);
			$applyData = [
					'seller_user_id' 					=> $user_id,
					'goods_id' 							=> $goods_ids,
					'goods_apply_recommend1_is_audit' 	=> State::STATE_DISABLED,
					'goods_apply_recommend1_content' 	=> [
						'active_price'	=> (string) (request()->data['active_price'] ?? ''),
						'market_price'	=> (string) (request()->data['market_price'] ?? ''),
						'score_multi'	=> (string) (request()->data['score_multi'] ?? ''),
						'tmall_link'	=> (string) (request()->data['tmall_link'] ?? ''),
						'jd_link'		=> (string) (request()->data['jd_link'] ?? ''),
						'reason'		=> iconv_substr((string) (request()->data['reason'] ?? ''), 0,300,'utf-8'),
						'goods_sku'		=> request()->data['goods_sku'] ?? [],
					],
				];
			if ( $apply ) {
				// dump($apply);
				# 修改记录
				$map = [
					'goods_apply_recommend1_id' => $apply['goods_apply_recommend1_id'],
				];
				if ( false == Fun::mApi('goods','GoodsApplyRecommend1')->isUpdate(true)->save($applyData, $map) ) {
					throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败，请稍后重试');
				}
				db('goods_apply_recommend1')->where($map)->update(['goods_apply_recommend1_create_time' => time()]);
			} else {
				# 增加记录
				if ( false == Fun::mApi('goods','GoodsApplyRecommend1')->isUpdate(false)->save($applyData) ) {
					throw new ResponseException(Code::CODE_OTHER_FAIL, '操作失败，请稍后重试');
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