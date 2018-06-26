<?php
/**
 * 资金账户
 * Create by Lazycat
 * 2017-08-24
 */
namespace enhong;
use enhong\Status;
use think\Exception;
use think\Db;

class CheckAccount
{
    private static $sw;  //记录事务执行结果
    //private static $logData;    //日志信息

    /**
     * 检测用户资金账户状态
     * @param $account
     * @param string $check_sub_account
     * @return array|mixed
     */
    public static function checkAccount($account,$check_sub_account = ''){
        $logData = [
            'account'           => $account,
            'check_sub_account' => $check_sub_account,
        ];

        if(empty($account)) return self::ret(['code' => 0,'msg' => '资金账户不存在！'],1,$logData);

        $sub_account = [];
        //如果是系统账号时直接跳过检测
        if(config('cfg.sys')['userid'] == $account['id']) {
            $code = 1;
            $msg    = '用户资金账户状态正常！';
            if(!empty($check_sub_account)){
                if(!is_array($check_sub_account)) $check_sub_account = [$check_sub_account];
                foreach ($check_sub_account as $val){
                    $sub_account[$val] = ['code' => 1,'msg' => '子账户状态正常！'];
                }
            }
            return self::ret(['code' => $code,'sub_account' => $sub_account,'msg' => $msg],0,$logData);
        }

        $md5_crc = md5_crc($account);
        if($account['crc'] !== $md5_crc && $account['status'] == 1){  //存在异常，直接冻结
            Db::startTrans();
            try{
                if(self::$sw[] = false === db('correlation_data')->where(['id' => $account['id']])->setField('status',0)){
                    throw new Exception('设置账户冻结状态失败！');
                }

                $data = [
                    'userid'        => $account['id'],
                    'employee_id'   => 1,   //系统雇员ID
                    'type'          => 0,
                    'reason'        => '【系统检测】 资金账户存在异常',
                ];

                if(!self::$sw[] = model('app\api\model\AccountLog')->validate('app\api\validate\AccountLog.add')->save($data)){
                    echo ('创建资金账户冻结日志失败！('.model('app\api\model\AccountLog')->getError().')');
                }

                Db::commit();
            }catch (Exception $e){
                $msg = $e->getMessage();
                Db::rollback();
                return self::ret(['code' => 0,'msg' => $msg],1,$logData);
            }

            return self::ret(['code' => 0,'msg' => '资金账户存在异常！'],1,$logData);
        }

        if($account['status'] == 1) {
            $code = 1;
            $msg    = '资金账户状态正常！';
        }else{
            $code = 0;
            $msg    = '资金账户存在异常！（账户状态冻结）';
        }


        //检测子账号状态
        if($check_sub_account != ''){
            if(!is_array($check_sub_account)) $check_sub_account = [$check_sub_account];
            $sub_code = 0;
            foreach ($check_sub_account as $val){
                $sub_account[$val] = self::checkSubAccount($account,$val);
                if($sub_account[$val]['code'] != 1) $sub_code++;
            }

            if($sub_code >0) {
                $code = 0;
                $msg .= '（子账户存在异常）';
            }
        }

        return self::ret(['code' => $code,'sub_account' => $sub_account,'msg' => $msg],0,$logData);

    }

    /**
     * 检查子账户状态
     * @param $account
     * @param $sub_account
     * @return array
     */
    public static function checkSubAccount($account,$sub_account){
        switch($sub_account){
            case 'integration':
                if($account['is_integration_freeze'] != 1) return ['code' => 0,'msg' => '积分账户存在异常，已冻结！'];
                break;
            case 'cash':
                if($account['is_cash_freeze'] != 1) return ['code' => 0,'msg' => '现金账户存在异常，已冻结！'];
                break;
            case 'lurpak':
                if($account['is_lurpak_freeze'] != 1) return ['code' => 0,'msg' => '云积分账户存在异常，已冻结！'];
                break;
            case 'stock':
                if($account['is_stock_freeze'] != 1) return ['code' => 0,'msg' => '库存积分账户存在异常，已冻结！'];
                break;
            case 'imawards':
                if($account['is_imawards_freeze'] != 1) return ['code' => 0,'msg' => '代理统一管理奖账户存在异常，已冻结！'];
                break;
            case 'devote_lurpak':
                if($account['is_devote_lurpak_freeze'] != 1) return ['code' => 0,'msg' => '拉升云积分价值贡献账户存在异常，已冻结！'];
                break;
            case 'project_mortgage':
                if($account['is_project_mortgage_freeze'] != 1) return ['code' => 0,'msg' => '大项目抵押金账户存在异常，已冻结！'];
                break;
            case 'consume':
                if($account['is_consume_freeze'] != 1) return ['code' => 0,'msg' => '消费云积分账户存在异常，已冻结！'];
                break;
            case 'mortgage_poundage':
                if($account['is_mortgage_poundage_freeze'] != 1) return ['code' => 0,'msg' => '提现手续费抵押金账户存在异常，已冻结！'];
                break;
            case 'vip_lurpak':
                if($account['is_vip_lurpak_freeze'] != 1) return ['code' => 0,'msg' => 'VIP升级提成账户存在异常，已冻结！'];
                break;
        }

        return ['code' => 1,'msg' => '子账号状态正常'];
    }

    public static function ret($data,$is_log = 1,$logData = '',$logTable = 'sys_check_account_'){
        if($is_log == 1) {
            $tmp = $data;
            $tmp['atime']   = date('Y-m-d H:i:s');
            $tmp['url']     = request()->url();
            if(!empty($logData)) $tmp['argv'] = var_export($logData,true);
            mongo_insert($logTable . date('ym'),$tmp);
        }
        return $data;
    }

    /**
     * 检测商户资金账户状态
     * @param $account
     * @param string $check_sub_account
     * @return array
     */
    public static function checkCommercial($account,$check_sub_account = ''){
        $logData = [
            'account'           => $account,
            'check_sub_account' => $check_sub_account,
        ];

        if(empty($account)) return self::ret(['code' => 0,'msg' => '资金账户不存在！'],1,$logData,'sys_check_commercial_');

        $sub_account = [];
        //如果是系统商户时直接跳过检测
        if(config('cfg.sys')['commercial_tenant_id'] == $account['id']) {
            $code = 1;
            $msg    = '商户资金账户状态正常！';
            if(!empty($check_sub_account)){
                if(!is_array($check_sub_account)) $check_sub_account = [$check_sub_account];
                foreach ($check_sub_account as $val){
                    $sub_account[$val] = ['code' => 1,'msg' => '子账户状态正常！'];
                }
            }
            return self::ret(['code' => $code,'sub_account' => $sub_account,'msg' => $msg],0,$logData,'sys_check_commercial_');
        }

        $md5_crc = md5_crc_commercial($account);
        if($account['crc'] !== $md5_crc && $account['status'] == 1){  //存在异常，直接冻结
            Db::startTrans();
            try{
                if(self::$sw[] = false === db('commercial_tenant')->where(['id' => $account['id']])->setField('status',0)){
                    throw new Exception('设置支付商户冻结状态失败！');
                }

                $data = [
                    'commercial_tenant_id'  => $account['id'],
                    'employee_id'           => 1,   //系统雇员ID
                    'type'                  => 0,
                    'reason'                => '【系统检测】 支付商户资金账户存在异常',
                ];

                if(!self::$sw[] = model('app\api\model\CommercialTenantAccountLog')->validate('app\api\validate\CommercialTenantAccountLog.add')->save($data)){
                    echo ('创建支付商户资金账户冻结日志失败！('.model('app\api\model\CommercialTenantAccountLog')->getError().')');
                }

                Db::commit();
            }catch (Exception $e){
                $msg = $e->getMessage();
                Db::rollback();
                return self::ret(['code' => 0,'msg' => $msg],1,$logData,'sys_check_commercial_');
            }

            return self::ret(['code' => 0,'msg' => '支付商户资金账户存在异常！'],1,$logData,'sys_check_commercial_');
        }

        if($account['status'] == 1) {
            $code = 1;
            $msg    = '商户资金账户状态正常！';
        }else{
            $code = 0;
            $msg    = '商户资金账户存在异常！（账户状态冻结）';
        }

        //检测子账号状态
        if($check_sub_account != ''){
            if(!is_array($check_sub_account)) $check_sub_account = [$check_sub_account];
            $sub_code = 0;
            foreach ($check_sub_account as $val){
                $sub_account[$val] = self::checkSubAccountCommercial($account,$val);
                if($sub_account[$val]['code'] != 1) $sub_code++;
            }

            if($sub_code >0) {
                $code = 0;
                $msg .= '（子账户存在异常）';
            }
        }

        return self::ret(['code' => $code,'sub_account' => $sub_account,'msg' => $msg],0,$logData,'sys_check_commercial_');
    }

    /**
     * 检查子账户状态
     * @param $account
     * @param $sub_account
     * @return array
     */
    public static function checkSubAccountCommercial($account,$sub_account){
        switch($sub_account){
            case 'cash':
                if($account['is_cash_freeze'] != 1) return ['code' => 0,'msg' => '支付商户现金账户存在异常，已冻结！'];
                break;
            case 'lurpak':
                if($account['is_lurpak_freeze'] != 1) return ['code' => 0,'msg' => '支付商户云积分账户存在异常，已冻结！'];
                break;
        }

        return ['code' => 1,'msg' => '支付商户子账号状态正常'];
    }



    /**
     * 检测代理资金账户状态
     * @param $account
     * @param string $check_sub_account
     * @return array
     */
    public static function checkAgentAccount($account,$check_sub_account = ''){
        $logData = [
            'account'           => $account,
            'check_sub_account' => $check_sub_account,
        ];

        if(empty($account)) return self::ret(['code' => 0,'msg' => '资金账户不存在！'],1,$logData,'sys_check_agent_account_');

        $sub_account = [];

        $md5_crc = md5_crc_agent($account);
        if($account['crc'] !== $md5_crc && $account['status'] == 1){  //存在异常，直接冻结
            Db::startTrans();
            try{
                if(self::$sw[] = false === db('agent_account')->where(['id' => $account['id']])->setField('status',0)){
                    throw new Exception('设置支付商户冻结状态失败！');
                }

                $data = [
                    'agentid'  				=> $account['id'],
                    'employee_id'           => 1,   //系统雇员ID
                    'type'                  => 0,
                    'reason'                => '【系统检测】 代理资金账户存在异常',
                ];

                if(!self::$sw[] = model('app\api\model\AgentAccountLog')->validate('app\api\validate\AgentAccountLog.add')->save($data)){
                    echo ('创建支付代理资金账户冻结日志失败！('.model('app\api\model\AgentAccountLog')->getError().')');
                }

                Db::commit();
            }catch (Exception $e){
                $msg = $e->getMessage();
                Db::rollback();
                return self::ret(['code' => 0,'msg' => $msg],1,$logData,'sys_check_agent_account_');
            }

            return self::ret(['code' => 0,'msg' => '支付代理资金账户存在异常！'],1,$logData,'sys_check_agent_account_');
        }

        if($account['status'] == 1) {
            $code = 1;
            $msg    = '代理资金账户状态正常！';
        }else{
            $code = 0;
            $msg    = '代理资金账户存在异常！（账户状态冻结）';
        }

        //检测子账号状态
        if($check_sub_account != ''){
            if(!is_array($check_sub_account)) $check_sub_account = [$check_sub_account];
            $sub_code = 0;
            foreach ($check_sub_account as $val){
                $sub_account[$val] = self::checkSubAgentAccount($account,$val);
                if($sub_account[$val]['code'] != 1) $sub_code++;
            }

            if($sub_code >0) {
                $code = 0;
                $msg .= '（子账户存在异常）';
            }
        }

        return self::ret(['code' => $code,'sub_account' => $sub_account,'msg' => $msg],0,$logData,'sys_check_agent_account_');
    }

    /**
     * 检查子账户状态
     * @param $account
     * @param $sub_account
     * @return array
     */
    public static function checkSubAgentAccount($account,$sub_account){
        switch($sub_account){
            case 'integration':
                if($account['is_integration_freeze'] != 1) return ['code' => 0,'msg' => '积分账户存在异常，已冻结！'];
                break;
            case 'cash':
                if($account['is_cash_freeze'] != 1) return ['code' => 0,'msg' => '现金账户存在异常，已冻结！'];
                break;
            case 'lurpak':
                if($account['is_lurpak_freeze'] != 1) return ['code' => 0,'msg' => '云积分账户存在异常，已冻结！'];
                break;
            case 'stock':
                if($account['is_stock_freeze'] != 1) return ['code' => 0,'msg' => '库存积分账户存在异常，已冻结！'];
                break;
            case 'imawards':
                if($account['is_imawards_freeze'] != 1) return ['code' => 0,'msg' => '代理统一管理奖账户存在异常，已冻结！'];
                break;
            case 'devote_lurpak':
                if($account['is_devote_lurpak_freeze'] != 1) return ['code' => 0,'msg' => '拉升云积分价值贡献账户存在异常，已冻结！'];
                break;
            case 'project_mortgage':
                if($account['is_project_mortgage_freeze'] != 1) return ['code' => 0,'msg' => '大项目抵押金账户存在异常，已冻结！'];
                break;
            case 'consume':
                if($account['is_consume_freeze'] != 1) return ['code' => 0,'msg' => '消费云积分账户存在异常，已冻结！'];
                break;
            case 'mortgage_poundage':
                if($account['is_mortgage_poundage_freeze'] != 1) return ['code' => 0,'msg' => '提现手续费抵押金账户存在异常，已冻结！'];
                break;
            case 'vip_lurpak':
                if($account['is_vip_lurpak_freeze'] != 1) return ['code' => 0,'msg' => 'VIP升级提成账户存在异常，已冻结！'];
                break;
        }

        return ['code' => 1,'msg' => '代理子账号状态正常'];
    }

    /**
     * 检查支付方式
     */
    public static function payType($id,$value = ''){
        $rs = db('payment_type')->cache(true,60)->where(['id' => $id])->field('status,type_name,top_limit,lower_limit')->find();

        if($rs){
            if($rs['status'] != 1) return ['code' => 0,'msg' => '【'.$rs['type_name'].'】支付方式已停用！'];
            if($value != '' && ($value < $rs['lower_limit'] || $value > $rs['top_limit'])){
               return ['code' => 0,'msg' => '单笔交易金额不得低于'.$rs['lower_limit'].'元，且不得高于'.$rs['top_limit'].'元！'];
            }

            return ['code' => 1,'msg' => '支付方式正常！'];
        }

        return ['code' => 0,'msg' => '支付方式不存在！'];
    }

    /**
     * 检测是否包含禁用用关键词
     * @param $str string 要检测的字符串
     * @param $type int     检测类型，1=用户昵称，2=联盟商家店铺名
     */
    public static function checkDisabledKeyword($str,$type = 1){
        $where['status']    = 1;
        if($type == 1) $where['category_id'] = ['in','0,1'];
        else $where['category_id'] = ['in','0,2'];
        $list = db('disabled_keyword')->cache(true,1800)->where($where)->field('keyword')->select();

        if($list){
            $word = [];
            foreach($list as $val){
                $word = array_merge($word,explode(',',$val['keyword']));
            }

            foreach($word as $val){
                if(trim($val)) {
                    if (preg_match('/' . trim($val) . '/', $str)) {
                        return ['code' => 0, 'msg' => '含有禁用关键词【' . $val . '】！'];
                        break;
                    }
                }
            }
        }

        return ['code' => 1,'msg' => '字符串合法！'];
    }
}

