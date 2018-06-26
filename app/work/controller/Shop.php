<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
use mercury\constants\State;
use mercury\factory\Factory;
class Shop extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 597;  //表单模板ID
        $this->module_name  = '店铺列表';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);
        //$btns   = 'return \'<a href="/shop/edit/shop_id/\'.$val[\'shop_id\'].\'" class="btn blue btn-outline btn-block md5">修改</a>\'';   //操作按钮
        $btns   = 'return \'<a href="/shop/edit/shop_id/\'.$val[\'shop_id\'].\'" class="btn blue btn-outline btn-block md5">修改</a>\'.(($val[\'shop_id\']==0) ? \'\':\'\').(($val[\'shop_id\']!=0) ? \'<div class="btn blue btn-outline btn-block" onclick="extra_tr_view($(this))">查看日志</div>\':\'\');';
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
     * 设置自营
     */
    public function setStatus3(){
        $data = input('post.');
        $shop_id = implode(',',$data['shop_id']);
        if(!empty($data['shop_id'])){
            $where['shop_id'] = $shop_id;
            $where['shop_state'] = 1;
            $r = db('shop')->where($where)->update(['shop_type_id'=>14,'shop_update_time'=>time()]);
            if($r){
                $res['msg'] = '设置成功，更新'.$r.'条记录';
                $res['code'] = 1;
            }else{
                $res['msg'] = '设置失败！';
                $res['code'] = 0;
            }
        }else{
            $res['msg'] = '设置失败！';
            $res['code'] = 0;
        }
        return $res;
    }
    /**
     * 批量设置店铺状态
     */
    public function setStatus2(){
        $data = input('post.');
        $shop_id = implode(',',$data['shop_id']);
//        if($data['value'] == 1){
//            $where = [
//                'shop_id'=>['in',$shop_id]
//            ];
//            $res = db('shop')->where($where)->update(['shop_state' => $data['value']]);
//            if($res) return ['code' => 20000,'msg' => '设置成功！'];
//            return ['code' => 0,'msg' => '设置失败！'];
//        }
        if($data['value'] != 1 && intval($data['day']) < 1){
            return ['code'=>0,'msg'=>'当前状态时长不能小于1！'];
        }
        if($data['content'] == ''){
            return ['code'=>0,'msg'=>'操作说明必须填写！'];
        }

        $data['admin_id'] = session('admin.id');
        $res   = Factory::instance('/goods/v1/shopAudit/create')->run($data);
        if($res['code'] == 20000){
            if($data['value'] != 1){
                if(!empty($data['shop_id'])){
                    $where['shop_id'] = $shop_id;
                    if($data['value'] == 5){
                        db('shop')->where($where)->update(['is_again'=>1]);
                    }
                    $where['goods_state'] = State::STATE_GOODS_NORMAL;
                    $r2 = db('goods')->where($where)->select();
                    $r = db('goods')->where($where)->update(['goods_state'=>State::STATE_GOODS_UNDER,'goods_update_time'=>time()]);
                    \app\api\controller\goods\v1\Goods::deleteDetailCache(array_column($r2,'goods_id'));
                    $ress['msg'] = '设置成功，更新'.$r.'条记录';
                    $ress['code'] = 1;
                }
            }else{
                $ress['msg']= '更新成功！';
                $ress['code']= 1;
            }
        }else{
            $ress['msg']= '更新失败！';
            $ress['code']= 0;
        }
        return $ress;
    }

    /**
     * 修改
     */
    public function edit(){
        $res = $this->_edit();
        $data   = Factory::instance('/goods/v1/GoodsCategory/index')->run(['category_sid'=>0]);
        $goods_category_ids = explode(',',$res['goods_category_ids']);
        $goods_category_level1 = explode(',',$res['goods_category_level1']);
        $count = count($goods_category_ids);
        $two = array();
        for ($i = 0; $i < $count; $i++){
            $two[$i]['id'] = $goods_category_ids[$i];

            $list = Factory::instance('/goods/v1/GoodsCategory/detail')->run(['category_id'=>$goods_category_ids[$i]]);

            $info = Factory::instance('/goods/v1/GoodsCategory/index')->run(['category_id'=>$list['data']['category_sid'] ?? 0]);
            $two[$i]['info'] = $info['data'];
        }
        $this->assign('count',$count);
        $this->assign('two',$two);
        $this->assign('category',$data['data'] ?? []);
        $this->assign('goods_category_level1',explode(',',$res['goods_category_level1']));
        $this->assign('goods_category_ids',explode(',',$res['goods_category_ids']));
        return view();
    }

    /**
     * 保存修改
     */
    public function edit_save(){
        $data = request()->post();
        if(in_array(0,explode(',',$data['goods_category_ids'])) || in_array(0,explode(',',$data['goods_category_level1']))){
            return json(['code'=>0,'msg'=>'类目必须填写完整！']);
        }
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
