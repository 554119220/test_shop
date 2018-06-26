<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 图片截图
 *
 * @param $images
 * @param $w
 * @param string $h
 * @return string
 */
function thumb($images, $w, $h = '') {
    if (!$h) $h = $w;
    return \app\common\traits\F::thumbnail($images, $w, $h);
}

/**
 * 获取图片域名
 *
 * @return mixed
 */
function getImagesDomain($images = '') {
    if (!$images) return '';
    $domain = \app\common\traits\F::getImagesDomain();
    return "{$domain}{$images}";
}

/**
 * @title domain
 * @param $domain
 * @param string $path
 * @return string
 */
function domain($domain, $path = '') {
    return \app\common\traits\F::domain($domain, $path);
}

/**
 * @title searchFilter
 * @param $data
 * @param $type
 * @return mixed
 */
function searchFilter($data, $type) {
    return \mercury\filter\Filter::instance($data, $type)->run();
}

/**
 * @title urlFilter
 * @param $data
 * @return mixed
 */
function urlFilter($data) {
    return \mercury\filter\Filter::instance($data)->toString();
}

/**
 * @title hook 插件
 * @param $class
 * @param $methods
 * @param $params
 * @return mixed
 */
function hook($class, $methods, $params) {
    return \think\Hook::exec($class, $methods, $params);
}