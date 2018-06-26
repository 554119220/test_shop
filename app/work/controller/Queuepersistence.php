<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28 0028
 * Time: 11:29
 */

namespace app\work\controller;


use app\common\traits\F;
use mercury\async\Beanstalkd;
use mercury\constants\Cache;
use mercury\constants\State;
use think\Exception;

class Queuepersistence extends Common
{
    protected $redis;
    public function _initialize()
    {
//        $this->redis    = F::redis();
        parent::_initialize();
    }

    public function index()
    {
        $this->redis  = F::redis();
        $key    = Cache::QUEUE_PERSISTENCE_ROW;
        $q_key  = request()->param('key');
        $q_key  = $q_key ? "{$q_key}*" : '';
        $keys   = $this->redis->keys("{$key}*{$q_key}");
        $this->redis->scan('', []);
        $tmp    = [];
        $page   = request()->param('p', 1);
        $rows   = 20;
        $start  = ($page - 1) * $rows;
        $end    = $page == 1 ? $rows : ($page * $rows);
        $cnt    = count($keys);
        $pageSize   = ceil($cnt / $rows);
        if (!empty($keys)) {
            for ($i = $start; $i < $end; $i++) {
                $d    = $this->redis->get($keys[$i]);
                $tmp[$i]    = unserialize($d);
                $tmp[$i]['create_time'] = date('Y-m-d H:i:s', $tmp[$i]['create_time']);
                $tmp[$i]['key'] = $keys[$i];
            }
        }
        $this->assign('list', $tmp);
        $pageinfo = [
            'p'         => $page,
            'pagesize'  => $pageSize,
        ];
        $this->assign('pageinfo',$pageinfo);
        return view();
    }

    /**
     * @title removePersistence 移除持久化
     * > you are api description
     * @return array
     */
    public function removePersistence()
    {
        try {
            $key    = input('key');
            $this->redis    = F::redis();
            if ($this->redis->exists($key)) {
                if ($this->redis->del([$key])) throw new Exception('删除失败');
            }
            $ret    = [
                'code'  => State::STATE_NORMAL,
                'msg'   => '操作成功'
            ];
        } catch (Exception $e) {
            $ret    = [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage()
            ];
        }
        return $ret;
    }

    /**
     * @title removeQueue 移除队列
     * @return array
     */
    public function removeQueue()
    {
        try {
            $job_id = input('job_id');
            $tube   = input('tube');

            $beanstalk  = new Beanstalkd($tube);
            $flag   = $beanstalk->del($job_id);
            if (false == $flag) throw new Exception('移除队列失败');

            $ret    = [
                'code'  => State::STATE_NORMAL,
                'msg'   => '操作成功'
            ];
        } catch (Exception $e) {
            $ret    = [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage()
            ];
        }
        return $ret;
    }

    public function addQueue()
    {
        try {
            $key    = input('key');
            $tube   = input('tube');
            #   $key    = 'mercury:queue:job:erp_refund:order_no:CO20180228113219450692:openid:5512ac7e-4608-b926-8b4e-e8b592a33475:sku_id:117480:sale_psw::refund_no:RO20180228113348770576:is_auto:1:goods_price:9.90:express_price:0';
            $search = "mercury:queue:job:{$tube}:";
            $key    = str_replace($search, '', $key);
            $data   = explode(':', $key);
            $cnt    = count($data);
            $key    = [];
            $value  = [];
            if ($cnt > 0) {
                for ($i = 0; $i < $cnt; $i++) {
                    if ($i % 2) {
                        $value[]  = $data[$i];
                    } else {
                        $key[]= $data[$i];
                    }
                }
            }
            $data   = array_combine($key, $value);

            $beanstalk  = new Beanstalkd($tube);
            $flag   = $beanstalk->put($data);
            if (false == $flag) throw new Exception('入列失败');


            $ret    = [
                'code'  => State::STATE_NORMAL,
                'data'  => $data,
                'msg'   => '操作成功'
            ];
        } catch (Exception $e) {
            $ret    = [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage()
            ];
        }
        return $ret;
    }
}