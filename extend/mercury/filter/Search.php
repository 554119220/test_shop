<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 14:01
 */

namespace mercury\filter;


class Search extends Filter
{
    protected $params = [
        'order'     => 'string',
        'sort'      => 'string',
        'q'         => 'string',
        'price_min' => 'float',
        'price_max' => 'float',
        'cate'      => 'string',
        'cate2'     => 'int',
        'free'      => 'int',
        'shop_id'   => 'int',
        'featured'  => 'int',
        'preferred' => 'int',
        'brand'     => 'int',
        'hot'       => 'int',
        'day'       => 'int',
        'self'      => 'int',
        'lowest_shopping_score'    => 'int',
    ];
}