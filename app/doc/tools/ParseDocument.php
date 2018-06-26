<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22 0022
 * Time: 11:47
 */

namespace app\doc\tools;


use app\common\Code;
use app\common\traits\F;
use mercury\ResponseException;
use think\Exception;

class ParseDocument
{
    const API_DIR   = 'api/controller/';
    const API_NAMESPACE = '\\app\\api\\controller\\';
    const FILTER_FILE_NAME  = [
        'Init',
        'Common'
    ];
    const CACHE_NAME    = 'tools:page:doc:group';
    #   模块名称
    const MODULE_NAME   = [
        'article'   => '文章',
        'finance'   => '财务',
        'goods'     => '商品',
        'orders'    => '订单',
        'pay'       => '支付',
        'promotions'=> '促销',
        'search'    => '搜索',
        'tools'     => '工具',
        'user'      => '用户',
        'work'      => '后台',
        'ads'       => '广告',
        'cps'       => '百望客'
    ];
    protected $api_dir, $dirs, $files, $version = '/v1/';
    public static $instance;
    public function __construct()
    {
        $this->api_dir  = APP_PATH . self::API_DIR;
        $this->dirs = scandir($this->api_dir);
    }

    public static function instance()
    {
        if (false == self::$instance instanceof self) self::$instance = new static();
        return self::$instance;
    }
    
    public function dirs()
    {
        $tmp    = [];
        foreach ($this->dirs as $k => $v) {
            if (strpos($v, '.') === false) $tmp[] = $v;
        }
        return $tmp;
    }

    public function files()
    {
        $cache_key  = F::getCacheName(self::CACHE_NAME);
        $tmp    = F::redis()->get($cache_key);
        if (!$tmp) {
            $dirs   = $this->dirs();
            $tmp    = [];
            foreach ($dirs as $v) {
                $dir    = "{$this->api_dir}{$v}{$this->version}";
                $files  = scandir($dir);
                $tmp[$v]['dir'] = $dir;
                $tmp[$v]['name']= $v;
                $tmp[$v]['title']   = self::MODULE_NAME[$v];
                if ($files) {
                    foreach ($files as $val) {
                        if (strpos($val, 'php') !== false) {
                            $file   = "{$dir}{$val}";
                            $key    = substr($val, 0, strpos($val, '.'));
                            if (is_file($file) && !in_array($key, self::FILTER_FILE_NAME)) {
                                $tmp[$v]['child'][$key]['file'] = $file;
                                $tmp[$v]['child'][$key]['file_name'] = $filename = $key;
                                $tmp[$v]['child'][$key]['name'] = $key;
                                $tmp[$v]['child'][$key]['file_full_name'] = $val;
                                $tmp[$v]['child'][$key]['last_modify_time'] = filemtime($file);
                                $tmp[$v]['child'][$key]['namespace'] = $namespace = self::API_NAMESPACE . str_replace('/', '\\', "{$v}{$this->version}{$filename}");
                                $classes= new \ReflectionClass($namespace);
                                $title  = $this->getClassTitle($classes->getDocComment());
                                if ($title) $tmp[$v]['child'][$key]['name'] = $title;
                                $methods= $classes->getMethods(\ReflectionMethod::IS_PUBLIC);
                                if ($methods) {
                                    foreach ($methods as $mkey =>  $mv) {
                                        if (strpos($mv->name, '_') !== 0) { #   过滤非action方法
                                            $method = new \ReflectionMethod($namespace, $mv->name);
                                            if (false == $method->isStatic()) {
                                                $tmp[$v]['child'][$key]['methods'][$mkey]['name']   = $mv->name;
                                                $title  = $this->getClassTitle($method->getDocComment());
                                                if ($title) $tmp[$v]['child'][$key]['methods'][$mkey]['name'] = $title;
                                                $tmp[$v]['child'][$key]['methods'][$mkey]['route']  = "/{$v}{$this->version}{$filename}/{$mv->name}";
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            F::redis()->setex($cache_key, 300, serialize($tmp));
        }
        if (is_string($tmp)) $tmp = unserialize($tmp);
        return $tmp;
    }

    /**
     * @title find
     * @param $route
     * @return bool|mixed
     */
    public function find($route)
    {
        try {
            $route      = strpos($route, '/') === 0 ? substr($route, 1) : $route;
            $controller = substr($route, 0, strripos($route, '/'));
            $action     = substr($route, strripos($route, '/') + 1);
            $file_path  = str_replace('//', '/', APP_PATH . self::API_DIR . $controller);
            $file       = $file_path . EXT;
            if (false == is_file($file)) throw new ResponseException(Code::CODE_OTHER_FAIL, '文件不存在');
            $namespace  = str_replace('/', '\\', self::API_NAMESPACE . $controller);
            $class      = new \ReflectionClass($namespace);
            $doc        = $class->getMethod($action)->getDocComment();
            $docs       = $this->getFunctionDoc($doc);
            $docs['title']  = $this->getClassTitle($doc);
            $docs['route']  = "/{$route}";
            return $docs;
        } catch (ResponseException $e) {
            throw new Exception($e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $doc
     * @return bool
     */
    protected function getClassTitle($doc, $prefix = '@title')
    {
        if (false == $doc) return false;
        $pattern    = "#({$prefix}[\s]*[\S]*)#";
        preg_match($pattern, $doc, $match);
        if (!isset($match[0]) && empty($match[0])) return false;
        return str_replace([$prefix, ' ', '\r', '\n', '\r\n', '\t', '&#13;', '&#10;'], '',$match[0]);
    }

    /**
     * @title getFunctionDoc
     * @param $doc
     * @return mixed
     */
    protected function getFunctionDoc($doc)
    {
//        $doc    = '/**
//     * @title 付款方式
//     * @request 请求参数
//     * |参数名|参数类型|是否必传|示例值|更多限制|参数描述|
//     * |---|---|---|---|---|---|
//     * |name|string|true|hello world|-|description|
//     * @response 响应参数
//     * |参数名|数据类型|返回数据|
//     * |---|---|---|
//     * |name|string|values|
//     * @response_example 响应示例
//     * `you are json code`
//     * @description 接口描述
//     * > you are api description
//     * @return array|mixed|string
//     */';
        $markdown   = new \Parsedown();
        #   request
        $pattern    = "#(@request[\s\S]*?@)#";
        preg_match($pattern, $doc, $match);
        $request_title  = $this->getClassTitle($match[0], '@request');
        $request   = $this->getMarkdownCode($match[0]);
        $docs['request']    = [
            'title'     => $request_title,
            'content'   => $request ? $markdown->text($request) : '',
        ];
        #   response
        $pattern    = "#(@response[\s\S]*?@)#";
        preg_match($pattern, $doc, $match);
        $response_title  = $this->getClassTitle($match[0], '@response');
        $response   = $this->getMarkdownCode($match[0]);
        $docs['response']   = [
            'title'     => $response_title,
            'content'   => $response ? $markdown->text($response) : '',
        ];
        #   response example
        $pattern    = "#(@response_example[\s\S]*?@)#";
        preg_match($pattern, $doc, $match);
        $response_example_title  = $this->getClassTitle($match[0], '@response_example');
        $response_example   = $this->getMarkdownCode($match[0], '`');
        $docs['response_example']   = [
            'title'     => $response_example_title,
            'content'   => $response_example ? $response_example : '',
        ];
        $pattern    = "#(@description[\s\S]*?@)#";
        preg_match($pattern, $doc, $match);
        $description_title  = $this->getClassTitle($match[0], '@description');
        $description    = $this->getDescription($match[0]);
        $docs['description']    = [
            'title'     => $description_title,
            'content'   => $description ? $markdown->text($description) : '',
        ];
        return $docs;
    }

    /**
     * @title getMarkdownCode
     * @param $doc
     * @param string $p
     * @return bool|mixed
     */
    protected function getMarkdownCode($doc, $p = '|')
    {
        $pattern    = "#(\\{$p}[\s\S]*\\{$p})#";
        preg_match($pattern, $doc, $match);
        if ($match) {
            return str_replace('*', '', $match[0]);
        }
        return false;
    }

    /**
     * @title getDescription
     * @param $doc
     * @param string $start
     * @param string $end
     * @return bool|mixed
     */
    protected function getDescription($doc, $start = '>', $end = '@')
    {
        $pattern    = "#(\\{$start}[\s\S]*\\{$end})#";
        preg_match($pattern, $doc, $match);
        if ($match) {
            return str_replace(['*', '@', '\r', '\n', '\r\n', '\t',], '', $match[0]);
        }
        return false;
    }
}