<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7 0007
 * Time: 14:00
 */

namespace mercury\upload;


use mercury\constants\Code;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use function Qiniu\thumbnail;
use function Qiniu\waterImg;
use app\common\traits\F as Fun;

/**
 * 七牛文件上传
 *
 * Class Qiniu
 * @package mercury\upload
 */
class Qiniu
{
    /**
     * @var array 七牛配置
     */
    protected $config       = [], $width, $height;

    /**
     * @var object $upManager  上传实例
     * @var object $auth       认证实例
     * @var object $instance   当前对象实例
     */
    protected static $upManager, $auth, $instance;

    public function __construct(array $config = [])
    {
        $this->config['access_key'] = isset($config['access_key']) ? $config['access_key'] : config('qiniu.access_key');
        $this->config['secret_key'] = isset($config['secret_key']) ? $config['secret_key'] : config('qiniu.secret_key');
        $this->config['bucket_name']= isset($config['bucket_name']) ? $config['bucket_name'] : config('qiniu.bucket_name');
        $this->config['url']        = isset($config['url']) ? $config['url'] :
            (config('qiniu.local_host') ? config('qiniu.local_host') : config('qiniu.qiniu_host'));
    }

    /**
     * 图片裁剪
     *
     * @param string|int $w
     * @param null|int|string $h
     * @return $this
     */
    public function crop($w, $h = null)
    {
        $this->height   = !is_null($h) ? $h : $w;
        $this->width    = $w;
        return $this;
    }

    /**
     * 文件上传
     *
     * @param $data
     * @param $filename
     * @return mixed
     */
    public function upload($data, $filename = null)
    {
        #   检测文件类型
        $flag   = Check::instance($data)->checkType();
        if (true !== $flag) return ['code' => Code::CODE_OTHER_FAIL, 'msg' => $flag];
        $s = microtime(true);
        $token  = $this->auth()->uploadToken($this->config['bucket_name']);
        //上传凭证,上传文件名,上传二进制流
        list($ret, $error) = $this->manager()->putFile($token, $filename, $data['tmp_name']);
        if (null !== $error) {
            return ['code' => Code::CODE_OTHER_FAIL, 'msg' => $error];
        }
        $ret['url']     = $this->config['url'] . $ret['key'];
        $e = microtime(true);
        // 检测图片

//        $check  = Check::instance($data)->checkWidthAndHeight(640, 640);
//
//        $res = Fun::curl($ret['url'] . '?imageInfo',[]);
//        // dump($res);exit;
//        if ( false == isset($res['width']) ) {
//            return ['code' => Code::CODE_OTHER_FAIL, 'msg' => '上传图片错误'];
//        }
        //是否有裁剪
        if ($this->width) {
            $ret['url']     = $this->thumbnail($ret['url'], $this->width, $this->height);
        }
        $ret['run_time']    = $e - $s;
        return array_merge(['data' => $ret], Code::CODE_ARRAY[Code::CODE_SUCCESS]);
    }

    /**
     * 图片上传
     *
     * @param $data
     * @param $filename
     * @return mixed
     */
    public function uploadImage($data, $filename = null)
    {
        #   检测文件类型
        $flag   = Check::instance($data)->checkType();
        if (true !== $flag) return ['code' => Code::CODE_OTHER_FAIL, 'msg' => $flag];

        // 检测图片宽高度
        $flag   = Check::instance($data)->checkWidthAndHeight();
        // dump($flag);
        if (1 != $flag['code']) {
            return [ 'code' => Code::CODE_OTHER_FAIL, 'msg' => $flag['msg'] ];
        }
        $s = microtime(true);
        $token  = $this->auth()->uploadToken($this->config['bucket_name']);
        //上传凭证,上传文件名,上传二进制流
        list($ret, $error) = $this->manager()->putFile($token, $filename, $data['tmp_name']);
        if (null !== $error) {
            return ['code' => Code::CODE_OTHER_FAIL, 'msg' => $error];
        }
        $ret['url']     = $this->config['url'] . $ret['key'];
        $e = microtime(true);

        //是否有裁剪
        if ($this->width) {
            $ret['url']     = $this->thumbnail($ret['url'], $this->width, $this->height);
        }
        $ret['run_time']    = $e - $s;
        unset($flag['code']);
        $ret['w'] = $flag['w'];
        $ret['h'] = $flag['h'];
        return array_merge(['data' => $ret], Code::CODE_ARRAY[Code::CODE_SUCCESS]);
    }

    /**
     * 获取实例
     *
     * @param array $config
     * @return static
     */
    public static function getInstance(array $config = [])
    {
        if (false == self::$instance instanceof self) self::$instance = new static($config);
        return self::$instance;
    }

    /**
     * 图片裁剪
     *
     * /**
     * 其中 <mode> 分为如下几种情况：
    模式	说明
    /0/w/<LongEdge>/h/<ShortEdge>	限定缩略图的长边最多为<LongEdge>，短边最多为<ShortEdge>，进行等比缩放，不裁剪。如果只指定 w 参数则表示限定长边（短边自适应），只指定 h 参数则表示限定短边（长边自适应）。
    /1/w/<Width>/h/<Height>	限定缩略图的宽最少为<Width>，高最少为<Height>，进行等比缩放，居中裁剪。转后的缩略图通常恰好是 <Width>x<Height> 的大小（有一个边缩放的时候会因为超出矩形框而被裁剪掉多余部分）。如果只指定 w 参数或只指定 h 参数，代表限定为长宽相等的正方图。
    /2/w/<Width>/h/<Height>	限定缩略图的宽最多为<Width>，高最多为<Height>，进行等比缩放，不裁剪。如果只指定 w 参数则表示限定宽（长自适应），只指定 h 参数则表示限定长（宽自适应）。它和模式0类似，区别只是限定宽和高，不是限定长边和短边。从应用场景来说，模式0适合移动设备上做缩略图，模式2适合PC上做缩略图。
    /3/w/<Width>/h/<Height>	限定缩略图的宽最少为<Width>，高最少为<Height>，进行等比缩放，不裁剪。如果只指定 w 参数或只指定 h 参数，代表长宽限定为同样的值。你可以理解为模式1是模式3的结果再做居中裁剪得到的。
    /4/w/<LongEdge>/h/<ShortEdge>	限定缩略图的长边最少为<LongEdge>，短边最少为<ShortEdge>，进行等比缩放，不裁剪。如果只指定 w 参数或只指定 h 参数，表示长边短边限定为同样的值。这个模式很适合在手持设备做图片的全屏查看（把这里的长边短边分别设为手机屏幕的分辨率即可），生成的图片尺寸刚好充满整个屏幕（某一个边可能会超出屏幕）。
    /5/w/<LongEdge>/h/<ShortEdge>	限定缩略图的长边最少为<LongEdge>，短边最少为<ShortEdge>，进行等比缩放，居中裁剪。如果只指定 w 参数或只指定 h 参数，表示长边短边限定为同样的值。同上模式4，但超出限定的矩形部分会被裁剪。
     *
     * @param $url
     * @param $w
     * @param $h
     * @return string
     */
    public function thumbnail($url, $w, $h)
    {
        return thumbnail($url, 5, $w, $h);
    }

    /**
     * 图片水印
     *
     * @param $images_url
     * @param $logo_url
     * @return string
     */
    public function water($images_url, $logo_url)
    {
        return waterImg($images_url, $logo_url);
    }

    /**
     * qiniu up manager
     *
     * @return UploadManager
     */
    private function manager()
    {
        if (false == self::$upManager instanceof UploadManager) self::$upManager = new UploadManager();
        return self::$upManager;
    }

    /**
     * qiniu auth
     *
     * @return Auth
     */
    protected function auth()
    {
        if (false == self::$auth instanceof Auth) self::$auth = new Auth($this->config['access_key'], $this->config['secret_key']);
        return self::$auth;
    }
}