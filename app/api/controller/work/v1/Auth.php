<?php
namespace app\api\controller\work\v1;
use app\api\controller\work\v1\Init;
use app\common\traits\F;

class Auth extends Init
{
    /**
     * name:创建接口Token
     * api:work.v1.auth/token
     * day:2017-08-10
     * author:lazycat
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <appid>      <int>    <1>     <应用ID>
     * <access_key> <int>    <1>     <应用access_key>
     * <secret_key> <int>    <1>     <应用secret_key>
     * <sign_code>  <int>    <1>     <应用sign_code>
     * <device_id>  <int>    <1>     <设备ID,PC请传入session_id>
     * <ip>         <int>    <0>     <IP>
     */
    public function token($check=1){
        $this->attr = ['R'];
        if($check == 1) {
            $res = $this->check('appid,access_key,secret_key,sign_code,device_id',false);
            if($res['code'] != 1) return $this->ret($res);
        }

        $where = [
            'id'            => $this->post['appid'],
            'access_key'    => $this->post['access_key'],
            'secret_key'    => $this->post['secret_key'],
            'sign_code'     => $this->post['sign_code'],
        ];
        $rs = db('app_client')->where($where)->field('id,terminal,status')->find();
        if($rs['status'] != 1) return $this->ret(['code' => 0,'msg' => '您的应用接口已被停用！']);

        db('app_client')->where(['id' => $rs['id']])->setInc('num',1,600);

        $where['device_id'] = $this->post['device_id'];
        $where['terminal']  = $rs['terminal'];
        $where['ip']        = isset($this->post['ip']) ? $this->post['ip'] : request()->ip();
        $where['token']     = md5(serialize($where));
        $cache_name         = $this->token_prefix . $where['token'];
        $where['atime']     = date('Y-m-d H:i:s');

        cache($cache_name,$where,1200);
        return $this->ret(['code' => 1,'data' => $where]);
    }

}
