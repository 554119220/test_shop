<?php
namespace app\api\controller\ads\v1;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
/**
 * @title 广告
 * @author Lzy
 * @date 2018-03-19 10:00:00
 */
class Ads
{

	private $createField = [
		'ads_no',
		'ads_title',
		'ads_sub_title',
		'ads_descript',
		'ads_images',
		'ads_url',
		'ads_user_id',
		'ads_state',
		'ads_sucai_id',
		'ads_position_id',
		'ads_sort',
		'ads_sday',
		'ads_eday',
		'ads_days',
		'ads_days_nums',
		'ads_money',
		'ads_pay_money',
	];

	function create()
	{
		try {
			# get param
			$param 		= request()->data;
			$shop_id 	= intval(request()->user['user_shop_id'] ?? 0);
			$user_id 	= intval(request()->user['user_id'] ?? 0);
			if ( State::STATE_NORMAL > $shop_id || State::STATE_NORMAL > $user_id ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# 插入数据
			$position = Fun::dataDetail(Fun::mApi('ads','AdsPosition'),$param['ads_position_id']);
			// dump($position);exit;
			$insertData = [
				'ads_no' 		=> null,
				'ads_user_id' 	=> $user_id,
				'ads_state' 	=> State::ADS_STATE_WAIT_PAY,
				'ads_images'	=> null,
				'ads_days_nums' => count(explode(',',$param['ads_days'])),
				'ads_money' 	=> count(explode(',',$param['ads_days'])) * $position['ads_position_price'],
			];
			$param = array_merge($param,$insertData);
			if ( false == Fun::mApi('ads','Ads')->isUpdate(false)->allowField($this->createField)->save($param) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			return Code::CODE_SUCCESS;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	function index2()
	{
		try {
			# get param
			$param 		= request()->data;
			$user_id 	= intval(request()->user['user_id'] ?? 0);
			if ( State::STATE_NORMAL > $user_id ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# get data
			$map['ads_user_id'] = $user_id;
			$list = Fun::pageList(Fun::mApi('ads','Ads'),[
				'where' 	=> $map,
				'page'		=> intval($param['page'] ?? 1),
				'pagesize'	=> intval($param['pagesize'] ?? 10),
				'order'		=> 'ads_id desc',
			]);
			if ( empty($list) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			return $list;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	function info2()
	{
		try {
			# get param
			$ads_id 	= intval(request()->data['id'] ?? 0);
			$user_id 	= intval(request()->user['user_id'] ?? 0);
			if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $ads_id ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			# get data
			$info = Fun::dataDetail(Fun::mApi('ads','Ads'),[
				'where' => [
					'ads_id' 		=> $ads_id,
					'ads_user_id' 	=> $user_id,
				],
				'relation' => 'position',
			]);
			if ( empty($info) ) {
				throw new ResponseException( Code::CODE_NO_CONTENT );
			}
			return $info;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	/**
     * @title 获取广告
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |position_id         |int    |true   |0  |---    |广告位id，多个用_隔开|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
		    "data": [
		    	5:{
			        "ads_position_id": "5",
			        "ads_position_name": "广告位标题",
			        "ads_position_sub_name": "广告位副标题",
			        "ads_position_descript": "广告位描述",
			        "list_data":{
			            1:{
				            "ads_title": "广告标题",
				            "ads_sub_title": "广告副标题",
				            "ads_descript": "广告描述",
				            "ads_images": "广告图片",
				            "ads_url":'广告链接',
				        },
				        2:{
				            "ads_title": "广告标题",
				            "ads_sub_title": "广告副标题",
				            "ads_descript": "广告描述",
				            "ads_images": "广告图片",
				            "ads_url":'广告链接',
				        },
				        3:{...},
				        ...
			        },
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
	function index()
	{
		try {
			# param
			$position_id = trim((string)(request()->data['position_id'] ?? ''));
			if ( empty($position_id) ) {
				throw new ResponseException(Code::CODE_NO_CONTENT);
			}
			$position_ids = explode("_", $position_id);
			# get data
			$list  = Fun::dataAll(Fun::mApi('ads','AdsPosition'),[
				'where' => [
					'ads_position_id' 		=> [ 'in', $position_ids ],
					'ads_position_state' 	=> State::STATE_NORMAL,
				],
				'field' => [
					'ads_position_id',
					'ads_position_name',
					'ads_position_sub_name',
					'ads_position_descript',
					'ads_position_num',
					'ads_position_default_title',
					'ads_position_default_sub_title',
					'ads_position_default_descript',
					'ads_position_default_image',
					'ads_position_default_url',
				],
				'relation' => 'list_data',
			]);
			// dump($list);exit;
			if ( empty($list) ) {
				throw new ResponseException(Code::CODE_NO_CONTENT);
			}
			# 数据处理 没有投放的 加入默认广告
			foreach ($list as $key => $adsPosition) {
				$list_new	= [];
				$sort 		= $adsPosition['ads_position_num'];

				$list_data 	= array_column($adsPosition['list_data'] ?? [], null,'ads_sort');
				for ($i = 1; $i <= $sort; $i++) {
					if ( isset($list_data[$i]) ) {
						# 有投放的
						$list_new[$i] = $list_data[$i];
					} else {
						# 没有投放的
						$list_new[$i] = [
							'ads_id'			=> 0,
							'ads_title' 		=> $adsPosition['ads_position_default_title'],
							'ads_sub_title' 	=> $adsPosition['ads_position_default_sub_title'],
							'ads_descript' 		=> $adsPosition['ads_position_default_descript'],
							'ads_images' 		=> $adsPosition['ads_position_default_image'],
							'ads_url' 			=> $adsPosition['ads_position_default_url'],
							'ads_state'			=> (string)State::STATE_NORMAL,
							'ads_sort'			=> $i,
							'ads_state_name'	=> State::ADS_STATE_ARRAY[State::STATE_NORMAL] ?? "",
						];
					}
				}
				$list[$key]['list_data'] = $list_new;
			}
			# 按传入的position_id顺序返回
			$list = array_column($list, null, 'ads_position_id');
			$res = [];
			foreach ($position_ids as $position_id) {
				$res[$position_id] = $list[$position_id] ?? [];
			}
			# ...
			return $res;
		} catch (ResponseException $e) {
			return $e->getData();
		}
	}

	function createErpAdsOrder()
	{
		try {
			# get param
			$user_id 	= intval(request()->user['user_id'] ?? 0);
			$ads_id 	= intval(request()->data['id'] ?? 0);
			// dump(request()->user['openid'] ?? '');
			$openid		= request()->user['openid'] ?? '';
			
			// dump($user_id);exit;
			if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $ads_id ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL);
			}
			# 是否有数据
			$info = Fun::dataDetail(Fun::mApi('ads','Ads'), [
				'where' 	=> [
					'ads_user_id' 	=> $user_id,
					'ads_id'		=> $ads_id,
				],
				'relation' 	=> 'position',
			]);
			if ( empty($info) ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '需要付款的订单不存在');
			}
			if ( $info['ads_state'] != State::ADS_STATE_WAIT_PAY ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '订单当前状态不能付款');
			}
			# 收款方不存在
			$receive = $info['position']['ads_position_receive'] ?? '';
			if ( empty($receive) ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '广告位暂时不能付款');
			}
			# 是否超过支付时间
			// echo time();
			if ( time() - strtotime($info['ads_create_time']) > State::ADS_STATE_WAIT_PAY_TIME ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '订单超时，已不能支付。');
			}
			# 是否已过投放日期
			if ( date('Ymd') >= date('Ymd',strtotime($info['ads_eday'])) ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '投放日期已过期');
			}
			# 获得 请求获得erp单号
			$erp = new \lbzy\sdk\erp\Erp;
			$erpApiData = [
				'buyer_openid' 	=> $openid,
				'seller_openid' => $receive,
				'out_order_no'	=> $info['ads_no'],
				'amount'		=> $info['ads_money'],
				# 成功跳转地址
				'return_url'	=> request()->data['referer'] ?? Fun::domain('ads','/ads/index'),
				# 异步curl地址
				'notify_url'	=> Fun::domain('ads','/ads_notify'),
				# 异常跳转地址
				'callback_url'	=> Fun::domain('ads','/ads/index'),
				# 过期时间 ads过期时间 - (time() - ads创建时间) - erp接口timeout
				'expire_time'	=> State::ADS_STATE_WAIT_PAY_TIME - ( time() - strtotime($info['ads_create_time']) ) - $erp->timeout,
			];
			$erpAdsOrderInfo = $erp->api('/mall.v1.order/purchaseAdsOrder', $erpApiData);
			// dump($erpAdsOrderInfo);
			if ( ! isset($erpAdsOrderInfo['code']) || $erpAdsOrderInfo['code'] != State::STATE_NORMAL ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '创建支付订单失败：' . $erpAdsOrderInfo['msg']);
			}
			// dump($erpAdsOrderInfo);exit;
			# 请求erp 获得收银台url
			$erpApiData = [
				'openid' 	=> $openid,
				'code'		=> $erpAdsOrderInfo['data']['order']['code'],
			];
			$erpPayUrlInfo = $erp->api('/mall.v1.order/quickPayUrl', $erpApiData);
			if ( $erpPayUrlInfo['code'] != State::STATE_NORMAL ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '创建支付链接失败：' . $erpPayUrlInfo['msg']);
			}
			// dump($erpPayUrlInfo);exit;
			# ...
			return  ['url' => $erpPayUrlInfo['data']['url']];
		} catch (ResponseException $e) {
			// dump($e);
			# ...
			return $e->getData();
		}
	}

	/**
	 * 订单支付
	 * @return [type] [description]
	 */
	function pay()
	{
		try {
			# get param
			$erpData = $logData = request()->post();
			# erp签名校验
			$erp = new \lbzy\sdk\erp\Erp;
			if ( false == $erp->verifySign($erpData) ) {
				$logData['content'] 	= '签名校验失败';
				$logData['is_success'] 	= 0;
				$this->Log($logData);
				throw new ResponseException(Code::CODE_OTHER_FAIL, '签名校验失败.');
			}
			# 是否有数据
			$info = Fun::dataDetail(Fun::mApi('ads','Ads'), [
				'where' 	=> [
					'ads_no' => $erpData['order_no'] ?? '',
				],
			]);
			if ( empty($info) ) {
				$logData['content'] 	= '没有找到广告单号：' . $erpData['order_no'] ?? '';
				$logData['is_success'] 	= 0;
				$this->Log($logData);
				throw new ResponseException(Code::CODE_OTHER_FAIL, '需要付款的订单不存在');
			}
			if ( $info['ads_state'] ) {
				$logData['content'] 	= '广告订单状态错误：' . $info['ads_state'] . State::ADS_STATE_ARRAY[$info['ads_state']] ?? '';
				$logData['is_success'] 	= 0;
				$this->Log($logData);
				throw new ResponseException(Code::CODE_OTHER_FAIL, '需要付款的订单状态不是待付款');
			}
			# 更改状态
			$updateData = [
				'ads_id'		=> $info['ads_id'],
				'ads_state' 	=> State::ADS_STATE_NORMAL,
				'ads_pay_time' 	=> time(),
				'ads_is_pay'	=> State::STATE_NORMAL,
				'ads_pay_money'	=> $erpData['amount'],
			];
			if ( false == Fun::mApi('ads','Ads')->isUpdate(true)->save($updateData) ) {
				$logData['content'] = '修改状态失败：' . $erpData['order_no'] ?? '';
				$logData['is_success'] = 0;
				$this->Log($logData);
				throw new ResponseException(Code::CODE_OTHER_FAIL, '订单付款失败');
			}
			# 记录成功
			$logData['content'] 	= '成功';
			$logData['is_success'] 	= 1;
			$this->Log($logData);
			# ...
			return Code::CODE_SUCCESS;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	private function Log($param = [])
	{
		$param['ads_datetime'] 	= date('Y-m-d H:i:s');
		$param['ads_date'] 		= date('Y-m-d');
		$param['ads_time'] 		= time();
		ksort($param);
		Fun::writeLogByMongoDb('ads',$param);
	}

	/**
	 * 取消
	 * @return [type] [description]
	 */
	function cancel()
	{
		try {
			# get param
			$user_id 	= intval(request()->user['user_id'] ?? 0);
			$ads_id 	= intval(request()->data['ads_id'] ?? 0);
			if ( State::STATE_NORMAL > $user_id || State::STATE_NORMAL > $ads_id ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '取消失败');
			}
			# update
			$updateData = [
				'ads_state'		=> State::ADS_STATE_CANCEL,
			];
			$where = [
				'ads_user_id'   => $user_id,
				'ads_id'		=> $ads_id,
				'ads_state'		=> State::ADS_STATE_WAIT_PAY,
			];
			if ( false == Fun::mApi('ads','Ads')->isUpdate(true)->save($updateData,$where) ) {
				throw new ResponseException(Code::CODE_OTHER_FAIL, '取消失败1');
			}
			# ...
			return Code::CODE_SUCCESS;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	/**
	 * 过期未付款 设置取消状态
	 */
	static function changeForNoPay()
	{
		try {
			Fun::mApi('ads','Ads')->isUpdate(true)->save(['ads_state' => State::ADS_STATE_CANCEL ],[
				'ads_create_time' 	=> [ 'lt', time() - State::ADS_STATE_WAIT_PAY_TIME ],
				'ads_state'			=> State::ADS_STATE_WAIT_PAY,
			]);
			# ...
			return Code::CODE_SUCCESS;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}

	/**
	 * 投放日期已过 设置过期状态
	 */
	static function changeForPastDate()
	{
		try {
			Fun::mApi('ads','Ads')->isUpdate(true)->save(['ads_state' => State::ADS_STATE_PASS_DATE ],[
				'ads_eday' 		=> [ 'lt', date('Y-m-d',time()) ],
				'ads_state'		=> State::ADS_STATE_NORMAL,
			]);
			# ...
			return Code::CODE_SUCCESS;
		} catch (ResponseException $e) {
			# ...
			return $e->getData();
		}
	}
}