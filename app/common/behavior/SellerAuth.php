<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 9:57
 */

namespace app\common\behavior;

use app\common\traits\F;
use mercury\constants\Code;
use mercury\constants\State;
use mercury\constants\Cache;
use mercury\ResponseException;

/**
 * Class SellerAuth
 * @package app\common\behavior
 *
 * 商家认证
 */
class SellerAuth
{
    public function run(&$params)
    {
        /**
         * 判断用户是否有登陆
         */
        if (!session('user')) {
            #   是否为Ajax请求
            if (request()->isAjax()) {
                try {
                    throw new ResponseException(Code::CODE_UNAUTHORIZED);
                } catch (ResponseException $e) {
                    exit(json_encode($e->getData()));
                }
            } else {
                //需要登陆的控制器
                F::gotoUrl(F::domain('www', '/user/login'));
            }
        }
        /**
         * 判断用户是否已开店
         */
        if (!session('user.user_shop_id')) {
            F::gotoUrl(F::domain('settled'));
        } else {
            $shop   = db('shop')->where(['shop_id' => session('user.user_shop_id')])->find();
            if($shop['shop_state'] == 5 && request()->controller() != 'Shopupdate' && request()->controller() != 'Prompt2'){
                F::gotoUrl(F::domain('seller','/prompt2'));
            }else{
                if ($shop['shop_state'] != State::STATE_NORMAL && request()->controller() != 'Prompt' && $shop['shop_state'] != 5) {
                    F::gotoUrl(F::domain('seller','/prompt'));
                }
            }
            session('shop', $shop);
        }
    }
}