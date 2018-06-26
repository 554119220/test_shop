<?php

namespace mercury\constants\state;

/**
 * Class User
 *
 * 用户宝贝
 */
interface UserBaby
{
    /**
     * 宝贝性别
     */
    const USER_BABY_SEX_HIDE 	= 0;
	const USER_BABY_SEX_MAN 	= 1;
	const USER_BABY_SEX_WOMAN = 2;
	
	const USER_BABY_SEX_ARRAYS = [
		self::USER_BABY_SEX_HIDE 	=> '孕期',
		self::USER_BABY_SEX_MAN 	=> '男',
		self::USER_BABY_SEX_WOMAN => '女',
	];
	
	
}








