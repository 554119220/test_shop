<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-02-01 17:03:17
 */
namespace app\work\validate;
use think\Validate;
class Channel698 extends Validate
{
    protected $rule = array (
  'channel_title' => 'require',
  'channel_name' => 'require',
  'channel_state' => 'require',
);

    protected $message = array (
  'channel_title.require' => '频道标题必填',
  'channel_name.require' => '频道名称必填',
  'channel_state.require' => '频道状态必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'channel_title',
    1 => 'channel_name',
    2 => 'channel_state',
  ),
  'edit' => 
  array (
    0 => 'channel_title',
    1 => 'channel_name',
    2 => 'channel_state',
  ),
);

}
