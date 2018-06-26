<?php
namespace app\common\traits;

/**
 * 项目函数
 * @author Lzy
 * @date 2017-11-11 10:00:00
 */

trait App
{
	/**
	 * 表全部数据,无分页
	 * 需要先创建对应表的model
	 * @param  [type] $model [description]
	 * @param  [type] $param [description]
	 * @return array
	 */
	static function dataAll ($model, $param = [])
	{
		# 合并参数
		$param = array_merge(request()->param(), $param);
		# 表模型
		$model 		= is_object($model) ? $model : model($model);
		# 缓存设置
	    $cache      = $param['cache'] ?? false;
	    $cache_time = $param['cache_time'] ?? 0;
	    $model->cache($cache, $cache_time);

	    # 关联模型
	    $relation 	= $param['relation'] ?? false;
	    $model->relation($relation);

	    # 条件
	    $where      = $param['where'] ?? [];
	    $model->where($where);

	    # sql查询条件
	    $sql        = $param['sql'] ?? null;
	    $model->where($sql);

	    # 查询字段
	    $field      = $param['field'] ?? '*';
	    $model->field($field);

	    # 分组
	    $group      = $param['group'] ?? null;
	    $model->group($group);

	    # 排序
		$order      = $param['order'] ?? false;
	    $model->order($order);

		$list 		= $model->select();
	    return empty($list) ? []: collection($list)->toArray();

	}

	/**
	 * 列表数据,可分页
	 * 需要先创建对应表的model
	 * @param  [type] $model [description]
	 * @param  [type] $param [description]
	 * @return array
	 */
	static function dataList ($model, $param = [])
	{
		# 合并参数
		$param = array_merge(request()->param(), $param);
		# 表模型
		$model 		= is_object($model) ? $model : model($model);
		# 缓存设置
	    $cache      = $param['cache'] ?? false;
	    $cache_time = $param['cache_time'] ?? 0;
	    $model->cache($cache,$cache_time);

	    # 关联模型
	    $relation 	= $param['relation'] ?? false;
	    $model->relation($relation);

	    # 条件
	    $where      = $param['where'] ?? [];
	    $model->where($where);

	    # sql查询条件
	    $sql        = $param['sql'] ?? null;
	    $model->where($sql);

	    # 查询字段
	    $field      = $param['field'] ?? '*';
	    $model->field($field);

	    # 分组
	    $group      = $param['group'] ?? null;
	    $model->group($group);

	    # 排序
		$order      = $param['order'] ?? false;
	    $model->order($order);

		# limit设置
		$limit 		= (int) ($param['limit'] ?? 15);
		$model->limit($limit);

		# page设置
	    $page       = (int) ($param['page'] ?? 1);
	    $model->page($page);

	    $list 		= $model->select();
	    return empty($list) ? [] : collection($list)->toArray();
	}

	/**
	 * 分页数据
	 * 需要先创建对应表的model,
	 * @param  [type] $model [description]
	 * @param  [type] $param [description]
	 * @return array
	 */
	static function pageList ($model, $param = [])
	{
		# 合并参数
		$param = array_merge(request()->param(), $param);
		# 表模型
		$model 		= is_object($model) ? $model : model($model);
		# 缓存设置
	    $cache      = $param['cache'] ?? false;
	    $cache_time = $param['cache_time'] ?? 0;
	    $model->cache($cache,$cache_time);

	    # 关联模型
	    $relation 	= $param['relation'] ?? false;
	    $model->relation($relation);

	    # 条件
	    $where      = $param['where'] ?? [];
	    $model->where($where);

	    # sql查询条件
	    $sql        = $param['sql'] ?? null;
	    $model->where($sql);

	    # 查询字段
	    $field      = $param['field'] ?? '*';
	    $model->field($field);

	    # 分组
	    $group      = $param['group'] ?? null;

	    # 排序
		$order      = $param['order'] ?? false;
	    $model->order($order);

		# 分页设置
		$pagesize 	= (int) ($param['pagesize'] ??  15);

		# 当前页
	    $page       = (int) ($param['page'] ??  1);

	    $list 		= $model->paginate([ 'list_rows' => $pagesize, 'page' => $page, 'query' => request()->param() ]);

	    if ($list->isEmpty()) return [];
	    $p      = $list->render();
	    $data   = $list->toArray();
	    $data['page']   = $p;
	    return $data;
	}

	/**
	 * 获取详情
	 * 需要先创建对应表的model
	 * @param  [type] $model  [description]
	 * @param  [integer|array] $param [description]
	 * @return array
	 */
	static function dataDetail($model, $param = [])
	{

		# 表模型
		$model 		= is_object($model) ? $model : model($model);
		# 条件
	    if (is_numeric($param) && preg_match('/^[0-9]+$/', $param) ) {
	    	# 主键查找
	    	$detail = $model->find(intval($param));
	    } elseif ( is_array($param) ) {
	    	# 条件查找
			$param = array_merge(request()->param(), $param);

	    	# 缓存设置
		    $cache      = $param['cache'] ?? false;
		    $cache_time = $param['cache_time'] ?? 0;
		    $model->cache($cache,$cache_time);

		    # 关联模型
		    $relation 	= $param['relation'] ?? false;
		    $model->relation($relation);

		    # 条件
		    $where      = $param['where'] ?? [];
		    $model->where($where);

		    # sql查询条件
		    $sql        = $param['sql'] ?? null;
		    $model->where($sql);

		    # 查询字段
		    $field      = $param['field'] ?? '*';
		    $model->field($field);

		    $detail = $model->find();
		    // dump($detail);exit;
	    } else {
	    	$detail = [];
	    }
	    return empty($detail) ? [] : $detail->toArray();
	}

	/**
	 * 快速实例化api里面的validate
	 * @param  [type] $dir  [description]
	 * @param  [type] $name [description]
	 * @param  [type] $v 	[description]
	 * @return think\Validate
	 */
	static function vApi($dir, $name, $v = 'v1')
	{
		$class = "\\app\\api\\validate\\$dir\\$v\\$name";
		return new $class;
	}

	/**
	 * 快速实例化api里面的model
	 * @param  [type] $dir  [description]
	 * @param  [type] $name [description]
	 * @return think\Validate
	 */
	static function mApi($dir, $name)
	{
		$class = "\\app\\api\\model\\$dir\\$name";
		return new $class;
	}

	/**
	 * 获取请求绑定的参数
	 * @param  string $key [description]
	 * @return [type]      [description]
	 */
	static function getBindParam ($bind = null) 
	{
		switch ($bind) {
			case 'data':
				return request()->data;
				break;
			case 'user':
				return request()->user;
				break;
			case 'app':
				return request()->user;
				break;
			default:
				return request()->param();
				break;
		}
	}

	/**
	 * 店铺链接
	 * @param  [type] $shop_id     [店铺id]
	 * @param  [type] $shop_domain [店铺域名]
	 * @return [type]              [description]
	 */
	static function shop_url($shop_id, $shop_domain = null)
	{
		return '/shop?id=' . (int) $shop_id;
		return F::domain( empty($shop_domain) ? $shop_id : $shop_domain );
	}

	/**
	 * 检测 (从0开始的sid分类表)id值记录 是否是第n级分类
	 * @param  [string]  	$param['table'] 	[表名]
	 * @param  [string] 	$param['pk']		[主键字段名,指定data时必传]
	 * @param  [strign]  	$param['sid'] 		[上级字段名]
	 * @param  [integer]  	$param['n']			[检测几级 正整数]
	 * @param  [integer]  	$param['value'] 	[当前id的值]
	 * @param  [array]      $param['data'] 		[数据源 droplist格式,传入时请指定pk]
	 * @return boolean
	 */
	static function isLevelsCategory($param)
	{
		$table 		= $param['table'] ?? '';
		$pk 		= $param['pk'] ?? 'id';
		$sid 		= $param['sid'] ?? 'sid';
		$n 			= intval($param['n'] ?? 1);
		$id 		= intval($param['value'] ?? 0);
		$dataList 	= $param['data'] ?? [];
		$dataList 	= array_column((array)$dataList, null, $pk);

		if ($n <= 0) {
			return false;
		} else {
			while ($n-- > 0) {
				# 获取单条记录
				if ( $dataList ) {
					$info = $dataList[$id] ?? [];
				} else {
					$info = db($table)->field($sid)->find($id);
				}
				if ( empty($info) || false == isset($info[$sid]) ) {
					return false;
				}
				# 已到顶级,sid并不是0
				if ( $n == 0 && $info[$sid] != 0 ) {
					return false;
				}
				# 未到顶级,sid却小于等于0
				if ( $n > 0 && $info[$sid] <= 0 ) {
					return false;
				}
				# sid作为下次查询的id
				$id = $info[$sid];
			}
			return true;
		}
	}

	/**
	 * 是否是本站链接
	 * @param  [type]  $url [description]
	 * @return boolean      [description]
	 */
	static function isMyWebHttpUrl($url)
	{
		# 先转换小写
		$url = strtolower($url);
		# 是否是有效地址
		if ( false == \think\Validate::is($url, 'url') ) {
			return false;
		}
		# 是否是http或https开头
		if ( strpos($url, request()->scheme() . '://') !== 0 ) {
			return false;
		}
		# 解析出域名数组
		$host 	= explode('.', parse_url($url, PHP_URL_HOST));
		$length = count($host);
		$str 	= '';
		# 循环匹配
		while($length--){
			if ($str) {
				$str = '.' . $str;
			}
			$str = array_pop($host) . $str;
			if ( $str == strtolower(config('url_domain_root')) ) {
				return true;
			}
		}
		return false;
	}

}
