<?php
/**
 * 算法参数设置
 * create by Lazycat
 * 2017-08-23
 */
namespace app\api\controller\work\v1;
use app\api\controller\work\v1\Init;
use enhong\Status;
use think\Db;
use think\Exception;
use think\Validate;

class Suanfa extends Init
{
    /**
     * name:参数设置
     * api:work.v1.suanfa/configSave
     * day:2017-08-23
     * author:lazycat
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <openid>     <string>        <1>     <雇员openid>
     * <post>       <stirng>        <1>     <post序列化过来的数据>
     * <cfg_field>  <string>        <1>     <要设置的字段>
     */
    public function configSave($check=1){
        $this->attr = ['U'];
        if($check == 1) {
            $res = $this->check('openid,post,cfg_field');
            if($res['code'] != 1) return $this->ret($res);
        }
        $post = unserialize(html_entity_decode($this->post['post']));

        switch($this->post['cfg_field']){
            case 'base':
                $rules = [
                    'score_back_ratio'              =>  'require|float|gt:0.0001|lt:0.0015',
                    'lurpak_to_cash_ratio'          =>  'require|float|gt:0.8|lt:3.5',
                    'min_score_to_back'             =>  'require|integer|gt:10',
                    'into_lurpak_ratio'             =>  'require|float|egt:0|lt:1',
                    'into_consume_ratio'            =>  'require|float|egt:0|lt:1',
                    'into_devote_lurpak_ratio'      =>  'require|float|egt:0|lt:1',
                    'into_project_mortgage_ratio'   =>  'require|float|egt:0|lt:1',
                    'into_mortgage_poundage_ratio'  =>  'require|float|egt:0|lt:1',
                    'upgrade_reward_after_day'      =>  'require|float|between:0,14',
                    'cash_to_inventory_ratio'       =>  'require|float|gt:5|lt:12',
                ];

                $message = [
                    'score_back_ratio.require'              =>  '每天积分返比例，按千分之一返还必填',
                    'lurpak_to_cash_ratio.require'          =>  '每天积分兑换云积分比例必填',
                    'min_score_to_back.require'             =>  '最低100分起计算返还必填',
                    'into_lurpak_ratio.require'             =>  '每天转换的云积分放云积分账户比例必填',
                    'into_consume_ratio.require'            =>  '每天转换的云积分放入消费金账户比例必填',
                    'into_devote_lurpak_ratio.require'      =>  '每天转换的云积分放入拉升云积分账户比例必填',
                    'into_project_mortgage_ratio.require'   =>  '每天转换的云积分放入大项目储备金账户比例必填',
                    'into_mortgage_poundage_ratio.require'  =>  '每天转换的云积分放入手续费抵拥金账户比例必填',
                    'upgrade_reward_after_day.require'      =>  '用户升级后上级发放奖励时间',
                    'cash_to_inventory_ratio.require'       =>  '购买库存积分赠送倍数必填',
                ];

                $valide = new Validate($rules,$message);
                $result = $valide->check($post);
                if(!$result) return $this->ret(['code' => 0,'msg' => $valide->getError()]);

                $data = [
                    'score_back_ratio'              =>  $post['score_back_ratio'],
                    'lurpak_to_cash_ratio'          =>  $post['lurpak_to_cash_ratio'],
                    'min_score_to_back'             =>  $post['min_score_to_back'],
                    'into_lurpak_ratio'             =>  $post['into_lurpak_ratio'],
                    'into_consume_ratio'            =>  $post['into_consume_ratio'],
                    'into_devote_lurpak_ratio'      =>  $post['into_devote_lurpak_ratio'],
                    'into_project_mortgage_ratio'   =>  $post['into_project_mortgage_ratio'],
                    'into_mortgage_poundage_ratio'  =>  $post['into_mortgage_poundage_ratio'],
                    'upgrade_reward_after_day'      =>  $post['upgrade_reward_after_day'],
                    'cash_to_inventory_ratio'       =>  $post['cash_to_inventory_ratio'],
                ];

                if($data['into_lurpak_ratio'] + $data['into_consume_ratio'] + $data['into_devote_lurpak_ratio'] + $data['into_project_mortgage_ratio'] + $data['into_mortgage_poundage_ratio'] != 1) return $this->ret(['code' => 0,'msg' =>'每返回数据存入各账户比例和必须等于1（即100%）']);

                if(!$this->sw[] = db('config_suanfa')->where(['id' => 1])->update([$this->post['cfg_field'] => serialize($data)])){
                    return $this->ret(['code' => 0,'msg' => '保存参数失败！']);
                }

                cache('suanfa_'.$this->post['cfg_field'],$data);
            break;

            //=============================================================
            case 'inventory':
                $rules = [
                    'dec_inventory_score_ratio'     =>  'require',
                    'distribute_poundage_ratio'     =>  'require|float|egt:0|elt:0.5',
                ];

                $message = [
                    'dec_inventory_score_ratio.require'     =>  '分发返还系数必填',
                    'distribute_poundage_ratio.require'     =>  '分发返还系数必填',
                ];

                $valide = new Validate($rules,$message);
                $result = $valide->check($post);
                if(!$result) return $this->ret(['code' => 0,'msg' => $valide->getError()]);

                $tmp = explode(',',$post['dec_inventory_score_ratio']);
                foreach ($tmp as $key => $val){
                    $val = (float) $val;
                    if($val <= 0 || $val > 4) return $this->ret(['code' => 0,'msg' => '分发返还系数格式错误或参数数值范围错误！']);
                    $tmp[$key] = $val;
                }
                $post['dec_inventory_score_ratio']   = implode(',',$tmp);

                $data = [
                    'dec_inventory_score_ratio'     =>  $post['dec_inventory_score_ratio'],
                    'distribute_poundage_ratio'     =>  $post['distribute_poundage_ratio'],
                ];

                if(!$this->sw[] = db('config_suanfa')->where(['id' => 1])->update([$this->post['cfg_field'] => serialize($data)])){
                    return $this->ret(['code' => 0,'msg' => '保存参数失败！']);
                }

                cache('suanfa_'.$this->post['cfg_field'],$data);
                break;

            //=============================================================
            case 'consume':
                $rules = [
                    'reward_score_ratio'            =>  'require|float|gt:0.8|lt:2',
                    'reward_score_ratio_layer_1'    =>  'require|float|gt:0|lt:0.2',
                    'reward_score_ratio_layer_2'    =>  'require|float|gt:0|lt:0.8',
                    'reward_score_ratio_layer_3'    =>  'require|float|gt:0|lt:0.8',
                    'reward_score_ratio_layer_4'    =>  'require|float|gt:0|lt:0.8',
                ];

                $message = [
                    'reward_score_ratio.require'            =>  '用户获得100%积分奖励必填',
                    'reward_score_ratio_layer_1.require'    =>  '一级推荐人获的10%积分奖励必填',
                    'reward_score_ratio_layer_2.require'    =>  '二级推荐人获得一级推荐人特别奖励50%必填',
                    'reward_score_ratio_layer_3.require'    =>  '三级推荐人获得二级推荐人特别奖励50%必填',
                    'reward_score_ratio_layer_4.require'    =>  '四级推荐人获得三级推荐人特别奖励50%',
                ];

                $valide = new Validate($rules,$message);
                $result = $valide->check($post);
                if(!$result) return $this->ret(['code' => 0,'msg' => $valide->getError()]);

                $data = [
                    'reward_score_ratio'            =>  $post['reward_score_ratio'],
                    'reward_score_ratio_layer_1'    =>  $post['reward_score_ratio_layer_1'],
                    'reward_score_ratio_layer_2'    =>  $post['reward_score_ratio_layer_2'],
                    'reward_score_ratio_layer_3'    =>  $post['reward_score_ratio_layer_3'],
                    'reward_score_ratio_layer_4'    =>  $post['reward_score_ratio_layer_4'],
                ];

                if(!$this->sw[] = db('config_suanfa')->where(['id' => 1])->update([$this->post['cfg_field'] => serialize($data)])){
                    return $this->ret(['code' => 0,'msg' => '保存参数失败！']);
                }

                cache('suanfa_'.$this->post['cfg_field'],$data);
                break;

            //=============================================================
            case 'sale':
                $rules = [
                    'reward_score_ratio_layer_1'    =>  'require|float|gt:0|lt:0.2',
                    'reward_score_ratio_layer_2'    =>  'require|float|gt:0|lt:0.8',
                    'reward_score_ratio_layer_3'    =>  'require|float|gt:0|lt:0.8',
                    'reward_score_ratio_layer_4'    =>  'require|float|gt:0|lt:0.8',
                ];

                $message = [
                    'reward_score_ratio_layer_1.require'    =>  '一级推荐人获得“商家扣除库存积分”的5%为积分奖励必填',
                    'reward_score_ratio_layer_2.require'    =>  '二级推荐人获得一级推荐人特别奖励50%必填',
                    'reward_score_ratio_layer_3.require'    =>  '三级推荐人获得二级推荐人特别奖励50%必填',
                    'reward_score_ratio_layer_4.require'    =>  '四级推荐人获得三级推荐人特别奖励50%',
                ];

                $valide = new Validate($rules,$message);
                $result = $valide->check($post);
                if(!$result) return $this->ret(['code' => 0,'msg' => $valide->getError()]);

                $data = [
                    'dec_inventor_score_ratio'      =>  '0.25,0.5,1,2,4',
                    'reward_score_ratio_layer_1'    =>  $post['reward_score_ratio_layer_1'],
                    'reward_score_ratio_layer_2'    =>  $post['reward_score_ratio_layer_2'],
                    'reward_score_ratio_layer_3'    =>  $post['reward_score_ratio_layer_3'],
                    'reward_score_ratio_layer_4'    =>  $post['reward_score_ratio_layer_4'],
                ];

                if(!$this->sw[] = db('config_suanfa')->where(['id' => 1])->update([$this->post['cfg_field'] => serialize($data)])){
                    return $this->ret(['code' => 0,'msg' => '保存参数失败！']);
                }

                cache('suanfa_'.$this->post['cfg_field'],$data);
                break;

            //=============================================================
            case 'upgrade_level_2':
            case 'upgrade_level_3':
                $rules = [
                    'reward_lurpak_ratio_layer_1'   =>  'require|float|gt:0|lt:0.2',
                    'reward_lurpak_ratio_layer_2'   =>  'require|float|gt:0|lt:0.2',
                ];

                $message = [
                    'reward_lurpak_ratio_layer_1.require'   =>  '云积分奖励，升级的那个用户开户费的12%奖励并转成云积分',
                    'reward_lurpak_ratio_layer_2.require'   =>  '云积分奖励，升级的那个用户开户费的8%奖励并转成云积分',
                ];

                $valide = new Validate($rules,$message);
                $result = $valide->check($post);
                if(!$result) return $this->ret(['code' => 0,'msg' => $valide->getError()]);

                $data = [
                    'reward_lurpak_ratio_layer_1'   =>  $post['reward_lurpak_ratio_layer_1'],
                    'reward_lurpak_ratio_layer_2'   =>  $post['reward_lurpak_ratio_layer_2'],
                ];

                if(!$this->sw[] = db('config_suanfa')->where(['id' => 1])->update([$this->post['cfg_field'] => serialize($data)])){
                    return $this->ret(['code' => 0,'msg' => '保存参数失败！']);
                }

                cache('suanfa_'.$this->post['cfg_field'],$data);
                break;

            //=============================================================
            case 'upgrade_level_4':
            case 'upgrade_level_5':
            case 'upgrade_level_6':
                $rules = [
                    'team_reward_score_ratio'       =>  'require|float|gt:0.0005|lt:0.006',
                    'reward_lurpak_ratio_layer_1'   =>  'require|float|gt:0|lt:0.8',
                    'lurpak_back_ratio_layer_1'     =>  'require|float|gt:0|lt:0.05',
                ];

                $message = [
                    'team_reward_score_ratio.require'       =>  '团队奖励必填',
                    'reward_lurpak_ratio_layer_1.require'   =>  '云积分奖励，升级的那个用户开户费的60%奖励并转成云积分放入推荐云积分账户必填',
                    'lurpak_back_ratio_layer_1.require'     =>  '推荐云积分账户按1%返还至云积分账户必填',
                ];

                $valide = new Validate($rules,$message);
                $result = $valide->check($post);
                if(!$result) return $this->ret(['code' => 0,'msg' => $valide->getError()]);

                $data = [
                    'team_reward_score_ratio'       =>  $post['team_reward_score_ratio'],
                    'reward_lurpak_ratio_layer_1'   =>  $post['reward_lurpak_ratio_layer_1'],
                    'lurpak_back_ratio_layer_1'     =>  $post['lurpak_back_ratio_layer_1'],
                ];

                if(!$this->sw[] = db('config_suanfa')->where(['id' => 1])->update([$this->post['cfg_field'] => serialize($data)])){
                    return $this->ret(['code' => 0,'msg' => '保存参数失败！']);
                }

                cache('suanfa_'.$this->post['cfg_field'],$data);
                break;
            //=============================================================
            case 'agent_base':
                $rules = [
                    'reward_score_ratio'            =>  'require|float|gt:0|elt:1',
                    'reward_score_ratio_layer_1'    =>  'require|float|gt:0|lt:3',
                    'company_reward_score_ratio'    =>  'require|float|gt:0|elt:1',
                    'not_load_allot_ratio'          =>  'require|float|gt:0|lt:0.8',
                ];

                $message = [
                    'reward_score_ratio.require'            =>  '购买代理用户获得积分奖励系数必填',
                    'reward_score_ratio_layer_1.require'    =>  '上一级推荐人获得积分奖励系数必填',
                    'company_reward_score_ratio.require'    =>  '代理公司获得积分奖励系数必填',
                    'not_load_allot_ratio.require'          =>  '代理公司未落地前划分部分统一管理奖发放给代理必填',
                ];

                $valide = new Validate($rules,$message);
                $result = $valide->check($post);
                if(!$result) return $this->ret(['code' => 0,'msg' => $valide->getError()]);

                $data = [
                    'reward_score_ratio'            =>  $post['reward_score_ratio'],
                    'reward_score_ratio_layer_1'    =>  $post['reward_score_ratio_layer_1'],
                    'company_reward_score_ratio'    =>  $post['company_reward_score_ratio'],
                    'not_load_allot_ratio'          =>  $post['not_load_allot_ratio'],
                ];

                if(!$this->sw[] = db('config_suanfa')->where(['id' => 1])->update([$this->post['cfg_field'] => serialize($data)])){
                    return $this->ret(['code' => 0,'msg' => '保存参数失败！']);
                }

                cache('suanfa_'.$this->post['cfg_field'],$data);
                break;

            default:
                return $this->ret(['code' => 0,'msg' => '错误的参数设置！']);
        }

        return $this->ret(['code' => 1,'msg' => '保存参数成功！']);
    }

    /**
     * name:获取算法参数
     * api:work.v1.suanfa/getConfig
     * day:2017-08-23
     * author:lazycat
     * -----------------------------------
     * 固定格式用于导入生成接口文档
     * -----------------------------------
     * <参数><类型><是否必须><描述><例子>
     * -----------------------------------
     * <param start>
     * -----------------------------------
     * <openid>     <string>        <1>     <雇员openid>
     */
    public function getConfig($check=1){
        if($check == 1) {
            $res = $this->check('openid');
            if($res['code'] != 1) return $this->ret($res);
        }

        $rs = db('config_suanfa')->where(['id' => 1])->field('id,create_time,update_time,is_lock',true)->find();
        if($rs) {
            foreach ($rs as $key => $val) {
                if ($val) {
                    $rs[$key] = unserialize(html_entity_decode($val));
                }
            }

            return $this->ret(['code' => 1,'data' => $rs]);
        }

        return $this->ret(['code' =>3]);


    }
}
