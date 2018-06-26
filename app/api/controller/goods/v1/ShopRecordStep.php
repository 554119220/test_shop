<?php
namespace app\api\controller\goods\v1;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\ShopRecordStep as Model;
use app\api\model\goods\ShopGoodsBrand;
use app\api\model\goods\ShopGoodsCategory;

/**
 * 店铺
 * @author Lzy
 * @date 2017-11-14 10:00:00
 */
class ShopRecordStep
{

    /**
     * 创建店铺
     * @param $shop_name 店铺名称
     * @param $shop_tel 店铺电话
     * @param $shop_mobile 店铺手机号
     * @param # $shop_domain 店铺域名
     * @param $shop_contect_person 联系人
     * @param $shop_province_id 省份
     * @param $shop_city_id 城市
     * @param $shop_district_id 区域
     * @param $shop_town_id 乡镇
     * @param $shop_street 街道详细地址
     * @param $shop_email 电子邮件地址
     * @param $shop_description 店铺介绍
     * @param $user_id 用户id
     * @param $shop_type_id 店铺类型
     * @param $shop_logo 店铺logo
     * 店铺品牌
     * @param $brand
     * 店铺分类
     * @param $category
     */
    function create ()
    {
        try {
            # ...
            $param = request()->data;
            # 品牌处理
            $brand = [];
            # 分类处理
            $category = [];
            # 添加操作
            $model = new Model;
            $model->startTrans();
            $b_m = new ShopGoodsBrand($brand);
            // $c_m = new ShopGoodsCategory($category);
            $s0 = $model->allowField(true)->save($param);
            $s1 = $model->ShopGoodsBrand()->allowField(true)->save($b_m);
            // $s2 = $model->ShopGoodsCategory()->allowField(true)->save($c_m);
            $s3 = db('user')->where(['user_id' => $param['user_id']])->save(['user_shop_id' => $model->shop_id]);
            if ( false == $s0 || false == $s1 || false == $s3 ) {
                $model->rollback();
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            $model->commit();
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }


    /**
     * 店铺详情
     * @param $shop_id
     */
    public function detail()
    {
        try {
            # get param
            $shop_id = request()->data['shop_id'] ?? [];
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # find data
            $shop  = Fun::dataDetail('\\app\\api\\model\\goods\\Shop', $shop_id);
            if ( empty($shop) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 地区信息
            $area = [];
            # 结果处理
//            $shop['province']   = $area[$shop['shop_province_id']];
//            $shop['city']       = $area[$shop['shop_city_id']];
//            $shop['district']   = $area[$shop['shop_district_id']];
//            $shop['town']       = $area[$shop['shop_town_id']];
            # ...
            return $shop;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 更新店铺
     * @param $shop_id
     * @param $shop_name 店铺名称
     * @param $shop_tel 店铺电话
     * @param $shop_mobile 店铺手机号
     * @param $shop_contect_person 联系人
     * @param $shop_province_id 省份
     * @param $shop_city_id 城市
     * @param $shop_district_id 区域
     * @param $shop_town_id 乡镇
     * @param $shop_street 街道详细地址
     * @param $shop_email 电子邮件地址
     * @param $shop_description 店铺介绍
     * @param $shop_logo 店铺logo
     */
    public function update()
    {
        try {
            # get param
            $param = request()->data;
            $shop_id = intval($param['shop_id']) ?? 0;
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # ...
//            $allowField[] = 'shop_tel';
//            $allowField[] = 'shop_mobile';
//            $allowField[] = 'shop_contect_person';
//            $allowField[] = 'shop_province_id';
//            $allowField[] = 'shop_city_id';
//            $allowField[] = 'shop_district_id';
//            $allowField[] = 'shop_town_id';
//            $allowField[] = 'shop_street';
//            $allowField[] = 'shop_email';
//            $allowField[] = 'shop_description';
//            $allowField[] = 'shop_logo';
            foreach ($param as $key=>$value){
                $allowField[] = $key;
            }
            if ( false === (new Model)->allowField($allowField)->save($param,['shop_id'=>$param['shop_id']]) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 设置域名
     * @param $shop_id
     * @param $shop_domain
     */
    public function set_domain ()
    {
        try {
            # get param
            $shop_id = intval(request()->user['shop_id'] ?? 0);
            $shop_domain = request()->data['shop_domain'] ?? '';
            if ( State::STATE_NORMAL > $shop_id ) {
                throw new ResponseException( Code::CODE_PARAMETER_CAN_NOT_BE_EMPTY );
            }
            # ...
            $is = Fun::dataDetail('\\app\\api\\model\\goods\\Shop', [ 'where' => [ 'shop_domain' => $shop_domain ] , 'field' => 'shop_id' ]);
            $model = new Model;
            $model->startTrans();
            # 是否已存在
            $is = $model->where([ 'shop_domain' => $shop_id ])->field('shop_id')->find();
            if ( $is ) {
                $model->rollback();
                throw new ResponseException( Code::CODE_OTHER_FAIL , '域名已存在');
            }
            # 修改失败
            if ( false === $model->where(['shop_id' => $shop_id])->allowField(['shop_domain'])->save(['shop_domain' => $shop_domain]) ) {
                $model->rollback();
                throw new ResponseException( Code::CODE_OTHER_FAIL );
            }
            # ...
            $model->commit();
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    /**
     * 店铺列表
     * @param $pagesize...
     * @param $order...
     * @param $page...
     * @param $q...
     * @param $imgsize...
     * @return [type] [description]
     */
    function shop_list()
    {
        try {
            # ...
            $param = request()->data;
            # 查询条件处理
            $param['where'] = [
                'shop_state'        => State::STATE_NORMAL,
                'shop_goods_num'    => [ 'gt', State::STATE_DISABLED ],
            ];
            if ( isset($param['q']) ) {
                $param['where']['shop_name'] = [ 'like', $param['q'] ];
            }
            $param['cache']         = true;
            $param['cache_time']    = \mercury\constants\Common::TIME_MONENT;
            $param['order']         = $param['order'] ?? 'shop_service_fraction desc';
            $param['pagesize']      = intval($param['pagesize'] ?? 10);
            $param['relation']      = 'Goods';
            $list = Fun::pageList('\\app\\api\\model\\goods\\Shop', $param);
            if ( empty($list) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # 地区信息
            $area = [];
            # 结果处理
            foreach ($list['data'] as $key => $value) {
                $list['data'][$key]['shop_logo']    = Fun::thumbnail($value['shop_logo'], $param['imgsize'] ?? 100 );
                $list['data'][$key]['province']     = $area[$value['shop_province_id']] ?? '';
                $list['data'][$key]['city']         = $area[$value['shop_city_id']] ?? '';
                $list['data'][$key]['shop_url']     = Fun::shop_url($value['shop_id'], $value['shop_domain']);
            }
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    
}