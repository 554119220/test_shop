<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 16:10
 */

namespace mercury\constants\state;

/**
 * Interface Comment
 * @package mercury\constants\state
 *
 * 评价状态对应码
 */
interface Comment
{
    const STATE_COMMENT_POOR    = -1;   //差评

    const STATE_COMMENT_GOOD    = 1;    //好评

    const STATE_COMMENT_MIDDLE  = 0;    //中评

    const STATE_COMMENT_ARRAYS  = [
        self::STATE_COMMENT_POOR    => '差评',
        self::STATE_COMMENT_MIDDLE  => '中评',
        self::STATE_COMMENT_GOOD    => '好评',
    ];

    #   评价已生效
    const STATE_COMMENT_EFFECT_NORMAL   = 1;
    #   评价未生效
    const STATE_COMMENT_EFFECT_DISABLED = 0;
    const STATE_COMMENT_EFFECT_ARRAYS   = [
        self::STATE_COMMENT_EFFECT_DISABLED => '未生效',
        self::STATE_COMMENT_EFFECT_NORMAL   => '已生效',
    ];
}