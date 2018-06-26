<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/26 0026
 * Time: 20:40
 */

namespace app\api\model\orders\traits;


use app\common\traits\F;
use mercury\constants\Code;
use mercury\factory\Factory;
use mercury\ResponseException;

trait Address
{
    /**
     * 拼装收货地址
     *
     * @param $user_id
     * @param $id
     * @return array
     */
    public function getOrdersAddress($user_id, $id)
    {
        try {
            $map    = [
                'address_id'    => $id,
                'user_id'       => $user_id,
            ];
            $data   = $this->where($map)->find();
            $district_api   = '/tools/v1/district/getName';
            if (empty($data)) throw new ResponseException(Code::CODE_OTHER_FAIL, '地址不存在');
            $data   = $data->toArray();
            request()->bind('data', ['id' => $data['address_province_id']]);
            $province   = Factory::instance($district_api)->run();
            if ($province['code'] != Code::CODE_SUCCESS)
                throw new ResponseException($province['code'], $province['msg']);
            request()->bind('data', ['id' => $data['address_city_id']]);
            $city       = Factory::instance($district_api)->run();
            if ($city['code'] != Code::CODE_SUCCESS)
                throw new ResponseException($city['code'], $city['msg']);
            request()->bind('data', ['id' => $data['address_district_id']]);
            $district   = Factory::instance($district_api)->run();
            if ($district['code'] != Code::CODE_SUCCESS)
                throw new ResponseException($district['code'], $district['msg']);
            $town       = '';
            if (!empty($data['address_town_id'])) {
                request()->bind('data', ['id' => $data['address_town_id']]);
                $town       = Factory::instance($district_api)->run();
                if ($town['code'] != Code::CODE_SUCCESS) {
                    throw new ResponseException($town['code'], $town['msg']);
                }
                $town   = $town['data'];
            }
            $postal_code= empty($data['address_postal_code']) ? : $data['address_postal_code'];
            $street     = rtrim("{$province['data']}{$city['data']}{$district['data']}{$town}{$data['address_street']},{$postal_code}", ',');
            $ret['name']    = $data['address_name'];
            $ret['street']  = $street;
            $ret['connect'] = rtrim("{$data['address_mobile']},{$data['address_tel']},{$data['address_email']}", ',');
            return $ret;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}