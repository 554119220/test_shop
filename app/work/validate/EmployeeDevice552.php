<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2017-10-09 15:24:51
 */
namespace app\work\validate;
use think\Validate;
class EmployeeDevice552 extends Validate
{
    protected $rule = array (
  'status' => 'require',
  'device_type' => 'require',
  'account' => 'require',
  'device_id' => 'require',
);

    protected $message = array (
  'status.require' => '状态必填',
  'device_type.require' => '设备类型必填',
  'account.require' => '雇员必填',
  'device_id.require' => '设备ID必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'status',
    1 => 'device_type',
    2 => 'account',
    3 => 'device_id',
  ),
  'edit' => 
  array (
    0 => 'status',
    1 => 'device_type',
    2 => 'account',
    3 => 'device_id',
  ),
);

}
