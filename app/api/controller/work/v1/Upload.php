<?php
namespace app\api\controller\work\v1;
use app\api\controller\work\v1\Init;
use enhong\Tfs;
class Upload extends Init
{
    public function _initialize()
    {
        parent::_initialize();
        $qiniu_file = array(
            'Config.php',
            'Auth.php',
            'Etag.php',
            'functions.php',
            'Http/Client.php',
            'Http/Error.php',
            'Http/Request.php',
            'Http/Response.php',
            'Processing/Operation.php',
            'Processing/PersistentFop.php',
            'Storage/BucketManager.php',
            'Storage/FormUploader.php',
            'Storage/ResumeUploader.php',
            'Storage/UploadManager.php',
            'Zone.php',
        );
        foreach($qiniu_file as $val){
            $url = 'Qiniu.'.str_replace('/','.',substr($val,0,-4));
            vendor($url);
        }

        config('qiniu',config('cfg.qiniu'));
    }

    /**
     * 上传图片 base64格式
     * 2017-06-07
     */
    public function imagesBase64($check=1){
        if($check == 1) {
            $res = $this->check('filebody,type');
            if($res['code'] != 1) return $this->ret($res);
        }

        if($this->post['type'] != 'image/jpeg') return $this->ret(['code' => 0,'msg' => '文件格式错误！']);


        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $this->post['filebody'], $result)){
            $filebody = base64_decode(str_replace($result[1], '', $this->post['filebody']));
        }else $filebody = base64_decode($this->post['filebody']);

        //充许上传格式
        $ext_arr    = array('gif','jpg','png');

        //充许上传文件大小，限制3M
        $maxsize    = 1024 * 1024 * 3;
        $filesize   = strlen($filebody);
        if($filesize > $maxsize){
            return $this->ret(['code' => 0,'msg' => '图片文件最大不能超过3M']);
        }

        //七牛接口初始化
        $auth   = new \Qiniu\Auth(config('qiniu.ak'), config('qiniu.sk'));
        $token  = $auth->uploadToken(config('qiniu.bucket'));
        $Config = new \Qiniu\Config();
        $qn     = new \Qiniu\Storage\UploadManager();

        list($ret, $err) = $qn->put($token, null, $filebody,$Config);
        //file_put_contents('a.txt',var_export($ret,true));

        if ($err != null) {
            //echo "上传失败。错误消息：".$err->message();
            return $this->ret(['code' => 0,'msg' => '上传失败！'.$err->message()]);
        }else{
            $url = config('qiniu.domain').'/'.$ret['key'];
            return $this->ret(['code' => 1,'data' => ['url' => $url]]);
        }

    }

    /**
     * 文件流上传文件
     * @param int $check
     */
    public function images($check=1){
        if($check == 1) {
            $res = $this->check('filebody');
            if($res['code'] != 1) return $this->ret($res);
        }

        //if($this->post['type'] != 'image/jpeg') return $this->ret(['code' => 0,'msg' => '文件格式错误！']);


        $filebody = $this->post['filebody'];

        //充许上传格式
        $ext_arr    = array('gif','jpg','png');

        //充许上传文件大小，限制3M
        $maxsize    = 1024 * 1024 * 3;
        $filesize   = strlen($filebody);
        if($filesize > $maxsize){
            return $this->ret(['code' => 0,'msg' => '图片文件最大不能超过3M']);
        }

        //七牛接口初始化
        $auth   = new \Qiniu\Auth(config('qiniu.ak'), config('qiniu.sk'));
        $token  = $auth->uploadToken(config('qiniu.bucket'));
        $Config = new \Qiniu\Config();
        $qn     = new \Qiniu\Storage\UploadManager();

        list($ret, $err) = $qn->put($token, null, $filebody,$Config);
        //file_put_contents('a.txt',var_export($ret,true));

        if ($err != null) {
            //echo "上传失败。错误消息：".$err->message();
            return $this->ret(['code' => 0,'msg' => '上传失败！'.$err->message()]);
        }else{
            $url = config('qiniu.domain').'/'.$ret['key'];
            return $this->ret(['code' => 1,'data' => ['url' => $url]]);
        }

    }


    /**
     * name:TFS文件上传，表单POST方式上传文件
     * api:work.v1.upload/tfsFormUploadImages
     * day:2017-08-19
     * author:lazycat
     * content:filebody不加入签名，上传格式只支持.png|.jpg|.gif，最大不可超过5M
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <filebody>   <string>    <1>     <上传文件表单名>
     */
    public function tfsFormUploadImages($check=1){
        if(!isset($_FILES['filebody'])) return $this->ret(['code' => 0,'msg' => '请文件不能为空！']);

        $ext = strtolower(pathinfo($_FILES['filebody']['name'], PATHINFO_EXTENSION));
        $ext = strstr($ext,'.') ? $ext : '.'.$ext;
        $extArr = ['.png','.jpg','.gif','.jpeg'];
        if(!in_array($ext,$extArr)) return $this->ret(['code' => 0,'不支持该类型文件上传！']);

        //充许上传文件大小，限制5M
        $maxsize    = 1024*1024*5;
        $filesize   = filesize($_FILES['filebody']['tmp_name']);
        if($filesize > $maxsize) return $this->ret(['code' => 0,'msg' => '图片大小不可超过5M']);

        $res = Tfs::upload(file_get_contents($_FILES['filebody']['tmp_name']),$ext);

        return $this->ret($res);
    }

    /**
     * name:TFS文件上传，Base64方式上传文件
     * api:work.v1.upload/tfsBase64UploadImages
     * day:2017-08-19
     * author:lazycat
     * content:filebody不加入签名，上传格式只支持.png|.jpg|.gif，最大不可超过5M
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <filebody>   <string>    <1>     <base64编码后的图片>
     * <ext>        <string>    <1>     <扩展名>
     */
    public function tfsBase64UploadImages($check=1){
        if($check == 1) {
            $res = $this->check('ext',1,'filebody');
            if($res['code'] != 1) return $this->ret($res);
        }

        if(!isset($this->post['filebody']) || empty($this->post['filebody'])) return $this->ret(['code' => 0,'msg' => '请文件不能为空！']);
        $filebody = base64_decode($this->post['filebody']);

        $ext = strtolower($this->post['ext']);
        $ext = strstr($ext,'.') ? $ext : '.'.$ext;
        $extArr = ['.png','.jpg','.gif'];
        if(!in_array($ext,$extArr)) $this->ret(['code' => 0,'不支持该类型文件上传！']);

        //充许上传文件大小，限制5M
        $maxsize    = 1024*1024*5;
        $filesize   = strlen($filebody);
        if($filesize > $maxsize) return $this->ret(['code' => 0,'msg' => '图片大小不可超过5M']);

        $res = Tfs::upload($filebody,$ext);

        return $this->ret($res);
    }

}
