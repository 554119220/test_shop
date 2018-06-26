<?php
namespace mercury\constants\state;

interface GoodsExpressTpl
{
	// 是否包邮
	const GOODS_EXPRESS_NO_FREE = 0;
    const GOODS_EXPRESS_IS_FREE = 1;
    

    const GOODS_EXPRESS_FREE_ARRAYS = [
		self::GOODS_EXPRESS_NO_FREE => '自定义运费',
        self::GOODS_EXPRESS_IS_FREE => '卖家承担运费（<span style="color:red">仅限大陆地区，港澳台除外</span>）',
    ];
	
	// 计费方式
	const GOODS_EXPRESS_TYPE_NUMBER = 0;
    const GOODS_EXPRESS_TYPE_WEIGHT = 1;
	
	const GOODS_EXPRESS_TYPE_ARRAYS = [
        self::GOODS_EXPRESS_TYPE_NUMBER => '计件',
        self::GOODS_EXPRESS_TYPE_WEIGHT => '计重',
    ];
	
	// 快递类型
	const GOODS_EXPRESS_WAYS_NORMAL 	= 0;
    const GOODS_EXPRESS_WAYS_EMS 	= 1;
	
	const GOODS_EXPRESS_WAYS_ARRAYS = [
        self::GOODS_EXPRESS_WAYS_NORMAL 	=> '快递',
        self::GOODS_EXPRESS_WAYS_EMS 		=> 'EMS',
    ];
	
	const GOODS_EXPRESS_WAYS_ARRAYS_NEW = [
        self::GOODS_EXPRESS_WAYS_NORMAL 	=> 'fees',
        self::GOODS_EXPRESS_WAYS_EMS 		=> 'ems_fees',
    ];
	
	// 最大20个模板
	const GOODS_EXPRESS_TPL_MAX = 50;
}
