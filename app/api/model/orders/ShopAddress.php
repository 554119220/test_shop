<?php
namespace app\api\model\orders;
use app\api\model\tools\District;
use app\api\model\orders\traits\Address;

/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-11-25 04:58:39
 */
class ShopAddress extends \think\Model
{
    use Address;
    protected $pk = 'address_id';
    protected $append = ['province_name', 'city_name', 'district_name', 'town_name'];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'address_create_time';
    protected $updateTime = 'address_update_time';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $auto = [];
    protected $insert = [ 'address_id' ];
    protected $update = [];


    /**
     ****************************************************************************************************
     * 修改器 - insert&update 自动完成 *****************************************************************
     ****************************************************************************************************
     */




    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动完成 *******************************************************************
     ****************************************************************************************************
     */
    public function getProvinceNameAttr($value, $data)
    {
        if (!empty($data['address_province_id'])) {
            $ret = $this->getAreaModel()->getDistrictInfo($data['address_province_id']);
            return $ret['a_name'];
        }
        return '';
    }

    public function getCityNameAttr($value, $data)
    {
        if (!empty($data['address_city_id'])) {
            $ret = $this->getAreaModel()->getDistrictInfo($data['address_city_id']);
            return $ret['a_name'];
        }
        return '';
    }

    public function getDistrictNameAttr($value, $data)
    {
        if (!empty($data['address_district_id'])) {
            $ret = $this->getAreaModel()->getDistrictInfo($data['address_district_id']);
            return $ret['a_name'];
        }
        return '';
    }

    public function getTownNameAttr($value, $data)
    {
        if (!empty($data['address_town_id'])) {
            $ret = $this->getAreaModel()->getDistrictInfo($data['address_town_id']);
            return $ret['a_name'];
        }
        return '';
    }

    /**
     * 获取district模型
     *
     * @return District
     */
    private function getAreaModel()
    {
        return new District();
    }


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


}