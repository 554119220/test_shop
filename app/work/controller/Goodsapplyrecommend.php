<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
use app\common\traits\F as Fun;
use mercury\constants\State;
use mercury\factory\Factory;

class Goodsapplyrecommend extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 690;  //表单模板ID
        $this->module_name  = '优选申请';   //模块名称
        $this->initForm();
        $this->list_fields = [
            [
                'label' => "ID",
                'name' => "goods_apply_recommend_id",
                'function' => '',
                'attr' => '',
            ],
            [
                'label' => "商家",
                'name' => "seller_user_id",
                'function' => 'return db("user")->find($val["seller_user_id"])["user_username"] ?? "";',
                'attr' => '',
            ],
            [
                'label' => "商品主图",
                'name' => "goods_id",
                'function' => 'return \'<a class="image-zoom" style="width:200px" href="\' . app\common\traits\F::getImages($val["goods"]["goods_images"] ?? "") . \'"><img src="\' . app\common\traits\F::getImages($val["goods"]["goods_images"] ?? "") . \'?imageMogr2/thumbnail/400x400!"></a>\';',
                'attr' => 'style="width:210px;"',
            ],
            [
                'label' => "商品名称",
                'name' => "goods_id",
                'function' => 'return db("goods")->find($val["goods_id"])["goods_name"] ?? "";',
                'attr' => '',
            ],
            [
                'label' => "操作",
                'name' => "goods_apply_recommend_create_time",
                'function' => '
                    return $val[\'goods_apply_recommend_is_audit\'] == 0
                    ? 
                    \'<div class="btn blue btn-outline btn-block" style="margin:10px 0;"><a target="_blank" href="\' . \app\common\traits\F::domain("wap") . \'/goods?id=\' . db("goods_sku")->where(["goods_id" => $val["goods"]["goods_id"] ?? 0])->value("goods_sku_id") . \'">查看商品</a></div>
                    <div class="btn red btn-outline btn-block" onclick="extra_tr_view($(this))">审核</div>\'
                    :
                    \'<div class="btn blue btn-outline btn-block" style="margin:10px 0;"><a target="_blank" href="\' . \app\common\traits\F::domain("wap") . \'/goods?id=\' . db("goods_sku")->where(["goods_id" => $val["goods"]["goods_id"] ?? 0])->value("goods_sku_id") . \'">查看商品</a></div>
                    <div class="btn blue btn-outline btn-block" onclick="extra_tr_view($(this))">查看详情</div>\';
                ',
                'attr' => '',
            ],
            [
                'label' => "快速操作",
                'name' => "goods_apply_recommend_create_time",
                'function' => '
                    if ($val["goods_apply_recommend_is_audit"] == 1) {
                        return "无";
                    } else {
                        $select = [];
                        $select[] = \'<select style="margin:5px 0;" class="form-control">\';
                        $select[] = \'    <option value="">拒绝理由</option>\';
                        foreach(explode(PHP_EOL, config("site.goods")["goods_recommend_refuse_content"]) as $v){
                            $select[] = \'    <option value="\' . $v . \'">\' . $v . \'</option>\';
                        }
                        $select[] = \'</select>\';
                        return \'
                            <div class="btn blue btn-outline btn-block pass_fast" >直接通过</div>
                            \' . implode("", $select) . \'
                            <div style="margin:5px 0;" class="btn red btn-outline btn-block refuse_fast">直接拒绝</div>
                        \';
                    }
                ',
                'attr' => '',
            ],
            [
                'label' => "商品状态",
                'name' => "goods_id",
                'function' => 'return \mercury\constants\State::STATE_GOODS_ARRAY[$val["goods"]["goods_state"] ?? null] ?? "--";',
                'attr' => '',
            ],
            [
                'label' => "最大价格",
                'name' => "goods_id",
                'function' => 'return $val["goods"]["goods_max_price"] ?? "--";',
                'attr' => '',
            ],
            [
                'label' => "最小价格",
                'name' => "goods_id",
                'function' => 'return $val["goods"]["goods_min_price"] ?? "--";',
                'attr' => '',
            ],
            [
                'label' => "商品类目",
                'name' => "goods_id",
                'function' => '
                    $categoryDetail = \mercury\factory\Factory::instance("/goods/v1/GoodsCategory/detail")->run(["category_id" => $val["goods"]["goods_category_id"] ?? 0]);
                    return implode(" <span style=\"color:red;\">/</span> ", $categoryDetail["data"]["upName"] ?? ["--"]);',
                'attr' => '',
            ],
            [
                'label' => "商品库存",
                'name' => "goods_id",
                'function' => 'return $val["goods"]["goods_sku_num"] ?? "--";',
                'attr' => '',
            ],
            [
                'label' => "商品销量",
                'name' => "goods_id",
                'function' => 'return $val["goods"]["goods_sale_num"] ?? "--";',
                'attr' => '',
            ],
            [
                'label' => "购物积分比例",
                'name' => "goods_id",
                'function' => 'return $val["goods"]["goods_shopping_score_multi"] ?? "--";',
                'attr' => '',
            ],
            [
                'label' => "资质图片",
                'name' => "goods_id",
                'function' => '
                    # 所需资质列表
                    $GoodsQualificationsGroup = \mercury\factory\Factory::instance("/goods/v1/GoodsQualificationsGroup/index")->run(["category_id" => $val["goods"]["goods_category_id"] ?? 0])["data"] ?? [];
                    # 查找类型为5的 图片类型
                    $QualificationsGroup = [];
                    foreach($GoodsQualificationsGroup as $GoodsQualificationsGroupInfo){
                        if ( $GoodsQualificationsGroupInfo["goods_qualifications_group_form_type"] == 5 ) {
                            $QualificationsGroup[] = $GoodsQualificationsGroupInfo["goods_qualifications_group_id"];
                        }
                    }
                    # 查找到商品id的资质
                    $QualificationsImageKeyArr = db("goods_qualifications")->field("qualifications_value")->where(["goods_id" => $val["goods"]["goods_id"],"qualifications_id" => ["in",$QualificationsGroup]])->select();
                    # 查找到商品id的资质 是否有图片key
                    foreach($QualificationsImageKeyArr as $QualificationsImageKeyInfo){
                        $QualificationsImageKey = $QualificationsImageKeyInfo["qualifications_value"] ? json_decode($QualificationsImageKeyInfo["qualifications_value"],true) : [];
                        if ( isset($QualificationsImageKey[0]) ) {
                            $QualificationsImage = app\common\traits\F::getImages($QualificationsImageKey[0] ?? "");
                            return \'<img data-original="\' . $QualificationsImage . \'" class="docs-pictures" src="\' . $QualificationsImage . \'?imageMogr2/thumbnail/60x60!">\';
                        }
                    }
                    # 没有找到图片key
                    return \'<span style="color:red;">无图片</span>\';
                ',
                'attr' => '',
            ],
            [
                'label' => "是否已审核",
                'name' => "goods_apply_recommend_is_audit",
                'function' => 'return $val["goods_apply_recommend_is_audit"] ? "是" : "否";',
                'attr' => '',
            ],
            [
                'label' => "申请时间",
                'name' => "goods_apply_recommend_create_time",
                'function' => 'return date(\'Y-m-d H:i:s\',$val[\'goods_apply_recommend_create_time\']);',
                'attr' => '',
            ],
        ];
    }

    public function index(){
        # 是否已审核
        $isAudit = input('goods_apply_recommend_is_audit');
        if ( preg_match('/^[0-9]+$/', $isAudit) ) {
            $selectMap = [
                'goods_apply_recommend_is_audit' => $isAudit,
            ];
        } else {
            $selectMap = [
                'goods_apply_recommend_is_audit' => ['in',[0,1]],
            ];
        }
        # 查找商家
        $seller = input('seller_user_id');
        if ( $seller ) {
            $selectMap['seller_user_id'] = db('user')->where(['user_username' => $seller])->find()['user_id'] ?? 0;
        }
        # get data
        $res = Fun::pageList($this->fcfg['model'],[
            'order'     => 'goods_apply_recommend_create_time desc',
            'relation'  => 'goods',
            'page'      => input('p', 1),
            'where'     => $selectMap,
        ]);
        $res['data']['list']    = $res['data'] ?? [];
        $res['data']['pageinfo'] = [
            'pagesize'  => $res['per_page'] ?? 0,
            'p'         => $res['current_page'] ?? 0,
            'page'      => $res['last_page'] ?? 0,
            'count'     => $res['total'] ?? 0,
        ];
        
        $this->assign('res',$res);
        $btns   = '';
        $btns   = [$btns];
        $html = html_table($res['data']['list'],$this->list_fields, false,1,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view();
    }

    public function indexItemAudit(){
        $info = Fun::dataDetail(Fun::mApi('goods','GoodsApplyRecommend'),[
            'where' => [ 'goods_apply_recommend_id' => input('id',0,'intval') ],
            'relation' => 'goods',
        ]);
        
        $list = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommendAudit'),[
            'where' => [ 'goods_apply_recommend_id' => input('id',0,'intval') ],
            'order' => 'goods_apply_recommend_audit_create_time desc',
        ]);
        $GoodsQualificationsGroup = Factory::instance('/goods/v1/GoodsQualificationsGroup/index')->run(['category_id' => $info['goods']['goods_category_id'] ?? 0])['data'] ?? [];
        $GoodsQualifications = Factory::instance('/goods/v1/GoodsQualifications/detail')->run(['goods_id' => $info['goods']['goods_id'] ?? 0])['data'] ?? [];
        $this->assign('info', $info);
        $this->assign('list',$list);
        $this->assign('goodsSkuId', db('goods_sku')->where(['goods_id'=>$info['goods']['goods_id'] ?? 0])->value('goods_sku_id'));
        $this->assign('GoodsQualificationsGroup', $GoodsQualificationsGroup);
        $this->assign('GoodsQualifications', array_column($GoodsQualifications, null,'qualifications_id'));
        return view();
    }

    public function indexItemAuditLog(){
        $list = Fun::dataAll(Fun::mApi('goods','GoodsApplyRecommendAudit'),[
            'where' => [ 'goods_apply_recommend_id' => input('id',0,'intval') ],
        ]);
        $info = Fun::dataDetail(Fun::mApi('goods','GoodsApplyRecommend'),[
            'where' => [ 'goods_apply_recommend_id' => input('id',0,'intval') ],
            'relation' => 'goods',
        ]);
        // dump($list);
        $this->assign('list',$list);
        $this->assign('info', $info);
        $this->assign('goodsSkuId', db('goods_sku')->where(['goods_id'=>$info['goods']['goods_id'] ?? 0])->value('goods_sku_id'));
        return view();
    }


    public function indexItemAuditSave(){
        db()->startTrans();
        $info = Fun::dataDetail(Fun::mApi('goods','GoodsApplyRecommend'),input('goods_apply_recommend_id'));
        if ( empty($info) ) {
            return [ 'code' => 0, 'msg' => '操作失败' ];
        }
        # 已审核
        $update1 = ['goods_apply_recommend_is_audit' => 1];
        $updateMap1 = ['goods_apply_recommend_id' => input('goods_apply_recommend_id')];
        if ( false == Fun::mApi('goods','GoodsApplyRecommend')->allowField(true)->save($update1,$updateMap1) ) {
            return [ 'code' => 0, 'msg' => '操作失败1' ];
        }
        # 添加操作记录
        if ( false == Fun::mApi('goods','GoodsApplyRecommendAudit')->isUpdate(false)->allowField(true)->save(request()->post()) ) {
            return [ 'code' => 0, 'msg' => '操作失败2' ]; 
        }
        # 商品状态
        if ( 1 == request()->post('goods_apply_recommend_audit_pass') ) {
            $goodsUpdate = [
                'goods_recommend_type'  => State::STATE_GOODS_RECOMMEND_TYPE_THREE,
                'goods_update_time'     => time(),
            ];
            if ( false == db('goods')->where(['goods_id' => $info['goods_id']])->update($goodsUpdate) ) {
                return [ 'code' => 0, 'msg' => '操作失败3' ];
            }
            ### 删除旧缓存，商品详情的
            \app\api\controller\goods\v1\Goods::deleteDetailCache($info['goods_id']);
        }
        db()->commit();
        return [ 'code' => 1, 'msg' => '操作成功' ];
    }

    /**
     * 批量审核
     * @return [type] [description]
     */
    public function indexItemAuditSaveAll(){
        // dump($this->post);exit;
        $ids = $this->post['goods_apply_recommend_id'] ?? [];
        db()->startTrans();
        foreach ($ids as $goods_apply_recommend_id) {
            $info = Fun::dataDetail(Fun::mApi('goods','GoodsApplyRecommend'),$goods_apply_recommend_id);
            if ( empty($info) ) {
                return [ 'code' => 0, 'msg' => '操作失败' ];
            }
            if ( $info['goods_apply_recommend_is_audit'] == 1 ) {
                return [ 'code' => 0, 'msg' => 'ID为的' . $goods_apply_recommend_id . '记录已审核' ];
            }
            # 已审核
            $update1 = ['goods_apply_recommend_is_audit' => 1];
            $updateMap1 = ['goods_apply_recommend_id' => $goods_apply_recommend_id];
            if ( false == Fun::mApi('goods','GoodsApplyRecommend')->allowField(true)->save($update1,$updateMap1) ) {
                return [ 'code' => 0, 'msg' => '操作失败1' ];
            }
            # 添加操作记录
            $addAuditData = array_merge($this->post,['goods_apply_recommend_id' => $goods_apply_recommend_id]);
            if ( false == Fun::mApi('goods','GoodsApplyRecommendAudit')->isUpdate(false)->allowField(true)->save($addAuditData) ) {
                return [ 'code' => 0, 'msg' => '操作失败2' ]; 
            }
            # 商品状态
            if ( 1 == request()->post('goods_apply_recommend_audit_pass') ) {
                $goodsUpdate = [
                    'goods_recommend_type'  => State::STATE_GOODS_RECOMMEND_TYPE_THREE,
                    'goods_update_time'     => time(),
                ];
                if ( false == db('goods')->where(['goods_id' => $info['goods_id']])->update($goodsUpdate) ) {
                    return [ 'code' => 0, 'msg' => '操作失败3' ];
                }
                ### 删除旧缓存，商品详情的
                \app\api\controller\goods\v1\Goods::deleteDetailCache($info['goods_id']);
            }
        }
        db()->commit();
        return [ 'code' => 1, 'msg' => '操作成功' ];
    }

    /**
     * 批量删除
     */
    public function deleteSelect(){
        $id = $param[$this->formtpl['primary_key']] ?? $this->post[$this->formtpl['primary_key']];
        $where = [
            $this->formtpl['primary_key']   => ['in',$id]
        ];

        $res = db($this->table)->where($where)->delete();

        if($res) return ['code' => 1,'msg' => '删除成功！'];
        return ['code' => 0,'msg' => '删除失败！'];
    }

    /**
     * 批量设置状态
     */
    public function setStatus(){
        $res = $this->_setStatus();
        return $res;
    }

    /**
     * 修改
     */
    public function edit(){
        $res = $this->_edit();
        return view();
    }

    /**
     * 保存修改
     */
    public function edit_save(){
        $res = $this->_edit_save();
        return $res;
    }

    /**
     * 新增
     */
    public function add(){
        $res = $this->_add();
        return view();
    }
    /**
     * 保存新增
     */
    public function add_save(){
        $res = $this->_add_save();
        return $res;
    }
    /**
     * 转移目录
     */
    public function change2Category(){
        $res = $this->_change2Category();
        return $res;
    }

    public function test()
    {
        config('app_debug', true);
        config('app_trace', true);
        config('show_error_msg', true);
        # 是否已审核
        $isAudit = input('goods_apply_recommend_is_audit');
        if ( preg_match('/^[0-9]+$/', $isAudit) ) {
            $selectMap = [
                'goods_apply_recommend_is_audit' => $isAudit,
            ];
        } else {
            $selectMap = [
                'goods_apply_recommend_is_audit' => ['in',[0,1]],
            ];
        }
        # 查找商家
        $seller = input('seller_user_id');
        if ( $seller ) {
            $selectMap['seller_user_id'] = db('user')->where(['user_username' => $seller])->find()['user_id'] ?? 0;
        }
        # get data
        $res = Fun::pageList($this->fcfg['model'],[
            'order'     => 'goods_apply_recommend_create_time desc',
            'relation'  => 'goods',
            'page'      => input('p', 1),
            'where'     => $selectMap,
        ]);
        $res['data']['list']    = $res['data'] ?? [];
        $res['data']['pageinfo'] = [
            'pagesize'  => $res['per_page'] ?? 0,
            'p'         => $res['current_page'] ?? 0,
            'page'      => $res['last_page'] ?? 0,
            'count'     => $res['total'] ?? 0,
        ];
        
        $this->assign('res',$res);
        $btns   = '';
        $btns   = [$btns];
        $html = html_table($res['data']['list'],$this->list_fields, false,1,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view('index');
    }
}