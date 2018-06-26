<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/25 0025
 * Time: 4:49
 */

namespace app\api\service\orders\v1;


use app\api\model\orders\ShopAddress;
use app\api\model\orders\UserAddress;
use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\Common;
use mercury\constants\State;
use mercury\ResponseException;
use think\Db;

/**
 * 收货地址
 *
 * Class AddressManager
 * @package app\api\service\orders\v1
 */
class AddressManager
{
    /**
     * @var bool $is_seller 是否未商家
     * @var string $user_key 用户字段
     * @var object $model 实例化的模型
     */
    protected $is_seller = false, $user_key, $model, $map = [], $data = [];

    public function __construct()
    {
        if ($this->is_seller) {
            $this->model    = new ShopAddress();
        } else {
            $this->model    = new UserAddress();
        }
        $this->map  = [
            'user_id'   => request()->user['user_id']
        ];

        $this->data = request()->data;
    }

    /**
     * @title 收货地址
     *
     * @param int $user_id
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function index()
    {
        try {
            $data   = $this->model->where($this->map)->order('address_is_default desc, address_id desc')->select();
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 创建收货地址
     *
     * @param int $user_id
     * @param int $province_id
     * @param int $city_id
     * @param int $town_id
     * @param int $street
     * @param int $mobile|tel
     * @param int $name
     * @param int $is_default
     * @return array|int
     */
    public function create()
    {
        try {
            //  查找用户是否有地址
            Db::startTrans();
            #   判断用户目前收货地址个数
            $max    = Common::USER_ADDRESS_MAX_ROWS;
            if ($max <= $this->model->where($this->map)->count())
                throw new ResponseException(Code::CODE_OTHER_FAIL, "最多可有 {$max} 个收货地址");

            #   判断用户是否有默认地址
            $this->map['address_is_default']    = State::STATE_NORMAL;
            $user_address   = $this->model->where($this->map)->column('address_id');
            $default=   isset($this->data['is_default']) &&
            $this->data['is_default'] == State::STATE_NORMAL  ||
            empty($user_address) ? State::STATE_NORMAL : '';
            $data   = [
                'address_province_id'   => $this->data['province_id'],
                'address_city_id'       => $this->data['city_id'],
                'address_district_id'   => $this->data['district_id'],
                'address_town_id'       => $this->data['town_id'],
                'address_street'        => $this->data['street'],
                'user_id'               => $this->map['user_id'],
                //'address_email'         => $this->data['email'],
                'address_mobile'        => $this->data['mobile'],
                //'address_tel'           => $this->data['tel'],
                'address_name'          => $this->data['name'],
                'address_is_default'    => $default,
                'address_postal_code'   => $this->data['postal_code'],
            ];
            if (!empty($user_address) && isset($this->data['is_default']) && !empty($this->data['is_default'])) {
                $flag   = $this->model->save(['address_is_default' => State::STATE_DISABLED], $this->map);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '移除默认地址失败');
            }
            if (empty($data['address_town_id'])) unset($data['address_town_id']);
            if (empty($data['address_email'])) unset($data['address_email']);
            if (empty($data['address_postal_code'])) unset($data['address_postal_code']);
            if (empty($data['address_is_default'])) unset($data['address_is_default']);
            if (empty($data['address_mobile'])) unset($data['address_mobile']);
            if (empty($data['address_tel'])) unset($data['address_tel']);
            $flag   = $this->model->insert($data);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '添加地址失败');
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 修改收货地址
     *
     * @return array|int
     */
    public function modify()
    {
        try {
            Db::startTrans();
            $this->map['address_id']    = $this->data['id'];
            $user_address   = $this->model->where('user_id', $this->map['user_id'])->where('address_is_default', State::STATE_NORMAL)->column('address_id');
            $default=   isset($this->data['is_default']) &&
            $this->data['is_default'] == State::STATE_NORMAL  ||
            empty($user_address) ? State::STATE_NORMAL : '';
            $data   = [
                'address_province_id'   => $this->data['province_id'],
                'address_city_id'       => $this->data['city_id'],
                'address_district_id'   => $this->data['district_id'],
                'address_town_id'       => $this->data['town_id'],
                'address_street'        => $this->data['street'],
                'user_id'               => $this->map['user_id'],
                //'address_email'         => $this->data['email'],
                'address_mobile'        => $this->data['mobile'],
                //'address_tel'           => $this->data['tel'],
                'address_name'          => $this->data['name'],
                'address_is_default'    => $default,
                'address_postal_code'   => $this->data['postal_code'],
            ];
            $default_address    = !empty($user_address[0]) ? $user_address[0] : '';
            #   移除默认地址，如果当前地址为默认地址的话，并且用户移除默认地址的话则需要为用户增加一个默认地址
            if ($this->map['address_id'] == $default_address && (!isset($this->data['is_default']) || $this->data['is_default'] != State::STATE_NORMAL)) {
                #   如果当前修改的地址是默认地址并且用户把当前地址移除默认地址则需要为用户设置一个默认地址
                $flag   = $this->model->limit(1)->save(['address_is_default' => State::STATE_NORMAL], [
                    'user_id'   => $this->map['user_id'],
                ]);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '设置默认地址失败');
            } else if ($this->map['address_id'] != $default_address && (isset($this->data['is_default']) && $this->data['is_default'] == State::STATE_NORMAL)) {
                #   如果用户设置当前地址为默认地址则需要移除之前的默认地址
                $flag   = $this->model->save(['address_is_default' => State::STATE_DISABLED],
                    ['user_id' => $this->map['user_id'], 'address_is_default' => State::STATE_NORMAL]);
                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '移除默认地址失败');
            }

//            if (!empty($user_address) &&
//                isset($this->data['address_is_default']) &&
//                $this->data['address_is_default'] == State::STATE_NORMAL) {
//                $flag   = $this->model->save(['address_is_default' => State::STATE_DISABLED],
//                    ['user_id' => $this->map['user_id'], 'address_is_default' => State::STATE_NORMAL]);
//                if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '移除默认地址失败');
//            }
//            if (empty($data['address_town_id'])) unset($data['address_town_id']);
//            if (empty($data['address_email'])) unset($data['address_email']);
//            if (empty($data['address_postal_code'])) unset($data['address_postal_code']);
//            if (empty($data['address_is_default'])) unset($data['address_is_default']);
//            if (empty($data['address_mobile'])) unset($data['address_mobile']);
//            if (empty($data['address_tel'])) unset($data['address_tel']);
            $flag   = $this->model->save($data, $this->map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '修改地址失败');
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 收货地址详情
     *
     * @param string $openid
     * @param int $id
     * @return array
     */
    public function detail()
    {
        try {
            if (isset(request()->data['id']) && request()->data['id'] > State::STATE_DISABLED) {
                $this->map['address_id']    = request()->data['id'];
            } else {
                $this->map['address_is_default']    = State::STATE_NORMAL;
            }
            $data   = $this->model->where($this->map)->find();
            if (!$data) throw new ResponseException(Code::CODE_NO_CONTENT);
            $data   = $data->toArray();
            return $data;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * @title 设置为默认
     *
     * @param int $user_id
     * @param int $id
     * @return array|int
     */
    public function setDefault()
    {
        try {
            Db::startTrans();
            $this->map['address_id']    = $this->data['id'];
            $flag   = $this->model->save(['address_is_default' => State::STATE_DISABLED], [
                'user_id' => $this->map['user_id'], 'address_is_default' => State::STATE_NORMAL
            ]);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '移除默认地址失败');
            $flag   = $this->model->save(['address_is_default' => State::STATE_NORMAL], $this->map);
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '设置默认地址失败');
            Db::commit();
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            Db::rollback();
            return $e->getData();
        }
    }

    /**
     * @title 删除收货地址
     *
     * @param int $user_id
     * @param int $id
     * @return array|int
     */
    public function delete()
    {
        try {
            $this->map['address_id']    = $this->data['id'];
            $data   = $this->model->where($this->map)->where('address_is_default', State::STATE_NORMAL)->find();
            if ($data) throw new ResponseException(Code::CODE_OTHER_FAIL, '默认地址不可删除');
            $flag   = $this->model->where($this->map)->delete();
            if (false == $flag) throw new ResponseException(Code::CODE_OTHER_FAIL, '删除收货地址失败');
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }
}