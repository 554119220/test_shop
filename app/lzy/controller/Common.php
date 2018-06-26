<?php
namespace app\lzy\controller;
use think\Controller;

class Common extends Controller
{


	public function _initialize(){
		$local[1] = '192.168';
		$local[2] = '127.0.0';
		if (  false == in_array( substr($_SERVER['SERVER_ADDR'],0,7), $local ) ) {
			die('hello world!');
		}
	}





	
}