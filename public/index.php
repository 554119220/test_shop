<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
$stop   = '/www/download/stop.html';
if (is_file($stop)) {
    $html   = file_get_contents($stop);
    exit($html);
}
$ds     = DIRECTORY_SEPARATOR;
//$dirs   = realpath(__DIR__);
$root   = str_replace('/public', '', $_SERVER['DOCUMENT_ROOT']);
//$root   = realpath(__DIR__) . '/../';
//echo $dirs . '<br />';
//App path
define('APP_PATH', "{$root}{$ds}app{$ds}");
//echo APP_PATH;
//config path
define('CONF_PATH', "{$root}{$ds}config{$ds}");
//echo CONF_PATH;
//runtime path
define('RUNTIME_PATH', "{$root}{$ds}runtime{$ds}");
#   线上
//define('RUNTIME_PATH', "/www/logs/runtime/");
define('ADDONS_PATH', "{$root}{$ds}addons{$ds}");
#   root
define('ROOT', $root);
//echo APP_PATH;
// 加载框架引导文件
// 加载框架引导文件
require "{$root}{$ds}thinkphp/start.php";
