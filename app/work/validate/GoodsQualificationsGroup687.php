<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-01-31 15:25:18
 */
namespace app\work\validate;
use think\Validate;
class GoodsQualificationsGroup687 extends Validate
{
    protected $rule = array (
  'goods_qualifications_group_name' => 'require',
  'goods_qualifications_group_state' => 'require',
  'goods_qualifications_group_form_type' => 'require',
  'category_id' => 'require',
);

    protected $message = array (
  'goods_qualifications_group_name.require' => '名称必填',
  'goods_qualifications_group_state.require' => '状态必填',
  'goods_qualifications_group_form_type.require' => '资质类型必填',
  'category_id.require' => '分类ID必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'goods_qualifications_group_name',
    1 => 'goods_qualifications_group_state',
    2 => 'goods_qualifications_group_form_type',
    3 => 'category_id',
  ),
  'edit' => 
  array (
    0 => 'goods_qualifications_group_name',
    1 => 'goods_qualifications_group_state',
    2 => 'goods_qualifications_group_form_type',
    3 => 'category_id',
  ),
);

}
