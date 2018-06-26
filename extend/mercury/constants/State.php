<?php
/**
 * Created by PhpStorm.
 * User: Mercury
 * Date: 2017/10/27 0027
 * Time: 16:35
 *
 * 数据的一些基本的状态嘛
 */

namespace mercury\constants;


use mercury\constants\state\Comment;
use mercury\constants\state\Goods;
use mercury\constants\state\Orders;
use mercury\constants\state\Refund;
use mercury\constants\state\Refunds;
use mercury\constants\state\Service;
use mercury\constants\state\Shop;
use mercury\constants\state\User;
use mercury\constants\state\ShopSettled;
use mercury\constants\state\GoodsExpressTpl;
use mercury\constants\state\GoodsPackageTpl;
use mercury\constants\state\GoodsProtectionTpl;
use mercury\constants\state\GoodsParams;
use mercury\constants\state\GoodsSkuGroup;
use mercury\constants\state\GoodsParamsGroup;
use mercury\constants\state\ShopGoodsCategory;
use mercury\constants\state\UserExtend;
use mercury\constants\state\UserBaby;
use mercury\constants\state\GoodsQualificationsGroup;
use mercury\constants\state\GoodsQualifications;
use mercury\constants\state\Ads;

class State implements Goods, Orders, Refund, Refunds, Service, User, Comment, Shop, ShopSettled, GoodsExpressTpl, GoodsPackageTpl, GoodsProtectionTpl, GoodsParams, GoodsSkuGroup,GoodsParamsGroup,ShopGoodsCategory,UserExtend,UserBaby,GoodsQualificationsGroup,GoodsQualifications,Ads
{

    /**
     * 公用状态码 bool
     */
    const STATE_DISABLED    = 0;    //禁用状态

    const STATE_DELETE      = 0;    //删除状态

    const STATE_NORMAL      = 1;    //正常状态

    const STATE_CANCEL      = 20;   //取消状态

    const STATE_SUCCESS     = 100;  //已完成

    const STATE_ARRAY   = [
        self::STATE_DISABLED    => '已禁用/已删除',
        self::STATE_NORMAL      => '正常',
        self::STATE_CANCEL      => '取消',
        self::STATE_SUCCESS     => '完成',
    ];

    //退款
    const STATE_REFUND  = 1;
    //退货退款
    const STATE_REFUNDS = 2;

    const STATE_REFUND_TYPE = [
        self::STATE_REFUND  => '仅退款',
        self::STATE_REFUNDS => '退货并退款',
    ];
}