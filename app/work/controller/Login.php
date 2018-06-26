<?php
namespace app\work\controller;
use app\common\traits\F;
use app\work\controller\Init;
use enhong\MyQrcode;

class Login extends Init
{
    public function index()
    {
        return view();
    }

    public function checkLogin(){
        $msg = $this->validate($this->post,'Login');
        if(true !== $msg) return ['code' => 0,'msg' => $msg];

        //if(captcha_check($this->post['vcode']) !== true) return ['code' => 10,'msg' => '验证码错误！'];
        $this->post['password'] = md5_pwd($this->post['password']);

        //config('api_debug',true);
        $res = api('Admin/login',$this->post);
        if($res['code'] == 1){
            session('admin',$res['data']);
        }
        return $res;
    }

    public function logout(){
        session('admin',null);
        return redirect('/login');
    }

    /**
     * 接口参数
     * @return array
     */
    private function api_cfg()
    {
        $data = [
            'appid'         => 1,
            'access_key'    => '1f982aa4178c278c95529e28b0f1b20f',
            'secret_key'    => 'cfdb08305ddf31113eed7d6bd7c6ce94',
            'sign_code'     => 'a35bbf3cf3cb98f91bc748f4660127ee',
        ];
        return $data;
    }

    /**
     * 验证码
     */
    public function vcode(){
        return captcha_src();
    }

    /**
     * 雇员扫码登录预数据处理
     * 2017-09-27
     * jojojing
     */
    public function qcode( $check=1 ){
        //扫码登陆预数据处理
        /*
        $cacheKey = 'scanCodeLogin_'.md5(session_id().'_zrst2017');
        cache($cacheKey,array('status'=>1, 'session_id' => md5(session_id().'_zrst2017'),'openid'=>''),60);

        //扫码登陆
        $data = 'scLogin_'.md5(session_id().'_zrst2017').'-'.time();
        $result = MyQrcode::create_qrcode($data,false);

        //直接输出文件流
        echo $result; die;*/
        $cacheKey = 'scanCodeLogin_'.md5(session_id().'_zrst2017');
        cache($cacheKey,array('status'=>1, 'session_id' => md5(session_id().'_zrst2017'),'openid'=>''),60);

        //扫码登陆
        $data = 'scLogin_'.md5(session_id().'_zrst2017').'-'.time();
        echo F::qrCode($data);

    }

    /**
     * 雇员扫码登录
     * 2017-09-27
     * jojojing
     */
    public function scanCodeLogin(){
        //扫码登陆数据处理
        $cacheKey  = 'scanCodeLogin_'.md5(session_id().'_zrst2017');
        $cacheData = cache($cacheKey);

        if( $cacheData['openid'] ){
            /* 通过openid查询是否存在当前雇员 */
            $employInfo = db('employee')->where(['openid' => $cacheData['openid']])->field('account,psw')->find();
            if(!$employInfo) return [ 'code' => 0, 'msg' => '扫码登录失败' ];

            $param = array( 'username' => $employInfo['account'], 'password' => $employInfo['psw'] );
            $loginInfo = api('admin/login',$param);

            if($loginInfo['code'] != 1){
                return ['code' => 0, 'msg' => $loginInfo['data']];
            }else{
                session('admin',$loginInfo['data']); //写入会话
                return ['code' => 1, 'msg' => '扫码登录成功'];
            }
        }

        return [ 'code' => 0, 'msg' => '扫码登录失败' ];
    }

//    /*检测接口*/
//    public function checkLogins(){
//        $rs = api('admin/loginApp',['username' => 'zr00006','password'=>'e5121c074003c8f9424854951ecd7afd','device_token'=>'bf81e0ae2bc8b38e28a76050c27dd478']);
//
//        $ca = cache('work_login_'.md5($rs['data']['openid']));
//        dump($ca);die;
//    }

}
