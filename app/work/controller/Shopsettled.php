<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Shopsettled extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 592;  //表单模板ID
        $this->module_name  = '招商入驻';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_index();
        $this->assign('res',$res);
        $btns   = 'return \' \'.(($val[\'shop_settled_id\']!=0) ? \'<div class="btn red btn-outline btn-block" onclick="extra_tr_view($(this))">审核</div>\':\'\');';
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
     * 更新用户信息
     * @return array
     */
    public function setStatus3(){
        $data = input('post.');
        if(count($data['shop_settled_id']) != 1){
            return ['code'=>0,'msg'=>'每次只能更新一条记录！'];
        }
        $shop_settled_id = implode(',',$data['shop_settled_id']);
        $shop_settled_id = intval($shop_settled_id);
        //将用户信息更新到店铺审核表
        $user_id = db('shop_settled')->cache(true)->where(['shop_settled_id'=>$shop_settled_id])->value('user_id');
        if(!$user_id){
            return ['code'=>0,'msg'=>'更新错误！'];
        }
        $openid = db('user')->cache(true)->where(['user_id'=>$user_id])->value('openid');
        if($openid){
            $erp = new \lbzy\sdk\erp\Erp();
            $new_user = $erp->api('/pc.v1.user.user/getUser',['openid'=>$openid,'get_more'=>1]);
            if($new_user['code'] != 1){
                return ['code'=>0,'msg'=>'更新错误！'];
            }
            $detail['is_enterprise'] = $new_user['data']['is_enterprise'];
            if($new_user['data']['is_enterprise'] == 1){
                $detail['licence_Photo'] = $new_user['data']['info']['licence_Photo'];
                $detail['name'] = $new_user['data']['info']['enterprise_name'];
                $detail['idcard_f_photo'] = $new_user['data']['info']['corporation_f_code'];
                $detail['idcard_b_photo'] = $new_user['data']['info']['corporation_b_code'];
                $detail['idcard_h_photo'] = $new_user['data']['info']['corporation_h_code'];
            }else{
                $detail['licence_Photo'] = '';
                $detail['name'] = $new_user['data']['info']['realname'];
                $detail['idcard_f_photo'] = $new_user['data']['info']['idcard_f_photo'];
                $detail['idcard_b_photo'] = $new_user['data']['info']['idcard_b_photo'];
                $detail['idcard_h_photo'] = $new_user['data']['info']['idcard_h_photo'];
            }
            $res = db('shop_settled')->where(['shop_settled_id'=>$shop_settled_id])->update($detail);
            if(!$res){
                return ['code'=>0,'msg'=>'更新失败！'];
            }
            return ['code'=>1,'msg'=>'更新成功！'];
        }else{
            return ['code'=>0,'msg'=>'更新失败！'];
        }
    }

    /**
     * 修改
     */
    public function edit(){
        $res = $this->_edit();
        $res['shop_settled_content'] = unserialize($res['shop_settled_content']);
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
