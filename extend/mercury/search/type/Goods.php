<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2 0002
 * Time: 18:17
 */

namespace mercury\search\type;

use mercury\search\Elastic;

/**
 * Class Goods
 * @package mercury\search\type
 *
 * 商品搜索
 */
class Goods extends Elastic
{
    /**
     * @var string $type type
     */
    protected $type = 'goods';

    public function where()
    {
        
    }
}