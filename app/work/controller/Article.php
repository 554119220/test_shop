<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
use mercury\factory\Factory;
class Article extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 663;  //表单模板ID
        $this->module_name  = '文章';   //模块名称
        $this->initForm();

    }

    public function index(){
        $cList = Factory::instance('/article/v1/Article_category/index2')->run()['data'] ?? [];
        $res = $this->_index(['where' => ['article_state' => ['in',[1,0]]]]);
        // dump($res);
        foreach($res['data']['list'] ?? [] as $key => $value){
            $sid = $value['article_category_id'];
            $l = 3;
            $name = [];
            while( $l-- > 0 ){
                $name[] = $cList[$sid]['article_category_name'] ?? '';
                $sid    = $cList[$sid]['article_category_sid'] ?? 0;
            }
            $name = array_reverse($name);
            $res['data']['list'][$key]['article_category_id'] = implode("-", $name);
        }
        $this->assign('res',$res);

        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],'',0,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteSelect(){
        // $res = $this->_deleteSelect();
        return $res;
    }

    /**
     * 批量设置状态
     */
    public function setStatus(){
        $res = $this->_setStatus();
        return $res;
    }

    /**
     * 修改
     */
    public function edit(){
        $res = $this->_edit();
        return view();
    }

    /**
     * 保存修改
     */
    public function edit_save(){
        $this->post_cmp();
        $res = $this->validate($this->post,$this->fcfg['model'].'.edit');
        if($res !== true){
            return ['code' => 0,'msg' => $res];
        }

        $res = model($this->fcfg['model'])->allowField(true)->save($this->post,[$this->formtpl['primary_key'] => $this->post[$this->formtpl['primary_key']]]);
        if(false === $res){
            ['code' => 0,'msg' => '修改失败！'];
        }
        if ( false === db('article_content')->where(['article_id' => $this->post['article_id']])->update([ 'article_content' => $this->post['article_content']]) ) {
            return ['code' => 0,'msg' => '添加文章内容失败！'];
        }
        return ['code' => 1,'msg' => '修改成功！'];
    }

    /**
     * 新增
     */
    public function add(){
        $res = $this->_add();
        return view();
    }

    /**
     * 保存新增
     */
    public function add_save(){
        $this->post_cmp();
        $res = $this->validate($this->post,$this->fcfg['model'].'.add');
        if($res !== true){
            return ['code' => 0,'msg' => $res];
        }
        $db = db();
        $db->startTrans();
        $res = model($this->fcfg['model'])->allowField(true)->save($this->post);
        if(false === $res){
            return ['code' => 0,'msg' => '添加文章失败！'];
        }
        if ( false === db('article_content')->insert([ 'article_content' => $this->post['article_content'], 'article_id' => db('article_content')->getLastInsID()]) ) {
            return ['code' => 0,'msg' => '添加文章内容失败！'];
        }
        $db->commit();
        return ['code' => 1,'msg' => '添加成功！'];
    }
    /**
     * 转移目录
     */
    public function change2Category(){
        $res = $this->_change2Category();
        return $res;
    }
}
