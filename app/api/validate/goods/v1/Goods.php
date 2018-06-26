<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-24 10:52:40
 */
use app\common\traits\F as Fun;
use mercury\constants\State;

class Goods extends \think\Validate
{

    protected $user_id,$shop_id;
    protected $rule = [
        // sku_id
        'id'                        => [ 'require' ],

        'goods_id'                  => [ 'require', 'checkGoods' => '' ],

        'goods_category_id'         => [ 'require', 'checkGoodsCategory' => '' ],

        'goods_name'                => [ 'require', 'checkGoodsNameMax' => 40, ],
        //'goods_sub_name'            => [ 'require', ],

        'goods_service_days'        => [ 'require', 'integer', 'egt' => 0, 'checkGoodsServiceDays' ],
        'goods_images'              => [ 'require' ],
        'goods_state'               => [ 'require' ],
        'goods_recommend'           => [ 'require', 'checkRecommend' ],
        'goods_shopping_score_multi'=> [ 'require', 'checkMulti' => '' ],

        

        'shop_goods_category_ids'   => [ 'require', 'array', 'checkShopGoodsCategory' => '' ],
        

        'shop_goods_brand_id'       => [ 'require', 'checkShopGoodsBrand' => '' ],
        'express_id'                => [ 'require', 'checkExpress' => '' ],
        'package_id'                => [ 'require', 'checkPackage' => '' ],
        'protection_id'             => [ 'require', 'checkProtection' => '' ],

        'goods_sku_group'           => [ 'require', 'array', 'checkGoodsSkuGroup' => '' ],
        'goods_sku'                 => [ 'require', 'array', 'checkGoodsSku' ],
        'goods_params'              => [ 'require', 'array', 'checkGoodsParams' => '' ],
        'goods_qualifications'      => [ 'requireCallback' => ['\app\api\validate\goods\v1\Goods', 'checkGoodsQualificationsRequire'], 'array', 'checkGoodsQualifications' => '' ],
        'goods_content'             => [ 'require', 'checkGoodsContent' => '' ],
    ];


    protected $message = [
        'goods_id.require'                                  => '商品不能为空',
        'goods_id.checkGoods'                               => '用户商品错误',

        'goods_name.require'                                => '商品名称必须',
        'goods_name.checkGoodsNameMax'                      => '商品名称过长',

        //'goods_sub_name.require'                            => '商品副标题必须',

        'goods_service_days.require'                        => '售后天数必须',
        'goods_service_days.integer'                        => '售后天数必须是整数',
        'goods_service_days.egt'                            => '售后天数不能小于0',
        'goods_service_days.checkGoodsServiceDays'          => '售后天数不能小于商品默认售后天数',

        'goods_images.require'                              => '商品主图必须',

        'goods_state.require'                               => '商品状态必须选择',

        'express_id.require'                                => '运费模板必须',
        'express_id.checkExpress'                           => '运费模板错误',

        'package_id.require'                                => '包装模板必须',
        'package_id.checkPackage'                           => '包装模板错误',

        'protection_id.require'                             => '售后模板必须',
        'protection_id.checkProtection'                     => '售后模板错误',

        'goods_recommend.require'                           => '橱窗推荐必须',
        'goods_recommend.checkRecommend'                    => '橱窗推荐错误',

        'goods_shopping_score_multi.checkMulti'             => '积分比例错误',
        'goods_shopping_score_multi.require'                => '购物积分比例必须',

        'shop_goods_category_ids.require'                   => '店铺分类必须',
        'shop_goods_category_ids.array'                     => '店铺分类错误1',
        'shop_goods_category_ids.checkShopGoodsCategory'    => '店铺分类错误2',

        'shop_goods_brand_id.require'                       => '商品品牌必须',
        'shop_goods_brand_id.checkShopGoodsBrand'           => '商品品牌错误',

        'goods_category_id.require'                         => '商品分类必须',
        'goods_category_id.checkGoodsCategory'              => '商品分类错误',

        'goods_content.require'                             => '商品详情必须',


        'goods_sku_group.require'                           => '库存属性组必须',
        'goods_sku_group.array'                             => '库存属性组错误',
        'goods_sku_group.checkGoodsSkuGroup'                => '库存属性组过多',

        'goods_sku.require'                                 => '库存必须',
        'goods_sku.array'                                   => '库存错误',

        'goods_params.require'                              => '产品参数必须',
        'goods_params.array'                                => '产品参数错误',
        'goods_params.checkGoodsParams'                     => '产品参数错误',

        'goods_qualifications.require'                      => '产品资质必须',
        'goods_qualifications.array'                        => '产品资质错误',
        'goods_qualifications.checkGoodsParams'             => '产品资质错误',
    ];


    public $scene = [
        'create' => [
            'goods_service_days',
            'goods_name',
            'goods_sub_name',
            'goods_images',
            'goods_state',
            'express_id',
            'package_id',
            'protection_id',
            'goods_recommend',
            'shop_goods_category_ids',
            'shop_goods_brand_id',
            'goods_category_id',
            'goods_content',
            'goods_sku_group',
            'goods_sku',
            'goods_params',
            'goods_qualifications',
        ],
        'update' => [
            'goods_id',
            'goods_service_days',
            'goods_name',
            'goods_sub_name',
            'goods_images',
            'goods_state',
            'express_id',
            'package_id',
            'protection_id',
            'goods_recommend',
            'goods_shopping_score_multi',
            'shop_goods_category_ids',
            'shop_goods_brand_id',
            'goods_category_id',
            'goods_content',
            'goods_sku_group',
            'goods_sku',
            'goods_params',
            'goods_qualifications',
        ],
        'index'                 => [],
        'detail'                => [ 'id' ],
        'detail2'               => [ 'id' ],
        'delete'                => [ 'goods_id' ],
        'batchShelves'          => [ 'goods_id' ],
        'batchUnder'            => [ 'goods_id' ],
        'batchUpdateCategory'   => [ 'goods_id', 'shop_goods_category_ids' ],
        'batchUpdateExpress'    => [ 'goods_id', 'express_id' ],
        'batchRecommend'        => [ 'goods_id' ],
        'batchRemoveRecommend'  => [ 'goods_id' ],
        'batchDelete'           => [ 'goods_id' ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->user_id = intval(request()->user['user_id'] ?? 0);
        $this->shop_id = intval(request()->user['user_shop_id'] ?? 0);
    }

    function checkGoodsServiceDays($value,$rule){
        // dump(request()->data);
        $category = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), intval(request()->data['goods_category_id']));
        if ( empty($category) || $value < $category['category_goods_service_days'] ) {
            return false;
        }
        return true;
    }

    public function checkExpress ($value, $rule, $data)
    {
        $map = [
            'seller_user_id'        => $this->user_id,
            'express_id'            => $value,
            'shop_id'               => $this->shop_id,
        ];
        // dump(session('user'));exit;
        if ( db('goods_express_tpl')->where($map)->field('express_id')->count() <= 0 ) {
            return false;
        }
        return true;
    }

    public function checkPackage ($value, $rule)
    {
        $map = [
            'seller_user_id'        => $this->user_id,
            'package_id'            => $value,
            'shop_id'               => $this->shop_id,
        ];
        if ( db('goods_package_tpl')->where($map)->field('package_id')->count() <= 0 ) {
            return false;
        }
        return true;
    }

    public function checkProtection ($value, $rule)
    {
        $map = [
            'seller_user_id'        => $this->user_id,
            'protection_id'         => $value,
            'shop_id'               => $this->shop_id,
        ];
        if ( db('goods_protection_tpl')->where($map)->field('protection_id')->count() <= 0 ) {
            return false;
        }
        return true;
    }

    public function checkShopGoodsCategory ($value, $rule)
    {
        $map = [
            'seller_user_id'            => $this->user_id,
            'goods_category_id'         => [ 'in', $value ],
            'shop_id'                   => $this->shop_id,
            'goods_category_state'      => State::STATE_NORMAL,
        ];
        // dump($value);exit;
        if ( count($value) != db('shop_goods_category')->where($map)->field('shop_goods_category_id')->count() ) {
            return false;
        }
        return true;
    }

    public function checkShopGoodsBrand ($value, $rule)
    {   
        # 可以不选品牌
        if ($value == 0) {
            return true;
        }
        $map = [
            'seller_user_id'            => $this->user_id,
            'shop_goods_brand_id'       => [ 'in', $value ],
            'shop_id'                   => $this->shop_id,
        ];
        if ( db('shop_goods_brand')->where($map)->field('shop_goods_brand_id')->count() <= 0 ) {
            return false;
        }
        return true;
    }

    public function checkGoodsCategory ($value, $rule)
    {
        $goods_category = Fun::dataDetail(Fun::mApi('goods','GoodsCategory'), intval($value));
        # 无数据
        if ( empty($goods_category) ) {
            return false;
        }
        # 是否正常
        if ( $goods_category['category_state'] != State::STATE_NORMAL ) {
            return false;
        }
        # 不在店铺的二级分类中
        $shop_goods_category = db('shop')->where(['shop_id' => $this->shop_id])->value('goods_category_ids');
        if ( ! $shop_goods_category ) {
            return false;
        }
        $shop_goods_category_new = explode(",", (string)$shop_goods_category);
        if ( false == in_array($goods_category['category_sid'], $shop_goods_category_new) ) {
            return false;
        }
        return true;
    }

    function checkGoodsSkuGroup($value, $rule)
    {
        # 属性组过长
        if ( count($value) > State::STATE_GOODS_SKU_GROUP_MAX ) {
            return '属性组最多只能为' . State::STATE_GOODS_SKU_GROUP_MAX . '个';
        }
        # 验证
        $v = new GoodsSkuGroup();
        foreach ($value as $data) {
            if ( false == $v->scene('create')->check($data) ) {
                return (string) $v->getError();
            }
        }
        return true;
    }

    function checkGoodsSku($value, $rule)
    {
        if ( count($value) > 140 ) {
            return '商品属性值过多';
        }
        # 验证
        $v = new GoodsSku();
        foreach ($value as $data) {
            if ( false == $v->scene('create')->check($data) ) {
                return (string) $v->getError();
            }
        }
        foreach ($value as $data) {
            if ( $data['goods_sku_market_price'] < $data['goods_sku_price'] ) {
                return '市场价不能小于销售价';
            }
        }
        return true;
    }

    function checkGoodsParams($value, $rule)
    {
        # 参数过长
        if ( count($value) > State::STATE_GOODSPARAMS_MAX ) {
            return false;
        }
        # 验证
        foreach ($value as $data) {
            $v = Fun::vApi('goods','GoodsParams');
            if ( false == $v->scene('create')->check($data) ) {
                return (string) $v->getError();
            }
        }
        return true;
    }

    static function checkGoodsQualificationsRequire($value,$rule)
    {
        // dump($value);
        if ( empty($value) ) {
            return false;
        } else {
            return true;
        }
    }

    function checkGoodsQualifications($value, $rule)
    {
        # 参数过长
        if ( count($value) > State::STATE_QUALIFICATIONS_MAX ) {
            return false;
        }
        # 验证
        foreach ($value as $key => $data) {
            # 值存在，则验证
            $v = Fun::vApi('goods','GoodsQualifications');
            if ( false == $v->scene('create')->check($data) ) {
                unset($value[$key]);
            }
        }
        # 重新绑定
        $data = request()->data;
        $data['goods_qualifications'] = (array)$value;
        request()->bind('data',$data);
        # ...
        return true;
    }

    function checkRecommend($value,$rule)
    {
        if ( $value == State::STATE_NORMAL ) {
            $map = [
                'seller_user_id'    => $this->user_id,
                'goods_state'       => [ 'in', array_keys(State::STATE_GOODS_USER_ARRAY) ],
                'goods_recommend'   => State::STATE_NORMAL,
            ];
            if ( isset(request()->data['goods_id']) ) {
                $map['goods_id'] = ['neq' , request()->data['goods_id']];
            }
            $count = db('goods')->where($map)->count();
            // dump($map);
            // dump($count);exit;
            if ( State::STATE_GOODS_RECOMMEND_NUMS <= $count ) {
                return '最多创建' . State::STATE_GOODS_RECOMMEND_NUMS . '个橱窗商品';
            }
        }
        return true;
    }

    static function checkMulti($value,$rule){
        if ( false == preg_match('/^[0-9]+$/',$value) ) {
            return '购物积分比例必须是整数';
        }
        # 商品是否存在
        $goods = Fun::dataDetail(Fun::mApi('goods','Goods'), request()->data['goods_id'] ?? 0);
        if ( empty($goods) ) {
            return '设置购物积分比例时出错';
        }
        return true;
        // dump($goods);exit;
        # 设置比例
        if ( $goods['goods_is_self'] == 1 ) {
            # 自营
            $multi    = [20, 80];
        } else if ( $goods['goods_recommend_type'] == State::STATE_GOODS_RECOMMEND_TYPE_THREE ) {
            $multi    = [20, 20];
        }else if ($goods['goods_recommend_type'] == State::STATE_GOODS_RECOMMEND_TYPE_TWO ){
            # 精选
            $min = intval(config('site.goods')['goods_recommend1_min_multi'] ?? 0);
            $max = intval(config('site.goods')['goods_recommend1_max_multi'] ?? 0);
            $multi = [$min,$max];
        } else {
            # 默认比例
            $multi    = [20, 20];
        }
        # 检测
        $max = max($multi);
        $min = min($multi);
        // dump($value < $min);dump($max);dump($min);exit;
        if($value > $max){
            return '购物积分比例不能大于' . $max;
        }
        if($value < $min){
            return '购物积分比例不能小于' . $min;
        }

        # ...
        return true;
    }

    /**
     * 发布和编辑商品 商品价格 扣除购物积分后还必须大于0.5
     * @param  [type]  $goodsSku [description]
     * @param  integer $muilt    [description]
     * @return [type]            [description]
     */
    function checkMulti2($goodsSkuList,$muilt = 20)
    {
        $min = min(array_column($goodsSkuList, 'goods_sku_price'));
        // dump($min * (100 - $muilt) / 100);exit;
        if ( round($min, 2) * (100 - $muilt) / 100 < 0.5 ) {
            return false;
        }
        return true;
    }

    function checkGoods($value,$rule,$data)
    {
        $goodsIds = explode(',', $value);
        foreach ($goodsIds as $key => $id) {
            $goods = Fun::dataDetail(Fun::mApi('goods','Goods'), $id);
            if ( empty($goods) || $goods['seller_user_id'] != (request()->user['user_id'] ?? -1) ) {
                return false;
            }
        }
        return true;
    }

    function checkGoodsNameMax($value, $rule)
    {
        $len = strlen($value);
        if ( $len > $rule * 3 ) {
            return false;
        }
        return true;
    }

    function checkGoodsContent($value,$rule)
    {
        # 检测本站链接
        $hrefValue = str_replace("'", '"', $value);
        preg_match_all('/<a.*?href="(.*?)".*?>.*?<\/a>/is', $hrefValue, $arrs);
        // dump($arrs);
        $hrefs = $arrs[1] ?? [];
        if ($hrefs) {
            # 域名匹配
            $hostRule   = "/^([0-9a-zA-Z]+.)*?" . config('url_domain_root') . "$/i";
            // dump($hrefs);
            foreach ($hrefs as $href) {
                $host = parse_url($href, PHP_URL_HOST);
                if ( preg_match($hostRule, $host) == false ) {
                    // dump($host);
                    return '商品详情只能添加本站链接！';
                }
            }
        }
        # ...
        return true;
    }

}