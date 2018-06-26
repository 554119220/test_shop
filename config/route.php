<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

    '__domain__' => [
        'api'   => 'api',
        'www'   => 'welcome',
        'wap'   => 'wap',
        'work'  => 'work',
        'lzy'   => 'lzy',
        'rpc'   => 'rpc',
        'seller'=> 'seller',
        'settled'=> 'settled',
        's'     => 'search',
        'test'  => 'test',
        'news'  => 'news',
        'help'  => 'help',
        'doc'   => 'doc',
        'ads'   => 'ads',
        'oauth2'   => 'oauth',
    ],

    'addons' => Route::any('addons/:name/:controller/:action', function ($name, $controller, $action) {
        $controller = $controller ? ucfirst($controller) : 'Index';
        $action     = $action ? : 'index';
        return hook("\\addons\\{$name}\\controller\\{$controller}", $action, []);
    }),

    /**
     * 数据提交
     */
    'api'   => Route::post('api', function (){
        //生成签名,取得header，带上必传参数,控制器，方法名
        if (request()->isPost() || request()->isAjax()) {
            try {
                $s              = microtime(true);

                #   禁用关键词

                $headers        = request()->header();
                $crypt_api      = $headers['content-redirect']; //获取API
                $access_token   = $headers['content-accesstoken'];  //获取access token
                $token          = session('__token__');
                $authApi        = new \mercury\auth\api\AuthApi($crypt_api, $token);    //实例化对象
                #   暂时关闭

                if (false == $authApi->verifyAccessToken($access_token)) {  //验证access token
//                    throw new \mercury\ResponseException(\mercury\constants\Code::CODE_OTHER_FAIL, '请刷新页面再次提交');
                }

                //获取原始api
                //实例化对象
                $factory    = new \mercury\factory\Factory($authApi->getApi());

                //验证必传参数
//                $required   = \mercury\required\Validation::getInstance(
//                    $factory->getController(),
//                    $factory->getAction(),
//                    request()->param())->check();
//
//                if (true !== $required)
//                    throw new \mercury\ResponseException(\mercury\constants\Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY, $required);
                $ve     = microtime(true);
                //rpc 调用api
                //绑定参数
                //request()->bind('data', request()->param());
                //绑定用户信息
                //request()->bind('user', session('user'));
                //执行对象
                $ret    = $factory->run();
                //如果执行失败，则返回错误信息
                //执行成功后立即删除当前token
                $e  = microtime(true);
                $ret['ytime']   = $e - $ve;
                $ret['runtime'] = $e - $s;
                $ret['nytime']  = $ve- $s;
            } catch (\mercury\ResponseException $e) {
                $et  = microtime(true);
                $ret   = $e->getData();
                $ret['runtime']    = $et - $s;
            }
            return json($ret);
        }
    }),



    /**
     * 文件上传
     */
    'upload'  => Route::post('upload',function(){
        if (request()->file()) {
            $ret = \mercury\upload\Qiniu::getInstance()->upload(request()->file('file')->getInfo());
            return json($ret);
        } else {
            return [ 'msg' => '不能超过' . ini_get('upload_max_filesize') ];
        }
    }),
    // 图片上传，有限制
    'uploadImage'  => Route::post('uploadImage',function(){
        if (request()->file()) {
            $ret = \mercury\upload\Qiniu::getInstance()->uploadImage(request()->file('file')->getInfo());
            return json($ret);
        } else {
            return [ 'msg' => '不能超过' . ini_get('upload_max_filesize') ];
        }
    }),
    /**
     * 编辑器图片上传
     */
    'ueditors'   => Route::rule('ueditors', function (){
        //仅限于以下方法才能调用，必须是登陆用户才能使用
        $actions    = [
            'config',
            'uploadimage'
        ];
        $action     = request()->get('action');
        if (in_array($action, $actions)) {
            switch ($action) {  //获取配置
                case 'config':
                    $data   = (new \mercury\editor\ueditor\Config())->getConfig();
                    break;
                case 'uploadimage': //上传文件
                    if (!empty($_FILES['upfile'])) {
                        $ret   = \mercury\upload\Qiniu::getInstance()->upload($_FILES['upfile']);
                        /* 返回数据 */
                        $data   = [
                            "state" => strtoupper($ret['info']),          //上传状态，上传成功时必须返回"SUCCESS"
                            "url"   => $ret['data']['url'],            //返回的地址
                            "title" => $ret['data']['key'],          //新文件名
                        ];
                    }
                    break;
            }
            return json($data);
        }
    }),

    /**
     * 验证码
     * @param int $w 图片长度
     * @param int $h 图片宽度
     * @param int $l 文本长度
     */
    'verifyCode'    => Route::get('verifyCode', function() {
        $width  = request()->has('w') ? request()->get('w') : 0;
        $height = request()->has('h') ? request()->get('h') : 0;
        $length = request()->has('l') ? request()->get('l') : 5;
        $config = [
            'imageW'    => $width,
            'imageH'    => $height,
            'length'    => $length,
        ];
        $captcha    = new \think\captcha\Captcha($config);
        return $captcha->entry();
    }),

    #   图片缩略
    'thumb' => Route::get('thumb', function () {
        header('Content-type: image/png');
        $file   = request()->get('src');
        $w      = request()->get('w');
        $h      = request()->get('h');
        $attr   = request()->get('attr');
        $images =  \app\common\traits\F::thumbnail($file, $w, $h);
//        \app\common\traits\F::writeLog($images);
        $imageData  = file_get_contents($images, true);
//        echo "<img {$attr} src='{$images}'>";
        return $imageData;
    }),

    #   重定向
    'redirect'  => Route::get('redirect', function ($domain) {
        if (strpos($domain, 'http') !== 0) {
            $uri = request()->get('uri');
            $url = \app\common\traits\F::domain($domain, $uri);
        } else {
            $url = $domain;
        }
        \app\common\traits\F::gotoUrl($url);
    }),

    #   异步
    'notify'    => Route::post('notify', function () {
        config('app_trace', false);
        \app\common\traits\F::gearmanLogs('erp_pay_notify', request()->param(), true);
        $flag   = \lbzy\sdk\erp\Rsa::pubVerifyPri(input());
        if (true !== $flag) exit("签名校验失败,{$flag}");
//        $controller = new \app\api\controller\orders\v1\Notify();
        $ret    = \mercury\factory\Factory::instance('/orders/v1/notify/pay')->run();
        \app\common\traits\F::gearmanLogs('erp_pay_notify', array_merge(request()->param(), ['ret' => $ret]), true);
//        $ret    = $controller->pay();
//        \app\common\traits\F::gearmanLogs('erp_pay_notify_return', $ret, true);
        if ($ret['code'] == \mercury\constants\Code::CODE_SUCCESS) {
            echo 'success';
        } else {
            echo json_encode($ret);
        }
    }),

    'ads_notify' => Route::post('ads_notify', function () {
        config('app_trace', false);
        $flag   = \lbzy\sdk\erp\Erp::instance()->verifySign(input());
        if (true !== $flag) exit("签名校验失败,{$flag}");
        $ret    = \mercury\factory\Factory::instance('/ads/v1/Ads/pay')->run();
        if ($ret['code'] == \mercury\constants\Code::CODE_SUCCESS) {
            echo 'success';
        } else {
            echo json_encode($ret);
        }
    }),
];
