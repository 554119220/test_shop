<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Commonmodules;
use app\common\traits\F as Fun;
class Goodscategory extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 625;  //表单模板ID
        $this->module_name  = '商品分类';   //模块名称
        $this->initForm();

    }

    public function index(){
        $res = $this->_category(['upkey' => 'category_sid', 'order' => 'category_sort desc,category_id asc','pk' => 'category_id']);
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
        $category_id = request()->param()['category_id'] ?? [];
        $map1 = [
            'category_id'   => [ 'in', $category_id ],
        ];
        $map2 = [
            'category_sid'  => [ 'in', $category_id ],
        ];
        if ( false == db('goods_category')->where($map1)->whereOr($map2)->delete() ) {
            return [ 'code' => 0, 'msg' => '删除失败' ];
        } else {
            # 更新缓存
            \app\api\controller\goods\v1\GoodsCategory::toRedis2();
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
        if ( $res['code'] == 1 ) {
            # 更新缓存
            \app\api\controller\goods\v1\GoodsCategory::toRedis2();
        }
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
        # 获取资质组
        $GoodsQualificationsGroup = db('goods_qualifications_group')->order('goods_qualifications_group_id desc')->where(['category_id' => input('category_id',0,'int')])->select();
        $this->assign('GoodsQualificationsGroup',$GoodsQualificationsGroup);

        $res = $this->_edit();
        return view();
    }

    /**
     * 保存修改
     */
    public function edit_save(){
        $res = $this->_edit_save();
        if ( $res['code'] == 0 ) {
            return $res;
        }
        # 资质设置
        $res = $this->saveGoodsQualificationsGroup($this->post['goods_qualifications_group'] ?? []);
        # 更新缓存
        \app\api\controller\goods\v1\GoodsCategory::toRedis2();
        


        return $res;
    }

    private function saveGoodsQualificationsGroup($param)
    {
        // dump($param);exit;
        db()->startTrans();
        $ids = [];
        $category_id = input('category_id',0, 'int');
        foreach ($param as $key => $value) {
            # 资质名称必须
            if ( empty($value['goods_qualifications_group_name']) && $value['goods_qualifications_group_name'] != '0' ) {
                return [ 'code' => 0, 'msg' => '资质名称必须' ];
            }
            # 资质类型必须选择
            if ( empty($value['goods_qualifications_group_form_type']) && $value['goods_qualifications_group_form_type'] != '0' ) {
                return [ 'code' => 0, 'msg' => '资质类型必须选择' ];
            }
            # 资质值必须填写
            if ( 
                $value['goods_qualifications_group_form_type'] == \mercury\constants\State::GOODS_QUALIFICATIONS_GROUP_TYPE_SELECT
                ||
                $value['goods_qualifications_group_form_type'] == \mercury\constants\State::GOODS_QUALIFICATIONS_GROUP_TYPE_CHECKBOX
            ) {
                if ( empty($value['goods_qualifications_group_value']) && $value['goods_qualifications_group_value'] != '0' ) {
                    return [ 'code' => 0, 'msg' => '资质值必须填写' ];
                }
            }
            # 资质状态必须选择
            if (
                false == isset($value['goods_qualifications_group_state'])
                ||
                (empty($value['goods_qualifications_group_state']) && $value['goods_qualifications_group_state'] != '0')
            ) {
                return [ 'code' => 0, 'msg' => '资质状态必须选择' ];
            }
            # 是否有id
            if ( isset($value['goods_qualifications_group_id']) ) {
                $ids[] = intval($value['goods_qualifications_group_id']);
            }
        }
        # 删除旧
        $delMap = [
            'category_id' => $category_id,
            'goods_qualifications_group_id' => [ 'not in' , $ids ],
        ];
        $count = db('goods_qualifications_group')->where($delMap)->count();
        if ( $count != db('goods_qualifications_group')->where($delMap)->delete() ) {
            return [ 'code' => 0, 'msg' => '保存资质失败' ];
        }
        # 更新和添加
        foreach ($param as $key => $value) {
            $value['goods_qualifications_group_update_time'] = time();
            if ( isset($value['goods_qualifications_group_id']) ) {
                if ( false === db('goods_qualifications_group')->update($value) ) {
                    return [ 'code' => 0, 'msg' => '保存资质失败' ];
                }
            } else {
                $value['goods_qualifications_group_create_time'] = time();
                $value['category_id'] = $category_id;
                if ( false === db('goods_qualifications_group')->insert($value) ) {
                    return [ 'code' => 0, 'msg' => '保存资质失败' ];
                }
            }
        }
        db()->commit();
        return [ 'code' => 1, 'msg' => '保存成功' ];
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
        # 更新缓存
        \app\api\controller\goods\v1\GoodsCategory::toRedis2();
        return $res;
    }
}
