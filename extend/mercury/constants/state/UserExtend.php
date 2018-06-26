<?php
namespace mercury\constants\state;

/**
 * Class User
 *
 * 用户扩展信息
 */
interface UserExtend
{
    /**
     * 用户性别
     */
    const USER_EXTEND_SEX_HIDE 	= 0;
	const USER_EXTEND_SEX_MAN 	= 1;
	const USER_EXTEND_SEX_WOMAN = 2;
	
	const USER_EXTEND_SEX_ARRAYS = [
		self::USER_EXTEND_SEX_HIDE 	=> '保密',
		self::USER_EXTEND_SEX_MAN 	=> '男',
		self::USER_EXTEND_SEX_WOMAN => '女',
	];
	
	/**
	 * 身份
	 */
	const USER_EXTEND_TYPE_ONE 		= 1;
	const USER_EXTEND_TYPE_TWO 		= 2;
	const USER_EXTEND_TYPE_THREE 	= 3;
	const USER_EXTEND_TYPE_FOUR 	= 4;
	
	const USER_EXTEND_TYPE_ARRAYS = [
		self::USER_EXTEND_TYPE_ONE 		=> '帅哥',
		self::USER_EXTEND_TYPE_TWO 		=> '美女',
		self::USER_EXTEND_TYPE_THREE 	=> '奶爸',
		self::USER_EXTEND_TYPE_FOUR 	=> '辣妈',
	];
	
	
}








