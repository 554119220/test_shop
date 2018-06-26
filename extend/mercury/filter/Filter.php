<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 14:01
 */

namespace mercury\filter;


abstract class Filter
{
    protected $data = [], $params = [],
        $filter   = [
        'script'
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @title instance
     * @param $data
     * @param $type
     * @return mixed
     */
    public static function instance($data, $type = 'Common')
    {
        $class  = sprintf('%s\\%s', __NAMESPACE__, ucfirst($type));
        return new $class($data);
    }

    /**
     * @title run
     * @return array
     */
    public function run()
    {
        $tmp    = [];
        foreach ($this->params as $k => $v) {
            if (array_key_exists($k, $this->data)) {
                switch ($v) {
                    case 'string':
                        $tmp[$k]  = str_replace($this->filter, '', $this->data[$k]);
                        break;
                    case 'int':
                        $tmp[$k] = intval($this->data[$k]);
                        break;
                    case 'float':
                        $tmp[$k] = round($this->data[$k], 2);
                        break;
                }
            }
        }
        return array_filter($tmp);
    }

    public function toString()
    {
        return sprintf('?%s', http_build_query($this->run()));
    }
}