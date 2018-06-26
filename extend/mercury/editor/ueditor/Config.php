<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8 0008
 * Time: 19:10
 */

namespace mercury\editor\ueditor;

/**
 * Class Config
 * @package mercury\editor
 *
 * 配置信息
 */
class Config
{
    public function getConfig()
    {
        //return include "config.json";
        $dir    = realpath(__DIR__);
        $ds     = DS;
        return json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("{$dir}{$ds}config.json")), true);
    }
}