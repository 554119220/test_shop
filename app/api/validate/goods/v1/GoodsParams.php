<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-24 14:31:19
 */
use app\common\traits\F as Fun;
use mercury\constants\State;
use app\api\model\goods\GoodsParams as Model;
use app\api\model\goods\GoodsParamsGroupValue as ValueModel;

class GoodsParams extends \think\Validate
{
    protected $model = null;
    protected $group = [];
    protected $groupValue = [];
    protected $rule = [
        'group_id'          => [ 'require', 'checkGroup' ],
        'group_name'        => [ 'require', 'checkGroupName' ],
        'group_value'       => [ 'require', 'array', 'checkValue' ],

        'goods_id'          => [ 'require' ],
    ];


    protected $message = [
        'group_id.require'          => '产品参数必须',
        'group_id.checkGroup'       => '产品参数组错误',

        'group_name.require'        => '产品参数名必须',
        'group_name.checkGroupName' => '产品参数名错误',

        'group_value_ids.require'   => '产品参数必须选择',
        'group_value_ids.checkIds'  => '产品参数错误',

        'group_value.require'       => '产品参数值必须',
        'group_value.array'         => '产品参数值不正确',
        'group_value.checkValue'    => '产品参数值错误',

        'goods_id.require'          => '商品id必须',
    ];


    public $scene = [
        'create' => [
            'group_id',
            'group_name',
            'group_value_ids',
            'group_value',
        ],
        'detail' => [
            'goods_id',
        ],
    ];

    public function __construct(){
        parent::__construct();
        $this->model = new Model;
    }

    function checkGroup ($value, $rule)
    {
        # 一二级也需要加进来
        $three  = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), request()->data['goods_category_id']);
        $two    = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), $three['category_sid'] ?? 0);
        $one    = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), $two['category_sid'] ?? 0);
        $threeId    = $three['category_id'] ?? 0;
        $twoId      = $two['category_id'] ?? 0;
        $oneId      = $one['category_id'] ?? 0;
        $this->group =  Fun::dataDetail(Fun::mApi('goods', 'GoodsParamsGroup'), intval($value));
        // group 不在三级类目里面
        if ( empty($this->group) || $this->group['category_id'] <= 0 || false == in_array($this->group['category_id'], [ $threeId, $twoId, $oneId ]) ) {
            return false;
        }
        return true;
    }

    function checkGroupName($value, $rule)
    {
        if ( $value != ($this->group['params_group_name'] ?? 0) ) {
            return false;
        }
        return true;
    }

    function checkValue($value, $rule)
    {
        switch ($this->group['params_group_form_type']) {
            # input
            case State::GOODS_PARAMS_GROUP_TYPE_INPUT:
                if ( false == isset($value[0]) || empty($value[0]) || count($value) > 1 || false == is_string($value[0]) ) {
                    return false;
                }
                break;
            # select
            case State::GOODS_PARAMS_GROUP_TYPE_SELECT:
                if ( false == isset($value[0]) || empty($value[0]) || count($value) > 1 || false == is_string($value[0]) ) {
                    return false;
                }
                $param['where']['params_group_value_name']  = $value[0];
                $param['where']['group_id']                 = $this->group['params_group_id'];
                $param['where']['params_group_value_state'] = 1;
                $detail = Fun::dataDetail(Fun::mApi('goods','GoodsParamsGroupValue'), $param);
                if ( empty($detail) ) {
                    return false;
                }
                break;
            # checkbox
            case State::GOODS_PARAMS_GROUP_TYPE_CHECKBOX:
                foreach (array_values($value) as $name) {
                    if ( empty($name) || false == is_string($name) ) {
                        return false;
                    }
                }
                $param['where']['params_group_value_name']  = [ 'in', $value ];
                $param['where']['group_id']                 = $this->group['params_group_id'];
                $param['where']['params_group_value_state'] = 1;
                $list = Fun::dataAll(Fun::mApi('goods','GoodsParamsGroupValue'), $param);
                if ( count($value) != count($list) ) {
                    return false;
                }
                break;
            # textarea
            case State::GOODS_PARAMS_GROUP_TYPE_TEXTAREA:
                if ( false == isset($value[0]) || empty($value[0]) || count($value) > 1 || false == is_string($value[0]) ) {
                    return false;
                }
                break;
            default:
                return false;
                break;
        }
        return true;
    }
}