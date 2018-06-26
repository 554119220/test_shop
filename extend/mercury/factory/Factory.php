<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/21 0021
 * Time: 18:14
 */

namespace mercury\factory;
use app\common\traits\F;
use mercury\constants\Cache;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\required\Validation;
use mercury\ResponseException;
use mercury\session\Session;

/**
 * 工厂模式
 *
 * Class Factory
 * @package mercury\factory
 */
class Factory
{
    /**
     * @var string $api
     * @var string $action
     * @var string $controller
     */
    protected $api, $action = 'index', $controller, $write_logs, $redis;
    /**
     * @var object $instance
     */
    public static $instance;
    public function __construct($api, $write_logs = true)
    {
        $this->api  = $api;
//        F::gearmanLogs('debug', ['api' => $api, 'type' => 'debug_api', 'request' => json_encode(request())]);
        $this->write_logs   = $write_logs;
        $this->parseApi();
        $this->redis    = F::redis();
    }

    /**
     * 获取实例
     *
     * @param $api
     * @return static
     */
    public static function instance($api, $write_logs = true)
    {
//        self::$instance = new static($api);
//        return self::$instance;
        return new self($api, $write_logs);
    }

    /**
     * 执行response
     *
     * @return array|mixed
     */
    public function run(array $params = [], $only_params = false)
    {
        #   绑定数据
        $s  = microtime(true);
        request()->bind('user', session('user'));
        $params['ZR_ID']    = Cookies::instance(session('user.user_id'))->getId();
        if ($only_params) {
            request()->bind('data', $params);
        } else {
            if (isset(request()->data)) {
                $params = array_merge(request()->data, $params);
            }
            request()->bind('data', array_merge(request()->param(), $params));
        }
        $key    = F::getCacheName(Cache::SUBMIT_REPEAT_CHECK . md5(http_build_query(request()->data) . $this->api));
        try {
            #   数据验证
            if ($this->redis->exists($key)) {
                throw new ResponseException(Code::CODE_OTHER_FAIL, '不可重复提交');
            } else {
                $this->redis->setex($key, 30, 1);
            }
            if (request()->isAjax() || request()->isPost()) {
                $filter     = Filter::instance(request()->data, $this->api)->check();
                if (true !== $filter) throw new ResponseException(Code::CODE_OTHER_FAIL, "‘{$filter}’ 可能涉及敏感词，请勿使用");
            }
            $required   = $this->validate(request()->data);
            if (true !== $required) {
                #   是否需要登陆
                if ($required == Code::CODE_UNAUTHORIZED) throw new ResponseException($required);
                #   跳转至404页面，404页面待设计
                if (request()->isGet()) {
//                    dump($required);
                    exit();
                    F::goto404();
                }
                #   返回错误信息
                throw new ResponseException(Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY, $required);
            }
            #   解析controller
            $this->parseController();
            #   执行对象
            if (false == is_callable([$this->controller, $this->action])) {
                #   base64 controller
                $base   = true;
                throw new ResponseException(Code::CODE_OTHER_FAIL, '操作对象不存在');
            }

            $ret    = call_user_func_array([new $this->controller(), $this->action], []);
            //$obj    = new $this->controller();
            //$action = $this->action;
            //$ret    = $obj->$action();
            #   判断response信息
            if (is_int($ret) || is_string($ret)) {
                throw new ResponseException($ret);
            } elseif (is_array($ret)) {
                #   如果返回数据不存在code时则为成功
                if (!isset($ret['code'])) {
                    $ret    = array_merge(['data' => $ret], Code::CODE_ARRAY[Code::CODE_SUCCESS], ['code' => Code::CODE_SUCCESS]);
                }
            }
            #   设置session
            if ($ret['code'] == Code::CODE_SUCCESS && isset($ret['data'])) {
                $session = new Session($this->controller, $this->action);
                $session->setSession($ret['data']);
            }
        } catch (ResponseException $e) {
            $ret    = $e->getData();
        }
        #   移除session里面的token
        //if ($ret['code'] == Code::CODE_SUCCESS && (request()->isAjax() || request()->isPost())) session('__token__', null);
        $e  = microtime(true);
        if ($this->write_logs) {
            F::operationLogs(
                $this->controller,
                $this->action,
                request()->data,
                request()->user,
                State::STATE_DISABLED,
                $ret,
                $e - $s,
                isset($base) ? State::STATE_NORMAL : State::STATE_DISABLED);
        }
        $this->redis->del([$key]);
        return $ret;
    }

    /**
     * 获取控制器
     *
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 获取action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * 解析API
     */
    private function parseApi()
    {
        if (strpos($this->api, '/') !== 0) $this->api = "/{$this->api}";
        if (substr_count($this->api, '/') == 4) {
            $last   = strripos($this->api, '/');
            $this->controller   = substr($this->api, 0, $last);
            $lasts  = strripos($this->controller, '/') + 1;
            $this->controller[$lasts]   = strtoupper($this->controller[$lasts]);
            $this->action       = substr($this->api, ++$last);
        } else {
            $this->controller   = $this->api;
        }
    }

    /**
     * 解析controller
     *
     * @return string
     */
    private function parseController()
    {
        $this->controller   = str_replace('/', '\\', $this->controller);
        $this->controller   = "\app\api\controller{$this->controller}";
    }

    /**
     * 数据验证
     *
     * @param array $data
     * @return bool|mixed
     */
    protected function validate(array $data)
    {
        return Validation::getInstance($this->controller, $this->action)->check($data);
    }
}