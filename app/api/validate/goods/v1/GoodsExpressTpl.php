<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-12-19 22:31:22
 */
use mercury\constants\State;
use app\common\traits\F as Fun;
use mercury\factory\Factory;
class GoodsExpressTpl extends \think\Validate
{
    protected $rule = [
        'express_name'              => [ 'require' ],
        'express_remark'            => [ 'require' ],
        'express_is_free'           => [ 'require', 'checkIsFree' => '' ],
        'express_type'              => [ 'require', 'checkType' => '' ],
        'express_first_price'       => [ 'require', 'number', 'egt' => 0 ],
        'express_continue_price'    => [ 'require', 'number', 'egt' => 0 ],
        'express_id'                => [ 'require' ],
        'express_content'           => [ 'require', 'array', 'checkContent' => '' ],
        'express_ship_time'         => [ 'require', 'regex' => '/^[1-9][0-9]{0,}$/', 'elt' => 999 ],
        'express_ship_province_id'  => [ 'require', 'gt' => 0, 'integer' ],
        'express_ship_city_id'      => [ 'require', 'gt' => 0, 'integer' ],

        'sku_id'                    => [ 'require', 'checkNumber' => '' ],
        'city_id'                   => [ 'require', 'checkNumber' => '' ],
        'num'                       => [ 'require', 'checkNumber' => '' ],
        'weight'                    => [ 'require', 'checkNumber' => '' ],
    ];

    protected $field = [
        'express_name'                      => '模板名称',

        'express_remark'                    => '模板备注',

        'express_is_free'                   => '是否包邮',

        'express_type'                      => '计费方式',

        'express_first_price'               => '首件|首重 价格',

        'express_continue_price'            => '续件|续重 价格',

        'express_id'                        => '模板id',

        'express_content'                   => '快递和EMS',

        'express_ship_time'                 => '发货时间',

        'express_ship_province_id'               => '发货省份',

        'express_ship_city_id'                   => '发货城市',

        'sku_id'                            => '商品id',

        'city_id'                           => '城市id',

        'num'                               => '数量',

        'weight'                            => '重量',
    ];


    protected $message = [
        'express_is_free.checkIsFree'                   => '是否包邮错误',
        'express_type.checkType'                        => '计费方式错误',
        'express_content.checkContent'                  => '快递和EMS错误',

        'express_ship_time.regex'                       => '发货时间必须为正整数',
        'express_ship_time.elt'                         => '发货时间过长',

        'sku_id.checkNumber'                            => '商品id错误',
        'num.checkNum'                                  => '数量错误',
        'weight.checkNumber'                            => '重量错误',
        'city_id.checkNumber'                           => '城市id错误',
    ];


    public $scene = [
        'index'     => [],
        'create'    => [
            'express_name',
            'express_remark',
            'express_is_free',
            'express_ship_time',
            'express_ship_province_id',
            'express_ship_city_id',
        ],
        'update'      => [
            'express_id',
            'express_name',
            'express_remark',
            'express_is_free',
            'express_ship_time',
            'express_ship_province_id',
            'express_ship_city_id',
        ],
        'create2' => [
            'express_type',
            'express_first_price',
            'express_continue_price',
        ],
        'check_content' => [
            'express_content',
        ],
        'detail'    => [
            'express_id',
        ],
        'delete'    => [
            'express_id'
        ],
        'have_city' => [],
        'courier_fees' => [ 'sku_id', 'city_id', 'num', 'weight' ],
    ];

    function checkIsFree($value, $rule)
    {
        // dump($state);dump($value);dump(isset($state[$value]));
        $state = State::GOODS_EXPRESS_FREE_ARRAYS;
        if ( false == isset($state[$value] ) ) {
            return '是否包邮错误';
        }
        if ( $value == State::GOODS_EXPRESS_IS_FREE ) {
            $detail = Fun::dataDetail(Fun::mApi('goods','GoodsExpressTpl'),[
                'where' => [
                	'express_is_free' 	=> State::GOODS_EXPRESS_IS_FREE,
                	'shop_id' 			=> intval(request()->user['user_shop_id'] ?? 0),
                ],
                'field' => 'express_id',
            ]);
            // 排除编辑中的情况
            if ( $detail && $detail['express_id'] != intval(request()->data['express_id'] ?? 0) ) {
                return '已经存在包邮模板';
            }
        }
        return true;
    }

    function checkType($value, $rule)
    {
        $state = State::GOODS_EXPRESS_TYPE_ARRAYS;
        if ( false == isset($state[$value] ) ) {
            return false;
        }
        return true;
    }

    function checkContent($content, $rule)
    {
        # 检测
        $state      = State::GOODS_EXPRESS_WAYS_ARRAYS;
        foreach ($content as $key => $value) {
            # 不存在对应的key
            if (false == isset($state[$key])) {
                return false;
            }
            foreach ($value as $vo) {
                # 检测 首件|首重
                $first = $vo['express_first_price'] ?? null;
                if (false == is_numeric($first) || $first < 0 ) {
                    return '快递和EMS 首件|首重 价格错误';
                }
                # 检测 续件|续重
                $continue = $vo['express_continue_price'] ?? null;
                if (false == is_numeric($continue) || $continue < 0 ) {
                    return '快递和EMS 续件|续重 价格错误';
                }
                # 检测 城市
                $citys = $vo['express_city_id'] ?? [];
                if ( empty($citys) || false == is_array($citys) ) {
                    return '快递和EMS 城市必须选择';
                }
                foreach ($citys as $city) {
                    if ( false == preg_match('/^[0-9]{1,10}$/', $city) ) {
                        return '快递和EMS 城市错误';
                    }
                }
            }
        }
        return true;
    }

    function checkNumber($value,$rule)
    {
        $value = explode(',',$value);
        foreach ($value as $ko => $vo) {
            if ( false == is_numeric($vo) || $vo < 0 ){
                return false;
            }
        }
        return true;
    }
}