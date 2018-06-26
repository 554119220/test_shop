<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/17 0017
 * Time: 11:36
 */

namespace app\api\service\orders\v1;


use app\api\model\orders\ShopAddress;
use app\api\model\orders\UserAddress;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\ResponseException;
use traits\think\Instance;

/**
 * Class Address
 * @package app\api\service\orders\v1
 *
 * 收货地址
 */
class Address
{
    use Instance;
    /**
     * 订单收货地址
     *
     *
     * @param int $address_id
     * @param int $orders_id
     * @param string $orders_no
     * @return array|int
     */
    public function orders(array $params)
    {
        try {

//            $address_info   = '广东省广州市白云区人和镇龙归南村高路大巷41号，423606';
//            $address_name   = 'mercury';
//            $address_connect= '';
            //联系方式：1857638****，010-110，309***.QQ.com
            //收件人姓名：mercury
            //$address_model  = new UserAddress();
            //$address    = $address_model->getOrdersAddress(request()->user['user_id'], $params['address_id']);
            $address    = $this->addressDetail(['user_id' => $params['user_id'], 'address_id' => $params['address_id']]);
            if (isset($address['code'])) throw new ResponseException($address['code'], $address['msg']);
            $data   = [
                'orders_id'  => $params['orders_id'],
                'orders_no'  => $params['orders_no'],
                'orders_address_name'   => $address['name'],
                'orders_address'        => $address['street'],
                'orders_address_connect'=> $address['connect'],
            ];

            if (false == db('orders_address')->insert($data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '添加地址失败');
            return [
                'code'  => Code::CODE_SUCCESS,
                'data'  => $address,
            ];
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 退款收货地址
     *
     * @param array $params
     *          int $params['address_id']
     *          int $params['orders_refund_id']
     *          string $params['orders_refund_no']
     *          int $params['user_id']
     * @param int $address_id
     * @param int $orders_refund_id
     * @param string $orders_refund_no
     * @return array|int
     */
    public function refund(array $params)
    {
        try {

            //$address= [];

//            $address_info   = '广东省广州市白云区人和镇龙归南村高路大巷41号，423606';
//            $address_name   = 'mercury';
//            $address_connect= '';
            //联系方式：1857638****，010-110，309***.QQ.com
            //收件人姓名：mercury
            $address    = $this->addressDetail(['user_id' => $params['user_id'], 'address_id' => $params['address_id'], 'is_seller' => true]);
            if (isset($address['code'])) throw new ResponseException($address['code'], $address['msg']);
            $data   = [
                'orders_refund_id'  => $params['orders_refund_id'],
                'orders_refund_no'  => $params['orders_refund_no'],
                'orders_refund_name'=> $address['name'],
                'orders_refund_address' => $address['street'],
                'orders_refund_connect' => $address['connect'],
            ];

            if (false == db('orders_refund_address')->insert($data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '添加地址失败');

            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 售后收货地址
     *
     * @param int $address_id
     * @param int $orders_service_id
     * @param string $orders_service_no
     * @param integer $orders_service_is_seller
     * @param int $user_id
     * @return array|int
     */
    public function service(array $params)
    {
        try {
//            $address= [];
//            $address_info   = '广东省广州市白云区人和镇龙归南村高路大巷41号，423606';
//            $address_name   = 'mercury';
//            $address_connect= '';
            //联系方式：1857638****，010-110，309***.QQ.com
            //收件人姓名：mercury

            $model  = $params['orders_service_is_seller'] == State::STATE_NORMAL ?
                new ShopAddress() :
                new UserAddress();

//            $address= $model->getOrdersAddress($params['user_id'], $params['address_id']);
//            if (isset($address['code'])) throw new ResponseException($address['code'], $address['msg']);
            $address_params = ['user_id' => $params['user_id'], 'address_id' => $params['address_id']];
            if ($params['orders_service_is_seller'] == State::STATE_NORMAL) $address_params['is_seller'] = true;
            $address    = $this->addressDetail($address_params);
            if (isset($address['code'])) throw new ResponseException($address['code'], $address['msg']);

            $data   = [
                'orders_service_id'  => $params['orders_service_id'],
                'orders_service_no'  => $params['orders_service_no'],
                'orders_service_address_name'   => $address['name'],
                'orders_service_address'        => $address['street'],
                'orders_service_address_connect'=> $address['connect'],
                'orders_service_is_seller'      => $params['orders_service_is_seller'],
//                'orders_service_express_time'   => time()
            ];

            if (false == db('orders_service_address')->insert($data))
                throw new ResponseException(Code::CODE_OTHER_FAIL, '添加地址失败');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 地址详情
     *
     * @param bool $is_seller
     * @param int $address_id
     * @param int $user_id
     * @param array $params
     */
    public function addressDetail(array $params)
    {
        try {
            if (isset($params['is_seller'])) {
                $model  = new ShopAddress();
            } else {
                $model  = new UserAddress();
            }
            $map    = [
                'user_id'   => $params['user_id'],
                'address_id'=> $params['address_id'],
                'address_state' => State::STATE_NORMAL,
            ];
            $data   = F::dataDetail($model, ['where' => $map]);
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data['name']   = $data['address_name'];
            $data['connect']= $data['address_mobile'];
            $code   = $data['address_postal_code'] > 0 ? : '';
            $data['street'] = rtrim("{$data['province_name']}{$data['city_name']}{$data['district_name']}{$data['town_name']}{$data['address_street']},{$code}", ',');
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}