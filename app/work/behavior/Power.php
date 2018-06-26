<?php
/**
 * 权限检测
 */
namespace app\work\behavior;
use app\work\controller\Common;
use think\Request;
class Power
{
    //定义此预加载，即可直接跳过Init中的_initialize()
    protected $request;

    public function __construct(){
        $this->request  = request();
    }

    public function run(&$params='')
    {
        $not_controller = ['Thumb','Login','Index'];   //跳过权限验证的控制器
        if(!in_array($this->request->controller(),$not_controller)) $this->power($params);
    }

    public function power($params){
        //return;
        $controller = strtolower($this->request->controller());
        $action     = strtolower($this->request->action());
        if(substr($action,0,1) == '_') return;  //开头含有_的方法跳过权限检查
        //file_put_contents('power.txt',$this->request->controller().'-'.$this->request->action().PHP_EOL,FILE_APPEND);
        $rs = db('controller')->cache(true,30)->where(['status' => 1,'controller' => $controller])->field('action')->find();
        if(!$rs) $this->noPower();
        if($rs['action']){
            $power = $this->_getPower();
            $n = 0;
            $action_list = json_decode(strtolower(html_entity_decode($rs['action'])),true);
            //dump($action);
            if(isset($action_list[$action]) && $action_list[$action]){
                //dump($action_list[$action]);

                foreach($action_list[$action] as $val){
                    //dump($this->request->controller().':'.$val);
                    //dump(session('admin.power')['action']);
                    if(in_array($controller.':'.$val,$power['action'])) $n++;
                }
                //dump($n);

            }
            //dump($n);exit();
            if($n == 0) $this->noPower();
        }else{
            $this->noPower();
        }
    }

    private function noPower(){
        if($this->request->isAjax()){
            //exit();
            //$this->redirect('/Index/noPowerAjax');
        }else {
            //$this->redirect('/Index/noPower');
        }
    }

    //获取雇员权限
    private function _getPower(){
        $cache_name = 'admin_power_'.session('admin.id');
        $data = cache($cache_name);
        //if(empty($data)){
            $res = api('Admin/power',['group_id' => session('admin.group_id')]);
            //file_put_contents('t.txt',var_export($res,true));
            if($res['code'] == 1){
                $data = $res['data'];
                cache($cache_name,$data,900);
            }
        //}

        return $data;
    }
}
