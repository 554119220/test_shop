<?php
namespace app\api\controller\goods\v1;
use app\api\model\goods\ShopAddress;
use app\api\model\goods\ShopSettledAudit;
use app\api\model\user\User;
use app\common\traits\F;
use mercury\factory\Factory;
use mercury\ResponseException;
use app\common\traits\F as Fun;
use mercury\constants\Code;
use mercury\constants\State;
use app\api\model\goods\ShopSettled as Model;

/**
 * Class ShopSettled
 * @package app\api\controller\goods\v1
 *
 * @title 开店审核
 */

class ShopSettled2
{
    # 开店详情记录
    protected $detail = [];
    protected $info = [];
    protected $user = [];
    protected $is_work = true;

    # 开店步骤
    const STEP_AGREE            = 'step_agree';
    const STEP_SHOP_TYPE        = 'step_shop_type';
    const STEP_BRAND_CATEGORY   = 'step_brand_category';
    const STEP_SHOP_INFO        = 'step_shop_info';
    const STEP_SHOP_QUALIFICATION       = 'step_shop_qualification';
    const STEP_AUDIT            = 'step_audit';
    const STEP_SHOP_BRANDINFO       ='step_shopbrandinfo';

    # 步骤列表
    const STEP_ARRAYS = [
        self::STEP_AGREE                => '开店协议',
        self::STEP_SHOP_TYPE            => '选择开店类型',
        self::STEP_BRAND_CATEGORY       => '选择品牌和类目',
        self::STEP_SHOP_INFO            => '店铺资料',
        self::STEP_SHOP_BRANDINFO       =>'品牌资料',
        self::STEP_SHOP_QUALIFICATION            => '行业资质',
        self::STEP_AUDIT                => '等待审核',
    ];

    /**
     * @param $openid
     */
    function __construct()
    {
        # 获取用户信息
        $this->user = request()->user;
        $data = request()->data;
        # 获取入驻信息
            $param['where'] = ['user_id'=>$this->user['user_id']];
            $this->detail = Fun::dataDetail('\\app\\api\\model\\goods\\ShopSettled',$param);
    }

    /**
     * 用户检测
     * @return [type] [description]
     */
    function check_user()
    {
        try {
            # 用户是否存在
            if ( empty($this->user) ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '用户不存在' );
            }
            # 用户状态是否正常
            if ( $this->user['user_state'] != State::STATE_USER_NORMAL ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '您当前状态不能开店' );
            }
            # 用户是否已有店铺
//            if ( $this->user['user_shop_id'] > 0 ) {
//                throw new ResponseException( Code::CODE_OTHER_FAIL, '您已经开过店' );
//            }
            # 用户状态是否验证

            if ( $this->user['erpUser']['is_valid_credentials'] != 1 ){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '您还没有通过认证' );
            }

            # 用户等级检测
            if ( $this->user['erpUser']['level_id'] < 1 ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '您还不是盛客以及以上' );
            }
            # sdk...
            # ...
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 检测用户品牌资质是否填写完整
     * @return array
     */
    function check_brand(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            if(!empty($content['brand']) && isset($content['brand'])){
                foreach ($content['brand'] as $kk=>$vv){
                    if(!isset($vv['is_brand'])){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '您的品牌('.$vv['name'].')资料没有填写完整，请返回填写完整！' );
                    }
                    if(!empty($vv['images'.$vv['is_brand']])){
                        foreach ($vv['images'.$vv['is_brand']] as $kkk=>$vvv){
                            if($vvv['images'] == ''){
                                unset($content['brand'][$kk]['images'.$vv['is_brand']][$kkk]);
                            }
                        }
                        $shop_qualifications = \app\api\model\goods\ShopType::where(['shop_type_id'=>$content['step_shop_type']])->value('shop_qualifications');
                        $shop_qualifications = unserialize($shop_qualifications);
                        if(!isset($shop_qualifications['shop_type_brand'.$vv['is_brand']])){
                            throw new ResponseException( Code::CODE_OTHER_FAIL, '品牌('.$vv['name'].')资质设置不完整，不能提交，请返回重新选择！' );
                        }
                        if(count($vv['images'.$vv['is_brand']]) != count($shop_qualifications['shop_type_brand'.$vv['is_brand']])){
                            throw new ResponseException( Code::CODE_OTHER_FAIL, '您的品牌('.$vv['name'].')资质没有填写完整，请返回填写完整！' );
                        }
                    }
                }
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证品牌资质填写情况
     * @return array
     */
    function check_shop_brand(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            foreach ($param['brand'] as $key=>$value){
                $chcke_brand = Fun::vApi('goods','ShopSettled');
                if(  true !== $chcke_brand->scene('check_brand2')->check($value)){
                    throw new ResponseException( Code::CODE_OTHER_FAIL,'品牌('.$value['name'].')下：'.$chcke_brand->getError() );
                }
                if(isset($value['images'.$value['is_brand']])){
                    foreach ($value['images'.$value['is_brand']] as $k=>$v){
                        $chcke_images2 = Fun::vApi('goods','ShopSettled');
                        #增加必填和选填
                        $where['shop_qualifications_id'] = $v['id'];
                        $is_must = Factory::instance('/goods/v1/shopQualifications/detail2')->run($where);
                        if(!$is_must['code'] == 20000){
                            throw new ResponseException( Code::CODE_OTHER_FAIL,'品牌('.$value['name'].')下：'.$v['images_name'].$is_must['msg'] );
                        }
                        if($is_must['data']['is_must']){
                            if(  true !== $chcke_images2->scene('brand_images')->check($v)){
                                throw new ResponseException( Code::CODE_OTHER_FAIL,'品牌('.$value['name'].')下：'.$v['images_name'].$chcke_images2->getError() );
                            }
                        }
                    }
                }else{
                    throw new ResponseException( Code::CODE_OTHER_FAIL,'该店铺类型没有设置品牌资质，请更换其他店铺类型！' );
                }

            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证店铺行业资质填写情况
     * @return array
     */
    function check_shop_industry(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            $shop_qualifications = \app\api\model\goods\ShopType::where(['shop_type_id'=>$content['step_shop_type']])->value('shop_qualifications');
            $shop_qualifications = unserialize($shop_qualifications);
            $param['industry'] = $param['industry'] ?? [];
            $shop_qualifications['shop_type_industry'] = $shop_qualifications['shop_type_industry'] ?? [];
            if(count($param['industry']) != count($shop_qualifications['shop_type_industry'])){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '行业资质有所调整，请刷新后重试！' );
            }
            $count = count($content['category_two']);
            for ($i = 0; $i <= $count-1;$i++){
                #新增选填和必填
                if($param['category_images'][$i]['category_qualifications_require']){
                    $chcke_brand = Fun::vApi('goods','ShopSettled');
                    if(  true !== $chcke_brand->scene('category_images')->check($param['category_images'][$i])){
                        throw new ResponseException( Code::CODE_OTHER_FAIL,'类目('.$content['category_two_name'][$i].')下：'.$chcke_brand->getError() );
                    }
                }
            }
            if(isset($param['industry'])){
                $count_industry = count($param['industry']);
                $chcke_brand = Fun::vApi('goods','ShopSettled');
                for ($i = 0; $i <= $count_industry-1;$i++){
                    #增加必填和选填
                    $where['shop_qualifications_id'] = $param['industry'][$i]['id'];
                    $is_must = Factory::instance('/goods/v1/shopQualifications/detail2')->run($where);
                    if(!$is_must['code'] == 20000){
                        throw new ResponseException( Code::CODE_OTHER_FAIL,$param['industry'][$i]['name'].$is_must['msg'] );
                    }
                    if($is_must['data']['is_must']){
                        if (true !== $chcke_brand->scene('industry_images')->check($param['industry'][$i])) {
                            throw new ResponseException(Code::CODE_OTHER_FAIL, '请上传' . $param['industry'][$i]['name'] . '资质图片');
                        }
                    }
                }
                $content['industry'] = $param['industry'];
            }else{
                $content['industry'] = [];
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证店铺名称禁用关键词
     * @return array
     */
    function check_disabled_keyword(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            $param['shop_name'] = str_replace(' ','',$param['shop_name']);
            $disabled_keyword = \app\api\model\goods\DisabledKeyword::where('id',1027)->value('keyword');
            $keyword = explode(',',$disabled_keyword);
            $count_keyword = count($keyword);
            for ($i = 0;$i < $count_keyword; $i++){
                if(stripos($param['shop_name'],$keyword[$i],0) !== false || stripos($param['shop_name'],$keyword[$i],0) !== false){
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '店铺名称出现了“'.$keyword[$i].'”字眼，请修改！' );
                }
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 检测用户行业资质是否填写完整
     * @return array
     */
    function check_industry(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            $shop_qualifications = \app\api\model\goods\ShopType::where(['shop_type_id'=>$content['step_shop_type']])->value('shop_qualifications');
            $shop_qualifications = unserialize($shop_qualifications);
            $content['industry'] = $content['industry'] ?? [];
            $content['shop_type_industry'] = $content['shop_type_industry'] ?? [];
            $shop_qualifications['shop_type_industry'] = $shop_qualifications['shop_type_industry'] ?? [];
            if(count($content['industry']) != count($shop_qualifications['shop_type_industry'])){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '您选择的店铺类型行业资质需要填写'.count($shop_qualifications['shop_type_industry']).'个，您已经填写'.count($content['industry']).'个,请返回上一步补充完整！' );
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证品牌名称
     * @return array
     */
    function check_shop_brand_name(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            foreach ($param['brand'] as $key=>$value){
                if(empty($value['name'])){
                    unset($param['brand'][$key]);
                }else{
                    $chcke_brand = Fun::vApi('goods','ShopSettled');
                    $brand_name['brand_name'] = $value['name'];
                    if(  true !== $chcke_brand->scene('check_brand')->check($brand_name)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL,$chcke_brand->getError() );
                    }
                    # 如果当前是是否旗舰店，如果是旗舰店的话不允许用户再添加类似品牌
                    if($content['step_shop_type'] == 3){
//                        if(\app\api\model\goods\GoodsBrand::where('goods_brand_name',$value['name'])->value('goods_brand_id')){
//                            throw new ResponseException( Code::CODE_OTHER_FAIL, '您选择的旗舰店，当前品牌已经入驻，请更换其他品牌！' );
//                        }
                        $id = \app\api\model\goods\GoodsBrand::where('goods_brand_name',$value['name'])->value('goods_brand_id');
                        if($id){
                            $shop_id = db('shop_goods_brand')->where(['brand_id' => $id])->field('shop_id')->select();
                            if(!empty($shop_id)){
                                foreach ($shop_id as $key=>$value){
                                    $shop_type_id = db('shop')->where(['shop_id' => $value['shop_id']])->field('shop_type_id')->find();
                                    if($shop_type_id['shop_type_id'] == 3){
                                        throw new ResponseException( Code::CODE_OTHER_FAIL, '您选择的旗舰店，当前品牌已经入驻，请更换其他品牌！' );
                                    }
                                }
                            }

                        }
                    }
                }
            }
            $data['brand'] = $param['brand'];
            $data['code'] = 1;
            return $data;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证品牌名称2
     * @return array
     */
    function check_shop_brand_name2(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            foreach ($content['brand'] as $key=>$value){
                if(empty($value['name'])){
                    unset($content['brand'][$key]);
                }else{
                    $chcke_brand = Fun::vApi('goods','ShopSettled');
                    $brand_name['brand_name'] = $value['name'];
                    if(  true !== $chcke_brand->scene('check_brand')->check($brand_name)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL,$chcke_brand->getError() );
                    }
                    # 如果当前是是否旗舰店，如果是旗舰店的话不允许用户再添加类似品牌
                    if($content['step_shop_type'] == 3){
                        $id = \app\api\model\goods\GoodsBrand::where('goods_brand_name',$value['name'])->value('goods_brand_id');
                        if($id){
                            $shop_id = db('shop_goods_brand')->where(['brand_id' => $id])->field('shop_id')->select();
                            if(!empty($shop_id)){
                                foreach ($shop_id as $key=>$value){
                                    $shop_type_id = db('shop')->where(['shop_id' => $value['shop_id']])->field('shop_type_id')->find();
                                    if($shop_type_id['shop_type_id'] == 3){
                                        throw new ResponseException( Code::CODE_OTHER_FAIL, '您选择的旗舰店，当前品牌已经入驻，请更换其他品牌！' );
                                    }
                                }
                            }

                        }
                    }
                }
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证品牌个数
     * @return array
     */
    function check_shop_brand_count(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            $shop_type_brand_count = \app\api\model\goods\ShopType::where(['shop_type_state'=>1,'shop_type_id'=>$content['step_shop_type']])->value('is_brand_must');
            if(isset($param['brand']) && !empty($param['brand'])){
                foreach ($param['brand'] as $key => $value){
                    if(empty($value['name'])){
                        unset($param['brand'][$key]);
                    }
                }
            }
            if(count($param['brand']) < $shop_type_brand_count){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '您选择的店铺类型品牌至少填写'.$shop_type_brand_count.'个！' );
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证品牌个数
     * @return array
     */
    function check_shop_brand_count2(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            $shop_type_brand_count = \app\api\model\goods\ShopType::where(['shop_type_state'=>1,'shop_type_id'=>$content['step_shop_type']])->value('is_brand_must');
            if(count($content['brand']) < $shop_type_brand_count){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '您选择的店铺类型品牌至少填写'.$shop_type_brand_count.'个！' );
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 检测店铺类型
     * @return array
     */
    function check_shop_type(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            if (!\app\api\model\goods\ShopType::where(['shop_type_state'=>1,'shop_type_id'=>$content['step_shop_type']])->value('shop_type_state')) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '当前店铺类型已经被停止使用，请更换其他店铺类型！' );
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 当前步骤验证店铺类型
     * @return array
     */
    function check_shop_type2(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            if (!\app\api\model\goods\ShopType::where(['shop_type_state'=>1,'shop_type_id'=>$param['shop_type']])->value('shop_type_state')) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '当前店铺类型已经被停止使用，请更换其他店铺类型！' );
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 验证店铺名称唯一
     * @return array
     */
    function check_shop_name(){
        try {
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            $content    = $detail['shop_settled_content'] ?? [];
            $shop_type_name = \app\api\model\goods\ShopType::where('shop_type_id',$content['step_shop_type'])->value('shop_type_suffix');

            if(\app\api\model\goods\Shop::where('shop_name',$param['shop_name'].$shop_type_name)->value('shop_id')){
                throw new ResponseException( Code::CODE_OTHER_FAIL, '店铺名称已经被使用！' );
            }
            return [];
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }

    }

    /**
     * 入驻详情
     * @return [type] [description]
     */
    public function detail ()
    {
        try {
            # 获取开店信息
            if ( empty($this->detail) ) {
                throw new ResponseException( Code::CODE_NO_CONTENT );
            }
            # ...
            if(!empty($this->detail['shop_settled_content_bak'])){
                $this->detail['shop_settled_content'] = $this->detail['shop_settled_content_bak'];
            }
            return $this->detail;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    /**
     * 店铺类型
     */
    function shop_type ()
    {
        try {
            # 获取店铺类型
            $list = $this->get_shop_type();
            # ...
            return $type;
        } catch (ResponseException $e) {
            return $e->getData();
        }
    }

    private function get_shop_type ()
    {
        $list = Fun::dataAll('\\app\\api\\model\\goods\\ShopType', $param);
        $list = array_column($list, null, 'shop_type_id');
        switch ($this->user['level']) {
            case 'value':
                # code...
                break;
            default:
                # code...
                break;
        }
        return $list;
    }

    /**
     * 店铺 商品类目 & 商品品牌
     */
    function brand_category ()
    {
        try {
            $list = $this->get_brand_category();
            # ...
            return $list;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

    private function get_brand_category ()
    {
        # 品牌
        $brand = Fun::dataAll('\\app\\api\\model\\goods\\GoodsBrand');
        # 分类
        $param['where'] = [ 'category_state' => State::STATE_NORMAL ];
        $param['cache'] = true;
        $param['cache_time'] = \mercury\constants\Common::TIME_ONE_HOUR;
        $param['field'] = [ 'category_id', 'category_name', 'category_sort', 'category_sid', 'category_icon', 'category_images' ];
        $category   = Fun::dataAll('\\app\\api\\model\\goods\\GoodsCategory', $param);
        $option = [
            // 'begin' => request()->param('category_id/d', 0),
            'id'    => 'category_id',
            'pid'   => 'category_sid',
            'child' => 'child',
            'level' => 2,
        ];
        $category = Fun::array_tree($category, $option);
        # ...
        return [ 'brand' => $brand, 'category' => $category ];
    }

    /**
     * 品牌资质上传
     */
    function qualifications()
    {
        //上传商家资质
    }

    /**
     * 店铺信息
     */
    function shop_info()
    {

    }


    /**
     * @title 保存开店信息
     * @request 请求参数
     * |参数名|参数类型|是否必传|示例值|更多限制|参数说明|
     * |---|---|---|---|---|---|
     * |step|string|true|5|-|开店步骤，step_agree,开店协议；step_shop_type，选择开店类型；step_brand_category，选择品牌和类目，step_shop_info，店铺资料；step_shop_qualification，行业资质；step_shopbrandinfo，行业资质；step_audit，等待审核|
     * |is_agreen|string|false|5|step:step_agree必填|开店协议（1）|
     * |type|int|false|5|step:step_shop_type必填|选择店铺类型|
     * |brand|array|false|5|step:step_brand_category必填|选择品牌和类目|
     * |category|array|false|5|step:step_brand_category必填|选择品牌和类目|
     * |industry|array|false|5|step:step_shop_qualification必填|啊行业资质|
     * |category_images|array|false|5|step:step_shop_qualification必填|类目资质|
     * |shop_name|string|false|5|step:step_shop_info必填|店铺名称|
     * |shop_tel|int|false|5|step:step_shop_info必填|店铺电话|
     * |shop_mobile|int|false|5|step:step_shop_info必填|店铺手机|
     * |openid|string|false|5|step:step_shop_info必填|用户openid|
     * |shop_contect_person|string|false|5|step:step_shop_info必填|联系人|
     * |shop_province_id|int|false|5|step:step_shop_info必填|省份id|
     * |shop_city_id|int|false|5|step:step_shop_info必填|城市id|
     * |shop_district_id|int|false|5|step:step_shop_info必填|县区id|
     * |shop_town_id|int|false|5|step:step_shop_info必填|乡镇id|
     * |shop_street|string|false|5|step:step_shop_info必填|详细地址|
     * |shop_email|string|false|5|step:step_shop_info必填|邮箱|
     * |shop_description|string|false|5|step:step_shop_info必填|店铺介绍|
     * |shop_logo|string|false|5|step:step_shop_info必填|店铺logo|
     * @response 响应参数
     * |参数名|数据类型|返回数据|数据说明|
     * |---|---|---|---|
     * @response_example 响应示例
     * `{
    data: {
    },
    msg: "请求成功",
    info: "success",
    code: 20000
    }`
     * @description 接口描述
     * > you are api description
     * @return array|mixed|string
     */
    function save()
    {
        try {
            # ...
            $step_agree             = self::STEP_AGREE;
            $step_shop_type         = self::STEP_SHOP_TYPE;
            $step_brand_category    = self::STEP_BRAND_CATEGORY;
            $step_shop_info         = self::STEP_SHOP_INFO;
            $step_audit             = self::STEP_AUDIT;
            $step_shop_qualification             = self::STEP_SHOP_QUALIFICATION;
            $step_shopbrandinfo     = self::STEP_SHOP_BRANDINFO;
            # ...
            $param      = request()->param();
            $detail     = $this->detail ?? [];
            if(!empty($detail['shop_settled_content_bak'])){
                $content = $detail['shop_settled_content_bak'];
            }else{
                $content = $detail['shop_settled_content'];
            }
            # 是否在审核
            if (isset($detail['shop_settled_state']) && $detail['shop_settled_state'] == State::SETTLED_AUDIT ) {
                throw new ResponseException( Code::CODE_OTHER_FAIL, '开店审核中,不能操作' );
            }
            # 是否已开店成功
//            if (isset($detail['shop_settled_state']) && $detail['shop_settled_state'] == State::SETTLED_PASS ) {
//                throw new ResponseException( Code::CODE_OTHER_FAIL, '已审核通过,不能操作' );
//            }
            # 步骤操作
            switch ($param['step']) {
                # 品牌资质
                case $step_shopbrandinfo :
                    # 是否同意开店协议
                    if (false == isset($content[$step_agree]) || $content[$step_agree] != State::STATE_NORMAL ) {
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '请先同意开店协议' );
                    }

                    # 是否选择店铺类型
                    if (false == isset($content[$step_shop_type]) || empty($content[$step_shop_type]) ) {
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '请先选择店铺类型' );
                    }
                    #验证店铺是否类型是否禁止使用
                    if(!empty($is_check_shop_type)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_check_shop_type['msg']);
                    }

                    # 是否选择品牌和类目
                    if (false == isset($content['brand']) || empty($content['brand']) ) {
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '您没有品牌，不需要此步骤！' );
                    }

                    #验证品牌名称check_shop_brand_name
//                    $is_shop_brand_name = $this->check_shop_brand_name();
//                    if($is_shop_brand_name['code'] != 1){
//                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_shop_brand_name['msg']);
//                    }
                    //$param['brand'] = $is_shop_brand_name['brand'];

                    #验证品牌个数check_shop_brand_count
                    $is_check_shop_brand_count = $this->check_shop_brand_count2();
                    if(!empty($is_check_shop_brand_count)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_check_shop_brand_count['msg']);
                    }
                    # ...
                    #验证品牌填写情况check_shop_brand
                    $is_shop_brand = $this->check_shop_brand();
                    if(!empty($is_shop_brand)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_shop_brand['msg']);
                    }

                    $content['brand'] = $param['brand'];
                    $step_next = $step_shop_info;
                    break;

                # 行业资质
                case $step_shop_qualification :
                    # 是否同意开店协议
                    if (false == isset($content[$step_agree]) || $content[$step_agree] != State::STATE_NORMAL ) {
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '请先同意开店协议' );
                    }

                    # 是否选择店铺类型
                    if (false == isset($content[$step_shop_type]) || empty($content[$step_shop_type]) ) {
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '请先选择店铺类型' );
                    }
                    #验证店铺是否类型是否禁止使用
                    if(!empty($is_check_shop_type)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_check_shop_type['msg']);
                    }

                    #验证品牌名称check_shop_brand_name
//                    $is_shop_brand_name2 = $this->check_shop_brand_name2();
//                    if(!empty($is_shop_brand_name2)){
//                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_shop_brand_name2['msg']);
//                    }

                    #验证品牌个数check_shop_brand_count
//                    $is_check_shop_brand_count = $this->check_shop_brand_count2();
//                    if(!empty($is_check_shop_brand_count)){
//                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_check_shop_brand_count['msg']);
//                    }

                    #验证品牌资质是否填写完整
                    $is_brand = $this->check_brand();
                    if(!empty($is_brand)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, $is_brand['msg']);
                    }

                    #验证行业资质是否填写完整check_shop_industry
                    $shop_industry = $this->check_shop_industry();
                    if(!empty($shop_industry)){
                        throw new ResponseException( Code::CODE_OTHER_FAIL, $shop_industry['msg']);
                    }

                    if(isset($param['industry'])){
                        $content['industry'] = $param['industry'];
                    }else{
                        $content['industry'] = [];
                    }
                    $content['category_images'] = $param['category_images'];

                    $step_next = $step_shop_info;
                    break;
                # 无操作
                default:
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '无数据更新' );
                    break;
            }
            # 数据更新
            $model = new Model;
                # 更新
                $detail['shop_settled_content_bak'] = $content;
                $id = $detail['shop_settled_id'];
                unset($detail['shop_settled_id']);
                $detail['shop_settled_step'] = $step_next;
                //$allowField[] = 'shop_settled_state';
                $allowField[] = 'shop_settled_content_bak';
                unset($detail['shop_settled_create_time']);
                unset($detail['shop_settled_update_time']);
                # ...
                if ( false === $model->allowField($allowField)->save($detail, [ 'shop_settled_id' => $id ])) {
                    throw new ResponseException( Code::CODE_OTHER_FAIL, '店铺信息保存失败，请重试' );
                }

                $shop = new \app\api\model\goods\Shop();
                if($param['step'] == 'step_shop_qualification'){
                    $is_again = 2;
                    $allowField2[] = 'is_again';
                    if ( false === $shop->allowField($allowField2)->save(['is_again'=>$is_again], [ 'shop_id' => $this->user['user_shop_id'] ])) {
                        $model->rollback();
                        throw new ResponseException( Code::CODE_OTHER_FAIL, '店铺信息保存失败，请重试' );
                    }
                }
            $model->commit();
            # ...
            return Code::CODE_SUCCESS;
        } catch (ResponseException $e) {
            # ...
            return $e->getData();
        }
    }

}