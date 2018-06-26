<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27 0027
 * Time: 16:40
 */

namespace app\common\traits;

use mercury\async\Beanstalkd;
use mercury\auth\api\AuthApi;
use mercury\cache\PRedis;
use mercury\constants\Cache as Ca;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\factory\Factory;
use mercury\notice\Notice;
//use app\work\method\FormTpl;
use mercury\upload\Qiniu;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use think\Db;
use think\Exception;
use think\Image;
use think\Loader;
use think\View;

/**
 * Class F
 * @package app\common\method
 *
 *
 * 外部调用方法集合
 */
class F
{

    use \traits\think\Instance, Api, Base, App, Work, Cache, Yar, Async, Mathematics;
    /**
     * @param string|array $text 需要记录的文本
     * @param $path 存储路径
     * @return bool
     */
    public static function writeLogByFile($text, $filename = true, $path = '', $name = 'app_logger', $level = '')
    {
        //默认文件名为年月日
        if (true === $filename) $filename = date('Ymd');
        //未设置日志文件后缀则加上.log后缀
        if (false === strpos($filename, '.')) $filename .= '.log';
        //实例化对象
        $log    = new Logger($name);
        //设置等级
        $level  = $level ? $level : Logger::DEBUG;
        //设置目录分隔符
        $ds     = DIRECTORY_SEPARATOR;
        //获取根目录
        $app_path   = substr(APP_PATH, 0, strpos(APP_PATH, '.'));
        //临时获取路径
        $tmp_path   = "{$app_path}..{$ds}logger{$ds}";
        //设置日志存储路径及日志文件
        $path   = ($path ? "{$tmp_path}{$path}{$ds}" : $tmp_path) . $filename;
        $log->pushHandler(new StreamHandler($path, $level));
        //创建日志
        if (!is_string($text)) $text = var_export($text, true);
        return $log->addDebug($text);
    }

    public static function writeLog($text, $path = '')
    {
        //如果是正式环境,不写日志
        if (config('url_domain_root') == 'zrst.com') return true;
        if (!is_string($text)) $text = var_export($text, true);
        $filename   = date('Ymd');
        $file       = "{$filename}.txt";
        //设置目录分隔符
        $ds     = DIRECTORY_SEPARATOR;
        //获取根目录
//        $app_path   = substr(APP_PATH, 0, strpos(APP_PATH, '.'));
        //临时获取路径
        //$paths  = "/www/logs/runtime/logs";
        //$paths  = ROOT . "logs/";
        $paths = CACHE_PATH.'/writelog/';
        if (false == is_dir($paths)) mkdir($paths,0777,true);
        if (false == is_dir("{$paths}{$ds}{$path}")) mkdir("{$paths}{$ds}{$path}",0777,true);
        $tmp_path   = "{$paths}{$ds}";
        //设置日志存储路径及日志文件
        //$path   = ($path ? "{$tmp_path}{$path}{$ds}" : $tmp_path) . $file;
        $path   = !empty($path) && strpos($path, $ds) === false ? "{$path}{$ds}" : $path;
        $path   = "{$paths}{$ds}{$path}{$file}";
        $handle     = fopen($path, 'a+');
        fwrite($handle, "{$text}\r\n");
        fclose($handle);
    }

    /**
     * 写入日志到mongodb
     *
     * @param $table
     * @param array $data
     * @param bool $suffix  表后缀 为false则无后缀，为true时则带上年月份，为其他字符串时则直接带上字符串
     * @return int|string
     */
    public static function writeLogByMongoDb($table, array $data, $suffix = false)
    {
        if (true === $suffix) $suffix = date('Ym');
        if ($suffix) $table  .= "_{$suffix}";
        //记录时间
        if (!isset($data['time']) || empty($data['time'])) $data['time'] = date('Y-m-d H:i:s');
        $pre    = config('mongodb.prefix');
        $model  = Db::connect(config('mongodb'));
        return $model->table("{$pre}{$table}")->insert($data);
    }

    /**
     * curl
     *
     * @param $url
     * @param $data
     * @param bool $return_array
     * @param string $header
     * @return mixed|string
     * @throws Exception
     */
    public static function curl($url, $data, $return_array = true, $header = '')
    {
        if (strpos($url, 'http') !== 0)
            throw new Exception('Url不正确');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.62 Safari/537.36');
        if ($header) curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $ret = curl_exec($ch);
//        echo $url;
//        echo $ret;
//        echo curl_error($ch);
//        echo curl_errno($ch);
        curl_close($ch);
        //如果有错误则直接替换变量
        if (false !== strpos($ret, '<title>System Error</title>')) $ret = json_encode(['code' => Code::CODE_OTHER_FAIL, 'error' => $ret]);
        if ($return_array) $ret = json_decode($ret, true);
        return $ret;
    }

    /**
     * 工厂
     *
     * @param string $class     带命名空间
     * @param string $method    方法名称
     * @param array $params     参数
     * @return mixed
     * @throws Exception
     */
    public static function factory($class, $method, array $params = [])
    {
        if (is_callable([$class, $method])) {
            return call_user_func_array([new $class, $method], $params);
        }
        throw new Exception("{$class} not fount");
    }

    /**
     * api model
     *
     * @param $class
     * @param $module
     * @param string $version
     * @return mixed
     */
    public static function apiModel($class, $module, $version = 'v1')
    {
        $class  =   "\\app\\api\\model\\{$module}\\{$class}";
        return new $class;
    }

    /**
     * 逻辑层
     *
     * @param $model
     * @return object
     */
    public static function loaderLogic($model)
    {
        return Loader::model($model, 'logic');
    }

    /**
     * 服务层
     *
     * @param $model
     * @return object
     */
    public static function loaderService($model)
    {
        return Loader::model($model, 'service');
    }

    /**
     * 多级控制器
     *
     * @param $controller
     * @return false|object
     */
    public static function loaderController($controller)
    {
        return Loader::controller($controller);
    }

    /**
     * 针对TP框架内部调用trait
     *
     * @return mixed
     */
//    public static function instance()
//    {
//        return F::instance();
//    }

    /**
     * 生成域名
     *
     * @param $domain
     * @return string
     */
    public static function domain($domain, $path = '')
    {
        //$http   = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
        $http   = request()->scheme();
        $domain_root    = config('url_domain_root');
        if (!empty($path) && $path[0] != '/') $path = "/{$path}";
        return "{$http}://{$domain}.{$domain_root}{$path}";
    }

    /**
     * 生成ERP域名
     *
     * @param $domain
     * @return string
     */
    public static function erpDomain($domain, $path = '')
    {
        $http   = request()->scheme();
        $domain_root    = config('site')['site']['erp_domain'];
        if (!empty($path) && $path[0] != '/') $path = "/{$path}";
        return "{$http}://{$domain}.{$domain_root}{$path}";
    }

    /**
     * 使用redis
     *
     * @param array $config
     * @return \Predis\Client
     */
    public static function redis($config = [])
    {
        $redis  = new PRedis();
        return $redis->client();
//        $host   = isset($config['host']) ? $config['host'] : config('redis.host');
//        $port   = isset($config['port']) ? $config['port'] : config('redis.port');
//        $auth   = isset($config['auth']) ? $config['auth'] : config('redis.auth');
//        $redis  = new \Redis();
//        $redis->connect($host, $port);
//        if ($auth) $redis->auth($auth);
//        return $redis;
    }

    /**
     * 邮件发送
     *
     * @param array $params
     *              subject,content,to
     *              主题，内容，收件人
     * @param array $config
     *              host,user,pass,port
     *              SMTP服务器，用户，密码，端口
     * @return mixed
     */
    public static function sendMail(array $params, array $config = [])
    {
        return Notice::getInstance('email', $params, $config)->send();
    }

    /**
     * 短信发送
     *
     * @param array $params
     *              to,content
     *              接收短信人的手机号码，发送内容
     * @param array $config
     * @return mixed
     */
    public static function sendSms(array $params, array $config = [])
    {
        return Notice::getInstance('sms', $params, $config)->send();
    }

    /**
     * beanstalk入列
     *
     * @param $tube
     * @param string|array $data
     * @param int $pri
     * @param int $delay
     * @param int $ttr
     * @param array $config
     * @return int
     */
    public static function beanstalkPut($tube, $data, $pri = 1024, $delay = 0, $ttr = 60, $config = [])
    {
        return Beanstalkd::getInstance($tube, $config)->put($data, $pri, $delay, $ttr);
    }

    /**
     * 订单数据入列
     *
     * @param $tube
     * @param $id
     * @param $no
     * @param int $delay
     * @return int
     */
    public static function beanstalkOrdersPut($tube, $id, $no, $delay = 0)
    {
        return Beanstalkd::getInstance($tube)->ordersPut($id, $no, $delay);
    }

    /**
     * ERP收货
     *
     * @param $tube
     * @param array $data
     * @param int $delay
     * @return int
     */
    public static function beanstalkErpReceivePut($tube, array $data, $delay = 0)
    {
        return Beanstalkd::getInstance($tube)->erpReceivePut($data, $delay);
    }

    /**
     * 生成单号
     *
     * @param string $pre
     * @param int $len
     * @return string
     * float 1.4385969638824 运行时间【高配服务器有可能会出现重复的问题？(性能太高有可能会)】
     * int 100000 条不重复
     */
    public static function createNo($pre = '', $len = 20)
    {
        $now_time   = explode(' ', microtime());
        list($t1, $t2) = $now_time;
//        $t1 = $t1 * 1000000;
        $t1 = substr($t1, strpos($t1, '.') + 1, 6);
        $t1_len = strlen($t1);
        if ($t1_len < 6) $t1 .= str_repeat(0, 6-$t1_len);
        $t2 = date('YmdHis', $t2);
        $t3 = (string) "{$t2}{$t1}";
        unset($t1, $t2);
        return "{$pre}{$t3}";
    }

    /**
     * 创建字符串
     *
     * @param int $len
     * @return string
     */
    public static function createStr($str = '', $len = 64)
    {
        jsonp();
        $str    = !empty($str) ? $str :  uniqid(rand() . microtime(), true);
        $str    = hash('sha256', $str);
        return strtoupper($str);
    }


    /**
     * 图片裁剪
     *
     * @param string $images
     * @param int|string $w
     * @param string|int $h
     * @return string
     */
    public static function thumbnail($images, $w, $h = '')
    {
        #   判断是否为七牛图片
        if (strpos($images, 'http') !== 0 && strpos($images, '/') !== false) {
            return self::thumb($images, $w, $h);
        }
        if (empty($images)) $images = config('qiniu.default_images');
        if (empty($h)) $h = $w;
        if (strpos($images, 'http') !== 0) {
            $images = self::getImagesDomain() . $images;
        }
        return Qiniu::getInstance()->thumbnail($images, $w, $h);
    }

    /**
     * 本地图片缩略图
     *
     * @param string $file
     * @param int $w
     * @param int $h
     * @return string
     * @throws Exception
     */
    public static function thumb($file, $w, $h = '')
    {
        $root   = ROOT . '/public';
        $file   = "{$root}{$file}";
        if ($h == '') $h = $w;
        #   判断图片是否存在，如果图片不存在则使用默认图片
        if (false == is_file($file)) $file  = "{$root}/static/images/nopic_200x200.png";
        $filename   = pathinfo($file);
        $new_file   = "{$filename['dirname']}/{$filename['filename']}_{$w}×{$h}.{$filename['extension']}";
        if (false == is_file($new_file)) {
            $images = Image::open($file);
            $images->thumb($w, $h);
            $images->save($new_file);
        }
        return str_replace($root, '', $new_file);
    }

    /**
     * 获取图片域名
     *
     * @return mixed
     */
    public static function getImagesDomain()
    {
        $local_host = config('qiniu.local_host');
        return $local_host ? : config('qiniu.qiniu_host');
    }

    /**
     * 获取图片
     * @param  [type] $imagesKey [description]
     * @return [type]            [description]
     */
    public static function getImages($imagesKey){
        # 默认图片
        if ( empty($imagesKey) ) {
            return "/static/images/logo150.png";
        }
        # 已经是图片
        if ('http' == substr($imagesKey, 0, 4)) {
            return $imagesKey;
        }
        # 返回七牛key生成的图片
        return self::getImagesDomain() . $imagesKey;
    }

    /**
     * 获取挂件template
     *
     * @param $template
     * @return string
     */
    public static function setWidgetTemplate($template)
    {
        return "widget/{$template}";
    }

    /**
     * 转到404页面
     *
     * @param string $domain_prefix
     */
    public static function goto404($page = '404')
    {
        $domain = request()->server('HTTP_HOST');
        $domain_prefix = substr($domain, 0, strpos($domain, '.'));
        $path   = "/sorry/{$page}.html";
        if ($domain_prefix == 'wap') $path = "/sorry/m{$page}.html";
        self::gotoUrl($path);
//        $url= F::domain($domain_prefix, $path);
//        $js = "<script>window.location.href = '{$url}'</script>";
//        exit($js);
    }

    /**
     * 跳转的页面
     *
     * @param string $uri 跳转的uri
     */
    public static function gotoUrl($uri)
    {
        $url= strpos($uri, 'http') === 0 ? $uri : (strpos($uri, '/') === 0 ? $uri : "/{$uri}");
        $js = "<script>window.location.href = '{$url}'</script>";
        exit($js);
    }

    /**
     * 分页
     *
     * @param array $data
     * @return string
     */
    public static function pageTemplate(array $data)
    {
        if (isset($data['data']['last_page']) && $data['data']['last_page'] > 1) {
            $path   = config('template.view_path');
            $view   = new View();
            $prev   = $data['data']['current_page'] == 1 ? 0 : $data['data']['current_page'] - 1;
            $next   = $data['data']['current_page'] >= $data['data']['last_page'] ? 0 : $data['data']['current_page'] + 1;
            $vars   = [
                'total'         => $data['data']['total'],
                'per_page'      => $data['data']['per_page'],
                'current_page'  => $data['data']['current_page'],
                'last_page'     => $data['data']['last_page']+1,    #   因为模板for所以+1
                'path_info'     => request()->controller() . '/' . request()->action(),
                'get'           => request()->param(),
                'prev'          => $prev,
                'next'          => $next
            ];
            return $view->fetch("{$path}public/page.html", ['data' => $vars]);
        }
        return '';
    }

    /**
     * 地区选择
     *
     * @var array $params
     *      province,city,district,town
     * @return string
     */
    public static function selectDistrict(array $params = [])
    {
        $provinces  = Factory::instance('/tools/v1/district/index')->run(['id' => 0]);
        $city       = [];
        $district   = [];
        $town       = [];
        if (!empty($params['province'])) {
            $city   = Factory::instance('/tools/v1/district/index')->run(['id' => $params['province']]);
        }
        if (!empty($params['city'])) {
            $district   = Factory::instance('/tools/v1/district/index')->run(['id' => $params['city']]);
        }
        if (!empty($params['district'])) {
            $town   = Factory::instance('/tools/v1/district/index')->run(['id' => $params['district']]);
        }
        $path   = config('template.view_path');
        $view   = new View();
        return $view->fetch("{$path}public/district.html",
            [
                'province'  => $provinces,
                'city'      => $city,
                'district'  => $district,
                'town'      => $town,
                'params'    => $params,
            ]);
    }

    /**
     * 秒数转换时间
     *
     * @param $sec
     * @return false|string
     */
    public static function secToTime($sec, $cat = true)
    {
        if ($cat) {
            $d = '天';
            $h = '时';
            $i = '分';
            $s = '秒';
        } else {
            $d = ' ';
            $h = ':';
            $i = ':';
            $s = '';
        }

        $day    = floor($sec / 60 / 60 / 24);
        $hour   = floor($sec / 60 / 60 % 24);
        $min    = floor($sec / 60 % 60);
        $secs   = floor($sec % 60);
        return "{$day}{$d}{$hour}{$h}{$min}{$i}{$secs}{$s}";
//        if ($sec >= 86400) {
//            return gmdate("d{$d}H{$h}i{$i}s{$s}", $sec);
//        }
//        return gmdate("H{$h}i{$i}s{$s}", $sec);
    }

    /**
     * 记录操作日志
     *
     * @param $controller
     * @param $action
     * @param array $prams
     * @param $user
     * @param $source
     * @param $ret
     * @param $run_time
     * @param int $base
     */
    public static function operationLogs($controller, $action, array $prams, $user, $source, $ret, $run_time, $base = 0)
    {
        $controller = "{$controller}\\{$action}";
        if (isset($ret['data'])) {
            unset($ret['data']);
        }
        $logs   = [
            'controller'    => $base > State::STATE_DISABLED ? base64_encode($controller) : $controller,
            'params'        => $prams,
            'ret'           => $ret,
            'source'        => $source, #   1 api, 0 web
            'base'          => $base,
            'run_time'      => $run_time,   #   执行时间
        ];
        if ($user) {
            $logs['user']   = [
                'nick'      => request()->user['user_nick'],
                'username'  => request()->user['user_username'],
                'mobile'    => request()->user['user_mobile']
            ];
        }
        if ($base) {
            $header     = request()->header();
            $logs['request']    = $header;
        }
        F::gearmanLogs('api', $logs, true);
    }

    /**
     * @title 获取站点所有配置信息
     * @return false|mixed|\PDOStatement|string|\think\Collection
     */
    public static function getSiteConfig()
    {
        $redis  = F::redis();
        $key    = F::getCacheName(\mercury\constants\Cache::CONFIG);
        $config = $redis->get($key);
        if (!$config) {
            $config = db('config_category')->where(['upid' => ['gt', State::STATE_DISABLED]])->select();
            if ($config) {
                foreach ($config as &$item) {
                    $item['config'] = unserialize($item['config']);
                }
            }
            $redis->set($key, serialize($config));
        }
        if (is_string($config)) $config = unserialize($config);
        return $config;
    }

    public static function mod()
    {
    }

    /**
     * @title mongo
     * @param $table
     * @return \think\db\Query
     */
    public static function mongo($table)
    {
        $pre    = config('mongodb.prefix');
        $model  = Db::connect(config('mongodb'));
        return $model->table("{$pre}{$table}");
    }

    /**
     * @title delDb
     * @param $table
     * @return \think\db\Query
     */
    public static function delDb($table)
    {
        return db($table, config('database1'));
    }

    /**
     * @title database1
     * @return \think\db\Connection
     */
    public static function database1()
    {
        return Db::connect(config('database1'));
    }

    public static function matchShoppingScore($amount, $scoreRatio)
    {
        return self::amountCalc(($amount * $scoreRatio) / self::getShoppingScoreRatio(), 0);
    }

    /**
     * @title getShoppingScoreRatio 获取购物积分基数
     * @return float|mixed|string
     */
    public static function getShoppingScoreRatio()
    {
        $key    = \mercury\constants\Cache::TOOLS_SHOPPING_SCORE_RATIO;
        $key    = self::getCacheName($key);
        $redis  = self::redis();
        $value  = $redis->get($key);
        if (!$value) {
            $value  = db('config_fields')->where('name', 'shopping_score_ratio')->value('default') ?? 0.002;
            $redis->set($key, $value);
        }
        return $value * 100;
    }
}