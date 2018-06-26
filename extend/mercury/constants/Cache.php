<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/28 0028
 * Time: 15:43
 */

namespace mercury\constants;

/**
 * 缓存命名
 *
 * Class Cache
 * @package app\common\extra
 */
class Cache
{
    //缓存名称前缀
    const CACHE_NAME_PREFIX = 'zr:';
    //zr:user
    //zr:user:id:1
    //百望，用户表，id为1的列，差异为抽奖
    //zr:table:user:id:1:diff:lottery
    //keys(zr:user)
    //keys(zr:user:id)
    //前缀，表名，字段名，字段值,其他值
    //name expire
    //表级别的缓存，如配置信息
    //行级别的缓存，如单个用户的信息
    
    // 用户注册手机验证码
    const REGISTER_MOBILE_CODE          = 'user:register:code:mobile:';
    // 用户注册 是否已发手机验证码
    const REGISTER_MOBILE_IS_SEND_CODE  = 'user:register:mobile:code:is:send:';
    // 用户登录 错误密码记录次数
    const LOGIN_PASSWORD_ERROR_TIMES    = 'user:login:password:error:times:';
    // 用户登录 冻结账号
    const LOGIN_ACCOUNT_FREEZE          = 'user:login:account:freeze:';
    // 用户登录记住密码
    const LOGIN_ACCOUNT_REMEMBER        = 'user:login:account:remember:';

    #   地区缓存
    const DISTRICT_LIST_CACHE           = 'table:area:upid:';
    #   地区缓存
    const DISTRICT_ROW_CACHE            = 'table:area:id:';
    #   同级地区缓存
    const DISTRICT_LEVEL_CACHE            = 'table:area:levelcity:id:';
    # 通知模板 - 验证码
    const USER_MOBILE_CODE              = 'notice_tpl:mobile:code:';
    # 通知模板 - 短信
    const USER_MOBILE_SMS               = 'notice_tpl:mobile:sms:';
	# 商品分类 - 列表
	const GOODS_CATEGORY_LIST           = 'table:goods_category:list';
    # 店铺类型 - 列表
    const SHOP_TYPE_LIST           = 'table:shop_type:list';
    # 店铺类型 - 详情
    const SHOP_TYPE_DETAIL           = 'table:shop_type:shop_type_id:';
    # 店铺文章 - 详情
    const SHOP_ARTICLE_DETAIL           = 'table:shop_article:shop_article_id:';
    # 店铺后台配置缓存 - 列表
    const SHOP_CONFIG_LIST           = 'table:config:list';
    # 店铺类型资质 - 列表
    const SHOP_QUALIFCATIONS_LIST           = 'table:shop_qualifications:list:shop_type_id:';
    # 店铺类型行业资质 - 列表
    const SHOP_QUALIFCATIONS_TYPE_LIST           = 'table:shop_qualifications:type_list:shop_type_id:';
    # 店铺类型行业资质 - 列表
    const SHOP_QUALIFCATIONS_INDEX_LIST           = 'table:shop_qualifications:index_list:shop_type_id:';
    # 店铺类型会员资质 - 列表
    const SHOP_QUALIFCATIONS_USER_LIST           = 'table:shop_qualifications:user_list:shop_type_id:';
    # 禁用关键词详情
    const DISABLED_KEYWORD_DETAIL              = 'table:disabled_keyword:id:';
    # 列出所有快递公司
	const EXPRESS_COMPANY               = 'table:express_company';
	# 按组取出所有快递公司
	const EXPRESS_COMPANY_GROUP         = 'table:express_company:diff:group';
	#   发货通知
    const NOTICE_ORDERS_SHIP            = 'orders:notice:ship:';
    #   用户浏览历史记录
    const USER_BROWSE_HISTORY           = 'user:browse:history:';
	#省市缓存列表
	const PROVINCE_CITY_LIST			= 'province:city:list';
	#省市缓存列表2
	const PROVINCE_CITY_LIST_NEW		= 'province:city:list:new';
    #   支付方式
    const PAY_TYPE_ALL                  = 'table:pay_type';
    #   支付方式详情
    const PAY_TYPE_DETAIL               = 'table:pay_type:id:';
    #   搜索历史
    const SEARCH_HISTORY                = 'search:history:';
    #   搜索历史by user
    const SEARCH_HISTORY_BY_USER        = 'search:history:user:';
    #   搜索关键词
    const SEARCH_KEYWORDS               = 'search:keywords:';
	# erp api token
	const ERP_API_TOKEN					= 'erp:api:token:';
	# erp oauth 记录
	const ERP_API_OAUTH					= 'erp:api:oauth:';
    # erp付款失败
	const ERP_API_PAY_FAILS             = 'erp:api:pay:fail';
	const ERP_API_REFUND_FAILS          = 'erp:api:refund:fail';
	#   配置信息
    const CONFIG                        = 'table:config';
    #   配置信息子项
    const CONFIG_TYPE                   = 'table:config:type:';

    #   notice
    const NOTICE_MAX_EMAIL              = 'notice:max:email:';
    const NOTICE_MAX_SMS                = 'notice:max:sms:';
	
	# 文章
	const ARTICLE_CATEGORY_LIST 		= 'table:article_category:list';

	#   禁用关键词
	const DISABLED_KEYWORDS             = 'table:disabled_keyword';
	const DISABLED_KEYWORDS_ROW         = 'table:disabled_keyword:id:';
	
	# 商品
	const GOODS_SKU_DETAIL				= 'table:goods_sku:detail:id:';
	const GOODS_SKU_LIST				= 'table:goods_sku:list:goods_id:';
	const GOODS_SKU_GROUP_LIST			= 'table:goods_sku_group:list:goods_id:';

    # 商品精选
    const GOODS_APPLY_RECOMMEND1_NUMS   = 'table:goods_apply_recommend1:nums:user_id:';
	# 商品优选
    const GOODS_APPLY_RECOMMEND_NUMS   	= 'table:goods_apply_recommend:nums:user_id:';
	
    #   队列数据持久
    const QUEUE_PERSISTENCE_ROW         = 'mercury:queue:job:';

    #   是否正在付款
    const IS_PINGING                    = 'tools:is_paying:no:';
    #   是否在收银台，如果在的话则不能修改订单金额
    const IS_IN_ERP_PAY                 = 'tools:is_in_erp_pay:';

    #   不可重复提交
    const SUBMIT_REPEAT_CHECK           = 'tools:submit:repeat:data:';

    #   收货地址地区
    const TOOLS_DISTRICT_ADDRESS        = 'tools:district:address';

    #   access token
    const TOOLS_ACCESS_TOKEN            = 'tools:accesstoken:appid:';

    #   频道
    const CHANNEL_ALL                   = 'table:channel';
    const CHANNEL_ROW                   = 'table:channel:id:';

    const WECHAT_ACCESS_TOKEN           = 'tools:wechat:access_token';
    const WECHAT_TICKET                 = 'tools:wechat:ticket';

    #   购物积分
    const TOOLS_SHOPPING_SCORE_RATIO    = 'tools:shopping:score:ratio';
}

