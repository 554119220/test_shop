<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 10:24
 */

namespace app\api\controller\search\v1;

use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
use mercury\search\ElasticSearch;
/**
 * Class Shop
 * @package app\api\controller\search\v1
 * @title 搜索店铺
 */
class Shop extends Init
{
    public function __construct()
    {
        parent::__construct();
        $this->index    = ElasticSearch::shopInstance();
        $index  = config('url_domain_root') == 'zrst.com' ? 'shop' : 'shop2';
        $this->params   = $this->index->setIndex($index)->getParams();
    }

    public function index()
    {
        try {
            if (isset($this->data['q']) && !empty($this->data['q'])) {
                $q  = $this->data['q'];
                $this->params['body']['query']['bool']['must'][]['match']   = ['shop_name' => $q];
            }
            #   必须是正常店铺
            $this->params['body']['query']['bool']['must'][]['match']   = ['shop_state' => State::STATE_NORMAL];

            #   分页  默认为第一页
            $page   = 1;
            if (isset($this->data['page']) && !empty($this->data['page'])) {
                $page   = intval($this->data['page']) ? : 1;
            }
            --$page;
            $size   = isset($this->data['rows']) ?
                ($this->data['rows'] > self::ROWS_MAX_SIZE ? self::ROWS_MAX_SIZE : $this->data['rows']) :
                self::ROWS_SIZE;

            $this->params['from']   = $page * $size;  #   start
            $this->params['size']   = $size;

            $res    = $this->index->search($this->params);
            if ($res['hits']['total'] > 0) return $this->shopData($res['hits']);
        } catch (ResponseException $e) {

        }
        return Code::CODE_NO_CONTENT;
    }
}