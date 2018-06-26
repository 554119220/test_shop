<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 17:55
 */

namespace app\common\behavior;

use app\common\traits\F;

/**
 * 设置模板路径
 *
 * Class Template
 * @package app\common\behavior
 */
class Template
{
    public function run(&$params)
    {
        $module = request()->module();
        $path   = config('template.view_path');
        $ds     = DS;
        config('template.view_path', "{$path}{$module}{$ds}");
    }
}