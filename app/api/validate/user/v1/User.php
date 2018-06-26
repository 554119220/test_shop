<?php
namespace app\api\validate\user\v1;


class User extends \think\Validate
{
    protected $rule = [
        'user_mobile' => ['require', 'regex' => '/^1[0-9]{10}$/', 'unique' => 'user'],
        'user_password' => ['require', 'confirm' => 're_user_password'],
        'user_username' => ['require', 'regex' => '/^.*?$/', 'unique' => 'user'],
    ];


    protected $message = [
        'user_mobile.require' => '手机号码必须',
        'user_mobile.regex' => '手机号码格式错误',
        'user_mobile.unique' => '手机号已被注册',


        'user_username.require' => '用户名必须',
        'user_username.regex' => '用户名格式错误',
        'user_username.unique' => '用户名已被注册',


        'user_password.require' => '用户密码必须',
        'user_password.confirm' => '两次密码不一致',


    ];


    protected $scene = [
        'register' => ['user_mobile', 'user_username', 'user_password'],
    ];

    /**
     * 登陆验证
     *
     * @param array $data
     * @return array|bool
     */
    public function login(array $data)
    {
        $this->rule($this->rule);
        $this->message($this->message);
        if (!$this->check($data)) {
            return $this->getError();
        }
        return true;
    }

    /**
     * 注册验证
     *
     * @param $data
     * @return array|bool
     */
    public function register(array $data)
    {
        $this->rule($this->rule);
        $this->message($this->message);
        $this->scene($this->scene);
        if (!$this->check($data)) {
            return $this->getError();
        }
        return true;
    }
}