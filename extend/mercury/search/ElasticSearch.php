<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21 0021
 * Time: 10:58
 */

namespace mercury\search;


use Elasticsearch\ClientBuilder;
use Elasticsearch\Connections\Connection;
use traits\think\Instance;

/**
 * 搜索
 *
 * Class ElasticSearch
 * @package mercury\search
 */
class ElasticSearch
{
    /**
     * @var object $client
     * @var array $params   默认参数
     */
    protected $client, $params = [];

    /**
     * @var object $instance 对象
     */
    public static $instance = [];

    /**
     * index string mall
     */
    const SEARCH_INDEX  = 'mall';



    /**
     * type string goods
     */
    const SEARCH_TYPE_GOODS = 'goods';
    /**
     * type string shop
     */
    const SEARCH_TYPE_SHOP  = 'shops';

    /**
     * type string test
     */
    const SEARCH_TYPE_TEST  = 'test';


    /**
     * elasticsearch 跟 MySQL 中定义资料格式的角色关系对照表如下
     * MySQL             elasticsearch
     * database          index
     * table             type
     * table schema mapping
     * row               document
     * field             field
     *
     * ElasticSearch constructor.
     */

    public function __construct($type = '', $index = '')
    {
        $params = [
            'hosts' => [
                '192.168.10.131:9200',
            ],
            'retries'   => 2,
            //'imNotReal' => 5
        ];
        /*
         * [
            //[
                'elastic:6+u0uh3Gc9mWOWi%Ko1D@192.168.10.38:9200',
                //'name'  => 'node-1'
                //'user'  => 'elastic',
                //'pass'  => '6+u0uh3Gc9mWOWi%Ko1D',
            //]
        ]
         */
        $config = config('elastic');
        //$this->client   = ClientBuilder::fromConfig($params);
        $this->client   = ClientBuilder::create()
            ->setSSLVerification(false)->setHosts($config)->build();
        $this->params   = [
            'index' => $index,
            'type'  => $type,
        ];
    }

    /**
     * 获取商品的实例
     *
     * @return mixed
     */
    public static function goodsInstance()
    {
        $key    = self::SEARCH_TYPE_GOODS;
        if (!isset(self::$instance[$key]) || false == self::$instance[$key] instanceof self)
            self::$instance[$key] = new self($key);
        return self::$instance[$key];
    }

    /**
     * 获取店铺的实例
     *
     * @return mixed
     */
    public static function shopInstance()
    {
        $key    = self::SEARCH_TYPE_SHOP;
        if (!isset(self::$instance[$key]) || false == self::$instance[$key] instanceof self)
            self::$instance[$key] = new self($key);
        return self::$instance[$key];
    }

    /**
     * 获取测试的实例
     *
     * @return mixed
     */
    public static function testInstance()
    {
        $key    = self::SEARCH_TYPE_TEST;
        if (!isset(self::$instance[$key]) || false == self::$instance[$key] instanceof self)
            self::$instance[$key] = new self($key);
        return self::$instance[$key];
    }

    /**
     * 设置索引
     *
     * @param $index
     * @return $this
     */
    public function setIndex($index)
    {
        $this->params['index']  = $index;
        return $this;
    }

    /**
     * 设置索引
     *
     * @param array $params
     * @return array
     */
    public function index(array $params)
    {
        return $this->client->index($params);
    }

    /**
     * 更新索引
     *
     * @param array $params
     * @return array
     */
    public function update(array $params)
    {
        return $this->client->update($params);
    }

    /**
     * 获取数据
     *
     * @param array $params
     * @return array
     */
    public function get(array $params)
    {
        $params = $this->mergeParams($params);
        unset($params['body']);
        return $this->client->get($params);
    }

    /**
     * 搜索数据
     *
     * @param array $params
     * @return array
     */
    public function search(array $params)
    {
        //dump($this->mergeParams($params));
        return $this->client->search($params);
    }

    public function create(array $params)
    {
        return $this->client->create($this->mergeParams($params));
    }

    public function delete($params)
    {
        return $this->client->delete($this->mergeParams($params));
    }
    
    
    /**
     * 合并参数
     *
     * @param array $params
     * @return array
     */
    private function mergeParams(array $params)
    {
//        return array_merge($params, $this->params);
        //$this->params['body']    = $params;
//        $arr            = $this->params;
//        $arr['id']      = $params['id'];
//        $arr['body']    = $params;
        $params         = array_merge($params, $this->params);
        return $params;
    }

    public function getParams()
    {
        return $this->params;
    }
}