<?php
namespace app\api\validate\goods\v1;
/**
 * Create Model from Lzy ValidateGenerate.
 * @author Lzy
 * @date 2017-11-16 16:05:04
 */
class ShopSettled extends \think\Validate
{
    protected $rule = [
        'user_id' => [ 'require' ],
        'shop_settled_state' => [ 'require' ],
        'shop_settled_step' => [ 'require' ],
        'shop_settled_id' => [ 'require' ],
        'brand'           => ['requireWith:brand|array'],
        'number'          => ['require'],
        'barnd_logo'          => ['require'],
        'is_brand'          => ['require'],
        'trademark'          => ['require'],
        'private_authorization'          => ['requireIf:is_brand,1'],
        'brand_authorization'          => ['requireIf:is_brand,2'],
        'category_images'  => ['require'],
        'industry_images' => ['require'],
        'authorization'    => ['require'],
        'brand_name'       => ['require','min:2','max:20','chsAlphaNum'],
        'shop_name' => [ 'require','unique:shop,shop_name','min:2','max:10','chsAlphaNum' ],
        'shop_logo' => [ 'require' ],
        'shop_description' => [ 'require','min:20','max:100','checkDescription' ],
        'shop_province_id' => [ 'require','number' ],
        'shop_city_id'           => ['require','number'],
        'shop_district_id'          => ['require','number'],
        'shop_street'          => ['require','chsAlphaNum','max:30'],
        'shop_contect_person'          => ['require','chsAlphaNum','max:30'],
        'shop_mobile'          => ['require','number','min:11','max:11'],
        'shop_email'  => ['require','unique:shop,shop_email'],
        'images'      =>['require','alphaDash'],
    ];

    protected $field  = [
        'brand' => '品牌',
        'brand_name' => '品牌名称',
        'number' => '商标证号/受理书编号',
        'barnd_logo' => '品牌logo',
        'is_brand' => '品牌类型',
        'trademark' => '商标注册申请受理通知书',
        'private_authorization' => '独占授权书',
        'brand_authorization' => '网店代理品牌授权书',
        'category_images' => '合格证图片',
        'industry_images' => '资质图片',
        'authorization' => '网店代理授权书',
        'images'     => '图片',
        'shop_name' => '店铺名称',
        'shop_logo' => '店铺logo',
        'shop_description' => '店铺简介',
        'shop_province_id' => '省份',
        'shop_city_id' => '城市',
        'shop_district_id' => '县区',

        'shop_street' => '详细地址',
        'shop_contect_person' => '联系人',
        'shop_mobile' => '手机号码',
        'shop_email' => 'Email',
    ];

    protected $message = [
        'user_id.require' => '用户id不能为空',


        'shop_settled_state.require' => '申请状态不能为空',


        'shop_settled_step.require' => '当前步骤不能为空',


        'shop_settled_id.require' => 'shop_settled_id不能为空',


    ];


    public $scene = [
        'check_user'        => [ 'user_id', 'shop_settled_state', 'shop_settled_step' ],
        'detail'            => [],
        'shoptype'          => [],
        'brand_category'    => [],
        'save'              => [],
        'check_brand'       =>['brand_name'],
        'check_brand2'       =>['number','barnd_logo','is_brand'],
        'brand_images'     => ['images'],
        'monopolyshop'=>['number','barnd_logo','is_brand','trademark','private_authorization','brand_authorization','authorization'],
        'personalshop'=>['number','barnd_logo','is_brand','trademark','private_authorization','brand_authorization','authorization'],
        'selfsupportshop'=>['number','barnd_logo','is_brand','authorization','trademark','brand_authorization'],
        'flagshipshop'=>['number','barnd_logo','is_brand','authorization','trademark','brand_authorization'],
        'category_images' =>['category_images'],
        'industry_images' =>['industry_images'],
        'auth_shop'     =>['shop_name','shop_logo','shop_description','shop_province_id','shop_city_id','shop_district_id','shop_street','shop_contect_person','shop_mobile','shop_email'],
    ];

    /**
     * 验证店铺简介
     * @param $data
     * @return bool
     */
    protected function checkDescription($data)
    {
        $res = preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9，。？！@、（）-；：“”‘’%+=￥*&.,;:?!\s]+$/u', $data);
        if(!$res){
            return '店铺简介只能是中英文数字和中文常用符号';
        }else{
            return true;
        }

    }
}