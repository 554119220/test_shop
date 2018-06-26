<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 15:20
 */

namespace mercury\constants\state;

/**
 * Class Goods
 * @package mercury\constants\state
 *
 * 商品状态对应码
 */
interface Goods
{
    const STATE_GOODS_DELETE    = 0;    //已删除

    const STATE_GOODS_NORMAL    = 1;    //上架

    const STATE_GOODS_UNDER     = 2;    //已下架

    const STATE_GOODS_VIOLATION = 3;    //一般违规
	
	const STATE_GOODS_VERY_VIOLATION = 4;    //严重违规

	# 总状态
    const STATE_GOODS_ARRAY = [
		self::STATE_GOODS_NORMAL    		=> '上架',
		self::STATE_GOODS_UNDER     		=> '下架',
		self::STATE_GOODS_VIOLATION 		=> '违规',
        self::STATE_GOODS_DELETE    		=> '删除',
		self::STATE_GOODS_VERY_VIOLATION 	=> '严重违规',
    ];
	const STATE_GOODS_ARRAY_NEW = [
		[self::STATE_GOODS_NORMAL, '上架'],
		[self::STATE_GOODS_UNDER, '下架'],
		[self::STATE_GOODS_VIOLATION, '违规'],
        [self::STATE_GOODS_DELETE, '删除'],
		[self::STATE_GOODS_VERY_VIOLATION, '严重违规'],
    ];
	# 商家可查看的状态
	const STATE_GOODS_USER_ARRAY = [
		self::STATE_GOODS_NORMAL    		=> '上架',
		self::STATE_GOODS_UNDER     		=> '下架',
		self::STATE_GOODS_VIOLATION 		=> '违规',
		self::STATE_GOODS_VERY_VIOLATION 	=> '严重违规',
    ];
	# 商家可发布商品的状态
	const STATE_GOODS_CREATE_ARRAY = [
		self::STATE_GOODS_NORMAL    => '上架',
		self::STATE_GOODS_UNDER     => '下架',
    ];
	# 可删除的状态
	const STATE_GOODS_DELETE_ARRAY = [
		self::STATE_GOODS_NORMAL    		=> '上架',
		self::STATE_GOODS_VIOLATION 		=> '违规',
		self::STATE_GOODS_UNDER     		=> '下架',
		self::STATE_GOODS_VERY_VIOLATION 	=> '严重违规',
    ];
	# 可上架的状态
	const STATE_GOODS_SHELVES_ARRAY = [
		self::STATE_GOODS_VIOLATION 		=> '违规',
		self::STATE_GOODS_UNDER     		=> '下架',
		self::STATE_GOODS_VERY_VIOLATION 	=> '严重违规',
    ];

	# 橱窗推荐
    const STATE_GOODS_RECOMMEND     = 1;

    const STATE_GOODS_NO_RECOMMEND  = 0;

    const STATE_GOODS_RECOMMEND_ARRAY   = [
        self::STATE_GOODS_NO_RECOMMEND  => '不推荐',
        self::STATE_GOODS_RECOMMEND     => '推荐',
    ];
	# 最多设置橱窗数
	const STATE_GOODS_RECOMMEND_NUMS = 20;
	
	# 精选优选
	const STATE_GOODS_RECOMMEND_TYPE_ZERO 	= 0;
	const STATE_GOODS_RECOMMEND_TYPE_TWO 	= 1;
	const STATE_GOODS_RECOMMEND_TYPE_THREE 	= 2;
	const STATE_GOODS_RECOMMEND_TYPE_FOUR 	= 3;
	const STATE_GOODS_RECOMMEND_TYPE_FIVE 	= 4;
	
	const STATE_GOODS_RECOMMEND_TYPE_ARRAYS = [
		self::STATE_GOODS_RECOMMEND_TYPE_ZERO 	=> '常规',
		self::STATE_GOODS_RECOMMEND_TYPE_TWO 	=> '精选',
		self::STATE_GOODS_RECOMMEND_TYPE_THREE 	=> '优选',
		self::STATE_GOODS_RECOMMEND_TYPE_FOUR 	=> '热卖',
		self::STATE_GOODS_RECOMMEND_TYPE_FIVE 	=> '每日必抢',
	];
	
	const STATE_GOODS_RECOMMEND_TYPE_ARRAYS_NEW = [
		[self::STATE_GOODS_RECOMMEND_TYPE_ZERO,	'常规'],
		[self::STATE_GOODS_RECOMMEND_TYPE_TWO,	'精选'],
		[self::STATE_GOODS_RECOMMEND_TYPE_THREE, '优选'],
		[self::STATE_GOODS_RECOMMEND_TYPE_FOUR, '热卖'],
		[self::STATE_GOODS_RECOMMEND_TYPE_FIVE, '每日必抢'],
	];
	
	# 精选申请设置
	const STATE_AYPPLY_RECOMMEND1_MIN_SHOP_SCORE 	= 36; # 最低店铺积分
	const STATE_AYPPLY_RECOMMEND1_MIN_GOODS_UP_NUM 	= 1; # 最低在售商品数量
}














