<?php
/**
 * 状态码对照表
 * Created by lazycat
 * 2017-08-07
 */

namespace enhong;
class Status
{
    /**
     * 会员类型
     */
    const USER_TYPE_PERSON      = 0;
    const USER_TYPE_ENTERPRISE  = 1;

    const USER_TYPE_INFO    = [
        self::USER_TYPE_PERSON      => '个人会员',
        self::USER_TYPE_ENTERPRISE  => '企业会员',
    ];

    /**
     * 会员级别
     */
    const USER_LEVEL_1  = 1;
    const USER_LEVEL_2  = 2;
    const USER_LEVEL_3  = 3;
    const USER_LEVEL_4  = 4;
    const USER_LEVEL_5  = 5;

    const USER_LEVEL_INFO = [
        self::USER_LEVEL_1  => '消费商',
        self::USER_LEVEL_2  => '盛客',
        self::USER_LEVEL_3  => '盛投',
        self::USER_LEVEL_4  => '尊享账户',
        self::USER_LEVEL_5  => '代理',
    ];

    /**
     * 会员状态
     */
    const USER_STATUS_ON    = 1;
    const USER_STATUS_LOCK  = 0;

    const USER_STATUS_INFO  = [
        self::USER_STATUS_ON    => '正常',
        self::USER_STATUS_LOCK  => '冻结',
    ];

    /**
     * 认证状态
     */
    const USER_AUDIT_NO         = 0;
    const USER_AUDIT_SUCCESS    = 1;
    const USER_AUDIT_FAIL       = 2;

    const USER_AUDIT_INFO   = [
        self::USER_AUDIT_NO         => '待审核',
        self::USER_AUDIT_SUCCESS    => '已认证',
        self::USER_AUDIT_FAIL       => '认证失败',

    ];

    const AUDIT_NO      = 0;
    const AUDIT_SUCCESS = 1;
    const AUDIT_FAIL    = 2;
    const AUDIT_WAIT    = 3;

    const AUDIT_INFO    = [
        self::AUDIT_NO      => '未认证',
        self::AUDIT_SUCCESS => '已认证',
        self::AUDIT_FAIL    => '认证失败',
        self::AUDIT_WAIT    => '待审核',
    ];

    /**
     * 是、否
     */
    const XOR_YES   = 1;
    const XOR_NO    = 0;

    const XOR_INFO  = [
        self::XOR_NO    => '否',
        self::XOR_YES   => '是',
    ];

    /**
     * 证件类型
     */
    const CERT_TYPE_ID          = 0;
    const CERT_TYPE_PASSPORT    = 1;
    const CERT_TYPE_STUDEN      = 2;
    const CERT_TYPE_SOLDIER     = 3;

    const CERT_TYPE_INFO    = [
        self::CERT_TYPE_ID          => '身份证',
        self::CERT_TYPE_PASSPORT    => '护照',
        self::CERT_TYPE_STUDEN      => '学生',
        self::CERT_TYPE_SOLDIER     => '军人',
    ];

    /**
     * 组织机构类型
     */
    const STRU_TYPE_INFO = [
        1   => '企业',
        2   => '事业单位',
        3   => '机关',
        4   => '社会团体',
        5   => '民办非企业单位',
        6   => '基金会',
        7   => '居委会',
        8   => '村委会',
        9   => '其他组织机构',
    ];

    /**
     * 是否分公司
     */
    const BRANCH_OFFICE_NO      = 0;
    const BRANCH_OFFICE_YES     = 1;

    const BRANCH_OFFICE_INFO    = [
        self::BRANCH_OFFICE_NO  => '总公司',
        self::BRANCH_OFFICE_YES => '分公司',
    ];


    /**
     * 学历
     */
    const EDU_TYPE_INFO = [
        1   => '小学',
        2   => '初中',
        3   => '高中',
        4   => '中专',
        5   => '大专',
        6   => '本科',
        7   => '硕士研究生',
        8   => '博士',
        9   => '博士后',
        10  => '教授',
    ];

    /**
     * 性别
     */
    const SEX_MAN       = 1;
    const SEX_WOMEN     = 2;
    const SEX_SECRECY   = 0;

    const SEX_INFO  = [
        self::SEX_MAN       => '男',
        self::SEX_WOMEN     => '女',
        self::SEX_SECRECY   => '保密',
    ];


    /**
     * 政治面貌
     * 0群众,1中共党员,2中共预备党员,3共青团员,4民革党员,5 民盟盟员,6民建会员,
     * 7民进会员,8农工党党员,9 致公党党员,10九三学社社员,11 台盟盟员,12 无党派人士
     */
    const POLITICAL_TYPE_INFO = [
        0       => '群众',
        1       => '中共党员',
        2       => '中共预备党员',
        3       => '共青团员',
        4       => '民革党员',
        5       => '民盟盟员',
        6       => '民建会员',
        7       => '民进会员',
        8       => '农工党党员',
        9       => '致公党党员',
        10      => '九三学社社员',
        11      => '台盟盟员',
        12      => '无党派人士',
    ];

    /**
     * 银行卡绑定人类型
     */
    const BANK_BIND_TYPE = [
        1       => '企业名称',
        2       => '法人',
        3       => '经营授权人',
    ];

    /**
     * 资金流向
     */
    const FLOW_DIRECTION_INFO = [
        0   => '转入',
        1   => '转出',
    ];

    /**
     * 资金账户类型
     */
    const CASE_ACCOUNT_INFO = [
        0   => '资金账户',
        1   => '积分账户',
        2   => '现金账户',
        3   => '云积分账户',
        4   => '库存积分账户',
        5   => '代理统一管理奖账户存',
        6   => '拉升云积分价值贡献账户',
        7   => '大项目抵押金账户',
        8   => '消费云积分账户',
        9   => '提现手续费抵押金账户',
        10  => '云积分提成账户',
    ];

    /**
     * 资金账户状态
     */
    const CASH_ACCOUNT_STATUS_TEXT_INFO = [
        'status'                        => '资金账户状态',
        'is_integration_freeze'         => '积分账户状态',
        'is_cash_freeze'                => '现金账户状态',
        'is_lurpak_freeze'              => '云积分账户状态',
        'is_stock_freeze'               => '库存积分账户状态',
        'is_imawards_freeze'            => '代理统一管理奖账户存状态',
        'is_devote_lurpak_freeze'       => '拉升云积分价值贡献账户状态',
        'is_project_mortgage_freeze'    => '大项目抵押金账户状态',
        'is_consume_freeze'             => '消费云积分账户状态',
        'is_mortgage_poundage_freeze'   => '提现手续费抵押金账户状态',
        'is_vip_lurpak_freeze'          => '推荐尊享会员现金提成账户状态',
    ];

    /**
     * 代理职位
     */
    const AGENT_POSITION_INFO = [
        1   => '普通代理',
        2   => '会员拓展、市场运营、联盟商家等职能部长',
        3   => '架构师、秘书长、财务总监',
        4   => '总经理、副总经理',
        5   => '董事长',
        6   => '总部（或上级公司）投资的独立监理',
    ];

    /**
     * 充值状态
     */
    const RECHARGE_STATUS_INFO = [
        0   => '未付款',
        1   => '已到账',
        2   => '处理中',
        3   => '已关闭',
    ];

    /**
     * 支付方式
     */
    const PAYTYPE_INFO = [
        0   => '未知',
        1   => '余额账户',
        2   => '云积分账户',
        3   => '消费账户',
        4   => '微信',
        5   => '支付宝',
        6   => '网银',
        20  => '线下转账',
    ];

    /**
     * 提现状态
     */
    const WITHDRAW_STATUS_INFO = [
        1   => '已结算',
        2   => '未结算',
        3   => '在途',
        4   => '驳回',
    ];

    /**
     * 提现周期
     */
    const WITHDRAW_CYCLE_INFO = [
        1   => 'T+0',
        2   => 'T+1',
        3   => 'T+3',
        4   => 'T+7',
    ];

    /**
     * 线下转账审核状态
     */
    const BANK_TRANSFER_STATUS_INFO = [
        1   => '已确认',
        2   => '待审核',
        3   => '被驳回',
        4   => '关闭',
    ];

    /**
     * 库存积分分发调额审核状态
     */
    const STOCK_DISTRIBUTE_STATUS_INFO = [
        1   => '已分发',
        2   => '待审核',
        3   => '被驳回',
    ];

    /**
     * 调额类型
     */
    const APPLICATION_TYPE_INFO = [
        1   => '单笔限额',
        2   => '月限额',
    ];

    /**
     * 购买代理订单状态
     */
    const AGENT_ORDER_STATUS_INFO = [
        1   => '预定',
        2   => '通过审核待付款',
        3   => '驳回',
        4   => '已付款待确认',
        5   => '购买成功',
        6   => '未收到款项',
        7   => '已转出',
        8   => '已转入',
        20  => '关闭',
    ];

    /**
     * 代理转让订单状态
     */
    const AGENT_TRANSFER_STATUS_INFO = [
        0   => '待审核',
        1   => '审核通过待付手续费',
        2   => '驳回',
        3   => '已付款，转让成功',
        20  => '关闭',
    ];

    /**
     * 牌匾订单状态
     */
    const PLAQUE_ORDER_STATUS_INFO = [
        0   => '删除',
        1   => '已拍下',
        2   => '已付款',
        3   => '已发货',
        20  => '关闭',
    ];

    /**
     * 购买库存积分订单状态
     */
    const STOCK_ORDER_STATUS_INFO = [
        0       => '删除',
        1       => '未付款',
        2       => '已付款',
        20      => '已关闭',
    ];

    /**
     * 会员升级订单状态
     */
    const MEMBER_UPGRADE_ORDER_STATUS_INFO = [
        0       => '删除',
        1       => '已拍下',
        2       => '已付款',
        20      => '已关闭',
    ];

    /**
     * 流水号前缀
     */
    const ORDERS_PREFIX = [
        'RE'    => 'RE',    //充值订单
        'CA'    => 'CA',    //现金流水
        'IN'    => 'IN',    //积分流水
        'LU'    => 'LU',    //云积分流水
        'IV'    => 'IV',    //库存积分流水
        'CL'    => 'CL',    //消费账户流水
        'DL'    => 'DL',    //拉升云积分流水
        'MP'    => 'MP',    //提现抵消账户流水
        'IM'    => 'IM',    //统一管理奖流水
        'PM'    => 'PM',    //大项目抵押流水
        'VL'    => 'VL',    //VIP会员升级提成云积分账户
        'LC'    => 'LC',    //现金冻结账户流水
        'LP'    => 'LP',    //云积分冻结账户流水
        'UP'    => 'UP',    //会员升级订单
        'SC'    => 'SC',    //购买库存积分订单
        'SD'    => 'SD',    //库存积分分发订单
        'PA'    => 'PA',    //支付平台订单
        'WH'    => 'WH',    //现金提现订单
        'WL'    => 'WL',    //云积分提现订单
        'BT'    => 'BT',    //线下转账订单
        'AL'    => 'AL',    //联盟商家购买牌扁订单
        'LO'    => 'LO',    //云积分转账单号
        'CTCA'  => 'CTCA',  //商户现金流水
        'CTLU'  => 'CTLU',  //商户云积分流水
        'CTLC'  => 'CTLC',  //商户现金冻结流水
        'CTLP'  => 'CTLP',  //商户云积分冻结流水
        'AP'    => 'AP',    //调额单号
        'AG'    => 'AG',    //代理订单
        'AT'    => 'AT',    //代理转让订单
        'AC'    => 'AC',    //代理公司编号

        'AIN'    => 'AIN',    //代理积分流水
        'ALU'    => 'ALU',    //代理云积分流水
        'AIV'    => 'AIV',    //代理库存积分流水
        'ACL'    => 'ACL',    //代理消费账户流水
        'ADL'    => 'ADL',    //代理拉升云积分流水
        'AMP'    => 'AMP',    //代理提现抵消账户流水
        'AIM'    => 'AIM',    //代理统一管理奖流水
        'APM'    => 'APM',    //代理大项目抵押流水
        'AVL'    => 'AVL',    //代理VIP会员升级提成云积分账户
        'ALC'    => 'ALC',    //代理现金冻结账户流水
        'ALP'    => 'ALP',    //代理云积分冻结账户流水

    ];

}
