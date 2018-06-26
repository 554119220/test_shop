<?php
namespace app\api\model\ads;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2018-03-19 11:36:41
 */
use app\common\traits\F as Fun;
class AdsSucai extends \think\Model
{
    protected $pk = 'ads_sucai_id';
    protected $append = ['ads_sucai_images_name'];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'ads_sucai_create_time';
    protected $updateTime = 'ads_sucai_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 和 自动完成 **************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 自定义方法 **************************************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 关联模型 ****************************************************************************************
     ****************************************************************************************************
     */


    protected function getAdsSucaiImagesAttr($value,$data){
        return Fun::getImages((string) $value );
    }

    protected function getAdsSucaiImagesNameAttr($value,$data){
        return ($data['ads_sucai_images'] ?? '') ;
    }

    /**
     * 一对一关联 - user - 用户表
     */
    public function User()
    {
        return $this->hasOne("User", "user_id", "ads_sucai_user_id");
    }


    /**
     * 一对多关联 - AdsSucaiAudit - 审核表
     */
    public function AdsSucaiAudit()
    {
        return $this->hasMany('AdsSucaiAudit','ads_sucai_id','ads_sucai_id')->order('ads_sucai_audit_create_time desc');
    }


}