<?php
/**
 * 城市选择
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Common;
use mercury\factory\Factory;
class Selectcity extends Common
{
    public function city(){
        $data = [];
        if(isset($this->param['upid'])) $data['upid'] = $this->param['upid'];
        $arr['id'] = $data['upid'];
        $res   = Factory::instance('/tools/v1/District/index')->run($arr);
        if($res['code'] == 20000){
            $res['code'] = 1;
        }
        return $res;
    }

    public function sameDepthCity(){
        //config('api_debug',true);
        $data = [];
        if(isset($this->param['id'])) $data['id'] = $this->param['id'];
        $res   = Factory::instance('/tools/v1/District/levelcity')->run($data);
        if($res['code'] == 20000){
            $res['code'] = 1;
        }
        return $res;
    }

}