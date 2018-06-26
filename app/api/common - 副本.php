<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use think\Config;
use enhong\BuildForm;
use enhong\Status;
use think\Db;
use think\View;
/**
 * 接口请求
 * Create by lazycat
 * 2017-06-01
 * @param string $api      接口地址
 * @param string $data     提交数据
 * @param string $nosign    不签名字段
 */
function api($api,$data,$nosign=''){
    $apiurl     = Config::get('apiurl');
    $api_cfg    = Config::get('api_cfg');
    $token      = isset($data['token']) && $data['token'] ? $data['token'] : Config::get('token.token');
    $apiurl     = preg_match("/^(http:\/\/|https:\/\/).*$/",$api) ? $api : $apiurl . $api;
    if(strstr(strtolower($apiurl),'auth/token') == false) $data['token'] = $token;
    $data['sign']       =   sign($data,$nosign);
    $data['random']     =   isset($data['random']) && $data['random'] ? $data['random'] : session_id();
    //dump($apiurl);
    //dump($data);
    $res=curl_post($apiurl,$data);
    if(Config::get('api_debug')) print_r($res);
    $res=json_decode($res,true);
    if(Config::get('api_debug')) dump($res);

    return $res;
}

function api2($api,$data,$nosign=''){
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
    $res=curl_post($apiurl,$data);
    if(Config::get('api_debug')) print_r($res);
    $res=json_decode($res,true);
    if(Config::get('api_debug')) dump($res);

    return $res;
}

function curl_post($url,$data,$param=null,$timeout = 30){
    $curl = curl_init($url);// 要访问的地址
    //curl_setopt($curl, CURLOPT_REFERER, $param['referer']);

    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 MSIE 8.0'); // 模拟用户使用的浏览器
    //curl_setopt($curl, CURLOPT_USERAGENT, 'spider'); // 模拟用户使用的浏览器
    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    //curl_setopt($curl, CURLOPT_ENCODING, ''); // handle all encodings
    //curl_setopt($curl, CURLOPT_HTTPHEADER, $refer);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//SSL证书认证
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
    //curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址

    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    curl_setopt($curl,CURLOPT_POST,true); // post传输数据
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);// post传输数据

    //是否为上传文件
    if(!is_null($param) && !empty($param)) curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
    $res = curl_exec($curl);
    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);

    return $res;
}

function curl_get($url){
    $curl = curl_init($url);// 要访问的地址
    //curl_setopt($curl, CURLOPT_REFERER, $param['referer']);

    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 MSIE 8.0'); // 模拟用户使用的浏览器
    //curl_setopt($curl, CURLOPT_USERAGENT, 'spider'); // 模拟用户使用的浏览器
    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    //curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    //curl_setopt($curl, CURLOPT_ENCODING, ''); // handle all encodings
    //curl_setopt($curl, CURLOPT_HTTPHEADER, $refer);
    curl_setopt($curl, CURLOPT_HTTPGET, true);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//SSL证书认证
    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
    //curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址

    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    $res = curl_exec($curl);
    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    curl_close($curl);

    return $res;
}

/**
 * 生成签名
 * @param array     $data   要进行签名的数据
 * @param string    $nosign  不参与签名字段
 * @param int       $sign_type  盐的类型，空是为默认，1是为唐人街接口的盐
 */
function sign($data,$nosign='',$sign_type=''){
    $not = ['random'];
    if($nosign){
        $nosign = explode(',',$nosign);
        $not    = array_merge($not,$nosign);
    }
    $not = array_unique($not);
    if(!is_array($data)) $data = [];
    foreach($data as $key => $val){
        if(in_array($key,$not)) unset($data[$key]);
    }

    ksort($data);
    //dump($data);
    $query=http_build_query($data).'&'.($sign_type == 1 ? Config::get('trj_api_cfg.sign_code') : Config::get('api_cfg.sign_code'));
    //dump($query);
    $query=urldecode($query);
    return md5($query);
}



/**
 * 表单生成器
 * @param array $param 字段选项
 * @param array $data 值
 */
function buildform($param,$data=''){
    $html = array();
    $form = new BuildForm();
    $form->value = $data;
    foreach($param as $key=>$val){
        if(substr($key,0,5)=='field'){
            foreach($val as $vkey=>$val){
                $method = $val['formtype'];
                $html[] = $form->$method($val)->create();
            }
        }
    }
    $html = @implode('',$html);
    echo $html;
}

/**
 * 创建单个表单项
 */
function form_item($param,$data=''){
    $form = new BuildForm();
    $form->value = $data;
    $method = $param['formtype'];
    $html   = $form->$method($param)->item();
    //$html = $form->$param['formtype']($param)->item();
    return $html;
}

/**
 * 状态格式成按钮
 */
function status($code,$btn=''){
    if($btn == '') $btn = [['停用',''],['启用','text-success']];
    if(!isset($code) || !isset($btn)) return;
    $html = '<div class="'.(isset($btn[$code][1]) ? $btn[$code][1] : '').'">'. $btn[$code][0].'</div>';
    return $html;
}

/**
 * 格式模型名称
 */
function format_model_name($tables,$id=''){
    $model = substr($tables,strlen(config('database.prefix')));
    $model = explode('_',$model);
    foreach($model as &$val){
        $val = ucfirst($val);
    }
    $model = implode('',$model).$id;
    return $model;
}

/**
 * 视图排序时字段自动加上表名
 * @param $order
 * @param $view_model
 * @return string
 */
function order_conver($order,$view_model){
    if(strstr($order,'.') || substr($order,0,strlen(config('database.prefix'))) == config('database.prefix')) return $order;
    $orders  = explode(',',$order);
    foreach($orders as $key => &$val){
        $val = explode(' ',$val);
    }
    if(isset($val)) unset($val);
    //dump($orders);
    $fields = [];
    foreach($view_model as $val){
        $tmp[$val[0]] = Db::getFields($val[0]);
        $fields = array_merge($fields,$tmp);
    }

    $tmp = [];
    foreach($orders as $val){
        foreach($fields as $key => $vl){
            foreach($vl as $v){
                if($v['name'] == $val[0] && !isset($tmp[$v['name']])){
                    $tmp[$v['name']] = $key.'.'.$v['name'].' '.$val[1];
                }
            }
        }
    }

    //dump($tmp);
    return implode(',',$tmp);
    //dump($fields);
}

/**
 * 分页
 */
function pagelist($param){
    if(!isset($param['table'])) return ['code' => 0,'msg' => '缺少要操作的数据表或模型！'];
    $table      = $param['table'];
    $pagesize   = isset($param['pagesize']) ? $param['pagesize'] : 20;
    $order      = isset($param['order']) ? $param['order'] : 'id desc';
    $p          = isset($param['p']) ? $param['p'] : 1;
    $p          = $p < 1 ? 1 : $p;
    $where      = isset($param['where']) ? $param['where'] : [];
    $action_type= isset($param['action_type']) ? $param['action_type'] : 'default';
    $cache      = isset($param['cache']) ? $param['cache'] : false;
    $cache_time = isset($param['cache_time']) ? $param['cache_time'] : config('cache.expire');
    $field      = isset($param['field']) ? $param['field'] : '*';
    $view_model = isset($param['view_model']) ? $param['view_model'] : '';
    $group      = isset($param['group']) ? $param['group'] : null;
    $sql        = isset($param['sql']) ? $param['sql'] : null;
    $is_count   = isset($param['is_count']) && $param['is_count'] == 0 ? 0 : 1;
    //dump($param);
    switch($action_type){
        case 'view':    //视图查询
            if(!isset($view_model) || empty($view_model)) return ['code' => 0,'msg' => '未设置视图参数！','data' => ['list' => '','pageinfo' => '']];
            $order = order_conver($order,$view_model);
            $do = db();
            foreach($view_model as $val){
                //dump($val[1]);
                $on     = isset($val[2])&& $val[2] ? $val['2'] : '';
                $type   = isset($val[3])&& $val[3] ? $val['3'] : 'INNER';
                $do->view($val[0],$val[1],$on,$type);
            }
            $count = $do->where($where)->where($sql)->count();
            $page   = ceil($count/$pagesize);
            $p      = $p > $page ? $page : $p;

            foreach($view_model as $val){
                $on     = isset($val[2])&& $val[2] ? $val['2'] : '';
                $type   = isset($val[3])&& $val[3] ? $val['3'] : 'INNER';
                $do->view($val[0],$val[1],$on,$type);
            }
            $list   = $do->cache($cache,$cache_time)->where($where)->where($sql)->page($p)->limit($pagesize)->order($order)->select();
            //dump($list);
            //dump($do->getLastSQL());
            break;
        case 'relation':
            break;
        default:
            if($is_count == 1) {
                $count = db($table)->where($where)->where($sql)->group($group)->count();
                $page = ceil($count / $pagesize);
                $p = $p > $page ? $page : $p;
            }
            $list   = db($table)->cache($cache,$cache_time)->where($where)->where($sql)->field($field)->page($p)->group($group)->limit($pagesize)->order($order)->select();
    }

    if($list){
        $pageinfo = [
            'pagesize'  => $pagesize,
            'p'         => $p,
            //'sql'       => db($table)->getLastSql(),
        ];
        if($is_count == 1){
            $pageinfo['page']     = $page;
            $pageinfo['count']    = $count;
        }
        if(isset($param['item_function']) && $param['item_function']){
            foreach($list as &$val){
                $val = eval($param['item_function']($val));
            }
        }
        return ['code' => 1,'data' => ['list' => $list,'pageinfo' => $pageinfo]];
    }
    return ['code' => 3,'data' => ['list' => '','pageinfo' => '']];
}


/**
 * 将数据记录生成列表管理
 * @param array     $data   数据
 * @param array     $th     列标题
 * @param string    $btn   按钮
 * @param int       $colspan    是否扩展合并行
 * @param string    $data_conver    列表字段中进行数据格式化处理的PHP代码
 */
function html_table($data,$th,$btn='',$colspan=0,$data_conver=''){
    if(empty($data)) {
        $res['html']    = '<div class="text-center nors">暂无记录！</div>';
        return $res;
    }

    if(empty($th)){
        $res['html']    = '<div class="text-center nors">缺少输出字段！</div>';
        return $res;
    }

    if(!empty($data_conver)){
        eval(html_entity_decode($data_conver));
    }

    $id     = 'id'; //行标记
    $col    = count($th) + 1;   //列数
    $btns   = '<a href="'.url(request()->controller().'/edit','',false).'/'.$id.'/['.$id.']" class="btn blue btn-outline btn-block">修改</a>';   //操作按钮
    if($btn === false)  {
        $btns = '';
    }else{
        $col++;
        $btns = $btn ? $btn : $btns;
    }
    $base_url = request()->controller().'/'.request()->action();    //当前方法

    //dump($th);exit();

    $html   = '<table class="table table-bordered table-hover valign-middle">';
    $thead  = '<thead>';
    $thead  .= '<th class="text-center" width="60" nowrap>选择</th>';
    $field  = [];
    foreach($th as $key => $val){
        $field[] = $val['name'];
        $attr_th = [];
        $attr_th[]  = $val['attr'] ? html_entity_decode($val['attr']) : '';
        //排序按钮
        $order_url = url($base_url,array_merge(request()->param(),['order' => (request()->param('order') == $val['name'].'-asc' ? $val['name'].'-desc' : $val['name'].'-asc')]));
        $sort    = ' <a href="'.$order_url.'"><i class="fa fa-angle-'.(request()->param('order') == $val['name'].'-asc' ? 'up' : 'down').'"></i></a>';
        $thead  .= '<th '.implode(' ',$attr_th).' nowrap>'.$val['label'].$sort.'</th>';
    }
    if($btn !== false) {
        $thead  .= '<th class="text-center" width="100" nowrap>操作</th>';
    }
    $thead  .= '</thead>';
    if(!in_array($id,$field)) $id = $field[0];

    //dump($thead);exit();

    $tbody  = '<tbody>';
    foreach($data as $key => $val){
        $attr_tr = [];
        $attr_tr[] = 'id="'.$val[$id].'"';
        $tbody  .= '<tr '.implode(' ',$attr_tr).'>';
        $tbody  .= '<td class="text-center" width="60">
                        <label class="mt-checkbox mt-checkbox-outline">
						    <input type="checkbox" id="'.$id.'[]" name="'.$id.'[]" value="'.$val[$id].'">
						    <span></span>
					    </label>
					</td>';

        foreach($th as $k => $v){
            $attr_td = [];
            $attr_td[]  = 'data-field="'.$v['name'].'"';
            $attr_td[]  = $v['attr'] ? html_entity_decode($v['attr']) : '';
            //dump($attr_td);
            $tbody  .= '<td '.implode(' ',$attr_td).' style="word-wrap:break-word;word-break:break-all;">';
            $tbody  .= isset($v['function']) && $v['function'] ? eval(html_entity_decode($v['function'])) : $val[$v['name']];
            $tbody  .= '</td>';
        }
        if($btn !== false) {
            if(is_array($btn) && isset($btn[0])){
                //dump($btn);
                $tbody .= '<td class="text-center" width="100">' . eval(html_entity_decode($btn[0])) . '</td>';
            }else {
                $tbody .= '<td class="text-center" width="100">' . url_conver($btns, $val) . '</td>';
            }
        }
        $tbody  .= '</tr>';

        if(isset($val['sublist']) && $val['sublist']['count'] > 0){ //子级
            //dump($val['sublist']);exit();
            //$tmp['html'] = '';
            $tmp = html_table($val['sublist']['data'],$th,$btn,$colspan,$data_conver);
            $tbody  .= '<tr data-id="ext-sub-'.$val[$id].'-'.$val['sublist']['depth'].'" class="table-sublist"><td colspan="'.$col.'" style="padding:0;padding-left:60px">'.$tmp['html'].'</td></tr>';
        }

        if($colspan == 1){
            $tbody  .= '<tr class="hide ext-row" data-id="ext-row-'.$val[$id].'"><td colspan="'.$col.'"></td></tr>';
        }
    };

    $tbody  .= '</tbody>';

    $html .= $thead . $tbody .'</table>';
    $res['html']    = $html;

    return $res;
}

/**
 * 格式化Url
 * @param $url
 * @param $arr
 * @return mixed
 */
function url_conver($url,$arr){
    foreach($arr as $key => $val){
        if(!is_array($val)) $url = str_replace('['.$key.']',$val,$url);
    }
    return $url;
}
/**
 * 生成分页html
 * @param int $param['page'] 总页数
 * @param int $param['p']   当前页码
 * @param int $param['count'] 总记录数
 * @param int $param['pagesize'] 每页数量
 */
function page_html($param,$show_goto=1){
    if(empty($param)) return '';
    $allpage = $param['page'];
    if(isset($param['max']) && $param['max'] < $param['page']) $param['page'] =  $param['max'];
    $base_url   = request()->controller().'/'.request()->action();    //当前方法
    $vars       = request()->param();
    if(isset($vars['p'])) unset($vars['p']);

    if($param['page'] > 1) {
        $first = '<a class="btn-p page-s ' . ($param['p'] < 2 ? 'disabled' : '') . '" ' . ($param['p'] > 1 ? ' href="' . url($base_url, array_merge($vars, array('p' => $param['p'] - 1))) . '"' : '') . '>上一页</a>';
        $first .= '<a class="btn-p page-no ' . ($param['p'] == 1 ? 'active' : '') . '" ' . ($param['p'] != 1 ? ' href="' . url($base_url, array_merge($vars, array('p' => 1))) . '"' : '') . '>1</a>';
        $last = '<a class="btn-p page-no ' . ($param['p'] == $param['page'] ? 'active' : '') . '" ' . ($param['p'] != $param['page'] ? ' href="' . url($base_url, array_merge($vars, array('p' => $param['page']))) . '"' : '') . '>' . $param['page'] . '</a>';
        $last .= '<a class="btn-p page-s ' . ($param['p'] >= $param['page'] ? 'disabled' : '') . '" ' . ($param['p'] < $param['page'] ? ' href="' . url($base_url, array_merge($vars, array('p' => $param['p'] + 1))) . '"' : '') . '>下一页</a>';



        $page_num = [];
        if ($param['page'] < 9) {
            for ($i = 2; $i < $param['page']; $i++) {
                $page_num[] = $i;
            }
        } elseif ($param['p'] >= 6 && $param['p'] + 2 < $param['page']) {
            $page_num = [
                '',
                $param['p'] - 2,
                $param['p'] - 1,
                $param['p'],
                $param['p'] + 1,
                $param['p'] + 2,
                ''
            ];
        } elseif ($param['p'] <= 5 && $param['page'] >= 8) {
            for ($i = 2; $i <= 7; $i++) {
                $page_num[] = $i;
            }
            $page_num[] = '';
        } elseif ($param['page'] - $param['p'] <= 4) {
            $page_num[] = '';
            for ($i = $param['page'] - 7; $i < $param['page']; $i++) {
                $page_num[] = $i;
            }
        }

        $middle = '';
        foreach ($page_num as $val) {
            if ($val == '') $middle .= '<a class="page-nobox">…</a>';
            else $middle .= '<a class="btn-p page-no ' . ($param['p'] == $val ? 'active' : '') . '" ' . ($param['p'] != $val ? ' href="' . url($base_url, array_merge($vars, array('p' => $val))) . '"' : '') . '>' . $val . '</a>';
        }

        $total = '<div class="page-total">'.$param['count'].'条记录/共'.$allpage.'页</div>';

        $goto = '';
        if($allpage > 1 && $show_goto == 1){
            $goto = '<div class="input-group" style="width:300px;float:right">
                        <input type="text" id="custom_pagesize" value="'.$param['pagesize'].'" class="form-control" style="text-align:center">
                        <span class="input-group-addon">条/页，跳至</span>
                        <input type="text" id="custom_goto_page" value="'.$param['p'].'" class="form-control" style="text-align:center">
                        <span class="input-group-addon">页</span>
                        <span class="input-group-btn">
                            <button class="btn blue" type="button" onclick="gopage(\''.url($base_url,$vars,false).'\',$(this))">Go</button>
                        </span>
                     </div>';
        }

        return $first . $middle . $last . $total . $goto;
    }elseif($param['page'] == 1){
        $total = '<div class="page-total">'.$param['count'].'条记录/共'.$allpage.'页</div>';
        return $total;
    }
}

function page_html_not_count($param){
    if(empty($param)) return '';
    $base_url   = request()->controller().'/'.request()->action();    //当前方法
    $vars       = request()->param();
    if(isset($vars['p'])) unset($vars['p']);

    $first  = '';
    $last   = '';

    $first = '<a class="btn-p page-s" href="' . url($base_url, array_merge($vars, array('p' => $param['p'] - 1))) . '">上一页</a>';
    $last  = '<a class="btn-p page-s" href="' . url($base_url, array_merge($vars, array('p' => $param['p'] + 1))) . '">下一页</a>';

    $goto = '<div class="input-group" style="width:300px;float:right">
                        <input type="text" id="custom_pagesize" value="'.$param['pagesize'].'" class="form-control" style="text-align:center">
                        <span class="input-group-addon">条/页，跳至</span>
                        <input type="text" id="custom_goto_page" value="'.$param['p'].'" class="form-control" style="text-align:center">
                        <span class="input-group-addon">页</span>
                        <span class="input-group-btn">
                            <button class="btn blue" type="button" onclick="gopage(\''.url($base_url,$vars,false).'\',$(this))">Go</button>
                        </span>
                     </div>';


        return $first . $last . $goto;

}

/**
 * 前端分页菜单
 * @param int $param['page'] 总页数
 * @param int $param['p']   当前页码
 * @param int $param['count'] 总记录数
 * @param int $param['pagesize'] 每页数量
 */
function category_menu($param){
    if (empty($param)){
        $param = [];
        $param['page'] = 1;
        $param['p'] = 1;
        $param['pagesize'] = 10;
        $param['count'] = 0;
    }
    /* 每页数量不为默认值10时，则需重新计算总页数 */
    $param['page'] = $param['pagesize'] != 10 ? ceil($param['count']/$param['pagesize']) : $param['page'];

    $html = '';
    $html .= '<div class="news_ye">';
    $html .= '        <a class="news_left" href="javascript:go('.( $param['p']-1 < 1 ? 1 : $param['p'] - 1 ).');"></a>';
    for( $i=1; $i <= $param['page']; $i++ ){
        if ( $param['p'] == $i ){
            $html .= '        <a class="a aa" href="javascript:go('.$i.');">'.$i.'</a>';
        }else{
            $html .= '        <a class="a" href="javascript:go('.$i.');">'.$i.'</a>';
        }
    }
    $html .= '        <a class="news_right" href="javascript:go('.( $param['p']+1 > $param['page'] ? $param['page'] : $param['p']+1).');"></a>';
    $html .= '    </div>';

    return $html;
}

/**
 * 图片缩略图
 * @param String $url 图片地址
 * @param integer $param['w']  	宽度
 * @param integer $param['h']	高度
 * @param integer $param['t']	剪裁类型 1(等比)|2(按尺寸)|3
 * @param integer $h 			等同于$param['h']
 * @param integer $t 			等同于$param['t']
 * @param string  $nopic 		当图片不存在时默认显示的图片
 * @param integer $type 			七牛的另一种缩略图方式
 */
function thumb($url,$param=null,$h='',$t=2,$nopic='',$type=''){
    $nopic = $nopic ? $nopic : '/images/nopic_200x200.png';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $scheme='https://';
    else $scheme='http://';

    if(is_array($url)){
        $tmp_url=$url[0]?$url[0]:$url[1];
        $url=$tmp_url;
    }

    if(isset($param) && !is_array($param)) {
        $cfg['w']=$param;
        $cfg['h']=$h;
        $cfg['t']=$t;
        $param=$cfg;
    }

    if(empty($url)) $url = isset($param['nopic']) && $param['nopic'] ? $param['nopic'] : $nopic;

    $tmp=parse_url($url);

    $param['t']=$param['t']?$param['t']:2;
    $param['h']=$param['h']?$param['h']:$param['w'];


    if(isset($tmp['scheme']) && ($tmp['scheme']=='http' || $tmp['scheme']=='https')){
        if($param['t'] && $param['w'] && $param['h']){
            if(strpos($tmp['host'],'.qiniucdn.com') || strpos($tmp['host'],'.clouddn.com') || strstr($tmp['host'],'pic.tangmall.net') || strstr($tmp['host'],'img.tangmall.net') || strstr($tmp['host'],'img.trj.cc')){
                //$url=$url.'?imageView2/'.$param['t'].'/w/'.$param['w'].'/h/'.$param['h'];
                if($type==1) $url=$url.'?imageView2/'.$param['t'].'/w/'.$param['w'].'/h/'.$param['h'];
                else $url=$url.'?imageMogr2/thumbnail/!'.$param['w'].'x'.$param['h'].'r/gravity/Center/crop/'.$param['w'].'x'.$param['h'];

                //?imageMogr2/thumbnail/!300x300r/gravity/Center/crop/300x300
            }elseif(strpos($tmp['host'],'dttx.com')){
                //return $url;
            }else{
                $url=$scheme.'work.'.config('url_domain_root').'/Thumb/index?src='.$url.'&w='.$param['w'].'&h='.$param['h'].'&zc='.$param['t'];
            }
        }

    }else{
        if($param['t'] && $param['w'] && $param['h']){
            $url = $scheme.'work.'.config('url_domain_root').'/Thumb/index?src='.$url.'&w='.$param['w'].'&h='.$param['h'].'&zc='.$param['t'];
        }
    }

    return $url;
}

/**
 * 图片输出
 * @param string $url 图片url
 * @param integer $width 图片宽度
 * @param integer $height 图片高度
 */
function imgwh($url,$width=80,$height='',$type=''){
    return '<a class="image-zoom" href="'.$url.'" title="大图"><img src="'.thumb($url,$width,$height,2,'',$type).'" alt="图片"></a>';
}

/**
 * 终端格式为图片输出
 * @param $val
 * @param int $w
 * @return string
 */
function terminal_conver($val,$w=100){
    $url = '/images/work/icon-t-'.$val.'.png';
    return imgwh($url,$w);
}

/**
 * 格式化为连接输出
 * @param $title
 * @param $url
 * @param string $target
 * @return string
 */
function href($title,$url,$target="_self"){
    return '<a href="'.$url.'" target="'.$target.'">'.$title.'</a>';
}

/**
 * 取无限级分类
 */
function get_category($param){
    $table = isset($param['table']) ? $param['table'] : '';                         //读取数据表
    if(empty($table)) return ['code' => 0,'msg' => '未设置要读取的数据表！'];

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

    if($depth > $max_depth) goto end;

    $upid   = isset($param[$upkey]) && $param[$upkey] > 0 ? $param[$upkey] : 0;     //父级ID
    $where[$upkey]  = $upid;
    //dump($where);
    $list   = db($table)->cache($cache,$cache_time)->where($where)->field($field)->order($order)->limit($limit)->select();
    if($list) {
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

            if($depth < $max_depth) $list[$key]['sublist'] = get_category($options);
        }
        return ['code' => 1, 'data' => $list,'count' => count($list),'depth' => $depth];
    }

    end:
    return ['code' => 3,'data' => [],'count' => 0,'depth' => $depth];
}

/**
 * 获取所有子级ID
 * @param $table
 * @param $id
 * @param array $where
 * @return array
 */
function sortid($table,$id,$where=[]){
    $ids[] = $id;
    //return $ids;
    $where['upid'] = $id;
    $list = db($table)->where($where)->field('id')->order('id asc')->select();
    if($list){
        foreach($list as $val){
            $ids[] = $val['id'];
            $tmp = sortid($table,$val['id'],$where);
            $ids = array_merge($ids,$tmp);
        }
    }

    $ids = array_unique($ids);
    return $ids;
}

/**
 * 生成select 的option选项，支持无限级，配合get_category使用
 * @param array     $data   选项数据
 * @param array     $field  option的value和text的字段键名
 * @param string    $value  默认值
 * @return string
 */
function create_option($data,$field,$value=''){
    $html = '';
    if($data['count'] > 0){
        foreach($data['data'] as $val){
            $selected = (string)$val[$field[0]] === $value ? ' selected' : '';
            $str = '';
            if($data['depth'] > 1){
                for($i=1;$i<$data['depth'];$i++){
                    $str .= '　';
                }
                $str .= '|— ';
            }
            $html .= '<option value="'.$val[$field[0]].'"'.$selected.'>'.$str.$val[$field[1]].'</option>';
            if(isset($val['sublist']) && $val['sublist']['count'] > 0) $html .= create_option($val['sublist'],$field,$value);
        }
    }
    return $html;
}

/**
 * 生成select 的option选项，只能是二级分类，配合get_category使用
 * @param array     $data   选项数据
 * @param array     $field  option的value和text的字段键名
 * @param string    $value  默认值
 */
function create_group_option($data,$field,$value=''){
    $html = '';
    if($data['count'] > 0){
        foreach($data['data'] as $val){
            $html .= '<optgroup label="'.$val[$field[1]].'">';
            if(isset($val['sublist']) && $val['sublist']['count'] > 0) {
                foreach($val['sublist']['data'] as $v){
                    $selected = (string)$v[$field[0]] === $value ? ' selected' : '';
                    $html .= '<option value="'.$v[$field[0]].'"'.$selected.'>'.$v[$field[1]].'</option>';
                }
            }
            $html .= '</optgroup>';
        }
    }
    return $html;
}

/**
 * 高并发下创建不重复流水号
 * @param string $prefix
 * @param string $uid
 * @return string
 */
function create_no($prefix='',$uid=''){
    if(empty($uid)) $uid = date('His');
    $str    = $prefix.session_id().microtime(true).uniqid(md5(microtime(true)),true);
    $str    = md5($str);
    $prefix = $prefix.date('YmdH').$uid;
    $code   = $prefix.substr(uniqid($str,true),-8,8);
    return $code;
}

/**
 * 高并发创建不重复key
 */
function create_uid($prefix=''){
    $str    = session_id().microtime(true).uniqid(md5(microtime(true)),true);
    $str    = $prefix . md5($str);
    return $str;
}

function create_uuid($prefix = ''){
    $str = md5(session_id().microtime(true).uniqid(mt_rand(), true));
    $uuid  = substr($str,0,8) . '-';
    $uuid .= substr($str,8,4) . '-';
    $uuid .= substr($str,12,4) . '-';
    $uuid .= substr($str,16,4) . '-';
    $uuid .= substr($str,20,12);
    return $prefix . $uuid;
}

/**
 * 生成树形菜单json
 * @param $res
 * @param array $data
 * @param string $field
 * @return array
 */
function tree($res,$data=[],$field='name'){
    if(!is_array($data)) $data = explode(',',$data);
    $tree = [];
    if($res['count'] > 0){
        foreach($res['data'] as $val){
            $tree[] = [
                'text'      => '<span class="hide" data-id="'.$val['id'].'"></span>'.$val[$field],
                'state'     => ['opened' => true,'selected' => in_array($val['id'],$data) && $val['sublist']['count'] == 0 ? true : false ],
                'children'  => $val['sublist']['count'] > 0 ? tree($val['sublist'],$data) : [],
            ];
        }
    }
    return $tree;
}

/**
 * 返回第一张图片
 * @param $str
 * @param int $w
 * @return string
 */
function first_images($str,$w=100){
    $url = '';
    if($str){
        $str = explode(',',$str);
        $url = $str[0];
    }
    return imgwh($url,$w);
}

/**
 * 接口参数写入前格式
 * @param string $field 要格式化的字段
 */
function api_params_format($field){
    $data   = [];
    $post   = input('post.');

    if(isset($post[$field.'_name']) && $post[$field.'_name']){
        foreach($post[$field.'_name'] as $key => $val){
            if($val) {
                $data[] = [
                    'name'      => $val,
                    'type'      => $post[$field.'_type'][$key] ? $post[$field.'_type'][$key] : 'string',
                    'need'      => $post[$field.'_need'][$key],
                    'example'   => $post[$field.'_example'][$key],
                    'desc'      => $post[$field.'_desc'][$key],
                ];
            }
        }
    }
    $data = serialize($data);
    return $data;
}

/**
 * 目录导航
 * @param string $table     是操作的表
 * @param int    $id        分类ID
 * @param string $field     读取字段
 * @return 如：大分类 > 小分类
 */
function nav_sort($table,$id,$field='',$key='category_name'){
    $nav = [];
    $where['id']    = $id;
    $field          = $field ? $field : 'id,upid,category_name';

    $cache_name     = md5('nav_sort' . serialize([$table,$id,$where,$field]));
    $cache_time     = 1800;
    $rs = db($table)->cache($cache_name,$cache_time)->where($where)->field($field)->find();

    if($rs){
        $nav[] = $rs[$key];
        if($rs['upid'] > 0) {
            $tmp = nav_sort($table,$rs['upid'],$field,$key);
            $nav = array_merge([$tmp],$nav);
        }
    }

    $nav = implode(' > ',$nav);
    return $nav;
}

/**
 * 返回当天为某月的第几周
 */
function day_w(){
    $day = intval(date('d'));
    if($day < 8) $w = 1;
    elseif($day < 15) $w = 2;
    elseif($day < 22) $w = 3;
    else $w = 4;
    return date('ym').$w;
}

/**
 * Mongodb 插入数据，主要用于写日志
 * @param $table    string  表名
 * @param $data     array   是写入的数据
 */
function mongo_insert($table,$data){
    return;
    /*
    $mongo = new \enhong\HMongodb();
    $mongo->selectDb(config('mongodb.database'));
    $mongo->insert($table,$data);
    */

    $do = Db::connect(config('mongodb'));
    $do->table(config('mongodb.prefix') . $table)->insert($data);
}

/**
 * 加密
 */
function str_encode($str){
    $str = \crypt\Crypt::encrypt($str,config('crypt_str'));
    return $str;
}

/**
 * 解密
 */
function str_decode($str){
    $str = \crypt\Crypt::decrypt($str,config('crypt_str'));
    return $str;
}

/**
 * 大唐C+密码加密方法
 */
function dttx_md5($str){
    return MD5(SHA1($str) . '@$^^&!##$$%%$%$$^&&asdtans2g234234HJU');
}

/**
 * 返回二维数组中的某字段集合
 * @param array     $list       二维数组
 * @param string    $id         要取的键名
 * @return array
 */
function get_ids($list,$key = 'id'){
    $ids = [];

    if(!is_array($key)) $key = [$key];

    foreach($list as $val){
        foreach($key as $v){
            if($val[$v] > 0) $ids[] = $val[$v];
        }
    }
    $ids = array_unique($ids);
    return $ids;
}

/**
 * 设置数据中某值作为下标
 * @param $list
 * @param string $key
 * @return array
 */
function set_key_value($list,$key='id'){
    $data = [];
    foreach($list as $val){
        $data[$val[$key]][] = $val;
    }
    return $data;
}

/**
 * Object To Array
 */
function objectToArray($data){
    if(is_object($data)) $data = (array)$data;
    if(is_array($data)){
        foreach($data as $key => $val){
            $data[$key] = objectToArray($val);
        }
    }
    return $data;
}

/**
 * number_format
 * 四舍六入五留双，用于资金计算
 */
function _number_format($number,$decimals=2){
    //return number_format($number,$decimals,'.','');

    $pow = pow(10,$decimals);
    if(  (floor($number * $pow * 10) % 5 == 0) && (floor( $number * $pow * 10) == $number * $pow * 10) && (floor($number * $pow) % 2 ==0) ){
        $result = floor($number * $pow)/$pow;
    }else{//四舍五入
        $result = round($number,$decimals);
    }

    return number_format($result,$decimals,'.','');
}

function number_formats($number,$decimals=2){
    return number_format($number,$decimals,'.','');
}

/**
 * 生成资金校验字符串
 */
function md5_crc($data,$key=[]){
    if(empty($key)) $key = ['integration','cash','lurpak','stock','imawards','devote_lurpak','project_mortgage','consume','mortgage_poundage','lock_cash','lock_lurpak','vip_lurpak'];
    $tmp = [];
    foreach($key as $val){
        if(isset($data[$val])) {
            $n = in_array($val,['cash','lock_cash']) ? 2 : 4;
            $tmp[$val] = number_formats($data[$val],$n);
        }
    }
    $tmp['id'] = $data['id'];

    ksort($tmp);
    $str = implode(',',$tmp).config('md5_crc');
    return md5(sha1($str));
}

/**
 * 生成商户资金签名
 */
function md5_crc_commercial($data,$key=[]){
    if(empty($key)) $key = ['cash','lurpak','lock_cash','lock_lurpak'];
    $tmp = [];
    foreach($key as $val){
        if(isset($data[$val])) {
            $n = in_array($val,['cash','lock_cash']) ? 2 : 4;
            $tmp[$val] = number_formats($data[$val],$n);
        }
    }
    $tmp['id'] = $data['id'];

    ksort($tmp);
    $str = implode(',',$tmp).config('md5_crc');
    return md5(sha1($str));
}

/**
 * 生成代理资金校验字符串
 */
function md5_crc_agent($data,$key=[]){
    if(empty($key)) $key = ['integration','cash','lurpak','stock','imawards','devote_lurpak','project_mortgage','consume','mortgage_poundage','lock_cash','lock_lurpak'];
    $tmp = [];
    foreach($key as $val){
        if(isset($data[$val])) {
            $n = in_array($val,['cash','lock_cash']) ? 2 : 4;
            $tmp[$val] = number_formats($data[$val],$n);
        }
    }
    $tmp['id'] = $data['id'];

    ksort($tmp);
    $str = implode(',',$tmp).config('md5_crc');
    return md5(sha1($str));
}

/**
 * 密码加密
 */
function md5_pwd($str,$prefix=''){
    $str = sha1($str);
    $str = $str . ($prefix ? $prefix : config('md5_pwd'));
    return md5($str);
}

/**
 * 缓存数据表数据
 * @param $table
 * @return mixed
 */
function cache_table($table,$cache_time=3600){
    $cache_table=[
        'area'                  => 'id,a_name',
        'help_category'         => 'id,category_name',
        'news_category'         => 'id,category_name',
        'admin_group'           => 'id,group_name',
        'department'            => 'id,department',
        'enterprise_type'       => 'id,typename',
        'industry_type'         => 'id,typename',
        'bank'                  => 'id,name',
        'trade_type'            => 'id,trade_name',
        'member_level'          => 'id,level_name',
        'agent_level'           => 'id,level_name',
        'agent_job'             => 'id,job',
        'payment_trade_type'    => 'id,trade_name',
    ];

    $list = cache('cache_table_'.$table);
    if(empty($list)){
        $list = db($table)->cache('cache_table_'.$table,$cache_time)->column($cache_table[$table],'id');
    }
    return $list;
}

/**
 * 短网址生成
 * Create by lazycat 整理
 * 2017-04-24
 */
function short_url($link){ //返回数组
    $result = sprintf("%u",crc32($link));
    $show = '';
    while($result  >0){
        $s = $result % 62;
        if($s > 35){
            $s=chr($s+61);
        }elseif($s>9 && $s<=35){
            $s=chr($s+55);
        }
        $show .= $s;
        $result = floor($result / 62);
    }

    return $show;
}

/**
 * 表单记录选择器，有值情况下的输出
 * @param $value
 * @param $options
 * @return string|void
 */
function select_record_value($value,$options){
    if(empty($value) || empty($options)) return;
    if(!isset($options['table']) || !isset($options['field'])) return;
    $tpl = 'selectrecord:'.(isset($options['tpl_item']) ? $options['tpl_item'] : 'tpl_item_'.$options['table']);

    $rs = db($options['table'])->where(['id' => $value])->field($options['field'])->find();
    if($rs){
        $do = new View();
        $do->assign('rs',$rs);
        $html = $do->fetch($tpl);
        return $html;
    }

    return;
}

/**
 * 店铺url
 * @param $id
 * @param $domain
 */
function shop_url($id,$domain=''){
    $domain = $domain ? $domain : $id;
    $url = '//' . $domain . '.' . config('url_domain_root');
    return $url;
}

/**
 * 发送短信
 * create by lazycat
 * 2017-08-08
 * @param string $mobile 手机号码，多个用逗号隔开，不可超过100个
 * @param strign $message 短信内容，不可超过60字，超过将自动分成多条短信发送
 */
function send_sms_bak($mobile,$message){    //旧的SMS，已停用
    $data = [
        'username'  => config('cfg.sms')['username'],
        'password'  => config('cfg.sms')['password'],
        'phonelist' => $mobile,
        'msg'       => $message,
        'longnum'   => '',
    ];

    mongo_insert('smscode',$data);

    $data = http_build_query($data);
    $apiurl = config('cfg.sms')['apiurl'];
    $res = curl_post($apiurl,$data);

    $xml = new \think\config\driver\Xml();
    $arr = $xml->parse($res);

    if(isset($arr[0])){
        $arr = explode('|',$arr[0]);
        return ['code' => 1,'msgid' => $arr[1]];
    }else{
        return ['code' => 0,'msg' => '发送失败！'];
    }
}

/**
 *发送手机短信,并返回数组
 * @param array $api  手机短信接口
 * @param array $param
 * @param string $param['mobile'] 	接收短信的手机号码，可以是数组或半角逗号隔开的多个手机号码
 * @param string $param['content'] 	要发送的内容，请注意内容不要太长，通常在60个字以内
 * @return bool
 */
function send_sms($mobile,$message,$config = []){
    if(empty($mobile) || empty($message)) return ['code' => 0,'msg' => '手机号码及短信内容必填！'];
    $cfg = [
        'userid'        => config('cfg.sms')['userid'],
        'account'       => config('cfg.sms')['account'],
        'password'      => config('cfg.sms')['password'],
        'action'        => 'send',
    ];
    if(!empty($config)) $cfg = array_merge($cfg,$config);
    $apiurl = config('cfg.sms')['apiurl'];

    $cfg['mobile']  = is_array($mobile) ? @implode(',',$mobile) : $mobile;
    $cfg['content'] = $message;

    if(substr($cfg['mobile'],0,6) == '144444'){
        $cfg['atime']   = date('Y-m-d H:i:s');
        $cfg['url']     = request()->url();
        $cfg['res']     = 1;
        mongo_insert('smscode',$cfg);
        return ['code' => 1,'msg' => '短信发送成功！'];
    }

    $rules = [
        'mobile'    => 'mobile',
    ];
    $message = [
        'mobile'    => '手机号码格式错误！',
    ];
    $validate = new \think\Validate($rules,$message);
    if(!$validate->check($cfg)) {
        return ['code' => 0,'msg' => $validate->getError()];
    }

    //if(!strpos($cfg['content'],config('cfg.sms')['autograph'])) $cfg['content'] .= config('cfg.sms')['autograph'];

    $res = curl_post($apiurl,$cfg);
    $xml = simplexml_load_string($res);
    //print_r($res);

    $cfg['atime']   = date('Y-m-d H:i:s');
    $cfg['url']     = request()->url();

    if($xml->returnstatus=='Success'){
        $cfg['res'] = 1;
        mongo_insert('smscode',$cfg);
        return ['code' => 1,'msg' => '短信发送成功！'];
    }else{
        $cfg['res'] = 0;
        mongo_insert('smscode',$cfg);
        return ['code' => 0,'msg' => '短信发送失败！'];;
    }
}

/**
 * 根据用户ID生成分享码
 * @param $userid
 * @return string
 */
function share_code($userid)
{
    /*
    static $sourceString = [
        0,1,2,3,4,5,6,7,8,9,10,
        'a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','q','r',
        's','t','u','v','w','x',
        'y','z'
    ];
    */

    static $sourceString = [
        1,2,3,4,5,6,7,8,9,
        'a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','p','q','r',
        's','t','u','v','w','x',
        'y','z'
    ];

    $num = $userid;
    $code = '';
    while($num)
    {
        $mod = $num % 33;
        $num = (int)($num / 33);
        $code = "{$sourceString[$mod]}{$code}";
    }

    //判断code的长度
    if(empty($code[4])) $code = str_pad($code,6,'0',STR_PAD_RIGHT);

    return $code;
}

/**
 * 支付方式图标
 */
function paytype_icon($type,$width = 60){
    $url = '/images/work/paytype_icon_'.$type.'.png';
    return imgwh($url,$width);
}

/**
 * 算法缓存
 */
function cache_suanfa(){
    $field = ['base','inventory','consume','sale','upgrade_level_2','upgrade_level_3','upgrade_level_4','upgrade_level_5','upgrade_level_6','agent_base'];

    $tmp    = [];
    $n      = 0;
    foreach($field as $val){
        $tmp[$val] = cache('suanfa_'.$val);
        if(empty($tmp[$val])) $n++;
    }

    //存在缓存失效的配置
    if($n > 0){
        $rs = db('config_suanfa')->where(['id' => 1])->field('id,create_time,update_time,is_lock',true)->find();
        foreach ($rs as $key => $val) {
            if ($val) {
                $tmp[$key] = unserialize(html_entity_decode($val));
                cache('suanfa_'.$key,$tmp[$key]);
            }
        }
    }

    return $tmp;
}

/**
 * 生成子域名
 */
function domain($sub = 'www',$url = ''){
    $domain = request()->scheme().'://'.$sub.'.'.config('url_domain_root').$url;
    return $domain;
}

/**
 * 验证邮箱格式
 */

function isValidEmail($email) {
    $email = strtolower($email);
    if (!preg_match("/[^@]{1,64}@[^@]{1,255}/", $email)) {
        return false;
    }

    $email_array = explode("@", $email);

    $local_array = explode(".", $email_array[0]);

    $length = sizeof($local_array);
    for($i = 0; $i < $length; $i++){
        if (!preg_match("@^[a-z0-9_~-][a-z0-9_~.-]{0,63}$@", $local_array[$i])) {
            return false;
        }
    }
    unset($length);
    if(!preg_match("@^[?[0-9.] ]?$@", $email_array[1])){
        $domain_array = explode(".", $email_array[1]);
        $length = sizeof($domain_array);
        if($length < 2){
            return false;
        }
        for($i = 0; $i < $length; $i++) {
            if (!preg_match("/^(([a-z0-9][a-z0-9-]{0,61}[a-z0-9])|([a-z0-9] ))$/", $domain_array[$i])) {
                return false;
            }
        }
        unset($length);
    }
    return true;
}


/**
 * 生成分页ajax html
 * @param int $param['page'] 总页数
 * @param int $param['p']   当前页码
 * @param int $param['count'] 总记录数
 * @param int $param['pagesize'] 每页数量
 */
function page_ajax_html($param){
    if(empty($param)) return '';
    $allpage = $param['page'];
    if(isset($param['max']) && $param['max'] < $param['page']) $param['page'] =  $param['max'];
    $vars       = request()->param();
    if(isset($vars['p'])) unset($vars['p']);

    if($param['page'] > 1) {
        $first = '<strong class="ye_right" '.($param['p'] != $param['page'] ? ' onclick="last();" ' : '') . '></strong>';
        $first .= '<div class="ye_">';
        $first .= '<a class="btn-p page-no ' . ($param['p'] == 1 ? 'ye_btn' : '') . '" ' . ($param['p'] != 1 ? ' href="javascript:pages(1);"' : '') . '>1</a>';
        $last = '<a class="' . ($param['p'] == $param['page'] ? 'ye_btn' : '') . '" ' . ($param['p'] != $param['page'] ? ' href="javascript:pages('.$param['page'].');"' : '') . '>' . $param['page'] . '</a>';
        $last .= '</div>';

        $last .= '<strong class="ye_left" '.($param['p'] != 1 ? ' onclick="first();" ' : '') . '></strong>';




        $page_num = [];
        if ($param['page'] < 9) {
            for ($i = 2; $i < $param['page']; $i++) {
                $page_num[] = $i;
            }
        } elseif ($param['p'] >= 6 && $param['p'] + 2 < $param['page']) {
            $page_num = [
                '',
                $param['p'] - 2,
                $param['p'] - 1,
                $param['p'],
                $param['p'] + 1,
                $param['p'] + 2,
                ''
            ];
        } elseif ($param['p'] <= 5 && $param['page'] >= 8) {
            for ($i = 2; $i <= 7; $i++) {
                $page_num[] = $i;
            }
            $page_num[] = '';
        } elseif ($param['page'] - $param['p'] <= 4) {
            $page_num[] = '';
            for ($i = $param['page'] - 7; $i < $param['page']; $i++) {
                $page_num[] = $i;
            }
        }

        $middle = '';

        foreach ($page_num as $val) {
            if ($val == '') $middle .= '<span>...</span>';
            else $middle .= '<a class="' . ($param['p'] == $val ? 'ye_btn' : '') . '" ' . ($param['p'] != $val ? ' href="javascript:pages('.$val.');"' : '') . '>' . $val . '</a>';
        }

        $middle .= '';

        //$total = '<div class="page-total">'.$param['count'].'条记录/共'.$allpage.'页</div>';

        $goto = '';
        if($allpage > 1){
            $goto = '<div class="ye_main">
                <a href="javascript:page();" class="go">GO</a>
                <div class="ye">
                    跳转至:
                    <input type="text" id="pages" value="'.$param['p'].'" maxlength="4">
                    页
                </div>
                <input type="hidden" id="page_count" value="'.$param['page'].'"/><input type="hidden" id="count" value="'.$param['count'].'"/><input type="hidden" id="p" value="'.$param['p'].'"/>';
        }

        return $goto . $first . $middle . $last ;
    }elseif($param['page'] == 1){
        $total = '<div class="page-total">'.$param['count'].'条记录/共'.$allpage.'页</div>';
        return $total;
    }
}

/**
 * 验证手机格式
 */
function is_phone($value){
    return (preg_match('/13[0-9]\d{8}|14[0-9]\d{8}|15[0-9]\d{8}|17[0-9]\d{8}|18[0-9]\d{8}/', $value) && is_numeric($value));
}

/**
 * 数组清除指定值的
 */
function array_clean($arr,$remove){
    if(!is_array($remove)) $remove = [$remove];
    foreach($remove as $val){
        $key = array_search($val,$arr);
        if($key !== false) unset($arr[$key]);
    }

    $arr = array_values($arr);
    return $arr;
}

/**
 * 记录雇员操作相关SQL
 */
function sql_write($sql,$table='admin_sql'){
    $arr = ['insert','update','delete'];
    if(session('admin')){
        $action = strtolower(substr($sql,0,6));
        if(in_array($action,$arr)) {
            $data['atime']              = date('Y-m-d H:i:s');
            $data['sql_action']         = $action;
            $data['url']                = request()->url();
            $data['ip']                 = request()->ip();
            $data['employee_id']        = session('admin.id');
            $data['employee_account']   = session('admin.account');
            $data['employee_name']      = session('admin.name');
            $data['sql'] = $sql;
            //db($table)->insert($data);
            mongo_insert($table,$data);
        }
    }
}

/**
 * @desc arraySort php二维数组排序 按照指定的key 对数组进行排序
 * @param array $arr 将要排序的数组
 * @param string $keys 指定排序的key
 * @param string $type 排序类型 asc | desc
 * @return array
 */
function arraySort($arr, $keys, $type = 'asc') {
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v){
        $keysvalue[$k] = $v[$keys];
    }
    $type == 'asc' ? asort($keysvalue) : arsort($keysvalue);
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}

/**
 * 交易类型
 */
function trade_type($type=''){
    $where['upid']      = 0;
    $where['status']    = 1;
    if($type!='') $where['id'] = ['in',$type];

    $list = db('trade_type')->cache(true,60)->where($where)->field('id,trade_name')->select();
    if($list) {
        foreach ($list as $key => $val) {
            $tmp = db('trade_type')->cache(true,60)->where(['status' => 1])->where('concat(",",upid,",") like "%,'.$val['id'].',%"')->field('id,trade_name')->select();
            if($tmp){
                $list[$key]['sublist'] = ['code' => 1,'data' => $tmp,'count' => count($tmp),'depth' => 2];
            }
        }

        return ['code' => 1,'data' => $list,'count' => count($list),'depth' => 1];
    }

    return ['code' => 3];
}

/**
 * 截取头尾字段串，中间用*号隐藏
 */
function hide_substr($str,$first=2,$end=4,$icon='*'){
    $str = \think\helper\Str::substr($str,0,$first) . (str_pad($icon,strlen($str) - ($first + $end),$icon)). \think\helper\Str::substr($str,$end * -1,$end);
    return $str;
}