<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2017-12-26 17:58:12
 */
namespace app\work\validate;
use think\Validate;
class GoodsCategory625 extends Validate
{
    protected $rule = array (
  'category_name' => 'require',
  'category_state' => 'require',
  'category_goods_service_days' => 'require',
);

    protected $message = array (
  'category_name.require' => '类目名称必填',
  'category_state.require' => '类目状态必填',
  'category_goods_service_days.require' => '商品售后天数必填',
);

    protected $scene = array (
  'add' => 
  array (
    0 => 'category_name',
    1 => 'category_state',
    2 => 'category_goods_service_days',
  ),
  'edit' => 
  array (
    0 => 'category_name',
    1 => 'category_state',
    2 => 'category_goods_service_days',
  ),
);

}
