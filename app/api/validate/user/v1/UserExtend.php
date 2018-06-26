<?php
namespace app\api\validate\user\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2018-01-03 17:50:32
 */
use mercury\constants\State;
class UserExtend extends \think\Validate
{
    protected $rule = [
        'user_birthday' => [ 'require', 'checkUserBirthday' => ''],
        'user_sex'      => [ 'require', 'checkUserSex' => ''],
        'user_type'     => [ 'require', 'checkUserType' => ''],
        // 'user_born'     => [ 'require', 'checkUserBorn' => ''],
        'user_category' => [ 'require', 'array', 'checkUserCategory' => ''],
    ];


    protected $message = [
        'user_birthday.require' => '生日必须选择',

        'user_sex.require'      => '性别必须选择',

        'user_type.require'     => '身份必须选择',

        // 'user_born.require'     => '出生年代必须选择',

        'user_category.require' => '偏好必须选择一个',
        'user_category.array'   => '偏好错误',


    ];


    public $scene = [
        'detail'        => [],
        'create'        => [ 'user_birthday', 'user_sex', 'user_type', 'user_category' ],
        'update'        => [ 'user_birthday', 'user_sex', 'user_type', 'user_category' ],
        'loveCategory'  => [ 'user_birthday' ],
    ];

    function checkUserBirthday($value,$rule)
    {
        if ( false == strtotime($value) ) {
            return '生日错误';
        }
        // dump($value);
        if ( date('Y', strtotime($value)) < 1950 || date('Y', strtotime($value)) > date('Y') ) {
            return '生日错误';
        }
        return true;
    }

    function checkUserSex($value,$rule)
    {
        if ( false ==  isset( State::USER_EXTEND_SEX_ARRAYS[$value]) ) {
            return '性别错误';
        }
        return true;
    }

    function checkUserType($value,$rule)
    {
        if ( false ==  isset( State::USER_EXTEND_TYPE_ARRAYS[$value]) ) {
            return '身份错误';
        }
        return true;
    }

    function checkUserCategory($value,$rule)
    {
        $count = count($value);
        foreach ($value as $cid) {
            if ( false == preg_match('/^[1-9][0-9]{0,}$/', $cid) ) {
                return '偏好错误2';
            }
        }
        if ( $count > 10 ){
            return '偏好最多选择10个';
        }
        if ( $count != db('goods_category')->where([ 'category_id' => [ 'in', $value ] ])->count() ) {
            return '偏好错误3';
        }
        return true;
    }
}