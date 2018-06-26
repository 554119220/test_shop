<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Area extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 507;  //表单模板ID
        $this->module_name  = '地区管理';   //模块名称
        $this->initForm();

    }

    public function index(){
        $param['where'] = ['upid' => 0];
        $param['order'] = 'sort asc,id asc';
        $nav_sort = '';
        if(isset($this->param['upid']) && $this->param['upid']) {
            $param['where']['upid'] = $this->param['upid'];
            $nav_sort = nav_sort(format_model_name($this->formtpl['tables']),$this->param['upid'],'id,upid,a_name','a_name');
        }
        $this->assign('nav_sort',$nav_sort);


        $res = $this->_index($param);
        $this->assign('res',$res);

        $btns   = '<a href="/'.request()->controller().'/edit/id/[id]" class="btn blue btn-outline btn-block md10">修改</a>';
        $btns   .= '<a href="/'.request()->controller().'/'.request()->action().'/upid/[id]" class="btn red btn-outline btn-block">下一级</a>';
        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],$btns,0,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteCategorySelect(){
        $res = $this->_deleteCategorySelect();
        return $res;
    }

    /**
     * 转移目录
     */
    public function changeCategory(){
        $res = $this->_changeCategory();
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
     * 排序
     */
    public function setSort(){
        $res = $this->_setSort();
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
}
