<?php
/**
 * 配置参数设置
 * day:2017-06-17
 */
namespace app\work\controller;
use app\work\controller\Common;
use app\common\traits\F;
use mercury\constants\Cache;
class Config extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index(){
        $group = get_category([
            'table'     => 'config_category',
            'where'     => ['status' => 1],
            'order'     => 'sort asc,id asc',
        ]);
        $this->assign('group',$group['data']);
        return view();
    }

    /**
     * 分组字段
     */
    public function fields(){
        $list = db('config_fields')->where(['group_id' => $this->param['group_id'],'status' => 1])->field('update_time',true)->order('sort asc,id asc')->select();
        foreach($list as $key => &$val){
            if($val['options']){
                $tmp = eval(html_entity_decode($val['options']));
                $val = array_merge($val,$tmp);
            }
        }
        if(isset($val)) unset($val);
        $this->assign('list',$list);

        $config = [];
        $rs = db('config_category')->where(['id' => $this->param['group_id']])->field('config')->find();
        if($rs['config']){
            $config = unserialize((($rs['config'])));
            foreach ($config as &$item) {
                $item   = htmlspecialchars_decode($item);
            }
        }
        $this->assign('config',$config);

        return view();
    }

    public function save(){
        config('default_filter', '');
        $data['config'] = serialize($this->post);
        if(false !== db('config_category')->where(['id' => $this->post['group_id']])->update($data)){
            //更新redis缓存（子项）
            $this->toRedis();
            return ['code' => 1,'msg' => '操作成功！'];
        }
        return ['code' => 0,'msg' => '操作失败！'];
    }

    public function toRedis()
    {
        F::redis()->set(F::getCacheName( Cache::CONFIG_TYPE.$this->post['group_id']), serialize($this->post));
    }

}
