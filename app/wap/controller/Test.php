<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2 0002
 * Time: 10:02
 */

namespace app\wap\controller;


use app\common\traits\F;
use mercury\cache\PRedis;
use Predis\Client;
use Predis\Connection\Aggregate\PredisCluster;

class Test
{
    public function index()
    {
        //$url    = 'https://www.kuaidi100.com/all/';
        $url    =   ROOT . '/wuliu.html';
        $html   = file_get_contents($url);
        //$html   = '<a href="Express.php">hahaha</a>';
        //$pattern= "/<dt>(.*?)</dt>/";
        $pattern= "/<a(\S\s)href=\"(.*?)\"(\s\S)>(.*)</a>/";
        //file_put_contents($url, $html);
        preg_match_all($pattern, $html, $a);
        dump($a);
    }

    public function redis()
    {
//        $redis  = new PredisCluster();
//        $redis  = new PRedis();
//        $redis->client()->set('test1', 1);
        $a = F::redis()->get('test1');
        F::redis()->set('test2', 789456, time()+100);
        //dump($a);
//        $current= $redis->setSentinel()->getConnection()->getCurrent();
//        dump($current);
    }

    public function sentinel()
    {
        $sentinels = array(
            'tcp://192.168.10.27:26379?timeout=0.100',
            'tcp://192.168.10.28:26379?timeout=0.100',
            'tcp://192.168.10.29:26379?timeout=0.100',
        );

        $client = new Client($sentinels, array(
            'replication'   => 'sentinel',
            'service'       => 'redis1',
            'parameters'    => [
                'password'  => 'abcZR188'
            ]
        ));
        $res    = $client->set('test', 1);
        dump($client->getConnection()->getCurrent()->getParameters());
        //$a = $client->exists('foo') ? 'yes' : 'no';
        //dump($a);
    }
}