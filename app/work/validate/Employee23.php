<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2017-09-22 09:41:43
 */
namespace app\work\validate;
use think\Validate;
class Employee23 extends Validate
{
    protected $rule = array (
  'group_id' => 'require',
  'account' => 'require',
  'psw' => 'require',
  'status' => 'require',
  'name' => 'require',
  'sex' => 'require',
  'nation' => 'require',
  'birthday' => 'require',
  'educational' => 'require',
  'department' => 'require',
  'title' => 'require',
  'hiredate' => 'require',
  'province' => 'require',
  'address' => 'require',
  'openid' => 'require',
);

    protected $message = array (
  'group_id.require' => '角色组必填',
  'account.require' => '账户必填',
  'psw.require' => '密码必填',
  'status.require' => '状态必填',
  'name.require' => '姓名必填',
  'sex.require' => '性别必填',
  'nation.require' => '民族必填',
  'birthday.require' => '出生日期必填',
  'educational.require' => '学历必填',
  'department.require' => '部门必填',
  'title.require' => '职位必填',
  'hiredate.require' => '入职时间必填',
  'province.require' => '省份必填',
  'address.require' => '地址必填',
  'openid.require' => '用户openid必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'group_id',
    1 => 'account',
    2 => 'psw',
    3 => 'status',
    4 => 'name',
    5 => 'sex',
    6 => 'nation',
    7 => 'birthday',
    8 => 'educational',
    9 => 'department',
    10 => 'title',
    11 => 'hiredate',
    12 => 'province',
    13 => 'address',
    14 => 'openid',
  ),
  'edit' => 
  array (
    0 => 'group_id',
    1 => 'account',
    2 => 'psw',
    3 => 'status',
    4 => 'name',
    5 => 'sex',
    6 => 'nation',
    7 => 'birthday',
    8 => 'educational',
    9 => 'department',
    10 => 'title',
    11 => 'hiredate',
    12 => 'province',
    13 => 'address',
    14 => 'openid',
  ),
);

}
