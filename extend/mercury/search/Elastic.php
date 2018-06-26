<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2 0002
 * Time: 18:19
 */

namespace mercury\search;

/**
 * Class Elastic
 * @package mercury\search
 *
 * Elastic
 */
class Elastic
{
    protected $type, $index = 'mall', $config = [], $params = [], $body = [];

    /**
     * @var int
     */
    public $rows = 20, $page = 1;

    public static $instance;

    public function __construct()
    {
        $this->params['index']  = $this->index;
        $this->params['type']   = $this->type;
    }

    
    public static function instance()
    {
        if (false == self::$instance instanceof self) self::$instance = new static();
        return self::$instance;
    }
    
    public function search()
    {
        
    }

    /**
     * 分页
     *
     * @param $page
     * @param $rows
     * @return $this
     */
    public function page($page, $rows)
    {
        //$page   = $page > 1 ? ($page * $rows) : 0;
        $page   = $page < 1 ? 1 : $page;
        $page   = ($page - 1) * $rows;
        $this->params['size']   = $page;
        $this->params['from']   = $rows;
        return $this;
    }

    /**
     * 排序
     *
     * @param array $params
     *          ['id' => ['order' => 'asc']]
     * @return $this
     */
    public function sort(array $params)
    {
        $this->params['sort']   = $params;
        return $this;
    }

    public function like(array $params)
    {
        $this->body['query']['match']   = $params;
        return $this;
    }

    public function filter()
    {
        
    }

    public function eq(array $params)
    {
        $this->body['bool']['must'] = $params;
        return $this;
    }

    public function neq(array $params)
    {

    }

    public function between(array $params)
    {
        
    }

    public function where()
    {
        
    }
}