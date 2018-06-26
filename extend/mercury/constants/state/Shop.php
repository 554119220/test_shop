<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 16:26
 */

namespace mercury\constants\state;

/**
 * Interface Shop
 * @package mercury\constants\state
 *
 * 店铺状态对应码
 */
interface Shop
{
    const STATE_SHOP_DELETE = 0;    //已删除

    const STATE_SHOP_NORMAL = 1;    //正常

    const STATE_SHOP_FREEZE = 2;    //冻结

    const STATE_SHOP_CLOSE  = 3;    //已关闭

    const STATE_SHOP_FORCED_CLOSE   = 4;    //强制关闭

    const STATE_SHOP_ARRAY  = [
        self::STATE_SHOP_DELETE => '已删除',
        self::STATE_SHOP_NORMAL => '正常',
        self::STATE_SHOP_FREEZE => '冻结',
        self::STATE_SHOP_CLOSE  => '关闭',
        self::STATE_SHOP_FORCED_CLOSE   => '强制关闭',
    ];

    /**
     * 入住申请审核状态编码
     */
    const STATE_SHOP_SETTLED_SUCCESS    = 1;

    const STATE_SHOP_SETTLED_FAIL       = 2;

    const STATE_SHOP_SETTLED_WAIT       = 0;

    const STATE_SHOP_SETTLED_ARRAY  = [
        self::STATE_SHOP_SETTLED_WAIT   => '待审核',
        self::STATE_SHOP_SETTLED_SUCCESS=> '已通过审核',
        self::STATE_SHOP_SETTLED_FAIL   => '审核失败',
    ];

    /**
     * 开店步骤
     */
    const STATE_SHOP_SETTLED_STEP_LEVEL = 1;    //等级确认

    const STATE_SHOP_SETTLED_STEP_TYPE  = 2;    //店铺类型选中

    const STATE_SHOP_SETTLED_STEP_CATEGORY  = 3;    //店铺类目选择

    const STATE_SHOP_SETTLED_STEP_BRAND = 4;    //店铺品牌资质

    const STATE_SHOP_SETTLED_STEP_SHOP  = 5;    //店铺信息

    const STATE_SHOP_SETTLED_STEP_SUCCESS   = 6;    //开店成功

    const STATE_SHOP_SETTLED_STEP_ARRAY = [
        self::STATE_SHOP_SETTLED_STEP_LEVEL => [
            'action'        => 'level',
            'description'   => '等级认证',
        ],
        self::STATE_SHOP_SETTLED_STEP_TYPE  => [
            'action'        => 'type',
            'description'   => '店铺类型选择'
        ],
        self::STATE_SHOP_SETTLED_STEP_CATEGORY  => [
            'action'        => 'category',
            'description'   => '经营类目选择',
        ],
        self::STATE_SHOP_SETTLED_STEP_BRAND => [
            'action'        => 'brand',
            'description'   => '品牌验证',
        ],
        self::STATE_SHOP_SETTLED_STEP_SHOP  => [
            'action'        => 'shop',
            'description'   => '店铺信息录入'
        ],
        self::STATE_SHOP_SETTLED_STEP_SUCCESS   => [
            'action'        => 'success',
            'description'   => '开店成功'
        ],
    ];
}