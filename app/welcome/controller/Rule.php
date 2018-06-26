<?php
namespace app\welcome\controller;
use think\Controller;
use mercury\factory\Factory;
use app\common\traits\F as Fun;

class Rule extends Controller
{

	public function _initialize()
	{
		// echo \app\work\controller\Config::get('site.icp');
		$this->assign('cList',Factory::instance('/article/v1/ArticleCategory/index')->run(['sid' => 3])['data'] ?? []);
		$this->assign('homePage',Fun::domain('www'));
	}

	function index()
	{
		$map = [
			'sid' 		=> 3,
			'cid' 		=> input('cid',0, 'int'),
			'p' 		=> input('p',1, 'int'),
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