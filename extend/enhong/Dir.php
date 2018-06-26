<?php
/**
 * 简单目录操作类
 * 部分服务器可能会禁用scandir,所以使用readdir
 */
namespace enhong;
class Dir
{
	private $path	= '.';	//操作目录
	function __construct($path='.',$pattern='*')
	{
		$this->path = $path;
	}

	/**
	 * 扫描目录
	 * @param string $path
	 * @param array $ext
	 * @return array
	 */
	function scan_dir($path='',$ext=array()){
		$files 	= [];
		$path 	= $path ? $path : $this->path;
		$handle = opendir($path);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "." && $file != "..")
			{
				$file = $path.'/'.$file;
				if(is_dir($file)){
					$files = array_merge($files,$this->scan_dir($file,$ext));
				}else{
					if(!empty($ext)){
						$e = strtolower(substr($file,-4,4));
						if(in_array($e,$ext)) $files[] = $file;
					}else{
						$files[] = $file;
					}
				}
			}
		}

		return $files;
	}

}//类定义结束

if(!class_exists('DirectoryIterator')) {
	class DirectoryIterator extends Dir {}
}
?>