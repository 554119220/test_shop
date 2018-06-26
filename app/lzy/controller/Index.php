<?php
namespace app\lzy\controller;
use app\common\traits\F as Fun;
use mercury\ResponseException;
use mercury\constants\Code;
use mercury\constants\State;

class Index extends Common

{


	public function index()
	{
		// Fun::thumbnail('');
		// $param = [
		// 	'old_user_password' => 111111,
		// 	'user_password' => 123456,
		// 	're_user_password' => 123456,
		// 	'code' => 134544,
		// 	'user_id' => 1,
		// ];
		// dump(Fun::mApi('user', 'User')->isUpdate(true)->allowField('user_password')->save($param));
		// dump(empty(false));
		// dump(Fun::redis()->set('aa',213123,10));
		// $update['user_last_login_time'] = time();
        // $update['user_login_num']       = 2;
        // $update['user_last_login_ip']   = request()->ip(1);
        // $update['user_id']              = 1;

        // print_r($update);exit;
        // var_dump(Fun::mapi('user', 'User')->allowField(true)->isUpdate(true)->save($update));
		// session('user', db('user')->find(1));
		// print_r(session('user'));
		//var_dump(is_numeric('1.2'));
		// print_r(explode(',', 'aa'));
		// $arr = [
		// 	[ '13寸' , '15寸' ],
		// 	[ '320G' , '500G' ],
		// ];
		// print_r(Fun::array_arrange($arr));
		// $data[]  =[
		// 	'goods_id' => '1',
		// 	'goods_content' => '111',
		// ];
		// $data[]  =[
		// 	'goods_id' => '2',
		// 	'goods_content' => '11',
		// ];
		// print_r( collection((new \app\api\model\GoodsContent)->saveAll($data, false))->toArray() );
		// $model = \app\api\model\User::class;
		// dump(Fun::dataAll($model,[
		// 	'group' => 'user_mobile',
		// ]));
		// dump($_SERVER);
		// dump(PHP_OS);
		// $a = [
		// 	['a' => 'a'],
		// 	['a' => 'a'],
		// 	['a' => 'a'],
		// 	['a' => 'a'],
		// ];
		// foreach ($a as $key => $value) {
		// 	$a[$key]['a'] = $value['a'] . $key;
		// }
		// dump($a);
		// if( false > 000){}
		// dump(cookie('a'));
		// echo session_id();
		// cookie('a', 123, 600);

		// $a = '';
		// $a = [];
		// array_push(  $a, time());
		// print_r($a);
		// $model = new \app\api\model\User;
		// $user = $model->where(['user_username' => 'libo5', 'user_password' => 123456 ])->find();
		// $user->user_last_login_time = time();
        // $user->user_login_num       = 1;
        // $user->user_last_login_ip   = request()->ip(1);
        // $data = $user->toArray();
        // $model2 = new \app\api\model\User;
        // dump($model->where(['user_id' => $user->user_id])->update([ 'user_last_login_ip' => '222']));
		// Fun::sendMail([ 'to' => '1293685219@qq.com' , 'subject' => '注册出错', 'content' => 'o no' ]);
		// dump((new \app\api\validate\User)->scene('register')->check(['user_mobile' => 18377502575, 'code' => 123456]));
		// phpinfo();
		// dump(db('User')->getTableInfo());
		// fun()->api('/User/detail', ['id' => 1]);
		// loaderController('api/User')->lists());
		// $user = \app\common\model\User::all();
		// foreach ($user as $key => $value) {
			// print_r($value->user_Last_Login_Time);
		// }
		// dump(config('template.view_path'));
		// print_r(db()->query('SHOW FULL COLUMNS FROM `' . config('database.prefix') . 'user' . '`'));
		// $model = new \app\api\model\User;
		// $model->allowField(true)->save(["user_mobile" => "18377502575","code" => "123456","user_password" => "123456","re_user_password" => "123456","user_username" => "libo"]);
		return view();
	}







	
}