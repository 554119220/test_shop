<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2017-08-05 15:17:23
 */
namespace app\work\validate;
use think\Validate;
class Department24 extends Validate
{
    protected $rule = array (
  'status' => 'require',
  'department' => 'require',
);

    protected $message = array (
  'status.require' => '状态必填',
  'department.require' => '部门名称必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'status',
    1 => 'department',
  ),
  'edit' => 
  array (
    0 => 'status',
    1 => 'department',
  ),
);

}
