<?php
/**
 * 分类选择
 * day:{day}
 */
namespace app\work\controller;
use app\work\controller\Common;
class Selectcategory extends Common
{
    public function category(){
        // dump(unserialize($this->post['options']));exit;
        $options = unserialize($this->post['options']);

        $options['where'] = array_merge($options['where'], [ $options['upkey'] => $this->post['upid'] ]);

        $list = db($options['table'])->where($options['where'])->field($options['field'])->order($options['order'])->select();
        # 同化出id,name 因模板已固定死,而每个表的主键又不是id,name
        foreach ($list as $key => $value) {
            $list[$key]['id'] = $value[$options['key'][0]];
            $list[$key]['name'] = $value[$options['key'][1]];
        }
        // dump($list);exit;
        // $data['options']    = $this->post['options'];
        // $data['upid']       = $this->post['upid'];
        // $res = api('selectcategory/category',$data);
        return ['code' => 1, 'data' => $list];
    }

    public function sameDepthCategory(){
        //config('api_debug',true);
        $data['options']    = $this->post['options'];
        $data['upid']       = $this->post['upid'];
        $res = api('selectcategory/sameDepthCategory',$data);
        return $res;
    }

}
