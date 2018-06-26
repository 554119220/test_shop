<?php
namespace app\work\controller;
use app\work\controller\Common;
class Index extends Common
{
    public function index()
    {
        $res = api('Menu/menu',['openid' => session('admin.openid')]);
        $this->assign('menu',$res['data']);

        return view();
    }

    /**
     * 数据统计
     *
     * @return \think\response\View
     */
    public function main(){
        /* 待处理数据 */

        $data   = db('statistics')->cache(true)->order('statistics_id desc')->find();


        /*
        $rs = api('statistics/NoAudit',['openid' => session('admin.openid')]);
        if(!$rs){
            $rs['data']['noAuditPerson'] = '-';
            $rs['data']['noAuditEnterprise'] = '-';
            $rs['data']['noAuditAlliance'] = '-';
            $rs['data']['noAuditApplication'] = '-';
            $rs['data']['noDealWorkOrder'] = '-';
            $rs['data']['buyAgent'] = '-';
            $rs['data']['agentBankcard'] = '-';
            $rs['data']['agentWithdrawLurpak'] = '-';
            $rs['data']['agentWithdrawCash'] = '-';
            $rs['data']['bankTransfer'] = '-';
            $rs['data']['withdrawCash'] = '-';
            $rs['data']['withdrawLurpak'] = '-';
            $rs['data']['noAuditStockDistribut'] = '-';
        }
        $this->assign($rs['data']);
*/
        return view('', ['data' => $data]);
    }

    public function noPower(){
        return view();
    }

    public function noPowerAjax(){
        return ['code' => 0,'msg' => '没有权限！'];
    }

}
