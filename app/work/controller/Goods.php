<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
use app\common\traits\F as Fun;
class Goods extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 619;  //表单模板ID
        $this->module_name  = '商品';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);
        $btns   = ['return \'<a href="/goods/edit/goods_id/\'.$val[\'goods_id\'].\'" class="btn blue btn-outline btn-block">修改</a>\'.\'<a href="javascript:;" style="margin-top:10px;"data-clipboard-text="'.Fun::domain('wap').'/goods?id=\'. db(\'goods_sku\')->where([\'goods_id\'=>$val[\'goods_id\']])->order(\'goods_sku_price asc\')->value(\'goods_sku_id\') .\'" class="btn blue btn-outline btn-block copy-href">复制链接</a>\';'];
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
     * 设置精选优选
     */
    public function setRecommendType(){
        // dump(request()->post());exit;
        if ( false === db('goods')->where(['goods_id'=> ['in',input()['goods_id']]])->update(['goods_update_time' => time(), 'goods_recommend_type' => input('value', 0, 'int')]) ) {
            return [ 'code' => 0, 'msg' => '设置失败' ];
        } else {
            ### 删除旧缓存，商品详情的
            \app\api\controller\goods\v1\Goods::deleteDetailCache(input()['goods_id'] ?? '');
            return [ 'code' => 1, 'msg' => '设置成功' ];
        }
    }

    /**
     * 设置自营
     */
    public function setIsSelf(){
        // dump(request()->param()['goods_id']);exit;
        if ( false === db('goods')->where(['goods_id'=> ['in',input()['goods_id']]])->update(['goods_update_time' => time(), 'goods_is_self' => input('value', 0, 'int')]) ) {
            return [ 'code' => 0, 'msg' => '设置失败' ];
        } else {
            ### 删除旧缓存，商品详情的
            \app\api\controller\goods\v1\Goods::deleteDetailCache(input()['goods_id'] ?? '');
            return [ 'code' => 1, 'msg' => '设置成功' ];
        }
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
        // dump((100 - $this->post['goods_shopping_score_multi']) * round($this->post['goods_min_price'],2) / 100);exit;
        if ( (100 - ceil($this->post['goods_shopping_score_multi'])) * round($this->post['goods_min_price'],2) / 100 < 0.5  ) {
            return [ 'code' => 0, 'msg' => '购物积分比例设置过高' ];
        }
        $this->post['goods_update_time'] = time();
        $res = $this->_edit_save();
        ### 删除旧缓存，商品详情的
        \app\api\controller\goods\v1\Goods::deleteDetailCache(input()['goods_id'] ?? '');
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
