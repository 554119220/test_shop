<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Goodsparamsgroup extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 629;  //表单模板ID
        $this->module_name  = '商品参数组';   //模块名称
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
        if ( false == db('goods_params_group')->where(['params_group_id' => ['in', $this->post['params_group_id']]])->delete() ) {
            return [ 'code' => 0, 'msg' => '删除失败' ];
        }
        db('goods_params_group_value')->where(['group_id' => ['in', $this->post['params_group_id']]])->delete();
        return [ 'code' => 1, 'msg' => '删除成功' ];
    }

    /**
     * 批量设置状态
     */
    public function setStatus(){
        // $res = $this->_setStatus();
        return $res;
    }

    /**
     * 修改
     */
    public function edit(){
        $res = $this->_edit();
        $groupValue = db('goods_params_group_value')->where(['group_id' => input('params_group_id',0,'int')])->select();
        return view('',[
            'groupValue' => $groupValue ? implode(PHP_EOL,array_column($groupValue, 'params_group_value_name')) : '',
        ]);
    }

    /**
     * 保存修改
     */
    public function edit_save(){
        $this->post_cmp();
        $res = $this->validate($this->post,$this->fcfg['model'].'.edit');
        if($res !== true){
            return ['code' => 0,'msg' => $res];
        }
        # 获取组值和验证
        $value = explode(PHP_EOL, str_replace(PHP_EOL.PHP_EOL, PHP_EOL, trim($this->post['params_group_value_name'])));
        if (empty($value) && $this->post['params_group_form_type'] != \mercury\constants\State::GOODS_PARAMS_GROUP_TYPE_INPUT ) {
            return [ 'code' => 0, 'msg' => '参数组值必须' ];
        }
        if (empty($value) && $this->post['params_group_form_type'] != \mercury\constants\State::GOODS_PARAMS_GROUP_TYPE_TEXTAREA ) {
            return [ 'code' => 0, 'msg' => '参数组值必须' ];
        }
        # 修改
        $db = db();
        $db->startTrans();
        $res = model($this->fcfg['model'])->allowField(true)->save($this->post,[$this->formtpl['primary_key'] => $this->post[$this->formtpl['primary_key']]]);
        if(false !== $res){
            # 修改组值
            $id = $this->post['params_group_id'];
            // dump($id);exit;
            foreach ($value as $valueName) {
                $tempArr[] = [
                    'params_group_value_state'          => 1,
                    'params_group_value_name'           => $valueName,
                    'group_id'                          => $id,
                    'params_group_value_create_time'    => time(),
                    'params_group_value_update_time'    => time(),
                ];
            }
            # 删除旧
            if ( false === db('goods_params_group_value')->where(['group_id' => $id])->delete() ) {
                return ['code' => 0,'msg' => '添加失败！'];
            }
            # 添加新
            if ( false === db('goods_params_group_value')->insertAll($tempArr) ) {
                return ['code' => 0,'msg' => '添加失败！'];
            }
            $db->commit();
            return ['code' => 1,'msg' => '修改成功！'];
        }
        return ['code' => 0,'msg' => '修改失败！'];
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

        $this->post_cmp();
        $res = $this->validate($this->post,$this->fcfg['model'].'.add');
        if($res !== true){
            return ['code' => 0,'msg' => $res];
        }
        # 获取组值和验证
        $value = explode(PHP_EOL, str_replace(PHP_EOL.PHP_EOL, PHP_EOL, trim($this->post['params_group_value_name'])));
        if (empty($value) && $this->post['params_group_form_type'] != \mercury\constants\State::GOODS_PARAMS_GROUP_TYPE_INPUT ) {
            return [ 'code' => 0, 'msg' => '参数组值必须' ];
        }
        if (empty($value) && $this->post['params_group_form_type'] != \mercury\constants\State::GOODS_PARAMS_GROUP_TYPE_TEXTAREA ) {
            return [ 'code' => 0, 'msg' => '参数组值必须' ];
        }
        # 添加
        $db = db();
        $db->startTrans();
        $res = model($this->fcfg['model'])->allowField(true)->save($this->post);
        if(false !== $res){
            # 添加组值
            $id = $db->getLastInsID();
            foreach ($value as $valueName) {
                $tempArr[] = [
                    'params_group_value_state'          => 1,
                    'params_group_value_name'           => $valueName,
                    'group_id'                          => $id,
                    'params_group_value_create_time'    => time(),
                    'params_group_value_update_time'    => time(),
                ];
            }
            if ( false == db('goods_params_group_value')->insertAll($tempArr) ) {
                return ['code' => 0,'msg' => '添加失败！'];
            }
            $db->commit();
            return ['code' => 1,'msg' => '添加成功！'];
        }
        return ['code' => 0,'msg' => '添加失败！'];
    }
    /**
     * 转移目录
     */
    public function change2Category(){
        // $res = $this->_change2Category();
        return $res;
    }
}
