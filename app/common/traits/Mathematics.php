<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/23 0023
 * Time: 11:39
 */

namespace app\common\traits;


trait Mathematics
{
    /**
     * 数字的N倍
     *
     * @param $number
     * @return string
     */
    public static function numberNMulti($number, $right = 100, $scale = 0)
    {
        return bcmul($number, $right, $scale);
    }

    /**
     * 取小数
     *
     * @param $number
     * @param int $right
     * @param int $scale
     * @return string
     */
    public static function numberBcDiv($number, $right = 100, $scale = 2)
    {
        return bcdiv($number, $right, $scale);
    }

    /**
     * 金额计算
     *
     * @param $amount
     * @param int $scale
     * @return float|int
     */
    public static function amountCalc($amount, $scale = 2)
    {
        if (!$amount) return 0;
        $pow    = pow(10, $scale);
        #   舍去位为5 && 舍去位后无数字 && 舍去位前一位是偶数    =》 不进一
        if ((floor($amount * $pow * 10) % 5 == 0 ) &&
            (floor($amount * $pow * 10) == $amount * $pow * 10) &&
            (floor($amount * $pow) % 2 == 0)) {
            return floor($amount * $pow) / $pow;
        } else {
            return round($amount, $scale);
        }
    }

    /**
     * 格式化金额
     *
     * @param $number
     * @param $scale
     * @return float
     */
    public static function numberFormats($number, $scale = 2)
    {
        return (float) round(number_format($number, $scale, '.', ''), $scale);
    }

//    public static function numberNMulti($number, $n)
//    {
//        return
//    }
}