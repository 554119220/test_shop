<?php
namespace app\common\traits;

/**
 * 基础函数
 * @author Lzy
 * @date 2017-11-11 10:00:00
 */

trait Base
{
	/**
	 * 检测参数是否 有值或不为空
	 * @param [mixed] [$var]
	 * @return boolean
	 */
	static function isNoEmpty($var)
	{
		return false == empty($var) || $var == '0';
	}

	/**
	 * 检测变量是否是正整数
	 * @param [mixed] [$var]
	 * @return boolean
	 */
	static function isPositiveInteger($var)
	{
		return preg_match('/^[1-9][0-9]{0,}$/', $var);
	}

	/**
	 * 检测变量是否是非负整数
	 * @param [mixed] [$var]
	 * @return boolean
	 */
	static function isNonNegativeInteger($var)
	{
		return preg_match('/^[0-9]$/', $var) || preg_match('/^[1-9][0-9]{0,}$/', $var);
	}


	/**
	 * 返回中文不转码的json
	 * @param $data array
	 * @return json string
	 */
	static function json($data)
	{
		return json_encode($data, JSON_UNESCAPED_UNICODE);
	}


	/**
	 * 数组无限极分类树
	 * @param  array  	$data   			droplist数据集
	 * @param  int  	$option['begin'] 	从pid开始取起
	 * @param  string  	$option['id'] 		对应的子键名
	 * @param  string  	$option['pid'] 		对应的父键名
	 * @param  string  	$option['child'] 	子集键名
	 * @param  integer  $option['level']	层级 大于等于1的整数，不传则获取所有层级
	 * @param  bool  	$option['leaveKey']	是否保留key
	 * @return array
	 */
	static function array_tree($data, $option = [])
	{
		$begin 		= $option['begin'] 		?? 0;
		$id  		= $option['id'] 		?? 'id';
		$pid 		= $option['pid'] 		?? 'pid';
		$child 		= $option['child'] 		?? 'child';
		$level 		= $option['level']  	?? null;
		$leaveKey 	= $option['leaveKey'] 	?? false;

		$tree = [];
		foreach ($data as $key => $value) {
			if ($value[$pid] == $begin) {
				# 是否保存key
				if ( $leaveKey ) {
					$tree[$key] = $value;
				} else {
					$tree[] = $value;
				}
				unset($data[$key]);
			}
		}
		if ($level !== null) {
			$level--;
		}
		if ( $tree && ( ($level !== null && $level > 0) || $level === null ) ) {
			foreach ($tree as $k => $v) {
				$option['begin'] = $v[$id];
				$option['level'] = $level;
				$arr = self::array_tree($data, $option);
				if ($arr) {
					$tree[$k][$child] = $arr;
				}
			}
		}
		return $tree;
	}

	/**
	 * 获取类的公共非静态函数
	 * @param string 类名
	 * @return array
	 */
	static function get_class_public_functions($class)
	{
	    $list = [];
	    $class = new \ReflectionClass($class);
	    $methods = $class->getMethods();
	    foreach ($methods as $ReflectionMethod) {
	        if(strtolower($ReflectionMethod->class) == strtolower($class->name)){
	            if($ReflectionMethod->isPublic() && false == $ReflectionMethod->isStatic()){
	                $list[] = $ReflectionMethod->name;
	            }
	        }
	    }
	    return $list;
	}

	/**
	 * 找出文件夹下的所有文件
	 * @param string $dir 文件夹名字
	 * @param bool $recursion 是否递归读取所有
	 * @return array
	 */
	static function dir_files(string $dir, $recursion = false)
	{
		$files 	= scandir(rtrim($dir, DS));
		$result = [];
		foreach ($files as $value) {
			$path = $dir . DS  . $value;
			if(is_file($path)) {
				$result[] = $value;
			}
			if(is_dir($path) && in_array($value, array('.', '..')) == false) {
				if ($recursion) {
					$result[$value] = adminfw_dir_files($path, $recursion);
				} else {
					$result[$value] = [];
				}
			}
		}
		return $result;
	}

	/**
	 * 组合,多用于计算商品属性的组合情况
	    传入：
	    $arr = [
			[ '13寸', '15寸' ],
			[ '320G', '500G' ],
		];
		得到：
		$arr = [
			[ '13寸', '320G' ],
			[ '13寸', '500G' ],
			[ '15寸', '320G' ],
			[ '15寸', '500G' ],
		];
	 * @return array
	 */
	static function array_combine($arr)
	{
		# 结果
		$result = [];
		foreach ($arr as $value) {
			# 临时数组
			$tempArr = [];
			if ( $result ) {
				foreach ($result as $v) {
					foreach ($value as $vo) {
						$vTemp 		= $v;
						$vTemp[] 	= $vo;
						$tempArr[] 	= $vTemp;
					}
				}
			} else {
				foreach ($value as $vo) {
					$tempArr[] = [$vo];
				}
			}
			$result = $tempArr;
		}
		return $result;
	}

	/**
	 * 分组,多用于 商品属性组 分组的情况
	 * 传入：
	    $arr = [
			[ 'idKey' => 0, 'group_name_field' => '容量', 'group_value_field' => 320G' ],
			[ 'idKey' => 1, 'group_name_field' => '容量', 'group_value_field' => 320G' ],
			[ 'idKey' => 2, 'group_name_field' => '大小', 'group_value_field' => 13寸' ],
			[ 'idKey' => 3, 'group_name_field' => '大小', 'group_value_field' => 15寸' ],
		];
		得到：
		$arr = [
			[ 'group_name_field' => '容量', 'idKey' =>[ 0 => 0, 1 => 1 ], 'group_value_field' => [ 0 => '320G', 1 => '500G' ] ],
			[ 'group_name_field' => '大小', 'idKey' =>[ 0 => 2, 1 => 3 ], 'group_value_field' => [ 0 => '13寸', 1 => '15寸' ] ],
		];
	 * @param  [type] $arr        				[droplist数据]
	 * @param  [type] $group_name_field      	[组名]
	 * @param  [type] $group_value_field 		[组值]
	 * @param  [type] $idKey					[组的id]
	 * @return array
	 */
	static function array_divide_group($arr, $group_name_field, $group_value_field, $idKey = null)
	{
		$tempGroupName 		= null;
		$tempGroupValueArr 	= [];
		$result 			= [];

		$tempArr 			= array_unique(array_column($arr, $group_name_field));
		foreach ($tempArr as $key => $value) {
			$result[$key][$group_name_field] = $value;
		}
		# 赋值
		foreach ($arr as $value) {
			$tempGroupName = $value[$group_name_field];
			# 是否用主键值作为key
			if ( false == is_null($idKey) ) {
				$result[array_search($tempGroupName, $tempArr)][$idKey][] = $value[$idKey];
			}
			# ...
			$result[array_search($tempGroupName, $tempArr)][$group_value_field][] = $value[$group_value_field];
		}
		return $result;
	}

	/**
	 * 隐藏字符串内容,支持中文
	 * @param  [string]  $str   [description]
	 * @param  integer $start [description]
	 * @param  integer $size  [description]
	 * @return [type]         [description]
	 */
	static function hidden_str($str, $start = 0, $size = 1)
	{
	    $str = preg_split('/(?<!^)(?!$)/u', (string)$str);
	    if ( $size < 0 ) {
	    	$size = count($str) + $size - $start;
	    }
	    foreach ($str as $key => $value) {
	    	if($size > 0 && $key >= $start){
	    		$str[$key] = '*';
	    		$size--;
	    	}
	    }
	    return implode("", $str);
	}

	/**
	 * 校验是否是手机号
	 * @param  [type]  $mobile [description]
	 * @return boolean         [description]
	 */
	static function is_mobile($mobile)
	{
		return (bool) preg_match('/^1[0-9]{10}$/', (string) $mobile);
	}

	/**
	 * 字符串截取，支持中文和其他编码
	 * @static
	 * @access public
	 * @param string $str 		需要转换的字符串
	 * @param string $start 	开始位置
	 * @param string $length 	截取长度
	 * @param string $charset 	编码格式
	 * @param string $suffix 	截断显示字符
	 * @return string
	 */
	static function msubstr($str, $start = 0, $length = 1, $charset = "utf-8", $suffix = true)
	{
		if(function_exists("mb_substr")){
	        if($suffix && strlen($str) > $length){
	            return mb_substr($str, $start, $length, $charset) . ((strlen($str) / 3) > $length ? "..." : "");
	        }else{
	    		return mb_substr($str, $start, $length, $charset);
	    	}
	    }elseif(function_exists('iconv_substr')){
	        if ($suffix && strlen($str) > $length){
	            return iconv_substr($str, $start, $length, $charset) . "...";
	        }else{
	            return iconv_substr($str, $start, $length, $charset);
	        }
	    }
	    $re['utf-8']   	= "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	    $re['gb2312'] 	= "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	    $re['gbk']    	= "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	    $re['big5']   	= "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	    preg_match_all($re[$charset], $str, $match);
	    $slice = implode("", array_slice($match[0], $start, $length));
	    if($suffix){
	    	return $slice . '...';
	    }else{
	    	return $slice;
	    }
	}
}