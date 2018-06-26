<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30 0030
 * Time: 15:47
 */

namespace app\common\traits;

/**
 * Trait Api
 * @package app\common\method
 *
 * 针对API模块的一些方法
 */
trait Api
{
    /**
     * 生成签名或者验证签名
     *
     * @param $data
     * @return bool|string
     */
    public static function sign($data)
    {
        $sign   = '';
        //如果存在sign
        if (isset($data['sign'])) {
            $sign   = $data['sign'];
            unset($data['sign']);
        }
        //排序
        $data   = ksort($data);
        //格式化数组
        $data   = http_build_query($data);
        //生成签名字符串
        $data   = ucwords(md5($data));
        //如果sign不为空则判断是否正确
        if (!empty($sign)) return $sign == $data;
        return $data;
    }

    public static function getUserInfo($openid)
    {
        //请缓存用户信息
        return $openid;
    }

    public static function getAppInfo($params)
    {
        //请缓存app信息
        return $params;
    }

    /**
     * 获取header auth
     *
     * @param $module
     * @param $action
     * @param int $app_id
     * @return mixed
     */
    public static function headerAuth($module, $action, $app_id = 1)
    {
        //通过appid获取secret
        $secret = '';
        $session_id = $_COOKIE['PHPSESSID'];
        $str    =  "{$session_id}_{$module}_{$action}_{$secret}";
        return self::createStr($str);
    }
}