<?php
namespace app\api\model\goods;
/**
 * Create Model from Lzy ModelGenerate.
 * @author Lzy
 * @date 2017-12-19 22:23:10
 */
use app\common\traits\F as Fun;
use mercury\constants\State;
use mercury\factory\Factory;
class GoodsExpressTpl extends \think\Model
{
    protected $pk = 'express_id';
    protected $append = [ 'express_type_name', 'ship_address' ];
    protected $resultSetType = 'array';
    protected $autoWriteTimestamp = true;
    protected $createTime = 'express_create_time';
    protected $updateTime = 'express_update_time';
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
     * express_city_id - 所选城市id
     */
    protected function setExpressCityIdAttr($value, $data)
    {
        // return 'asdadsa';
        $city = [];
        // dump($data);exit;
        if ($data['express_is_free'] == State::STATE_DISABLED && false == empty($data['express_content']) ) {
            foreach($data['express_content'] as $key => $value){
                foreach ($value as $ko => $vo) {
                    $city[$key] = array_merge($city[$key] ?? [], $vo['express_city_id']);
                }
            }
        }
        // dump($city);exit;
        return Fun::json($city);
    }

    /**
     * express_type - 计费方式 0 计件 1 计重
     */
    protected function setExpressTypeAttr($value, $data)
    {
        return $value == State::STATE_NORMAL ? State::STATE_NORMAL : State::STATE_DELETE;
    }


    /**
     * express_content - 快递|ems ->子模板内容
     */
    protected function setExpressContentAttr($value, $data)
    {
        return Fun::json($data['express_is_free'] == State::STATE_DISABLED && $value ? $value : []);
    }


    /**
     ****************************************************************************************************
     * 获取器 - select&find 自动处理 *******************************************************************
     ****************************************************************************************************
     */


    /**
     * express_content - 快递|ems ->子模板内容
     */
    protected function getExpressContentAttr($value, $data)
    {
        $content = json_decode($value,true);
        if ( $content ) {
            $area = Factory::instance('/tools/v1/District/provinceCity2')->run()['data'] ?? [];
            foreach ($content as $key => $value) {
                foreach ($value as $ko => $vo) {
                    foreach ($vo['express_city_id'] as $k => $v) {
                        $name1 = $area[$v]['a_name'] ?? '';
                        $name0 = $area[$area[$v]['upid'] ?? 0]['a_name'] ?? '';
                        $content[$key][$ko]['express_city_name'][] = $name0 . '-' . $name1;
                    }
                }
            }
        }
        return $content;
    }

    /**
     * express_city_id - 所选城市id
     */
    protected function getExpressCityIdAttr($value, $data)
    {
        return json_decode($value, true);
    }

    /**
     * express_type_name - 所选城市id
     */
    protected function getExpressTypeNameAttr($value, $data)
    {
        return State::GOODS_EXPRESS_TYPE_ARRAYS[$data['express_type'] ?? ''] ?? '未知';
    }


    function getShipAddressAttr($value, $data)
    {
        $provinceId = $data['express_ship_province_id'] ?? 0;
        $cityId     = $data['express_ship_city_id'] ?? 0;
        $province   = Factory::instance('/tools/v1/district/detail')->run(['id' => $provinceId])['a_name'] ?? "";
        $city       = Factory::instance('/tools/v1/district/detail')->run(['id' => $cityId])['a_name'] ?? "";
        if ( $province && $city ) {
            return $province . '-' . $city;
        }
        return '';
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


    /**
     * 一对一关联 - user - 用户表
     */
    public function User()
    {
        return $this->hasOne("User", "user_id", "seller_user_id");
    }


}