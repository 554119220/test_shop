<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31 0031
 * Time: 9:37
 */

namespace app\common\behavior;

use app\common\traits\F;
use mercury\constants\State;

/**
 * Class Debug
 * @package app\common\behavior
 *
 * 针对某个用户/ip开启debug模式，方便找出错误信息
 */
class Debug
{
    /**
     * 针对的用户，请填写用户昵称
     */
    const OPEN_DEBUG_USERS  = [

    ];

    const ALLOWS    = [
        1   => '所有人均可访问',
        2   => '所有人均不可访问',
        3   => '仅白名单能访问',
        4   => '仅黑名单不可访问',
    ];

    /**
     * 针对的IP地址
     */
    const OPEN_DEBUG_IPS    = [
        '127.0.0.1'
    ];

    protected $debug    = [], $request = [], $ip;
    public function __construct()
    {
        $this->debug    = config('site.debug');
        $this->request  = config('site.request_limit');
        $this->ip       = request()->ip();
    }


    public function run(&$params)
    {
        /**
         * 如果为测试账号，则开启debug/trace及显示错误信息
         */
//        $ip = request()->ip();
//        if (in_array(session('user.nick'), self::OPEN_DEBUG_USERS) ||
//            in_array($ip, self::OPEN_DEBUG_IPS) ||
//            session('admin') ||
//            strpos($ip, '192.168') === 0) {
//            config('app_debug', true);
//            config('app_trace', true);
//            config('show_error_msg', true);
//            //错误显示页面设置
//            $think_path = THINK_PATH;
//            $ds         = DS;
//            config('exception_tmpl', "{$think_path}tpl{$ds}think_exception.tpl");
//        }
        #   判断是否能够访问
        if (false === $this->request() &&
            strpos(request()->server('REQUEST_URI'), '/work') !== 0 &&
            strpos(request()->server('HTTP_HOST'), 'work') !== 0) {
            F::goto404('opening');
        }
        #   判断是否需要开启debug模式
        if (true === $this->debug()) {
            config('app_debug', true);
            config('app_trace', true);
            config('show_error_msg', true);
            //错误显示页面设置
//            $think_path = THINK_PATH;
//            $ds         = DS;
            $tpl    = sprintf('%stpl%sthink_exception.tpl', THINK_PATH, DS);
            config('exception_tmpl', $tpl);
        }
        //是否需要重置404页面，是否需要针对wap或者PC端显示界面
        //设置整站配置信息
        //config('cfg', F::cacheConfig());
    }

    /**
     * 是否能访问
     *
     * @return bool
     */
    protected function request()
    {
        switch ($this->request['request_limit_state']) {
            case 2:
                if (session('admin')) return true;
                return false;
                break;
            case 3:
                $flag   = true;
                if (!empty($this->request['request_allow_ip_prefix'])) {
                    $allow_ip_prefix= explode("\n", $this->request['request_allow_ip_prefix']);
                    foreach ($allow_ip_prefix as $v) {
                        if (strpos($this->ip, $v) === 0) {
                            return true;
                        } else {
                            $flag = false;
                        }
                    }
                }
                if (!empty($this->request['request_allow_ip'])) {
                    $allow_ips      = explode("\n", $this->request['request_allow_ip']);
                    if (in_array($this->ip, $allow_ips)) {
                        return true;
                    } else {
                        $flag = false;
                    }
                }
                if (session('user') && !empty($this->request['request_allow_user'])) {
                    $allow_users    = explode("\n", $this->request['request_allow_user']);
                    if (in_array(session('user.user_username'), $allow_users)) {
                        return true;
                    } else {
                        $flag = false;
                    }
                }
                if (false == $flag && session('user')) return false;
                break;
            case 4:
                #   禁止访问
                if (!empty($this->request['request_deny_ip_prefix'])) {
                    $deny_ip_prefix = explode("\n", $this->request['request_deny_ip_prefix']);
                    foreach ($deny_ip_prefix as $v) {
                        if (strpos($this->ip, $v) === 0) return false;
                    }
                }
                if (!empty($this->request['request_deny_ip'])) {
                    $deny_ips       = explode("\n", $this->request['request_deny_ip']);
                    if (in_array($this->ip, $deny_ips)) return false;
                }

                if (session('user') && !empty($this->request['request_deny_user'])) {
                    $deny_users     = explode("\n", $this->request['request_deny_user']);
                    if (in_array(session('user.user_username'), $deny_users)) return false;
                }
                return true;
        }
        return true;
    }

    /**
     * 是否开启debug模式
     *
     * @return bool
     */
    protected function debug()
    {
        #   IP前缀判断
        if (!empty($this->debug['debug_allow_ip_prefix'])) {
            $allow_ip_prefix    = explode("\n", $this->debug['debug_allow_ip_prefix']);
            foreach ($allow_ip_prefix as $v) {
                if (strpos($this->ip, $v) === 0) return true;
            }
        }

        #   IP判断
        if (!empty($this->debug['debug_allow_ip'])) {
            $allow_ips          = explode("\n", $this->debug['debug_allow_ip']);
            if (in_array($this->ip, $allow_ips)) return true;
        }

        #   用户判断
        if (session('user') && !empty($this->debug['debug_allow_user'])) {
            $allow_users    = explode("\n", $this->debug['debug_allow_user']);
            if (in_array(session('user.user_username'), $allow_users)) return true;
        }
        if ($this->debug['debug_allow_employee'] == State::STATE_NORMAL && session('admin')) return true;
        return false;
    }
}