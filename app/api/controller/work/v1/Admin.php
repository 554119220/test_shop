<?php
namespace app\api\controller\work\v1;
use app\api\controller\work\v1\Init;
use enhong\MyQrcode;
use think\Session;

class Admin extends Init
{
    /**
     * 雇员登录
     * 2017-06-01
     */
    public function login($check=1){
        $this->attr = ['U'];
        if($check == 1) {
            $res = $this->check('username,password');
            if($res['code'] != 1) return $this->ret($res);
        }

        $where = [
            'account'   => strtolower($this->post['username']),
            'psw'       => $this->post['password'],
        ];
        $rs = db('employee')->where($where)->field('atime,etime,appid',true)->find();
        if($rs){
            //取权限
            $power = db('admin_group')->where(['id' => $rs['group_id'],'status' => 1])->field('menu_id,action')->find();
            if(!$power) return $this->ret(['code' => 0,'msg' => '该用户无权限！']);

            $power['action'] = json_decode(strtolower(html_entity_decode($power['action'])),true);
            $rs['power'] = $power;

            if($rs['status'] != 1) return $this->ret(['code' => 0,'msg' => '账号已被暂停使用！']);
            $sql = 'update '.config('database.prefix').'employee set loginum=loginum+1,lastlogintime=now(),lastloginip="'.$this->request->ip().'" where id='.$rs['id'];
            db()->execute($sql);
            //session('admin',$rs);

            //获取权限
            //toDo

            return $this->ret(['code' => 1,'data' => $rs,'msg' => '登录成功！']);
        }

        return $this->ret(['code' => 0,'msg' => '账号或密码错误！']);
    }


    /**
     * name:workApp登录
     * api:work.v1.admin/loginApp
     * day:2017-09-27
     * author:jojojing
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <username>       <string>        <1>     <雇员account>
     * <password>       <string>        <1>     <密码【加密后】>
     * <device_token>   <string>        <1>     <设备ID，适用于work-APP登录>
     */
    public function loginApp($check=1){
        $this->attr = ['U'];
        if($check == 1) {
            $res = $this->check('username,password,device_token');
            if($res['code'] != 1) return $this->ret($res);
        }

        $where = [
            'account'   => strtolower($this->post['username']),
            'psw'       => $this->post['password'],
        ];
        $rs = db('employee')->where($where)->field('atime,etime,appid',true)->find();
        if($rs){

            //如果是IOS或安卓设备则检测设备
            if(isset($this->post['device_token']) && $this->post['device_token'] && in_array($this->token['terminal'],[2,3])){
                $device = db('employee_device')->where(['account' => $rs['account']])->field('id,status,device_id')->find();
                if($device){
                    if($device['statis'] != 1) db('employee_device')->where(['id' => $device['id']])->setField('status',1);
                    /* 原设备ID与现设备ID不一致，直接提示不锁定 */
                    if( $this->post['device_token'] != $device['device_id'] ) {
                        return $this->ret(['code' => 0, 'msg' => '已绑定设备，不能在再其它设备使用']);
                    }
                }else{
                    db('employee_device')->insert(['account' => $rs['account'], 'status' => 1, 'device_type' => $this->token['terminal'], 'device_id' => $this->post['device_token'] ]);
                }
            }else{
                return $this->ret(['code' => 0,'msg' => '非移动设备禁止登录！']);
            }

            //分组
            $groupName  = db('admin_group')->where(['id' => $rs['group_id']])->column('id,group_name','id');
            $rs['group_name'] = isset($groupName[$rs['group_id']]) ? $groupName[$rs['group_id']] : '-';

            //保存雇员登录状态，写入缓存
            $cache_name = 'work_login_'.md5($rs['openid']);
            cache($cache_name,$rs,3600*24); //缓存一天

            if($rs['status'] != 1) return $this->ret(['code' => 0,'msg' => '账号已被暂停使用！']);
            $sql = 'update '.config('database.prefix').'employee set loginum=loginum+1,lastlogintime=now(),lastloginip="'.$this->request->ip().'" where id='.$rs['id'];
            db()->execute($sql);

            return $this->ret(['code' => 1,'data' => $rs,'msg' => '登录成功！']);
        }

        return $this->ret(['code' => 0,'msg' => '账号或密码错误！']);
    }


    /**
     * name:work扫码登录
     * api:work.v1.admin/scanCodeLogin
     * day:2017-09-29
     * author:jojojing
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <openid>       <string>        <1>     <雇员openid>
     * <ssid>         <string>        <1>     <扫码前缀【32位】>
     */
    public function scanCodeLogin( $check = 1 ){
        if($check == 1){
            $res = $this->check('openid,ssid');
            if($res['code'] != 1) return $this->ret($res);
        }

        $cacheKey = 'scanCodeLogin_'.$this->post['ssid'];
        $cacheData = cache($cacheKey);

        if ( $cacheData[ 'status' ] == 1 ) {
            $data[ 'openid' ] = $this->admin['openid'];
            $data[ 'status' ] = 2;
            cache($cacheKey,$data,60);
            return $this->ret(['code' => 1, 'msg' => '操作成功']);

        } else {
            return $this->ret(['code' => 0, 'msg' => '二维码已失效']);
        }

    }


    /**
     * 雇员权限
     * 2017-06-25
     */
    public function power($check=1){
        $this->attr = ['R'];
        if($check == 1) {
            $res = $this->check('group_id');
            if($res['code'] != 1) return $this->ret($res);
        }

        $power = db('admin_group')->where(['id' => $this->post['group_id'],'status' => 1])->field('menu_id,action')->find();
        if(!$power) return $this->ret(['code' => 0,'msg' => '无权限！']);

        $power['action'] = json_decode(strtolower(html_entity_decode($power['action'])),true);
        return $this->ret(['code' => 1,'data' => $power]);
    }

    /**
     * 雇员扫码登录预数据处理
     * 2017-09-27
     */
    public function qcode( $check=1 ){
        Session::init();
        //扫码登陆预数据处理
        $session_id = session_id();
        $cacheKey = 'scanCode_'.md5($session_id().'_zrst2017');
        cache($cacheKey,array('status'=>1, 'session_id' => md5($session_id().'_zrst2017'),'openid'=>''),60);

        //扫码登陆
        $data = md5(session_id().'_zrst2017').'-'.time();

        $result = MyQrcode::create_qrcode($data);

        return $this->ret(['code'=>1,'data'=>$result]);
    }

}
