<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Adssucai extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 729;  //表单模板ID
        $this->module_name  = '广告素材';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);
        $btns   = 'return \'<a href="/Adssucai/edit/ads_sucai_id/\'.$val[\'ads_sucai_id\'].\'" class="btn blue btn-outline btn-block md5">修改</a>\'.(($val[\'ads_sucai_id\']!=0) ? \'<div class="btn red btn-100px btn-outline btn-flush-cache md5" onclick="extra_tr_view($(this))">审核</div>\':\'\').(($val[\'ads_sucai_id\']!=0) ? \'\':\'\');';
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
        return $res;
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
}
