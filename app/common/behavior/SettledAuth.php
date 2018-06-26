<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 11:38
 * 开店认证
 */

namespace app\common\behavior;
use app\common\traits\F;
use mercury\factory\Factory;
use mercury\constants\State;
use mercury\constants\Cache;

class SettledAuth
{
    public function run(&$params)
    {
        if(!in_array(request()->controller(),array('Checksettled','Index'))){
            /**
             * 判断用户是否有登陆
             */
            if (!session('user')) {
                F::gotoUrl(F::domain('www', '/user/login'));
            }

            /**
             * 更新用户店铺信息
             */
            if(session('user.user_id') > State::STATE_DISABLED){
                $userinfo= db('user')->where(['user_id' => session('user.user_id')])->field('user_shop_id')->find();
                session('user.user_shop_id',$userinfo['user_shop_id']);
            }
            /**
             * 实时检测用户是否有资格开店
             */
            if(!in_array(request()->controller(),array('Checksettled'))){
                if(request()->controller() == 'Choice'){
                    $erp = new \lbzy\sdk\erp\Erp();
                    $new_user = $erp->api('/pc.v1.user.user/getUser',['openid'=>session('user.openid')]);
                    $erpUser = session('user.erpUser');
                    $erpUser['level_id'] = $new_user['data']['level_id'];
                    $erpUser['is_valid_credentials'] = $new_user['data']['is_valid_credentials'];
                    $erpUser['is_enterprise'] = $new_user['data']['is_enterprise'];
                    session('user.erpUser',$erpUser);
                }
                $erpUser = session('user.erpUser');
                if($erpUser['level_id'] < 1 || $erpUser['is_valid_credentials'] != State::STATE_NORMAL){
                    F::gotoUrl('/checksettled');
                }
            }

            /**
             * 验证店铺当前情况
             */
            $shop = db('shop_settled')->where(['user_id' => session('user.user_id')])->find();
            if($shop){
                $erpUser = session('user.erpUser');
                if (is_string($shop['shop_settled_content'])) $shop['shop_settled_content'] = unserialize($shop['shop_settled_content']);

                if($shop['shop_settled_state'] == State::STATE_NORMAL){

                    if(!db('shop')->where(['shop_state'=>State::STATE_NORMAL,'shop_id'=>session('user.user_shop_id')])->field('shop_id')->find()){

                        F::gotoUrl(F::domain('seller','/prompt'));

                    }

                    F::gotoUrl(F::domain('seller','/'));

                }elseif((($shop['shop_settled_state'] == State::STATE_DISABLED || $shop['shop_settled_state'] == 2) && request()->controller() != 'Checksettled') && request()->action() == 'index2'){

                    F::gotoUrl('/checksettled/process');

                }elseif($shop['shop_settled_state'] == 3 || $shop['shop_settled_state'] == 2){

                    if(request()->controller() == 'Index') F::gotoUrl('/checksettled/process');

                    if(isset($shop['shop_settled_content']['step_shop_type']) && request()->action() != 'choiceshoptype'){

                        $where = 'shop_type_state = 1 AND shop_type_id = '.$shop['shop_settled_content']['step_shop_type'].' AND (shop_user_type= '.$erpUser['is_enterprise'].' OR shop_user_type = 2) AND is_user_level <= '.$erpUser['level_id'];

                        $shop_type = db('shop_type')->where($where)->find();

                        if (!$shop_type) F::gotoUrl('/choice/choiceshoptype');
                    }
                }
                session('shop_settled',$shop);
            }
        }
    }
}