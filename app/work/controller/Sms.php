<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9 0009
 * Time: 13:55
 */

namespace app\work\controller;


use app\common\traits\F;

class Sms  extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
//        '主营各类男袜、女𧙕、运动𧙕、船袜、连裤袜等，诚信经营服务至上。';
        $table_suffix   = request()->get('table', date('Ym'));
//        $data   = F::mongo("gearman_sms_{$table_suffix}")->order('time desc')->find();
        $pagesize   = 40;
        $p          = isset($this->param['p']) && $this->param['p'] ? $this->param['p'] : 1;
        $limit      = (($p - 1) * $pagesize) . ','.$pagesize;

        $where = [];
        if(isset($this->param['mobile']) && $this->param['mobile'] != '') $where['mobile'] = ['like','%'.$this->param['mobile'].'%'];
        if(isset($this->param['content']) && $this->param['content'] != '') $where['content'] = ['like','%'.$this->param['content'].'%'];
        if(isset($this->param['flag']) && $this->param['flag'] != '') {
            switch ($this->param['flag']) {
                case 0:
                    $where['flag'] = false;
                    break;
                case 1:
                    $where['flag'] = true;
                    break;
                default:
                    break;
            }
        }
        $list   = F::mongo("gearman_sms_{$table_suffix}")->where($where)->limit($limit)->order('time','desc')->select();
        $this->assign('list',$list);
        $pageinfo = [
            'p'         => $p,
            'pagesize'  => $pagesize,
        ];
        $this->assign('pageinfo',$pageinfo);

        return view();
    }

    public function send()
    {
        
    }
}