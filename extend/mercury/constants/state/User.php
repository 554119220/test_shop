<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 15:17
 */

namespace mercury\constants\state;

/**
 * Class User
 * @package mercury\constants\state
 *
 * 用户状态
 */
interface User
{
    /**
     * 用户状态
     */
    const STATE_USER_DISABLED   = 0;
    const STATE_USER_NORMAL     = 1;

    CONST USER_STATE_ARRAYS = [
        self::STATE_USER_DISABLED	=> '禁用',
        self::STATE_USER_NORMAL 	=> '正常',
    ];
}