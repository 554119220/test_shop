<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-24 14:31:19
 */
use app\common\traits\F as Fun;
use mercury\constants\State;
use app\api\model\goods\GoodsQualifications as Model;

class GoodsQualifications extends \think\Validate
{
    protected $model = null;
    protected $group = [];
    protected $groupValue = [];
    protected $rule = [
        'qualifications_id'         => [ 'require', 'checkGroup' ],
        'qualifications_name'       => [ 'require', 'checkGroupName' ],
        'qualifications_value'      => [ 'require', 'array', 'checkValue' ],

        'goods_id'          => [ 'require' ],
    ];


    protected $message = [
        'qualifications_id.require'    => '产品资质必须',
        'qualifications_id.checkGroup' => '产品资质组错误',

        'qualifications_name.require'        => '产品资质名必须',
        'qualifications_name.checkGroupName' => '产品资质名错误1',

        'qualifications_value.require'       => '产品资质值必须',
        'qualifications_value.array'         => '产品资质值错误2',
        'qualifications_value.checkValue'    => '产品资质值错误3',

        'goods_id.require'          => '商品id必须',
    ];


    public $scene = [
        'create' => [
            'qualifications_id',
            'qualifications_name',
            'qualifications_value',
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
        // echo 2;
        $three  = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), request()->data['goods_category_id']);
        $two    = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), $three['category_sid'] ?? 0);
        $one    = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), $two['category_sid'] ?? 0);
        $threeId    = $three['category_id'] ?? 0;
        $twoId      = $two['category_id'] ?? 0;
        $oneId      = $one['category_id'] ?? 0;
        $this->group =  Fun::dataDetail(Fun::mApi('goods', 'GoodsQualificationsGroup'), intval($value));
        // dump($value);
        // group 不在三级类目里面
        if ( empty($this->group) || $this->group['category_id'] <= 0 || false == in_array($this->group['category_id'], [ $threeId, $twoId, $oneId ]) ) {
            return false;
        }
        return true;
    }

    function checkGroupName($value, $rule)
    {
        if ( $value != ($this->group['goods_qualifications_group_name'] ?? 0) ) {
            return false;
        }
        return true;
    }

    function checkValue($value, $rule)
    {
        // dump($this->group);
        switch ($this->group['goods_qualifications_group_form_type']) {
            # input 
            case State::GOODS_QUALIFICATIONS_GROUP_TYPE_INPUT:
            if ( false == isset($value[0]) || empty($value[0]) || count($value) > 1 || false == is_string($value[0]) ) {
                    return '请填写产品资质';
                }
                break;
            # select
            case State::GOODS_QUALIFICATIONS_GROUP_TYPE_SELECT:
                if ( false == isset($value[0]) || empty($value[0]) || count($value) > 1 || false == is_string($value[0]) ) {
                    return '请选择产品资质';
                }
                if ( false == in_array($value[0], $this->group['goods_qualifications_group_value']) ) {
                    return '请选择产品资质';
                }
                break;
            # checkbox
            case State::GOODS_QUALIFICATIONS_GROUP_TYPE_CHECKBOX:
                foreach (array_values($value) as $name) {
                    if ( empty($name) || false == is_string($name) ) {
                        return '请选择产品资质';
                    }
                }
                // dump($value);
                if ( false == in_array($name, $this->group['goods_qualifications_group_value']) ) {
                    return '请选择产品资质';
                }
                break;
            # textarea
            case State::GOODS_QUALIFICATIONS_GROUP_TYPE_TEXTAREA:
                if ( false == isset($value[0]) || empty($value[0]) || count($value) > 1 || false == is_string($value[0]) ) {
                    return '请填写产品资质';
                }
                break;
            #upload_img
            case State::GOODS_QUALIFICATIONS_GROUP_TYPE_UPLOAD_IMG:
                if ( false == isset($value[0]) || empty($value[0]) || count($value) > 1 || false == is_string($value[0]) ) {
                    return '请上传产品资质图片';
                }
                break;
            default:
                return false;
                break;
        }
        return true;
    }
}