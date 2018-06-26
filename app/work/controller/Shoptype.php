<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Shoptype extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 602;  //表单模板ID
        $this->module_name  = '店铺类型';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);
        //$btns   = 'return \'<a href="/shoptype/edit/shop_type_id/\'.$val[\'shop_type_id\'].\'" class="btn blue btn-outline btn-block md5">修改</a>\'.(($val[\'shop_type_id\']!=0) ? \'<div class="btn blue btn-outline btn-block md5" onclick="extra_tr_view($(this))">修改资质</div>\':\'\').(($val[\'shop_type_id\']!=0) ? \'<div class="btn red btn-100px btn-outline btn-flush-cache md5" href="javascript:;" onclick="flushCache(this)" data-url="/clearcache/flush?key=table:shop_type:shop_type_id:\'.$val[\'shop_type_id\'].\'">清除店铺缓存</div><div class="btn red btn-100px btn-outline btn-flush-cache md5" href="javascript:;" onclick="flushCache(this)" data-url="/clearcache/flush?key=table:shop_qualifications:list:shop_type_id:\'.$val[\'shop_type_id\'].\'">清除资质缓存</div><div class="btn red btn-100px btn-outline btn-flush-cache md5" href="javascript:;" onclick="flushCache(this)" data-url="/clearcache/flush?key=table:shop_qualifications:type_list:shop_type_id:\'.$val[\'shop_type_id\'].\'">清除资质缓存</div><div class="btn red btn-100px btn-outline btn-flush-cache" href="javascript:;" onclick="flushCache(this)" data-url="/clearcache/flush?key=table:shop_qualifications:user_list:shop_type_id:\'.$val[\'shop_type_id\'].\'">清除资质缓存</div>\':\'\');';
        //$btns   = 'return \' \'.(($val[\'shop_type_id\']!=0) ? \'<a href="javascript:;" onclick="flushCache(this)" data-url="/clearcache/flush?key=table:shop_type:shop_type_id:\'.$val['shop_type_id'].\'" class="btn red btn-100px btn-outline btn-flush-cache"><i class="icon-times"></i> 清除缓存</a>\':\'\');';
        $btns   = 'return \'<a href="/shoptype/edit/shop_type_id/\'.$val[\'shop_type_id\'].\'" class="btn blue btn-outline btn-block md5">修改</a>\'.(($val[\'shop_type_id\']!=0) ? \'<div class="btn blue btn-outline btn-block md5" onclick="extra_tr_view($(this))">修改资质</div>\':\'\').(($val[\'shop_type_id\']!=0) ? \'<div class="btn red btn-100px btn-outline btn-flush-cache md5" href="javascript:;" onclick="flushCache(this)" data-url="/clearcache/flush?key=table:shop_type:shop_type_id:\'.$val[\'shop_type_id\'].\'">清除店铺缓存</div>\':\'\');';
        $btns   = [$btns];
        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],$btns,1,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteSelect(){
        $res = $this->_deleteSelect();
        \app\api\controller\goods\v1\ShopType::toRedis1();
        return $res;
    }

    /**
     * 批量设置状态
     */
    public function setStatus(){
        $res = $this->_setStatus();
        \app\api\controller\goods\v1\ShopType::toRedis1();
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
        # 更新缓存
        \app\api\controller\goods\v1\ShopType::toRedis1();
        \app\api\controller\goods\v1\ShopType::toRedis2(input('shop_type_id'));
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
        # 更新缓存
        \app\api\controller\goods\v1\ShopType::toRedis1();
        return $res;
    }
    /**
     * 转移目录
     */
    public function change2Category(){
        $res = $this->_change2Category();
        return $res;
    }
}
