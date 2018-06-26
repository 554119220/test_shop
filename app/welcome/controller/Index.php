<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/4 0004
 * Time: 14:52
 */

namespace app\welcome\controller;


use app\api\model\orders\OrdersShop;
use app\common\traits\F;
use mercury\async\Gearman;
use mercury\constants\NoPre;
use mercury\constants\State;
use mercury\editor\UEditor;
use mercury\factory\Factory;
use mercury\hook\Login;
use mercury\notice\Notice;
use mercury\required\Validation;
use mercury\ResponseException;
use mercury\rpc\Client;
use mercury\search\ElasticSearch;
use think\Exception;
use think\Hook;
use think\Loader;
use think\session\driver\Memcache;
use think\session\driver\Memcached;

class Index
{
    /**
     * @return \think\response\Viewprotected
     * 'bind' =>
     * array (size=3)
     * 'data' =>
     * array (size=2)
     * 'method' => string 'post' (length=4)
     * 'type' => string 'get' (length=3)
     * 'user' =>
     * array (size=2)
     * 'username' => string 'mercury' (length=7)
     * 'password' => int 123456
     * 'app' =>
     * array (size=2)
     * 'app_id' => int 1
     * 'app_key' => int 123
     */
    public function index()
    {
        $user = F::dataDetail(F::mApi('user','User'),[
            'where' => [
                'openid' => '62b33320-bc50-8953-747e-b2f24ad740b9',
            ],
        ]);
        //dump($user);
//        dump(F::matchShoppingScore());
//        $params = ['text' => 'xxx'];
//        Hook::add('test', []);
//        $tags   = Hook::get();
//        dump($tags);
//        Hook::exec(Login::class, 'index', $params);
//        $a = hook(\addons\test\controller\Index::class, 'index', $params);
//        echo($a);
//        Hook::listen('')
        //$client = new Client('http://api.zrshop.com/user/v1/login');
        //phpinfo();
        /*try {
            $client = F::yarClient('v1/user/login?openid=213');
            //$client = new Client('http://rpc.zrshop.com/');
            $ret = $client->index1(['method' => 'post', 'type' => 'get']);
            //dump($ret);
            //F::writeLog('人们');
        } catch (\Yar_Client_Exception $e) {
            echo($e);
        }
        //$user   = ['username' => 'mercury', 'password' => 123456];
        //session('user', $user);
        //dump(session('user'));

        $e = UEditor::getInstance()->removeToolbar(['anchor', 'undo', 'redo', 'charts']);

        echo State::STATE_ORDERS_PAY;
        $a = array(
            0 =>
                array(
                    'data' =>
                        array(
                            'method' => 'post',
                            'type' => 'get',
                        ),
                    'user' =>
                        array(
                            'username' => 'mercury',
                            'password' => 123456,
                        ),
                    'app' =>
                        array(
                            'app_id' => 1,
                            'app_key' => 123,
                        ),
                ),
        );
        */
//        dump($a);
//        foreach ($a[0] as $k => $v) {
//            request()->bind($k, $v);
//        }
        //dump(request());
        //session('user', ['nick' => 'mercury', 'mobile' => '18576380995']);
        //$a = array(    'method' => 'POST',    'domain' => NULL,    'url' => NULL,    'baseUrl' => NULL,    'baseFile' => NULL,    'root' => NULL,    'pathinfo' => 'v1/user',    'path' => 'v1/user',    'routeInfo' =>    array (   ),    'env' => NULL,    'dispatch' =>    array (     'type' => 'module',     'module' =>      array (       0 => 'rpc',       1 => 'v1.user',       2 => NULL,     ),   ),    'module' => 'rpc',    'controller' => 'V1.user',    'action' => 'index',    'langset' => 'zh-cn',    'param' =>    array (     'openid' => '213',     'nick' => '1',     'pass' => '2',     'tmp_1' => '213',     'session_id' => '9e5upblr3mo3g7uqua5q0vjtl0',     'æC¿v' => '',   ),    'get' =>    array (     'openid' => '213',     'nick' => '1',     'pass' => '2',     'tmp_1' => '213',     'session_id' => '9e5upblr3mo3g7uqua5q0vjtl0',   ),    'post' =>    array (     'æC¿v' => '',   ),    'request' =>    array (   ),    'route' =>    array (   ),    'put' => NULL,    'session' =>    array (   ),    'file' =>    array (   ),    'cookie' =>    array (   ),    'server' =>    array (     'REDIRECT_STATUS' => '200',     'HTTP_HOST' => 'rpc.zrshop.com',     'HTTP_ACCEPT' => '*/*',     'HTTP_USER_AGENT' => 'PHP Yar Rpc-1.2.3',     'HTTP_CONNECTION' => 'close',     'HTTP_HOSTNAME' => 'rpc.zrshop.com',     'CONTENT_LENGTH' => '202',     'CONTENT_TYPE' => 'application/x-www-form-urlencoded',     'PATH' => 'C:\\WINDOWS\\system32;C:\\WINDOWS;C:\\WINDOWS\\System32\\Wbem;C:\\WINDOWS\\System32\\WindowsPowerShell\\v1.0\\;F:\\box\\bin;D:\\Program Files\\Git\\cmd;D:\\Program Files (x86)\\bin;C:\\wamp\\bin\\php\\php5.6.25\\php.exe;C:\\wamp\\bin\\php\\php5.6.25;D:\\Program Files (x86)\\composer;C:\\WINDOWS\\system32\\config\\systemprofile\\AppData\\Local\\Microsoft\\WindowsApps',     'SystemRoot' => 'C:\\WINDOWS',     'COMSPEC' => 'C:\\WINDOWS\\system32\\cmd.exe',     'PATHEXT' => '.COM;.EXE;.BAT;.CMD;.VBS;.VBE;.JS;.JSE;.WSF;.WSH;.MSC',     'WINDIR' => 'C:\\WINDOWS',     'SERVER_SIGNATURE' => '<address>Apache/2.4.23 (Win32) PHP/5.6.25 Server at rpc.zrshop.com Port 80</address> ',     'SERVER_SOFTWARE' => 'Apache/2.4.23 (Win32) PHP/5.6.25',     'SERVER_NAME' => 'rpc.zrshop.com',     'SERVER_ADDR' => '127.0.0.1',     'SERVER_PORT' => '80',     'REMOTE_ADDR' => '127.0.0.1',     'DOCUMENT_ROOT' => 'C:/web/zrshop/public',     'REQUEST_SCHEME' => 'http',     'CONTEXT_PREFIX' => '',     'CONTEXT_DOCUMENT_ROOT' => 'C:/web/zrshop/public',     'SERVER_ADMIN' => 'wampserver@wampserver.invalid',     'SCRIPT_FILENAME' => 'C:/web/zrshop/public/index.php',     'REMOTE_PORT' => '65525',     'REDIRECT_URL' => '/v1/user',     'REDIRECT_QUERY_STRING' => 'openid=213&nick=1&pass=2&tmp_1=213&session_id=9e5upblr3mo3g7uqua5q0vjtl0',     'GATEWAY_INTERFACE' => 'CGI/1.1',     'SERVER_PROTOCOL' => 'HTTP/1.1',     'REQUEST_METHOD' => 'POST',     'QUERY_STRING' => 'openid=213&nick=1&pass=2&tmp_1=213&session_id=9e5upblr3mo3g7uqua5q0vjtl0',     'REQUEST_URI' => '/v1/user?openid=213&nick=1&pass=2&tmp_1=213&session_id=9e5upblr3mo3g7uqua5q0vjtl0',     'SCRIPT_NAME' => '/index.php',     'PATH_INFO' => '/v1/user',     'PATH_TRANSLATED' => 'redirect:\\index.php\\v1\\user\\user',     'PHP_SELF' => '/index.php/v1/user',     'REQUEST_TIME_FLOAT' => 1509933023.6670001,     'REQUEST_TIME' => 1509933023,   ),    'header' =>    array (     'host' => 'rpc.zrshop.com',     'accept' => '*/*',     'user-agent' => 'PHP Yar Rpc-1.2.3',     'connection' => 'close',     'hostname' => 'rpc.zrshop.com',     'content-length' => '202',     'content-type' => 'application/x-www-form-urlencoded',   ),    'mimeType' =>    array (     'xml' => 'application/xml,text/xml,application/x-xml',     'json' => 'application/json,text/x-json,application/jsonrequest,text/json',     'js' => 'text/javascript,application/javascript,application/x-javascript',     'css' => 'text/css',     'rss' => 'application/rss+xml',     'yaml' => 'application/x-yaml,text/yaml',     'atom' => 'application/atom+xml',     'pdf' => 'application/pdf',     'text' => 'text/plain',     'image' => 'image/png,image/jpg,image/jpeg,image/pjpeg,image/gif,image/webp,image/*',     'csv' => 'text/csv',     'html' => 'text/html,application/xhtml+xml,*/*',   ),    'content' => NULL,    'filter' => '',    'bind' =>    array (     'user' =>      array (       'nick' => 'mercury',     ),     'params' =>      array (       'user' => 'mercury',       'password' => '123465',     ),   ),    'input' => 'æC¿v' . "\0" . '' . "\0" . '€ßì`' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . '' . "\0" . 'xPHP' . "\0" . 'YAR_a:3:{s:1:"i";i:-431767690;s:1:"m";s:6:"index1";s:1:"p";a:2:{i:0;a:2:{s:4:"nick";i:1;s:4:"pass";i:2;}i:1;i:213;}}',    'cache' => NULL,    'isCheckCache' => NULL, );
        //$a = 'a:3:{s:1:"i";i:-1947709231;s:1:"m";s:6:"index1";s:1:"p";a:2:{i:0;a:2:{s:4:"nick";i:1;s:4:"pass";i:2;}i:1;i:213;}}';
        //dump(unserialize($a));

        //dump($a);
        //$b = unserialize('YAR_a:{s:1:"i";i:-431767690;s:1:"m";s:6:"index1";s:1:"p";a:2:{i:0;a:2:{s:4:"nick";i:1;s:4:"pass";i:2;}i:1;i:213;}}');
        //dump($b);
        //dump(session('user'));
        //dump($ret);
        //Notice::getInstance('sms', ['to' => '14444447895', 'content' => '注册，您的验证码是{code}'])->send();
        return view();
    }
}
/*
    public function test()
    {
//        echo 1;
//        $flag   = F::writeLogByMongoDb('api', [1,2,3,4], 'test');
//        dump($flag);
        $logs   = [
            //'table' => 'test',
            'content'   => 'your verify code is 965741',
            'subject'   => '18576380995',
            'to'        => '309371064@qq.com'
        ];
        $flag   = Gearman::getInstance()->addTask(Gearman::FUNCTION_SEND_EMAIL, $logs);
        dump($flag);
        $logs1   = [
            'content'   => 'your verify code is 999999',
            'to'        => '18576380995'
        ];
        //$flag1   = Gearman::getInstance()->addTask(Gearman::FUNCTION_SEND_SMS, $logs1);
        //dump($flag1);
        $logs3  = [
            'table' => 'test',
            'suffix'=> true
        ];
        $flag2  = F::gearmanLogs('api', array_merge($logs3, $logs, $logs1));
        //$flag2  = Gearman::getInstance()->addTask(Gearman::FUNCTION_WRITE_LOG, array_merge($logs3, $logs, $logs1));
        dump($flag2);
    }

    public function test1()
    {
        return json($_COOKIE);
    }

    public function put()
    {
        $flag   = F::beanstalkPut('xxx', [1,2,3]);
        dump($flag);
    }

    public function validate()
    {
//        $error  = Validation::getInstance('', 'login')->check(['a' => 1, 'user_mobile' => 1, 'user_username' => 'mercury']);
//        dump($error);
        //$flag   = F::sendMail(['to' => 'xueycan@foxmail.com', 'subject' => '测试邮件', 'content' => '测试邮件']);
        $flag   = Notice::getInstance('email', ['to' => 'xueycan@foxmail.com', 'subject' => '测试邮件', 'content' => '测试邮件'])->send();
        dump($flag);
    }

    public function search()
    {
        //$search = new ElasticSearch();
        /*
        $cache = new \Memcached();
        //new Memcached();
        $flag = $cache->addServer('127.0.0.1', 11211);
        dump($flag);

        $memcached = new \think\session\driver\Memcached();
        dump($memcached);
    }
}
*/