<?php
/**
 * Work日志
 */
namespace app\work\behavior;
use app\work\controller\Common;
use think\Request;
class WorkLog extends Common
{
    //定义此预加载，即可直接跳过Init中的_initialize()
    public function _initialize()
    {

    }

    public function run(&$params='')
    {
        $this->log_write($params);
    }

    public function log_write($params){
        $post = request()->post();
        if(isset($post['password'])) $post['password'] = '*****';
        if(isset($post['load_psw'])) $post['load_psw'] = '*****';
        if(isset($post['safe_psw'])) $post['safe_psw'] = '*****';

        $logs   = [
            'atime'             => date('Y-m-d H:i:s'),
            'employee_id'       => session('admin.id'),
            'employee_account'  => session('admin.account'),
            'employee_name'     => session('admin.name'),
            'ip'                => request()->ip(),
            'controller'        => request()->controller(),
            'action'            => request()->action(),
            'url'               => request()->url(),
            'post'              => !empty($post) ? var_export($post,true) : '',
            'get'               => !empty(request()->get()) ? var_export(request()->get(),true) : '',
            //'param'             => var_export(request()->param(),true),
        ];

        mongo_insert('admin_log_'.date('ym'),$logs);
    }


}
