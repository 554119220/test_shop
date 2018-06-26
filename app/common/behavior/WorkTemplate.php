<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21 0021
 * Time: 15:00
 */

namespace app\common\behavior;


use think\Config;

class WorkTemplate
{
    public function run(&$params)
    {
        config('template.view_path', '');
    }
}