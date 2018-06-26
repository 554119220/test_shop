<?php
namespace app\work\controller;
use app\work\controller\Init;

class Common extends Init
{
    public function _initialize()
    {
        parent::_initialize();

        if(empty(session('admin'))) {
            if ($this->request->isAjax()) {
                return json(['code' => 0,'msg' => '请先登录！']);
            } else {
                //$this->redirect('/login');
                $js='<script>top.location.href="/login";</script>';
                echo $js;
                exit();
            }
        }
    }
}
