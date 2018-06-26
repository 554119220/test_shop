<?php
namespace app\news\controller;
use think\Controller;
use mercury\factory\Factory;
use app\common\traits\F as Fun;
use app\common\traits\F;
use mercury\constants\Cache;

class Index extends Controller
{

	public function _initialize()
	{
		/**
         * 获取配置
         */
        $cfg   = F::redis()->get(F::getCacheName(Cache::SHOP_CONFIG_LIST));
        if(!$cfg){
            $list = db('config_category')->where(['status' => State::STATE_NORMAL,'upid' => ['gt',State::STATE_DISABLED]])->field('group_name,config')->select();
            $cfg = [];
            foreach($list as $key => $val){
                if($val['config']){
                    $val['config'] = unserialize(html_entity_decode($val['config']));
                }
                $cfg[$val['group_name']] = $val['config'];
            }
            F::redis()->set(F::getCacheName(Cache::SHOP_CONFIG_LIST),serialize($cfg));
        }
        if (is_string($cfg)) $cfg = unserialize($cfg);
        $sellerUrl = ['loginurl'=>F::domain('www','/user/login'),'logouturl'=>F::domain('www','/user/logout'),'wwwurl'=>F::domain('www','/')];
        $cfg['url'] = $sellerUrl;
        config('cfg',$cfg);
		// echo \app\work\controller\Config::get('site.icp');
		$this->assign('cList',Factory::instance('/article/v1/ArticleCategory/index')->run(['sid' => 1])['data'] ?? []);
		$this->assign('homePage',Fun::domain('www'));
	}

	function index()
	{
		$map = [
			'sid' 		=> 1,
			'cid' 		=> input('cid',0, 'int'),
			'page' 		=> input('p',1, 'int'),
			'pagesize' 	=> 15,
		];
		$list = Factory::instance('/article/v1/article/index')->run($map);
		// dump($list);
		// dump(Fun::pageTemplate($list));exit;
		return view('',[
			'list' => $list['data'] ?? [],
			'page' => Fun::pageTemplate($list),
		]);
	}

	function detail()
	{
		
		$detail = Factory::instance('/article/v1/article/detail')->run()['data'] ?? [];
		// dump($detail);exit;
		return view('',[
			'detail' => $detail,
		]);
	}









}