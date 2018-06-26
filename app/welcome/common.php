<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 15:02
 */
/**
 * 域名
 *
 * @param $domain
 * @param string $path
 * @return string
 */
if (!function_exists('domain')) {
    function domain($domain, $path = '') {
        return \app\common\traits\F::domain($domain, $path);
    }
}