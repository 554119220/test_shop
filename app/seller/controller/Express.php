<?php
namespace app\seller\controller;
use mercury\factory\Factory;
use app\common\traits\F as Fun;
use mercury\constants\State;
use mercury\auth\api\AuthApi;
use mercury\editor\UEditor;

/**
 * 运费模板
 * @author Lzy
 * @datetime 2017-12-07 14:00:00
 */
class Express
{
    /**
     * 列表
     * @return \think\response\View
     */
    public function index()
    {
        // dump(Factory::instance('/goods/v1/GoodsExpressTpl/index')->run());exit;
        return view('',[
            'FreeArray'     => State::GOODS_EXPRESS_FREE_ARRAYS,
            'Express'       => Factory::instance('/goods/v1/GoodsExpressTpl/index')->run()['data'] ?? [],
            'headers'       => [
                'headers0'      => AuthApi::getInstance('/goods/v1/GoodsExpressTpl/delete')->createHeaders(),
                'headers1'      => AuthApi::getInstance('/goods/v1/GoodsExpressTpl/detail')->createHeaders(),
            ],
        ]);
    }

    /**
     * 创建
     * @return \think\response\View
     */
    public function create()
    {
        // dump(Factory::instance('/goods/v1/GoodsExpressTpl/have_city')->run());
        return view('',[
            'FreeArray'     => State::GOODS_EXPRESS_FREE_ARRAYS,
            'TypeArray'     => State::GOODS_EXPRESS_TYPE_ARRAYS,
            'WaysArray'     => State::GOODS_EXPRESS_WAYS_ARRAYS,
            'ProvinceList'  => Factory::instance('/tools/v1/district/index')->run()['data'] ?? [],
            'headers'       => [
                'headers0'      => AuthApi::getInstance('/goods/v1/GoodsExpressTpl/create')->createHeaders(),
                'headers1'      => AuthApi::getInstance('/tools/v1/District/index')->createHeaders(),
                'area_index'    => AuthApi::getInstance('/tools/v1/district/index')->createHeaders(),
            ],
        ]);
    }

    /**
     * 修改
     * @return \think\response\View
     */
    public function edit()
    {   
        // dump(Factory::instance('/tools/v1/district/index')->run());exit;
        $detail = Factory::instance('/goods/v1/GoodsExpressTpl/detail')->run()['data'] ?? [];
        // dump($detail);exit;
        // dump(Factory::instance('/tools/v1/district/index')->run());exit;
        if ( empty($detail) ) {
            return view('empty');
        }
        return view('',[
            'FreeArray'     => State::GOODS_EXPRESS_FREE_ARRAYS,
            'TypeArray'     => State::GOODS_EXPRESS_TYPE_ARRAYS,
            'WaysArray'     => State::GOODS_EXPRESS_WAYS_ARRAYS,
            'ProvinceList'  => Factory::instance('/tools/v1/district/index')->run(['id' => 0])['data'] ?? [],
            'CityList'      => Factory::instance('/tools/v1/district/index')->run(['id' => $detail['express_ship_province_id'] ?? 0])['data'] ?? [],
            'detail'        => $detail,
            'headers'       => [
                'headers0'      => AuthApi::getInstance('/goods/v1/GoodsExpressTpl/update')->createHeaders(),
                'headers1'      => AuthApi::getInstance('/tools/v1/District/index')->createHeaders(),
                'area_index'    => AuthApi::getInstance('/tools/v1/district/index')->createHeaders(),
            ],
        ]);
    }

    public function choose_distinct(){
        $ways = input('ways',-1,'int');
        // dump(Factory::instance('/goods/v1/GoodsExpressTpl/have_city')->run()['data']);exit;
        return view('',[
            'provinceCity'  => Factory::instance('/tools/v1/District/provinceCity')->run()['data'] ?? [],
        ]);
    }
}