<?php
/**
 * 百度Echarts
 */
namespace enhong;
class Echarts
{
    /**
     * 生成人脉图表数据
     */
    public function renmai($userid){
        if(empty($userid)) exit;

        $cache_name = 'renmai_'.$userid;
        $result = cache($cache_name);
        if($result) return $result;

        $rs = db('member')->where(['id' => $userid])->field('id,account as name')->find();
        $rs['children'] = $this->downUser($rs['id']);
        $data = array($rs);

        $option['title'] = array('text'=>'关系图');
        $option['toolbox'] = array(
            'show'			=> true,
            'feature'		=> array(
                'mark'			=> array('show'=> true),
                'dataView'		=> array('show'=> true,'readOnly' => false),
                'restore'		=> array('show' => true),
                'saveAsImage'	=> array('show' => true)
            ),
        );

        $option['series'] = array(
            array(
                'name'			=> '人脉',
                'type'			=> 'tree',
                'orient'		=> 'horizontal',  // vertical horizontal
                'rootLocation'	=> array('x' => 100,'y' => 230), // 根节点位置  {x: 100, y: 'center'}
                'nodePadding'	=> 18,
                'layerPadding'	=> 200,
                'hoverable'		=> false,
                'roam'			=> true,
                'symbolSize'	=> 16,
                'itemStyle'		=> array(
                    'normal'	=> array(
                        'color'		=> '#4883b4',
                        'label'		=> array(
                            'show'		=> true,
                            'position'	=> 'right',
                            'formatter'	=> '"{b}"',
                            'textStyle'	=> array(
                                'color'		=> '#000',
                                'fontSize'	=> 12,
                            ),
                        ),
                        'lineStyle'		=> array(
                            'color'		=> '#ccc',
                            'type'		=> 'curve' 	// 'curve'|'broken'|'solid'|'dotted'|'dashed'
                        ),
                    ),
                    'emphasis'	=> array(
                        'color'	=> '#4883b4',
                        'label'	=> array(
                            'show' => false,
                        ),
                        'borderWidth' => 0
                    ),
                ),
                'data'	=> $data,
            ),
        );
        $result = 'var option='.json_encode($option,JSON_UNESCAPED_UNICODE).';';
        cache($cache_name,$result,3600);

        return $result;
    }

    public function downUser($userid){
        $list = db('member')->where(['promo_userid' => $userid])->field('id,account as name')->order(['id' => 'asc'])->select();
        if($list) {
            foreach ($list as $key => $val) {
                $list[$key]['children'] = $this->downUser($val['id']);
            }
        }
        return $list;
    }


    //图表，最近七天统计记录
    public function echart_total(){
        exit;
        $do=M('totals');
        $list=$do->order('day desc')->limit(7)->select();

        foreach($list as $val){
            $day[]=$val['day'];
            $member[]=$val['member'];
            $products[]=$val['products'];
            $store[]=$val['store'];
        }

        $option['title']=array('text'=>'最近七天统计');
        $option['tooltip']=array('trigger'=>'axis');
        $option['legend']=array('data'=>array('新增会员','新增商品','新增店铺'));
        $option['toolbox']=array(
            'show'			=>true,
            'feature'		=>array(
                'mark'			=>array('show'=>true),
                'dataView'		=>array('show'=>true,'readOnly'=>false),
                'magicType'		=>array('show'=>true,'type'=>array('line','bar')),
                'restore'		=>array('show'=>true),
                'saveAsImage'	=>array('show'=>true)
            ),
        );

        $option['calculable']=true;
        $option['xAxis']=array(array(
            'type'	=>'category',
            'data'	=>$day
        ));

        $option['yAxis']=array(array('type'=>'value'));

        $option['series']=array(
            array(
                'name'	=>'新增会员',
                'type'	=>'bar',
                'data'	=>$member,
            ),
            array(
                'name'	=>'新增商品',
                'type'	=>'bar',
                'data'	=>$products,
            ),
            array(
                'name'	=>'新增店铺',
                'type'	=>'bar',
                'data'	=>$store,
            ),
        );

        echo 'var option='.json_encode($option,JSON_UNESCAPED_UNICODE).';';
    }

    public function echart_money(){
        exit;
        $do=M('totals');
        $list=$do->order('day desc')->limit(7)->select();

        foreach($list as $val){
            $day[]=$val['day'];
            $buy[]=$val['buy_money'];
            $sale[]=$val['sale_money'];
            $refund[]=$val['refund_money'];
            $itemrefund[]=$val['itemrefund_money'];
            $support[]=$val['support_money'];
            $ad[]=$val['ad_money'];
            $xiaobao_join[]=$val['xiaobao_join'];
            $xiaobao_quit[]=$val['xiaobao_quit'];
        }

        $option['title']=array('text'=>'最近七天交易统计');
        $option['tooltip']=array('trigger'=>'axis');
            $option['legend']=array('data'=>array('新增付款','确认收货','订单退款','宝贝退款','售后退款','广告投放','加入消保','退出消保'));
        $option['toolbox']=array(
            'show'			=>true,
            'feature'		=>array(
                'mark'			=>array('show'=>true),
                'dataView'		=>array('show'=>true,'readOnly'=>false),
                'magicType'		=>array('show'=>true,'type'=>array('line','bar')),
                'restore'		=>array('show'=>true),
                'saveAsImage'	=>array('show'=>true)
            ),
        );
        $option['grid']=array('left'=>'3%','right'=>'4%','bottom'=>'3%','containLabel'=>true);
        $option['calculable']=true;
        $option['xAxis']=array(array(
            'type'	=>'category',
            'boundaryGap'=>false,
            'data'	=>$day
        ));

        $option['yAxis']=array(array('type'=>'value'));

        $option['series']=array(
            array(
                'name'	=>'新增付款',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$buy,
            ),
            array(
                'name'	=>'确认收货',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$sale,
            ),
            array(
                'name'	=>'订单退款',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$refund,
            ),
            array(
                'name'	=>'宝贝退款',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$itemrefund,
            ),
            array(
                'name'	=>'售后退款',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$support,
            ),
            array(
                'name'	=>'广告投放',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$ad,
            ),
            array(
                'name'	=>'加入消保',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$xiaobao_join,
            ),
            array(
                'name'	=>'退出消保',
                'type'	=>'line',
                'stack'	=>'总量',
                'areaStyle'=>array('normal'=>array()),
                'data'	=>$xiaobao_quit,
            ),

        );

        echo 'var option='.json_encode($option,JSON_UNESCAPED_UNICODE).';';

    }

    /**
     * 生成云积分最近7天兑换
     */
    public function Silver_rates($data){
        $datas = array();
        if(!empty($data['data'])){
            $datas = '[';
            foreach($data['data'] as $val){
                $datas .='["'.$val['day'].'",'.$val['lurpak_to_cash_ratio'].'],';
            }
            $datas .= ']';
        }
        return $datas;
    }

}

