<?php
namespace lbzy\sdk\erp;
use app\common\traits\F as Fun;
use app\common\traits\F;
use mercury\constants\Cache;
use mercury\factory\Factory;

/**
 * erp授权登录
 */

class ErpOauth extends Erp
{
	public $callbackUrl;

	/**
     * 创建授权token信息
     */
    public function oauthToken()
    {
    	$data['sesid'] 			= session_id();
    	$data['callbackUrl'] 	= $this->callbackUrl;
        $res = $this->api('/mall.v1.oauth/createToken', $data);
        F::writeLogByMongoDb('test',['status'=>2,'type'=>'loginOauth','data'=>$data,'res'=>$res,'time'=>date('Y-m-d H:i:s')]);
        return $res;
    }

    /**
     * 返回授权地址
     */
    public function loginOauthUrl($url)
    {
        F::writeLogByMongoDb('test',['status'=>1,'type'=>'loginOauth','callbackUrl'=>$url,'time'=>date('Y-m-d H:i:s')]);
        $this->callbackUrl = $url;
    	$res = $this->oauthToken();
        // 记录授权记录
        Fun::redis()->setex(Fun::getCacheName(Cache::ERP_API_OAUTH . session_id()), 300, session_id() );
    	//return $res['code'] == 1 ? $res['data']['url'] : '';
        return $res['code'] == 1 ? $res['data']['url'] : (is_array($res) ? '?' . json_encode($res) : '?' . $res);
    }

    /**
     * 获取用户资料
     */
    public function userInfo()
    {
        # 是否有授权记录
        $cache = Fun::redis()->get( Fun::getCacheName(Cache::ERP_API_OAUTH . session_id()) );
        F::writeLogByMongoDb('test',['status'=>3,'type'=>'loginOauth','cache'=>$cache,'time'=>date('Y-m-d H:i:s')]);
        if ( empty($cache) ) {
            return [];
        } else {
            Fun::redis()->del( Fun::getCacheName(Cache::ERP_API_OAUTH . session_id()) );
            $res = $this->oauthToken();
            $data['oauthToken'] = $res['code'] == 1 ? $res['data']['token'] : '';
            $res = $this->api('/mall.v1.oauth/getUser',$data);
            F::writeLogByMongoDb('test',['status'=>4,'type'=>'loginOauth','data'=>$data,'res'=>$res,'time'=>date('Y-m-d H:i:s')]);
            return $res['code'] == 1 ? $res['data'] : [];
        }
    }

    /**
     * 授权登陆成功检测
     */
    public function oauthCheck($url)
    {
        $this->callbackUrl = $url;
        $user   = $this->userInfo();
        // dump($user);exit;
        if ( $user ) {
            # 绑定用户
            session('erpUser', $user);
            # erp 登录
            $res = Factory::instance('/user/v1/login/erp')->run();
            F::writeLogByMongoDb('test',['status'=>5,'type'=>'loginOauth','res'=>$res,'time'=>date('Y-m-d H:i:s')]);
            // dump($res);exit;
            return $res['code'] == 20000;
        }
        F::writeLogByMongoDb('test',['status'=>6,'type'=>'loginOauth','callbackUrl'=>$url,'time'=>date('Y-m-d H:i:s')]);
        return false;
    }

    /**
     * 获取注册链接
     */
    public function registerUrl($module)
    {
        $param['url']           = Fun::domain($module);
        $share_code = cookie('SHARE_CODE');
        if ($share_code) {
            $param['promo_code']    =  $share_code;
        }
        $erpConfig = new ErpConfig;
        return $erpConfig->get('registerUrl') . '?' . http_build_query($param);
    }

    /**
     * 校验安全密码
     *
     * @param array $params
     *              $openid
     *              $password
     * @return bool
     */
    public function checkPayPassword(array $params)
    {
        $ret    = $this->api('/mall.v1.user/checkSafePsw', $params);
        if ($ret['code'] == 1) return true;
        return $ret['msg'];
    }

}