<?php
namespace app\work\controller;
use think\Config;
use think\Controller;
use think\Hook;
use think\Session;
use enhong\Status;
class Init extends Controller
{
    protected $api_cfg;     //接口参数
    protected $token;       //授权token
    protected $apiurl;      //接口请求地址
    protected $post;
    protected $get;
    protected $param;
    protected $sw;          //记录事务执行结果
    protected $dotime;      //执行时间
    protected $terminal;    //终端类型
    protected $result;      //返回结果
    protected $log_table    = 'admin_logs';   //存放日志的表，method表示用当前控制器名作为表名
    protected $is_log       = true; //是否记录日志，配合config('api_log_level')
    protected $attr         = ['R'];   //方法属性,默认为读操作

    public function _initialize()
    {
        debug('begin');
        //request()->filter('htmlspecialchars');  //数据过滤设置
        $this->post     = input('post.');
        $this->get      = input('get.');
        $this->param    = $this->request->param();
        Session::init();
        $this->apiurl   = request()->scheme().'://api.'.config('url_domain_root').'/work.v1.';
        $this->api_cfg  = $this->api_cfg();
        config('apiurl',$this->apiurl);
        config('api_cfg',$this->api_cfg);

        $cache_name     = 'front_api_work_token_'.session_id();
        $this->token    = cache($cache_name);
        //每隔10分钟生成一次token
        if(empty($this->token)) {
            //config('api_debug',true);
            $res = api('auth/token', $this->api_cfg);
            if ($res['code'] != 1) {
                return $res;
            }
            $this->token = $res['data'];
            cache($cache_name,$this->token,600);
        }
        config('token',$this->token);
        $this->_config();
    }


    /**
     * 接口参数
     * @return array
     */
    private function api_cfg()
    {
        $data = [
            'appid'         => 1,
            'access_key'    => '1f982aa4178c278c95529e28b0f1b20f',
            'secret_key'    => 'cfdb08305ddf31113eed7d6bd7c6ce94',
            'sign_code'     => 'a35bbf3cf3cb98f91bc748f4660127ee',
            'device_id'     => session_id(),
            'ip'            => request()->ip(),
        ];
        return $data;
    }

    /**
     * 获取配置
     */
    public function _config(){
        $list = db('config_category')->cache('site_config')->where(['status' => 1,'upid' => ['gt',0]])->field('group_name,config')->select();
        $cfg = [];
        foreach($list as $key => $val){
            if($val['config']){
                $val['config'] = unserialize(html_entity_decode($val['config']));
            }
            $cfg[$val['group_name']] = $val['config'];
        }
        config('cfg',$cfg);
        return $cfg;
    }

    /**
     * 接口数据返回
     * @param $data
     */
    public function ret($data,$return_type = 'array'){
        $msg = [
            0   => '操作失败！',
            1   => '操作成功！',
            3   => '找不到记录！',
        ];
        if(!isset($data['msg']) && isset($msg[$data['code']]))  $data['msg'] = $msg[$data['code']];
        if(!isset($data['data'])) $data['data'] = '';

        $this->result = $data;
        if($return_type == 'json') $this->result = json($data);
        $this->dotime = debug('begin','end',6);

        $params = ['handle' => $this];
        Hook::listen('work_log',$params);

        return $this->result;
    }
}
