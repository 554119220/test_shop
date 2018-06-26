<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-12-14 11:17:46
 */
use app\common\traits\F as Fun;
use mercury\constants\State;
class ShopGoodsCategory extends \think\Validate
{
    protected $rule = [
        'category_id'           => [ 'require' ],
        'goods_category_name'   => [ 'require' ],
        'goods_category_sid'    => [ 'require', 'integer', 'egt' => 0 ],
        'goods_category_icon'   => [ 'require' ],
        'goods_category_sort'   => [ 'require', 'integer', 'egt' => 0, 'elt' => 9999 ],
        'goods_category_state'  => [ 'require', 'CheckState' => '' ],
    ];

    protected $field = [
        'goods_category_sort' => '分类排序',
        'goods_category_sid' => '父级分类',
    ];

    protected $message = [
        'category_id.require' => '分类ID必须',

        'goods_category_name.require' => '分类名称必须',

        'goods_category_icon.require' => '分类图标必须',

        'goods_category_sid.require' => '父级分类必须',

        'goods_category_state.require' => '状态必须',


    ];


    public $scene = [
        'index' => [],
        'create' => [
            'goods_category_name',
            'goods_category_sort',
            // 'goods_category_icon',
            'goods_category_sid',
            'goods_category_state'
        ],
        'update' => [
            'category_id',
            'goods_category_name',
            'goods_category_sort',
            // 'goods_category_icon',
            'goods_category_sid',
            'goods_category_state'
        ],
        'detail' => [ 'category_id' ],
        'delete' => [ 'category_id' ],
    ];

    function CheckState($value,$rule)
    {
        if (false == in_array($value,[State::STATE_DELETE,State::STATE_NORMAL])) {
            return '分类状态错误';
        }
        return true;
    }
}