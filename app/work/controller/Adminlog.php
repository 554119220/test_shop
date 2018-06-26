<?php
/**
 * 雇员操作日志
 * day:2017-09-21
 */
namespace app\work\controller;
use app\work\controller\Common;
use think\Db;
class Adminlog extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index(){
        $table  = config('mongodb.prefix').'admin_log_'.date('ym');
        $do     = Db::connect(config('mongodb'));

        $pagesize   = 40;
        $p          = isset($this->param['p']) && $this->param['p'] ? $this->param['p'] : 1;
        $limit      = (($p - 1) * $pagesize) . ','.$pagesize;

        $where = [];
        if(isset($this->param['employee_account']) && $this->param['employee_account'] != '') $where['employee_account'] = $this->param['employee_account'];
        if(isset($this->param['employee_name']) && $this->param['employee_name'] != '') $where['employee_name'] = $this->param['employee_name'];
        if(isset($this->param['post']) && $this->param['post'] != '') $where['post'] = ['like','%'.$this->param['post'].'%'];
        if(isset($this->param['get']) && $this->param['get'] != '') $where['get'] = ['like','%'.$this->param['get'].'%'];


        $list   = $do->table($table)->where($where)->limit($limit)->order('atime','desc')->select();
        $this->assign('list',$list);

        $pageinfo = [
            'p'         => $p,
            'pagesize'  => $pagesize,
        ];
        $this->assign('pageinfo',$pageinfo);

        return view();
    }


    public function adminSql(){
        $table  = config('mongodb.prefix').'admin_sql';
        $do     = Db::connect(config('mongodb'));

        $pagesize   = 40;
        $p          = isset($this->param['p']) && $this->param['p'] ? $this->param['p'] : 1;
        $limit      = (($p - 1) * $pagesize) . ','.$pagesize;

        $where = [];
        if(isset($this->param['employee_account']) && $this->param['employee_account'] != '') $where['employee_account'] = $this->param['employee_account'];
        if(isset($this->param['employee_name']) && $this->param['employee_name'] != '') $where['employee_name'] = $this->param['employee_name'];
        if(isset($this->param['sql']) && $this->param['sql'] != '') $where['sql'] = ['like','%'.$this->param['sql'].'%'];


        $list   = $do->table($table)->where($where)->limit($limit)->order('atime','desc')->select();
        $this->assign('list',$list);

        $pageinfo = [
            'p'         => $p,
            'pagesize'  => $pagesize,
        ];
        $this->assign('pageinfo',$pageinfo);

        return view();
    }

    public function smscode(){
        $table  = config('mongodb.prefix').'smscode';
        $do     = Db::connect(config('mongodb'));

        $pagesize   = 40;
        $p          = isset($this->param['p']) && $this->param['p'] ? $this->param['p'] : 1;
        $limit      = (($p - 1) * $pagesize) . ','.$pagesize;

        $where = [];
        if(isset($this->param['mobile']) && $this->param['mobile'] != '') $where['mobile'] = $this->param['mobile'];
        if(isset($this->param['url']) && $this->param['url'] != '') $where['url'] = ['like','%'.$this->param['url'].'%'];
        if(isset($this->param['content']) && $this->param['content'] != '') $where['content'] = ['like','%'.$this->param['content'].'%'];


        $list   = $do->table($table)->where($where)->limit($limit)->order('atime','desc')->select();
        $this->assign('list',$list);

        $pageinfo = [
            'p'         => $p,
            'pagesize'  => $pagesize,
        ];
        $this->assign('pageinfo',$pageinfo);

        return view();
    }
}
