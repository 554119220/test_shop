<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 11:12
 */

namespace mercury\required;


use app\common\traits\F;

class Required
{
    /**
     * @var array $request 请求数据, 错误信息，当前验证数组key，外加必传参数
     */
    protected $request, $errorMsg = true, $key, $required = [];

    /**
     * @var 实例，对象
     */
    protected static $instance, $map = [];


    /**
     * 构造方法
     *
     * RequireParams constructor.
     * @param array|object $request
     */
    public function __construct($request)
    {
        $this->request  = $request;

        /*
        $controllers    = explode('.', $this->request->controller());
        $version        = strtolower($controllers[0]);
        $controller     = strtolower($controllers[1]);
        $action         = strtolower($this->request->param('method'));
        $obj            = ucwords($this->request->action());
        $this->key      = "{$controller}_{$action}";
        $name_space     = "app\\api\\logic\\{$controller}\\{$version}\\{$obj}";
*/


        if (is_array($request)) {   //兼容web端
            $tmp            = explode('/', $request['pathinfo']);
            $obj            = ucwords($tmp[2]);
            $action         = strtolower($request['action']);
            $name_space     = "app\\api\\logic\\{$tmp[0]}\\{$tmp[1]}\\{$obj}";
            $this->key      = $request['pathinfo'];
        } else {
            $tmp            = explode('/', $request->pathinfo());
            $obj            = ucwords($tmp[2]);
            $action         = strtolower($this->request->action());
            $name_space     = "app\\api\\logic\\{$tmp[0]}\\{$tmp[1]}\\{$obj}";
            $this->key      = $request->pathinfo();
        }

        if (is_callable([$name_space, $action])) {
            $this->key  = "{$name_space}_{$action}";
            $class  = new $name_space;
            self::$map[$this->key]  = call_user_func_array([$class, $action], []);
            if (!empty($this->required)) self::$map[$this->key] = array_merge(self::$map[$this->key], $this->required);
        }

    }


    /**
     * 获取实例
     *
     * @param array|object|string $request
     * @return static
     */
    public static function getInstance($request)
    {
        if (false == self::$instance instanceof self) self::$instance = new static($request);
        return self::$instance;
    }

    /**
     * 进行验证
     *
     * @return array|string
     */
    public function verify()
    {
        if (isset(self::$map[$this->key]) && !empty(self::$map[$this->key])) {
            foreach (self::$map[$this->key] as $k => $v) {
                if (is_array($this->request)) {
                    if (!array_key_exists($k, $this->request['param'])) {
                        $this->errorMsg = "{$v}为必传参数！";
                        break;
                    }
                } else {
                    if (!array_key_exists($k, $this->request->param())) {
                        $this->errorMsg = "{$v}为必传参数！";
                        break;
                    }
                }
            }
        }
        return $this->errorMsg;
    }

    /**
     * 设置必传参数
     *
     * @param array $data
     * @return $this
     */
    public function setRequired(array $data)
    {
        $this->required = $data;
        return $this;
    }
    
    /**
     * 获取错误信息
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->errorMsg;
    }
}