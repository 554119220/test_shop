<?php
namespace lbzy\sdk\erp;
use app\common\traits\F as Fun;

class ErpConfig
{
	static function get($key = null)
	{
	    $con = [
	        'default'=>[
                'apiUrl' 		=> 'http://api.lwjz.com',
                'oauthUrl' 		=> 'http://oauth.lwjz.com',
                'registerUrl'	=> 'http://www.lwjz.com/register.html',
                'sign' 			=> [
                    'appid'         => 29,
                    'access_key'	=> '4fee70578b834e423f61b92008ef9ff7',
                    'secret_key'    => 'f87f70f953badaddf6da750e7d0f8f38',
                    'sign_code'     => '59e41514cb9c299c80305533bafeef4b',
                    'device_id' 	=> session_id(),
                    'name' => '商城',
                    'commercial_tenant_id' => 2000,
                ],
            ],
            'test_domain_root'=>[
                'apiUrl' 		=> 'http://api.lwjz.com',
                'oauthUrl' 		=> 'http://oauth.zr.com',
                'registerUrl'	=> 'http://www.lwjz.com/register.html',
                'sign' 			=> [
                    'appid'         => 29,
                    'access_key'	=> '4fee70578b834e423f61b92008ef9ff7',
                    'secret_key'    => 'f87f70f953badaddf6da750e7d0f8f38',
                    'sign_code'     => '59e41514cb9c299c80305533bafeef4b',
                    'device_id' 	=> session_id(),
                    'name' => '商城',
                    'commercial_tenant_id' => 2000,
                ],
            ],
            'online_domain_root'=>[
                'apiUrl' 		=> 'https://jcode.zrst.cn',
                'oauthUrl' 		=> 'https://oauth.zrst.cn',
                'registerUrl'	=> 'https://user.zrst.cn/register',
                'sign' 			=> [
                    'appid'         => 29,
                    'access_key'	=> '4fee70578b834e423f61b92008ef9ff7',
                    'secret_key'    => 'f87f70f953badaddf6da750e7d0f8f38',
                    'sign_code'     => '59e41514cb9c299c80305533bafeef4b',
                    'name' => '商城',
                    'commercial_tenant_id' => 2000,
                    'device_id' 	=> session_id(),
                ],
            ],
        ];

		# 获取域名对应配置
		$root = config('url_domain_root');
		if($root == config('online_domain_root')){
            $tmp = $con['online_domain_root'];
        }else if($root == config('test_domain_root')){
            $tmp = $con['test_domain_root'];
        }else{
            $tmp = $con['default'];
        }
        return is_null($key) ? $tmp : ($tmp[$key] ?? null) ;
	}









}