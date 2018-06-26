<?php
namespace mercury\constants\state;

/**
 * 广告
 */
interface Ads
{
    const ADS_STATE_WAIT_PAY_TIME = 3600;

	const ADS_STATE_WAIT_PAY 	= 0;
	const ADS_STATE_NORMAL 		= 1;
	const ADS_STATE_PASS_DATE 	= 2;
	const ADS_STATE_CANCEL 		= 3;
	const ADS_STATE_VIOLATION 	= 4;
	const ADS_STATE_ARRAY = [
		self::ADS_STATE_WAIT_PAY 	=> '待付款',
		self::ADS_STATE_NORMAL 		=> '投放中',# 已付款
		self::ADS_STATE_PASS_DATE 	=> '已过期',# 已过投放日期，或过期未付款
		self::ADS_STATE_CANCEL 		=> '已取消',# 作废，待付款的时候才可操作
		self::ADS_STATE_VIOLATION 	=> '违规',
	];
	
	const ADS_STATE_ARRAY2 = [
		[self::ADS_STATE_WAIT_PAY 	, '待付款'],
		[self::ADS_STATE_NORMAL 	, '投放中'],# 已付款
		[self::ADS_STATE_PASS_DATE 	, '已过期'],# 已过投放日期，或过期未付款
		[self::ADS_STATE_CANCEL 	, '已取消'],# 作废，待付款的时候才可操作
		[self::ADS_STATE_VIOLATION 	, '违规'],
	];
	
	const ADS_TYPE_GOODS = 0;
	const ADS_TYPE_SHOP = 1;
	const ADS_TYPE_ARRAY = [
		self::ADS_TYPE_GOODS 	=> '商品',
		self::ADS_TYPE_SHOP 	=> '店铺',
	];
	
	const ADS_MAX_BUY_MONTH = 3;
	
    const ADS_POSITION_SIZE = [
        [100,100],
        [200,200],
        [640,640],
		[800,800],
    ];

    const ADS_POSITION_TYPE1 = 1;
    const ADS_POSITION_TYPE2 = 2;
    const ADS_POSITION_TYPE3 = 3;
    const ADS_POSITION_TYPE = [
        self::ADS_POSITION_TYPE1 => '图片',
        self::ADS_POSITION_TYPE2 => '焦点图',
        self::ADS_POSITION_TYPE3 => '轮播图',
    ];
	const ADS_POSITION_TYPE_NEW = [
        [self::ADS_POSITION_TYPE1 , '图片'],
        [self::ADS_POSITION_TYPE2 , '焦点图'],
        [self::ADS_POSITION_TYPE3 , '轮播图'],
    ];

    const ADS_POSITION_DEVICE1 = 1;
    const ADS_POSITION_DEVICE2 = 2;
    const ADS_POSITION_DEVICE3 = 3;
    const ADS_POSITION_DEVICE4 = 4;
    const ADS_POSITION_DEVICE5 = 5;
    const ADS_POSITION_DEVICE = [
        self::ADS_POSITION_DEVICE1 => 'PC',
        self::ADS_POSITION_DEVICE2 => 'WAP',
        self::ADS_POSITION_DEVICE3 => 'ANDROID',
        self::ADS_POSITION_DEVICE4 => 'IOS',
        self::ADS_POSITION_DEVICE5 => 'APP',
    ];
	const ADS_POSITION_DEVICE_NEW = [
        [self::ADS_POSITION_DEVICE1 , 'PC'],
        [self::ADS_POSITION_DEVICE2 , 'WAP'],
        [self::ADS_POSITION_DEVICE3 , 'ANDROID'],
        [self::ADS_POSITION_DEVICE4 , 'IOS'],
        [self::ADS_POSITION_DEVICE5 , 'APP'],
    ];

}