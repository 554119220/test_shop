<?php
namespace app\seller\controller;
use mercury\factory\Factory;
use app\common\traits\F as Fun;
use mercury\constants\State;
use mercury\auth\api\AuthApi;
use mercury\editor\UEditor;

/**
 * 商品管理
 * @author Lzy
 * @datetime 2017-12-07 14:00:00
 */

class Goods
{
    /**
     * 商品管理
     * @return \think\response\View
     */
    public function index()
    {
        $list = Factory::instance('/goods/v1/Goods/index2')->run();
        return view('',[
            'data'              => $list['data'] ?? [],
            'stateArr'          => State::STATE_GOODS_USER_ARRAY,
            'recommendAttr'     => State::STATE_GOODS_RECOMMEND_TYPE_ARRAYS,
            'shopGoodsCategory' => Factory::instance('/goods/v1/shopGoodsCategory/index')->run()['data'] ?? [],
            'wapDomain'         => Fun::domain('wap'),
            'headers' => [
                'headers0' => AuthApi::getInstance('/goods/v1/Goods/batchShelves')->createHeaders(),
                'headers1' => AuthApi::getInstance('/goods/v1/Goods/batchUnder')->createHeaders(),
                'headers2' => AuthApi::getInstance('/goods/v1/Goods/batchUpdateCategory')->createHeaders(),
                'headers3' => AuthApi::getInstance('/goods/v1/Goods/batchUpdateExpress')->createHeaders(),
                'headers4' => AuthApi::getInstance('/goods/v1/Goods/batchRecommend')->createHeaders(),
                'headers5' => AuthApi::getInstance('/goods/v1/Goods/batchRemoveRecommend')->createHeaders(),
                'headers6' => AuthApi::getInstance('/goods/v1/Goods/batchDelete')->createHeaders(),
                'headers7' => AuthApi::getInstance('/goods/v1/GoodsApplyRecommend/create')->createHeaders(),
            ],
        ]);
    }

    /**
     * 发布商品
     * @return \think\response\View
     */
    public function create()
    {

        $cid = input('category_id', 0,'int');
        // dump(Factory::instance('/goods/v1/GoodsQualificationsGroup/index')->run());exit;
        if ( $cid <= 0 ) {
             $template = 'choose_category';
             $createCheck = Factory::instance('/goods/v1/Goods/createCheck')->run()['data'] ?? [];
             $check = 1;
             foreach($createCheck as $v){
                 if($v['is'] == 0){
                     $check = 0;
                 }
             }

             $param = [
                'GoodsCategory'         => Factory::instance('/goods/v1/Shop/category_list')->run()['data'] ?? [],
                'createCheck'           => $createCheck,
                'check'                 => $check,
                'headers'               => [
                    'headers0'          => AuthApi::getInstance('/goods/v1/GoodsCategory/index')->createHeaders(),
                ],
             ];
        } else {
            $goods_score_multi = config('site')['goods']['goods_score_multi'];
            $goods_score_multi = explode(',',$goods_score_multi);
            $template = '';
            $param = [
                'ShopGoodsCategory'         => Factory::instance('/goods/v1/ShopGoodsCategory/index2')->run()['data'] ?? [],
                'GoodsExpressTpl'           => Factory::instance('/goods/v1/GoodsExpressTpl/index')->run()['data'] ?? [],
                'GoodsPackageTpl'           => Factory::instance('/goods/v1/GoodsPackageTpl/index')->run()['data'] ?? [],
                'GoodsProtectionTpl'        => Factory::instance('/goods/v1/GoodsProtectionTpl/index')->run()['data'] ?? [],
                'ShopGoodsBrand'            => Factory::instance('/goods/v1/ShopGoodsBrand/index')->run()['data'] ?? [],
                'GoodsCategoryDetail'       => Factory::instance('/goods/v1/GoodsCategory/detail')->run()['data'] ?? [],
                'GoodsParamsGroup'          => Factory::instance('/goods/v1/GoodsParamsGroup/index')->run()['data'] ?? [],
                'GoodsQualificationsGroup'  => Factory::instance('/goods/v1/GoodsQualificationsGroup/index')->run()['data'] ?? [],
                'GoodsScoreMultis'         => $goods_score_multi,
                'UEditor'                   => $this->createEditor(),
                'headers'                   => [
                    'headers0'                      => AuthApi::getInstance('/goods/v1/Goods/create')->createHeaders(),
                ],
                'GoodsCondition'            => [
                    'sku_group_max'                 => State::STATE_GOODS_SKU_GROUP_MAX,
                    'sku_group_value_max'           => State::STATE_GOODS_SKU_GROUP_VALUE_MAX,
                    'sku_group_value_images_max'    => State::STATE_GOODS_SKU_GROUP_VALUE_IMAGES_MAX,
                ],
            ];
        }
        // print_r(Factory::instance('/goods/v1/ShopGoodsCategory/index2')->run());exit;
        return view($template, $param);
    }

    /**
     * 编辑商品
     * @return \think\response\View
     */
    public function edit()
    {
        // dump(request()->data);exit;
        $goods = Factory::instance('/goods/v1/Goods/detail2')->run()['data'] ?? [];
        if ( empty($goods) ) {
            return view('empty');
        }
        $category_id = input('category_id', 0, 'int');
        $category_id = $category_id > 0 ? $category_id : $goods['goods_category_id'];
        // 赋值变量
        request()->get([ 'category_id' => $category_id ]);

        $goods_score_multi = config('site')['goods']['goods_score_multi'];
        $goods_score_multi = explode(',',$goods_score_multi);
        
        // dump($goods);;
        // dump(Factory::instance('/goods/v1/GoodsParamsGroup/index')->run());exit;
        // dump(Factory::instance('/goods/v1/GoodsQualifications/detail')->run(['goods_id' => $goods['goods_id']]));exit;
        return view('',[
            'goods'                     => $goods,
            'category_id'               => $category_id,
            'GoodsCategory'             => Factory::instance('/goods/v1/Shop/category_list')->run()['data'] ?? [],
            'ShopGoodsCategory'         => Factory::instance('/goods/v1/ShopGoodsCategory/index2')->run()['data'] ?? [],
            'GoodsExpressTpl'           => Factory::instance('/goods/v1/GoodsExpressTpl/index')->run()['data'] ?? [],
            'GoodsPackageTpl'           => Factory::instance('/goods/v1/GoodsPackageTpl/index')->run()['data'] ?? [],
            'GoodsProtectionTpl'        => Factory::instance('/goods/v1/GoodsProtectionTpl/index')->run()['data'] ?? [],
            'ShopGoodsBrand'            => Factory::instance('/goods/v1/ShopGoodsBrand/index')->run()['data'] ?? [],
            'GoodsCategoryDetail'       => Factory::instance('/goods/v1/GoodsCategory/detail')->run()['data'] ?? [],
            'GoodsParamsGroup'          => Factory::instance('/goods/v1/GoodsParamsGroup/index')->run()['data'] ?? [],
            'GoodsParams'               => Factory::instance('/goods/v1/GoodsParams/detail')->run(['goods_id' => $goods['goods_id']])['data'] ?? [],
            'GoodsQualificationsGroup'  => Factory::instance('/goods/v1/GoodsQualificationsGroup/index')->run()['data'] ?? [],
            'GoodsQualifications'       => Factory::instance('/goods/v1/GoodsQualifications/detail')->run(['goods_id' => $goods['goods_id']])['data'] ?? [],
            'GoodsScoreMultis'         => $goods_score_multi,
            'UEditor'                   => $this->createEditor(htmlspecialchars_decode($goods['goods_content']['goods_content']) ?? ''),
            'headers'                   => [
                'headers0'                      => AuthApi::getInstance('/goods/v1/Goods/update')->createHeaders(),
                'headers1'                      => AuthApi::getInstance('/goods/v1/GoodsCategory/index')->createHeaders(),
            ],
            'GoodsCondition'            => [
                'sku_group_max'                 => State::STATE_GOODS_SKU_GROUP_MAX,
                'sku_group_value_max'           => State::STATE_GOODS_SKU_GROUP_VALUE_MAX,
                'sku_group_value_images_max'    => State::STATE_GOODS_SKU_GROUP_VALUE_IMAGES_MAX,
            ],
            'imgUrl'                    => Fun::getImagesDomain(),
        ]);
    }

    /**
     * 编辑商品,修改商品分类
     * @return \think\response\View
     */
    public function edit_category(){
        // dump(Factory::instance('/goods/v1/GoodsCategory/detail')->run()['data']);exit;
        return view('',[
            'GoodsCategory'         => Factory::instance('/goods/v1/Shop/category_list')->run()['data'] ?? [],
            'GoodsCategoryDetail'   => Factory::instance('/goods/v1/GoodsCategory/detail')->run()['data'] ?? [],
        ]);
    }

    /**
     * 批量设置店铺分类
     * @return \think\response\View
     */
    public function set_category()
    {
        return view('',[
            'list' => Factory::instance('/goods/v1/ShopGoodsCategory/index2')->run()['data'] ?? [],
        ]);
    }

    /**
     * 批量设置运费模板
     * @return \think\response\View
     */
    public function set_express()
    {
        return view('',[
            'list' => Factory::instance('/goods/v1/GoodsExpressTpl/index')->run()['data'] ?? [],
        ]);
    }

    /**
     * 编辑器
     * @return html
     */
    protected function createEditor($content = ''){
        $UEditorBar = [
            'source', //源代码
            'undo', //撤销
            'redo', //重做
            'bold', //加粗
            'italic', //斜体
            'underline', //下划线
            'link', //超链接
            'unlink', //取消链接
            'fontborder', //字符边框
            'strikethrough', //删除线
            'superscript', //上标
            'subscript', //下标
            'removeformat', //清除格式
            'formatmatch', //格式刷
            'autotypeset', //自动排版
            'pasteplain', //纯文本粘贴模式
            'forecolor', //字体颜色
            'backcolor', //背景色
            'insertorderedlist', //有序列表
            'insertunorderedlist', //无序列表
            'selectall', //全选
            'cleardoc', //清空文档
            'paragraph', //段落格式
            'fontfamily', //字体
            'fontsize', //字号
            'justifyleft', //居左对齐
            'justifyright', //居右对齐
            'justifycenter', //居中对齐
            'justifyjustify', //两端对齐
            'simpleupload', //单图上传
            'insertimage', //多图上传
            'horizontal', //分隔线
            'print', //打印
            'preview', //预览
            'searchreplace', //查询替换
            'help', //帮助
            'edittip ', //编辑提示
        ];
        return UEditor::getInstance(request()->module())->setToolbar($UEditorBar)->setBaseContent($content)->setId('goods_content')->create();
    }

    /**
     * 申请优选首页
     * @return \think\response\View
     */
    public function apply_recommend()
    {
        // request()->get([ 'shop_id' => 1 ]);
        $list = Factory::instance('/goods/v1/GoodsApplyRecommend/index')->run();
        // dump($list);
        // dump($list);exit;
        return view('',[
            'data'              => $list['data'] ?? [],
            'applyList'         => $list['data']['data'] ?? [],
            'recommendAttr'     => State::STATE_GOODS_RECOMMEND_TYPE_ARRAYS,
//            'page'              => Fun::pageTemplate($list),
        ]);
    }

    /**
     * 优选发起
     * @return \think\response\View
     */
    public function apply_recommend_create()
    {
        $list = Factory::instance('/goods/v1/GoodsApplyRecommend/canApplyGoodsList')->run();
        // dump($list);
        // dump($list);exit;
        return view('',[
            'data'              => $list['data'] ?? [],
            'list'              => $list['data']['data'] ?? [],
            'recommendAttr'     => State::STATE_GOODS_RECOMMEND_TYPE_ARRAYS,
            'noAudit'           => Factory::instance('/goods/v1/GoodsApplyRecommend/noAudit')->run()['data'] ?? [],
//            'page'              => Fun::pageTemplate($list),
            'headers'           => [
                'headers0'          => AuthApi::getInstance('/goods/v1/GoodsApplyRecommend/create')->createHeaders(),
            ],
        ]);
    }

    /**
     * 申请精选首页
     * @return \think\response\View
     */
    public function apply_recommend1()
    {
        // request()->get([ 'shop_id' => 1 ]);
        $list = Factory::instance('/goods/v1/GoodsApplyRecommend1/index')->run();
        // dump($list);
        // dump($list);exit;
        return view('',[
            'data'              => $list['data'] ?? [],
            'applyList'         => $list['data']['data'] ?? [],
            'recommendAttr'     => State::STATE_GOODS_RECOMMEND_TYPE_ARRAYS,
//            'page'              => Fun::pageTemplate($list),
        ]);
    }

    /**
     * 精选发起
     * @return \think\response\View
     */
    public function apply_recommend1_create()
    {
        $list = Factory::instance('/goods/v1/GoodsApplyRecommend1/canApplyGoodsList')->run();
        // dump(session('user'));
        // dump(Factory::instance('/goods/v1/Goods/statistics')->run()['data'] ?? []);
        // dump($list);exit;
        return view('',[
            'data'              => $list['data'] ?? [],
            'list'              => $list['data']['data'] ?? [],
            'recommendAttr'     => State::STATE_GOODS_RECOMMEND_TYPE_ARRAYS,
            'recommend1Notice1' => Factory::instance('/article/v1/article/detail')->run(['id'=>config('site.goods')['goods_recommend1_article_id1'] ?? 0])['data'] ?? [],
            'recommend1Notice2' => Factory::instance('/article/v1/article/detail')->run(['id'=>config('site.goods')['goods_recommend1_article_id2'] ?? 0])['data'] ?? [],
            'shopDetail'        => Factory::instance('/goods/v1/Shop/Detail')->run(['shop_id' => session('user.user_shop_id')])['data'] ?? [],
            'statistics'        => Factory::instance('/goods/v1/Goods/statistics')->run()['data'] ?? [],
            'noAudit'           => Factory::instance('/goods/v1/GoodsApplyRecommend1/noAudit')->run()['data'] ?? [],
//            'page'              => Fun::pageTemplate($list),
            'headers'           => [
                'headers0'          => AuthApi::getInstance('/goods/v1/GoodsApplyRecommend1/create')->createHeaders(),
            ],
        ]);
    }

    public function apply_recommend1_create_content()
    {
        $goodsSku = Fun::dataAll(Fun::mApi('goods','GoodsSku'),[
            'where' => [
                'goods_id' => intval(request()->param()['goods_id'] ?? 0),
            ],
            'field' => 'goods_sku_id,goods_sku_group_values,goods_sku_price,goods_sku_market_price,goods_id'
        ]);
        // dump($goodsSku);exit;
        return view('',[
            'goodsSku' => $goodsSku,
        ]);
    }

    public function apply_recommend1_create_must_know()
    {
        return view();
    }
}