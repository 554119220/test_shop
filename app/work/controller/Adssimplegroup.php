<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Adssimplegroup extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 726;  //表单模板ID
        $this->module_name  = '简单广告组';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);

        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],'',0,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteSelect(){
        $id = isset($param[$this->formtpl['primary_key']]) ? $param[$this->formtpl['primary_key']] : $this->post[$this->formtpl['primary_key']];
        $where = [
            $this->formtpl['primary_key']   => ['in',$id],
        ];

        // $res = db($this->table)->where($where)->delete();

        if($res) return ['code' => 1,'msg' => '删除成功！'];
        return ['code' => 0,'msg' => '删除失败！'];
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
