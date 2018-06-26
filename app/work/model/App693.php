<?php
/**
 * 此文件由表单生成器创建，所以格式会有点凌乱
 * day:2018-01-31 14:13:52
 */
namespace app\work\model;
use app\common\traits\F;
use think\Model;
class App693 extends Model
{
    protected $table        = 'zr_app';
    protected $createTime   = 'app_create_time';
    protected $updateTime   = 'app_update_time';
    protected $autoWriteTimestamp = true;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $insert       = ['app_key', 'app_secret'];

    protected function setAppKeyAttr()
    {
        return F::createStr();
    }

    protected function setAppSecretAttr()
    {
        return F::createStr();
    }
}
