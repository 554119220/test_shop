<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/6 0006
 * Time: 17:46
 */

namespace app\common\behavior;

use app\common\traits\F;

/**
 * 后台操作日志记录
 *
 * Class AdminLog
 * @package app\common\behavior
 */
class AdminLog
{
    public function run(&$params)
    {
        $request_params = request()->param();
        if(isset($request_params['password'])) $request_params['password'] = '*****';
        if(isset($request_params['load_psw'])) $request_params['load_psw'] = '*****';
        if(isset($request_params['safe_psw'])) $request_params['safe_psw'] = '*****';
        $logs   = [
            'employee_id'       => session('admin.id'),
            'employee_account'  => session('admin.account'),
            'employee_name'     => session('admin.name'),
            'ip'                => request()->ip(),
            'controller'        => request()->controller(),
            'action'            => request()->action(),
            'url'               => request()->url(),
            'params'            => $request_params
        ];
        F::gearmanLogs('admin', $logs, true);
    }
}