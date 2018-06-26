<?php
namespace lbzy\db;

class Select
{
	private static $instance;

	protected $where;
	protected $cache;
	protected $cache_time;
	protected $relation;
	protected $sql;
	protected $field;
	protected $group;
	protected $order;
	protected $limit;
	protected $page;
	protected $pagesize;

	private function __construct($model,$param)
	{
		$this->model 		= is_object($model) ? $model : model($model);
		$this->param 		= array_merge(request()->param(), $param);
		$this->where 		= $this->param['where'] ?? [];
		$this->cache 		= $this->param['cache'] ?? false;
		$this->cache_time 	= $this->param['cache_time'] ?? 0;
		$this->relation 	= $this->param['relation'] ?? false;
		$this->sql 			= $this->param['sql'] ?? null;
		$this->field 		= $this->param['field'] ?? '*';
		$this->group 		= $this->param['group'] ?? null;
		$this->order 		= $this->param['order'] ?? false;
		$this->limit 		= $this->param['limit'] ?? 15;
		$this->page 		= $this->param['page'] ?? 1;
		$this->pagesize 	= $this->param['pagesize'] ?? 15;
	}

	static function instance($model,array $param = [])
	{
		if ( is_null(self::$instance) ) {
			self::$instance = new self($model,$param);
		}
		return self::$instance;
	}

	function all()
	{
		$list = $this->model->cache($this->cache, $this->cache_time)
		->relation($this->relation)
		->where($this->where)
		->where($this->sql)
		->field($this->field)
		->group($this->group)
		->order($this->order)
		->select();
		# 得到数据
	    return empty($list) ? []: collection($list)->toArray();
	}

	function list()
	{
		$list = $this->model->cache($this->cache,$this->cache_time)
		->relation($this->relation)
		->where($this->where)
		->where($this->sql)
		->field($this->field)
		->group($this->group)
		->order($this->order)
		->limit($this->limit)
		->page($this->page)
		->select();
		# 得到数据
	    return empty($list) ? [] : collection($list)->toArray();
	}

	function page()
	{
		$list = $this->model->cache($this->cache,$this->cache_time)
		->relation($this->relation)
		->where($this->where)
		->where($this->sql)
		->field($this->field)
		->group($this->group)
		->order($this->order)
		->paginate([ 'list_rows' => $this->pagesize, 'page' => $this->page ]);
		# 得到数据
		return $list->isEmpty() ? [] : $list->toArray();
	}

	function info($id = 0)
	{
	    if (preg_match('/^[0-9]+$/', $id) && $id > 0 ) {
	    	# 主键查找
	    	$info = $this->model->find(intval($id));
	    } else {
	    	$info = $this->model->cache($this->cache,$this->cache_time)
	    	->relation($this->relation)
	    	->where($this->where)
	    	->where($this->sql)
	    	->field($this->field)
	    	->order($this->order)
	    	->find();
	    }
	    return empty($info) ? [] : $info->toArray();
	}

	function extraWhere($whereSet)
	{
		$where 		= [];
		foreach ($whereSet as $fieldSetInfo) {
			# 字段
			$field 		= $fieldSetInfo['field'];
			# 映射字段
			$map_field 	= $fieldSetInfo['map_field'] ?? $field;
			$inputValue = input($map_field);
			# 解析条件
			if ( !empty($inputValue) || '0' == $inputValue  ) {
				# 映射其他表
				$map_table = $fieldSetInfo['map_table'] ?? [];
				if ( $map_table ) {
					$map_table_where = [
						$map_table['find_field'] => [
							$map_table['find_exp'],
							$inputValue,
						],
					];
					$inputValue = db($map_table['table'])->where($map_table_where)->find()[$map_table['return_field']] ?? '';
				}
				# 搜索前执行,和 映射其他表 只设置一个就可以
				$beforeEval = $fieldSetInfo['before_eval'] ?? '';
				if ( $beforeEval ) {
					$inputValue = eval(html_entity_decode($beforeEval));
				}
				# 查询本表字段
				if ( !empty($inputValue) || '0' == $inputValue  ) {
					switch ($fieldSetInfo['exp']) {
						case 'like':
							$where[$field] = [$fieldSetInfo['exp'],"%" . $inputValue . "%"];
							break;
						default:
							$where[$field] = [$fieldSetInfo['exp'],$inputValue];
							break;
					}
				}
			}
		}
		$this->where = $where;
		return $this;
	}




















}