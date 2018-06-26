<?php
namespace lbzy\upload\Qiniu;
use mercury\constants\Code;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use function Qiniu\thumbnail;
use function Qiniu\waterImg;
use app\common\traits\F as Fun;
/**
 * 七牛上传
 */

class Upload
{
	private static $upManager, $auth,$instance;
	private $policy;
	

	private function __construct($config = [])
	{
		# 配置
		$this->config['access_key']		= $config['access_key'] 	?? config('qiniu.access_key');
        $this->config['secret_key'] 	= $config['secret_key'] 	?? config('qiniu.secret_key');
        $this->config['bucket_name']	= $config['bucket_name'] 	?? config('qiniu.bucket_name');
	}

	/**
     * 获取实例
     *
     * @param array $config
     * @return static
     */
    public static function instance(array $config = [])
    {
        if (false == self::$instance instanceof self) self::$instance = new static($config);
        return self::$instance;
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
    private function auth()
    {
        if (false == self::$auth instanceof Auth) self::$auth = new Auth($this->config['access_key'], $this->config['secret_key']);
        return self::$auth;
    }

    /**
     * 设置上传策略
     *
     * @return $this
     */
    public function policy($policy = null)
    {
    	if ( is_null($policy) ) {
			# 上传成功 返回参数设置
	    	$returnBody = [
				# 获得文件保存在空间中的资源名。
				"key" 			=> '$(key)',
				# 文件上传成功后的 HTTP ETag。若上传时未指定资源ID，Etag将作为资源ID使用。
				"hash" 			=> '$(etag)',
				# 上传的原始文件名。
				'fname' 		=> '$(fname)',
				# 资源尺寸，单位为字节。
				'fsize' 		=> '$(fsize)',
				# 资源类型，例如JPG图片的资源类型为 image/jpg。
				'mimeType' 		=> '$(mimeType)',
				# 图片信息
				'imageInfo' 	=> '$(imageInfo)',
				'format'		=> '$(format)',
				'w'				=> '$(imageInfo.width)',
				'h'				=> '$(imageInfo.height)',
				# 文件后缀
				'ext' 			=> '$(ext)',
			];
	    	# 上传策略
	        $this->policy = [
				# 上传返回参数设置
				// 'returnBody' => Fun::json($returnBody),
				// json的值居然不能有单双引号。。。
				'returnBody' => '{"key":$(key),"hash":$(etag),"fname":$(fname),"fsize":$(fsize),"mimeType":$(mimeType),"imageInfo":$(imageInfo),"format":$(format),"w":$(imageInfo.width),"h":$(imageInfo.height),"ext":"$(ext)"}',
			];
    	} else {
    		# 上传策略
    		$this->policy = $policy;
    	}
		return $this;
    }

    /**
     * 上传文件
     * @param $fileInfo
     * @param $filename
     * @return mixed
     */
    public function upload($fileInfo, $filename = null)
    {
    	$this->policy();
    	# 获取token
        $token  = $this->auth()->uploadToken($this->config['bucket_name'], null, 3600, $this->policy);
        # 上传文件,默认二进制流
        # $fileInfo = request()->file('file')->getInfo();
        list($data, $error) = $this->manager()->putFile($token, $filename, $fileInfo['tmp_name']);
        if ($error) {
            return [
            	'code' 	=> Code::CODE_OTHER_FAIL,
            	'msg' 	=> $error,
            	'info'	=> '',
            ];
        }
        # 文件链接地址
        $data['url'] = Fun::getImages($data['key']);
        # 返回结果
        $res = Code::CODE_ARRAY[Code::CODE_SUCCESS];
        $res['data'] = $data;
        return $res;
    }













}