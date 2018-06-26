<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2017-12-27 10:46:20
 */
namespace app\work\validate;
use think\Validate;
class GoodsParamsGroup629 extends Validate
{
    protected $rule = array (
  'params_group_name' => 'require',
  'category_id' => 'require',
  'params_group_form_type' => 'require',
);

    protected $message = array (
  'params_group_name.require' => '参数分组名必填',
  'category_id.require' => '所属类目必填',
  'params_group_form_type.require' => '参数类型必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'params_group_name',
    1 => 'category_id',
    2 => 'params_group_form_type',
  ),
  'edit' => 
  array (
    0 => 'params_group_name',
    1 => 'category_id',
    2 => 'params_group_form_type',
  ),
);

}
