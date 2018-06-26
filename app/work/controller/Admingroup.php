<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Admingroup extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 318;  //表单模板ID
        $this->module_name  = '角色管理';   //模块名称
        $this->initForm();

    }

    public function index(){
        $param = [];

        switch(session('admin.group_id')){
            case 1:
                $param['where']['id'] = ['not in','1,3'];
                break;
            case 3:
                break;
            default:
                $param['where']['employee_id']  = session('admin.id');
        }

        $res = $this->_category($param);
        $this->assign('res',$res);
        $html = html_table($res['data'],$this->formtpl['list_fields'],'',0,$this->formtpl['data_conver']);
        //dump($res);
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
        $this->post['employee_id'] = session('admin.id');
        $res = $this->_add_save();
        return $res;
    }

    /**
     * 菜单列表
     */
    public function menu(){
        $where['status'] = 1;
        if(session('admin.group_id') != 3) $where['id'] = ['in',session('admin.power')['menu_id']];
        $list = get_category([
            'table'     => 'menu',
            'field'     => 'id,upid,name',
            'order'     => 'sort asc,id asc',
            'where'     => $where,
        ]);

        //dump($list);
        //$this->assign('menu',$list['data']);
        $rs = db('admin_group')->where(['id' => $this->param['id']])->field('menu_id')->find();
        $tree = tree($list,$rs['menu_id']);
        $this->assign('tree',$tree);

        return view();
    }

    /**
     * 保存菜单ID
     */
    public function setMenu(){
        $res = db('admin_group')->where(['id' => $this->post['group_id']])->update(['menu_id' => $this->post['ids']]);
        if(false !== $res) return ['code' => 1,'msg' => '操作成功！'];
        return ['code' => 0,'msg' => '操作失败！'];
    }

    /**
     * 模块权限
     */
    public function controllerList(){
        $action = [];
        $rs = db('admin_group')->where(['id' => $this->param['id']])->field('action')->find();
        if($rs['action']) $action = json_decode(html_entity_decode($rs['action']),true);
        $this->assign('action',$action);

        //dump(session('admin.power'));

        $list = db('controller')->where(['status' => 1])->field('controller,controller_name')->select();
        foreach($list as $key => $val){
            if(session('admin.group_id') == 3){
                $list[$key]['action'] = ['C','U','R','D'];
            }else {
                $val['action'] = [];
                if (in_array(strtolower($val['controller']) . ':c', session('admin.power')['action'])) $val['action'][] = 'C';
                if (in_array(strtolower($val['controller']) . ':u', session('admin.power')['action'])) $val['action'][] = 'U';
                if (in_array(strtolower($val['controller']) . ':r', session('admin.power')['action'])) $val['action'][] = 'R';
                if (in_array(strtolower($val['controller']) . ':d', session('admin.power')['action'])) $val['action'][] = 'D';
                if (empty($val['action'])) unset($list[$key]);
                else $list[$key] = $val;
            }

        }
        //dump($list);
        $this->assign('list',$list);

        return view();
    }

    public function setController(){
        $res = db('admin_group')->where(['id' => $this->post['id']])->update(['action' => json_encode($this->post['action'])]);
        if(false !== $res) return ['code' => 1,'msg' => '操作成功！'];
        return ['code' => 0,'msg' => '操作失败！'];
    }
}
