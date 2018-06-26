<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/16 0016
 * Time: 17:39
 */

namespace app\work\controller;


use app\common\traits\F;
use mercury\constants\Cache;
use think\Exception;

class Shopscore extends Commonmodules
{
    protected $name = 'shopping_score_ratio';
    public function index()
    {
        $value  = db('config_fields')->where('name', $this->name)->value('default');
        return view('', ['value' => $value]);
    }

    public function post()
    {
        try {
            $value  = input("value/f");
            if ($value <= 0) throw new Exception('请填写正确基数');
            if (false == db('config_fields')->where('name', $this->name)->update([
                'default'   => $value
            ])) throw new Exception('更新基数失败');
            $key    = F::getCacheName(Cache::TOOLS_SHOPPING_SCORE_RATIO);
            $flag   = F::redis()->set($key, $value);
            if (!$flag) throw new Exception('设置缓存失败');
            return [
                'code'  => 1,
                'msg'   => '操作成功'
            ];
        } catch (Exception $e) {
            return [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage()
            ];
        }
    }
}