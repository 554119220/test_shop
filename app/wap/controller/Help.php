<?php
namespace app\wap\controller;
use mercury\auth\api\AuthApi;
use mercury\factory\Factory;
use app\common\traits\F as Fun;

/**
 * 帮助中心
 */

class Help
{




	/**
	 * 帮助首页
	 * @return [type] [description]
	 */
	function index()
	{
        // dump(Fun::cacheConfig());exit;
		return view('',[
            'categoryList'  => Factory::instance('/article/v1/ArticleCategory/index')->run(['sid' => 2])['data'] ?? [],
            'headers'       => [
                'headers0'      => AuthApi::getInstance('/article/v1/Article/index')->createHeaders(),
            ],
        ]);
	}

	function detail()
    {
        return view('',[
            'detail' => Factory::instance('/article/v1/Article/detail')->run(['id' => input('id', 0, 'int')])['data'] ?? [],
        ]);
    }

}