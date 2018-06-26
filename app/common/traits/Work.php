<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31 0031
 * Time: 16:35
 */

namespace app\common\traits;


use app\common\extra\Code;
use Endroid\QrCode\QrCode;
use think\Config;
use think\Exception;

trait Work
{
    /**
     * api
     *
     * @param $api
     * @param $data
     * @param string $nosign
     * @return mixed|string
     */
    public static function api($api, $data, $nosign = '')
    {
        $apiurl     = Config::get('apiurl');
        $api_cfg    = Config::get('api_cfg');
        $token      = isset($data['token']) && $data['token'] ? $data['token'] : Config::get('token.token');
        $apiurl     = preg_match("/^(http:\/\/|https:\/\/).*$/",$api) ? $api : $apiurl . $api;
        if(strstr(strtolower($apiurl),'auth/token') == false) $data['token'] = $token;
        $data['sign']       =   sign($data,$nosign);
        $data['random']     =   isset($data['random']) && $data['random'] ? $data['random'] : session_id();
        //dump($apiurl);
        //dump($data);
        $res=       F::curl($apiurl,$data);
        if(Config::get('api_debug')) print_r($res);
        //$res=json_decode($res,true);
        if(Config::get('api_debug')) dump($res);
        return $res;
    }

    /**
     * api2
     *
     * @param $api
     * @param $data
     * @param string $nosign
     * @return mixed|string
     */
    public static function api2($api, $data, $nosign = '')
    {
        $apiurl     = Config::get('trj_apiurl');
        $api_cfg    = Config::get('trj_api_cfg');
        $token      = isset($data['token']) && $data['token'] ? $data['token'] : Config::get('trj_token.token');
        $apiurl     = preg_match("/^(http:\/\/|https:\/\/).*$/",$api) ? $api : $apiurl . $api;
        if(strstr(strtolower($apiurl),'auth/token') == false) $data['token'] = $token;
        $data['sign']       =   sign($data,$nosign,1);
        $data['random']     =   isset($data['random']) && $data['random'] ? $data['random'] : session_id();
        //dump($apiurl);
        //dump($data);
        //dump(config('trj_token'));
        $res        =F::curl($apiurl,$data);
        if(Config::get('api_debug')) print_r($res);
        //$res        =json_decode($res,true);
        if(Config::get('api_debug')) dump($res);

        return $res;
    }

    /**
     * 二维码生成
     *
     * @param $string
     */
    public static function qrCode($string)
    {
        $qr_code    = new QrCode($string);
        //$qr_code->setLogoPath('/favicon.ico');
        header("Content-type: {$qr_code->getContentType()}");
        return $qr_code->writeString();
    }

    /**
     * 获取后台菜单
     *
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function menu()
    {
        $group = db('admin_group')->where(['id' => 3])->field('menu_id')->find();
        //dump($group);
        //获取三级菜单
        $menu = db('menu')->where(['status' => 1,'upid' => 0,'id' => ['in' , $group['menu_id']]])->field('atime,etime,ip',true)->order('sort asc,id asc')->select();
        //dump($menu);
        foreach($menu as &$val){
            $val['dlist']   = db('menu')->where(['status' => 1,'upid' => $val['id'],'id' => ['in' , $group['menu_id']]])->field('atime,etime,ip',true)->order('sort asc,id asc')->select();
            foreach($val['dlist'] as &$v){
                $v['dlist']   = db('menu')->where(['status' => 1,'upid' => $v['id'],'id' => ['in' , $group['menu_id']]])->field('atime,etime,ip',true)->order('sort asc,id asc')->select();
            }
        }
        return $menu;
    }

    /**
     * 获取权限
     *
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function power()
    {
        $power = db('admin_group')->where(['id' => 3,'status' => 1])->field('menu_id,action')->find();
        $power['action']    = json_decode(strtolower(html_entity_decode($power['action'])),true);
        return $power;
    }

    /**
     * 取无限级分类
     *
     * @param array $param
     * @return array
     */
    public static function getCategory(array $param)
    {
        try {

            $table = isset($param['table']) ? $param['table'] : '';                         //读取数据表
            if(empty($table)) throw new Exception('未设置要读取的数据表');

            $upkey  = isset($param['upkey']) ? $param['upkey'] : 'upid';                    //父级字段
            $field  = isset($param['field']) && $param['field'] ? $param['field'] : '*';    //获取字段
            $where  = isset($param['where']) ? $param['where'] : [];                        //条件
            $order  = isset($param['order']) ? $param['order'] : 'sort asc,id asc';         //排序
            $limit  = isset($param['limit']) ? $param['limit'] : '';                        //获取数量
            $cache  = isset($param['cache']) ? $param['cache'] : false;                     //是否启用缓存
            $cache_time     = isset($param['cache_time']) ? $param['cache_time'] : config('cache.expire'); //是否启用缓存
            $max_depth      = isset($param['max_depth']) ? $param['max_depth'] : 5;             //最多获取层级
            $depth  = isset($param['depth']) ? $param['depth'] : 0;                         //层级
            $depth++;   //当前层级

            if($depth > $max_depth) throw new Exception('操作成功', 3);

            $upid   = isset($param[$upkey]) && $param[$upkey] > 0 ? $param[$upkey] : 0;     //父级ID
            $where[$upkey]  = $upid;
            //dump($where);
            $list   = db($table)->cache($cache,$cache_time)->where($where)->field($field)->order($order)->limit($limit)->select();
            if(!$list) throw new Exception('无数据', Code::CODE_NO_DATA);

            foreach ($list as $key => $val) {
                $options = [
                    'table'     => $table,
                    'where'     => $where,
                    'field'     => $field,
                    'limit'     => $limit,
                    'depth'     => $depth,
                    'max_depth' => $max_depth,
                    $upkey      => $val['id'],
                    'upkey'     => $upkey,
                ];
                //dump($options);

                if($depth < $max_depth) $list[$key]['sublist'] = self::getCategory($options);
            }
            $res = [
                'code'  => Code::CODE_TRUE,
                'data'  => $list,
                'count' => count($list),
                'depth' => $depth
            ];
        } catch (Exception $e) {
            $res = [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage(),
                'data'  => [],
                'count' => 0,
                'depth' => isset($depth) ? $depth : ''
            ];
        }
        return $res;
    }
}