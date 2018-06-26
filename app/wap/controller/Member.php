<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/14 0014
 * Time: 14:17
 */

namespace app\wap\controller;
use mercury\auth\api\AuthApi;
use mercury\factory\Factory;

/**
 * Class Member
 * @package app\wap\controller
 *
 * 会员中心
 */
class Member
{




	/**
	 * 用户首页
	 * @return [type] [description]
	 */
	function index()
	{
		// dump(session('user'));
        $header['orders_total'] = AuthApi::getInstance('/orders/v1/buyerOrders/total')->createHeaders();
		return view('', ['headers' => $header]);
	}

	/**
	 * 我的账户
	 * @return [type] [description]
	 */
	function account()
	{
        $data   = Factory::instance('/user/v1/account/index')->run();
		return view('', ['data' => $data]);
	}

	/**
	 * 账户明细
	 * @return [type] [description]
	 */
	function account_detail()
	{
		return view();
	}

	/**
	 * 设置
	 */
	function set()
	{
		return view();
	}

    /**
     * 退款/售后
     *
     * @return \think\response\View
     */
    public function refund()
    {
        return view();
	}

	/**
     * 用户信息
     * @return [type] [description]
     */
    function detail()
    {
    	// print_r(session('user'));
        return view();
    }

    /**
     * 安全设置
     * @return [type] [description]
     */
    function safe()
    {
        return view();
    }

    /**
     * 修改登录密码
     *
     * @return \think\response\View
     */
    public function update_password()
    {
        return view('',[
        	'headers0' =>  AuthApi::getInstance('/user/v1/Setting/update_password')->createHeaders(),
        	'headers1' =>  AuthApi::getInstance('/tools/v1/NoticeTpl/code')->createHeaders(),
        ]);
    }

    /**
     * 设置安全密码
     *
     * @return \think\response\View
     */
    public function set_pay_password()
    {
        return view('',[
        	'headers0' =>  AuthApi::getInstance('/user/v1/Setting/set_pay_password')->createHeaders(),
        	'headers1' =>  AuthApi::getInstance('/tools/v1/NoticeTpl/code')->createHeaders(),
        ]);
    }

    /**
     * 修改安全密码
     *
     * @return \think\response\View
     */
    public function update_pay_password()
    {
        return view('',[
        	'headers0' =>  AuthApi::getInstance('/user/v1/Setting/update_pay_password')->createHeaders(),
        	'headers1' =>  AuthApi::getInstance('/tools/v1/NoticeTpl/code')->createHeaders(),
        ]);
    }

    /**
     * 忘记密码
     *
     * @return \think\response\View
     */
    public function forgot_password()
    {
        return view('',[
        	'headers0' =>  AuthApi::getInstance('/user/v1/Setting/forgot_password')->createHeaders(),
        	'headers1' =>  AuthApi::getInstance('/tools/v1/NoticeTpl/code')->createHeaders(),
        ]);
    }

    /**
     * 忘记安全密码
     *
     * @return \think\response\View
     */
    public function forgot_pay_password()
    {
        return view('',[
        	'headers0' =>  AuthApi::getInstance('/user/v1/Setting/forgot_pay_password')->createHeaders(),
        	'headers1' =>  AuthApi::getInstance('/tools/v1/NoticeTpl/code')->createHeaders(),
        ]);
    }

    /**
     * 编辑资料
     *
     * @return \think\response\View
     */
    public function update_user()
    {
    	// print_r(session('user'));
        return view('',[
        	'headers0' =>  AuthApi::getInstance('/user/v1/Setting/update_user')->createHeaders(),
        ]);
    }

    /**
     * 个人扩展信息设置
     *
     * @return \think\response\View
     */
    public function extend()
    {
        // print_r(\app\common\traits\F::json((Factory::instance('/user/v1/Userextend/detail')->run())));exit;
        return view('',[
            'headers0'  => AuthApi::getInstance('/user/v1/UserExtend/update')->createHeaders(),
            'headers1'  => AuthApi::getInstance('/user/v1/UserExtend/loveCategory')->createHeaders(),
            'detail'    => Factory::instance('/user/v1/UserExtend/detail')->run()['data'] ?? [],
            'userSex'   => \mercury\constants\State::USER_EXTEND_SEX_ARRAYS,
            'userType'  => \mercury\constants\State::USER_EXTEND_TYPE_ARRAYS,
            'categorys' => Factory::instance('/goods/v1/GoodsCategory/index')->run()['data'] ?? [],
        ]);
    }

    /**
     * 个人宝贝信息设置
     *
     * @return \think\response\View
     */
    public function baby()
    {
        // print_r(\app\common\traits\F::json((Factory::instance('/user/v1/UserBaby/index')->run())));exit;
        return view('',[
            'list'      => Factory::instance('/user/v1/UserBaby/index')->run()['data'] ?? [],
        ]);
    }

    /**
     * 个人宝贝信息设置
     *
     * @return \think\response\View
     */
    public function baby_create()
    {
        // print_r((Factory::instance('/user/v1/Userextend/detail')->run()));exit;
        return view('',[
            'userSex'   => \mercury\constants\State::USER_BABY_SEX_ARRAYS,
            'headers0'  => AuthApi::getInstance('/user/v1/UserBaby/create')->createHeaders(),
        ]);
    }

    /**
     * 个人宝贝信息设置
     *
     * @return \think\response\View
     */
    public function baby_update()
    {
        // print_r((Factory::instance('/user/v1/Userextend/detail')->run()));exit;
        return view('',[
            'detail'    => Factory::instance('/user/v1/UserBaby/detail')->run()['data'] ?? [],
            'userSex'   => \mercury\constants\State::USER_BABY_SEX_ARRAYS,
            'headers0'  => AuthApi::getInstance('/user/v1/UserBaby/update')->createHeaders(),
        ]);
    }

    /**
     * 我的收藏
     *
     * @return \think\response\View
     */
    public function attention()
    {
        $header['goods']    = AuthApi::getInstance('/goods/v1/attentionGoods/index')->createHeaders();
        $header['shop']     = AuthApi::getInstance('/goods/v1/attentionShop/index')->createHeaders();
        $header['delete_goods'] = AuthApi::getInstance('/goods/v1/attentionGoods/delete')->createHeaders();
        $header['delete_shop']  = AuthApi::getInstance('/goods/v1/attentionShop/delete')->createHeaders();
        return view('', ['headers' => $header]);
    }

    /**
     * 我的优惠券
     *
     * @return \think\response\View
     */
    public function coupon()
    {
        return view();
    }

}