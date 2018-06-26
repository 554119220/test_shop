<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/6 0006
 * Time: 10:29
 */

namespace app\common\behavior;


use app\common\traits\F;

/**
 * Class Required
 * @package app\common\behavior
 *
 * 已弃用 @Mercury
 */
class Required
{
    protected static $map = [];
    protected $key, $errMessage = true;

    public function run(&$params)
    {
        $controllers    = explode('.', request()->controller());
        $version    = strtolower($controllers[0]);
        $controller = strtolower($controllers[1]);
        $action     = strtolower(request()->param('method'));
        $obj        = ucwords(request()->action());
        $name_space = "app\\api\\logic\\{$controller}\\{$version}\\{$obj}";
        if (is_callable([$name_space, $action])) {
            $this->key  = "{$name_space}_{$action}";
            $class  = new $name_space;
            self::$map[$this->key]  = call_user_func_array([$class, $action], []);
            if (true !== $this->verify(request()->param())) {
                //dump($params);

            }
        }
    }

    /**
     * 参数验证
     *
     * @param array $params
     * @return bool
     */
    private function verify(array $params)
    {
        if (isset(self::$map[$this->key]) && !empty(self::$map[$this->key])) {
            foreach (self::$map[$this->key] as $k => $v) {
                if (!array_key_exists($k, $params)) {
                    $this->errorMsg = "{$v}为必传参数！";
                    break;
                }
            }
        }
        return $this->errMessage;
    }
}