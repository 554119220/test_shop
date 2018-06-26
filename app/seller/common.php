<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/01/10
 * Time: 15:06
 */
/**
 * 域名
 *
 * @param $domain
 * @param string $path
 * @return string
 */
if (!function_exists('domain')) {
    function domain($domain, $path = '') {
        return \app\common\traits\F::domain($domain, $path);
    }
}

/**
 * 重新组装数据店铺资质数据
 * @param array $arr
 * @param array $arr2
 * @return array
 */
function again_values($arr = [] , $arr2 = [],$arr_key='industry_images',$arr_key2='shop_qualifications_id'){
    if(!empty($arr) && !empty($arr2)){
        foreach ($arr2 as $k=>$v){
            foreach ($arr as $key=>$value){
                if($v[$arr_key2] == $value['id']){
                    $arr2[$k]['images'] = $value[$arr_key] ?? '';
                }
            }
        }
    }
    return $arr2;
}

/**
 * 重新组装数据店铺资质数据
 * @param array $arr
 * @param array $arr2
 * @return array
 */
function again_values2($arr = [] , $arr2 = []){

    if(!empty($arr) && !empty($arr2)){
        foreach ($arr2 as $k=>$v){
            foreach ($arr as $key=>$value){
                if($v['shop_qualifications_id'] == $value['id']){
                    $arr2[$k]['images'] = $value['images'] ?? [];
                }
            }
        }
    }
    return $arr2;
}