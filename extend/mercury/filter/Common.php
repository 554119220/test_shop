<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4 0004
 * Time: 11:00
 */

namespace mercury\filter;


class Common extends Filter
{
    protected $params   = [
        'goods_id'  => 'int',
        'shop_id'   => 'int',
        'id'        => 'int',
        'share_code'=> 'string',
        'cps_spm'   => 'string'
    ];
}