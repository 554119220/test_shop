<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/12 0012
 * Time: 13:48
 */

namespace mercury\upload;


use app\common\traits\F;
use think\Image;

class Check
{
    protected $file, $fileType;

    const IMAGE_TYPE    = [
        'jpeg',
        'jpg',
        'png'
    ];

    public static $instance = [];

    public function __construct($file)
    {
        $this->file     = $file;
        $this->fileType = substr($this->file['name'], strripos($this->file['name'], '.') + 1);
    }

    /**
     * @title 获取图片对象
     * @return Image
     */
    protected function getImageObject()
    {
        return Image::open(is_array($this->file) ? $this->file['tmp_name'] : $this->file);
    }

    /**
     * @title 验证图片类型
     * @return bool|string
     */
    public function checkType()
    {
        if (!in_array($this->fileType, self::IMAGE_TYPE)) return '请上传图片';
        return true;
    }

    /**
     * @title 验证宽高度
     * @request 请求参数
     * @return bool|array
     */
    public function checkWidthAndHeight()
    {
        $width  = $this->getImageObject()->width();
        $height = $this->getImageObject()->height();
        // dump($width);
        # 配置数组
        $arr = [
            'goods/create' => [
                [1,1],
            ],
            'goods/edit' => [
                [1,1],
            ],
            'sucai/create' => \mercury\constants\State::ADS_POSITION_SIZE,
            'sucai/update' => \mercury\constants\State::ADS_POSITION_SIZE,
        ];
        # 通过请求的refer判断是否要验证宽高
        $refererPath    = explode('/', parse_url($_SERVER['HTTP_REFERER'] ?? '/',PHP_URL_PATH));
        # 请求的referer 地址
        $refererAction  = $refererPath[1] . '/' . ($refererPath[2] ?? '');
        # 验证
        $sizeArr = $arr[$refererAction] ?? [];
        // dump($refererAction);
        switch ($refererAction) {
            # 商品
            case 'goods/create':
            case 'goods/edit':
                foreach ($sizeArr as $key => $value) {
                    if ($value[0] > $width) {
                        return [ 'code' => 0, 'msg' => "图片宽度必须大于等于" . $value[0] ];
                    }
                    if ($value[1] > $height) {
                        return [ 'code' => 0, 'msg' => "图片高度必须大于等于" . $value[1] ];
                    }
                    if ($width !== $height) {
                        return [ 'code' => 0, 'msg' => '图片宽高不一致' ];
                    }
                }
                break;
            # 素材
            case 'sucai/create':
            case 'sucai/update':
                $flag = false;
                foreach ($sizeArr as $key => $value) {
                    if ($value[0] == $width && $value[1] == $height ) {
                        $flag = true;
                    }
                }
                
                if ( $flag == false ) {
                    return [ 'code' => 0, 'msg' => '图片宽高不符合要求' ];;
                }
                break;
            # 商家店铺设置
            case 'setting/index':
            case 'choice/qualifications':
            case 'choice/setshop':
                if ($width !== $height) {
                    return [ 'code' => 0, 'msg' => '图片宽高不一致' ];
                }
                break;
            default:
                break;
        }
        # 不存在，则不需验证
        return [ 'code' => 1, 'w' => $width, 'h' => $height ];
        
    }

    

    /**
     * @title instance
     * @request 请求参数
     * @param $file
     * @return mixed
     */
    public static function instance($file)
    {
        $key    = md5(http_build_query($file));
        if (!isset(self::$instance[$key]) ||
            false == self::$instance[$key] instanceof self)
            self::$instance[$key] = new static($file);
        return self::$instance[$key];
    }

}