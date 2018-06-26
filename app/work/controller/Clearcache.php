<?php
/**
 * 清除缓存
 * day:2017-06-17
 */
namespace app\work\controller;
use app\common\traits\F;
use app\work\controller\Common;
use think\Cache;

class Clearcache extends Common
{
    public function index(){
        return view();
    }

    public function clear(){
        if($this->post['type'] == 1){
            cache('site_config',null);
        }else{
            Cache::clear();
        }

        return ['code' => 1,'msg' => '清除成功！'];
    }

    /**
     * 清除redis缓存
     *
     * @return array
     */
    public function flush()
    {
        if (isset($this->get['flush'])) {
            $key = "{$this->get['key']}*";
        } else {
            $key = $this->get['key'];
        }
        $key    = F::getCacheName($key);
        if (strpos($key, '*') !== false) {
            $keys   = F::redis()->keys($key);
        } else {
            $keys   = [$key];
        }
        $flag   = F::redis()->del($keys);
        if (false == $flag) return ['code' => 0, 'msg' => '清除失败'];
        return ['code' => 1, 'msg' => '清除成功'];
    }
}
