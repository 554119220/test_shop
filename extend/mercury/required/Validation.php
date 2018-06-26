<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16 0016
 * Time: 9:34
 */

namespace mercury\required;
use app\common\traits\F;
use function app\work\controller\edit;
use mercury\constants\Code;
use mercury\ResponseException;


/**
 * validate验证类
 *
 * Class Validation
 * @package mercury\required
 */
class Validation
{

    /**
     * @var object $instance
     */
    public static $instance;

    /**
     * @var string $path_info
     * @var string $action
     * @var object $vInstance
     * @var array $data
     * @var string $error
     * @var string $object
     */
    protected $path_info, $action, $vInstance, $data = [], $error = true, $object;

    const NAME_SPACE_PRE    = "\\app\\api\\validate\\";

    /**
     * @var array MAPPING_VAR   如果有openid则不在验证user_id
     */
    const MAPPING_VAR       = [
        'user_id'
    ];

    /**
     * Validation constructor.
     * @param string $path_info 命名空间的path info
     * @param string $action   验证方法
     * @param array $data   需要验证的数据
     */
    public function __construct($path_info, $action, array $data = [])
    {
        if (strpos($path_info, '/') === 0) $path_info = substr($path_info, 1);
//        $this->path_info    = strtolower(str_replace(['.', '/'], '\\', $path_info));
        $this->path_info    = str_replace(['.', '/'], '\\', $path_info);
        $this->action       = $action;
        $this->data         = $data;
        $firstStr           = strripos($this->path_info, '\\') + 1;
        $this->path_info[$firstStr] = ucfirst($this->path_info[$firstStr]);
        $this->object       = "\\app\\api\\validate\\{$this->path_info}";
    }

    /**
     * 验证
     *
     * @return bool|mixed
     */
    public function check(array $data = [])
    {
        if ($this->isCallback()) {
            if (!empty($data)) $this->data = $data;
            $this->vInstance    = new $this->object();
            if (!empty($this->vInstance->scene[$this->action])) {
                #   排除卖家及买家
                if (in_array('user_id', $this->vInstance->scene[$this->action]) ||
                    in_array('seller_user_id', $this->vInstance->scene[$this->action])) {
                    $this->vInstance->scene[$this->action] = array_filter($this->vInstance->scene[$this->action], function ($val) {
                        return (false === strpos($val, 'user_id'));
                    });
                    #   必须登陆
                    if (!isset(request()->user)) return Code::CODE_UNAUTHORIZED;
                }
                if (!empty($this->vInstance->scene[$this->action])) {
                    $flag   = $this->vInstance->scene($this->action)->check($this->data);
                    if (false == $flag) $this->error = $this->vInstance->getError();
                }
            }
            //$this->error    = call_user_func_array([(new $this->object()), $this->action], ['data' => $this->data]);
        }
        return $this->error;
    }

    /**
     * 是否可以执行
     *
     * @return bool
     */
    public function isCallback()
    {
        $file   = str_replace('\\', '/', $this->object);
        $file   = ROOT . "{$file}.php";
        return file_exists($file);
        //return is_callable([$this->object, $this->action]);
    }

    /**
     * 获取实例
     *
     * @param $path_info
     * @param $action
     * @param array $data
     * @return static
     */
    public static function getInstance($path_info, $action, array $data = [])
    {
        return new static($path_info, $action, $data);
    }

    /**
     * 设置path info
     *
     * @param $path_info
     * @return $this
     */
    public function setPathInfo($path_info)
    {
        $this->path_info    = $path_info;
        return $this;
    }

    /**
     * 设置action
     *
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action   = $action;
        return $this;
    }

    /**
     * 设置验证数据
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
}