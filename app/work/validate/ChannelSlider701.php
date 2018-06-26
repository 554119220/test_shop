<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-02-01 17:26:02
 */
namespace app\work\validate;
use think\Validate;
class ChannelSlider701 extends Validate
{
    protected $rule = array (
  'channel_images' => 'require',
);

    protected $message = array (
  'channel_images.require' => '图片必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'channel_images',
  ),
  'edit' => 
  array (
    0 => 'channel_images',
  ),
);

}
