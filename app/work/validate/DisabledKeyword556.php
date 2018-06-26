<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2017-10-23 16:21:10
 */
namespace app\work\validate;
use think\Validate;
class DisabledKeyword556 extends Validate
{
    protected $rule = array (
  'status' => 'require',
  'category_id' => 'require',
  'keyword' => 'require',
);

    protected $message = array (
  'status.require' => '状态必填',
  'category_id.require' => '类别必填',
  'keyword.require' => '关键词必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'status',
    1 => 'category_id',
    2 => 'keyword',
  ),
  'edit' => 
  array (
    0 => 'status',
    1 => 'category_id',
    2 => 'keyword',
  ),
);

}
