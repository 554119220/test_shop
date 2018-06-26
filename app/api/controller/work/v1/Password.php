<?php
namespace app\api\controller\work\v1;
use app\api\controller\work\v1\Init;
class Password extends Init
{
    /**
     * name:修改雇员登录密码
     * api:work.v1.password/changePassword
     * day:2017-10-12
     * author:jojojing
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <openid>             <string>        <1>     <雇员openid>
     * <old_password>       <string>        <1>     <旧密码加密串>
     * <password>           <string>        <1>     <新密码加密串>
     */
    public function changePassword($check=1){
        $this->attr = ['U'];
        if($check == 1) {
            $res = $this->check('openid,old_password,password');
            if($res['code'] != 1) return $this->ret($res);
        }

        $admin = db('employee')->where(['openid' => $this->post['openid']])->field('id,status,psw')->find();

        if($admin){
            if($admin['status'] != 1) return $this->ret(['code' => 0,'msg' => '雇员账号存在异常！']);
            if($admin['psw'] !== $this->post['old_password']) return $this->ret(['code' => 0,'msg' => '旧密码错误！']);

            if(db('employee')->where(['openid' => $this->post['openid']])->update(['psw' => $this->post['password']])) return $this->ret(['code' => 1,'msg' => '修改成功！']);
            return $this->ret(['code' => 0,'msg' => '修改失败！']);
        }
        return $this->ret(['code' => 0,'msg' => '雇员不存！']);
    }
}
