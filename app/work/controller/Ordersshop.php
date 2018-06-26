<?php
/**
 * 此文件由表单生成器创建
 * day:{day}
 */
namespace app\work\controller;
use app\common\traits\F;
use app\work\controller\Commonmodules;
use mercury\async\Beanstalkd;
use mercury\constants\State;
use think\Exception;

class Ordersshop extends Commonmodules
{
    public function _initialize()
    {
        parent::_initialize();
        $this->formtpl_id   = 648;  //表单模板ID
        $this->module_name  = '订单管理';   //模块名称
        $this->initForm();

    }

    public function index(){
        $map    = array_filter(input());
        if (isset($map['custom_between_day'])) {
            if (isset($map['custom_sday'])) {
                $custom_sday    = strtotime($map['custom_sday']);
                unset($map['custom_sday']);
            } else {
                $custom_sday    = strtotime('2018-01-01');
            }
            if (isset($map['custom_eday'])) {
                $custom_eday    = strtotime($map['custom_eday']);
                unset($map['custom_eday']);
            } else {
                $custom_eday    = time();
            }
            $map[$map['custom_between_day']]  = ['between', "{$custom_sday},{$custom_eday}"];
            unset($map['custom_between_day']);
        }
        $res = $this->_index();
        $this->assign('res',$res);
        $btns   = 'return "<a class=\'btn blue btn-outline btn-block md10\' href=\'/ordersshop/edit/orders_shop_id/$val[orders_shop_id]\'>修改</a>
<div data-id=\'$val[orders_shop_id]\' class=\'btn red btn-outline btn-block md10\' onclick=\'extra_tr_view($(this))\'>详情</div>
<div data-id=\'$val[orders_shop_no]\' class=\'btn green btn-outline btn-block\' onclick=\'notify($(this))\'>ERP收货</div>";';
        $btns   = [$btns];
        $html = html_table($res['data']['list'],$this->formtpl['list_fields'],$btns,1,$this->formtpl['data_conver']);
        $this->assign('html_table',$html['html']);

        $this->_searchFields(); //搜索表单

        return view();
    }

    /**
     * 批量删除
     */
    public function deleteSelect(){
        $res = $this->_deleteSelect();
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
        $res = $this->_edit_save();
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
        return $res;
    }
    /**
     * 转移目录
     */
    public function change2Category(){
        $res = $this->_change2Category();
        return $res;
    }

    /**
     * 订单详情
     *
     * @return \think\response\View
     */
    public function detail()
    {
        $model  = F::apiModel('OrdersShop', 'orders');
        $id     = intval(input('id'));
        $data   = [];
        if ($id > 0) {
            $data   = F::dataDetail($model, [
                'where'     => ['orders_shop_id' => $id],
                'relation'  => 'goods,logs,ordersAddress'
            ]);
        }
        if (!empty($data)) {
            $data['seller'] = F::dataDetail(F::apiModel('User', 'user'), $data['seller_user_id']);
            $data['user'] = F::dataDetail(F::apiModel('User', 'user'), $data['buyer_user_id']);
            $data['shop'] = F::dataDetail(F::apiModel('Shop', 'goods'), $data['shop_id']);
        }
        return view('', ['data' => $data]);
    }

    public function notify()
    {
        try {
            $no = input('shop_no');
            $user_id  = db('orders_shop')->where(['orders_shop_no' => $no, 'orders_shop_state' => ['in', '4,5']])->value('buyer_user_id');
            if (!$user_id) throw new Exception('订单不存在或状态不正确');
            $data   = [
                'order_no'  => $no,
                'openid'    => db('user')->where(['user_id' => $user_id])->value('openid'),
                'safe_psw'  => '',
                'is_auto'   => State::STATE_NORMAL
            ];
            $flag   = Beanstalkd::getInstance('erp_receive')
                ->put($data);
            if (!$flag) throw new Exception('入列失败');
            return [
                'code'  => State::STATE_NORMAL,
                'msg'   => '操作成功'
            ];
        } catch (Exception $e) {
            return [
                'code'  => $e->getCode(),
                'msg'   => $e->getMessage()
            ];
        }
    }
}
