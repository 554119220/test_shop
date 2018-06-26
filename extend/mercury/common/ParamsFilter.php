<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2 0002
 * Time: 11:55
 */

namespace mercury\common;


use mercury\constants\Code;
use mercury\ResponseException;

trait ParamsFilter
{
    protected $params = [], $onlyParams = [];

    /**
     * @title filter
     * @param array $requestParams
     */
    public function filter(array $requestParams)
    {
        try {
            if (!isset($this->onlyParams)) throw new ResponseException(Code::CODE_OTHER_FAIL, '不接受任何参数');
            foreach ($requestParams as $key => $param) {
                if (!in_array($key, $this->onlyParams)) {
                    unset($requestParams[$key]);
                }
            }
        } catch (ResponseException $e) {
//            return $e->getData();
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    /**
     * @title setOnlyParams
     * @param array $params
     * @return $this
     */
    public function setOnlyParams(array $params)
    {
        $this->onlyParams   = $params;
        return $this;
    }
}