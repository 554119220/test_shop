<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Common;
class Password extends Common
{
    public function index(){

        return view();
    }

    public function changePassword(){

        $old_password   = isset($this->post['old_password']) ? trim($this->post['old_password']) : '';
        $password       = isset($this->post['password']) ? trim($this->post['password']) : '';
        $password2      = isset($this->post['password2']) ? trim($this->post['password2']) : '';

        if(empty($old_password)){
            return ['code' => 0, 'msg' => '旧密码不能为空'];
        }
        if(empty($password)){
            return ['code' => 0, 'msg' => '新密码不能为空'];
        }
        if(empty($password2)){
            return ['code' => 0, 'msg' => '请输入确认密码'];
        }
        if($password != $password2){
            return ['code' => 0, 'msg' => '两次密码输入不一致'];
        }

        $data['openid']         = session('admin.openid');
        $data['old_password']   = md5_pwd($this->post['old_password']);
        $data['password']       = md5_pwd($this->post['password']);
        $res = api('password/changePassword',$data);

        if($res['code'] == 1){
            session('admin',null);
        }

        return $res;
    }

}
