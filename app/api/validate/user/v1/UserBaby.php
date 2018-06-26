<?php
namespace app\api\validate\user\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2018-01-04 17:57:09
 */
use mercury\constants\State;
class UserBaby extends \think\Validate
{
    protected $rule = [
        'user_baby_birthday'    => [ 'require', 'checkBabyBirthday' => '' ],
        'user_baby_sex'         => [ 'require', 'checkBabySex' => '' ],
        'user_baby_name'        => [ 'require', 'max' => 15 ],
        'user_baby_nick'        => [ 'require', 'max' => 15 ],
    ];

    protected $field = [
        'user_baby_sex'         => '宝宝性别',

        'user_baby_birthday'    => '宝宝生日',

        'user_baby_nick'        => '宝贝昵称',

        'user_baby_name'        => '宝贝姓名',

    ];


    


    public $scene = [
        'index' => [],
        'detail' => [ 'user_baby_id' ],
        'create' => [ 'user_baby_sex', 'user_baby_birthday', 'user_baby_nick', 'user_baby_name' ],
        'update' => [ 'user_baby_id', 'user_baby_sex', 'user_baby_birthday', 'user_baby_nick', 'user_baby_name' ],
    ];

    function checkBabySex($value,$rule)
    {
        if ( false ==  isset( State::USER_BABY_SEX_ARRAYS[$value]) ) {
            return '宝贝性别错误';
        }
        return true;
    }

    function checkBabyBirthday($value, $rule)
    {
        if ( false == strtotime($value) ) {
            return '宝贝生日错误';
        }
        // dump($value);
        if ( date('Y', strtotime($value)) < (date('Y') - 10) ) {
            return '宝贝生日错误2';
        }
        return true;
    }
}