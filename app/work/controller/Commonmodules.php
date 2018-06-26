<?php
namespace app\work\controller;
use app\common\traits\F;
use app\work\controller\Common;
use think\Db;
class Commonmodules extends Common
{
    protected $formtpl_id   = 309;  //表单模板ID
    protected $module_name  = '表单模板';   //模块名称
    protected $formtpl      = array();  //表单模板参数
    protected $table;   //当前数据表
    protected $fcfg;    //配置

    public function _initialize()
    {
        return parent::_initialize();
    }


    /**
     * 初始化模板
     */
    public function initForm(){
        //模板配置
        $res = api('Formtpl/formDetail',['id' => $this->formtpl_id]);
        if($res['code'] != 1) exception('获取不到表单模板！');
        $formtpl        = $res['data'];

        $this->formtpl  = $formtpl;
        $this->table    = substr($formtpl['tables'],strlen(config('database.prefix')));
        $this->fcfg['model']    = format_model_name($formtpl['tables'],$this->formtpl_id);

        //排序
        $this->fcfg['order'] = $formtpl['order'] ? $formtpl['order'] : "{$formtpl['primary_key']} desc";
        if(request()->param('order')){
            $this->fcfg['order']    = str_replace('-',' ',request()->param('order'));
        }

        //列表字段
        $this->fcfg['fields'] = '*';
        $tmp = [];
        foreach($formtpl['list_fields'] as $val){
            $tmp[] = $val['name'];
        }
        if($tmp) $this->fcfg['fields'] = implode(',',$tmp);
        $this->formtpl['view_model']   = $formtpl['view_model'] ? eval(str_replace('[field]',$this->fcfg['fields'],html_entity_decode($formtpl['view_model']))) : '';

        //分页数量
        $this->fcfg['pagesize'] = $formtpl['pagesize'] ? $formtpl['pagesize'] : 20;
        if($this->request->param('pagesize')) $this->fcfg['pagesize'] = $this->request->param('pagesize');

        $this->fcfg['p']    = 1;
        if($this->request->param('p')) $this->fcfg['p'] = $this->request->param('p');

        //搜索条件
        $this->fcfg['where'] = $this->searchWhere();
        $this->assign('formtpl',$formtpl);

    }

    /**
     * 搜索条件
     */
    public function searchWhere(){
        $res = api('Formtpl/searchFields',['id' => $this->formtpl_id]);
        //dump($res);
        $where  = [];
        if($res['code'] == 1){
            //return $res['data'];
            $req = $this->request->param();
            //dump($req);
            foreach($res['data'] as $val){
                if(isset($req[$val['name']]) && $req[$val['name']] !== '' && !strstr($val['name'],'custom_')){
                    if ($val['before_function']) {
                        $req[$val['name']]   = eval(html_entity_decode($val['before_function']));
                    }

                    //if(empty($req[$val['name']])) continue;
                    $val['search_type'] = trim($val['search_type']);
                    switch($val['search_type']){
                        case 'like':
                        case 'not like':
                            $where[$val['name']] = [$val['search_type'] , '%'.$req[$val['name']].'%'];
                            break;
                        default:
                            $where[$val['name']] = [$val['search_type'] , $req[$val['name']]];
                    }
                }

                //between搜索
                if(isset($req[$val['name']]) && $req[$val['name']] !== '' && strstr($val['name'],'custom_')){

//                    if ($val['before_function']) {
//                        $req[$val['name']]   = eval(html_entity_decode($val['before_function']));
//                    }
                    if(strstr($val['name'],'custom_between_')){
                        $tmp = explode('_',$val['name']);
                        //dump($req[$val['name']]);
                        $sfield = 'custom_s'.end($tmp);
                        $efield = 'custom_e'.end($tmp);

                        $req[$sfield] = isset($req[$sfield]) ? strtotime($req[$sfield]) : strtotime('2018-01-01');
                        $req[$efield] = isset($req[$efield]) ? strtotime($req[$efield]) : time();

                        if(!empty($req[$sfield]) && !empty($req[$efield])) $where[$req[$val['name']]] = ['between',[$req[$sfield],$req[$efield]]];
                        if(empty($req[$sfield]) && !empty($req[$efield])) $where[$req[$val['name']]] = ['elt',$req[$efield]];
                        if(!empty($req[$sfield]) && empty($req[$efield])) $where[$req[$val['name']]] = ['egt',$req[$sfield]];
                    }
                }
            }
        }

        //使用了视图模型
        /*
        if($this->formtpl['action_type'] == 'view' && $this->formtpl['view_model']){
            $fields = [];
            foreach($this->formtpl['view_model'] as $val){
                $tmp[$val[0]] = Db::getFields($val[0]);
                $fields = array_merge($fields,$tmp);
            }

            //dump($fields);
            foreach($fields as $key => $val){
                foreach($where as $k => $v){
                    if(isset($val[$k])){
                        $where[$key.'.'.$k] = $v;
                        unset($where[$k]);
                    }
                }
            }
        }
        */

        //dump($where);

        return $where;
    }

    /**
     * 获取表单模板字段用于生成表单
     */
    public function _formFields(){
        $group = db('formtpl_group')->where(['status' => 1,'formtpl_id' => $this->formtpl_id])->field('atime,etime',true)->order('sort asc,id asc')->select();
        foreach($group as &$val){
            $val['fields']  = db('formtpl_fields')->where(['group_id' => $val['id'],'status' => 1])->field('atime,etime',true)->order('sort asc,id asc')->select();
            foreach($val['fields'] as &$v){
                if($v['options']){
                    $options = eval(html_entity_decode($v['options']));
                    $v = array_merge($v,$options);
                }
            }
        }

        $this->assign('group',$group);
        return $group;
    }

    /**
     * 表单数据格式化
     */
    public function post_cmp(){
        $res = api('Formtpl/formtplFields',['id' => $this->formtpl_id]);

        foreach($res['data'] as $val){
            if(isset($this->post[$val['name']])) {
                if ($val['before_function']) {
                    $this->post[$val['name']]   = eval(html_entity_decode($val['before_function']));
                }

                switch($val['formtype']){
                    case 'checkbox':
                        if(empty($this->post[$val['name']])) $this->post[$val['name']] = '';
                        else $this->post[$val['name']] = implode(',',$this->post[$val['name']]);
                        break;
                    case 'password':
                        if($this->post[$val['name']] && $this->post[$val['name']] != $this->post['_password_'.$val['name']]) $this->post[$val['name']] = md5_pwd($this->post[$val['name']]);
                        unset($this->post['_password_'.$val['name']]);
                        break;
                }
            }
        }
    }

    /**
     * 搜索表单
     */
    public function _searchFields(){
        $fields = db('formtpl_search_fields')->where(['status' => 1,'formtpl_id' => $this->formtpl_id])->field('atime,etime',true)->order('sort asc,id asc')->select();
        //隐藏字段与非隐藏字段分离
        $tmp = ['hidden' => '','default' => []];
        foreach($fields as $val){
            if($val['options']){
                $options = eval(html_entity_decode($val['options']));
                $val = array_merge($val,$options);
            }
            if($val['formtype'] == 'hidden') $tmp['hidden'][] = $val;
            else $tmp['default'][] = $val;
        }

        //dump($tmp);

        $this->assign('search_fields',$tmp);
        return $tmp;
    }


    /**
     * 列表页
     */
    public function _index($param=null){
        $options['table']       = isset($param['table']) ? $param['table'] : $this->table;
        $options['pagesize']    = isset($param['pagesize']) ? $param['pagesize'] : $this->fcfg['pagesize'];
        $options['order']       = isset($param['order']) ? $param['order'] : $this->fcfg['order'];
        $options['p']           = isset($param['p']) ? $param['p'] : $this->fcfg['p'];
        $options['where']       = isset($param['where']) ? array_merge($this->fcfg['where'],$param['where']) : $this->fcfg['where'];
        $options['sql']         = isset($param['sql']) ? $param['sql'] : null;
        $options['action_type'] = isset($param['action_type']) ? $param['action_type'] : $this->formtpl['action_type'];
        $options['cache']       = isset($param['cache']) ? $param['cache'] : false;
        $options['field']       = isset($param['field']) ? $param['field'] : $this->fcfg['fields'];
        $options['view_model']  = isset($param['view_model']) && $param['view_model'] ? $param['view'] : $this->formtpl['view_model'];
        $options['is_count']    = isset($param['is_count']) && $param['is_count'] == 0 ? 0 : 1;
        //dump($options);
        $res = pagelist($options);
        return $res;
    }

    /**
     * 批量删除记录
     */
    public function _deleteSelect($param=null){
        $id = isset($param[$this->formtpl['primary_key']]) ? $param[$this->formtpl['primary_key']] : $this->post[$this->formtpl['primary_key']];
        $where = [
            $this->formtpl['primary_key']   => ['in',$id],
            'is_lock'   => 0,
        ];

        $res = db($this->table)->where($where)->delete();

        if($res) return ['code' => 1,'msg' => '删除成功！'];
        return ['code' => 0,'msg' => '删除失败！'];
    }

    /**
     * 批量设置状态
     */
    public function _setStatus($param=null){
        $id = isset($param[$this->formtpl['primary_key']]) ? $param[$this->formtpl['primary_key']] : $this->post[$this->formtpl['primary_key']];
        $where = [
            $this->formtpl['primary_key'] => ['in',$id],
        ];

        $res = db($this->table)->where($where)->update([$this->post['field'] => $this->post['value']]);
        if($res) return ['code' => 1,'msg' => '设置成功！'];
        return ['code' => 0,'msg' => '设置失败！'];
    }

    /**
     * 修改记录
     */
    public function _edit($param=null){
        $where = [
            $this->formtpl['primary_key']    => $this->param[$this->formtpl['primary_key']],
        ];
        $res = db($this->table)->where($where)->find();
        $this->assign('res',$res);
        $this->assign('primary_key', $this->formtpl['primary_key']);
        $this->_formFields();
        return $res;
    }

    /**
     * 保存修改
     */
    public function _edit_save($param=null){
        $this->post_cmp();
        $res = $this->validate($this->post,$this->fcfg['model'].'.edit');
        if($res !== true){
            return ['code' => 0,'msg' => $res];
        }

        $res = model($this->fcfg['model'])->allowField(true)->save($this->post,[$this->formtpl['primary_key'] => $this->post[$this->formtpl['primary_key']]]);
        if(false !== $res) return ['code' => 1,'msg' => '修改成功！'];
        return ['code' => 0,'msg' => '修改失败！'];
    }

    /**
     * 新增记录
     */
    public function _add($param=null){
        $res = $this->_formFields();
        return $res;
    }

    public function _add_save($param=null){
        $this->post_cmp();
        $res = $this->validate($this->post,$this->fcfg['model'].'.add');
        if($res !== true){
            return ['code' => 0,'msg' => $res];
        }

        $res = model($this->fcfg['model'])->allowField(true)->save($this->post);
        if(false !== $res) return ['code' => 1,'msg' => '添加成功！'];
        return ['code' => 0,'msg' => '添加失败！'];
    }


    /**
     * 获取分类
     */
    public function _category($param=null){
        $options['table']       = isset($param['table']) ? $param['table'] : $this->table;
        $options['order']       = isset($param['order']) ? $param['order'] : "sort asc,{$this->formtpl['primary_key']} asc";
        $options['where']       = isset($param['where']) ? $param['where'] : $this->fcfg['where'];
        $options['cache']       = isset($param['cache']) ? $param['cache'] : false;
        $options['field']       = isset($param['field']) ? $param['field'] : $this->fcfg['fields'];
        $options['max_depth']   = isset($param['max_depth']) ? $param['max_depth'] : 3;
        $options['upkey']       = isset($param['upkey']) ? $param['upkey'] : 'upid';
        $options['order']       = isset($param['order']) ? $param['order'] : 'sort asc,id asc';
        $options['pk']          = isset($param['pk']) ? $param['pk'] : 'id';
        // dump($options);
        $res = get_category($options);
        //dump($res);
        return $res;
    }

    /**
     * 删除类目
     */
    public function _deleteCategorySelect($param=null){
        $id     = isset($param[$this->formtpl['primary_key']]) ? $param[$this->formtpl['primary_key']] : $this->post[$this->formtpl['primary_key']];
        $ids    = [];
        foreach($id as $val){
            $tmp = sortid($this->table,$val);
            $ids = array_merge($ids,$tmp);
        }

        $where = [
            $this->formtpl['primary_key'] => ['in',$ids],
        ];

        //dump($where);

        $res = db($this->table)->where($where)->delete();

        if($res) return ['code' => 1,'msg' => '删除成功！'];
        return ['code' => 0,'msg' => '删除失败！'];
    }

    /**
     * 排序
     */
    public function _setSort($param=null){
        foreach($this->post['id'] as $key => $val){
            db($this->table)->where([$this->formtpl['primary_key'] => $val])->update(['sort' => ($key+1)]);
        }
        return ['code' => 1,'msg' => '设置成功！'];
    }

    /**
     * 分类管理-转移目录
     */
    public function _changeCategory($param=null){
        $id     = isset($param[$this->formtpl['primary_key']]) ? $param[$this->formtpl['primary_key']] : $this->post[$this->formtpl['primary_key']];
        $where = [
            'id' => ['in',$id],
        ];
        $res = db($this->table)->where($where)->update(['upid' => $this->post['upid']]);
        if($res) return ['code' => 1,'msg' => '转移成功！'];
        return ['code' => 0,'msg' => '转移失败！'];
    }

    /**
     * 记录管理-转移目录
     */
    public function _change2Category($param=null){
        $id     = isset($param[$this->formtpl['primary_key']]) ? $param[$this->formtpl['primary_key']] : $this->post[$this->formtpl['primary_key']];
        $where = [
            'id' => ['in',$id],
        ];
        $res = db($this->table)->where($where)->update(['category_id' => $this->post['category_id']]);
        if($res) return ['code' => 1,'msg' => '转移成功！'];
        return ['code' => 0,'msg' => '转移失败！'];
    }

    /**
     * 生成按钮
     */
    public function _createButton($btns,$group_name=''){
        if($group_name != ''){
            $html = '<div class="btn-group">
                         <a class="btn blue btn-outline btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"> '.$group_name.'
                            <i class="fa fa-angle-down"></i>
                         </a>
                         <ul class="dropdown-menu pull-right">';

            foreach($btns as $val){
                if(isset($val['divider']) && $val['divider'] == 1){
                    $html .= '<li class="divider"></li>';
                }else {
                    $html .= '<li><a href="' . $val['url'] . '"'.(isset($val['onclick']) && $val['onclick'] ? ' onclick="'.$val['onclick'].'"' : '').'> ' . $val['name'] . '</a></li>';
                }
            }

            $html .= '</ul></div>';
        }else{
            $html   = '<a href="'.$val['url'].'" class="btn blue btn-outline btn-block">'.$val['name'].'</a>';
        }
        
        return $html;
    }
}
