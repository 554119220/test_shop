<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\common\traits\F;
use app\work\controller\Commonmodules;
use mercury\constants\State;

class Ordersserviceappeal extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 682;  //表单模板ID
        $this->module_name  = '售后申诉';   //模块名称
        $this->initForm();

    }

    public function index(){
//        $res = $this->_index();

        $params = [
            'where' => ['service_state' => ['in', [State::STATE_SERVICE_SELLER_APPEAL, State::STATE_SERVICE_BUYER_APPEAL]]],
            'page'  => input('p', 1),
            'relation'  => 'service',
            'order' => $this->fcfg['order']
        ];
//        $res = $this->_index($params);
//        dump($res);
        $res = F::pageList($this->fcfg['model'], $params);
        $res['data']['list']    = $res['data'];
        $res['data']['pageinfo'] = [
            'pagesize'  => $res['per_page'],
            'p'         => $res['current_page'],
            'page'      => $res['last_page'],
            'count'     => $res['total'],
        ];

        $this->assign('res',$res);
        $btns   = 'return "<div data-id=\'$val[service_logs_id]\' data-serviceid=\'$val[orders_service_id]\' class=\'btn red btn-outline btn-block\' onclick=\'extra_tr_view($(this))\'>详情</div>";';
        $btns   = [$btns];
        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],$btns,1,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);
//        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],'',0,$this->formtpl['data_conver']);
//        $this->assign('html_table',$html['html']);

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
