<?php
namespace app\api\model\user;
use mercury\constants\state\User as UserState;
use app\common\traits\F as Fun;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-13 14:01:44
 */
use mercury\constants\State;
class User extends \think\Model
{
    protected $pk = 'user_id';
    protected $append = [ 'user_avatar_key', 'hide_mobile' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'user_create_time';
    protected $updateTime = 'user_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [ 'user_register_time', 'user_register_ip', 'user_last_login_time', 'user_last_login_ip', 'user_login_num', 'user_shop_id', 'user_avatar', 'user_state', 'user_nick', 'user_pay_password' ];
    protected $update = [];
    protected  $data = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */


    /**
     * user_register_time - 用户注册时间
     */
    protected function setUserRegisterTimeAttr($value, $data)
    {
        return time();
    }


    /**
     * user_register_ip - 用户注册IP
     */
    protected function setUserRegisterIpAttr($value, $data)
    {
        return request()->ip(1);
    }


    /**
     * user_password - 用户密码
     */
    protected function setUserPasswordAttr($value, $data)
    {
        return empty($value) ? $value : $this->passwordDeal($value);;
    }


    /**
     * user_last_login_time - 用户最后登陆时间
     */
    protected function setUserLastLoginTimeAttr($value, $data)
    {
        return time();
    }


    /**
     * user_last_login_ip - 用户最后登陆IP地址
     */
    protected function setUserLastLoginIpAttr($value, $data)
    {
        return request()->ip(1);
    }


    /**
     * user_is_auth - 用户是否已认证
     */
    protected function setUserIsAuthAttr($value, $data)
    {
        return intval($value);
    }


    /**
     * user_level - 用户等级
     */
    protected function setUserLevelAttr($value, $data)
    {
        return intval($value);
    }


    /**
     * user_login_num - 用户总登陆次数
     */
    protected function setUserLoginNumAttr($value, $data)
    {
        $value = intval($value);
        return ++$value;
    }


    /**
     * user_shop_id - 用户店铺ID
     */
    protected function setUserShopIdAttr($value, $data)
    {
        return 0;
    }


    /**
     * user_avatar - 用户头像
     */
    protected function setUserAvatarAttr($value, $data)
    {
        return empty($value) ? '' : (string) $value;
    }


    /**
     * user_state - 用户当前状态0已删除，1正常状态，2已禁用
     */
    protected function setUserStateAttr($value, $data)
    {
        $state = State::USER_STATE_ARRAYS;
        return isset($state[$value]) ? $value : State::STATE_USER_DISABLED;
    }


    /**
     * user_nick - 用户昵称
     */
    protected function setUserNickAttr($value, $data)
    {
        return empty($value) ? ($data['user_username'] ?? '') : $value;
    }


    /**
     * user_pay_password - 用户支付密码
     */
    protected function setUserPayPasswordAttr($value, $data)
    {
        return empty($value) ? '' : $this->payPasswordDeal($value);
    }




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * user_register_time - 用户注册时间
     */
    protected function getUserRegisterTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }


    /**
     * user_register_ip - 用户注册IP
     */
    protected function getUserRegisterIpAttr($value, $data)
    {
        return long2ip($value);
    }


    /**
     * user_last_login_time - 用户最后登陆时间
     */
    protected function getUserLastLoginTimeAttr($value, $data)
    {
        return date('Y-m-d H:i:s', $value);
    }


    /**
     * user_last_login_ip - 用户最后登陆IP地址
     */
    protected function getUserLastLoginIpAttr($value, $data)
    {
        return long2ip($value);
    }

    /**
     * user_avatar - 用户头像
     */
    protected function getUserAvatarAttr($value, $data)
    {
        // dump($value);exit;
        return $value ? Fun::getImages($value) : Fun::domain('www') . '/images/user_logo.jpg';
    }


    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */


    protected function getUserAvatarKeyAttr($value, $data)
    {
        return $data['user_avatar'] ?? '';
    }

    protected function getHideMobileAttr($value, $data)
    {
        return Fun::hidden_str($data['user_mobile'] ?? '***********', 3, 4);
    }

    /**
     * 密码处理
     * @param [type] $password [description]
     */
    function passwordDeal($password){
        return $password;
    }

    /**
     * 安全密码处理
     * @param [type] $password [description]
     */
    function payPasswordDeal($password){
        return $password;
    }



    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


}