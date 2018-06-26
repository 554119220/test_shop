<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
class Articlecategory extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 668;  //表单模板ID
        $this->module_name  = '文章分类';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_category(['upkey' => 'article_category_sid', 'order' => 'article_category_sort desc,article_category_id asc','pk' => 'article_category_id']);
        $this->assign('res',$res);
        $html = html_table($res['data'],$this->formtpl['list_fields'],'',0,$this->formtpl['data_conver']);
        //dump($res);
        $this->assign('html_table',$html['html']);
        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteCategorySelect(){
        $category_id = request()->param()['article_category_id'] ?? [];
        $map1 = [
            'article_category_id'   => [ 'in', $category_id ],
        ];
        $map2 = [
            'article_category_sid'  => [ 'in', $category_id ],
        ];
        if ( false == db('article_category')->where($map1)->whereOr($map2)->delete() ) {
            return [ 'code' => 0, 'msg' => '删除失败' ];
        } else {
            # 更新缓存
            \app\api\controller\article\v1\ArticleCategory::toRedis2();
            return [ 'code' => 1, 'msg' => '成功' ];
        }
    }

    /**
     * 转移目录
     */
    public function changeCategory(){
        $res = $this->_changeCategory();
        return $res;
    }

    /**
     * 批量设置状态
     */
    public function setStatus(){
        $res = $this->_setStatus();
        \app\api\controller\article\v1\ArticleCategory::toRedis2();
        return $res;
    }

    /**
     * 排序
     */
    public function setSort(){
        $res = $this->_setSort();
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
        $res = $this->_edit_save();
        \app\api\controller\article\v1\ArticleCategory::toRedis2();
        return $res;
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
        $res = $this->_add_save();
        \app\api\controller\article\v1\ArticleCategory::toRedis2();
        return $res;
    }
}
